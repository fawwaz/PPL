<?php
/**
 * The template for displaying Author Archive pages.
 * 
 */
get_header();
sc_bred_crumbs();
sc_before_content();
?>
<!--Start Content Wrapper-->
<div id="content_wrapper">
    <div class="grid_17 alpha">
        <div class="content">
            <h2><?php printf(__('Author Archives: %s', THEME_SLUG), "<a class='url fn n' href='" . get_author_posts_url(get_the_author_meta('ID')) . "' title='" . esc_attr(get_the_author()) . "' rel='me'>" . get_the_author() . "</a>"); ?></h2>
            <?php if (have_posts()) : the_post(); ?>
                <?php
                // If a user has filled out their description, show a bio on their entries.
                if (get_the_author_meta('description')) :
                    ?>
                    <div id="author-info">
                        <div id="author-avatar"> <?php echo get_avatar(get_the_author_meta('user_email'), apply_filters('sc_author_bio_avatar_size', 60)); ?> </div>
                        <!-- #author-avatar -->
                        <div id="author-description">
                            <h2><?php printf(__('About %s', THEME_SLUG), get_the_author()); ?></h2>
                            <?php the_author_meta('description'); ?>
                        </div>
                        <!-- #author-description	-->
                    </div>
                    <!-- #entry-author-info -->
                <?php endif; ?>
            <?php endif; ?>
            <?php
            /* Since we called the_post() above, we need to
             * rewind the loop back to the beginning that way
             * we can run the loop properly, in full.
             */
            rewind_posts();
            /* Run the loop for the author archive page to output the authors posts
             * If you want to overload this in a child theme then include a file
             * called loop-author.php and that will be used instead.
             */
            get_template_part('loop', 'author');
            ?>
            <?php
            sc_nav();
            ?>            
        </div>
        <div class="clear"></div>
    </div>
    <div class="grid_7 omega">
    <?php get_sidebar(); ?>
    </div>
    <div class="clear"></div>
</div>
<!--End Content Wrapper-->
<?php
sc_after_content();
get_footer();
?>