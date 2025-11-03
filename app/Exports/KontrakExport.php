<?php

namespace App\Exports;

use App\Models\Kontrak;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class KontrakExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    protected $kontraks;

    public function __construct($kontraks = null)
    {
        $this->kontraks = $kontraks ?: Kontrak::with(['kantor.kota'])->get();
    }

    public function collection()
    {
        return $this->kontraks->map(function ($kontrak) {
            // Hitung durasi dalam hari
            $duration = null;
            if ($kontrak->tanggal_mulai && $kontrak->tanggal_selesai) {
                try {
                    $start = is_string($kontrak->tanggal_mulai) ? \Carbon\Carbon::parse($kontrak->tanggal_mulai) : $kontrak->tanggal_mulai;
                    $end = is_string($kontrak->tanggal_selesai) ? \Carbon\Carbon::parse($kontrak->tanggal_selesai) : $kontrak->tanggal_selesai;
                    $duration = $start->diffInDays($end);
                } catch (\Exception $e) {
                    $duration = null;
                }
            }

            return [
                'ID' => $kontrak->id,
                'Nama Perjanjian' => $kontrak->nama_perjanjian,
                'No Perjanjian Pihak 1' => $kontrak->no_perjanjian_pihak_1 ?? 'N/A',
                'No Perjanjian Pihak 2' => $kontrak->no_perjanjian_pihak_2 ?? 'N/A',
                'Tanggal Mulai' => $this->formatDate($kontrak->tanggal_mulai),
                'Tanggal Selesai' => $this->formatDate($kontrak->tanggal_selesai),
                'Durasi (Hari)' => $duration ? $duration . ' hari' : 'N/A',
                'Nilai Kontrak' => $kontrak->nilai_kontrak ? 'Rp ' . number_format($kontrak->nilai_kontrak, 0, ',', '.') : 'N/A',
                'Status Perjanjian' => $this->getStatusPerjanjian($kontrak->status_perjanjian),
                'Status' => $kontrak->status ?? 'Aktif',
                'Kantor' => $kontrak->kantor ? $kontrak->kantor->nama_kantor : 'N/A',
                'Parent Kantor' => $this->formatParentKantor($kontrak->sbu_type, $kontrak->sbu),
                'Asset Owner' => $kontrak->asset_owner ?? 'N/A',
                'Peruntukan Kantor' => $kontrak->peruntukan_kantor ?? 'N/A',
                'Keterangan' => $kontrak->keterangan ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Perjanjian',
            'No Perjanjian Pihak 1',
            'No Perjanjian Pihak 2',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Durasi (Hari)',
            'Nilai Kontrak',
            'Status Perjanjian',
            'Status',
            'Kantor',
            'Parent Kantor',
            'Asset Owner',
            'Peruntukan Kantor',
            'Keterangan'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 40,  // Nama Perjanjian
            'C' => 25,  // No Perjanjian Pihak 1
            'D' => 25,  // No Perjanjian Pihak 2
            'E' => 15,  // Tanggal Mulai
            'F' => 15,  // Tanggal Selesai
            'G' => 15,  // Durasi (Hari)
            'H' => 20,  // Nilai Kontrak
            'I' => 18,  // Status Perjanjian
            'J' => 15,  // Status
            'K' => 25,  // Kantor
            'L' => 25,  // Parent Kantor
            'M' => 25,  // Asset Owner
            'N' => 20,  // Peruntukan Kantor
            'O' => 30,  // Keterangan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setTitle('Data Kontrak PLN Icon Plus');

        // Styling header
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'], // Font putih
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E3A8A'], // PLN Blue yang lebih gelap
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        // Terapkan border ke semua cell data
        $sheet->getStyle('A1:O' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        // Alternate row colors
        foreach ($sheet->getRowIterator() as $row) {
            $rowIndex = $row->getRowIndex();
            if ($rowIndex > 1 && $rowIndex % 2 !== 0) { // Skip header, apply to odd data rows
                $sheet->getStyle('A' . $rowIndex . ':O' . $rowIndex)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE0E0E0'], // Light grey
                    ],
                ]);
            }
        }

        // Set alignment for specific columns
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // ID
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Durasi
        $sheet->getStyle('H:H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Nilai Kontrak
        $sheet->getStyle('I:I')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Status Perjanjian
        $sheet->getStyle('J:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Status

        // Wrap text for keterangan
        $sheet->getStyle('O:O')->getAlignment()->setWrapText(true); // Keterangan
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Auto-fit all columns
                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
                
                // Set row height for header
                $sheet->getRowDimension(1)->setRowHeight(30);
                
                // Add borders to all cells
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'E5E7EB'],
                        ],
                    ],
                ]);
                
                // Alternate row colors
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':' . $highestColumn . $row)->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8FAFC'],
                            ],
                        ]);
                    }
                }
                
                // Center align specific columns
                $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // ID
                $sheet->getStyle('E:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Dates
                $sheet->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Duration
                $sheet->getStyle('H:H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Amount
                $sheet->getStyle('I:I')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Status Perjanjian
                $sheet->getStyle('J:J')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Status
                
                // Wrap text for long content
                $sheet->getStyle('B:B')->getAlignment()->setWrapText(true); // Nama Perjanjian
                $sheet->getStyle('H:H')->getAlignment()->setWrapText(true); // Alamat Kantor
                $sheet->getStyle('P:Q')->getAlignment()->setWrapText(true); // Deskripsi & Keterangan
                
                // Add PLN branding
                $sheet->getCell('A1')->setValue('PLN ICON PLUS - DATA KONTRAK');
                $sheet->mergeCells('A1:S1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 14,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1E3A8A'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                
                // Insert actual headers at row 2
                $sheet->insertNewRowBefore(2, 1);
                $sheet->getRowDimension(2)->setRowHeight(25);
                
                // Set actual headers
                $headers = $this->headings();
                foreach ($headers as $index => $header) {
                    $column = chr(65 + $index); // A, B, C, etc.
                    $sheet->getCell($column . '2')->setValue($header);
                }
                
                // Style the actual headers
                $sheet->getStyle('A2:' . $highestColumn . '2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '3B82F6'], // Lighter blue
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '1E3A8A'],
                        ],
                    ],
                ]);
            },
        ];
    }

    private function formatDate($date)
    {
        if (!$date) {
            return 'N/A';
        }
        
        try {
            if (is_string($date)) {
                return \Carbon\Carbon::parse($date)->format('d/m/Y');
            }
            return $date->format('d/m/Y');
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function getStatusPerjanjian($status)
    {
        switch ($status) {
            case 'baru':
                return 'Baru';
            case 'berjalan':
                return 'Berjalan';
            case 'selesai':
                return 'Selesai';
            default:
                return 'N/A';
        }
    }

    private function formatParentKantor($sbuType, $sbu)
    {
        if (!$sbuType && !$sbu) {
            return 'N/A';
        }
        
        if ($sbuType && $sbu) {
            return $sbu; // Return the full SBU name
        }
        
        if ($sbuType) {
            return $sbuType;
        }
        
        return $sbu ?? 'N/A';
    }
}