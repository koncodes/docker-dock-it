<?php
/**
 * The template for displaying all single-book posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package kn-books
 */

use const KN\BookPlugin\PLUGIN_FILE;

get_header();
?>

	<div id="page" class="site container">
		<main id="primary" class="site-main site-single-book">
			<section class="site-main-col-lg-8">

				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', 'book' );

					?>


				<?php endwhile; // End of the loop.
				?>

			</section>
			<section class="site-main-col-lg-4">
                <aside id="secondary" class="widget-area">
                    <h2 class="wp-block-heading">Other Recent Books</h2>
	                <?php echo do_shortcode('[recent-books posts_per_page="3"]'); ?>
                </aside><!-- #secondary -->
			</section>
            <!-- Navigation Links -->
            <section class="book-navigation">
                <div class="nav-previous"><?php previous_post_link('%link', '%title'); ?></div>
                <div class="nav-next"><?php next_post_link('%link', '%title'); ?></div>
            </section>
		</main><!-- #main -->
	</div>

<?php
get_footer();
