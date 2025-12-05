@extends('layouts.public')

@section('title', 'Profil Perusahaan - PLN Icon Plus')
@section('description', 'Profil Perusahaan PLN Icon Plus: ringkasan perusahaan, perjalanan, visi, misi, dan nilai-nilai inti.')

@push('styles')
<style>
 .about-hero { position: relative; background: #ffffff; overflow: hidden; }
 .about-hero img { width: 100%; height: 520px; object-fit: cover; object-position: center; display: block; }
 .hero-overlay { position: absolute; inset: 0; display: flex; align-items: center; }
 .hero-content { max-width: 700px; padding: 48px 0 56px; }
 .hero-heading {
  color: #ffffff;
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  line-height: 1.25;
  letter-spacing: 0.05px;
  font-size: clamp(1.6rem, 1.5vw + 1rem, 2.2rem);
}
 .about-chip {
  display: none;
 }
 .about-hero .about-subtitle {
  color: rgba(255,255,255,0.92);
  font-family: 'Inter', sans-serif;
  font-weight: 400;
  font-size: 0.95rem;
  line-height: 1.6;
  max-width: 640px;
}
 .about-title {
  color: #0f4c81;
  font-weight: 800;
  letter-spacing: 0.2px;
 }
 .about-subtitle {
  color: #5b6b7b;
  max-width: 760px;
 }
 .about-section {
  padding: 40px 0 16px;
 }
 .about-card {
  background: #ffffff;
  border: 1.5px solid rgba(15, 76, 117, 0.22);
  border-radius: 20px;
  padding: 20px 22px;
 }
 .about-chip {
  display: inline-flex; align-items:center; gap:8px;
  border-radius: 999px; padding: 6px 12px;
  background: rgba(15, 76, 117, 0.1); color:#0f4c81; font-weight:600; font-size: .8rem;
 }
 .about-bullet li { margin-bottom: 8px; }
 .about-bullet li::marker { color: #0f4c81; }
 .about-kpi {
  border: 1.5px solid rgba(15, 76, 117, 0.22);
  border-radius: 16px; padding: 16px; background:#fff;
 }
 .about-kpi h3 { color:#0f4c81; margin:0; font-weight:800; }
 .about-kpi span { color:#4f647a; font-size:.9rem; }
 .about-value-title { color:#0f4c81; font-weight:700; }
</style>
@endpush

@section('content')
<div class="about-hero">
  <img src="{{ asset('bg/profil.png') }}" alt="Profil Perusahaan">
  <div class="container hero-overlay">
    <div class="hero-content">
      <h1 class="display-5 hero-heading">Tentang<br>PLN Icon Plus</h1>
      <p class="about-subtitle mt-2">Unleashing <strong>Beyond kWh</strong></p>
    </div>
  </div>
</div>

<div class="container about-section">
  <div class="row g-3">
    <div class="col-md-8">
      <div class="about-card">
        <h4 class="mb-2" style="color:#0f4c81;">Ringkasan</h4>
        <div style="color:#475569;">
          <p class="mb-2">PT PLN Icon Plus merupakan Entitas Anak PT PLN (Persero). PLN Icon Plus didirikan pada tahun 2000 sebagai bagian dari PT PLN (Persero) untuk memenuhi kebutuhan jaringan telekomunikasi PLN. Awalnya, perusahaan ini fokus pada operasi Network Operation Centre di Gandul, Cinere, untuk mendukung kelistrikan PLN.</p>
          <p class="mb-2">Seiring berkembangnya kebutuhan akan jaringan telekomunikasi yang lebih luas dan andal, PLN Icon Plus mulai menyalurkan kapasitas jaringan serat optik PLN ke publik, khususnya untuk perusahaan dan lembaga yang membutuhkan konektivitas ekstensif.</p>
          <p class="mb-2">Pada 2008, PLN Icon Plus mulai memperluas jaringan ke wilayah terpencil di Indonesia dengan memanfaatkan hak jaringan ketenagalistrikan PLN, yaitu Right of Way (RoW). Seiring waktu, PLN Icon Plus terus berinovasi dan memperkenalkan produk serta layanan berbasis teknologi terkini.</p>
          <p class="mb-0">Pada 21 September 2022, PLN Icon Plus menjadi Subholding PT PLN (Persero) dengan fokus baru pada tiga bisnis utama: kelistrikan, layanan konektivitas, dan layanan IT, serta berperan penting dalam inisiatif Beyond kWh yang mencakup pengembangan bisnis di luar sektor kelistrikan.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="about-kpi mb-3">
        <h3>120+</h3>
        <span>Kantor dipetakan</span>
      </div>
      <div class="about-kpi">
        <h3>24/7</h3>
        <span>Kesiapan layanan</span>
      </div>
    </div>
  </div>
</div>

<div class="container about-section">
  <div class="row g-3">
    <div class="col-md-6">
      <div class="about-card">
        <h4 class="mb-2" style="color:#0f4c81;">Visi</h4>
        <p class="mb-2" style="color:#475569;">Menjadi pemimpin penyedia smart connectivity solutions, digital dan green energy yang terintegrasi untuk mendukung transisi energi di Indonesia.</p>
      </div>
    </div>
    <div class="col-md-6">
      <div class="about-card">
        <h4 class="mb-2" style="color:#0f4c81;">Misi</h4>
        <ul class="about-bullet mb-0" style="color:#475569;">
          <li>Mengembangkan smart solution connectivity, digital dan green energy yang inovatif dan berbasis prinsip-prinsip ESG.</li>
          <li>Memenangkan hati pelanggan dengan produk dan layanan berkualitas guna memberikan pengalaman terbaik.</li>
          <li>Memastikan penggunaan sumber daya secara optimal untuk meningkatkan keunggulan kompetitif dengan berorientasi kepada aspirasi pemangku kepentingan.</li>
          <li>Membangun talenta yang berkualitas dan membina budaya kerja berkelanjutan.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container about-section">
  <div class="about-card">
    <h4 class="mb-3" style="color:#0f4c81;">Sejarah Perusahaan PLN Icon Plus</h4>
    <div class="row g-3">
      <div class="col-md-6">
        <ul class="about-bullet mb-0" style="color:#475569;">
          <li><strong>2000</strong> — PT Indonesia Comnets Plus berdiri.</li>
          <li><strong>2005</strong> — Memperoleh izin prinsip Internet Telephony untuk keperluan publik.</li>
          <li><strong>2007</strong> — Memperoleh izin prinsip Penyelenggara Jasa Interkoneksi Internet (NAP) dan penyelenggara Jasa Internet Telephony untuk Keperluan Publik.</li>
          <li><strong>2020</strong> — Memiliki lisensi Jasa Telekomunikasi Layanan Call Center dan meluncurkan aplikasi layanan pelanggan New PLN Mobile.</li>
        </ul>
      </div>
      <div class="col-md-6">
        <ul class="about-bullet mb-0" style="color:#475569;">
          <li><strong>2021</strong> — Rebranding Stroomnet (internet broadband ICON+) menjadi ICONNET.</li>
          <li><strong>2022</strong> — Transformasi ICON+ menjadi PLN Icon Plus sebagai Subholding PT PLN (Persero).</li>
          <li><strong>2023</strong> — ICONNET mencapai 1 juta pelanggan dan PLN Icon Plus go internasional melayani Fiber Optic di Timor Leste.</li>
        </ul>
      </div>
    </div>
  </div>
  
</div>

<div class="container about-section">
  <h4 class="mb-3" style="color:#0f4c81;">Nilai-Nilai Perusahaan</h4>
  <div class="row g-3">
    <div class="col-md-4">
      <div class="about-card h-100">
        <div class="about-value-title mb-1">Amanah</div>
        <ul class="about-bullet mb-0" style="color:#475569;">
          <li>Memegang teguh kepercayaan yang diberikan.</li>
          <li>Memenuhi janji dan komitmen.</li>
          <li>Bertanggung jawab atas tugas, keputusan, dan tindakan yang dilakukan.</li>
          <li>Berpegang teguh pada nilai moral dan etika.</li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="about-card h-100">
        <div class="about-value-title mb-1">Kompeten</div>
        <ul class="about-bullet mb-0" style="color:#475569;">
          <li>Terus belajar dan mengembangkan kapabilitas.</li>
          <li>Meningkatkan kompetensi diri untuk menjawab tantangan.</li>
          <li>Membantu orang lain belajar.</li>
          <li>Menyelesaikan tugas dengan kualitas terbaik.</li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="about-card h-100">
        <div class="about-value-title mb-1">Harmonis</div>
        <ul class="about-bullet mb-0" style="color:#475569;">
          <li>Saling peduli dan menghargai perbedaan.</li>
          <li>Menghargai setiap orang, apa pun latar belakangnya.</li>
          <li>Suka menolong orang lain.</li>
          <li>Membangun kinerja yang kondusif.</li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="about-card h-100">
        <div class="about-value-title mb-1">Loyal</div>
        <ul class="about-bullet mb-0" style="color:#475569;">
          <li>Berdedikasi dan mengutamakan kepentingan bangsa dan negara.</li>
          <li>Menjaga nama baik sesama karyawan, pimpinan, BUMN, dan negara.</li>
          <li>Rela berkorban untuk mencapai tujuan yang lebih besar.</li>
          <li>Patuh kepada pimpinan sepanjang tidak bertentangan dengan hukum dan etika.</li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="about-card h-100">
        <div class="about-value-title mb-1">Adaptif</div>
        <ul class="about-bullet mb-0" style="color:#475569;">
          <li>Terus berinovasi dan antusias menghadapi perubahan.</li>
          <li>Cepat menyesuaikan diri untuk menjadi lebih baik.</li>
          <li>Terus-menerus melakukan perbaikan dan mengikuti perkembangan teknologi.</li>
          <li>Bertindak positif.</li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="about-card h-100">
        <div class="about-value-title mb-1">Kolaboratif</div>
        <ul class="about-bullet mb-0" style="color:#475569;">
          <li>Membangun kerja sama yang strategis.</li>
          <li>Memberi kesempatan kepada berbagai pihak untuk berkontribusi.</li>
          <li>Terbuka dalam bekerja sama untuk menghasilkan nilai tambah.</li>
          <li>Menggerakkan pemanfaatan berbagai sumber daya untuk tujuan bersama.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container about-section pb-5">
  <div class="about-card">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <h4 class="mb-1" style="color:#0f4c81;">Hubungi Kami</h4>
        <p class="mb-0" style="color:#475569;">Informasi lebih lanjut terkait layanan dan kolaborasi.</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('public.directory') }}" class="btn btn-outline-primary">Directory Kantor</a>
        <a href="{{ route('public.peta') }}" class="btn btn-primary" style="background:#0f4c81;border-color:#0f4c81;">Lihat Peta</a>
      </div>
    </div>
  </div>
</div>
@endsection
