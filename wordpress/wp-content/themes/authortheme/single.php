<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WPD_Homework
 */

get_header();
?>

<div id="page" class="site container">
    <main id="primary" class="site-main site-single-page">
        <section class="site-main-col-lg-8">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

            ?>


		<?php endwhile; // End of the loop.
		?>

        </section>
        <section class="site-main-col-lg-4">
            <?php get_sidebar(); ?>
        </section>
    </main><!-- #main -->
</div>

<?php
get_footer();
