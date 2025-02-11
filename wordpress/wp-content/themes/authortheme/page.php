<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WPD_Homework
 */

get_header();
?>

<div id="page" class="site container">
    <main id="primary" class="site-main  site-page">
        <section class="site-main-col-lg-8">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) :
                                the_post();

                                get_template_part( 'template-parts/content', 'page' );

                                // If comments are open, or we have at least one comment, load up the comment template.
                                if ( comments_open() || get_comments_number() ) :
                                    comments_template();
                                endif;

                endwhile;
	            the_posts_navigation();
            else :
	            get_template_part( 'template-parts/content', 'none' );
            endif;
            ?>

        </section>
<!--        <section class="site-main-col-lg-4">-->
<!--            --><?php //get_sidebar(); ?>
<!--        </section>-->
    </main><!-- #main -->
</div><!-- #main -->

<?php
get_footer();
