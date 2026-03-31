/**
 * Perfect Love Restored - Main JS
 */
(function () {
    'use strict';

    // Mobile menu toggle
    var menuToggle = document.querySelector('.menu-toggle');
    var navigation = document.querySelector('.main-navigation');

    if (menuToggle && navigation) {
        menuToggle.addEventListener('click', function () {
            var isOpen = navigation.classList.toggle('is-open');
            menuToggle.setAttribute('aria-expanded', isOpen);
        });
    }

    // Close mobile menu on link click
    var navLinks = document.querySelectorAll('.main-navigation a');
    navLinks.forEach(function (link) {
        link.addEventListener('click', function () {
            if (navigation.classList.contains('is-open')) {
                navigation.classList.remove('is-open');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // Sticky header shadow on scroll
    var header = document.querySelector('.site-header');
    if (header) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 10) {
                header.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.06)';
            } else {
                header.style.boxShadow = 'none';
            }
        });
    }
})();
