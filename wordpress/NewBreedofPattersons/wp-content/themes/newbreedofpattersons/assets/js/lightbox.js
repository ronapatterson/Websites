(function () {
    'use strict';

    // Create lightbox DOM using safe DOM methods
    var overlay = document.createElement('div');
    overlay.className = 'nbop-lightbox';

    var closeBtn = document.createElement('button');
    closeBtn.className = 'nbop-lightbox-close';
    closeBtn.setAttribute('aria-label', 'Close');
    closeBtn.textContent = '\u00D7';

    var img = document.createElement('img');
    img.src = '';
    img.alt = '';

    overlay.appendChild(closeBtn);
    overlay.appendChild(img);
    document.body.appendChild(overlay);

    function openLightbox(src, alt) {
        img.src = src;
        img.alt = alt || '';
        overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        overlay.classList.remove('is-open');
        document.body.style.overflow = '';
        img.src = '';
    }

    document.querySelectorAll('.gallery-item').forEach(function (item) {
        item.addEventListener('click', function () {
            var fullSrc = this.getAttribute('data-full');
            var imgEl = this.querySelector('img');
            var altText = imgEl ? imgEl.alt : '';
            openLightbox(fullSrc, altText);
        });
    });

    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) {
            closeLightbox();
        }
    });

    closeBtn.addEventListener('click', closeLightbox);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
})();
