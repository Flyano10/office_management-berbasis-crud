@extends('layouts.public')

@section('title', 'Profil Perusahaan - PLN Icon Plus')
@section('description', 'Profil Perusahaan PLN Icon Plus: ringkasan perusahaan, perjalanan, visi, misi, dan nilai-nilai inti.')

@push('styles')
<style>
 :root {
  --pln-blue: #21618C;
  --pln-blue-dark: #1A4D73;
  --pln-blue-light: #2E86AB;
  --pln-blue-lighter: #E8F4F8;
  --pln-blue-bg: #F5FAFC;
  --text-dark: #1A1A1A;
  --text-gray: #6C757D;
 }

 /* Hero Section - Jangan diubah */
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
 .about-hero .about-subtitle {
  color: rgba(255,255,255,0.92);
  font-family: 'Inter', sans-serif;
  font-weight: 400;
  font-size: 0.95rem;
  line-height: 1.6;
  max-width: 640px;
 }

 /* Content Section Styling */
 .about-section {
  padding: 3rem 0;
  background: #ffffff;
 }

 .about-section:nth-child(even) {
  background: var(--pln-blue-bg);
 }

 .about-card {
  background: #ffffff;
  border: 1px solid rgba(33, 97, 140, 0.15);
  border-radius: 8px;
  padding: 2rem;
  transition: all 0.2s ease;
  box-shadow: 0 2px 8px rgba(33, 97, 140, 0.08);
 }

 .about-card:hover {
  box-shadow: 0 4px 16px rgba(33, 97, 140, 0.12);
  border-color: var(--pln-blue);
 }

 .about-card h4 {
  color: var(--pln-blue);
  font-weight: 700;
  font-size: 1.25rem;
  margin-bottom: 1rem;
 }

 .about-card p {
  color: var(--text-gray);
  line-height: 1.7;
  font-size: 0.9375rem;
  margin-bottom: 1rem;
 }

 .about-card ul {
  color: var(--text-gray);
  line-height: 1.7;
  font-size: 0.9375rem;
 }

 .about-bullet li {
  margin-bottom: 0.75rem;
  padding-left: 0.5rem;
 }

 .about-bullet li::marker {
  color: var(--pln-blue);
  font-weight: 700;
 }

 .about-kpi {
  border: 1px solid rgba(33, 97, 140, 0.15);
  border-radius: 8px;
  padding: 1.5rem;
  background: #ffffff;
  transition: all 0.2s ease;
  box-shadow: 0 2px 8px rgba(33, 97, 140, 0.08);
 }

 .about-kpi:hover {
  box-shadow: 0 4px 16px rgba(33, 97, 140, 0.12);
  border-color: var(--pln-blue);
 }

 .about-kpi h3 {
  color: var(--pln-blue);
  margin: 0 0 0.5rem 0;
  font-weight: 800;
  font-size: 2rem;
  line-height: 1;
 }

 .about-kpi span {
  color: var(--text-gray);
  font-size: 0.875rem;
  font-weight: 600;
 }

 .about-value-title {
  color: var(--pln-blue);
  font-weight: 700;
  font-size: 1.125rem;
  margin-bottom: 0.75rem;
 }

 /* Scroll Animation */
 .fade-in-up {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s ease, transform 0.6s ease;
 }

 .fade-in-up.visible {
  opacity: 1;
  transform: translateY(0);
 }

 .fade-in-left {
  opacity: 0;
  transform: translateX(-30px);
  transition: opacity 0.6s ease, transform 0.6s ease;
 }

 .fade-in-left.visible {
  opacity: 1;
  transform: translateX(0);
 }

 .fade-in-right {
  opacity: 0;
  transform: translateX(30px);
  transition: opacity 0.6s ease, transform 0.6s ease;
 }

 .fade-in-right.visible {
  opacity: 1;
  transform: translateX(0);
 }

 /* Button Styling */
 .btn-primary {
  background: var(--pln-blue);
  border-color: var(--pln-blue);
  border-radius: 8px;
  padding: 0.625rem 1.5rem;
  font-weight: 600;
  transition: all 0.2s ease;
 }

 .btn-primary:hover {
  background: var(--pln-blue-dark);
  border-color: var(--pln-blue-dark);
 }

 .btn-outline-primary {
  border-color: var(--pln-blue);
  color: var(--pln-blue);
  border-radius: 8px;
  padding: 0.625rem 1.5rem;
  font-weight: 600;
  transition: all 0.2s ease;
 }

 .btn-outline-primary:hover {
  background: var(--pln-blue);
  border-color: var(--pln-blue);
  color: white;
 }

 /* Responsive */
 @media (max-width: 768px) {
  .about-section {
   padding: 2rem 0;
  }

  .about-card {
   padding: 1.5rem;
  }

  .about-kpi {
   padding: 1.25rem;
  }
 }
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
  <div class="row g-4">
    <div class="col-md-8">
      <div class="about-card fade-in-left">
        <h4>Ringkasan</h4>
        <p>PT PLN Icon Plus merupakan Entitas Anak PT PLN (Persero). PLN Icon Plus didirikan pada tahun 2000 sebagai bagian dari PT PLN (Persero) untuk memenuhi kebutuhan jaringan telekomunikasi PLN. Awalnya, perusahaan ini fokus pada operasi Network Operation Centre di Gandul, Cinere, untuk mendukung kelistrikan PLN.</p>
        <p>Seiring berkembangnya kebutuhan akan jaringan telekomunikasi yang lebih luas dan andal, PLN Icon Plus mulai menyalurkan kapasitas jaringan serat optik PLN ke publik, khususnya untuk perusahaan dan lembaga yang membutuhkan konektivitas ekstensif.</p>
        <p>Pada 2008, PLN Icon Plus mulai memperluas jaringan ke wilayah terpencil di Indonesia dengan memanfaatkan hak jaringan ketenagalistrikan PLN, yaitu Right of Way (RoW). Seiring waktu, PLN Icon Plus terus berinovasi dan memperkenalkan produk serta layanan berbasis teknologi terkini.</p>
        <p class="mb-0">Pada 21 September 2022, PLN Icon Plus menjadi Subholding PT PLN (Persero) dengan fokus baru pada tiga bisnis utama: kelistrikan, layanan konektivitas, dan layanan IT, serta berperan penting dalam inisiatif Beyond kWh yang mencakup pengembangan bisnis di luar sektor kelistrikan.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="about-kpi mb-3 fade-in-right">
        <h3>120+</h3>
        <span>Kantor dipetakan</span>
      </div>
      <div class="about-kpi fade-in-right">
        <h3>24/7</h3>
        <span>Kesiapan layanan</span>
      </div>
    </div>
  </div>
</div>

<div class="container about-section">
  <div class="row g-4">
    <div class="col-md-6">
      <div class="about-card fade-in-left">
        <h4>Visi</h4>
        <p>Menjadi pemimpin penyedia smart connectivity solutions, digital dan green energy yang terintegrasi untuk mendukung transisi energi di Indonesia.</p>
      </div>
    </div>
    <div class="col-md-6">
      <div class="about-card fade-in-right">
        <h4>Misi</h4>
        <ul class="about-bullet mb-0">
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
  <div class="about-card fade-in-up">
    <h4 class="mb-3">Sejarah Perusahaan PLN Icon Plus</h4>
    <div class="row g-4">
      <div class="col-md-6">
        <ul class="about-bullet mb-0">
          <li><strong>2000</strong> — PT Indonesia Comnets Plus berdiri.</li>
          <li><strong>2005</strong> — Memperoleh izin prinsip Internet Telephony untuk keperluan publik.</li>
          <li><strong>2007</strong> — Memperoleh izin prinsip Penyelenggara Jasa Interkoneksi Internet (NAP) dan penyelenggara Jasa Internet Telephony untuk Keperluan Publik.</li>
          <li><strong>2020</strong> — Memiliki lisensi Jasa Telekomunikasi Layanan Call Center dan meluncurkan aplikasi layanan pelanggan New PLN Mobile.</li>
        </ul>
      </div>
      <div class="col-md-6">
        <ul class="about-bullet mb-0">
          <li><strong>2021</strong> — Rebranding Stroomnet (internet broadband ICON+) menjadi ICONNET.</li>
          <li><strong>2022</strong> — Transformasi ICON+ menjadi PLN Icon Plus sebagai Subholding PT PLN (Persero).</li>
          <li><strong>2023</strong> — ICONNET mencapai 1 juta pelanggan dan PLN Icon Plus go internasional melayani Fiber Optic di Timor Leste.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container about-section">
  <h4 class="mb-4 fade-in-up" style="color:var(--pln-blue);">Nilai-Nilai Perusahaan</h4>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="about-card h-100 fade-in-up">
        <div class="about-value-title">Amanah</div>
        <ul class="about-bullet mb-0">
          <li>Memegang teguh kepercayaan yang diberikan.</li>
          <li>Memenuhi janji dan komitmen.</li>
          <li>Bertanggung jawab atas tugas, keputusan, dan tindakan yang dilakukan.</li>
          <li>Berpegang teguh pada nilai moral dan etika.</li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="about-card h-100 fade-in-up">
        <div class="about-value-title">Kompeten</div>
        <ul class="about-bullet mb-0">
          <li>Terus belajar dan mengembangkan kapabilitas.</li>
          <li>Meningkatkan kompetensi diri untuk menjawab tantangan.</li>
          <li>Membantu orang lain belajar.</li>
          <li>Menyelesaikan tugas dengan kualitas terbaik.</li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="about-card h-100 fade-in-up">
        <div class="about-value-title">Harmonis</div>
        <ul class="about-bullet mb-0">
          <li>Saling peduli dan menghargai perbedaan.</li>
          <li>Menghargai setiap orang, apa pun latar belakangnya.</li>
          <li>Suka menolong orang lain.</li>
          <li>Membangun kinerja yang kondusif.</li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="about-card h-100 fade-in-up">
        <div class="about-value-title">Loyal</div>
        <ul class="about-bullet mb-0">
          <li>Berdedikasi dan mengutamakan kepentingan bangsa dan negara.</li>
          <li>Menjaga nama baik sesama karyawan, pimpinan, BUMN, dan negara.</li>
          <li>Rela berkorban untuk mencapai tujuan yang lebih besar.</li>
          <li>Patuh kepada pimpinan sepanjang tidak bertentangan dengan hukum dan etika.</li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="about-card h-100 fade-in-up">
        <div class="about-value-title">Adaptif</div>
        <ul class="about-bullet mb-0">
          <li>Terus berinovasi dan antusias menghadapi perubahan.</li>
          <li>Cepat menyesuaikan diri untuk menjadi lebih baik.</li>
          <li>Terus-menerus melakukan perbaikan dan mengikuti perkembangan teknologi.</li>
          <li>Bertindak positif.</li>
        </ul>
      </div>
    </div>
    <div class="col-md-4">
      <div class="about-card h-100 fade-in-up">
        <div class="about-value-title">Kolaboratif</div>
        <ul class="about-bullet mb-0">
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
  <div class="about-card fade-in-up">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
      <div>
        <h4 class="mb-1">Hubungi Kami</h4>
        <p class="mb-0">Informasi lebih lanjut terkait layanan dan kolaborasi.</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('public.help') }}" class="btn btn-outline-primary">Kontak Kami</a>
        <a href="{{ route('public.peta') }}" class="btn btn-primary">Lihat Peta</a>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
// Scroll Animation
document.addEventListener('DOMContentLoaded', function() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, observerOptions);

  // Observe all elements with animation classes
  document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right').forEach(el => {
    observer.observe(el);
  });
});
</script>
@endpush
@endsection
