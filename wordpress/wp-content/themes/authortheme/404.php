<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WPD_Homework
 */

get_header();
?>
<div id="page" class="site container">
	<main id="primary" class="site-main 404-page">
		<section class="error-404 not-found">
			<header class="page-header">
                <h1 class="page-title"><?php esc_html_e( '404', 'kn_homework' ); ?></h1>
                <h3><?php esc_html_e( 'Your Page Was Not Found', 'kn_homework' ); ?></h3>
			</header><!-- .page-header -->

			<div class="page-content">
				<p><?php esc_html_e( 'Use the button below to go to main page of the site.', 'kn_homework' ); ?></p>

                <span class="button home-link"><a href="index.html"><?php esc_html_e( 'Go to Home', 'kn_homework' ); ?></a></span>

			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->
</div>

<?php
get_footer();
