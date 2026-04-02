<?php get_header(); ?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="decorative-line"></div>
            <h1>Count It All Joy</h1>
            <p class="subtitle">Bringing warmth, wisdom, and purpose to every moment</p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="btn btn-primary">Explore Services</a>
        </div>
    </section>

    <!-- Services Preview -->
    <section class="section section-light">
        <div class="container">
            <div class="section-title">
                <h2>How I Can Help</h2>
                <div class="decorative-line"></div>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <span class="icon">&#127881;</span>
                    <h3>Event Planning</h3>
                    <p>Crafting memorable events from concept to execution, ensuring every detail reflects your vision.</p>
                </div>
                <div class="service-card">
                    <span class="icon">&#127908;</span>
                    <h3>Event MC</h3>
                    <p>Energizing and hosting events with warmth, humor, and professionalism that keeps your guests engaged.</p>
                </div>
                <div class="service-card">
                    <span class="icon">&#127897;</span>
                    <h3>Speaking Engagement</h3>
                    <p>Inspiring audiences with purpose-driven messages that resonate and create lasting impact.</p>
                </div>
                <div class="service-card">
                    <span class="icon">&#128141;</span>
                    <h3>Marriage Pastor</h3>
                    <p>Officiating ceremonies with heart, meaning, and a personal touch that celebrates your love story.</p>
                </div>
                <div class="service-card">
                    <span class="icon">&#128156;</span>
                    <h3>Counseling</h3>
                    <p>Guiding individuals and couples through life's challenges with compassion and practical wisdom.</p>
                </div>
                <div class="service-card">
                    <span class="icon">&#128241;</span>
                    <h3>Social Media Marketing</h3>
                    <p>Building authentic online presence and engagement that connects with your audience.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Preview -->
    <section class="section section-cream">
        <div class="container">
            <div class="about-preview">
                <div class="image-side">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/about-placeholder.svg" alt="Ms. Isme">
                </div>
                <div class="text-side">
                    <h2>Meet Ms. Isme</h2>
                    <p>With a heart for people and a passion for purpose, Ms. Isme brings joy, wisdom, and authenticity to everything she does. Whether planning your dream event, speaking to inspire, or walking alongside you through life's journey — she's here for you.</p>
                    <p>Her philosophy is simple: count it all joy. Every challenge, every celebration, every moment of growth is an opportunity to find meaning and beauty.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="section section-light testimonials">
        <div class="container">
            <div class="section-title">
                <h2>Kind Words</h2>
                <div class="decorative-line"></div>
            </div>
            <div class="testimonial-carousel">
                <div class="testimonial-track">
                    <div class="testimonial-slide">
                        <p class="quote">Ms. Isme brought such warmth and joy to our wedding ceremony. She made it personal, meaningful, and unforgettable. We couldn't have asked for a better pastor.</p>
                        <p class="author">Sarah & James</p>
                        <p class="role">Wedding Ceremony</p>
                    </div>
                    <div class="testimonial-slide">
                        <p class="quote">Her energy as an MC is unmatched. She kept our entire corporate event flowing smoothly while making everyone feel welcome and engaged. Truly a professional.</p>
                        <p class="author">Michael T.</p>
                        <p class="role">Corporate Event</p>
                    </div>
                    <div class="testimonial-slide">
                        <p class="quote">The counseling sessions with Ms. Isme changed my perspective on so many things. Her wisdom and compassion created a safe space for real growth and healing.</p>
                        <p class="author">Ari W.</p>
                        <p class="role">Counseling Client</p>
                    </div>
                </div>
                <div class="carousel-dots">
                    <button class="dot active" data-index="0" aria-label="Testimonial 1"></button>
                    <button class="dot" data-index="1" aria-label="Testimonial 2"></button>
                    <button class="dot" data-index="2" aria-label="Testimonial 3"></button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <section class="cta-banner">
        <div class="container">
            <h2>Ready to Get Started?</h2>
            <p>Let's create something beautiful together. Reach out today and let's talk about how I can serve you.</p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-light">Book a Consultation</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
