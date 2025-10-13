-- PLN Kantor Management - Database Queries
-- File ini berisi query SQL untuk aplikasi

-- Query untuk mendapatkan data kantor
SELECT 
    k.id,
    k.nama_kantor,
    k.alamat,
    k.status,
    jk.nama_jenis_kantor,
    ko.nama_kota,
    p.nama_provinsi
FROM kantor k
JOIN jenis_kantor jk ON k.jenis_kantor_id = jk.id
JOIN kota ko ON k.kota_id = ko.id
JOIN provinsi p ON ko.provinsi_id = p.id
WHERE k.status = 'aktif'
ORDER BY k.nama_kantor;

-- Query untuk mendapatkan data gedung
SELECT 
    g.id,
    g.nama_gedung,
    g.alamat,
    g.jumlah_lantai,
    g.status_kepemilikan,
    k.nama_kantor
FROM gedung g
JOIN kantor k ON g.kantor_id = k.id
WHERE g.status = 'aktif'
ORDER BY g.nama_gedung;

-- Query untuk mendapatkan data kontrak
SELECT 
    k.id,
    k.nama_perjanjian,
    k.tanggal_mulai,
    k.tanggal_selesai,
    k.nilai_kontrak,
    k.status_perjanjian,
    kantor.nama_kantor
FROM kontrak k
JOIN kantor ON k.kantor_id = kantor.id
WHERE k.status = 'aktif'
ORDER BY k.tanggal_mulai DESC;
