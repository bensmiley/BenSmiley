<?php
/**
 * binpress template for displaying the standard Loop
 *
 * @package WordPress
 * @subpackage binpress
 * @since binpress 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <h1 class="post-title"><?php

        if ( is_singular() ) :
            the_title();
        else : ?>

            <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><?php
            the_title(); ?>
            </a><?php

        endif; ?>

    </h1>

    <div class="post-content"><?php

        if ( '' != get_the_post_thumbnail() ) : ?>
            <?php the_post_thumbnail(); ?><?php
        endif; ?>

        <?php if ( is_front_page() || is_category() || is_archive() || is_search() ) : ?>

            <?php the_excerpt(); ?>
            <a href="<?php the_permalink(); ?>"><?php _e( 'Read more &raquo;', 'binpress' ); ?></a>

        <?php else : ?>

            <?php the_content( __( 'Continue reading &raquo', 'binpress' ) ); ?>

        <?php endif; ?>

        <?php
        wp_link_pages(
            array(
                'before' => '<div class="linked-page-nav"><p>' . __( 'This article has more parts: ', 'binpress' ),
                'after' => '</p></div>',
                'next_or_number' => 'number',
                'separator' => ' ',
                'pagelink' => __( '&lt;%&gt;', 'binpress' ),
            )
        );
        ?>

    </div>

</article>