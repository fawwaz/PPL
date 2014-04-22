<?php
/**
 * Template Name: Blog
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
            $limit = get_option('posts_per_page');
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            query_posts('showposts=' . $limit . '&paged=' . $paged);
            $wp_query->is_archive = true;
            $wp_query->is_home = false;
            get_template_part('loop');
            wp_reset_query();            
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