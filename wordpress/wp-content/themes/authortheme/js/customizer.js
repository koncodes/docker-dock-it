/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					clip: 'rect(1px, 1px, 1px, 1px)',
					position: 'absolute',
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					clip: 'auto',
					position: 'relative',
				} );
				$( '.site-title a, .site-description' ).css( {
					color: to,
				} );
			}
		} );
	} );
	// Update the primary color CSS variable
	wp.customize( 'kn_homework_primary_color', function( value ) {
		value.bind( function( newval ) {
			document.documentElement.style.setProperty('--color-primary', newval);
		} );
	} );

	// Update the secondary color CSS variable
	wp.customize( 'kn_homework_secondary_color', function( value ) {
		value.bind( function( newval ) {
			document.documentElement.style.setProperty('--color-secondary', newval);
		} );
	} );

	// Update the header color CSS variable
	wp.customize( 'kn_homework_header_color', function( value ) {
		value.bind( function( newval ) {
			document.documentElement.style.setProperty('--color-header', newval);
		} );
	} );

	// Update the header title text
	wp.customize( 'kn_homework_header_title', function( value ) {
		value.bind( function( newval ) {
			$('.header-banner-title span').text(newval);
		} );
	} );

	// Update the header description text
	wp.customize( 'kn_homework_header_description', function( value ) {
		value.bind( function( newval ) {
			$('.header-banner-description span').text(newval);
		} );
	} );

	// Update the header button tagline
	wp.customize( 'kn_homework_header_button_tagline', function( value ) {
		value.bind( function( newval ) {
			$('.header-banner-button-tagline span').text(newval);
		} );
	} );

	// Update the button text
	wp.customize( 'kn_homework_header_button_name', function( value ) {
		value.bind( function( newval ) {
			$('.header-banner-button span').text(newval);
		} );
	} );

	// Update the button link href
	wp.customize( 'kn_homework_header_button_link', function( value ) {
		value.bind( function( newval ) {
			$('.header-banner-button-link').attr('href', newval);
		} );
	} );
}( jQuery ) );
