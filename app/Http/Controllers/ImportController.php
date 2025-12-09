<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Kantor;
use App\Models\Gedung;
use App\Models\Lantai;
use App\Models\Ruang;
use App\Models\Bidang;
use App\Models\SubBidang;
use App\Models\Okupansi;
use App\Models\Kontrak;
use App\Models\Realisasi;
use App\Models\Inventaris;
use App\Models\KategoriInventaris;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    /**
     * Show Data Management Center
     */
    public function index()
    {
        return view('import.index');
    }

    /**
     * Export data to CSV or Excel
     */
    public function exportData(Request $request)
    {
        try {
            $request->validate([
                'model' => 'required|string|in:kantor,gedung,lantai,ruang,bidang,sub_bidang,okupansi,kontrak,realisasi,inventaris',
                'format' => 'required|string|in:csv,excel'
            ]);

            $model = $request->model;
            $format = $request->format;
            
            // Get data from model
            $data = $this->getModelData($model);
            
            if ($format === 'csv') {
                return $this->exportCsv($data, $model);
            } else {
                return $this->exportExcel($data, $model);
            }

        } catch (\Exception $e) {
            Log::error('Export error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create database backup
     */
    public function createBackup(Request $request)
    {
        try {
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $storagePath = storage_path('app/backups');
            
            // Create directory if not exists
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }
            
            $filepath = $storagePath . '/' . $filename;
            
            // Get database credentials
            $dbHost = config('database.connections.mysql.host');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            
            // Try to find mysqldump in Laragon path (Windows)
            $mysqldumpPath = 'mysqldump';
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Check common Laragon paths
                $possiblePaths = [
                    'C:\\laragon\\bin\\mysql\\mysql-8.0.30\\bin\\mysqldump.exe',
                    'C:\\laragon\\bin\\mysql\\mysql-5.7.24\\bin\\mysqldump.exe',
                    'mysqldump.exe'
                ];
                
                foreach ($possiblePaths as $path) {
                    if (file_exists($path)) {
                        $mysqldumpPath = '"' . $path . '"';
                        break;
                    }
                }
            }
            
            // Create mysqldump command
            $command = sprintf(
                '%s --host=%s --user=%s --password=%s --no-tablespaces %s > "%s" 2>&1',
                $mysqldumpPath,
                escapeshellarg($dbHost),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                $filepath
            );
            
            // Execute backup
            exec($command, $output, $returnVar);
            
            // Check if backup file was created and has content
            if (file_exists($filepath) && filesize($filepath) > 0) {
                Log::info('Database backup created', [
                    'filename' => $filename,
                    'size' => filesize($filepath),
                    'user' => auth('admin')->user()->nama_admin ?? 'Unknown'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Backup created successfully',
                    'filename' => $filename,
                    'size' => $this->formatBytes(filesize($filepath)),
                    'download_url' => route('data-management.download-backup', ['filename' => $filename])
                ]);
            } else {
                // Log the error output
                Log::error('Backup failed', [
                    'command' => $command,
                    'output' => $output,
                    'return_var' => $returnVar
                ]);
                
                throw new \Exception('Backup failed. Check if mysqldump is available. Output: ' . implode(' ', $output));
            }

        } catch (\Exception $e) {
            Log::error('Backup error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Download backup file
     */
    public function downloadBackup($filename)
    {
        $filepath = storage_path('app/backups/' . $filename);
        
        if (file_exists($filepath)) {
            return response()->download($filepath);
        }
        
        abort(404, 'Backup file not found');
    }

    /**
     * Restore database from backup
     */
    public function restoreBackup(Request $request)
    {
        try {
            $request->validate([
                'backup_file' => 'required|file|mimes:sql'
            ]);

            $file = $request->file('backup_file');
            $filepath = $file->getPathname();
            
            // Get database credentials
            $dbHost = config('database.connections.mysql.host');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            
            // Try to find mysql in Laragon path (Windows)
            $mysqlPath = 'mysql';
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Check common Laragon paths
                $possiblePaths = [
                    'C:\\laragon\\bin\\mysql\\mysql-8.0.30\\bin\\mysql.exe',
                    'C:\\laragon\\bin\\mysql\\mysql-5.7.24\\bin\\mysql.exe',
                    'mysql.exe'
                ];
                
                foreach ($possiblePaths as $path) {
                    if (file_exists($path)) {
                        $mysqlPath = '"' . $path . '"';
                        break;
                    }
                }
            }
            
            // Create mysql import command
            $command = sprintf(
                '%s --host=%s --user=%s --password=%s %s < "%s" 2>&1',
                $mysqlPath,
                escapeshellarg($dbHost),
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbName),
                $filepath
            );
            
            // Execute restore
            exec($command, $output, $returnVar);
            
            if ($returnVar === 0 || empty($output)) {
                Log::info('Database restored', [
                    'user' => auth('admin')->user()->nama_admin ?? 'Unknown'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Database restored successfully'
                ]);
            } else {
                Log::error('Restore failed', [
                    'output' => $output,
                    'return_var' => $returnVar
                ]);
                
                throw new \Exception('Restore failed. Output: ' . implode(' ', $output));
            }

        } catch (\Exception $e) {
            Log::error('Restore error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Restore failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate report
     */
    public function generateReport(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|string|in:inventaris,kontrak,okupansi,summary,ruang,realisasi',
                'format' => 'required|string|in:pdf,excel'
            ]);

            $type = $request->type;
            $format = $request->format;
            
            // Get report data
            $data = $this->getReportData($type);
            
            if ($format === 'pdf') {
                return $this->generatePdfReport($data, $type);
            } else {
                return $this->generateExcelReport($data, $type);
            }

        } catch (\Exception $e) {
            Log::error('Report generation error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Report generation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download template for import
     */
    public function downloadTemplate(Request $request)
    {
        $model = $request->get('model', 'kantor');
        
        $headers = $this->getTemplateHeaders($model);
        $filename = "template_{$model}_import.csv";
        
        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Process import
     */
    public function processImport(Request $request)
    {
        try {
            $request->validate([
                'model' => 'required|string|in:kantor,gedung,lantai,ruang,bidang,sub_bidang,okupansi,kontrak,realisasi',
                'file' => 'required|file|mimes:csv,xlsx,xls|max:10240'
            ]);

            $model = $request->model;
            $file = $request->file('file');
            
            // Baca file CSV
            $data = $this->readCsvFile($file);
            
            // Validasi data
            $validation = $this->validateImportData($data, $model);
            
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data validation failed',
                    'errors' => $validation['errors']
                ]);
            }

            // Preview data
            if ($request->preview) {
                return response()->json([
                    'success' => true,
                    'preview' => true,
                    'data' => array_slice($data, 0, 5), // Tampilkan 5 baris pertama
                    'total_rows' => count($data)
                ]);
            }

            // Import data
            $result = $this->importData($data, $model);

            Log::info("Bulk import {$model}", [
                'imported_count' => $result['imported'],
                'failed_count' => $result['failed'],
                'user' => auth('admin')->user()->nama_admin ?? 'Unknown'
            ]);

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengimport {$result['imported']} data, {$result['failed']} gagal",
                'imported' => $result['imported'],
                'failed' => $result['failed'],
                'errors' => $result['errors']
            ]);

        } catch (\Exception $e) {
            Log::error('Import error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Read CSV file
     */
    private function readCsvFile($file)
    {
        $data = [];
        $handle = fopen($file->getPathname(), 'r');
        
        if ($handle !== false) {
            $headers = fgetcsv($handle); // Skip baris header
            
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) === count($headers)) {
                    $data[] = array_combine($headers, $row);
                }
            }
            
            fclose($handle);
        }
        
        return $data;
    }

    /**
     * Validate import data
     */
    private function validateImportData($data, $model)
    {
        $errors = [];
        $valid = true;

        foreach ($data as $index => $row) {
            $rowErrors = $this->validateRow($row, $model, $index + 2); // +2 karena skip header dan mulai dari 1
            if (!empty($rowErrors)) {
                $errors["Row " . ($index + 2)] = $rowErrors;
                $valid = false;
            }
        }

        return [
            'valid' => $valid,
            'errors' => $errors
        ];
    }

    /**
     * Validate single row
     */
    private function validateRow($row, $model, $rowNumber)
    {
        $rules = $this->getValidationRules($model);
        $errors = [];

        foreach ($rules as $field => $rule) {
            if (!isset($row[$field]) || empty($row[$field])) {
                if (strpos($rule, 'required') !== false) {
                    $errors[] = "Field {$field} is required";
                }
            }
        }

        return $errors;
    }

    /**
     * Import data to database
     */
    private function importData($data, $model)
    {
        $imported = 0;
        $failed = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($data as $index => $row) {
                try {
                    $this->createRecord($row, $model);
                    $imported++;
                } catch (\Exception $e) {
                    $failed++;
                    $errors["Row " . ($index + 2)] = $e->getMessage();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'imported' => $imported,
            'failed' => $failed,
            'errors' => $errors
        ];
    }

    /**
     * Create record based on model
     */
    private function createRecord($row, $model)
    {
        switch ($model) {
            case 'kantor':
                return Kantor::create($this->mapKantorData($row));
            case 'gedung':
                return Gedung::create($this->mapGedungData($row));
            case 'lantai':
                return Lantai::create($this->mapLantaiData($row));
            case 'ruang':
                return Ruang::create($this->mapRuangData($row));
            case 'bidang':
                return Bidang::create($this->mapBidangData($row));
            case 'sub_bidang':
                return SubBidang::create($this->mapSubBidangData($row));
            case 'okupansi':
                return Okupansi::create($this->mapOkupansiData($row));
            case 'kontrak':
                return Kontrak::create($this->mapKontrakData($row));
            case 'realisasi':
                return Realisasi::create($this->mapRealisasiData($row));
            default:
                throw new \Exception("Unknown model: {$model}");
        }
    }

    /**
     * Get template headers
     */
    private function getTemplateHeaders($model)
    {
        switch ($model) {
            case 'kantor':
                return ['kode_kantor', 'nama_kantor', 'jenis_kantor_id', 'kota_id', 'alamat', 'latitude', 'longitude', 'parent_kantor_id', 'status_kantor', 'status_kepemilikan'];
            case 'gedung':
                return ['nama_gedung', 'kantor_id', 'alamat_gedung', 'status_gedung'];
            case 'lantai':
                return ['nama_lantai', 'nomor_lantai', 'gedung_id'];
            case 'ruang':
                return ['nama_ruang', 'lantai_id', 'bidang_id', 'sub_bidang_id', 'kapasitas', 'status_ruang'];
            case 'bidang':
                return ['nama_bidang', 'deskripsi'];
            case 'sub_bidang':
                return ['nama_sub_bidang', 'bidang_id', 'deskripsi'];
            case 'okupansi':
                return ['ruang_id', 'bidang_id', 'sub_bidang_id', 'tanggal_okupansi', 'jml_pegawai_organik', 'jml_pegawai_tad', 'jml_pegawai_kontrak', 'keterangan'];
            case 'kontrak':
                return ['nama_perjanjian', 'no_perjanjian_pihak_1', 'no_perjanjian_pihak_2', 'kantor_id', 'asset_owner', 'tanggal_mulai', 'tanggal_selesai', 'status_perjanjian'];
            case 'realisasi':
                return ['kontrak_id', 'tanggal_realisasi', 'kompensasi', 'deskripsi', 'rp_kompensasi', 'lokasi_kantor', 'alamat'];
            default:
                return [];
        }
    }

    /**
     * Get validation rules
     */
    private function getValidationRules($model)
    {
        switch ($model) {
            case 'kantor':
                return [
                    'kode_kantor' => 'required|string|max:255',
                    'nama_kantor' => 'required|string|max:255',
                    'jenis_kantor_id' => 'required|exists:jenis_kantor,id',
                    'kota_id' => 'required|exists:kota,id',
                    'alamat' => 'required|string',
                    'status_kantor' => 'required|in:aktif,tidak_aktif',
                    'status_kepemilikan' => 'required|in:milik_sendiri,sewa'
                ];
            case 'gedung':
                return [
                    'nama_gedung' => 'required|string|max:255',
                    'kantor_id' => 'required|exists:kantor,id',
                    'alamat_gedung' => 'required|string',
                    'status_gedung' => 'required|in:aktif,tidak_aktif'
                ];
            case 'lantai':
                return [
                    'nama_lantai' => 'required|string|max:255',
                    'nomor_lantai' => 'required|integer',
                    'gedung_id' => 'required|exists:gedung,id'
                ];
            case 'ruang':
                return [
                    'nama_ruang' => 'required|string|max:255',
                    'lantai_id' => 'required|exists:lantai,id',
                    'bidang_id' => 'required|exists:bidang,id',
                    'kapasitas' => 'required|integer|min:1',
                    'status_ruang' => 'required|in:tersedia,terisi,perbaikan'
                ];
            case 'bidang':
                return [
                    'nama_bidang' => 'required|string|max:255',
                    'deskripsi' => 'nullable|string'
                ];
            case 'sub_bidang':
                return [
                    'nama_sub_bidang' => 'required|string|max:255',
                    'bidang_id' => 'required|exists:bidang,id',
                    'deskripsi' => 'nullable|string'
                ];
            case 'okupansi':
                return [
                    'ruang_id' => 'required|exists:ruang,id',
                    'bidang_id' => 'required|exists:bidang,id',
                    'tanggal_okupansi' => 'required|date',
                    'jml_pegawai_organik' => 'required|integer|min:0',
                    'jml_pegawai_tad' => 'required|integer|min:0',
                    'jml_pegawai_kontrak' => 'required|integer|min:0'
                ];
            case 'kontrak':
                return [
                    'nama_perjanjian' => 'required|string|max:255',
                    'no_perjanjian_pihak_1' => 'required|string|max:255',
                    'no_perjanjian_pihak_2' => 'required|string|max:255',
                    'kantor_id' => 'required|exists:kantor,id',
                    'asset_owner' => 'required|string|max:255',
                    'tanggal_mulai' => 'required|date',
                    'tanggal_selesai' => 'required|date|after:tanggal_mulai',
                    'status_perjanjian' => 'required|in:aktif,selesai,batal'
                ];
            case 'realisasi':
                return [
                    'kontrak_id' => 'required|exists:kontrak,id',
                    'tanggal_realisasi' => 'required|date',
                    'kompensasi' => 'required|in:Pemeliharaan,Pembangunan',
                    'deskripsi' => 'required|string',
                    'rp_kompensasi' => 'required|numeric|min:0',
                    'lokasi_kantor' => 'nullable|in:UIW,UID,UIP,UIT'
                ];
            default:
                return [];
        }
    }

    /**
     * Map data for each model
     */
    private function mapKantorData($row)
    {
        return [
            'kode_kantor' => $row['kode_kantor'],
            'nama_kantor' => $row['nama_kantor'],
            'jenis_kantor_id' => $row['jenis_kantor_id'],
            'kota_id' => $row['kota_id'],
            'alamat' => $row['alamat'],
            'latitude' => $row['latitude'] ?? null,
            'longitude' => $row['longitude'] ?? null,
            'parent_kantor_id' => $row['parent_kantor_id'] ?? null,
            'status_kantor' => $row['status_kantor'],
            'status_kepemilikan' => $row['status_kepemilikan']
        ];
    }

    private function mapGedungData($row)
    {
        return [
            'nama_gedung' => $row['nama_gedung'],
            'kantor_id' => $row['kantor_id'],
            'alamat_gedung' => $row['alamat_gedung'],
            'status_gedung' => $row['status_gedung']
        ];
    }

    private function mapLantaiData($row)
    {
        return [
            'nama_lantai' => $row['nama_lantai'],
            'nomor_lantai' => $row['nomor_lantai'],
            'gedung_id' => $row['gedung_id']
        ];
    }

    private function mapRuangData($row)
    {
        return [
            'nama_ruang' => $row['nama_ruang'],
            'lantai_id' => $row['lantai_id'],
            'bidang_id' => $row['bidang_id'],
            'sub_bidang_id' => $row['sub_bidang_id'] ?? null,
            'kapasitas' => $row['kapasitas'],
            'status_ruang' => $row['status_ruang']
        ];
    }

    private function mapBidangData($row)
    {
        return [
            'nama_bidang' => $row['nama_bidang'],
            'deskripsi' => $row['deskripsi'] ?? null
        ];
    }

    private function mapSubBidangData($row)
    {
        return [
            'nama_sub_bidang' => $row['nama_sub_bidang'],
            'bidang_id' => $row['bidang_id'],
            'deskripsi' => $row['deskripsi'] ?? null
        ];
    }

    private function mapOkupansiData($row)
    {
        return [
            'ruang_id' => $row['ruang_id'],
            'bidang_id' => $row['bidang_id'],
            'sub_bidang_id' => $row['sub_bidang_id'] ?? null,
            'tanggal_okupansi' => $row['tanggal_okupansi'],
            'jml_pegawai_organik' => $row['jml_pegawai_organik'],
            'jml_pegawai_tad' => $row['jml_pegawai_tad'],
            'jml_pegawai_kontrak' => $row['jml_pegawai_kontrak'],
            'keterangan' => $row['keterangan'] ?? null
        ];
    }

    private function mapKontrakData($row)
    {
        return [
            'nama_perjanjian' => $row['nama_perjanjian'],
            'no_perjanjian_pihak_1' => $row['no_perjanjian_pihak_1'],
            'no_perjanjian_pihak_2' => $row['no_perjanjian_pihak_2'],
            'kantor_id' => $row['kantor_id'],
            'asset_owner' => $row['asset_owner'],
            'tanggal_mulai' => $row['tanggal_mulai'],
            'tanggal_selesai' => $row['tanggal_selesai'],
            'status_perjanjian' => $row['status_perjanjian']
        ];
    }

    private function mapRealisasiData($row)
    {
        return [
            'kontrak_id' => $row['kontrak_id'],
            'tanggal_realisasi' => $row['tanggal_realisasi'],
            'kompensasi' => $row['kompensasi'],
            'deskripsi' => $row['deskripsi'],
            'rp_kompensasi' => $row['rp_kompensasi'],
            'lokasi_kantor' => $row['lokasi_kantor'] ?? null,
            'alamat' => $row['alamat'] ?? null
        ];
    }

    /**
     * Get model data for export
     */
    private function getModelData($model)
    {
        switch ($model) {
            case 'kantor':
                return Kantor::with(['jenisKantor', 'kota'])->get();
            case 'gedung':
                return Gedung::with('kantor')->get();
            case 'lantai':
                return Lantai::with('gedung')->get();
            case 'ruang':
                return Ruang::with(['lantai', 'bidang', 'subBidang'])->get();
            case 'bidang':
                return Bidang::all();
            case 'sub_bidang':
                return SubBidang::with('bidang')->get();
            case 'okupansi':
                return Okupansi::with(['ruang', 'bidang', 'subBidang'])->get();
            case 'kontrak':
                return Kontrak::with('kantor')->get();
            case 'realisasi':
                return Realisasi::with('kontrak')->get();
            case 'inventaris':
                return Inventaris::with(['kategori', 'kantor', 'gedung', 'lantai', 'ruang', 'bidang'])->get();
            default:
                return collect([]);
        }
    }

    /**
     * Export to CSV
     */
    private function exportCsv($data, $model)
    {
        try {
            $filename = "export_{$model}_" . date('Y-m-d_H-i-s') . '.csv';
            
            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                
                // Set UTF-8 BOM for Excel compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                if ($data && $data->count() > 0) {
                    $firstItem = $data->first();
                    
                    // Handle array or object
                    if (is_array($firstItem)) {
                        $headers = array_keys($firstItem);
                        fputcsv($file, $headers);
                        
                        foreach ($data as $row) {
                            fputcsv($file, is_array($row) ? $row : (array)$row);
                        }
                    } else {
                        // Write headers
                        $headers = array_keys($firstItem->toArray());
                        fputcsv($file, $headers);
                        
                        // Write data
                        foreach ($data as $row) {
                            fputcsv($file, $row->toArray());
                        }
                    }
                } else {
                    // Empty data
                    fputcsv($file, ['No data available']);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        } catch (\Exception $e) {
            Log::error('Export CSV error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export to Excel (CSV format with .xlsx extension for simplicity)
     */
    private function exportExcel($data, $model)
    {
        // For simplicity, we'll use CSV format but with .xlsx extension
        // In production, consider using PhpSpreadsheet or Laravel Excel package
        return $this->exportCsv($data, $model);
    }

    /**
     * Get report data
     */
    private function getReportData($type)
    {
        switch ($type) {
            case 'inventaris':
                return Inventaris::with(['kategori', 'kantor', 'gedung', 'lantai', 'ruang', 'bidang'])
                    ->orderBy('lokasi_kantor_id')
                    ->get();
            case 'kontrak':
                return Kontrak::with('kantor')
                    ->where('tanggal_selesai', '>', now())
                    ->where('tanggal_selesai', '<', now()->addMonths(3))
                    ->orderBy('tanggal_selesai')
                    ->get();
            case 'okupansi':
                return Okupansi::with(['ruang', 'bidang', 'subBidang'])
                    ->orderBy('bidang_id')
                    ->get();
            case 'ruang':
                return Ruang::with(['lantai', 'bidang', 'subBidang'])
                    ->orderBy('status_ruang')
                    ->get();
            case 'realisasi':
                return Realisasi::with('kontrak')
                    ->orderBy('tanggal_realisasi', 'desc')
                    ->get();
            case 'summary':
                return [
                    'total_kantor' => Kantor::count(),
                    'total_gedung' => Gedung::count(),
                    'total_lantai' => Lantai::count(),
                    'total_ruang' => Ruang::count(),
                    'total_bidang' => Bidang::count(),
                    'total_sub_bidang' => SubBidang::count(),
                    'total_okupansi' => Okupansi::sum('jml_pegawai_organik') + Okupansi::sum('jml_pegawai_tad') + Okupansi::sum('jml_pegawai_kontrak'),
                    'total_kontrak' => Kontrak::count(),
                    'kontrak_aktif' => Kontrak::where('status_perjanjian', 'aktif')->count(),
                ];
            default:
                return collect([]);
        }
    }

    /**
     * Generate PDF report (placeholder)
     */
    private function generatePdfReport($data, $type)
    {
        // Placeholder - implement with DomPDF or similar
        $filename = "report_{$type}_" . date('Y-m-d') . '.csv';
        
        // Convert to collection if array
        if (is_array($data)) {
            $data = collect([$data]);
        } elseif (!($data instanceof \Illuminate\Support\Collection)) {
            $data = collect($data);
        }
        
        // For now, return CSV
        return $this->exportCsv($data, $type);
    }

    /**
     * Generate Excel report
     */
    private function generateExcelReport($data, $type)
    {
        // Convert to collection if array
        if (is_array($data)) {
            $data = collect([$data]);
        } elseif (!($data instanceof \Illuminate\Support\Collection)) {
            $data = collect($data);
        }
        
        return $this->exportCsv($data, $type);
    }
}

