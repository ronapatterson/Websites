# Recipe Star Rating, Reviews & FAQ System — Design Spec

## Overview

Add a 1-5 star rating system with optional written reviews, recipe-specific FAQ sections, and sort/filter by rating to the DrMommies WordPress site. Only logged-in users can rate, review, or ask questions. Each user may rate a given recipe once (no changes). Ratings with review text and FAQ questions require admin approval before being visible. Average ratings and vote counts display on both single recipe pages and recipe cards. Recipes can be sorted and filtered by rating on the recipes page.

## Database

### Table: `wp_recipe_ratings`

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT UNSIGNED AUTO_INCREMENT | Primary key |
| `recipe_id` | BIGINT UNSIGNED NOT NULL | FK to `wp_posts.ID` |
| `user_id` | BIGINT UNSIGNED NOT NULL | FK to `wp_users.ID` |
| `rating` | TINYINT UNSIGNED NOT NULL | 1-5 |
| `review_text` | TEXT NULL | Optional written review |
| `approved` | TINYINT UNSIGNED NOT NULL DEFAULT 1 | 1 = approved, 0 = pending |
| `created_at` | DATETIME NOT NULL | Timestamp |

**Unique constraint** on `(recipe_id, user_id)` — enforces one rating per user per recipe at the DB level.

**Approval logic:**
- Star-only ratings (no review text): auto-approved (`approved = 1`). The star value counts toward the average immediately.
- Ratings with review text: `approved = 0` (pending). The star value still counts toward the average immediately, but the review text is only visible after admin approval.

### Table: `wp_recipe_faqs`

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT UNSIGNED AUTO_INCREMENT | Primary key |
| `recipe_id` | BIGINT UNSIGNED NOT NULL | FK to `wp_posts.ID` |
| `user_id` | BIGINT UNSIGNED NOT NULL | FK to `wp_users.ID` |
| `question` | TEXT NOT NULL | The question |
| `answer` | TEXT NULL | Admin answer (filled in from admin page) |
| `approved` | TINYINT UNSIGNED NOT NULL DEFAULT 0 | Visible only when approved |
| `created_at` | DATETIME NOT NULL | Timestamp |

### Cached post meta (per recipe)

- `_rating_average` — float (e.g. `4.3`)
- `_rating_count` — int (e.g. `17`)
- `_review_count` — int (approved reviews with text only)

These are recalculated from the ratings table on each new rating insert so page loads never query the ratings table directly.

## Backend (PHP)

All code lives in the theme's `functions.php`, following existing patterns.

### Table creation

Added to the existing `drmommies_create_tables()` function that runs on `after_switch_theme`. Uses `dbDelta()` — same pattern as the `wp_newsletter_subscribers` table. Creates both `wp_recipe_ratings` and `wp_recipe_faqs`.

### AJAX handler: `drmommies_rate_recipe`

- Registered via `wp_ajax_rate_recipe` only (no `wp_ajax_nopriv_` — logged-in users only)
- Validates nonce (uses the existing `drmommies_nonce`)
- Sanitizes inputs:
  - `recipe_id` — absint, must be a published `recipe` post type
  - `rating` — intval, must be 1-5
  - `review_text` — sanitize_textarea_field, optional
- Checks for existing rating by this user on this recipe — returns error if already rated
- Sets `approved`: 1 if no review text, 0 if review text is provided
- Inserts row into `wp_recipe_ratings`
- Recalculates average, rating count, and review count; updates cached post meta
- Returns JSON: `{ success: true, data: { average: 4.3, count: 17, needsApproval: false } }`

### AJAX handler: `drmommies_submit_faq`

- Registered via `wp_ajax_submit_faq` only (logged-in users only)
- Validates nonce, sanitizes `recipe_id` and `question` text
- Inserts row into `wp_recipe_faqs` with `approved = 0`
- Returns JSON success with "Your question has been submitted and is pending approval" message

### Helper functions

- `drmommies_get_recipe_rating($post_id)` — returns `['average' => float, 'count' => int, 'review_count' => int]` from cached post meta
- `drmommies_get_approved_reviews($post_id)` — queries `wp_recipe_ratings` for rows where `review_text IS NOT NULL AND approved = 1`, returns array of reviews with user display name, rating, text, date
- `drmommies_get_approved_faqs($post_id)` — queries `wp_recipe_faqs` for `approved = 1` rows, returns array with question, answer, user display name, date

### Admin page: Review & FAQ Moderation

A submenu page under the "Recipes" admin menu for managing pending content:

- **Pending Reviews tab:** Lists ratings with review text where `approved = 0`. Admin can approve or delete each review.
- **Pending FAQs tab:** Lists questions where `approved = 0`. Admin can approve (with optional answer), or delete.
- **Approved FAQs tab:** Lists approved FAQs. Admin can edit the answer or delete.
- Uses standard WordPress admin table styling. No custom React/JS — plain PHP admin page with form submissions.

### JSON-LD update

When a recipe has ratings, the structured data in `single-recipe.php` will include:

```json
"aggregateRating": {
  "@type": "AggregateRating",
  "ratingValue": "4.3",
  "ratingCount": "17"
}
```

## Frontend

### Single recipe page (`single-recipe.php`)

**Rating widget** placed in the print/save bar (next to Print Recipe and Save buttons):

- **Average display:** Filled/empty star characters + "(X ratings)" text
- **Logged-in, not yet rated:** Clickable stars with hover preview (gold fill). Click submits via AJAX, no page reload. Stars animate and average/count update immediately.
- **Logged-in, already rated:** Static stars with "You rated this X stars" text
- **Logged-out:** Static average stars with "Log in to rate" link

**Review form** (below the rating widget, for logged-in users who haven't rated):

- Part of the rating submission — after selecting stars, an optional textarea appears: "Add a written review (optional)"
- Submitted together with the star rating in a single AJAX call
- If review text is included, show message: "Your review is pending approval"

**Reviews section** (below recipe content, above related recipes):

- Heading: "Reviews (X)" where X is the approved review count
- Each review shows: user display name, star rating as small stars, review text, date
- If no approved reviews yet: "No reviews yet. Be the first to review this recipe!"

**FAQ section** (below Reviews, above related recipes):

- Heading: "Questions & Answers"
- Approved FAQs displayed as an accordion-style list (click question to expand answer)
- Questions without an admin answer yet show as answered: "Answer pending"
- Logged-in users see a "Ask a Question" form at the bottom: single textarea + submit button
- On submit: AJAX call, success message "Your question has been submitted and is pending approval"
- Logged-out users see: "Log in to ask a question"

### Recipe cards (`page-recipes.php`, related recipes, archive)

- Small read-only star display in the card body, below the existing meta row (prep time, cook time, servings)
- Shows average as filled/empty stars + count, e.g. "★★★★☆ (12)"
- No interactive rating from cards — display only
- Cards include `data-rating` and `data-rating-count` attributes for client-side sort/filter

### Sort & filter by rating (`page-recipes.php`)

**Sort dropdown** added next to the search input:

- Options: "Newest" (default), "Highest Rated", "Most Reviewed"
- Client-side sorting using the `data-rating` and `data-rating-count` attributes on recipe cards
- "Newest" preserves the original server-rendered order (tracked via `data-index` attribute)

**Rating filter buttons** added as a second row below the category filter buttons:

- Buttons: "All Ratings" (default, active), "4+ Stars", "3+ Stars"
- Client-side filtering using `data-rating` attribute
- Works in combination with the existing category filter and search — all three filter dimensions AND together

### Styling (in `style.css`)

- Filled stars: site purple `#673de6`
- Empty stars: `#d1d5db`
- Hover preview on interactive stars: gold `#f59e0b`
- Unicode `★` characters — no icon library needed
- Reviews section: card-style layout matching existing recipe cards aesthetic
- FAQ accordion: purple accent on active question, smooth expand/collapse
- Sort dropdown: styled to match existing filter buttons
- Rating filter buttons: same style as category filter buttons
- Responsive — all new components work on mobile

### JavaScript (in `js/main.js`)

- Uses existing `drMommiesData.ajaxUrl` and `drMommiesData.nonce` (already localized)
- Rating click handler sends AJAX POST with stars + optional review text
- On success: updates star display and count, shows review pending message if applicable, disables further interaction
- FAQ submit handler sends AJAX POST, shows success/pending message, clears form
- FAQ accordion toggle (click to expand/collapse)
- Sort dropdown handler: reorders recipe cards in the DOM based on selected sort
- Rating filter handler: hides/shows cards based on minimum rating, integrates with existing category filter and search

## Files to modify

1. **`functions.php`** — table creation (2 tables), AJAX handlers (rate_recipe, submit_faq), helper functions, admin moderation page
2. **`single-recipe.php`** — interactive rating widget, review form, reviews section, FAQ section, JSON-LD update, star display on related recipe cards
3. **`page-recipes.php`** — star display on recipe cards, data attributes, sort dropdown, rating filter row
4. **`archive-recipe.php`** — star display on recipe cards, data attributes
5. **`style.css`** — rating, review, FAQ, sort/filter component styles
6. **`js/main.js`** — rating AJAX, FAQ AJAX, accordion, sort/filter logic

## Out of scope

- Anonymous/logged-out rating, reviewing, or asking questions
- Editing a submitted rating or review
- Email notifications for new reviews/questions or approval status
- Nested/threaded review replies
- Voting on reviews (helpful/not helpful)
- FAQ categories or tagging
