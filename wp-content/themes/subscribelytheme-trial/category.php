<?php
/**
 * The template for displaying Category Archive pages.
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
                <h1><?php printf(__('Category Archives: %s', 'cloriato'), '' . single_cat_title('', false) . ''); ?></h1>
                <?php
                $category_description = category_description();
                if (!empty($category_description))
                    echo '' . $category_description . '';
                /* Run the loop for the category page to output the posts.
                 * If you want to overload this in a child theme then include a file
                 * called loop-category.php and that will be used instead.
                 */
                ?>
                <?php get_template_part('loop'); ?>
            <?php endif; ?>    
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