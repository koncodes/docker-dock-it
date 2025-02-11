<?php
/**
 * The template for displaying single review posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WPD_Homework
 */
use KN\BookPlugin\ReviewMeta;
$reviewMeta = ReviewMeta::getInstance();
$book_id = $reviewMeta->getBookID();

get_header();
?>

    <div id="page" class="site container">
        <main id="primary" class="site-main site-single-review">
            <section class="site-main-col-lg-8 single-review-page">
                <div class="post-thumbnail">
                    <?php echo has_post_thumbnail($book_id) ? get_the_post_thumbnail($book_id, 'full') : '' ?>
                </div>
                <div class="entry-con">
                    <?php
                    while ( have_posts() ) :
                        the_post();

                        get_template_part( 'template-parts/content', get_post_type() );

                        ?>


                    <?php endwhile; // End of the loop.
                    ?>
                </div>
            </section>
            <section class="site-main-col-lg-4">
				<?php get_sidebar(); ?>
            </section>
        </main><!-- #main -->
    </div>

<?php
get_footer();
