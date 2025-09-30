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
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller
{
    /**
     * Show import form
     */
    public function index()
    {
        return view('import.index');
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
            
            // Read CSV file
            $data = $this->readCsvFile($file);
            
            // Validate data
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
                    'data' => array_slice($data, 0, 5), // Show first 5 rows
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
            $headers = fgetcsv($handle); // Skip header row
            
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
            $rowErrors = $this->validateRow($row, $model, $index + 2); // +2 because we skip header and start from 1
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
}

