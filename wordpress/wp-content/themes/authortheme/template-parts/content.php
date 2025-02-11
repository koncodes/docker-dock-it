<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WPD_Homework
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="image-header"><?php kn_homework_post_thumbnail(); ?></div>
    <div class="entry-con">
        <header class="entry-header">
            <a class="entry-title-link" href="<?php the_permalink(); ?>">

            <?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
            </a>

            <?php

            if ( 'post' === get_post_type() ) :
                ?>
                <div class="entry-meta">
                    <?php
                    kn_homework_posted_by();

                    $categories_list = get_the_category_list( esc_html__( ', ', 'kn_homework' ) );
                    if ( $categories_list ) {
                        /* translators: 1: list of categories. */
                        printf( '<span class="cat-links">' . esc_html__( ' in %1$s', 'kn_homework' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                    ?>
                </div><!-- .entry-meta -->
            <?php endif; ?>
        </header><!-- .entry-header -->


        <div class="entry-content">
            <?php
            if(is_front_page()){
                the_content(
                    sprintf(
                        wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'kn_homework' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post( get_the_title() )
                    )
                );
            } elseif (is_archive()) {
                if (has_excerpt()) {
                    the_excerpt(); // Display the post excerpt if available
                } else {
                    // Display shortened content if no excerpt is available
                    echo '<p>' . wp_trim_words(get_the_content(), 30, '...') . '</p>'; // Shorten content to 30 words with '...' at the end
                }
            } else{
                the_content(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'kn_homework' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post( get_the_title() )
                    )
                );
            };

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'kn_homework' ),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->
        <div class="entry-footer">
            <?php kn_homework_entry_footer(); ?>
	        <?php if ( is_home() || is_archive() || is_search() || is_category() ) : ?>
                <span class="read-more button">
                    <a href="<?php the_permalink(); ?>">
                        <?php esc_html_e( 'Read More', 'kn_homework' ); ?>
                    </a>
                </span>
	        <?php endif; ?>
            <?php if ( get_edit_post_link() ) : ?>
                <?php
                edit_post_link(
                    sprintf(
                        wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Edit <span class="screen-reader-text">%s</span>', 'kn_homework' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post( get_the_title() )
                    ),
                    '<span class="edit-link button">',
                    '</span>'
                );
                ?>

            <?php endif; ?>
        </div><!-- .entry-footer -->
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
