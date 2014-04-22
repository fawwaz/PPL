<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
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
            <?php
            /* Queue the first post, that way we know
             * what date we're dealing with (if that is the case).
             *
             * We reset this later so we can run the loop
             * properly with a call to rewind_posts().
             */
            if (have_posts())
                the_post();
            ?>
            <h1>
                <?php if (is_day()) : ?>
                    <?php printf(__('Daily Archives: %s',THEME_SLUG), get_the_date()); ?>
                <?php elseif (is_month()) : ?>
                    <?php printf(__('Monthly Archives: %s',THEME_SLUG), get_the_date('F Y')); ?>
                <?php elseif (is_year()) : ?>
                    <?php printf(__('Yearly Archives: %s',THEME_SLUG), get_the_date('Y')); ?>
                <?php else : ?>
                    <?php _e('Blog Archives',THEME_SLUG); ?>
                <?php endif; ?>
            </h1>
            <?php
            /* Since we called the_post() above, we need to
             * rewind the loop back to the beginning that way
             * we can run the loop properly, in full.
             */
            rewind_posts();
            /* Run the loop for the archives page to output the posts.
             * If you want to overload this in a child theme then include a file
             * called loop-archives.php and that will be used instead.
             */
            get_template_part('loop', 'archive');
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