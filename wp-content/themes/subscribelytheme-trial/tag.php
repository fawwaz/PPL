<?php
/**
 * The template used to display Tag Archive pages
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
            <?php if (have_posts()) : ?>
                <h1><?php printf(__('Tag Archives: %s', THEME_SLUG), '' . single_cat_title('', false) . ''); ?></h1>
                <?php get_template_part('loop'); ?>
            <?php endif; ?>
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