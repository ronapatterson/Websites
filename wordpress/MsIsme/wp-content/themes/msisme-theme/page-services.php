<?php get_header(); ?>

<main>
    <section class="page-hero">
        <div class="container">
            <h1>Services</h1>
            <p>Bringing joy, purpose, and professionalism to every engagement</p>
        </div>
    </section>

    <section class="section section-light">
        <div class="container">
            <!-- Event Planning -->
            <div class="service-row">
                <div class="service-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/service-placeholder.svg" alt="Event Planning">
                </div>
                <div class="service-text">
                    <h3>Event Planning</h3>
                    <p>From intimate gatherings to grand celebrations, I bring your vision to life with meticulous attention to detail and a personal touch. Every event tells a story, and I'm here to make sure yours is unforgettable.</p>
                    <p>Services include venue selection, vendor coordination, timeline management, decor planning, and day-of coordination. Whether it's a birthday, anniversary, church event, or community gathering — I've got you covered.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary">Get in Touch</a>
                </div>
            </div>

            <!-- Event MC -->
            <div class="service-row reverse">
                <div class="service-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/service-placeholder.svg" alt="Event MC">
                </div>
                <div class="service-text">
                    <h3>Event MC</h3>
                    <p>A great MC sets the tone for the entire event. I bring energy, warmth, and professionalism to every stage, keeping your guests engaged and your program flowing seamlessly from start to finish.</p>
                    <p>Whether it's a corporate gala, wedding reception, community event, or celebration of life — I tailor my approach to match your event's unique vibe and audience.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary">Get in Touch</a>
                </div>
            </div>

            <!-- Speaking Engagement -->
            <div class="service-row">
                <div class="service-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/service-placeholder.svg" alt="Speaking Engagement">
                </div>
                <div class="service-text">
                    <h3>Speaking Engagement</h3>
                    <p>Words have power, and I use mine to inspire, motivate, and uplift. Whether it's a keynote address, workshop, panel discussion, or conference breakout session, I deliver messages that resonate long after the event ends.</p>
                    <p>Topics include personal growth, joy and resilience, women's empowerment, faith and purpose, leadership, and community building.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary">Get in Touch</a>
                </div>
            </div>

            <!-- Marriage Pastor -->
            <div class="service-row reverse">
                <div class="service-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/service-placeholder.svg" alt="Marriage Pastor">
                </div>
                <div class="service-text">
                    <h3>Marriage Pastor</h3>
                    <p>Your wedding day is one of the most important days of your life. I officiate ceremonies with heart, meaning, and a personal touch that celebrates your unique love story and honors your commitment to each other.</p>
                    <p>I work closely with each couple to craft a ceremony that reflects your values, traditions, and personality — whether traditional, contemporary, or a beautiful blend of both.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary">Get in Touch</a>
                </div>
            </div>

            <!-- Counseling -->
            <div class="service-row">
                <div class="service-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/service-placeholder.svg" alt="Counseling">
                </div>
                <div class="service-text">
                    <h3>Counseling</h3>
                    <p>Life brings seasons of challenge, transition, and growth. I provide compassionate, faith-informed counseling for individuals and couples navigating difficult times, offering a safe space for honest conversation and practical guidance.</p>
                    <p>Areas of focus include relationship challenges, life transitions, grief and loss, personal growth, pre-marital counseling, and family dynamics.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary">Get in Touch</a>
                </div>
            </div>

            <!-- Social Media Marketing -->
            <div class="service-row reverse">
                <div class="service-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/service-placeholder.svg" alt="Social Media Marketing">
                </div>
                <div class="service-text">
                    <h3>Social Media Marketing</h3>
                    <p>In today's digital world, your online presence matters. I help individuals, ministries, and small businesses build authentic social media strategies that connect with their audience and amplify their message.</p>
                    <p>Services include content strategy, platform management, brand voice development, community engagement, and analytics review. Let's tell your story online with purpose and authenticity.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary">Get in Touch</a>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-banner">
        <div class="container">
            <h2>Ready to Get Started?</h2>
            <p>Let's create something beautiful together. Reach out today.</p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-light">Book a Consultation</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
