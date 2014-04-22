<?php

function sc_nav() {
    ?>
    <nav id="nav-single"> <span class="nav-previous">
            <?php next_posts_link(__('&larr; Older posts', THEME_SLUG)); ?>
        </span> <span class="nav-next">
            <?php previous_posts_link(__('Newer posts &rarr;', THEME_SLUG)); ?>
        </span> </nav>
    <?php
}

function sc_single_nav() {
    ?>
    <nav id="nav-single"> <span class="nav-previous">
           <?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', THEME_SLUG ) . '</span> %title' ); ?>
        </span> <span class="nav-next">
            <?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', THEME_SLUG ) . '</span>' ); ?>
        </span> </nav>
    <?php
}

/**
 * Function: sc_get_terms_dropdown()
 * Description : returns costom taxonomy dropdown list
 * @param type $taxonomies
 * @param type $args
 * @return string 
 */
function sc_get_terms_dropdown($taxonomies, $args) {
    $myterms = get_terms($taxonomies, $args);
    $output = "<select id='cat'>";
    foreach ($myterms as $term) {
        $root_url = get_bloginfo('url');
        $term_taxonomy = $term->taxonomy;
        $term_slug = $term->slug;
        $term_name = $term->name;
        $link = $root_url . '/' . $term_taxonomy . '/' . $term_slug;
        $output .="<option value='" . $term_slug . "'>" . $term_name . "</option>";
    }
    $output .="</select>";
    return $output;
}

function sc_thumbnails() {
    ?>
    <ul id="thumbnails">        
        <?php
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
        ?>        
    </ul> 
    <?php
}
?>
