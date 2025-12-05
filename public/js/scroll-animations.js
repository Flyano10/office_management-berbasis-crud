// Fungsi untuk mengecek apakah elemen ada di viewport
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.75 &&
        rect.bottom >= 0
    );
}

// Fungsi untuk menangani animasi scroll
function handleScrollAnimations() {
    const elements = document.querySelectorAll('.fade-in, .hero-content h1, .hero-content p, .hero-actions, .system-card');
    
    elements.forEach(element => {
        if (isInViewport(element)) {
            element.classList.add('animate');
        }
    });
}

// Jalankan saat halaman dimuat
window.addEventListener('load', () => {
    // Trigger animasi untuk hero section
    const heroContent = document.querySelector('.hero-content');
    if (heroContent) {
        heroContent.querySelector('h1').classList.add('animate');
        setTimeout(() => {
            heroContent.querySelector('p').classList.add('animate');
        }, 200);
        setTimeout(() => {
            heroContent.querySelector('.hero-actions').classList.add('animate');
        }, 400);
    }
    
    // Tambahkan event listener untuk scroll
    window.addEventListener('scroll', handleScrollAnimations);
    
    // Jalankan sekali saat pertama kali dimuat
    handleScrollAnimations();
});

// Smooth scroll untuk link anchor
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80, // Sesuaikan dengan tinggi header
                behavior: 'smooth'
            });
        }
    });
});
