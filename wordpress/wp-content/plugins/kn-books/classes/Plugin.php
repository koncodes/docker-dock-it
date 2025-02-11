<?php

namespace KN\BookPlugin;

class Plugin extends Singleton
{

    /**
     * plugin constructor
     *
     * registers activation and deactivation hooks, and initializes components
     */
    public function __construct() {
        // Add activation and deactivation hooks
        register_activation_hook(PLUGIN_FILE, [$this, 'activatePlugin']); // Use PLUGIN_FILE constant

        // Initialize post types and shortcodes
        BookPostType::getInstance();
        BookGenre::getInstance();
        BookMeta::getInstance();
        ReviewPostType::getInstance();
        ReviewMeta::getInstance();
        ReviewShortcode::getInstance();
        RecentBooksShortcode::getInstance();
        BookSettings::getInstance();

	    add_action('wp_enqueue_scripts', [$this, 'enqueueStyles']);

    }

    /**
     * activates the plugin
     * called when the plugin is activated
     * manually registers the post types and flushes the permalink cache
     *
     * @return void
     */
    public function activatePlugin() {
        //manually register the post type
        BookPostType::getInstance()->registerPostType();
        ReviewPostType::getInstance()->registerPostType();
        BookGenre::getInstance()->registerGenre();
        ReviewShortcode::getInstance()->displayRandomReview();
        RecentBooksShortcode::getInstance()->displayRecentBooks();


        //flush the permalink cache
        flush_rewrite_rules();
    }

	/**
	 * enqueue plugin styles
	 * @return void
	 */
	public function enqueueStyles() {
		wp_enqueue_style(
			'kn-books-style',
			plugin_dir_url(PLUGIN_FILE) . 'css/kn-books.css',
			[],  // Dependencies
			'1.0.0' // Version number
		);
	}

//	/**
//	 * load a custom template for single book posts
//	 * @param string $template
//	 * @return string
//	 */
//	public function loadBookTemplate($template) {
//		if (is_singular('book')) {
//			// Look for the custom single-book.php in the plugin's templates folder
//			$plugin_template = plugin_dir_path(PLUGIN_FILE) . 'templates/single-book.php';
//
//			if (file_exists($plugin_template)) {
//				return $plugin_template;
//			}
//		}
//		return $template; // If not a single book, use the default template
//	}
//	/**
//	 * load a custom template for archive book page
//	 * @param string $template
//	 * @return string
//	 */
//	public function loadArchiveBookTemplate($template) {
//		if (is_post_type_archive('book')) {
//			// Look for the custom archive-book.php in the plugin's templates folder
//			$plugin_template = plugin_dir_path(PLUGIN_FILE) . 'templates/archive-book.php';
//
//			if (file_exists($plugin_template)) {
//				return $plugin_template;
//			}
//		}
//		return $template; // If not the book archive, use the default template
//	}
//	/**
//	 * load a custom template for archive book page
//	 * @param string $template
//	 * @return string
//	 */
//	public function loadReviewTemplate($template) {
//		if (is_singular('review')) {
//			// Look for the custom single-review.php in the plugin's templates folder
//			$plugin_template = plugin_dir_path(PLUGIN_FILE) . 'templates/single-review.php';
//
//			if (file_exists($plugin_template)) {
//				return $plugin_template;
//			}
//		}
//		return $template; // If not the book archive, use the default template
//	}
}