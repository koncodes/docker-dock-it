<?php
/**
 * Template part for displaying posts
 *
 *
 * @package kn-books
 */

use KN\BookPlugin\BookMeta;
use KN\BookPlugin\BookSettings;
use KN\BookPlugin\ReviewMeta;
use const KN\BookPlugin\TEXT_DOMAIN;

$book_id = get_the_ID();
$bookMeta = BookMeta::getInstance();
$bookSettings = BookSettings::getInstance();
?>

<article id="post-<?php the_ID(); ?>" class="content-book">

    <div class="post-sidebar">
        <div class="post-sidebar-sticky">
            <?php kn_homework_post_thumbnail(); ?>
            <div class="book-buy button">
                <?php

                $bookCost = $bookMeta->getBookPrice();
                $purchaseLink = $bookMeta->getPurchaseLink();
                $showPurchaseLink = $bookSettings->showPurchaseLink();; // Default to true if not set

                if ($bookCost) {
                    echo '<span>$' . esc_html( number_format($bookCost, 2) ) . '</span>';
                }
                if ($showPurchaseLink && $purchaseLink) {
                    echo '<a href="' . esc_url( $purchaseLink ) . '"><i class="fas fa-arrow-up-right-from-square"></i></a>';
                }
                ?>
            </div>
        </div>
    </div>

    <header class="entry-header">
		<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>

        <?php
		$showBookReviews = BookSettings::getInstance()->showBookReivews();
		if ($showBookReviews) {
			$reviewPosts = '';

			$args    = [
				'post_type'  => 'review',
				'meta_query' => [
					[
						'key'     => 'reviewBookID',
						'value'   => $book_id,
						'compare' => '='
					]
				]
			];

			$reviews = new WP_Query( $args );

			if ( $reviews->have_posts() ) {
				$totalRating  = 0;
				$totalReviews = 0;

				while ( $reviews->have_posts() ) {
					$reviews->the_post();
					$reviewMeta = ReviewMeta::getInstance();
					$starRating = $reviewMeta->getStarRating();
					$stars = str_repeat( '★', (int) $starRating ) . str_repeat( '☆', 5 - (int) $starRating );

					$reviewPosts .= ' <div class="book-review">
                            <div class="book-reviewer">
							    <span class="book-review-name">' . $reviewMeta->getReviewerName() . '</span>
							    <span class="book-review-location">' . $reviewMeta->getReviewerLocation() . '</span>
							</div>
		                    <div class="book-review-body">
                                <div class="book-review-stars-con">
                                    <span>Rated </span>
                                    <span class="book-review-stars">' . $stars . '</span> 
                                    <span class="book-review-stars-num">(' . $starRating . ')</span> 
                                    <span class="book-review-label">' . __( 'Stars', TEXT_DOMAIN ) . '</span>
                                    <span class="book-review-date">On ' . get_the_date( 'M j, Y' ) . '</span>
                                </div>
                                <h4 class="book-review-title">' . get_the_title() . '</h4>
                                <div class="book-review-content">
                                    "' . get_the_content() . '"
                                </div>
	                        </div>
							
	                    </div>';

					$totalRating += (int) $starRating;
					$totalReviews ++;
				}
				$averageRating = $totalRating / $totalReviews;
				$averageStars  = str_repeat( '<span class="star-solid">★</span>', round( $averageRating ) ) . str_repeat( '<span class="star-empty">☆</span>', 5 - round( $averageRating ) );

				echo '<div class="book-meta-rating">
                        <div class="book-meta-average-stars">
	                        <span class="book-meta-stars">' . $averageStars . '</span> 
	                        <span class="book-meta-stars-num">' . number_format( $averageRating, 2 ) . '</span> 
	                        <span class="book-meta-label">' . __( 'Stars', TEXT_DOMAIN ) . '</span>
	                    </div>
	                    <div class="book-meta-reviewers">
		                    <span class="book-meta-reviewers-num">' . $totalReviews . '</span> 
		                    <span class="book-meta-label">' . (($totalReviews == 1) ? __('Review', TEXT_DOMAIN) : __('Reviews', TEXT_DOMAIN)) . '</span>
		                </div> 
	                </div>';
			}
			wp_reset_postdata();
		}
        ?>
	</header><!-- .entry-header -->


	<div class="entry-content">
        <?php
            the_content();
		?>


	</div><!-- .entry-content -->

    <?php
    if ($showBookReviews) {
	    echo '<div class="book-reviews">';
        echo '<h3>' . __( 'Reviews', TEXT_DOMAIN ) . '</h3>';

	    if ( $reviewPosts ) {
            echo '<div class="book-reviews-con">' . $reviewPosts . '</div>';
	    } else {
		    echo '<div class="no-book-reviews">' . __( 'No reviews available for this book.', TEXT_DOMAIN ) . '</div>';
	    }

	    if ( is_user_logged_in() ) {
		    ?>
            <div class="leave-review">
                <input id="review-form-toggle" class="review-form-toggle" name="review-form-toggle" value="false" type="checkbox">
                <label class="review-form-toggle-label" for="review-form-toggle">Leave a Review for <?php echo get_the_title() ?></label>
                <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
		            <?php wp_nonce_field('submit_review', 'review_nonce'); ?>
                    <div class="d-flex">
                        <div class="">
                            <label for="review_title" class="form-label"><?php echo __( 'Review Title', TEXT_DOMAIN ); ?></label>
                            <input type="text" id="review_title" name="review_title" class="form-control" required>
                        </div>
                        <div class="">
                            <label for="star_rating" class="form-label"><?php echo __( 'Star Rating', TEXT_DOMAIN ); ?></label>
                            <select id="star_rating" name="<?= ReviewMeta::STAR_RATING ?>" class="form-select" required>
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="">
                            <label for="reviewer_name" class="form-label"><?php echo __( 'Your Name', TEXT_DOMAIN ); ?></label>
                            <input type="text" id="reviewer_name" name="<?= ReviewMeta::REVIEWER_NAME ?>" class="form-control" required>
                        </div>
                        <div class="">
                            <label for="reviewer_location" class="form-label"><?php echo __( 'Your Location', TEXT_DOMAIN ); ?></label>
                            <input type="text" id="reviewer_location" name="<?= ReviewMeta::REVIEWER_LOCATION ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="">
                        <div>
                            <label for="review_content" class="form-label"><?php echo __( 'Your Review', TEXT_DOMAIN ); ?></label>
                            <textarea id="review_content" name="review_content" class="form-control" rows="2" required></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="<?= ReviewMeta::BOOK_ID ?>" value="<?= esc_attr(get_the_ID()); ?>">

                    <button type="submit" name="review_submit" class="button"><?php echo __( 'Submit Review', TEXT_DOMAIN ); ?></button>
                </form>
            </div>
		    <?php
	    }
	    echo '</div>';
    }

    ?>
    <div class="entry-footer">
        <?php kn_homework_entry_footer(); ?>
        <?php if ( get_edit_post_link() ) : ?>
            <?php
            edit_post_link(
                sprintf(
                    wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                        __( 'Edit <span class="screen-reader-text">%s</span>', 'kn_homework' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post( get_the_title() )
                ),
                '<span class="edit-link button">',
                '</span>'
            );
            ?>

        <?php endif; ?>
    </div><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
