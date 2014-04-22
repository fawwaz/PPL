<?php
class im_add_shortcode {

    var $pluginname = "ssshorts_buttons";

    function im_add_shortcode() {
        // Modify the version when tinyMCE plugins are changed.
        add_filter('tiny_mce_version', array(&$this, 'im_change_tinymce_version'));
        // init process for button control
        add_action('init', array(&$this, 'addShortcode'));
    }

    function addShortcode() {
        // Don't bother doing this stuff if the current user lacks permissions
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
            return;
        // Add only in Rich Editor mode
        if (get_user_option('rich_editing') == 'true') {
            // add the button for wp2.5 in a new way
            add_filter("mce_external_plugins", array(&$this, "add_im_plugin"), 5);
            add_filter('mce_buttons_3', array(&$this, 'register_im_button'), 5);
        }
    }

    // used to insert button in wordpress 2.5x editor
    function register_im_button($buttons) {
        array_push($buttons, "", $this->pluginname);
        return $buttons;
    }

    // Load the TinyMCE plugin : editor_plugin.js (wp2.5)
    function add_im_plugin($plugin_array) {
        global $ShortcodeSumoPath;
        $plugin_array[$this->pluginname] = IM_PLUGIN_PATH . 'inc/shortcode/shortcode.js';
        return $plugin_array;
    }

    function im_change_tinymce_version($version) {
        return++$version;
    }

}

// Call it now
$tinymce_button = new im_add_shortcode ();