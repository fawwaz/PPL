<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */
get_header();
sc_front__header_entry();
sc_before_content();
?>
<!--Start Content Wrapper-->
<div id="content_wrapper">
    <h1 class="thumb_head">
        <?php
        if (inkthemes_get_option('inkthemes_home_head') != '') {
            echo inkthemes_get_option('inkthemes_home_head');
        } else {
            echo 'Our Premium Videos';
        }
        ?>
    </h1>    
    <div class="border_strip"></div>
    <ul id="thumbnails">
        <?php
        $post_type = POST_TYPE;
        if (inkthemes_get_option('inkthemes_thumb_number') !='') {
            $limit = inkthemes_get_option('inkthemes_thumb_number');
        } else {
            $limit = 8;
        }
        query_posts('post_type=' . $post_type . '&showposts=' . $limit . '');
        sc_thumbnails();
        wp_reset_query();
        ?>
    </ul>
</div>
<!--End Content Wrapper-->
<?php
sc_after_content();
get_footer();
?>