-- PLN Kantor Management - Database Views
-- File ini berisi view SQL untuk aplikasi

-- View untuk statistik kantor
CREATE VIEW v_kantor_stats AS
SELECT 
    COUNT(*) as total_kantor,
    COUNT(CASE WHEN status = 'aktif' THEN 1 END) as kantor_aktif,
    COUNT(CASE WHEN status = 'tidak_aktif' THEN 1 END) as kantor_tidak_aktif
FROM kantor;

-- View untuk statistik gedung
CREATE VIEW v_gedung_stats AS
SELECT 
    COUNT(*) as total_gedung,
    COUNT(CASE WHEN status_kepemilikan = 'milik' THEN 1 END) as gedung_milik,
    COUNT(CASE WHEN status_kepemilikan = 'sewa' THEN 1 END) as gedung_sewa
FROM gedung;

-- View untuk statistik kontrak
CREATE VIEW v_kontrak_stats AS
SELECT 
    COUNT(*) as total_kontrak,
    COUNT(CASE WHEN status_perjanjian = 'aktif' THEN 1 END) as kontrak_aktif,
    COUNT(CASE WHEN status_perjanjian = 'selesai' THEN 1 END) as kontrak_selesai,
    SUM(nilai_kontrak) as total_nilai_kontrak
FROM kontrak;

-- View untuk data kantor lengkap
CREATE VIEW v_kantor_lengkap AS
SELECT 
    k.id,
    k.nama_kantor,
    k.alamat,
    k.status,
    jk.nama_jenis_kantor,
    ko.nama_kota,
    p.nama_provinsi,
    COUNT(g.id) as jumlah_gedung
FROM kantor k
JOIN jenis_kantor jk ON k.jenis_kantor_id = jk.id
JOIN kota ko ON k.kota_id = ko.id
JOIN provinsi p ON ko.provinsi_id = p.id
LEFT JOIN gedung g ON g.kantor_id = k.id
GROUP BY k.id, k.nama_kantor, k.alamat, k.status, jk.nama_jenis_kantor, ko.nama_kota, p.nama_provinsi;
