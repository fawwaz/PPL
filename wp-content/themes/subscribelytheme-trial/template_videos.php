<?php
/**
 * Template Name: All Videos
 *
 */
get_header();
sc_bred_crumbs();
sc_before_content();
?>
<!--Start Content Wrapper-->
<div id="content_wrapper">
    <div class="fullwidth videos">
        <?php
        $cat = SC_CAT;
        $terms = get_terms($cat);
        $count = count($terms);
        if ($count > 0) {
            $i = 0;
            foreach ($terms as $term) {
                $cat_id = $term->term_id;
                ?>
                <?php echo '<h1 class="cat_title"><a href="' . get_term_link($term->slug, $cat) . '">' . $term->name . '</a></h1>'; ?>
                <ul id="thumbnails">        
                    <?php
                    $post_type = POST_TYPE;
                    query_posts(array('posts_per_page' => 4, 'post_type' => $post_type, $cat => "$term->name"));
                    if (have_posts()) : while (have_posts()) : the_post();
                            ?>               
                            <li>
                                <div class="thumbnail">
                                    <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                                        <?php inkthemes_get_thumbnail(206, 147); ?>
                                    <?php } else { ?>
                                        <?php inkthemes_get_image(206, 147); ?> 
                                        <?php
                                    }
                                    ?>
                                    <a href="<?php the_permalink() ?>"><span></span></a>
                                </div>
                                <h6><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo get_the_title(); ?></a></h6>
                                <p>Posted on <?php the_time('F j, Y ') ?></p>

                            </li>                   
                            <?php
                        endwhile;
                    endif;
                    wp_reset_postdata();
                    wp_reset_query();
                    ?>        
                </ul> 
                <div class="clear"></div>
                <?php
            }
        }
        ?>
    </div>    
</div>
<!--End Content Wrapper-->
<?php
sc_after_content();
get_footer();
?>