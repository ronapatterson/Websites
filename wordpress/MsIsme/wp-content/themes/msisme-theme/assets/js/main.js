document.addEventListener('DOMContentLoaded', function () {

    // ========== Mobile Menu Toggle ==========
    var menuToggle = document.querySelector('.menu-toggle');
    var mainNav = document.querySelector('.main-nav');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function () {
            menuToggle.classList.toggle('active');
            mainNav.classList.toggle('active');
        });

        // Close menu when a link is clicked
        var navLinks = mainNav.querySelectorAll('a');
        navLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                menuToggle.classList.remove('active');
                mainNav.classList.remove('active');
            });
        });
    }

    // ========== Testimonial Carousel ==========
    var track = document.querySelector('.testimonial-track');
    var dots = document.querySelectorAll('.carousel-dots .dot');

    if (track && dots.length > 0) {
        var currentSlide = 0;
        var totalSlides = dots.length;
        var autoplayInterval = null;

        function goToSlide(index) {
            currentSlide = index;
            track.style.transform = 'translateX(-' + (currentSlide * 100) + '%)';
            dots.forEach(function (dot, i) {
                dot.classList.toggle('active', i === currentSlide);
            });
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                var index = parseInt(dot.getAttribute('data-index'));
                goToSlide(index);
                resetAutoplay();
            });
        });

        function nextSlide() {
            goToSlide((currentSlide + 1) % totalSlides);
        }

        function resetAutoplay() {
            clearInterval(autoplayInterval);
            autoplayInterval = setInterval(nextSlide, 5000);
        }

        // Start autoplay
        autoplayInterval = setInterval(nextSlide, 5000);
    }

    // ========== Header Scroll Effect ==========
    var header = document.querySelector('.site-header');

    if (header) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                header.style.boxShadow = '0 4px 30px rgba(45, 27, 14, 0.12)';
            } else {
                header.style.boxShadow = '0 2px 20px rgba(45, 27, 14, 0.08)';
            }
        });
    }

    // ========== Smooth Scroll for Anchor Links ==========
    var anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
            var targetId = link.getAttribute('href');
            if (targetId === '#') return;
            var target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
