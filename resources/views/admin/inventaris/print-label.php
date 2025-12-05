<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Label - {{ $inventaris->kode_inventaris }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .print-controls {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .print-controls button {
            padding: 10px 20px;
            margin: 0 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }
        
        .btn-print {
            background: #3b82f6;
            color: white;
        }
        
        .btn-back {
            background: #6b7280;
            color: white;
        }
        
        .label-preview {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .label {
            width: 80mm;
            height: 50mm;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .qr-section {
            flex: 0 0 auto;
            text-align: center;
        }
        
        .qr-section canvas {
            width: 35mm !important;
            height: 35mm !important;
        }
        
        .info-section {
            flex: 1;
            padding-left: 3mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .kode {
            font-size: 11pt;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 2mm;
            word-break: break-all;
        }
        
        .nama {
            font-size: 8pt;
            color: #374151;
            line-height: 1.3;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .pln-logo {
            font-size: 7pt;
            color: #6b7280;
            margin-top: 2mm;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .print-controls {
                display: none;
            }
            
            .label-preview {
                justify-content: flex-start;
            }
            
            .label {
                border: 1px solid #000;
                box-shadow: none;
                page-break-inside: avoid;
                margin: 2mm;
            }
        }
        
        @page {
            size: auto;
            margin: 5mm;
        }
    </style>
    <!-- QRCode.js library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
</head>
<body>
    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">
            🖨️ Print Label
        </button>
        <button class="btn-back" onclick="window.close()">
            ← Kembali
        </button>
        <p style="margin-top: 10px; color: #6b7280; font-size: 12px;">
            Ukuran label: 80mm x 50mm (cocok untuk label stiker standar)
        </p>
    </div>
    
    <div class="label-preview">
        <div class="label">
            <div class="qr-section">
                <canvas id="qrcode"></canvas>
            </div>
            <div class="info-section">
                <div class="kode">{{ $inventaris->kode_inventaris }}</div>
                <div class="nama">{{ Str::limit($inventaris->nama_barang, 50) }}</div>
                <div class="pln-logo">PLN Icon Plus</div>
            </div>
        </div>
    </div>
    
    <script>
        // Generate QR Code
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('qrcode');
            QRCode.toCanvas(canvas, '{{ $inventaris->kode_inventaris }}', {
                width: 132, // ~35mm at 96dpi
                margin: 1,
                color: {
                    dark: '#1e3a8a',
                    light: '#ffffff'
                }
            }, function(error) {
                if (error) console.error(error);
            });
        });
    </script>
</body>
</html>