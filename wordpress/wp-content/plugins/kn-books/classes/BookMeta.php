<?php

namespace KN\BookPlugin;

class BookMeta extends Singleton
{
    /**
     * keys stored in database
     * use constants to avoid type and debugging issues
     */
    const BOOK_ISBN = 'bookBookISBN';
    const PUBLISHER_NAME = 'bookPublisherName';
    const PUBLISHER_IMPRINT = 'bookPublisherImprint';

    const PUBLISH_DATE = 'bookPublishDate';
    const PAGE_COUNT = 'bookPageCount';
    const BOOK_PRICE = 'bookBookCost';
	const PURCHASE_LINK = 'bookPurchaseLink';



	/**
     * static property to hold the singleton instance
     *
     * @var BookMeta
     */
    protected static $instance;

    /**
     *  constructor for BookMeta
     * hooks the registration of meta boxes and the saving of book information through actions
     */
    public function __construct()
    {
        add_action( 'admin_init', [$this, 'registerMetaBox'], 0 );
        add_action('save_post_' . BookPostType::POST_TYPE, [$this, 'saveInformation'], 0);
	    add_action('init', [$this, 'setDefaults'], 0);

    }

    /**
     * registers the meta box for book details
     * @return void
     */
    function registerMetaBox() {
        add_meta_box('book_information',
            __('Book Details', TEXT_DOMAIN),
            [$this, 'detailsForm'],
            BookPostType::POST_TYPE,
            'normal', 'core');
    }

    /**
     * displays the form fields in the custom meta box
     * @return void
     */
    function detailsForm() {
        $bookISBN = $this->getBookISBN();
        $publisherName = $this->getPublisherName();
        $publisherImprint = $this->getPublisherImprint();
        $publishDate = $this->getPublishDate();

        $pageCount = $this->getPageCount();
        $bookPrice = $this->getBookPrice();
        $purchaseLink = $this->getPurchaseLink();
        ?>
        <style>
            :is(#book_publishing_information, #book_information) .inside {
                padding: 0;
                margin: 0;
            }
            .book-meta-box h4 {
                margin: 0;
                padding: 20px;
                border-bottom: 1px solid #dfdfdf;
            }
            .book-meta-box label {
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #dfdfdf;
            }
            .book-meta-box:last-of-type label:last-of-type {
                border-bottom: 0;
            }
            .book-meta-box .book-meta-box-label {
                background: #F9F9F9;
                padding: 20px 10px 20px 20px;
                flex: .35;
                font-weight: 700;
                border-right: 1px solid #dfdfdf;
            }
            .book-meta-box span {
                flex: 1;
                padding: 15px;
            }
            .book-meta-box label input {
                flex: 2;
                padding: 5px;
                border: 1px solid #ccc;
                border-radius: 4px;
                width: 100%;
            }
        </style>
        <div class="book-meta-box">
            <h4><?= __('Publishing Information', TEXT_DOMAIN) ?></h4>
            <label><span class="book-meta-box-label"><?= __('ISBN', TEXT_DOMAIN) ?></span> <span><input type="number" name="<?= self::BOOK_ISBN ?>" value="<?= $bookISBN ?>"></span></label>
            <label><span class="book-meta-box-label"><?= __('Publisher', TEXT_DOMAIN) ?></span> <span><input type="text" name="<?= self::PUBLISHER_NAME ?>" value="<?= $publisherName ?>"></span></label>
            <label><span class="book-meta-box-label"><?= __('Imprint', TEXT_DOMAIN) ?></span> <span><input type="text" name="<?= self::PUBLISHER_IMPRINT ?>" value="<?= $publisherImprint ?>"></span></label>
            <label><span class="book-meta-box-label"><?= __('Publish Date', TEXT_DOMAIN) ?></span> <span><input type="date" name="<?= self::PUBLISH_DATE ?>" value="<?= $publishDate ?>"></span></label>

        </div>
        <div class="book-meta-box">
            <h4><?= __('Book Details', TEXT_DOMAIN) ?></h4>
            <label><span class="book-meta-box-label"><?= __('Page Count', TEXT_DOMAIN) ?></span> <span><input type="number" name="<?= self::PAGE_COUNT ?>" value="<?= $pageCount ?>"></span></label>
            <label><span class="book-meta-box-label"><?= __('Price', TEXT_DOMAIN) ?></span> <span><input type="number" step="0.01" name="<?= self::BOOK_PRICE ?>" value="<?= $bookPrice ?>"></span></label>
            <label><span class="book-meta-box-label"><?= __('Purchase Link', TEXT_DOMAIN) ?></span> <span><input type="text" name="<?= self::PURCHASE_LINK ?>" value="<?= $purchaseLink ?>"></span></label>
        </div>
        <?php
    }

    /**
     * @return void
     */
    function saveInformation() {

        //get values
        $bookISBN = isset($_POST[self::BOOK_ISBN]) ? sanitize_text_field($_POST[self::BOOK_ISBN]) : '';
        $publisherName = isset($_POST[self::PUBLISHER_NAME]) ? sanitize_text_field($_POST[self::PUBLISHER_NAME]) : '';
        $publisherImprint = isset($_POST[self::PUBLISHER_IMPRINT]) ? sanitize_text_field($_POST[self::PUBLISHER_IMPRINT]) : '';
        $publishDate = isset($_POST[self::PUBLISH_DATE]) ? sanitize_text_field($_POST[self::PUBLISH_DATE]) : '';
        $pageCount = isset($_POST[self::PAGE_COUNT]) ? (int) sanitize_text_field($_POST[self::PAGE_COUNT]) : 0;
        $bookPrice = isset($_POST[self::BOOK_PRICE]) ? (float) sanitize_text_field($_POST[self::BOOK_PRICE]) : 0.0;
	    $purchaseLink = isset($_POST[self::PURCHASE_LINK]) ? sanitize_text_field($_POST[self::PURCHASE_LINK]) : '';


        //sanitize
        $bookISBN = sanitize_text_field($bookISBN);
        $publisherName = sanitize_text_field($publisherName);
        $publisherImprint = sanitize_text_field($publisherImprint);
        $publishDate = sanitize_text_field($publishDate);
        $pageCount = sanitize_text_field($pageCount);
        $bookPrice = sanitize_text_field($bookPrice);
	    $purchaseLink = sanitize_text_field($purchaseLink);

	    // Convert page count to an integer and sanitize
        $pageCount = (int) sanitize_text_field($pageCount);

        // Convert book price to a float and sanitize
        $bookPrice = (float) sanitize_text_field($bookPrice);

	    $post = get_post();

	    update_post_meta($post->ID, self::BOOK_ISBN, $bookISBN);
        update_post_meta($post->ID, self::PUBLISHER_NAME, $publisherName);
        update_post_meta($post->ID, self::PUBLISHER_IMPRINT, $publisherImprint);
        update_post_meta($post->ID, self::PUBLISH_DATE, $publishDate);
        update_post_meta($post->ID, self::PAGE_COUNT, $pageCount);
        update_post_meta($post->ID, self::BOOK_PRICE, $bookPrice);
	    update_post_meta($post->ID, self::PURCHASE_LINK, $purchaseLink);
    }

    /**
     * gets ISBN
     * @return string ISBN value
     */
    public function getBookISBN() {
        $post = get_post();
        return get_post_meta($post->ID, self::BOOK_ISBN, true);
    }

    /**
     * gets publisher name
     * @return string publisher name.
     */
    public function getPublisherName() {
        $post = get_post();
        return get_post_meta($post->ID, self::PUBLISHER_NAME, true);
    }

    /**
     * gets imprint
     * @return string imprint
     */
    public function getPublisherImprint() {
        $post = get_post();
        return get_post_meta($post->ID, self::PUBLISHER_IMPRINT, true);
    }

    /**
     * gets publish date
     * @return string publish date
     */
    public function getPublishDate() {
        $post = get_post();
        return get_post_meta($post->ID, self::PUBLISH_DATE, true);
    }

    /**
     * gets page count
     * @return int page count
     */
    public function getPageCount() {
        $post = get_post();
        return get_post_meta($post->ID, self::PAGE_COUNT, true);
    }

    /**
     * gets book price
     * @return float book price
     */
    public function getBookPrice() {
        $post = get_post();
        return get_post_meta($post->ID, self::BOOK_PRICE, true);
    }

	/**
	 * gets purchase link
	 * @return string book price
	 */
	public function getPurchaseLink() {
		$post = get_post();
		return get_post_meta($post->ID, self::PURCHASE_LINK, true);
	}

    /**
     * set default settings
     * @return void
     */
    public function setDefaults() {
	    add_option( BookSettings::SHOW_PURCHASE_LINK, 1);
	    add_option( BookSettings::SHOW_BOOK_REVIEWS, 1);
	    add_option( BookSettings::BOOK_TERM_SINGULAR, 'Book');
	    add_option( BookSettings::BOOK_TERM_PLURAL, 'Books');
    }

}