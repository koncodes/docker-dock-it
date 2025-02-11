<?php

namespace KN\BookPlugin;

use WP_Query;

class ReviewShortcode extends Singleton
{
	/**
	 * Static property to hold our singleton instance
	 */
	protected static $instance;

	/**
	 * Constructor - Registers the shortcode
	 *
	 * @return void
	 */
	protected function __construct() {
		add_shortcode('random-review', [$this, 'displayRandomReview']);
	}

	/**
	 * Shortcode function to output a random book review.
	 *
	 * @return string HTML output for the shortcode
	 */
	public function displayRandomReview($atts) {
		$atts = shortcode_atts([
			'count' => 1,
		], $atts);

		$args = [
			'post_type' => 'review',
			'posts_per_page' => (int)$atts['count'], // Use the 'count' attribute
			'orderby' => 'rand', // Get random reviews
			'meta_query' => [
				[
					'key' => 'reviewStarRating',
					'value' => [4, 5], // Only get reviews with 4 or 5 stars
					'compare' => 'IN',
					'type' => 'NUMERIC',
				],
			],
		];

		// Initialize WP_Query
		$query = new WP_Query($args);

		// Ensure that $query is a valid WP_Query object
		if ($query->have_posts()) {
			$output = '';
			while ($query->have_posts()) {
				$query->the_post();

				$bookID = get_post_meta(get_the_ID(), 'reviewBookID', true);

				$output .= '<div class="random-review">';

				$bookImage = has_post_thumbnail($bookID) ? get_the_post_thumbnail($bookID, 'full') : '';
				$output .= '<div class="random-review-img">' . $bookImage . '</div>';

				$output .= '<div class="random-review-details">';

				$starRating = get_post_meta(get_the_ID(), 'reviewStarRating', true);
				$stars = str_repeat('★', (int)$starRating) . str_repeat('☆', 5 - (int)$starRating);
				if ($starRating) {
					$output .= '<div class="random-review-stars">' . esc_html($stars) . ' (' . esc_html($starRating) . ') ' . __('Stars', TEXT_DOMAIN) . '</div>';
				}

				$output .= '<div class="random-review-content">"' . esc_html(get_the_content()) . '"</div>';

				$output .= '<div class="random-reviewer">';
				$reviewerName = get_post_meta(get_the_ID(), 'reviewReviewerName', true);
				if ($reviewerName) {
					$output .= '<span class="random-review-name">— ' . esc_html($reviewerName) . '</span>';
				}
				$reviewerLocation = get_post_meta(get_the_ID(), 'reviewReviewerLocation', true);
				if ($reviewerLocation) {
					$output .= '<span class="random-review-location"> (' . esc_html($reviewerLocation) . ')</span>';
				}
				$bookTitle = get_the_title($bookID);
				$bookLink = get_permalink($bookID);
				$output .= '<span class="review-title">' . __(' about', TEXT_DOMAIN) . ' <a href="' . esc_url($bookLink) . '">' . esc_html($bookTitle) . '</a></span>';

				$reviewDate = get_the_date('M j, Y'); // Format the date as needed
				if ($reviewDate) {
					$output .= '<span class="random-review-date">' . __(' on ', TEXT_DOMAIN) . esc_html($reviewDate) . '</span>';
				}

				$output .= '</div></div></div>';
			}
			wp_reset_postdata();
			return $output;
		}
		return '<p>' . __('No reviews found.', TEXT_DOMAIN) . '</p>'; // Fallback if no reviews are found
	}
}