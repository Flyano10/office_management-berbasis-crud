<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DatabaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'backup:database {--type=daily : Type of backup (daily, weekly, monthly)} {--compress : Compress backup file}';

    /**
     * The console command description.
     */
    protected $description = 'Create database backup for PLN Kantor Management System';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting database backup...');
        
        try {
            $type = $this->option('type');
            $compress = $this->option('compress');
            
            // Get database configuration
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            
            // Create backup filename with timestamp
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $backupName = "pln_backup_{$type}_{$timestamp}";
            $backupFile = $backupName . '.sql';
            
            // Create backup directory if not exists
            $backupDir = storage_path('app/backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $backupPath = "{$backupDir}/{$backupFile}";
            
            // Create mysqldump command
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s --port=%s --single-transaction --routines --triggers %s > %s',
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbName),
                escapeshellarg($backupPath)
            );
            
            // Execute backup command
            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);
            
            if ($returnVar !== 0) {
                throw new \Exception('Mysqldump failed with return code: ' . $returnVar);
            }
            
            // Compress if requested
            if ($compress) {
                $this->info('📦 Compressing backup...');
                $compressedFile = $backupPath . '.gz';
                exec("gzip {$backupPath}", $output, $returnVar);
                
                if ($returnVar === 0) {
                    $backupPath = $compressedFile;
                    $backupFile = $backupFile . '.gz';
                }
            }
            
            // Verify backup file exists and has content
            if (!file_exists($backupPath) || filesize($backupPath) === 0) {
                throw new \Exception('Backup file is empty or does not exist');
            }
            
            $fileSize = $this->formatBytes(filesize($backupPath));
            $this->info("✅ Database backup created successfully!");
            $this->info("📁 File: {$backupFile}");
            $this->info("📊 Size: {$fileSize}");
            
            // Upload to cloud storage if configured
            if (config('filesystems.disks.s3.key')) {
                $this->info('☁️ Uploading to cloud storage...');
                $cloudPath = "backups/database/{$backupFile}";
                Storage::disk('s3')->put($cloudPath, file_get_contents($backupPath));
                $this->info("✅ Backup uploaded to cloud: {$cloudPath}");
            }
            
            // Log backup success
            Log::info('Database backup completed successfully', [
                'type' => $type,
                'file' => $backupFile,
                'size' => filesize($backupPath),
                'compressed' => $compress,
                'cloud_uploaded' => config('filesystems.disks.s3.key') ? true : false
            ]);
            
            // Clean up old backups based on type
            $this->cleanupOldBackups($type);
            
            $this->info('🎉 Backup process completed!');
            
        } catch (\Exception $e) {
            $this->error('❌ Backup failed: ' . $e->getMessage());
            Log::error('Database backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Clean up old backup files
     */
    private function cleanupOldBackups($type)
    {
        $this->info('🧹 Cleaning up old backups...');
        
        $retention = [
            'daily' => 7,    // Keep 7 days
            'weekly' => 4,   // Keep 4 weeks  
            'monthly' => 12  // Keep 12 months
        ];
        
        $keepDays = $retention[$type] ?? 7;
        $cutoffDate = Carbon::now()->subDays($keepDays);
        
        $backupDir = storage_path('app/backups');
        $files = glob("{$backupDir}/pln_backup_{$type}_*.sql*");
        
        $deletedCount = 0;
        foreach ($files as $file) {
            $fileTime = filemtime($file);
            if ($fileTime < $cutoffDate->timestamp) {
                unlink($file);
                $deletedCount++;
            }
        }
        
        if ($deletedCount > 0) {
            $this->info("🗑️ Deleted {$deletedCount} old backup files");
        }
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size >= 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }
}