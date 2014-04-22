<?php

function inkthemes_setup() {
    add_theme_support('post-thumbnails');
    add_image_size('post_thumbnail', 595, 224, true);
    add_theme_support('automatic-feed-links');
    add_editor_style();
    //Load languages file
    load_theme_textdomain('subscribely', get_template_directory() . '/lang');
    $locale = get_locale();
    $locale_file = get_template_directory() . "/lang/$locale.php";
    if (is_readable($locale_file))
        require_once($locale_file);
}

add_action('after_setup_theme', 'inkthemes_setup');
/* ----------------------------------------------------------------------------------- */
/* Custom Menus Function
  /*----------------------------------------------------------------------------------- */

// Add CLASS attributes to the first <ul> occurence in wp_page_menu
function inkthemes_add_menuclass($ulclass) {
    return preg_replace('/<ul>/', '<ul class="ddsmoothmenu">', $ulclass, 1);
}

add_filter('wp_page_menu', 'inkthemes_add_menuclass');
add_action('after_setup_theme', 'inkthemes_register_custom_menu');

function inkthemes_register_custom_menu() {
    register_nav_menu('custom_menu', __('Main Menu', THEME_SLUG));
}

function inkthemes_nav() {
    if (function_exists('wp_nav_menu'))
        wp_nav_menu(array('theme_location' => 'custom_menu', 'container_id' => 'menu', 'menu_class' => 'ddsmoothmenu', 'fallback_cb' => 'inkthemes_nav_fallback'));
    else
        inkthemes_nav_fallback();
}

function inkthemes_nav_fallback() {
    ?>
    <div id="menu">
        <ul class="ddsmoothmenu">
            <?php
            wp_list_pages('title_li=&show_home=1&sort_column=menu_order');
            ?>
        </ul>
    </div>
    <?php
}

function inkthemes_nav_menu_items($items) {
    if (is_home()) {
        $homelink = '<li class="current_page_item">' . '<a href="' . home_url('/') . '">' . __('Home', THEME_SLUG) . '</a></li>';
    } else {
        $homelink = '<li>' . '<a href="' . home_url('/') . '">' . __('Home', THEME_SLUG) . '</a></li>';
    }
    $items = $homelink . $items;
    return $items;
}

add_filter('wp_list_pages', 'inkthemes_nav_menu_items');
/* ----------------------------------------------------------------------------------- */
/* Breadcrumbs Plugin
  /*----------------------------------------------------------------------------------- */

function inkthemes_breadcrumbs() {
    $delimiter = '&raquo;';
    $home = 'Home'; // text for the 'Home' link
    $before = '<span class="current">'; // tag before the current crumb
    $after = '</span>'; // tag after the current crumb
    echo '<div id="crumbs">';
    global $post;
    $homeLink = home_url();
    echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
    if (is_category()) {
        global $wp_query;
        $cat_obj = $wp_query->get_queried_object();
        $thisCat = $cat_obj->term_id;
        $thisCat = get_category($thisCat);
        $parentCat = get_category($thisCat->parent);
        if ($thisCat->parent != 0)
            echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
        echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
    }
    elseif (is_day()) {
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
        echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
        echo $before . get_the_time('d') . $after;
    } elseif (is_month()) {
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
        echo $before . get_the_time('F') . $after;
    } elseif (is_year()) {
        echo $before . get_the_time('Y') . $after;
    } elseif (is_single() && !is_attachment()) {
        if (get_post_type() != 'post') {
            $post_type = get_post_type_object(get_post_type());
            $slug = $post_type->rewrite;
            echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
            echo $before . get_the_title() . $after;
        } else {
            $cat = get_the_category();
            $cat = $cat[0];
            echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
            echo $before . get_the_title() . $after;
        }
    } elseif (!is_single() && !is_page() && get_post_type() != 'post') {
        $post_type = get_post_type_object(get_post_type());
        echo $before . $post_type->labels->singular_name . $after;
    } elseif (is_attachment()) {
        $parent = get_post($post->post_parent);
        $cat = get_the_category($parent->ID);
        $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;
    } elseif (is_page() && !$post->post_parent) {
        echo $before . get_the_title() . $after;
    } elseif (is_page() && $post->post_parent) {
        $parent_id = $post->post_parent;
        $breadcrumbs = array();
        while ($parent_id) {
            $page = get_page($parent_id);
            $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
            $parent_id = $page->post_parent;
        }
        $breadcrumbs = array_reverse($breadcrumbs);
        foreach ($breadcrumbs as $crumb)
            echo $crumb . ' ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;
    } elseif (is_search()) {
        echo $before . 'Search results for "' . get_search_query() . '"' . $after;
    } elseif (is_tag()) {
        echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
    } elseif (is_author()) {
        global $author;
        $userdata = get_userdata($author);
        echo $before . 'Articles posted by ' . $userdata->display_name . $after;
    } elseif (is_404()) {
        echo $before . 'Error 404' . $after;
    }
    if (get_query_var('paged')) {
        if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
            echo ' (';
        echo __('Page', 'infoway') . ' ' . get_query_var('paged');
        if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
            echo ')';
    }
    echo '</div>';
}

//* ----------------------------------------------------------------------------------- */
/* Function to call first uploaded image in functions file
  /*----------------------------------------------------------------------------------- */

/**
 * This function thumbnail id and
 * returns thumbnail image
 * @param type $iw
 * @param type $ih 
 */
function inkthemes_get_thumbnail($iw, $ih, $class = '') {
    $permalink = get_permalink($id);
    $thumb = get_post_thumbnail_id();
    $image = inkthemes_thumbnail_resize($thumb, '', $iw, $ih, true, 90);
    if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) {
        print "<a href='$permalink'><img src='$image[url]' width='$image[width]' height='$image[height]' class='galleryimg $class' /></a>";
    }
}

/**
 * This function gets image width and height and
 * Prints attached images from the post        
 */
function inkthemes_get_image($width, $height) {
    $w = $width;
    $h = $height;
    global $post, $posts;
    //This is required to set to Null
    $img_source = '';
    $permalink = get_permalink($id);
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    if (isset($matches [1] [0])) {
        $img_source = $matches [1] [0];
    }
    $img_path = inkthemes_image_resize($img_source, $w, $h);
    if (!empty($img_path[url])) {
        print "<a href='$permalink'><img src='$img_path[url]' class='postimg' alt='Post Image'/></a>";
    }
}

//For Attachment Page
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 */
function inkthemes_posted_in() {
// Retrieves tag list of current post, separated by commas.
    $tag_list = get_the_tag_list('', ', ');
    if ($tag_list) {
        $posted_in = __('This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', THEME_SLUG);
    } elseif (is_object_in_taxonomy(get_post_type(), 'category')) {
        $posted_in = __('This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', THEME_SLUG);
    } else {
        $posted_in = __('Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', THEME_SLUG);
    }
// Prints the string, replacing the placeholders.
    printf(
            $posted_in, get_the_category_list(', '), $tag_list, get_permalink(), the_title_attribute('echo=0')
    );
}

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if (!isset($content_width))
    $content_width = 590;

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override twentyten_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @uses register_sidebar
 */
function inkthemes_widgets_init() {
// Area 1, located at the top of the sidebar.
    register_sidebar(array(
        'name' => __('Primary Widget Area', THEME_SLUG),
        'id' => 'primary-widget-area',
        'description' => __('The primary widget area', THEME_SLUG),
        'before_widget' => '<div class="sidebar_widget">',
        'after_widget' => ' </div>',
        'before_title' => '<h3 class="sidebar_title">',
        'after_title' => '</h3>',
    ));
// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
    register_sidebar(array(
        'name' => __('Secondary Widget Area', THEME_SLUG),
        'id' => 'secondary-widget-area',
        'description' => __('The secondary widget area', THEME_SLUG),
        'before_widget' => '<div class="sidebar_widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="sidebar_title">',
        'after_title' => '</h3>',
    ));
    // Area 3, located in the footer. Empty by default.
    register_sidebar(array(
        'name' => __('First Footer Widget Area', THEME_SLUG),
        'id' => 'first-footer-widget-area',
        'description' => __('The first footer widget area', THEME_SLUG),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h5 class="footer_title">',
        'after_title' => '</h5>',
    ));
    // Area 4, located in the footer. Empty by default.
    register_sidebar(array(
        'name' => __('Second Footer Widget Area', THEME_SLUG),
        'id' => 'second-footer-widget-area',
        'description' => __('The second footer widget area', THEME_SLUG),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h5 class="footer_title">',
        'after_title' => '</h5>',
    ));
    // Area 5, located in the footer. Empty by default.
    register_sidebar(array(
        'name' => __('Third Footer Widget Area', THEME_SLUG),
        'id' => 'third-footer-widget-area',
        'description' => __('The third footer widget area', THEME_SLUG),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h5 class="footer_title">',
        'after_title' => '</h5>',
    ));
    // Area 6, located in the footer. Empty by default.
    register_sidebar(array(
        'name' => __('Fourth Footer Widget Area', THEME_SLUG),
        'id' => 'fourth-footer-widget-area',
        'description' => __('The fourth footer widget area', THEME_SLUG),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h5 class="footer_title">',
        'after_title' => '</h5>',
    ));
}

/** Register sidebars by running inkthemes_widgets_init() on the widgets_init hook. */
add_action('widgets_init', 'inkthemes_widgets_init');

/**
 * Pagination
 *
 */
function inkthemes_pagination($pages = '', $range = 2) {
    $showitems = ($range * 2) + 1;
    global $paged;
    if (empty($paged))
        $paged = 1;
    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if (!$pages) {
            $pages = 1;
        }
    }
    if (1 != $pages) {
        echo "<ul class='paging'>";
        if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
            echo "<li><a href='" . get_pagenum_link(1) . "'>&laquo;</a></li>";
        if ($paged > 1 && $showitems < $pages)
            echo "<li><a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo;</a></li>";
        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
                echo ($paged == $i) ? "<li><a href='" . get_pagenum_link($i) . "' class='current' >" . $i . "</a></li>" : "<li><a href='" . get_pagenum_link($i) . "' class='inactive' >" . $i . "</a></li>";
            }
        }
        if ($paged < $pages && $showitems < $pages)
            echo "<li><a href='" . get_pagenum_link($paged + 1) . "'>&rsaquo;</a></li>";
        if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
            echo "<li><a href='" . get_pagenum_link($pages) . "'>&raquo;</a></li>";
        echo "</ul>\n";
    }
}

/////////Theme Options
/* ----------------------------------------------------------------------------------- */
/* Add Favicon
  /*----------------------------------------------------------------------------------- */
function inkthemes_childtheme_favicon() {
    if (inkthemes_get_option('inkthemes_favicon') != '') {
        echo '<link rel="shortcut icon" href="' . inkthemes_get_option('inkthemes_favicon') . '"/>' . "\n";
    } else {
        ?>
        <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicon.ico" />
        <?php
    }
}

add_action('wp_head', 'inkthemes_childtheme_favicon');
/* ----------------------------------------------------------------------------------- */
/* Show analytics code in footer */
/* ---------------------------------------------------------------------------------- */

function inkthemes_childtheme_analytics() {
    $output = get_option('inkthemes_analytics');
    if ($output <> "")
        echo stripslashes($output);
}

add_action('wp_head', 'inkthemes_childtheme_analytics');
/* ----------------------------------------------------------------------------------- */
/* Custom CSS Styles */
/* ----------------------------------------------------------------------------------- */

function inkthemes_of_head_css() {
    $output = '';
    $custom_css = inkthemes_get_option('inkthemes_customcss');
    if ($custom_css <> '') {
        $output .= $custom_css . "\n";
    }
// Output styles
    if ($output <> '') {
        $output = "<!-- Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
        echo $output;
    }
}

add_action('wp_head', 'inkthemes_of_head_css');

function inkthemes_get_category_id($cat_name) {
    $term = get_term_by('name', $cat_name, 'category');
    return $term->term_id;
}

//Trm excerpt
function inkthemes_trim_excerpt($length) {
    global $post;
    $explicit_excerpt = $post->post_excerpt;
    if ('' == $explicit_excerpt) {
        $text = get_the_content('');
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]>', $text);
    } else {
        $text = apply_filters('the_content', $explicit_excerpt);
    }
    $text = strip_shortcodes($text); // optional
    $text = strip_tags($text);
    $excerpt_length = $length;
    $words = explode(' ', $text, $excerpt_length + 1);
    if (count($words) > $excerpt_length) {
        array_pop($words);
        array_push($words, '[&hellip;]');
        $text = implode(' ', $words);
        $text = apply_filters('the_excerpt', $text);
    }
    return $text;
}

//Trim excerpt
function inkthemes_custom_trim_excerpt($length) {
    global $post;
    $explicit_excerpt = $post->post_excerpt;
    if ('' == $explicit_excerpt) {
        $text = get_the_content('');
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]>', $text);
    } else {
        $text = apply_filters('the_content', $explicit_excerpt);
    }
    $text = strip_shortcodes($text); // optional
    $text = strip_tags($text);
    $excerpt_length = $length;
    $words = explode(' ', $text, $excerpt_length + 1);
    if (count($words) > $excerpt_length) {
        array_pop($words);
        array_push($words, '[&hellip;]');
        $text = implode(' ', $words);
        $text = apply_filters('the_excerpt', $text);
    }
    return $text;
}

//changing excerpt length
function new_excerpt_length($length) {
    return 30;
}

add_filter('excerpt_length', 'new_excerpt_length');
/* ----------------------------------------------------------------------------------- */
/* Styles Enqueue */
/* ----------------------------------------------------------------------------------- */

function inkthemes_add_stylesheet() {
    if (inkthemes_get_option('inkthemes_altstylesheet') != 'default') {
        wp_enqueue_style('coloroptions', get_template_directory_uri() . "/css/colors/" . inkthemes_get_option('inkthemes_altstylesheet') . ".css", '', '', 'all');
    }
    wp_enqueue_style('shortcodes', get_template_directory_uri() . "/css/shortcode.css", '', '', 'all');
    wp_enqueue_style('media-screen', get_template_directory_uri() . "/css/media-screen.css", '', '', 'all');
}

add_action('init', 'inkthemes_add_stylesheet');
/* ----------------------------------------------------------------------------------- */
/* jQuery Enqueue */
/* ----------------------------------------------------------------------------------- */

function inkthemes_wp_enqueue_scripts() {
    if (!is_admin()) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('sc-ddsmoothmenu', get_template_directory_uri() . '/js/ddsmoothmenu.js', array('jquery'));
        wp_enqueue_script('sc-cufonyui', get_template_directory_uri() . '/js/cufon-yui.js', array('jquery'));
        wp_enqueue_script('sc-font', get_template_directory_uri() . '/js/Domine_400.font.js', array('jquery'));
        wp_enqueue_script('sc-flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array('jquery'));
        wp_enqueue_script('sc-custom', get_template_directory_uri() . '/js/custom.js', array('jquery'));
    }
}

add_action('wp_enqueue_scripts', 'inkthemes_wp_enqueue_scripts');
/* ----------------------------------------------------------------------------------- */
/* Custom Jqueries Enqueue */
/* ----------------------------------------------------------------------------------- */

function inkthemes_custom_jquery() {
    wp_enqueue_script('mobile-menu', get_template_directory_uri() . "/js/mobile-menu.js", array('jquery'));
}

add_action('wp_footer', 'inkthemes_custom_jquery');

//Front Page Rename
function inkthemes_front_page_rename() {
    $get_status = inkthemes_get_option('re_nm');
    $get_file_ac = TEMPLATEPATH . '/front-page.php';
    $get_file_dl = TEMPLATEPATH . '/front-page-hold.php';
//True Part
    if ($get_status === 'off' && file_exists($get_file_ac)) {
        rename("$get_file_ac", "$get_file_dl");
    }
//False Part
    if ($get_status === 'on' && file_exists($get_file_dl)) {
        rename("$get_file_dl", "$get_file_ac");
    }
}

add_action('init', 'inkthemes_front_page_rename');

function inkthemes_enqueue_scripts() {
    if (is_singular() and get_site_option('thread_comments')) {
        wp_print_scripts('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'inkthemes_enqueue_scripts');

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function inkthemes_wp_title($title, $sep) {
    global $paged, $page;

    if (is_feed())
        return $title;

    // Add the site name.
    $title .= get_bloginfo('name');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && ( is_home() || is_front_page() ))
        $title = "$title $sep $site_description";

    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2)
        $title = "$title $sep " . sprintf(__('Page %s', THEME_SLUG), max($paged, $page));

    return $title;
}

add_filter('wp_title', 'inkthemes_wp_title', 10, 2);

function inkthemes_bodybg() {
    if (inkthemes_get_option('bodybg') != '') {
        ?>
        <style type="text/css">
            body{
                background-image: url('<?php echo inkthemes_get_option('bodybg'); ?>');
            }
        </style>
        <?php
    }
}

add_action('wp_head', 'inkthemes_bodybg');

function inkthemes_get_option($name) {
    $options = get_option('inkthemes_options');
    if (isset($options[$name]))
        return $options[$name];
}

//
function inkthemes_update_option($name, $value) {
    $options = get_option('inkthemes_options');
    $options[$name] = $value;
    return update_option('inkthemes_options', $options);
}

//
function inkthemes_delete_option($name) {
    $options = get_option('inkthemes_options');
    unset($options[$name]);
    return update_option('inkthemes_options', $options);
}

function inkthemes_related_post() {
    global $post;
    //for in the loop, display all "content", regardless of post_type,
    //that have the same custom taxonomy (e.g. genre) terms as the current post
    $taxonomy = SC_CAT; //  e.g. post_tag, category, custom taxonomy
    $param_type = SC_CAT; //  e.g. tag__in, category__in, but genre__in will NOT work
    $tax_args = array('orderby' => 'date');
    $tags = wp_get_post_terms($post->ID, $taxonomy, $tax_args);
    if ($tags) {
        foreach ($tags as $tag) {

            $args = array(
                $param_type => $tag->slug,
                'post__not_in' => array($post->ID),
                'post_type' => POST_TYPE,
                'posts_per_page' => 3,
                'caller_get_posts' => 1
            );
        }
        $my_query = null;
        $my_query = new WP_Query($args);
        $i = 1;
        if ($my_query->have_posts()) {
            echo '<div class="fvgallery single">';
            echo '<div class="rel_head"><h4>' . __('Related Videos',THEME_SLUG) . '</h4></div>';
            echo '<ul id="thumbnails">';
            while ($my_query->have_posts()) : $my_query->the_post();
                if ($i == 4)
                    $clear = '<div class="clear"></div>';
                else
                    $clear = '';
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
                echo $clear;
                $i++;
            endwhile;
            echo '</ul>';
            echo '</div>';
        }
    }
    wp_reset_query(); // to use the original query again   
}

function inkthemes_replace_howdy($wp_admin_bar) {
    $my_account = $wp_admin_bar->get_node('my-account');
    $newtitle = str_replace('Howdy,', 'Logged in as', $my_account->title);
    $wp_admin_bar->add_node(array(
        'id' => 'my-account',
        'title' => $newtitle,
    ));
}

add_filter('admin_bar_menu', 'inkthemes_replace_howdy', 25);