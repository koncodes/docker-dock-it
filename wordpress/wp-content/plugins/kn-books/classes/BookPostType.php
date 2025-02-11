<?php

namespace KN\BookPlugin;

use DateTime;
use WP_Query;

class BookPostType extends Singleton
{
    const POST_TYPE = 'book';

    /**
     * static property to hold our singleton instance
     */
    protected static $instance;

    /**
     * constructor to registers hooks for book post type
     * @return void
     */
    public function __construct()
    {
        add_action( 'init', [$this, 'registerPostType'], 0 );
        add_filter('the_content', [$this, 'bookContent']);
//        add_filter('the_excerpt', [$this, 'bookExcerpt']);
    }

    /**
     * register custom post type of book
     * @return void
     */
    public function registerPostType() {

	    $singular = BookSettings::getInstance()->bookTermSingular();
	    $plural = BookSettings::getInstance()->bookTermPlural();

        $labels = array(
            'name'                  => _x( $plural, 'Post Type General Name', TEXT_DOMAIN ),
            'singular_name'         => _x(  $singular, 'Post Type Singular Name', TEXT_DOMAIN ),
            'menu_name'             => __( 'Books', TEXT_DOMAIN ),
            'name_admin_bar'        => __(  $singular, TEXT_DOMAIN ),
            'archives'              => __(  $plural, TEXT_DOMAIN ),
            'attributes'            => __( $singular . ' Attributes', TEXT_DOMAIN ),
            'parent_item_colon'     => __( 'Parent ' . $singular . ':', TEXT_DOMAIN ),
            'all_items'             => __( 'All ' . $plural, TEXT_DOMAIN ),
            'add_new_item'          => __( 'Add New ' . $singular, TEXT_DOMAIN ),
            'add_new'               => __( 'Add New ' . $singular, TEXT_DOMAIN ),
            'new_item'              => __( 'New ' . $singular, TEXT_DOMAIN ),
            'edit_item'             => __( 'Edit ' . $singular, TEXT_DOMAIN ),
            'update_item'           => __( 'Update ' . $singular, TEXT_DOMAIN ),
            'view_item'             => __( 'View ' . $singular, TEXT_DOMAIN ),
            'view_items'            => __( 'View ' . $plural, TEXT_DOMAIN ),
            'search_items'          => __( 'Search ' . $plural, TEXT_DOMAIN ),
            'not_found'             => __( 'Not found', TEXT_DOMAIN ),
            'not_found_in_trash'    => __( 'Not found in Trash', TEXT_DOMAIN ),
            'featured_image'        => __( 'Featured Image', TEXT_DOMAIN ),
            'set_featured_image'    => __( 'Set featured image', TEXT_DOMAIN ),
            'remove_featured_image' => __( 'Remove featured image', TEXT_DOMAIN ),
            'use_featured_image'    => __( 'Use as featured image', TEXT_DOMAIN ),
            'insert_into_item'      => __( 'Insert into ' . strtolower($singular), TEXT_DOMAIN ),
            'uploaded_to_this_item' => __( 'Uploaded to this ' . strtolower($singular), TEXT_DOMAIN ),
            'items_list'            => __( $plural . ' list', TEXT_DOMAIN ),
            'items_list_navigation' => __( $plural . ' list navigation', TEXT_DOMAIN ),
            'filter_items_list'     => __( 'Filter ' . strtolower($plural) . ' list', TEXT_DOMAIN ),
        );
        $args = array(
            'label'                 => __( $singular, TEXT_DOMAIN ),
            'description'           => __( $plural, TEXT_DOMAIN ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
            'taxonomies'            => array( BookGenre::TAXONOMY ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
            'menu_icon'             => 'dashicons-book',
            'rewrite'               => array(
	            'slug'       => strtolower($singular),
	            'with_front' => false,
            ),
            'has_archive'           => strtolower($singular),

        );
        register_post_type( self::POST_TYPE, $args );

    }

    /**
     * edit book content to include meta
     * @param string $content
     * @return string
     */
    public function bookContent($content) {
        // ensure this only runs for books
        if (get_post_type() == self::POST_TYPE) {
            $meta = BookMeta::getInstance();

            $bookISBN = $meta->getBookISBN();
            $publisherName = $meta->getPublisherName();
            $publisherImprint = $meta->getPublisherImprint();

            $publishDate = $meta->getPublishDate();
	        $publishDate = new DateTime($publishDate);
	        $publishDate = $publishDate->format('M j, Y');

            $pageCount = $meta->getPageCount();
            $bookPrice = $meta->getBookPrice();

            // add book metadata to variable
            $bookMetaContent = '<div class="book-meta">
                <h3>' . __('Details', TEXT_DOMAIN) . '</h3>' .
                               ($bookISBN ? '<div>
                	<span class="book-meta-label">' . __('ISBN', TEXT_DOMAIN) . '</span> 
                	<span class="book-meta-text">' . $bookISBN . '</span>
                </div>' : '') .
                               ($publisherName ? '<div>
                	<span class="book-meta-label">' . __('Publisher', TEXT_DOMAIN) . '</span> 
                	<span class="book-meta-text">' . $publisherName . '</span>
                </div>' : '') .
                               ($publisherImprint ? '<div>
                	<span class="book-meta-label">' . __('Imprint', TEXT_DOMAIN) . '</span> 
                	<span class="book-meta-text">' . $publisherImprint . '</span>
                </div>' : '') .
                               ($publishDate ? '<div>
                	<span class="book-meta-label">' . __('Publish Date', TEXT_DOMAIN) . '</span> 
                	<span class="book-meta-text">' . $publishDate . '</span>
                </div>' : '') .
                               ($pageCount ? '<div>
                	<span class="book-meta-label">' . __('Page Count', TEXT_DOMAIN) . '</span> 
                	<span class="book-meta-text">' . $pageCount . '</span>
                </div>' : '') . '
            </div>';

	        // get genres associated with the book
	        $bookMetaGenres = '';
	        $genres = wp_get_post_terms(get_the_ID(), 'book_genre');

	        if (!empty($genres)) {
		        $bookMetaGenres .= '<div class="book-genres"><span class="book-genres-title">' . __('Genres', TEXT_DOMAIN) . '</span>';

		        foreach ($genres as $genre) {
					$bookMetaGenres .= '<span><a href="' . esc_url( get_term_link($genre) ) . '">' . esc_html($genre->name) . '</a></span>';
		        }

		        $bookMetaGenres .= '</div>';
	        }

	        $content = $content . $bookMetaGenres . $bookMetaContent;

            wp_reset_postdata();
        }
        return $content;
    }

//    /**
//     * edit book excerpt to include meta
//     * @param string $excerpt
//     * @return string
//     */
//    public function bookExcerpt($excerpt) {
//        // ensure this only runs for books
//        if (get_post_type() == self::POST_TYPE) {
//            $meta = BookMeta::getInstance();
//
//	        $bookMetaExcerpt = '';
//
//	        $bookISBN = $meta->getBookISBN();
//            $publisherName = $meta->getPublisherName();
//
//
//            $bookMetaExcerpt = '<div class="book-meta-excerpt">
//	            <div>
//	            	<span class="book-meta-label">' . __('ISBN', TEXT_DOMAIN) . '</span>
//	                <span class="book-meta-text">' . $bookISBN . '</span>
//	            </div>
//	                <div><span>' . __('Publisher', TEXT_DOMAIN) . '</span>
//	                <span class="book-meta-text">' . $publisherName . '</span>
//	            </div>
//            </div>';
//
//            // get reviews associated with the book
////            $book_id = get_the_ID();
////            $args = [
////                'post_type' => 'review',
////                'meta_query' => [
////                    [
////                        'key' => 'reviewBookID',
////                        'value' => $book_id,
////                        'compare' => '='
////                    ]
////                ]
////            ];
////
////            $reviews = new WP_Query($args);
////
////            if ($reviews->have_posts()) {
////                $totalRating = 0;
////                $totalReviews = 0;
////
////                while ($reviews->have_posts()) {
////                    $reviews->the_post();
////                    $starRating = get_post_meta(get_the_ID(), 'reviewStarRating', true);
////
////                    $totalRating += (int) $starRating;
////                    $totalReviews++;
////                }
////                $averageRating = $totalRating / $totalReviews;
////                $averageStars = str_repeat('★', round($averageRating)) . str_repeat('☆', 5 - round($averageRating));
////
////                $bookMetaExcerpt = '<div class="book-meta-rating">
////                    <div class="book-meta-average-stars">
////                    	<span class="book-meta-stars">' . $averageStars . '</span>
////                    	<span class="book-meta-stars-num">' . number_format($averageRating, 2) . '</span>
////                    	<span class="book-meta-label">' . __('Stars', TEXT_DOMAIN) . '</span>
////                 	</div>
////                 	<div class="book-meta-reviewers">
////	                    <span class="book-meta-reviewers-num">' . $totalReviews . '</span>
////	                    <span class="book-meta-label">' . (($totalReviews == 1) ? __('Review', TEXT_DOMAIN) : __('Reviews', TEXT_DOMAIN)) . '</span>
////	                </div>
////                </div>' . $bookMetaExcerpt;
////
////            }
//
//            $excerpt = $bookMetaExcerpt . $excerpt;
//            wp_reset_postdata();
//
//        }
//        return $excerpt;
//    }
}