<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WPD_Homework
 */

use KN\BookPlugin\BookSettings;
use KN\BookPlugin\ReviewMeta;
use const KN\BookPlugin\TEXT_DOMAIN;

$book_id = get_the_ID();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="image-header"><?php kn_homework_post_thumbnail(); ?></div>
    <div class="entry-con">

        <header class="entry-header">
            <a class="entry-title-link" href="<?php the_permalink(); ?>">
                <?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
            </a>
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
            if (is_archive()) {
                if (has_excerpt()) {
                    the_excerpt(); // Display the post excerpt if available
                } else {
                    // Display shortened content if no excerpt is available
                    echo '<p>' . wp_trim_words(get_the_content(), 30, '...') . '</p>'; // Shorten content to 30 words with '...' at the end
                }
            } else{
                the_content(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'kn_homework' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post( get_the_title() )
                    )
                );
            };
            ?>
        </div><!-- .entry-content -->
        <div class="entry-footer">
            <?php kn_homework_entry_footer(); ?>
            <span class="read-more button">
                <a href="<?php the_permalink(); ?>">
                    <?php esc_html_e( 'Read More', 'kn_homework' ); ?>
                </a>
            </span>
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
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
