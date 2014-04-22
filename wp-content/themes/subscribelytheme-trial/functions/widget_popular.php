<?php

function widget_popularPosts_init() {
    if (!function_exists('register_sidebar_widget'))
        return;

    function widget_popularPosts($args) {

        // "$args is an array of strings that help widgets to conform to
        // the active theme: before_widget, before_title, after_widget,
        // and after_title are the array keys." - These are set up by the theme
        extract($args);
        // These are our own options
        $options = get_option('widget_popular_posts');
        $title = $options['title'];  // Title in sidebar for widget
        $show = $options['show'];  // # of Posts we are showing       
        if ($show < 1)
            $show = 1;
        if ($exclude == "")
            $exclude = "0";

        // Output
        echo $before_widget . $before_title . $title . $after_title;
        // GET POSTS
        ?>     
            <ol id="popular_posts">
                <?php $pp = new WP_Query('orderby=comment_count&posts_per_page=5&post_type='.POST_TYPE); ?>
        <?php while ($pp->have_posts()) : $pp->the_post(); ?>
                    <li>
                        <p><?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                                <?php inkthemes_get_thumbnail(264, 175); ?>                    
                            <?php } else { ?>
                                <?php inkthemes_get_image(264, 175); ?> 
                                <?php
                            }
                            ?>                                                
                        <h6 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h6>
                        </p>                        
                    </li>
                    <div class="clear"></div>
        <?php endwhile; ?>
            </ol>
        <?php
        // echo widget closing tag
        echo $after_widget;
    }

    // Settings form
    function widget_popular_control() {
        // Get options
        $options = get_option('widget_popular_posts');
        // options exist? if not set defaults
        if (!is_array($options))
            $options = array('title' => 'Popular Videos', 'show' => '5', 'excerpt' => '1', 'exclude' => '');

        // form posted?
        if ($_POST['myRecentPosts-submit']) {
            // Remember to sanitize and format use input appropriately.
            $options['title'] = strip_tags(stripslashes($_POST['myRecentPosts-title']));
            update_option('widget_popular_posts', $options);
        }
        // Get options for form fields to show
        $title = htmlspecialchars($options['title'], ENT_QUOTES);
        $show = htmlspecialchars($options['show'], ENT_QUOTES);
        // The form fields
        echo '<p style="text-align:right;">
				<label for="myRecentPosts-title">' . __('Title:', 'inkthemes_blogtrend') . ' 
				<input style="width: 150px;" id="myRecentPosts-title" name="myRecentPosts-title" type="text" value="' . $title . '" />
				</label></p>';
        echo '<input type="hidden" id="myRecentPosts-submit" name="myRecentPosts-submit" value="1" />';
    }

    // Register widget for use
    register_sidebar_widget(array('Popular Video Widget', 'widgets'), 'widget_popularPosts');
    // Register settings for use, 300x100 pixel form
    register_widget_control(array('Popular Video Widget', 'widgets'), 'widget_popular_control', 260, 200);
}

// Run code and init
add_action('widgets_init', 'widget_popularPosts_init');
?>