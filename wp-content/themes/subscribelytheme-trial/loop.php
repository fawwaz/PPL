<!-- Start the Loop. -->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <!--Start Post-->
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                <?php inkthemes_get_thumbnail(219, 171, 'postimg'); ?>
            <?php } else { ?>
                <?php inkthemes_get_image(219, 171); ?> 
                <?php
            }
            ?>	
            <!--Start Post content-->
            <div class="post_content">
                <h1 class="post_title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
                <div class="border"></div>
                <p class="video_meta">By <?php the_author_posts_link(); ?> | <?php the_time('j F, Y') ?> | <?php echo $categories; ?></p>
                <div class="border post_border"></div>
                <?php the_excerpt(); ?>
                <a href="<?php the_permalink(); ?>" class="continue"><?php _e('Read More', THEME_SLUG); ?></a>
            </div>
            <!--End Post content-->
            <div class="border_strip"></div>
        </div>
        <!--End Post-->
        <?php
    endwhile;
    sc_nav();
else:
    ?>
    <!--End Loop-->
    <div class="post">
        <p>
    <?php _e('Sorry, no posts matched your criteria.', THEME_SLUG); ?>
        </p>
    </div>
<?php endif; ?>