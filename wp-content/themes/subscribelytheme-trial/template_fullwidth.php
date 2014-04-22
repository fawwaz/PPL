<?php
/**
 * Template Name: Fullwidth
 *
 */
get_header();
sc_bred_crumbs();
sc_before_content();
?>
<!--Start Content Wrapper-->
<div id="content_wrapper">
    <div class="fullwidth">
         <?php if (have_posts()) : the_post(); ?>
            <h1 class="title"><?php the_title(); ?></h1>
                <?php the_content(); ?>	
            <?php endif; ?>           
    </div>    
</div>
<!--End Content Wrapper-->
<?php
sc_after_content();
get_footer();
?>