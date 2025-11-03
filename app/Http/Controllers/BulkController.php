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
use App\Models\Kota;
use App\Models\JenisKantor;
use App\Services\AuditLogService;

class BulkController extends Controller
{
    /**
     * Bulk delete operations
     */
    public function bulkDelete(Request $request, $model)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer'
            ]);

            // Validasi parameter model
            if (!in_array($model, ['kantor', 'gedung', 'ruang', 'okupansi', 'kontrak', 'realisasi', 'bidang', 'sub_bidang', 'lantai', 'inventaris', 'kategori_inventaris'])) {
                throw new \Exception("Invalid model: {$model}");
            }
            $ids = $request->ids;
            $deletedCount = 0;

            DB::beginTransaction();

            switch ($model) {
                case 'kantor':
                    $deletedCount = Kantor::whereIn('id', $ids)->delete();
                    break;
                case 'gedung':
                    $deletedCount = Gedung::whereIn('id', $ids)->delete();
                    break;
                case 'lantai':
                    $deletedCount = Lantai::whereIn('id', $ids)->delete();
                    break;
                case 'ruang':
                    $deletedCount = Ruang::whereIn('id', $ids)->delete();
                    break;
                case 'bidang':
                    $deletedCount = Bidang::whereIn('id', $ids)->delete();
                    break;
                case 'sub_bidang':
                    $deletedCount = SubBidang::whereIn('id', $ids)->delete();
                    break;
                case 'okupansi':
                    $deletedCount = Okupansi::whereIn('id', $ids)->delete();
                    break;
                case 'kontrak':
                    $deletedCount = Kontrak::whereIn('id', $ids)->delete();
                    break;
                case 'realisasi':
                    $deletedCount = Realisasi::whereIn('id', $ids)->delete();
                    break;
                case 'inventaris':
                    $deletedCount = Inventaris::whereIn('id', $ids)->delete();
                    break;
                case 'kategori_inventaris':
                    $deletedCount = KategoriInventaris::whereIn('id', $ids)->delete();
                    break;
            }

            DB::commit();

            // Catat log audit
            AuditLogService::logBulkOperation('delete', $model, $deletedCount, $request, "Bulk delete {$deletedCount} {$model}");

            Log::info("Bulk delete {$model}", [
                'deleted_count' => $deletedCount,
                'ids' => $ids,
                'user' => auth('admin')->user()->nama_admin ?? 'Unknown'
            ]);

            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} data {$model}",
                'deleted_count' => $deletedCount,
                'toast' => [
                    'type' => 'success',
                    'title' => 'Bulk Delete Berhasil',
                    'message' => "{$deletedCount} data {$model} berhasil dihapus!"
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk delete error', [
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
     * Bulk export operations
     */
    public function bulkExport(Request $request, $model)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer',
                'format' => 'required|string|in:excel,csv'
            ]);

            // Validasi parameter model
            if (!in_array($model, ['kantor', 'gedung', 'ruang', 'okupansi', 'kontrak', 'realisasi', 'bidang', 'sub_bidang', 'lantai', 'inventaris', 'kategori_inventaris'])) {
                throw new \Exception("Invalid model: {$model}");
            }
            $ids = $request->ids;
            $format = $request->format;

            // Ambil data berdasarkan model
            $data = $this->getBulkData($model, $ids);

            if ($format === 'excel') {
                return $this->exportToExcel($data, $model);
            } else {
                return $this->exportToCsv($data, $model);
            }

        } catch (\Exception $e) {
            Log::error('Bulk export error', [
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
     * Get bulk data based on model
     */
    private function getBulkData($model, $ids)
    {
        switch ($model) {
            case 'kantor':
                return Kantor::with(['kota', 'jenisKantor'])
                    ->whereIn('id', $ids)
                    ->get();
            case 'gedung':
                return Gedung::with(['kantor.kota'])
                    ->whereIn('id', $ids)
                    ->get();
            case 'lantai':
                return Lantai::with(['gedung.kantor'])
                    ->whereIn('id', $ids)
                    ->get();
            case 'ruang':
                return Ruang::with(['lantai.gedung.kantor', 'bidang', 'subBidang'])
                    ->whereIn('id', $ids)
                    ->get();
            case 'bidang':
                return Bidang::whereIn('id', $ids)->get();
            case 'sub_bidang':
                return SubBidang::with(['bidang'])
                    ->whereIn('id', $ids)
                    ->get();
            case 'okupansi':
                return Okupansi::with(['ruang.lantai.gedung.kantor', 'bidang', 'subBidang'])
                    ->whereIn('id', $ids)
                    ->get();
            case 'kontrak':
                return Kontrak::with(['kantor.kota'])
                    ->whereIn('id', $ids)
                    ->get();
            case 'realisasi':
                return Realisasi::with(['kontrak.kantor'])
                    ->whereIn('id', $ids)
                    ->get();
            case 'inventaris':
                return Inventaris::with(['kategori', 'kantor', 'gedung', 'lantai', 'ruang', 'bidang', 'subBidang'])
                    ->whereIn('id', $ids)
                    ->get();
            case 'kategori_inventaris':
                return KategoriInventaris::whereIn('id', $ids)->get();
            default:
                return collect();
        }
    }

    /**
     * Export to Excel
     */
    private function exportToExcel($data, $model)
    {
        try {
            $filename = "PLN_{$model}_export_" . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Gunakan class export khusus untuk kontrak
            if ($model === 'kontrak') {
                return \Maatwebsite\Excel\Facades\Excel::download(
                    new \App\Exports\KontrakExport($data),
                    $filename
                );
            }
            
            // Untuk model lain, gunakan CSV dulu
            return $this->exportToCsv($data, $model);
        } catch (\Exception $e) {
            Log::error('Excel export error', [
                'error' => $e->getMessage(),
                'model' => $model
            ]);
            
            // Fallback ke CSV kalau Excel gagal
            return $this->exportToCsv($data, $model);
        }
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($data, $model)
    {
        $filename = "{$model}_export_" . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data, $model) {
            $file = fopen('php://output', 'w');
            
            // Tambah header berdasarkan model
            $headers = $this->getCsvHeaders($model);
            fputcsv($file, $headers);
            
            // Tambah data
            foreach ($data as $row) {
                $csvRow = $this->formatCsvRow($row, $model);
                fputcsv($file, $csvRow);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get CSV headers based on model
     */
    private function getCsvHeaders($model)
    {
        switch ($model) {
            case 'kantor':
                return ['ID', 'Kode Kantor', 'Nama Kantor', 'Jenis Kantor', 'Kota', 'Alamat', 'Status'];
            case 'gedung':
                return ['ID', 'Nama Gedung', 'Kantor', 'Alamat', 'Status'];
            case 'ruang':
                return ['ID', 'Nama Ruang', 'Gedung', 'Lantai', 'Kapasitas', 'Status', 'Bidang'];
            case 'okupansi':
                return ['ID', 'Ruang', 'Bidang', 'Tanggal', 'Pegawai Organik', 'Pegawai TAD', 'Pegawai Kontrak', 'Total Pegawai'];
            case 'kontrak':
                return ['ID', 'Nama Perjanjian', 'No Pihak I', 'No Pihak II', 'Asset Owner', 'Kantor', 'Tanggal Mulai', 'Tanggal Selesai', 'Status'];
            case 'realisasi':
                return ['ID', 'Kontrak', 'Tanggal Realisasi', 'Kompensasi', 'Deskripsi', 'Rp Kompensasi', 'Lokasi'];
            case 'bidang':
                return ['ID', 'Nama Bidang', 'Deskripsi'];
            case 'sub_bidang':
                return ['ID', 'Nama Sub Bidang', 'Bidang', 'Deskripsi'];
            case 'lantai':
                return ['ID', 'Nama Lantai', 'Nomor Lantai', 'Gedung', 'Kantor'];
            case 'inventaris':
                return ['ID', 'Kode Inventaris', 'Nama Barang', 'Kategori', 'Jumlah', 'Kondisi', 'Lokasi Kantor', 'Lokasi Gedung', 'Lokasi Lantai', 'Lokasi Ruang', 'Bidang', 'Sub Bidang', 'Tanggal Input', 'Deskripsi'];
            case 'kategori_inventaris':
                return ['ID', 'Nama Kategori', 'Deskripsi', 'Dibuat', 'Diupdate'];
            default:
                return [];
        }
    }

    /**
     * Format CSV row based on model
     */
    private function formatCsvRow($row, $model)
    {
        switch ($model) {
            case 'kantor':
                return [
                    $row->id,
                    $row->kode_kantor,
                    $row->nama_kantor,
                    $row->jenisKantor->nama_jenis ?? 'N/A',
                    $row->kota->nama_kota ?? 'N/A',
                    $row->alamat,
                    $row->status_kantor
                ];
            case 'gedung':
                return [
                    $row->id,
                    $row->nama_gedung,
                    $row->kantor->nama_kantor ?? 'N/A',
                    $row->alamat,
                    $row->status_gedung
                ];
            case 'ruang':
                return [
                    $row->id,
                    $row->nama_ruang,
                    $row->lantai->gedung->nama_gedung ?? 'N/A',
                    $row->lantai->nomor_lantai ?? 'N/A',
                    $row->kapasitas,
                    $row->status_ruang,
                    $row->bidang->nama_bidang ?? 'N/A'
                ];
            case 'okupansi':
                return [
                    $row->id,
                    $row->ruang->nama_ruang ?? 'N/A',
                    $row->bidang->nama_bidang ?? 'N/A',
                    $row->tanggal_okupansi,
                    $row->jml_pegawai_organik,
                    $row->jml_pegawai_tad,
                    $row->jml_pegawai_kontrak,
                    $row->total_pegawai
                ];
            case 'kontrak':
                return [
                    $row->id,
                    $row->nama_perjanjian,
                    $row->no_perjanjian_pihak_1,
                    $row->no_perjanjian_pihak_2,
                    $row->asset_owner,
                    $row->kantor->nama_kantor ?? 'N/A',
                    $row->tanggal_mulai,
                    $row->tanggal_selesai,
                    $row->status_perjanjian
                ];
            case 'realisasi':
                return [
                    $row->id,
                    $row->kontrak->nama_perjanjian ?? 'N/A',
                    $row->tanggal_realisasi,
                    $row->kompensasi,
                    $row->deskripsi,
                    $row->rp_kompensasi,
                    $row->lokasi_kantor
                ];
            case 'bidang':
                return [
                    $row->id,
                    $row->nama_bidang,
                    $row->deskripsi ?? 'N/A'
                ];
            case 'sub_bidang':
                return [
                    $row->id,
                    $row->nama_sub_bidang,
                    $row->bidang->nama_bidang ?? 'N/A',
                    $row->deskripsi ?? 'N/A'
                ];
            case 'lantai':
                return [
                    $row->id,
                    $row->nama_lantai,
                    $row->nomor_lantai,
                    $row->gedung->nama_gedung ?? 'N/A',
                    $row->gedung->kantor->nama_kantor ?? 'N/A'
                ];
            case 'inventaris':
                return [
                    $row->id,
                    $row->kode_inventaris,
                    $row->nama_barang,
                    $row->kategori->nama_kategori ?? 'N/A',
                    $row->jumlah,
                    $row->kondisi,
                    $row->kantor->nama_kantor ?? 'N/A',
                    $row->gedung->nama_gedung ?? 'N/A',
                    $row->lantai->nama_lantai ?? 'N/A',
                    $row->ruang->nama_ruang ?? 'N/A',
                    $row->bidang->nama_bidang ?? 'N/A',
                    $row->subBidang->nama_sub_bidang ?? 'N/A',
                    $row->tanggal_input->format('d/m/Y'),
                    $row->deskripsi ?? 'N/A'
                ];
            case 'kategori_inventaris':
                return [
                    $row->id,
                    $row->nama_kategori,
                    $row->deskripsi ?? 'N/A',
                    $row->created_at->format('d/m/Y H:i'),
                    $row->updated_at->format('d/m/Y H:i')
                ];
            default:
                return [];
        }
    }
}
