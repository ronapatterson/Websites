# Recipe Ratings, Reviews & FAQ Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add star ratings with optional reviews, recipe-specific FAQ sections, sort/filter by rating, and an admin moderation page to the DrMommies WordPress theme.

**Architecture:** Custom database tables (`wp_recipe_ratings`, `wp_recipe_faqs`) with cached post meta for fast reads. AJAX handlers for all user interactions. Admin moderation page under the Recipes menu. Client-side sort/filter using data attributes on recipe cards.

**Tech Stack:** PHP (WordPress theme functions), MySQL (dbDelta), vanilla JavaScript, CSS

**Spec:** `docs/superpowers/specs/2026-04-08-recipe-star-ratings-design.md`

---

## File Map

| File | Action | Responsibility |
|---|---|---|
| `wp-content/themes/drmommies/functions.php` | Modify | Table creation, AJAX handlers, helper functions, admin page |
| `wp-content/themes/drmommies/single-recipe.php` | Modify | Rating widget, review form, reviews section, FAQ section, JSON-LD |
| `wp-content/themes/drmommies/page-recipes.php` | Modify | Star display on cards, data attributes, sort dropdown, rating filter row |
| `wp-content/themes/drmommies/archive-recipe.php` | Modify | Star display on cards, data attributes |
| `wp-content/themes/drmommies/style.css` | Modify | All new component styles |
| `wp-content/themes/drmommies/js/main.js` | Modify | Rating AJAX, FAQ AJAX, accordion, sort/filter logic |

---

### Task 1: Database Tables & Helper Functions

**Files:**
- Modify: `wp-content/themes/drmommies/functions.php:201-213` (existing `drmommies_create_tables`)
- Modify: `wp-content/themes/drmommies/functions.php:241-249` (after `drmommies_get_recipe_meta`)

- [ ] **Step 1: Add both tables to `drmommies_create_tables()`**

In `functions.php`, replace the existing `drmommies_create_tables` function (lines 201-214) with:

```php
function drmommies_create_tables() {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // Newsletter subscribers table
    $table1 = $wpdb->prefix . 'newsletter_subscribers';
    dbDelta("CREATE TABLE IF NOT EXISTS $table1 (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL UNIQUE,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id)
    ) $charset;");

    // Recipe ratings table
    $table2 = $wpdb->prefix . 'recipe_ratings';
    dbDelta("CREATE TABLE IF NOT EXISTS $table2 (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        recipe_id BIGINT UNSIGNED NOT NULL,
        user_id BIGINT UNSIGNED NOT NULL,
        rating TINYINT UNSIGNED NOT NULL,
        review_text TEXT NULL,
        approved TINYINT UNSIGNED NOT NULL DEFAULT 1,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY recipe_user (recipe_id, user_id),
        KEY recipe_id (recipe_id),
        KEY approved (approved)
    ) $charset;");

    // Recipe FAQs table
    $table3 = $wpdb->prefix . 'recipe_faqs';
    dbDelta("CREATE TABLE IF NOT EXISTS $table3 (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        recipe_id BIGINT UNSIGNED NOT NULL,
        user_id BIGINT UNSIGNED NOT NULL,
        question TEXT NOT NULL,
        answer TEXT NULL,
        approved TINYINT UNSIGNED NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY recipe_id (recipe_id),
        KEY approved (approved)
    ) $charset;");
}
```

- [ ] **Step 2: Add helper functions after `drmommies_get_recipe_meta`**

Append after line 249 in `functions.php`:

```php
// Helper: get cached recipe rating data
function drmommies_get_recipe_rating($post_id) {
    return [
        'average' => (float) get_post_meta($post_id, '_rating_average', true) ?: 0,
        'count'   => (int) get_post_meta($post_id, '_rating_count', true) ?: 0,
        'review_count' => (int) get_post_meta($post_id, '_review_count', true) ?: 0,
    ];
}

// Helper: recalculate and cache rating meta from the ratings table
function drmommies_update_rating_cache($recipe_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'recipe_ratings';

    $stats = $wpdb->get_row($wpdb->prepare(
        "SELECT AVG(rating) as avg_rating, COUNT(*) as total_count FROM $table WHERE recipe_id = %d",
        $recipe_id
    ));
    $review_count = (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE recipe_id = %d AND review_text IS NOT NULL AND review_text != '' AND approved = 1",
        $recipe_id
    ));

    $average = $stats->avg_rating ? round((float) $stats->avg_rating, 1) : 0;
    $count = (int) $stats->total_count;

    update_post_meta($recipe_id, '_rating_average', $average);
    update_post_meta($recipe_id, '_rating_count', $count);
    update_post_meta($recipe_id, '_review_count', $review_count);

    return ['average' => $average, 'count' => $count, 'review_count' => $review_count];
}

// Helper: get approved reviews for a recipe
function drmommies_get_approved_reviews($post_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'recipe_ratings';

    return $wpdb->get_results($wpdb->prepare(
        "SELECT r.rating, r.review_text, r.created_at, u.display_name
         FROM $table r
         JOIN {$wpdb->users} u ON r.user_id = u.ID
         WHERE r.recipe_id = %d AND r.review_text IS NOT NULL AND r.review_text != '' AND r.approved = 1
         ORDER BY r.created_at DESC",
        $post_id
    ));
}

// Helper: get approved FAQs for a recipe
function drmommies_get_approved_faqs($post_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'recipe_faqs';

    return $wpdb->get_results($wpdb->prepare(
        "SELECT f.question, f.answer, f.created_at, u.display_name
         FROM $table f
         JOIN {$wpdb->users} u ON f.user_id = u.ID
         WHERE f.recipe_id = %d AND f.approved = 1
         ORDER BY f.created_at DESC",
        $post_id
    ));
}

// Helper: render read-only stars HTML
function drmommies_render_stars_html($average, $count) {
    $html = '<span class="recipe-stars-display">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= round($average)) {
            $html .= '<span class="star filled">&#9733;</span>';
        } else {
            $html .= '<span class="star empty">&#9733;</span>';
        }
    }
    $html .= ' <span class="rating-count">(' . intval($count) . ')</span></span>';
    return $html;
}
```

- [ ] **Step 3: Create the tables by re-running the setup**

Run the table creation manually since the hook only fires on theme switch:

```bash
cd /home/dev/repos/Websites/wordpress/DrMommies && wp eval 'drmommies_create_tables();'
```

Expected: No output (success). Verify:

```bash
wp db query "SHOW TABLES LIKE '%recipe_ratings%';"
wp db query "SHOW TABLES LIKE '%recipe_faqs%';"
```

Expected: Both table names appear.

- [ ] **Step 4: Commit**

```bash
git add wp-content/themes/drmommies/functions.php
git commit -m "feat: add recipe_ratings and recipe_faqs tables with helper functions"
```

---

### Task 2: AJAX Handlers (Rate Recipe & Submit FAQ)

**Files:**
- Modify: `wp-content/themes/drmommies/functions.php` (append after the helper functions added in Task 1)

- [ ] **Step 1: Add the rate_recipe AJAX handler**

Append to `functions.php`:

```php
// AJAX: Rate a recipe (logged-in users only)
function drmommies_rate_recipe() {
    check_ajax_referer('drmommies_nonce', 'nonce');

    $recipe_id = absint($_POST['recipe_id'] ?? 0);
    $rating = intval($_POST['rating'] ?? 0);
    $review_text = sanitize_textarea_field($_POST['review_text'] ?? '');

    if (!$recipe_id || get_post_type($recipe_id) !== 'recipe' || get_post_status($recipe_id) !== 'publish') {
        wp_send_json_error(['message' => 'Invalid recipe.']);
    }
    if ($rating < 1 || $rating > 5) {
        wp_send_json_error(['message' => 'Rating must be between 1 and 5.']);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'recipe_ratings';
    $user_id = get_current_user_id();

    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE recipe_id = %d AND user_id = %d",
        $recipe_id, $user_id
    ));
    if ($exists) {
        wp_send_json_error(['message' => 'You have already rated this recipe.']);
    }

    $has_review = !empty($review_text);
    $approved = $has_review ? 0 : 1;

    $wpdb->insert($table, [
        'recipe_id'   => $recipe_id,
        'user_id'     => $user_id,
        'rating'      => $rating,
        'review_text' => $has_review ? $review_text : null,
        'approved'    => $approved,
        'created_at'  => current_time('mysql'),
    ]);

    $stats = drmommies_update_rating_cache($recipe_id);

    wp_send_json_success([
        'average'       => $stats['average'],
        'count'         => $stats['count'],
        'review_count'  => $stats['review_count'],
        'needsApproval' => $has_review,
    ]);
}
add_action('wp_ajax_rate_recipe', 'drmommies_rate_recipe');
```

- [ ] **Step 2: Add the submit_faq AJAX handler**

Append to `functions.php`:

```php
// AJAX: Submit a FAQ question (logged-in users only)
function drmommies_submit_faq() {
    check_ajax_referer('drmommies_nonce', 'nonce');

    $recipe_id = absint($_POST['recipe_id'] ?? 0);
    $question = sanitize_textarea_field($_POST['question'] ?? '');

    if (!$recipe_id || get_post_type($recipe_id) !== 'recipe' || get_post_status($recipe_id) !== 'publish') {
        wp_send_json_error(['message' => 'Invalid recipe.']);
    }
    if (empty($question)) {
        wp_send_json_error(['message' => 'Please enter a question.']);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'recipe_faqs';

    $wpdb->insert($table, [
        'recipe_id'  => $recipe_id,
        'user_id'    => get_current_user_id(),
        'question'   => $question,
        'approved'   => 0,
        'created_at' => current_time('mysql'),
    ]);

    wp_send_json_success(['message' => 'Your question has been submitted and is pending approval.']);
}
add_action('wp_ajax_submit_faq', 'drmommies_submit_faq');
```

- [ ] **Step 3: Commit**

```bash
git add wp-content/themes/drmommies/functions.php
git commit -m "feat: add AJAX handlers for recipe rating and FAQ submission"
```

---

### Task 3: Admin Moderation Page

**Files:**
- Modify: `wp-content/themes/drmommies/functions.php` (append after AJAX handlers)

- [ ] **Step 1: Register the admin menu page**

Append to `functions.php`:

```php
// Admin: Recipe moderation page
function drmommies_admin_moderation_menu() {
    add_submenu_page(
        'edit.php?post_type=recipe',
        'Reviews & FAQ Moderation',
        'Moderation',
        'manage_options',
        'recipe-moderation',
        'drmommies_moderation_page_callback'
    );
}
add_action('admin_menu', 'drmommies_admin_moderation_menu');
```

- [ ] **Step 2: Add the moderation page handler for approve/delete actions**

Append to `functions.php`:

```php
function drmommies_moderation_page_callback() {
    global $wpdb;
    $ratings_table = $wpdb->prefix . 'recipe_ratings';
    $faqs_table = $wpdb->prefix . 'recipe_faqs';

    // Handle actions
    if (isset($_POST['moderation_action']) && check_admin_referer('drmommies_moderation')) {
        $action = sanitize_text_field($_POST['moderation_action']);
        $item_id = absint($_POST['item_id'] ?? 0);
        $item_type = sanitize_text_field($_POST['item_type'] ?? '');

        if ($item_type === 'review' && $item_id) {
            if ($action === 'approve') {
                $wpdb->update($ratings_table, ['approved' => 1], ['id' => $item_id]);
                $recipe_id = $wpdb->get_var($wpdb->prepare("SELECT recipe_id FROM $ratings_table WHERE id = %d", $item_id));
                if ($recipe_id) drmommies_update_rating_cache($recipe_id);
            } elseif ($action === 'delete') {
                $recipe_id = $wpdb->get_var($wpdb->prepare("SELECT recipe_id FROM $ratings_table WHERE id = %d", $item_id));
                $wpdb->delete($ratings_table, ['id' => $item_id]);
                if ($recipe_id) drmommies_update_rating_cache($recipe_id);
            }
        } elseif ($item_type === 'faq' && $item_id) {
            if ($action === 'approve') {
                $answer = sanitize_textarea_field($_POST['faq_answer'] ?? '');
                $wpdb->update($faqs_table, ['approved' => 1, 'answer' => $answer ?: null], ['id' => $item_id]);
            } elseif ($action === 'delete') {
                $wpdb->delete($faqs_table, ['id' => $item_id]);
            }
        }

        echo '<div class="notice notice-success"><p>Action completed.</p></div>';
    }

    // Fetch pending items
    $pending_reviews = $wpdb->get_results(
        "SELECT r.*, u.display_name, p.post_title
         FROM $ratings_table r
         JOIN {$wpdb->users} u ON r.user_id = u.ID
         JOIN {$wpdb->posts} p ON r.recipe_id = p.ID
         WHERE r.review_text IS NOT NULL AND r.review_text != '' AND r.approved = 0
         ORDER BY r.created_at DESC"
    );

    $pending_faqs = $wpdb->get_results(
        "SELECT f.*, u.display_name, p.post_title
         FROM $faqs_table f
         JOIN {$wpdb->users} u ON f.user_id = u.ID
         JOIN {$wpdb->posts} p ON f.recipe_id = p.ID
         WHERE f.approved = 0
         ORDER BY f.created_at DESC"
    );

    $approved_faqs = $wpdb->get_results(
        "SELECT f.*, u.display_name, p.post_title
         FROM $faqs_table f
         JOIN {$wpdb->users} u ON f.user_id = u.ID
         JOIN {$wpdb->posts} p ON f.recipe_id = p.ID
         WHERE f.approved = 1
         ORDER BY f.created_at DESC"
    );

    ?>
    <div class="wrap">
        <h1>Reviews & FAQ Moderation</h1>

        <h2>Pending Reviews (<?php echo count($pending_reviews); ?>)</h2>
        <?php if (empty($pending_reviews)) : ?>
            <p>No pending reviews.</p>
        <?php else : ?>
            <table class="widefat striped">
                <thead><tr><th>Recipe</th><th>User</th><th>Rating</th><th>Review</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($pending_reviews as $review) : ?>
                    <tr>
                        <td><a href="<?php echo get_edit_post_link($review->recipe_id); ?>"><?php echo esc_html($review->post_title); ?></a></td>
                        <td><?php echo esc_html($review->display_name); ?></td>
                        <td><?php echo str_repeat('&#9733;', $review->rating) . str_repeat('&#9734;', 5 - $review->rating); ?></td>
                        <td><?php echo esc_html($review->review_text); ?></td>
                        <td><?php echo esc_html($review->created_at); ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <?php wp_nonce_field('drmommies_moderation'); ?>
                                <input type="hidden" name="item_id" value="<?php echo esc_attr($review->id); ?>">
                                <input type="hidden" name="item_type" value="review">
                                <button type="submit" name="moderation_action" value="approve" class="button button-primary button-small">Approve</button>
                                <button type="submit" name="moderation_action" value="delete" class="button button-small" onclick="return confirm('Delete this review?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <hr>
        <h2>Pending Questions (<?php echo count($pending_faqs); ?>)</h2>
        <?php if (empty($pending_faqs)) : ?>
            <p>No pending questions.</p>
        <?php else : ?>
            <table class="widefat striped">
                <thead><tr><th>Recipe</th><th>User</th><th>Question</th><th>Date</th><th>Answer & Actions</th></tr></thead>
                <tbody>
                <?php foreach ($pending_faqs as $faq) : ?>
                    <tr>
                        <td><a href="<?php echo get_edit_post_link($faq->recipe_id); ?>"><?php echo esc_html($faq->post_title); ?></a></td>
                        <td><?php echo esc_html($faq->display_name); ?></td>
                        <td><?php echo esc_html($faq->question); ?></td>
                        <td><?php echo esc_html($faq->created_at); ?></td>
                        <td>
                            <form method="post">
                                <?php wp_nonce_field('drmommies_moderation'); ?>
                                <input type="hidden" name="item_id" value="<?php echo esc_attr($faq->id); ?>">
                                <input type="hidden" name="item_type" value="faq">
                                <textarea name="faq_answer" rows="2" style="width:100%;margin-bottom:6px;" placeholder="Write an answer (optional)..."></textarea>
                                <button type="submit" name="moderation_action" value="approve" class="button button-primary button-small">Approve</button>
                                <button type="submit" name="moderation_action" value="delete" class="button button-small" onclick="return confirm('Delete this question?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <hr>
        <h2>Approved FAQs (<?php echo count($approved_faqs); ?>)</h2>
        <?php if (empty($approved_faqs)) : ?>
            <p>No approved FAQs yet.</p>
        <?php else : ?>
            <table class="widefat striped">
                <thead><tr><th>Recipe</th><th>User</th><th>Question</th><th>Answer</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($approved_faqs as $faq) : ?>
                    <tr>
                        <td><a href="<?php echo get_edit_post_link($faq->recipe_id); ?>"><?php echo esc_html($faq->post_title); ?></a></td>
                        <td><?php echo esc_html($faq->display_name); ?></td>
                        <td><?php echo esc_html($faq->question); ?></td>
                        <td>
                            <form method="post">
                                <?php wp_nonce_field('drmommies_moderation'); ?>
                                <input type="hidden" name="item_id" value="<?php echo esc_attr($faq->id); ?>">
                                <input type="hidden" name="item_type" value="faq">
                                <textarea name="faq_answer" rows="2" style="width:100%;"><?php echo esc_textarea($faq->answer); ?></textarea>
                                <button type="submit" name="moderation_action" value="approve" class="button button-primary button-small" style="margin-top:4px;">Update Answer</button>
                                <button type="submit" name="moderation_action" value="delete" class="button button-small" style="margin-top:4px;" onclick="return confirm('Delete this FAQ?');">Delete</button>
                            </form>
                        </td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}
```

- [ ] **Step 3: Verify the admin page loads**

Navigate to `http://drmommies.local/wp-admin/edit.php?post_type=recipe&page=recipe-moderation` and confirm the page renders with empty "Pending Reviews", "Pending Questions", and "Approved FAQs" sections.

- [ ] **Step 4: Commit**

```bash
git add wp-content/themes/drmommies/functions.php
git commit -m "feat: add admin moderation page for reviews and FAQs"
```

---

### Task 4: Single Recipe Page — Rating Widget & Review Form

**Files:**
- Modify: `wp-content/themes/drmommies/single-recipe.php:1-35` (top variables and JSON-LD)
- Modify: `wp-content/themes/drmommies/single-recipe.php:59-66` (print bar area)

- [ ] **Step 1: Add rating data variables at the top of the template**

In `single-recipe.php`, after line 14 (`'fiber' => ...`), add:

```php
    $rating_data = drmommies_get_recipe_rating(get_the_ID());
    $user_rating = null;
    $user_can_rate = false;
    if (is_user_logged_in()) {
        global $wpdb;
        $ratings_table = $wpdb->prefix . 'recipe_ratings';
        $user_rating = $wpdb->get_var($wpdb->prepare(
            "SELECT rating FROM $ratings_table WHERE recipe_id = %d AND user_id = %d",
            get_the_ID(), get_current_user_id()
        ));
        $user_can_rate = !$user_rating;
    }
```

- [ ] **Step 2: Update JSON-LD to include aggregateRating**

In `single-recipe.php`, in the `$ld` array (around line 31), before the closing `];`, add:

```php
    if ($rating_data['count'] > 0) {
        $ld['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => (string) $rating_data['average'],
            'ratingCount' => (string) $rating_data['count'],
        ];
    }
```

- [ ] **Step 3: Add the rating widget to the print bar**

In `single-recipe.php`, after the Save button (after line 65 — the closing `</button>` for `.btn-save-recipe`), add:

```php
                    <div class="recipe-rating-widget" data-recipe-id="<?php the_ID(); ?>">
                        <?php if ($user_can_rate) : ?>
                            <div class="rating-interactive">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <span class="star interactive" data-value="<?php echo $i; ?>">&#9733;</span>
                                <?php endfor; ?>
                            </div>
                        <?php elseif ($user_rating) : ?>
                            <div class="rating-user-voted">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <span class="star <?php echo $i <= $user_rating ? 'filled' : 'empty'; ?>">&#9733;</span>
                                <?php endfor; ?>
                                <span class="rating-label">You rated this <?php echo intval($user_rating); ?> star<?php echo $user_rating > 1 ? 's' : ''; ?></span>
                            </div>
                        <?php else : ?>
                            <div class="rating-login-prompt">
                                <?php echo drmommies_render_stars_html($rating_data['average'], $rating_data['count']); ?>
                                <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>">Log in to rate</a>
                            </div>
                        <?php endif; ?>
                        <div class="rating-average">
                            <?php echo drmommies_render_stars_html($rating_data['average'], $rating_data['count']); ?>
                        </div>
                        <div class="rating-message" style="display:none;"></div>
                    </div>
```

- [ ] **Step 4: Add the review form for logged-in users who can rate**

In `single-recipe.php`, right after the rating widget `</div>` added in Step 3 (still inside the `.recipe-print-bar`), but before the closing `</div>` of `.recipe-print-bar`, add:

```php
                    <?php if ($user_can_rate) : ?>
                    <div class="review-form-inline" id="review-form-inline" style="display:none;">
                        <textarea id="review-text" rows="3" placeholder="Add a written review (optional)..." maxlength="1000"></textarea>
                        <button type="button" class="btn-submit-rating" id="btn-submit-rating">Submit Rating</button>
                        <button type="button" class="btn-skip-review" id="btn-skip-review">Skip Review, Just Rate</button>
                    </div>
                    <?php endif; ?>
```

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/drmommies/single-recipe.php
git commit -m "feat: add rating widget and review form to single recipe page"
```

---

### Task 5: Single Recipe Page — Reviews Section & FAQ Section

**Files:**
- Modify: `wp-content/themes/drmommies/single-recipe.php` (after `.recipe-single-grid` closing div, before related recipes)

- [ ] **Step 1: Add the reviews section**

In `single-recipe.php`, after the closing `</div>` of `.recipe-single-grid` (line 146) and before the related recipes `<?php` block (line 148), add:

```php
        <!-- Reviews Section -->
        <?php
        $approved_reviews = drmommies_get_approved_reviews(get_the_ID());
        ?>
        <div class="recipe-reviews-section">
            <h2>Reviews (<?php echo count($approved_reviews); ?>)</h2>
            <?php if (!empty($approved_reviews)) : ?>
                <div class="reviews-list">
                    <?php foreach ($approved_reviews as $review) : ?>
                        <div class="review-card">
                            <div class="review-header">
                                <span class="review-author"><?php echo esc_html($review->display_name); ?></span>
                                <span class="review-stars">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <span class="star <?php echo $i <= $review->rating ? 'filled' : 'empty'; ?>">&#9733;</span>
                                    <?php endfor; ?>
                                </span>
                                <span class="review-date"><?php echo esc_html(date('M j, Y', strtotime($review->created_at))); ?></span>
                            </div>
                            <p class="review-text"><?php echo esc_html($review->review_text); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="no-reviews">No reviews yet. Be the first to review this recipe!</p>
            <?php endif; ?>
        </div>
```

- [ ] **Step 2: Add the FAQ section**

Immediately after the reviews section added in Step 1, add:

```php
        <!-- FAQ Section -->
        <?php
        $approved_faqs = drmommies_get_approved_faqs(get_the_ID());
        ?>
        <div class="recipe-faq-section">
            <h2>Questions & Answers</h2>
            <?php if (!empty($approved_faqs)) : ?>
                <div class="faq-list">
                    <?php foreach ($approved_faqs as $faq) : ?>
                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span class="faq-q-label">Q:</span>
                                <span><?php echo esc_html($faq->question); ?></span>
                                <span class="faq-toggle">+</span>
                            </button>
                            <div class="faq-answer" hidden>
                                <?php if ($faq->answer) : ?>
                                    <p><strong>A:</strong> <?php echo esc_html($faq->answer); ?></p>
                                <?php else : ?>
                                    <p class="faq-pending-answer"><em>Answer pending</em></p>
                                <?php endif; ?>
                                <span class="faq-meta">Asked by <?php echo esc_html($faq->display_name); ?> on <?php echo esc_html(date('M j, Y', strtotime($faq->created_at))); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (is_user_logged_in()) : ?>
                <div class="faq-submit-form" data-recipe-id="<?php the_ID(); ?>">
                    <h4>Ask a Question</h4>
                    <textarea id="faq-question-input" rows="3" placeholder="What would you like to know about this recipe?" maxlength="500"></textarea>
                    <button type="button" class="btn btn-primary" id="btn-submit-faq">Submit Question</button>
                    <div class="faq-form-message" style="display:none;"></div>
                </div>
            <?php else : ?>
                <p class="faq-login-prompt"><a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>">Log in</a> to ask a question.</p>
            <?php endif; ?>
        </div>
```

- [ ] **Step 3: Commit**

```bash
git add wp-content/themes/drmommies/single-recipe.php
git commit -m "feat: add reviews section and FAQ section to single recipe page"
```

---

### Task 6: Recipe Cards — Star Display & Data Attributes

**Files:**
- Modify: `wp-content/themes/drmommies/page-recipes.php:58-80` (recipe card markup)
- Modify: `wp-content/themes/drmommies/archive-recipe.php:17-39` (recipe card markup)
- Modify: `wp-content/themes/drmommies/single-recipe.php:177-198` (related recipe cards)

- [ ] **Step 1: Update recipe cards in `page-recipes.php`**

In `page-recipes.php`, on line 58, update the `<article>` opening tag to include data attributes:

Replace:
```php
                    <article class="recipe-card" data-category="<?php echo esc_attr($cat_slug); ?>" data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">
```

With:
```php
                    <?php $card_rating = drmommies_get_recipe_rating(get_the_ID()); ?>
                    <article class="recipe-card" data-category="<?php echo esc_attr($cat_slug); ?>" data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>" data-rating="<?php echo esc_attr($card_rating['average']); ?>" data-rating-count="<?php echo esc_attr($card_rating['count']); ?>" data-index="<?php echo esc_attr($recipes->current_post); ?>">
```

Then, in the `.card-body` div (after the `</div>` of `.card-meta` around line 72 and before the `<h3>`), add:

```php
                            <?php if ($card_rating['count'] > 0) : ?>
                                <div class="card-rating"><?php echo drmommies_render_stars_html($card_rating['average'], $card_rating['count']); ?></div>
                            <?php endif; ?>
```

- [ ] **Step 2: Update recipe cards in `archive-recipe.php`**

In `archive-recipe.php`, on line 17, update the `<article>` tag:

Replace:
```php
                <article class="recipe-card">
```

With:
```php
                <?php $card_rating = drmommies_get_recipe_rating(get_the_ID()); ?>
                <article class="recipe-card" data-rating="<?php echo esc_attr($card_rating['average']); ?>" data-rating-count="<?php echo esc_attr($card_rating['count']); ?>">
```

Then, in the `.card-body` div (after the `</div>` of `.card-meta` around line 31, before the `<h3>`), add:

```php
                        <?php if ($card_rating['count'] > 0) : ?>
                            <div class="card-rating"><?php echo drmommies_render_stars_html($card_rating['average'], $card_rating['count']); ?></div>
                        <?php endif; ?>
```

- [ ] **Step 3: Update related recipe cards in `single-recipe.php`**

In `single-recipe.php`, in the related recipes loop, after the `</div>` of `.card-meta` (around line 190, before `<h3>`), add:

```php
                            <?php
                            $rel_rating = drmommies_get_recipe_rating(get_the_ID());
                            if ($rel_rating['count'] > 0) : ?>
                                <div class="card-rating"><?php echo drmommies_render_stars_html($rel_rating['average'], $rel_rating['count']); ?></div>
                            <?php endif; ?>
```

- [ ] **Step 4: Commit**

```bash
git add wp-content/themes/drmommies/page-recipes.php wp-content/themes/drmommies/archive-recipe.php wp-content/themes/drmommies/single-recipe.php
git commit -m "feat: add star display and data attributes to recipe cards"
```

---

### Task 7: Sort Dropdown & Rating Filter Buttons

**Files:**
- Modify: `wp-content/themes/drmommies/page-recipes.php:16-35` (filter bar area)

- [ ] **Step 1: Add sort dropdown next to search**

In `page-recipes.php`, after the `.recipe-search-wrap` closing `</div>` (line 23) and before the closing `</div>` of `.recipe-search-row` (line 24), add:

```php
                <div class="recipe-sort-wrap">
                    <select id="recipe-sort" aria-label="Sort recipes">
                        <option value="newest">Newest</option>
                        <option value="highest-rated">Highest Rated</option>
                        <option value="most-reviewed">Most Reviewed</option>
                    </select>
                </div>
```

- [ ] **Step 2: Add rating filter row**

In `page-recipes.php`, after the closing `</div>` of `.recipe-filter-row` (line 34) and before the closing `</div>` of `.container` (line 35), add:

```php
        <div class="recipe-rating-filter-row">
            <button class="filter-btn rating-filter active" data-min-rating="0">All Ratings</button>
            <button class="filter-btn rating-filter" data-min-rating="4">4+ Stars</button>
            <button class="filter-btn rating-filter" data-min-rating="3">3+ Stars</button>
        </div>
```

- [ ] **Step 3: Commit**

```bash
git add wp-content/themes/drmommies/page-recipes.php
git commit -m "feat: add sort dropdown and rating filter buttons to recipes page"
```

---

### Task 8: CSS Styles for All New Components

**Files:**
- Modify: `wp-content/themes/drmommies/style.css:1358-1361` (existing `.recipe-search-row`)
- Modify: `wp-content/themes/drmommies/style.css` (append before print styles at line 1541)

- [ ] **Step 1: Update existing `.recipe-search-row` to add gap for the sort dropdown**

In `style.css`, replace the existing rule at lines 1358-1361:

Replace:
```css
.recipe-search-row {
    display: flex;
    justify-content: center;
    margin-bottom: 18px;
}
```

With:
```css
.recipe-search-row {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-bottom: 18px;
    align-items: center;
}
```

- [ ] **Step 2: Add all new component styles before print styles**

In `style.css`, before the `/* PRINT STYLES */` comment (line 1541), add:

```css
/* ===========================
   RECIPE RATING SYSTEM
   =========================== */
.recipe-rating-widget {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.recipe-rating-widget .star {
    font-size: 22px;
    cursor: default;
    transition: color 0.15s ease;
}
.recipe-rating-widget .star.filled { color: var(--color-primary-purple); }
.recipe-rating-widget .star.empty { color: #d1d5db; }
.rating-interactive .star {
    cursor: pointer;
    color: #d1d5db;
}
.rating-interactive .star.hover,
.rating-interactive .star.preview { color: #f59e0b; }
.rating-interactive .star.selected { color: var(--color-primary-purple); }
.rating-average { font-size: 14px; color: var(--color-gray); }
.rating-label { font-size: 13px; color: var(--color-gray); margin-left: 6px; }
.rating-login-prompt a {
    font-size: 13px;
    color: var(--color-primary-purple);
    font-weight: 600;
    margin-left: 6px;
}
.rating-message {
    font-size: 13px;
    padding: 6px 12px;
    border-radius: 8px;
    width: 100%;
}
.rating-message.success { background: #ecfdf5; color: #065f46; }
.rating-message.error { background: #fef2f2; color: #991b1b; }

/* Card rating display */
.card-rating {
    margin-bottom: 8px;
}
.recipe-stars-display .star { font-size: 14px; }
.recipe-stars-display .star.filled { color: var(--color-primary-purple); }
.recipe-stars-display .star.empty { color: #d1d5db; }
.recipe-stars-display .rating-count {
    font-size: 12px;
    color: var(--color-gray);
    margin-left: 4px;
}

/* Review form inline */
.review-form-inline {
    width: 100%;
    margin-top: 12px;
}
.review-form-inline textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e8e0ff;
    border-radius: 12px;
    font-family: var(--font-secondary);
    font-size: 14px;
    resize: vertical;
    outline: none;
    transition: var(--transition);
    margin-bottom: 8px;
}
.review-form-inline textarea:focus { border-color: var(--color-primary-purple); }
.btn-submit-rating, .btn-skip-review {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    font-family: var(--font-secondary);
    border: 2px solid var(--color-primary-purple);
    margin-right: 8px;
}
.btn-submit-rating {
    background: var(--color-primary-purple);
    color: white;
}
.btn-submit-rating:hover { background: var(--color-dark-purple); border-color: var(--color-dark-purple); }
.btn-skip-review {
    background: transparent;
    color: var(--color-primary-purple);
}
.btn-skip-review:hover { background: var(--color-primary-purple); color: white; }

/* ===========================
   REVIEWS SECTION
   =========================== */
.recipe-reviews-section {
    margin-top: 48px;
    padding-top: 36px;
    border-top: 2px solid #f0e8ff;
}
.recipe-reviews-section h2 {
    color: var(--color-dark-purple);
    font-family: var(--font-primary);
    font-size: 28px;
    margin-bottom: 24px;
}
.reviews-list { display: flex; flex-direction: column; gap: 16px; }
.review-card {
    background: var(--color-gray-light);
    border-radius: var(--border-radius);
    padding: 20px;
}
.review-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
    flex-wrap: wrap;
}
.review-author {
    font-weight: 600;
    color: var(--color-dark-purple);
    font-size: 15px;
}
.review-stars .star { font-size: 14px; }
.review-stars .star.filled { color: var(--color-primary-purple); }
.review-stars .star.empty { color: #d1d5db; }
.review-date { font-size: 13px; color: var(--color-gray); }
.review-text { font-size: 15px; color: #444; line-height: 1.7; }
.no-reviews { color: var(--color-gray); font-style: italic; }

/* ===========================
   FAQ SECTION
   =========================== */
.recipe-faq-section {
    margin-top: 48px;
    padding-top: 36px;
    border-top: 2px solid #f0e8ff;
}
.recipe-faq-section h2 {
    color: var(--color-dark-purple);
    font-family: var(--font-primary);
    font-size: 28px;
    margin-bottom: 24px;
}
.faq-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 32px; }
.faq-item {
    border: 2px solid #f0e8ff;
    border-radius: var(--border-radius);
    overflow: hidden;
}
.faq-question {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 16px 20px;
    background: white;
    border: none;
    cursor: pointer;
    font-family: var(--font-secondary);
    font-size: 15px;
    font-weight: 600;
    color: var(--color-dark-purple);
    text-align: left;
    gap: 10px;
    transition: var(--transition);
}
.faq-question:hover { background: var(--color-gray-light); }
.faq-question[aria-expanded="true"] { background: var(--color-light-purple); }
.faq-q-label { color: var(--color-primary-purple); font-weight: 700; }
.faq-toggle {
    margin-left: auto;
    font-size: 20px;
    color: var(--color-primary-purple);
    transition: transform 0.3s ease;
}
.faq-question[aria-expanded="true"] .faq-toggle { transform: rotate(45deg); }
.faq-answer {
    padding: 16px 20px;
    background: var(--color-gray-light);
    font-size: 15px;
    line-height: 1.7;
    color: #444;
}
.faq-pending-answer { color: var(--color-gray); }
.faq-meta { display: block; margin-top: 10px; font-size: 12px; color: var(--color-gray); }
.faq-submit-form {
    background: var(--color-gray-light);
    border-radius: var(--border-radius);
    padding: 24px;
    margin-top: 20px;
}
.faq-submit-form h4 {
    color: var(--color-dark-purple);
    margin-bottom: 12px;
}
.faq-submit-form textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e8e0ff;
    border-radius: 12px;
    font-family: var(--font-secondary);
    font-size: 14px;
    resize: vertical;
    outline: none;
    transition: var(--transition);
    margin-bottom: 12px;
}
.faq-submit-form textarea:focus { border-color: var(--color-primary-purple); }
.faq-form-message {
    margin-top: 10px;
    font-size: 13px;
    padding: 6px 12px;
    border-radius: 8px;
}
.faq-form-message.success { background: #ecfdf5; color: #065f46; }
.faq-form-message.error { background: #fef2f2; color: #991b1b; }
.faq-login-prompt { color: var(--color-gray); margin-top: 16px; }
.faq-login-prompt a { color: var(--color-primary-purple); font-weight: 600; }

/* ===========================
   SORT & FILTER ENHANCEMENTS
   =========================== */
.recipe-sort-wrap select {
    padding: 12px 20px;
    border: 2px solid #e8e0ff;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    font-family: var(--font-secondary);
    color: var(--color-dark-purple);
    background: white;
    cursor: pointer;
    outline: none;
    transition: var(--transition);
}
.recipe-sort-wrap select:focus { border-color: var(--color-primary-purple); }
.recipe-rating-filter-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 12px;
}
```

- [ ] **Step 3: Update the print styles to hide new sections**

In `style.css`, update the existing print rule to also hide the reviews and FAQ sections:

Replace:
```css
    .site-header, .site-footer, .recipe-print-bar,
    .recipe-newsletter-cta, .related-recipes,
    .whatsapp-bubble, .recipe-filter-bar,
    .page-hero { display: none !important; }
```

With:
```css
    .site-header, .site-footer, .recipe-print-bar,
    .recipe-newsletter-cta, .related-recipes,
    .recipe-reviews-section, .recipe-faq-section,
    .whatsapp-bubble, .recipe-filter-bar,
    .page-hero { display: none !important; }
```

- [ ] **Step 4: Commit**

```bash
git add wp-content/themes/drmommies/style.css
git commit -m "feat: add styles for ratings, reviews, FAQ, and sort/filter components"
```

---

### Task 9: JavaScript — Rating Interaction & AJAX

**Files:**
- Modify: `wp-content/themes/drmommies/js/main.js` (append before the final closing line)

- [ ] **Step 1: Add rating interaction JavaScript**

In `js/main.js`, append before the final empty line (line 204, after the reveal observer code):

```javascript
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
```

- [ ] **Step 2: Commit**

```bash
git add wp-content/themes/drmommies/js/main.js
git commit -m "feat: add rating interaction and AJAX submission JavaScript"
```

---

### Task 10: JavaScript — FAQ Accordion & Submission

**Files:**
- Modify: `wp-content/themes/drmommies/js/main.js` (append after the rating code from Task 9)

- [ ] **Step 1: Add FAQ accordion and submission JavaScript**

Append to `js/main.js` (after the rating block added in Task 9):

```javascript
    // ===========================
    // FAQ ACCORDION & SUBMISSION
    // ===========================
    var faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
            var answer = this.nextElementSibling;
            if (expanded) {
                answer.setAttribute('hidden', '');
            } else {
                answer.removeAttribute('hidden');
            }
        });
    });

    var btnSubmitFaq = document.getElementById('btn-submit-faq');
    if (btnSubmitFaq) {
        btnSubmitFaq.addEventListener('click', function() {
            var input = document.getElementById('faq-question-input');
            var question = input ? input.value.trim() : '';
            var msgEl = document.querySelector('.faq-form-message');
            var form = document.querySelector('.faq-submit-form');

            if (!question) {
                if (msgEl) {
                    msgEl.textContent = 'Please enter a question.';
                    msgEl.className = 'faq-form-message error';
                    msgEl.style.display = 'block';
                }
                return;
            }

            btnSubmitFaq.disabled = true;
            btnSubmitFaq.textContent = 'Submitting...';

            var formData = new FormData();
            formData.append('action', 'submit_faq');
            formData.append('recipe_id', form.getAttribute('data-recipe-id'));
            formData.append('question', question);
            formData.append('nonce', drMommiesData.nonce);

            fetch(drMommiesData.ajaxUrl, { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        if (msgEl) {
                            msgEl.textContent = data.data.message;
                            msgEl.className = 'faq-form-message success';
                            msgEl.style.display = 'block';
                        }
                        input.value = '';
                    } else {
                        if (msgEl) {
                            msgEl.textContent = data.data.message || 'Something went wrong.';
                            msgEl.className = 'faq-form-message error';
                            msgEl.style.display = 'block';
                        }
                    }
                })
                .catch(function() {
                    if (msgEl) {
                        msgEl.textContent = 'Something went wrong. Please try again.';
                        msgEl.className = 'faq-form-message error';
                        msgEl.style.display = 'block';
                    }
                })
                .finally(function() {
                    btnSubmitFaq.disabled = false;
                    btnSubmitFaq.textContent = 'Submit Question';
                });
        });
    }
```

- [ ] **Step 2: Commit**

```bash
git add wp-content/themes/drmommies/js/main.js
git commit -m "feat: add FAQ accordion toggle and AJAX submission JavaScript"
```

---

### Task 11: JavaScript — Sort & Rating Filter

**Files:**
- Modify: `wp-content/themes/drmommies/page-recipes.php:130-192` (inline `<script>` block)

- [ ] **Step 1: Replace the inline filter script in `page-recipes.php`**

In `page-recipes.php`, replace the entire `<script>` block (lines 130-192) with:

```html
<script>
(function() {
    var grid = document.getElementById('recipes-grid');
    var noResults = document.getElementById('no-recipes');
    var countBar = document.getElementById('recipes-count');
    var searchInput = document.getElementById('recipe-search');
    var sortSelect = document.getElementById('recipe-sort');

    function getActiveCategory() {
        var btn = document.querySelector('.filter-btn.active:not(.rating-filter)');
        return btn ? btn.getAttribute('data-cat') : '';
    }

    function getMinRating() {
        var btn = document.querySelector('.rating-filter.active');
        return btn ? parseFloat(btn.getAttribute('data-min-rating')) : 0;
    }

    function filterRecipes() {
        var activeCat = getActiveCategory();
        var minRating = getMinRating();
        var searchVal = searchInput ? searchInput.value.toLowerCase().trim() : '';
        var cards = grid.querySelectorAll('.recipe-card');
        var visible = 0;

        cards.forEach(function(card) {
            var catMatch = !activeCat || card.getAttribute('data-category') === activeCat;
            var titleMatch = !searchVal || card.getAttribute('data-title').includes(searchVal) || (card.querySelector('p') && card.querySelector('p').textContent.toLowerCase().includes(searchVal));
            var ratingMatch = !minRating || parseFloat(card.getAttribute('data-rating') || 0) >= minRating;

            if (catMatch && titleMatch && ratingMatch) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        noResults.style.display = visible === 0 ? 'block' : 'none';
        if (countBar) {
            countBar.textContent = visible + ' recipe' + (visible !== 1 ? 's' : '') + ' found';
        }
    }

    function sortRecipes() {
        var sortVal = sortSelect ? sortSelect.value : 'newest';
        var cards = Array.from(grid.querySelectorAll('.recipe-card'));

        cards.sort(function(a, b) {
            if (sortVal === 'highest-rated') {
                return (parseFloat(b.getAttribute('data-rating') || 0)) - (parseFloat(a.getAttribute('data-rating') || 0));
            } else if (sortVal === 'most-reviewed') {
                return (parseInt(b.getAttribute('data-rating-count') || 0)) - (parseInt(a.getAttribute('data-rating-count') || 0));
            } else {
                return (parseInt(a.getAttribute('data-index') || 0)) - (parseInt(b.getAttribute('data-index') || 0));
            }
        });

        cards.forEach(function(card) { grid.appendChild(card); });
        filterRecipes();
    }

    // Category filter buttons
    document.querySelectorAll('.filter-btn:not(.rating-filter)').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn:not(.rating-filter)').forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            filterRecipes();
        });
    });

    // Rating filter buttons
    document.querySelectorAll('.rating-filter').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.rating-filter').forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            filterRecipes();
        });
    });

    // Search
    if (searchInput) {
        searchInput.addEventListener('input', filterRecipes);
    }

    // Sort
    if (sortSelect) {
        sortSelect.addEventListener('change', sortRecipes);
    }

    // Init
    filterRecipes();
})();

function clearSearch() {
    document.getElementById('recipe-search').value = '';
    document.querySelector('.filter-btn[data-cat=""]').click();
}

// Mini contact form
document.getElementById('recipe-contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var result = document.getElementById('recipe-contact-result');
    result.className = 'form-result success';
    result.textContent = 'Thank you! We will be in touch soon.';
    this.reset();
});
</script>
```

- [ ] **Step 2: Commit**

```bash
git add wp-content/themes/drmommies/page-recipes.php
git commit -m "feat: add sort by rating and rating filter to recipes page"
```

---

### Task 12: Smoke Test

- [ ] **Step 1: Browse the site and verify all features**

Open in browser and check each feature:

1. **Recipes page** (`http://drmommies.local/recipes/`):
   - Recipe cards render without errors
   - Sort dropdown appears next to search
   - Rating filter buttons appear below category filters
   - Sort and filter controls work (will have no ratings yet, so "Highest Rated" and "4+ Stars" filter won't show visible differences until ratings exist)

2. **Single recipe page** (click any recipe):
   - Rating widget appears in the print/save bar
   - If logged out: shows "Log in to rate" link
   - Reviews section shows "No reviews yet" message
   - FAQ section shows "Ask a Question" form (if logged in) or "Log in to ask a question"

3. **Log in and test rating** (`http://drmommies.local/wp-login.php`):
   - Log in as admin
   - Go to a recipe, hover over stars (gold preview), click to select
   - Review form appears with "Submit Rating" and "Skip Review, Just Rate" buttons
   - Submit a rating — average updates, interaction disabled, success message shown

4. **Test FAQ submission** (same recipe, logged in):
   - Type a question and submit
   - See "pending approval" message

5. **Admin moderation** (`http://drmommies.local/wp-admin/edit.php?post_type=recipe&page=recipe-moderation`):
   - Pending questions appear
   - Approve a question with an answer
   - Return to recipe page and verify FAQ accordion works

6. **Recipe archive** (`http://drmommies.local/recipes/` via archive URL):
   - Stars display on cards for rated recipes

- [ ] **Step 2: Fix any issues found during testing**

Address any issues discovered in Step 1. Common things to check:
- JavaScript console errors
- PHP notices/warnings
- Styling alignment issues on mobile (resize browser)

- [ ] **Step 3: Final commit if fixes were needed**

```bash
git add -A
git commit -m "fix: address issues found during smoke testing"
```

---

## Summary

| Task | Description | Files |
|---|---|---|
| 1 | Database tables & helper functions | `functions.php` |
| 2 | AJAX handlers (rate + FAQ) | `functions.php` |
| 3 | Admin moderation page | `functions.php` |
| 4 | Single recipe: rating widget + review form | `single-recipe.php` |
| 5 | Single recipe: reviews section + FAQ section | `single-recipe.php` |
| 6 | Recipe cards: star display + data attributes | `page-recipes.php`, `archive-recipe.php`, `single-recipe.php` |
| 7 | Sort dropdown + rating filter buttons | `page-recipes.php` |
| 8 | CSS for all new components | `style.css` |
| 9 | JS: rating interaction + AJAX | `js/main.js` |
| 10 | JS: FAQ accordion + submission | `js/main.js` |
| 11 | JS: sort + rating filter logic | `page-recipes.php` (inline script) |
| 12 | Smoke test | All files |
