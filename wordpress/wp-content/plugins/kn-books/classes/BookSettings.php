<?php

namespace KN\BookPlugin;

class BookSettings extends Singleton
{
    /**
     * constants for the keys used to store settings in the database
     */
	const SHOW_PURCHASE_LINK = 'showPurchaseLink';
	const SHOW_BOOK_REVIEWS = 'showBookReviews';
	const BOOK_TERM_SINGULAR = 'bookTermSingular';
	const BOOK_TERM_PLURAL = 'bookTermPlural';


	const SETTINGS_GROUP = 'book-settings';


	/**
     * @var BookSettings|null the singleton instance of this class
     */
    protected static $instance;

    /**
     * BookSettings constructor
     *
     * registers settings and admin menus
     */
    public function __construct()
    {
        add_action( 'admin_init', [$this, 'registerSettings'], 0 );
        add_action( 'admin_menu', [$this, 'addMenuPages']);
    }

    /**
     * registers the settings
     *
     * @return void
     */
    function registerSettings() {
	    register_setting(self::SETTINGS_GROUP, self::SHOW_PURCHASE_LINK);
	    register_setting(self::SETTINGS_GROUP, self::SHOW_BOOK_REVIEWS);
	    register_setting(self::SETTINGS_GROUP, self::BOOK_TERM_SINGULAR);
	    register_setting(self::SETTINGS_GROUP, self::BOOK_TERM_PLURAL);

	    $this->addFields();
    }

    /**
     * adds menu pages
     *
     * @return void
     */
    public function addMenuPages() {

        add_submenu_page(
                'edit.php?post_type=book',
            'Book Plugin Settings',
            'Settings',
            'manage_options',
            'book-settings',
            [$this, 'settingsPage'],
            position: 99

        );

    }

    /**
     * sdds settings fields to the settings page
     *
     * @return void
     */
    public function settingsPage(){
        ?>
        <h1><?= __('Book Settings', 'kn-books') ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields( self::SETTINGS_GROUP ); ?>
            <?php do_settings_sections( self::SETTINGS_GROUP ); ?>
            <?php submit_button('Save Changes'); ?>

        </form>
        <?php
    }

    public function addFields(){
        add_settings_section(
                'book-general',
            'General Book Settings',
            function() {},
            self::SETTINGS_GROUP // or 'general', 'writing', etc
        );

	    add_settings_field(
		    self::SHOW_BOOK_REVIEWS,
		    'Show Reviews',
		    function() {
			    $checked = get_option( self::SHOW_BOOK_REVIEWS) ? 'checked' : '';
			    ?>
                <input type="checkbox" id="<?= self::SHOW_BOOK_REVIEWS ?>"
                       name="<?= self::SHOW_BOOK_REVIEWS ?>"
				    <?= $checked ?> value="1">
			    <?php
		    },
		    self::SETTINGS_GROUP,
		    'book-general'
	    );
	    add_settings_field(
		    self::SHOW_PURCHASE_LINK,
		    'Show Purchase Link',
		    function() {
			    $checked = get_option( self::SHOW_PURCHASE_LINK) ? 'checked' : '';
			    ?>
                <input type="checkbox" id="<?= self::SHOW_PURCHASE_LINK ?>"
                       name="<?= self::SHOW_PURCHASE_LINK ?>"
				    <?= $checked ?> value="1">
			    <?php
		    },
		    self::SETTINGS_GROUP,
		    'book-general'
	    );
	    add_settings_field(
		    self::BOOK_TERM_SINGULAR,
		    'Change Book Term Singular',
		    function() {
			    $bookTermSingular = esc_attr( get_option( self::BOOK_TERM_SINGULAR) ); // Get the saved value from the database
			    ?>
                <input type="text" id="<?= self::BOOK_TERM_SINGULAR ?>"
                       name="<?= self::BOOK_TERM_SINGULAR ?>" value="<?= $bookTermSingular ?>">
			    <?php
		    },
		    self::SETTINGS_GROUP,
		    'book-general'
	    );
	    add_settings_field(
		    self::BOOK_TERM_PLURAL,
		    'Change Book Term Plural',
		    function() {
			    $bookTermPlural = esc_attr( get_option( self::BOOK_TERM_PLURAL ) ); // Get the saved value from the database
			    ?>
                <input type="text" id="<?= self::BOOK_TERM_PLURAL ?>"
                       name="<?= self::BOOK_TERM_PLURAL ?>" value="<?= $bookTermPlural ?>">
			    <?php
		    },
		    self::SETTINGS_GROUP,
		    'book-general'
	    );
    }

	/**
	 * retrieves the value setting
	 *
	 * @return bool|null
	 */
	public function showPurchaseLink(){
		return get_option( self::SHOW_PURCHASE_LINK);
	}

	/**
	 * retrieves the value setting
	 *
	 * @return bool|null
	 */
	public function showBookReivews(){
		return get_option( self::SHOW_BOOK_REVIEWS);
	}
	/**
	 * retrieves the value setting
	 *
	 * @return string
	 */
	public function bookTermSingular(){
		return get_option( self::BOOK_TERM_SINGULAR);
	}
	/**
	 * retrieves the value setting
	 *
	 * @return string
	 */
	public function bookTermPlural(){
		return get_option( self::BOOK_TERM_PLURAL);
	}
}