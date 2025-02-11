<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WPD_Homework
 */

?>

	<footer id="colophon" class="site-footer">
        <div class="footer-1">
            <div class="container">
                <?php dynamic_sidebar( 'footer-1' ); ?>
            </div>
        </div>
        <div class="footer-2">
            <div class="container">
                <?php dynamic_sidebar( 'footer-2' ); ?>
            </div>
        </div>
        <div class="site-info">
            <div class="container">
                <span class="site-info-wordpress">
                    <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'kn_homework' ) ); ?>">
                        <?php
                        /* translators: %s: CMS name, i.e. WordPress. */
                        printf( esc_html__( 'Proudly powered by %s', 'kn_homework' ), 'WordPress' );
                        ?>
                    </a>
                </span>
                <span class="sep"> | </span>
                <span class="site-info-theme">
                    <?php
                    /* translators: 1: Theme name, 2: Theme author. */
                    printf( esc_html__( '%1$s by %2$s', 'kn_homework' ), 'Author Site', '<a href="http://underscores.me/">Konika Nahar</a>' );
                    ?>
                </span>
            </div>
        </div><!-- .site-info -->
	</footer><!-- #colophon -->
    <!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
