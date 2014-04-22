<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;
//define custom tag or category
define('POST_TYPE', 'videos');
define('SC_CAT', 'vid_cat');
define('SC_TAG', 'vid_tag');
if (!class_exists('SC_MediaEncoder')):
    /*
      Main WPVPMediaEncoder Class
     */

    class SC_MediaEncoder {

        /**
         * @var string WPVPMediaEncoder version
         */
        public $version = '2.0';

        public static function init() {
            $class = __CLASS__;
            new $class;
        }

        /**
         * The main loader
         * $uses WPVPMediaEncoder::includes() Include required files
         */
        public function __construct() {
            $this->includes();
            $this->setup_actions();
        }

        /**
         * Include required files
         * @access private
         */
        private function includes() {
            require( dirname(__FILE__) . '/classes/sc-core-class.php');
            require( dirname(__FILE__) . '/classes/sc-helper-class.php');
        }

        /**
         * Setup teh default hooks and actions
         * @access private
         */
        private function setup_actions() {         
            add_action('init', array(&$this, 'sc_register_videos_post_type'));            
            add_action('wp_enqueue_scripts', array(&$this, 'sc_enqueue_scripts'));
            add_action('add_attachment', array(&$this, 'sc_encode'), 1);
            add_action('publish_videos', array(&$this, 'sc_add_code_meta'), 20, 1);
            add_action('the_content', array(&$this, 'sc_insert_edit_link_into_video_post'), 10, 1);
            add_filter('media_send_to_editor', array(&$this, 'sc_insert_shortcode_into_post'), 10, 3);
            add_filter('upload_mimes', array(&$this, 'sc_add_video_formats_support'), 10, 1);
            add_filter('attachment_fields_to_save', array(&$this, 'sc_attachment_save'), 10, 1);
            add_filter('attachment_fields_to_edit', array(&$this, 'sc_attachment_edit'), 10, 1);
            add_shortcode('wpvp_flowplayer', array(&$this, 'sc_register_shortcode'));
            add_shortcode('wpvp_embed', array(&$this, 'sc_register_embed_shortcode'));
            add_shortcode('wpvp_upload_video', array(&$this, 'sc_register_front_uploader_shortcode'));
            //Only call the published notification email function if the option is set to yes
            $wpvp_published_notification = get_option('wpvp_published_notification', 'no');
            if ($wpvp_published_notification == 'yes') {
                add_action('draft_to_publish', array(&$this, 'sc_draft_to_publish_notification'), 20, 1);
                add_action('pending_to_publish', array(&$this, 'sc_draft_to_publish_notification'), 20, 1);
            }
            $wpvp_main_loop_alter = get_option('wpvp_main_loop_alter', 'yes');
            if ($wpvp_main_loop_alter == 'yes') {
                add_action('pre_get_posts', array(&$this, 'sc_get_custom_video_posts'), 1);
            }
        }

        /*
         * *register custom post type: videos on init action hook
         * @access private
         */

        public function sc_register_videos_post_type() {
            register_post_type(POST_TYPE, array('label' => 'Videos',
                'labels' => array('name' => 'Video',
                    'singular_name' => _x('Video Item', 'post type singular name'),
                    'add_new' => _x('Add New Video', 'video item'),
                    'add_new_item' => __('Add New Video Item'),
                    'edit' => 'Edit',
                    'edit_item' => __('Edit Video Item'),
                    'new_item' => __('New Video Item'),
                    'view_item' => __('View Video Item'),
                    'search_items' => __('Search Video'),
                    'not_found' => __('Nothing found'),
                    'not_found_in_trash' => __('Nothing found in Trash')),
                'public' => true,
                'can_export' => true,
                'has_archive' => TRUE,
                'show_ui' => true, // UI in admin panel
                '_builtin' => false, // It's a custom post type, not built in
                '_edit_link' => 'post.php?post=%d',
                'capability_type' => 'post',
                'menu_icon' => LIBURL . 'images/videos_menu_icon.png',
                'hierarchical' => false,
                'rewrite' => array("slug" => POST_TYPE), // Permalinks
                'has_archive' => true,
                'menu_position' => 3,
                'query_var' => POST_TYPE, // This goes to the WP_Query schema
                'supports' => array('title',
                    'author',
//            'excerpt',
                    'thumbnail',
                    'comments',
                    'editor',
                    //'trackbacks',
                    //'custom-fields',
                    'revisions'),
                'show_in_nav_menus' => true,
                'taxonomies' => array(SC_CAT, SC_TAG)
                    )
            );

            //register_taxonomy_for_object_type('category', 'videos');
            register_taxonomy(SC_CAT, array(POST_TYPE), array("hierarchical" => true,
                "label" => 'Video Categories',
                'labels' => array('name' => 'Video Categories',
                    'singular_name' => 'Category',
                    'search_items' => 'Search category',
                    'popular_items' => 'Search category',
                    'all_items' => 'All categories',
                    'parent_item' => 'Parent category',
                    'parent_item_colon' => 'Parent category:',
                    'edit_item' => 'Edit category',
                    'update_item' => 'Update category',
                    'add_new_item' => 'Add new category',
                    'new_item_name' => 'New category name',),
                'public' => true,
                'show_ui' => true,
                "rewrite" => true)
            );
            //Register custom taxonomy for tags
            register_taxonomy(SC_TAG, array(POST_TYPE), array("hierarchical" => false,
                "label" => 'Video Tags',
                'labels' => array('name' => 'Video Tags',
                    'singular_name' => 'Video Tags',
                    'search_items' => 'Video Tags',
                    'popular_items' => 'Popular video tags',
                    'all_items' => 'All Video tags',
                    'parent_item' => 'Parent Video tags',
                    'parent_item_colon' => 'Parent Video tags:',
                    'edit_item' => 'Edit Video tags',
                    'update_item' => 'Update Video tags',
                    'add_new_item' => 'Add New Video tags',
                    'new_item_name' => 'New video tag name'),
                'public' => true,
                'show_ui' => true,
                'rewrite' => array('slug' => 'tag')
                    )
            );
        }

        /*
         * Enqueue scripts on wp_enqueue_scripts action hook
         * @action public
         */

        public function sc_enqueue_scripts() {
            wp_enqueue_script('wpvp_flowplayer', LIBURL . 'js/flowplayer-3.2.10.min.js', array('jquery'), NULL);
            wp_enqueue_script('wpvp_front_end_js', LIBURL . 'js/wpvp-front-end.js', array('jquery'), NULL);
            wp_enqueue_script('wpvp_flowplayer_js', LIBURL . 'js/wpvp_flowplayer.js', array('jquery', 'wpvp_flowplayer'), NULL);
            $swf_loc = LIBURL . 'js/flowplayer-3.2.11.swf';
            $vars_to_pass = array('swf' => $swf_loc);
            wp_localize_script('wpvp_flowplayer_js', 'object_name', $vars_to_pass);
        }

        /**
         * add support for videos of defined extensions on upload_mimes filter hook
         * @access public
         */
        public function sc_add_video_formats_support($existing_mimes) {
            $existing_mimes['mov'] = 'video/quicktime';
            $existing_mimes['avi'] = 'video/avi';
            $existing_mimes['wmv|wvx|wm|wmx'] = 'video/x-ms-wmv';
            $existing_mimes['3gp|3gpp|3gpp2|3g2'] = 'video/3gpp';
            return $existing_mimes;
        }

        /**
         * Process encoding on add_attachment action hook
         * @access public
         */
        public function sc_encode($ID) {
            $postID = intval($_REQUEST['post_id']);
            if ($postID) {
                $helper = new SC_Helper();
                $options = $helper->sc_get_full_options();
                $newEncode = new SC_Encode_Media($options);
                $encode_video = $newEncode->sc_encode($ID);
                return $encode_video;
            } else {
                return;
            }
        }

        /**
         * send email on post status change to publish to the post author on draft_to_publish and pending_to_publish action hook
         * @access public
         */
        public function sc_draft_to_publish_notification($postObj) {
            global $post;
            if ($postObj->post_type == 'videos') {
                $post_content = $postObj->post_content;
                $post_author_id = $postObj->post_author;
                $userObj = get_userdata($post_author_id);
                $post_author_email = $userObj->user_email;
                $post_author_login = $userObj->user_login;
                $post_thumb = explode('splash=', $post_content);
                $post_thumb = explode(']', $post_thumb[1]);
                $post_thumb = $post_thumb[0];
                $post_permalink = get_permalink($postObj->ID);
                $admin = array($post_author_email);
                if (strlen($postObj->post_title) > 15) {
                    $postObj->post_title = substr($postObj->post_title, 0, 15) . '...';
                }
                $subject = get_bloginfo('name') . ': "' . $postObj->post_title . '" has been published';
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $message = 'Your video has been reviewed by ' . get_bloginfo('name') . ' and it has been published. You can view your video by accessing this link, "<a href="' . $post_permalink . '">' . $postObj->post_title . '</a>".<br /><br /><a href="' . $post_permalink . '"><img src="' . $post_thumb . '" width="250px" height="142px" /><br />' . $postObj->post_title . '</a>';
                $message .= '<br /><br />Regards,<br />' . get_bloginfo('name');
                $send_publish_notice = wp_mail($admin, $subject, $message, $headers);
            }
        }

        /*
         * *Update post data on attachment_fields_to_save filter hook
         * @access public
         */

        public function sc_attachment_save($data) {
            $helper = new SC_Helper();
            if ($helper->is_video($data['post_mime_type']) == 'video') {
                $parent_post = $data['post_parent'];
                $newdata = array(
                    'ID' => $parent_post,
                    'post_excerpt' => $data['post_content'],
                    'post_title' => $data['post_title'],
                    'tags_input' => $data['post_excerpt']
                );
                wp_update_post($newdata);
                return $data;
            } else {
                return $data;
            }
        }

        /*
         * *Edit attachment data on attachment_fields_to_edit filter hook
         * @access public
         */

        public function sc_attachment_edit($data) {
            $helper = new SC_Helper();
            $ext = $helper->guess_file_type($data['post_title']['value']);
            if ($ext == 'flv') {
                $data['post_excerpt']['label'] = 'Tags';
                $data['post_excerpt']['helps'] = 'Separate tags with commas';
                return $data;
            } else {
                return $data;
            }
        }

        /**
         * insert short code into post on media_send_to_editor filter hook
         * @access public
         */
        public function sc_insert_shortcode_into_post($html, $id, $attachment) {
            $postID = intval($_REQUEST['post_id']);
            $postObj = get_post($postID);
            $postContent = $html;
            if ($postObj->post_type == 'videos') {
                $helper = new SC_Helper();
                $options = $helper->sc_get_full_options();
                $newVideo = new SC_Encode_Media($options);
                $postContent = $newVideo->sc_insert_video_into_post($postContent, $id, $attachment);
            }
            return $postContent;
        }

        /**
         * function to add code to the post meta and update on post update if needed on publish_videos custom post type action hook
         * @access public
         */
        public function sc_add_code_meta($id) {
            $helper = new SC_Helper();
            $helper->sc_video_code_add_meta($id);
        }

        /**
         * insert video edit link into video post (only if admin or post author) on the_content action hook
         * @access public
         */
        public function sc_insert_edit_link_into_video_post($content) {
            global $post;
            $postID = $post->ID;
            $post_type = get_post_type($postID);
            $post_status = get_post_status($postID);
            $editPageID = get_option('wpvp_editor_page');
            $curr_user = wp_get_current_user();
            $user_id = $curr_user->ID;
            //get post Object based on post id
            $post_author = $post->post_author;
            if (current_user_can('administrator') || $user_id == $post_author) {
                $permalink = get_permalink($editPageID);
                if (is_single() && $post_type == 'videos' && $post_status == 'publish' && $editPageID && $permalink != "") {
                    $content = '<div class="wpvp_edit_video_link"><a href="' . $permalink . '?video=' . $postID . '">Edit Video</a></div>' . $content;
                }
            }
            return $content;
        }

        /**
         * Alter main Wordpress query for latest posts, categories, feed, tags to display videos custom post type
         * on pre_get_posts filter
         * @access public
         */
        public function sc_get_custom_video_posts($query) {
            if ((is_home() && $query->is_main_query()) || is_feed() || (is_category() && $query->is_main_query()) || (is_tag() && $query->is_main_query()) || (is_front_page() && $query->is_main_query()) || (is_author() && $query->is_main_query())) {
                $query->set('post_type', array('post', 'videos'));
            }
            return $query;
        }

        /*         * * REGISTER SHORT CODES ** */

        /**
         * Register shortcode for flowplayer videos
         * @access public
         */
        public function sc_register_shortcode($atts) {
            extract(shortcode_atts(array(
                        'src' => '',
                        'width' => '640',
                        'height' => '360',
                        'splash' => ''
                            ), $atts));
            $flowplayer_code = '<a href="' . $src . '" class="myPlayer" style="display:block;width:' . $width . 'px;height:' . $height . 'px;margin:10px auto"><img width="' . $width . '" height="' . $height . '" src="' . $splash . '" alt="" /></a>';
            return $flowplayer_code;
        }

        /**
         * register shortcode to embed videos via video codes
         * @access public
         */
        public function sc_register_embed_shortcode($atts) {
            extract(shortcode_atts(array(
                        'video_code' => '',
                        'width' => '560',
                        'height' => '315',
                        'type' => ''
                            ), $atts));
            $newDisplay = new SC_Encode_Media();
            $embedCode = $newDisplay->sc_video_embed($video_code, $width, $height, $type);
            return $embedCode;
        }

        /**
         * register shortcode for the front end uploader
         * @access public
         */
        public function sc_register_front_uploader_shortcode($atts) {
            extract(shortcode_atts(array(
                            ), $atts));
            $newMedia = new SC_Encode_Media();
            $uploader = $newMedia->sc_front_video_uploader();
            return $uploader;
        }

    }

    new SC_MediaEncoder();
endif;
function sc_remove_menu_items() {
    if( !current_user_can( 'administrator' ) ):
        remove_menu_page( 'edit.php?post_type=videos' );
    endif;
}
add_action( 'admin_menu', 'sc_remove_menu_items' );
?>
