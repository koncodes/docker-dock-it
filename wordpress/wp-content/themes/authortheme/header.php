<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WPD_Homework
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
    <style>

        :root {
            --color-primary: <?php echo esc_attr( get_theme_mod( 'kn_homework_primary_color', '#1033c5' ) ); ?>;
            --color-secondary: <?php echo esc_attr( get_theme_mod( 'kn_homework_secondary_color', '#e8c500' ) ); ?>;
            --color-header: <?php echo esc_attr( get_theme_mod( 'kn_homework_header_color', '#000000' ) ); ?>
        }
    </style>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'kn_homework' ); ?></a>

	<header id="masthead" class="site-header">
        <div class="site-navigation-con">
            <div class="container site-navigation-wrap">
                <div class="site-branding">
                    <?php
                    the_custom_logo();
                    if ( is_front_page() ) :
                        ?>
                        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                        <?php
                    else :
                        ?>
                        <span class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span>
                        <?php
                    endif; ?>
                </div><!-- .site-branding -->

                <nav id="site-navigation" class="main-navigation">
                    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                    </button>
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'menu-1',
                            'menu_id'        => 'primary-menu',
                        )
                    );
                    ?>
                </nav><!-- #site-navigation -->
            </div>
        </div>
        <?php if ( ( is_front_page() || is_customize_preview() ) ){ ?>
            <div class="header-banner">
                <img class="header-banner-img" src="<?php esc_url(header_image()); ?>" alt="Header bannner image">
                <div class="header-banner-text">
                    <?php $kn_homework_header_title = esc_html( get_theme_mod( 'kn_homework_header_title', 'Header Title' ) );
                    if ( $kn_homework_header_title || is_customize_preview() ) :
                        ?>
                        <div class="header-banner-title">
                            <span>
                                <?php echo $kn_homework_header_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </span>
                        </div>
                    <?php endif; ?>
	                <?php $kn_homework_header_description = esc_html( get_theme_mod( 'kn_homework_header_description', '' ) );
	                if ( $kn_homework_header_description || is_customize_preview() ) :
		                ?>
                        <div class="header-banner-description">
                                <span>
                                    <?php echo $kn_homework_header_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </span>
                        </div>
	                <?php endif; ?>
                    <div class="header-banner-subtitle">
                        <?php $kn_homework_header_button_tagline = esc_html( get_theme_mod( 'kn_homework_header_button_tagline', '' ) );
                        if ( $kn_homework_header_button_tagline || is_customize_preview() ) :
                            ?>
                            <div class="header-banner-button-tagline">
                                <span>
                                    <?php echo $kn_homework_header_button_tagline; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <?php $kn_homework_header_button_name = esc_html( get_theme_mod( 'kn_homework_header_button_name', 'Button' ) );
                        if ( $kn_homework_header_button_name || is_customize_preview() ) :
                            ?>
                            <a class="header-banner-button-link" href="<?php echo esc_url( get_theme_mod( 'kn_homework_header_button_link', '/' ) ); ?>">
                                <button class="header-banner-button">
                                    <span>
                                        <?php echo $kn_homework_header_button_name; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    </span>
                                </button>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php } ?>
		<?php if ( !is_front_page() ){ ?>
            <div class="header-banner title-banner">
                <img class="header-banner-img" src="<?php esc_url(header_image()); ?>" alt="Header bannner image">
                <h1>
                    <span>
                        <?php
                            if ( is_archive() ) {
	                            the_archive_title( '', '' );
                            } else {
	                            wp_title('');
                            }
                        ?>
                    </span>
                </h1>
            </div>
        <?php } ?>
    </header><!-- #masthead -->
