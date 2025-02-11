<?php

namespace KN\BookPlugin;

/**
 *
 */
class ReviewMeta extends Singleton
{
    //these are the keys that will be stored in the database
    //use constants to avoid type and debugging issues

    const REVIEWER_NAME = 'reviewReviewerName';
	const REVIEWER_LOCATION = 'reviewReviewerLocation';
	const STAR_RATING = 'reviewStarRating';
	const BOOK_ID = 'reviewBookID';


    //redefine instance variable so that instance is separate from the singleton
    /**
     * Static property to hold our singleton instance
     *
     */
    protected static $instance;

    /**
     * Constructor - Registers the review meta box
     *
     * @return void
     */
    public function __construct()
    {
        add_action( 'admin_init', [$this, 'registerMetaBox'], 0 );
        add_action('save_post_' . ReviewPostType::POST_TYPE, [$this, 'saveDirections'], 0);
    }

    /**
     * @return void
     */
    function registerMetaBox() {
        add_meta_box('review_details',
            'Details',
            [$this, 'informationForm'],
            ReviewPostType::POST_TYPE,
            'advanced', 'core');
    }

    /**
     * @return void
     */
    function informationForm() {
        // get meta from the database
        $reviewerName = $this->getReviewerName();
        $reviewerLocation = $this->getReviewerLocation();
        $starRating = $this->getStarRating();
        $bookID = $this->getBookID();
        ?>
        <style>
            #review_details .inside {
                padding: 0;
                margin: 0;
            }
            .book-meta-box h4 {
                margin: 0;
                padding: 20px;
                border-bottom: 1px solid #dfdfdf;
            }
            .book-meta-box label {
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #dfdfdf;
            }
            .book-meta-box:last-of-type label:last-of-type {
                border-bottom: 0;
            }
            .book-meta-box .book-meta-box-label {
                background: #F9F9F9;
                padding: 20px 10px 20px 20px;
                flex: .35;
                font-weight: 700;
                border-right: 1px solid #dfdfdf;
            }
            .book-meta-box span {
                flex: 1;
                padding: 15px;
            }
            .book-meta-box label :is(input, select) {
                flex: 2;
                padding: 5px;
                border: 1px solid #ccc;
                border-radius: 4px;
                width: 100%;
            }
        </style>
        <?php
        $books = get_posts([
            'post_type' => 'book',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);
        ?>

        <div class="book-meta-box">
            <h4>Book</h4>
            <?php if (!empty($books)) { ?>
                <label>
                    <?php $singular = BookSettings::getInstance()->bookTermSingular(); ?>
                    <span class="book-meta-box-label"><?= __(  $singular, TEXT_DOMAIN ) ?></span>
                    <span><select name="<?= self::BOOK_ID ?>" id="book_id">
                    <?php foreach ($books as $book) { ?>
                        <option value="<?= $book->ID ?>" <?php selected($bookID, $book->ID); ?>>
                            <?= $book->post_title ?>
                        </option>
                    <?php } ?></select></span>
                </label>
                <?php }
            // Close the query to reset the global $post object
            wp_reset_postdata();
            ?>
        </div>
        <div class="book-meta-box">
            <h4><?= __('Reviewer Details', TEXT_DOMAIN ) ?></h4>
            <label>
                <span class="book-meta-box-label"><?= __('Your Name', TEXT_DOMAIN ) ?></span>
                <span><input type="text" name="<?= self::REVIEWER_NAME ?>" value="<?= $reviewerName ?>"></span>
            </label>
            <label>
                <span class="book-meta-box-label"><?= __('Your Location', TEXT_DOMAIN ) ?></span>
                <span><input type="text" name="<?= self::REVIEWER_LOCATION ?>" value="<?= $reviewerLocation ?>"></span>
            </label>
            <label>
                <span class="book-meta-box-label"><?= __('Rating (1-5 stars)', TEXT_DOMAIN ) ?></span>
                <span><select name="<?= self::STAR_RATING ?>" id="star_rating">
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <option value="<?php echo $i; ?>" <?php selected($starRating, $i); ?>>
                        <?php echo $i; ?>
                    </option>
                <?php endfor; ?></select></span>
            </label>
        <?php
    }

    /**
     * @return void
     */
    function saveDirections() {
        //get values
        $reviewerName = $_POST[self::REVIEWER_NAME];
        $reviewerLocation = $_POST[self::REVIEWER_LOCATION];
        $starRating = $_POST[self::STAR_RATING];
        $bookID = $_POST[self::BOOK_ID];

        //sanitize
        $reviewerName = sanitize_text_field($reviewerName);
        $reviewerLocation = sanitize_text_field($reviewerLocation);
        $starRating = sanitize_text_field($starRating);
        $bookID = sanitize_text_field($bookID);

        //put in database
        $post = get_post();
        update_post_meta($post->ID, self::REVIEWER_NAME, $reviewerName);
        update_post_meta($post->ID, self::REVIEWER_LOCATION, $reviewerLocation);
        update_post_meta($post->ID, self::STAR_RATING, $starRating);
        update_post_meta($post->ID, self::BOOK_ID, $bookID);

    }

    /**
     * @return mixed
     */
    public function getReviewerName() {
        $post = get_post();
        return get_post_meta($post->ID, self::REVIEWER_NAME, true);
    }

    /**
     * @return mixed
     */
    public function getReviewerLocation() {
        $post = get_post();
        return get_post_meta($post->ID, self::REVIEWER_LOCATION, true);
    }

    /**
     * @return mixed
     */
    public function getStarRating() {
        $post = get_post();
        return get_post_meta($post->ID, self::STAR_RATING, true);
    }

    /**
     * @return mixed
     */
    public function getBookID() {
        $post = get_post();
        return get_post_meta($post->ID, self::BOOK_ID, true);
    }
}