/* Doctor Mommies - Main JavaScript */

(function() {
    'use strict';

    // Mobile hamburger menu
    const hamburger = document.getElementById('hamburger');
    const nav = document.getElementById('main-nav');

    if (hamburger && nav) {
        hamburger.addEventListener('click', function() {
            const isOpen = nav.style.display === 'block';
            nav.style.display = isOpen ? '' : 'block';
            nav.style.position = isOpen ? '' : 'absolute';
            nav.style.top = isOpen ? '' : '100%';
            nav.style.left = isOpen ? '' : '0';
            nav.style.right = isOpen ? '' : '0';
            nav.style.background = isOpen ? '' : '#1F1346';
            nav.style.padding = isOpen ? '' : '20px';
            nav.style.zIndex = isOpen ? '' : '999';
            hamburger.setAttribute('aria-expanded', !isOpen);
        });
    }

    // Newsletter form submission
    const form = document.getElementById('newsletter-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const emailInput = document.getElementById('newsletter-email');
            const email = emailInput ? emailInput.value.trim() : '';
            const btn = form.querySelector('button[type="submit"]');

            if (!email) return;

            const originalText = btn.textContent;
            btn.textContent = 'Subscribing...';
            btn.disabled = true;

            if (typeof drMommiesData !== 'undefined' && drMommiesData.ajaxUrl) {
                const formData = new FormData();
                formData.append('action', 'newsletter_signup');
                formData.append('email', email);
                formData.append('nonce', drMommiesData.nonce);

                fetch(drMommiesData.ajaxUrl, {
                    method: 'POST',
                    body: formData,
                })
                .then(r => r.json())
                .then(data => {
                    showNotification(data.data.message, data.success ? 'success' : 'error');
                    if (data.success) {
                        form.reset();
                    }
                })
                .catch(() => {
                    showNotification('Something went wrong. Please try again.', 'error');
                })
                .finally(() => {
                    btn.textContent = originalText;
                    btn.disabled = false;
                });
            } else {
                // Fallback if AJAX not available
                setTimeout(() => {
                    showNotification('Thank you for subscribing!', 'success');
                    form.reset();
                    btn.textContent = originalText;
                    btn.disabled = false;
                }, 800);
            }
        });
    }

    // Notification helper
    function showNotification(message, type) {
        const existing = document.querySelector('.dr-notification');
        if (existing) existing.remove();

        const el = document.createElement('div');
        el.className = 'dr-notification';
        el.textContent = message;
        el.style.cssText = [
            'position:fixed',
            'bottom:90px',
            'right:30px',
            'padding:14px 24px',
            'border-radius:12px',
            'font-size:15px',
            'font-weight:600',
            'z-index:10000',
            'box-shadow:0 4px 20px rgba(0,0,0,0.2)',
            'animation:slideInRight 0.3s ease',
            type === 'success'
                ? 'background:#673de6;color:white;'
                : 'background:#e53e3e;color:white;',
        ].join(';');

        document.body.appendChild(el);
        setTimeout(() => { if (el.parentNode) el.remove(); }, 4000);
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Intersection Observer for scroll animations
    const animateElements = document.querySelectorAll('.recipe-card, .blog-card, .testimonial-card, .stat-card');
    if ('IntersectionObserver' in window && animateElements.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        animateElements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(el);
        });
    }

    // Add animation keyframes
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }
    `;
    document.head.appendChild(style);

})();

    // Sticky header shadow on scroll
    const header = document.querySelector('.site-header');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.style.boxShadow = '0 4px 20px rgba(31,19,70,0.3)';
            } else {
                header.style.boxShadow = '';
            }
        });
    }

    // Save/unsave recipe (localStorage)
    const saveBtn = document.querySelector('.btn-save-recipe');
    if (saveBtn) {
        const recipeId = saveBtn.getAttribute('data-id');
        const saved = JSON.parse(localStorage.getItem('savedRecipes') || '[]');
        if (saved.includes(recipeId)) {
            saveBtn.textContent = '♥ Saved';
            saveBtn.style.background = 'var(--color-primary-purple)';
            saveBtn.style.color = 'white';
        }
        saveBtn.addEventListener('click', function() {
            const list = JSON.parse(localStorage.getItem('savedRecipes') || '[]');
            const idx = list.indexOf(recipeId);
            if (idx === -1) {
                list.push(recipeId);
                saveBtn.textContent = '♥ Saved';
                saveBtn.style.background = 'var(--color-primary-purple)';
                saveBtn.style.color = 'white';
                showNotification('Recipe saved!', 'success');
            } else {
                list.splice(idx, 1);
                saveBtn.textContent = '♡ Save';
                saveBtn.style.background = '';
                saveBtn.style.color = '';
                showNotification('Recipe removed from saved.', 'error');
            }
            localStorage.setItem('savedRecipes', JSON.stringify(list));
        });
    }

    // Reveal on scroll for .reveal elements
    const reveals = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && reveals.length) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        reveals.forEach(el => revealObserver.observe(el));
    }

    // ===========================
    // RECIPE RATING SYSTEM
    // ===========================
    var ratingWidget = document.querySelector('.recipe-rating-widget');
    if (ratingWidget) {
        var interactiveStars = ratingWidget.querySelectorAll('.rating-interactive .star');
        var reviewFormInline = document.getElementById('review-form-inline');
        var btnSubmitRating = document.getElementById('btn-submit-rating');
        var btnSkipReview = document.getElementById('btn-skip-review');
        var ratingMessage = ratingWidget.querySelector('.rating-message');
        var selectedRating = 0;

        // Hover preview
        interactiveStars.forEach(function(star) {
            star.addEventListener('mouseenter', function() {
                var val = parseInt(this.getAttribute('data-value'));
                interactiveStars.forEach(function(s) {
                    s.classList.toggle('preview', parseInt(s.getAttribute('data-value')) <= val);
                });
            });
            star.addEventListener('mouseleave', function() {
                interactiveStars.forEach(function(s) {
                    s.classList.remove('preview');
                    s.classList.toggle('selected', parseInt(s.getAttribute('data-value')) <= selectedRating);
                });
            });
            star.addEventListener('click', function() {
                selectedRating = parseInt(this.getAttribute('data-value'));
                interactiveStars.forEach(function(s) {
                    s.classList.toggle('selected', parseInt(s.getAttribute('data-value')) <= selectedRating);
                });
                // Show review form
                if (reviewFormInline) {
                    reviewFormInline.style.display = 'block';
                }
            });
        });

        function submitRating(reviewText) {
            if (!selectedRating) return;
            var formData = new FormData();
            formData.append('action', 'rate_recipe');
            formData.append('recipe_id', ratingWidget.getAttribute('data-recipe-id'));
            formData.append('rating', selectedRating);
            formData.append('review_text', reviewText || '');
            formData.append('nonce', drMommiesData.nonce);

            fetch(drMommiesData.ajaxUrl, { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        // Update average display using safe DOM methods
                        var avgDisplay = ratingWidget.querySelector('.rating-average');
                        if (avgDisplay) {
                            updateStarsDisplay(avgDisplay, data.data.average, data.data.count);
                        }
                        // Disable interaction
                        var interactive = ratingWidget.querySelector('.rating-interactive');
                        if (interactive) {
                            interactive.textContent = '';
                            var label = document.createElement('span');
                            label.className = 'rating-label';
                            label.textContent = 'You rated this ' + selectedRating + ' star' + (selectedRating > 1 ? 's' : '');
                            interactive.appendChild(label);
                        }
                        if (reviewFormInline) reviewFormInline.style.display = 'none';

                        var msg = data.data.needsApproval
                            ? 'Thanks! Your rating is recorded. Your review is pending approval.'
                            : 'Thanks for rating this recipe!';
                        showRatingMessage(msg, 'success');
                    } else {
                        showRatingMessage(data.data.message || 'Something went wrong.', 'error');
                    }
                })
                .catch(function() {
                    showRatingMessage('Something went wrong. Please try again.', 'error');
                });
        }

        if (btnSubmitRating) {
            btnSubmitRating.addEventListener('click', function() {
                var reviewText = document.getElementById('review-text').value.trim();
                submitRating(reviewText);
            });
        }

        if (btnSkipReview) {
            btnSkipReview.addEventListener('click', function() {
                submitRating('');
            });
        }

        function updateStarsDisplay(container, average, count) {
            container.textContent = '';
            var wrapper = document.createElement('span');
            wrapper.className = 'recipe-stars-display';
            for (var i = 1; i <= 5; i++) {
                var starEl = document.createElement('span');
                starEl.className = 'star ' + (i <= Math.round(average) ? 'filled' : 'empty');
                starEl.textContent = '\u2605';
                wrapper.appendChild(starEl);
            }
            var countEl = document.createElement('span');
            countEl.className = 'rating-count';
            countEl.textContent = ' (' + count + ')';
            wrapper.appendChild(countEl);
            container.appendChild(wrapper);
        }

        function showRatingMessage(text, type) {
            if (ratingMessage) {
                ratingMessage.textContent = text;
                ratingMessage.className = 'rating-message ' + type;
                ratingMessage.style.display = 'block';
            }
        }
    }

