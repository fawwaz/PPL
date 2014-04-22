<?php
/**
 * The single template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
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
            <?php if (have_posts()) while (have_posts()) : the_post(); ?>
                    <!--Start Post-->
                    <div class="post single video">
                        <!--Start Post content-->
                        <div class="post_content">
                            <h1 class="post_title"><?php the_title(); ?></h1>                   
                            <div class="border_strip"></div>
                            <?php the_content(); ?>
                        </div>
                        <!--End Post content-->
                        <?php wp_link_pages(array('before' => '<div class="page-link"><span>' . 'Pages:' . '</span>', 'after' => '</div>')); ?>
                        <div class="border"></div>
                        <p class="video_meta">By <?php the_author_posts_link(); ?> | <?php the_time('j F, Y') ?> | <?php echo $categories; ?></p>
                        <div class="border"></div>
                    </div>
                    <!--End Post-->  
                <?php endwhile; ?>
            <?php sc_single_nav(); ?>
            <div class="clear"></div>	
            <?php comments_template(); ?>
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