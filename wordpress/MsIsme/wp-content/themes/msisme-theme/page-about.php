<?php get_header(); ?>

<main>
    <section class="page-hero">
        <div class="container">
            <h1>About Ms. Isme</h1>
            <p>The heart behind the mission</p>
        </div>
    </section>

    <section class="section section-light">
        <div class="container">
            <div class="about-content">
                <div class="about-photo">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/about-placeholder.svg" alt="Ms. Isme">
                </div>
                <div class="about-bio">
                    <h2>Hello, I'm Ms. Isme</h2>
                    <p>With years of experience serving communities through events, counseling, and ministry, I have made it my life's work to uplift, inspire, and bring joy to those around me.</p>
                    <p>My journey has taken me from planning intimate gatherings to hosting large-scale events, from one-on-one counseling sessions to speaking before hundreds. Through it all, one truth has remained constant: joy is a choice, and it is available to each of us.</p>
                    <p>I believe in the power of connection, the beauty of celebration, and the strength that comes from walking through life's challenges with purpose and faith.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-cream">
        <div class="container">
            <div class="philosophy-section">
                <h2>"Count It All Joy"</h2>
                <div class="decorative-line" style="margin: 0 auto 30px;"></div>
                <p>This isn't just a tagline — it's a way of life. Inspired by the timeless wisdom of James 1:2, "Count it all joy" reminds us that every season has purpose. Whether you're celebrating a milestone, navigating a transition, or building something new, there is joy to be found in the journey.</p>
                <p>This philosophy is the foundation of everything I do. It shapes how I plan events, how I counsel, how I speak, and how I show up for the people I serve. My goal is to help you find that joy in your own story.</p>
            </div>
        </div>
    </section>

    <section class="cta-banner">
        <div class="container">
            <h2>Let's Work Together</h2>
            <p>Ready to start your journey? I'd love to hear from you.</p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-light">Get In Touch</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
