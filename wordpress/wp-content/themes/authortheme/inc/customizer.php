<?php
/**
 * WPD Homework Theme Customizer
 *
 * @package WPD_Homework
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function kn_homework_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'kn_homework_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'kn_homework_customize_partial_blogdescription',
			)
		);
	}

	// Add a setting for the site primary color
	$wp_customize->add_setting( 'kn_homework_primary_color', array(
		'default'   => '#1033c5', // Default color
		'sanitize_callback' => 'sanitize_hex_color',
		'transport' => 'postMessage',
	) );

	// Add a control for the site primary color
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kn_homework_primary_color', array(
		'label'    => __( 'Primary Color', 'kn_homework' ),
		'section'  => 'colors', // Use the existing Colors section
		'settings' => 'kn_homework_primary_color',
	) ) );

	// Add a setting for the site secondary color
	$wp_customize->add_setting( 'kn_homework_secondary_color', array(
		'default'   => '#e8c500', // Default color
		'sanitize_callback' => 'sanitize_hex_color',
		'transport' => 'postMessage',
	) );

	// Add a control for the site secondary color
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kn_homework_secondary_color', array(
		'label'    => __( 'Secondary Color', 'kn_homework' ),
		'section'  => 'colors', // Use the existing Colors section
		'settings' => 'kn_homework_secondary_color',
	) ) );

	// Add a setting for the header color
	$wp_customize->add_setting( 'kn_homework_header_color', array(
		'default'   => '#fbfbfb',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport' => 'postMessage',
	) );

	// Add a control for the header color
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'kn_homework_header_color', array(
		'label'    => __( 'Header Color', 'kn_homework' ),
		'section'  => 'colors',
		'settings' => 'kn_homework_header_color',
	) ) );


	$wp_customize->add_section( 'kn_homework_site_header', array(
		'title'    => __( 'Site Header Options', 'kn_homework' ),
		'priority' => 30,
	) );

	// Add a setting for the site title
	$wp_customize->add_setting( 'kn_homework_header_title', array(
		'default'   => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport' => 'postMessage',
	) );

	// Add a control for the site title
	$wp_customize->add_control( 'kn_homework_header_title', array(
		'label'    => __( 'Title', 'kn_homework' ),
		'section'  => 'kn_homework_site_header',
		'type'     => 'text',
	) );

	// Add a setting for the site tagline
	$wp_customize->add_setting( 'kn_homework_header_description', array(
		'default'   => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport' => 'postMessage',
	) );

	// Add a control for the site tagline
	$wp_customize->add_control( 'kn_homework_header_description', array(
		'label'    => __( 'Header Description', 'kn_homework' ),
		'section'  => 'kn_homework_site_header',
		'type'     => 'text',
	) );

	// Add a setting for the site tagline
	$wp_customize->add_setting( 'kn_homework_header_button_tagline', array(
		'default'   => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport' => 'postMessage',
	) );

	// Add a control for the site tagline
	$wp_customize->add_control( 'kn_homework_header_button_tagline', array(
		'label'    => __( 'Button Tagline', 'kn_homework' ),
		'section'  => 'kn_homework_site_header',
		'type'     => 'text',
	) );

	// Add a setting for the site button title
	$wp_customize->add_setting( 'kn_homework_header_button_name', array(
		'default'   => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport' => 'postMessage',
	) );

	// Add a control for the site title
	$wp_customize->add_control( 'kn_homework_header_button_name', array(
		'label'    => __( 'Button Name', 'kn_homework' ),
		'section'  => 'kn_homework_site_header',
		'type'     => 'text',
	) );

	// Add a setting for the title link
	$wp_customize->add_setting( 'kn_homework_header_button_link', array(
		'default'   => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport' => 'postMessage',
	) );

	// Add a control for the title link
	$wp_customize->add_control( 'kn_homework_header_button_link', array(
		'label'    => __( 'Button Link (URL)', 'kn_homework' ),
		'section'  => 'kn_homework_site_header',
		'type'     => 'url',
	) );
}
add_action( 'customize_register', 'kn_homework_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function kn_homework_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function kn_homework_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function kn_homework_customize_preview_js() {
	wp_enqueue_script( 'kn_homework-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), _S_VERSION, true );
}
add_action( 'customize_preview_init', 'kn_homework_customize_preview_js' );
