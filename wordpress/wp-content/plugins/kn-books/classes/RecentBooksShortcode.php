<?php

namespace KN\BookPlugin;

use DateTime;
use WP_Query;

class RecentBooksShortcode extends Singleton
{

    /**
     * Static property to hold our singleton instance
     *
     */
    protected static $instance;

    /**
     * Constructor - Registers the shortcode
     *
     * @return void
     */
    protected function __construct() {
        add_shortcode('recent-books', [$this, 'displayRecentBooks']);
    }

    /**
     * Shortcode function to output recent books.
     *
     * @param array $attributes Attributes passed with the shortcode
     * @return string HTML output for the shortcode
     */
    public function displayRecentBooks(array $attributes = []): string
    {

        $a = shortcode_atts([
            'posts_per_page' => 5, // Default number of posts
        ], $attributes, 'recent-books');

	    // Get current post ID if we're on a single book page
	    $exclude_id = (is_singular('book')) ? get_the_ID() : 0;

	    $args = [
            'post_type'      => 'book',
            'posts_per_page' => (int)$a['posts_per_page'],
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post__not_in'   => [$exclude_id], // Exclude current book page
	    ];

        $recentBooks = new WP_Query($args);

        if ($recentBooks->have_posts()) {
            $output = '<div class="recent-books-con"><div class="recent-books">';
            while ($recentBooks->have_posts()) {
                $recentBooks->the_post();
                $output .= '<div class="book-item">';
	            if (has_post_thumbnail()) {
		            $output .= get_the_post_thumbnail(get_the_ID(), 'full');
	            }
                $output .= '<div class="book-item-text"><h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';

	            $publisherName = get_post_meta(get_the_ID(), 'bookPublisherName', true);
	            if ($publisherName) {
		            $output .= '<div class="book-publisher">' . esc_html($publisherName) . '</div>';
	            }
	            $publishDate = get_post_meta(get_the_ID(), 'bookPublishDate', true);
	            $date = new DateTime($publishDate);
	            $formattedPublishDate = $date->format('M j, Y');
	            if ($formattedPublishDate) {
		            $output .= '<div class="book-date">' . __('Published', TEXT_DOMAIN) . ' ' . esc_html($formattedPublishDate) . '</div>';
	            }
	            $output .= '<div class="book-excerpt">' . get_the_excerpt() . '</div>';
	            $bookCost = get_post_meta(get_the_ID(), 'bookBookCost', true);
	            if ($bookCost) {
		            $output .= '<div class="book-cost">$' . esc_html( number_format($bookCost, 2) ) . '</div>';
	            }
				$output .= '</div></div>';
            }
            $output .= '</div></div>';
            wp_reset_postdata();
        } else {
	        $plural = BookSettings::getInstance()->bookTermPlural();
	        $output = '<p>' . __('No ' . $plural . ' found.', TEXT_DOMAIN) . '</p>';
        }
        return $output;
    }
}
