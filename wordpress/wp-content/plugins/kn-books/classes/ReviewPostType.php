<?php

namespace KN\BookPlugin;


class ReviewPostType extends Singleton
{

    const POST_TYPE = 'review';

    /**
     * Static property to hold our singleton instance
     * @var self
     *
     */
    protected static $instance;

    /**
     * reviewPostType constructor
     * adds actions to register review post type
     * adds to main book menu
     * filters content for reviews to add custom metadata
     */
    public function __construct()
    {
        add_action( 'init', [$this, 'registerPostType'], 0 );
        add_action('admin_menu', [$this, 'addReviewSubmenu']);
        add_filter('the_content', [$this, 'reviewContent']);

	    add_action('init', [$this, 'handleReviewSubmission']);

    }

    // Register Custom Post Type

    /**
     * registers the custom post type 'review'.
     *
     * @return void
     */
    public function registerPostType() {
	    $plural = BookSettings::getInstance()->bookTermPlural();

        $labels = array(
            'name'                  => _x( 'Reviews', 'Post Type General Name', TEXT_DOMAIN ),
            'singular_name'         => _x( 'Review', 'Post Type Singular Name', TEXT_DOMAIN ),
            'menu_name'             => __( 'Reviews', TEXT_DOMAIN ),
            'name_admin_bar'        => __( 'Review', TEXT_DOMAIN ),
            'archives'              => __( 'Review Archives', TEXT_DOMAIN ),
            'attributes'            => __( 'Review Attributes', TEXT_DOMAIN ),
            'parent_item_colon'     => __( 'Parent Review:', TEXT_DOMAIN ),
            'all_items'             => __( 'All Reviews', TEXT_DOMAIN ),
            'add_new_item'          => __( 'Add New Review', TEXT_DOMAIN ),
            'add_new'               => __( 'Add New', TEXT_DOMAIN ),
            'new_item'              => __( 'New Review', TEXT_DOMAIN ),
            'edit_item'             => __( 'Edit Review', TEXT_DOMAIN ),
            'update_item'           => __( 'Update Review', TEXT_DOMAIN ),
            'view_item'             => __( 'View Review', TEXT_DOMAIN ),
            'view_items'            => __( 'View Reviews', TEXT_DOMAIN ),
            'search_items'          => __( 'Search Reviews', TEXT_DOMAIN ),
            'not_found'             => __( 'Not found', TEXT_DOMAIN ),
            'not_found_in_trash'    => __( 'Not found in Trash', TEXT_DOMAIN ),
            'featured_image'        => __( 'Featured Image', TEXT_DOMAIN ),
            'set_featured_image'    => __( 'Set featured image', TEXT_DOMAIN ),
            'remove_featured_image' => __( 'Remove featured image', TEXT_DOMAIN ),
            'use_featured_image'    => __( 'Use as featured image', TEXT_DOMAIN ),
            'insert_into_item'      => __( 'Insert into review', TEXT_DOMAIN ),
            'uploaded_to_this_item' => __( 'Uploaded to this review', TEXT_DOMAIN ),
            'items_list'            => __( 'Reviews list', TEXT_DOMAIN ),
            'items_list_navigation' => __( 'Reviews list navigation', TEXT_DOMAIN ),
            'filter_items_list'     => __( 'Filter reviews list', TEXT_DOMAIN ),
        );
        $args = array(
            'label'                 => __( 'Review', TEXT_DOMAIN ),
            'description'           => __( 'All reviews of ' . $plural . '.', TEXT_DOMAIN ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'revisions' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => false,
            'show_in_menu'          => 'edit.php?post_type=book',  // Put reviews under the Books menu

        );
        register_post_type( self::POST_TYPE, $args );

    }
    // Add the 'Add New Review' option in the Books submenu

    /**
     * adds 'Add New Review' submenu under books menu
     *
     * @return void
     */
    public function addReviewSubmenu() {
        add_submenu_page(
            'edit.php?post_type=book',      // Parent slug (Books menu)
            __('Add New Review', TEXT_DOMAIN), // Page title
            __('Add New Review', TEXT_DOMAIN), // Menu title
            'manage_options',               // Capability
            'post-new.php?post_type=review' // Menu slug (links to 'Add New' review page)
        );
    }

	/**
	 * filters the post content to append custom review metadata
	 * @param string $content
	 * @return string
	 */
	public function reviewContent($content) {
		// Ensure this runs only for 'review' post types
		if(get_post_type() == self::POST_TYPE) {
			$meta = ReviewMeta::getInstance();

			// Get the custom metadata for the review
			$reviewerName = $meta->getReviewerName();
			$reviewerLocation = $meta->getReviewerLocation();
			$starRating = $meta->getStarRating();
			$bookID = $meta->getBookID();

			// Append review metadata and book info to the content
			$content .= '<div class="review-meta">';
			$content .= '<h3>' . __('Review Information', TEXT_DOMAIN) . '</h3>';
			$content .= '<p><strong>' . __('Name', TEXT_DOMAIN) . ':</strong> ' . esc_html($reviewerName) . '</p>';
			$content .= '<p><strong>' . __('Location', TEXT_DOMAIN) . ':</strong> ' . esc_html($reviewerLocation) . '</p>';
			$content .= '<p><strong>' . __('Rating', TEXT_DOMAIN) . ':</strong> ' . esc_html($starRating) . '/5</p>';

			$content .= '</div>';
		}

		return $content;
	}


	/**
	 * Handles the review submission
	 *
	 * @return void
	 */
	public function handleReviewSubmission() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_submit'])) {

			// Check nonce for security
			if (!isset($_POST['review_nonce']) || !wp_verify_nonce($_POST['review_nonce'], 'submit_review')) {
				return; // Nonce check failed
			}

			// Validate and sanitize input
			$reviewTitle = sanitize_text_field($_POST['review_title']);
			$reviewerName = sanitize_text_field($_POST[ReviewMeta::REVIEWER_NAME]);
			$reviewerLocation = sanitize_text_field($_POST[ReviewMeta::REVIEWER_LOCATION]);
			$starRating = intval($_POST[ReviewMeta::STAR_RATING]);
			$content = sanitize_textarea_field($_POST['review_content']);
			$bookID = intval($_POST[ReviewMeta::BOOK_ID]);

			// Create the review post
			$review_post = array(
				'post_title'   => $reviewTitle,
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => self::POST_TYPE,
			);

			// Insert the post into the database
			$post_id = wp_insert_post($review_post);

			// Save custom metadata
			if ($post_id) {
				update_post_meta($post_id, ReviewMeta::REVIEWER_NAME, $reviewerName);
				update_post_meta($post_id, ReviewMeta::REVIEWER_LOCATION, $reviewerLocation);
				update_post_meta($post_id, ReviewMeta::STAR_RATING, $starRating);
				update_post_meta($post_id, ReviewMeta::BOOK_ID, $bookID);
			}

			// Redirect to the current page
			$current_url = esc_url($_SERVER['REQUEST_URI']);
			wp_redirect($current_url);
			exit();
		}
	}

}