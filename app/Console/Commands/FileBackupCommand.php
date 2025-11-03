<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use ZipArchive;

class FileBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'backup:files {--type=daily : Type of backup (daily, weekly, monthly)}';

    /**
     * The console command description.
     */
    protected $description = 'Create file backup for PLN Kantor Management System';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Starting file backup...');
        
        try {
            $type = $this->option('type');
            
            // Create backup filename with timestamp
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $backupName = "pln_files_{$type}_{$timestamp}.zip";
            
            // Create backup directory if not exists
            $backupDir = storage_path('app/backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $backupPath = "{$backupDir}/{$backupName}";
            
            // Create ZIP archive
            $zip = new ZipArchive();
            if ($zip->open($backupPath, ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Cannot create ZIP file: ' . $backupPath);
            }
            
            // Directories to backup
            $backupPaths = [
                'storage/app/public' => 'storage',
                'public/uploads' => 'uploads',
                'resources/views' => 'views',
                'config' => 'config',
                'database/migrations' => 'migrations',
                'database/seeders' => 'seeders',
                '.env' => '.env'
            ];
            
            $totalFiles = 0;
            
            foreach ($backupPaths as $sourcePath => $zipPath) {
                $fullPath = base_path($sourcePath);
                
                if (file_exists($fullPath)) {
                    if (is_dir($fullPath)) {
                        $totalFiles += $this->addDirectoryToZip($zip, $fullPath, $zipPath);
                        $this->info("📁 Added directory: {$sourcePath}");
                    } else {
                        $zip->addFile($fullPath, $zipPath);
                        $totalFiles++;
                        $this->info("📄 Added file: {$sourcePath}");
                    }
                } else {
                    $this->warn("⚠️ Path not found: {$sourcePath}");
                }
            }
            
            // Close ZIP file
            $zip->close();
            
            // Verify backup file exists and has content
            if (!file_exists($backupPath) || filesize($backupPath) === 0) {
                throw new \Exception('Backup file is empty or does not exist');
            }
            
            $fileSize = $this->formatBytes(filesize($backupPath));
            $this->info("✅ File backup created successfully!");
            $this->info("📁 File: {$backupName}");
            $this->info("📊 Size: {$fileSize}");
            $this->info("📈 Total files: {$totalFiles}");
            
            // Upload to cloud storage if configured
            if (config('filesystems.disks.s3.key')) {
                $this->info('☁️ Uploading to cloud storage...');
                $cloudPath = "backups/files/{$backupName}";
                Storage::disk('s3')->put($cloudPath, file_get_contents($backupPath));
                $this->info("✅ Backup uploaded to cloud: {$cloudPath}");
            }
            
            // Log backup success
            Log::info('File backup completed successfully', [
                'type' => $type,
                'file' => $backupName,
                'size' => filesize($backupPath),
                'total_files' => $totalFiles,
                'cloud_uploaded' => config('filesystems.disks.s3.key') ? true : false
            ]);
            
            // Clean up old backups
            $this->cleanupOldBackups($type);
            
            $this->info('🎉 File backup process completed!');
            
        } catch (\Exception $e) {
            $this->error('❌ File backup failed: ' . $e->getMessage());
            Log::error('File backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Add directory to ZIP recursively
     */
    private function addDirectoryToZip($zip, $sourcePath, $zipPath, &$fileCount = 0)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourcePath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($iterator as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = $zipPath . '/' . substr($filePath, strlen($sourcePath) + 1);
                
                // Skip certain files
                if ($this->shouldSkipFile($filePath)) {
                    continue;
                }
                
                $zip->addFile($filePath, $relativePath);
                $fileCount++;
            }
        }
        
        return $fileCount;
    }
    
    /**
     * Check if file should be skipped
     */
    private function shouldSkipFile($filePath)
    {
        $skipPatterns = [
            '.git',
            '.DS_Store',
            'Thumbs.db',
            '.tmp',
            '.log'
        ];
        
        foreach ($skipPatterns as $pattern) {
            if (strpos($filePath, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Clean up old backup files
     */
    private function cleanupOldBackups($type)
    {
        $this->info('🧹 Cleaning up old file backups...');
        
        $retention = [
            'daily' => 7,    // Keep 7 days
            'weekly' => 4,   // Keep 4 weeks  
            'monthly' => 12  // Keep 12 months
        ];
        
        $keepDays = $retention[$type] ?? 7;
        $cutoffDate = Carbon::now()->subDays($keepDays);
        
        $backupDir = storage_path('app/backups');
        $files = glob("{$backupDir}/pln_files_{$type}_*.zip");
        
        $deletedCount = 0;
        foreach ($files as $file) {
            $fileTime = filemtime($file);
            if ($fileTime < $cutoffDate->timestamp) {
                unlink($file);
                $deletedCount++;
            }
        }
        
        if ($deletedCount > 0) {
            $this->info("🗑️ Deleted {$deletedCount} old file backup files");
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