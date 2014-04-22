<?php

add_action('init', 'of_options');
if (!function_exists('of_options')) {

    function of_options() {
        // VARIABLES
        $themename = 'Subscribely Pro Theme Option';
        $shortname = "of";
        // Populate OptionsFramework option in array for use in theme
        global $of_options;
        $of_options = inkthemes_get_option('of_options');
        // Front page on/off
        $file_rename = array("on" => "On", "off" => "Off");
        // Background Defaults
        $background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat', 'position' => 'top center', 'attachment' => 'scroll');
        //Stylesheet Reader
        $alt_stylesheets = array("default" => "default", "black" => "black", "blue" => "blue", "green" => "green", "orange" => "orange", "purple" => "purple", "red" => "red", "teal-green" => "teal-green", "yellow" => "yellow");

        //RTL Stylesheet Reader
        $lan_stylesheets = array("default" => "default", "rtl" => "rtl");


        $captcha_option = array("on" => "On", "off" => "Off");


        // Pull all the categories into an array
        $options_categories = array();
        $options_categories_obj = get_categories();
        foreach ($options_categories_obj as $category) {
            $options_categories[$category->cat_ID] = $category->cat_name;
        }
        // Pull all the pages into an array
        $options_pages = array();
        $options_pages_obj = get_pages('sort_column=post_parent,menu_order');
        $options_pages[''] = 'Select a page:';
        foreach ($options_pages_obj as $page) {
            $options_pages[$page->ID] = $page->post_title;
        }
        // If using image radio buttons, define a directory path
        $imagepath = get_stylesheet_directory_uri() . '/images/';

        $options = array();
        $options[] = array("name" => "General Settings",
            "type" => "heading");

        $options[] = array("name" => "Custom Logo",
            "desc" => "Upload a logo for your Website. The recommended size for the logo is 340px width x 46px height.",
            "id" => "inkthemes_logo",
            "type" => "upload");

        $options[] = array("name" => "Custom Favicon",
            "desc" => "Specify a 16px x 16px image that will represent your website's favicon.",
            "id" => "inkthemes_favicon",
            "type" => "upload");

        $options[] = array("name" => " Background Image",
            "desc" => "Choose a suitable background image that will complement your website.",
            "id" => "inkthemes_bodybg",
            "std" => "",
            "type" => "upload");

        $options[] = array("name" => "Google Tracking Code",
            "desc" => "Paste your Google Analytics (or other) tracking code here.",
            "id" => "inkthemes_analytics",
            "std" => "",
            "type" => "textarea");


        //Slider Setting
        $options[] = array("name" => "Slider Settings",
            "type" => "heading");

        //First slider
        $options[] = array("name" => "First Slider Heading",
            "desc" => "Enter your first slider heading in few lines.",
            "id" => "inkthemes_heading_1",
            "std" => "",
            "type" => "text");
        $options[] = array("name" => "First Slider Description (Only Available In Premium Version)",
            "desc" => "Enter short description for first slider description.",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "textarea");

        $options[] = array("name" => "First Slider Button Text",
            "desc" => "Enter your button text for first slider",
            "id" => "inkthemes_btn_text_1",
            "std" => "",
            "type" => "text");

        $options[] = array("name" => "First Slider Button Link URL (Only Available In Premium Version)",
            "desc" => "Enter your link url for first slider button",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "text");

        $options[] = array("name" => "First Slider Video Area (Only Available In Premium Version)",
            "desc" => "Upload your image or enter your video embed code for first slider.",
            "id" => "",
            "std" => "",
            "class" => "trialhint",
            "type" => "upload");

        //Second slider
        $options[] = array("name" => "Second Slider Heading (Only Available In Premium Version)",
            "desc" => "Enter your second slider heading in few lines.",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "text");
        $options[] = array("name" => "Second Slider Description (Only Available In Premium Version)",
            "desc" => "Enter short description for second slider description.",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "textarea");

        $options[] = array("name" => "Second Slider Button Text (Only Available In Premium Version)",
            "desc" => "Enter your button text for second slider",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "text");

        $options[] = array("name" => "Second Slider Button Link URL (Only Available In Premium Version)",
            "desc" => "Enter your link url for second slider button",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "text");

        $options[] = array("name" => "Second Slider Video Area (Only Available In Premium Version)",
            "desc" => "Upload your image or enter your video embed code for second slider.",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "upload");

        //Third slider
        $options[] = array("name" => "Third Slider Heading (Only Available In Premium Version)",
            "desc" => "Enter your second third heading in few lines.",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "text");
        $options[] = array("name" => "Third Slider Description (Only Available In Premium Version)",
            "desc" => "Enter short description for third slider description.",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "textarea");

        $options[] = array("name" => "Third Slider Button Text (Only Available In Premium Version)",
            "desc" => "Enter your button text for third slider",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "text");

        $options[] = array("name" => "Third Slider Button Link URL (Only Available In Premium Version)",
            "desc" => "Enter your link url for third slider button",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "text");

        $options[] = array("name" => "Third Slider Video Area (Only Available In Premium Version)",
            "desc" => "Upload your image or enter your video embed code for third slider.",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "upload");

        //Fourth slider
        $options[] = array("name" => "Fourth Slider Heading (Only Available In Premium Version)",
            "desc" => "Enter your second fourth heading in few lines.",
            "id" => "inkthemes_heading_4",
            "class" => "trialhint",
            "std" => "",
            "type" => "text");
        $options[] = array("name" => "Fourth Slider Description",
            "desc" => "Enter short description for fourth slider description.",
            "id" => "inkthemes_des_4",
            "std" => "",
            "type" => "textarea");

        $options[] = array("name" => "Fourth Slider Button Text (Only Available In Premium Version)",
            "desc" => "Enter your button text for fourth slider",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "text");

        $options[] = array("name" => "Fourth Slider Button Link URL (Only Available In Premium Version)",
            "desc" => "Enter your link url for fourth slider button",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "text");

        $options[] = array("name" => "Fourth Slider Video Area (Only Available In Premium Version)",
            "desc" => "Upload your image or enter your video embed code for fourth slider.",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "upload");

        //Fifth slider
        $options[] = array("name" => "Fifth Slider Heading (Only Available In Premium Version)",
            "desc" => "Enter your second fifth heading in few lines.",
            "id" => "",
            "class" => "trialhint",
            "std" => "",
            "type" => "text");
        $options[] = array("name" => "Fifth Slider Description (Only Available In Premium Version)",
            "desc" => "Enter short description for fifth slider description.",
            "id" => "",
            "std" => "",
            "class" => "trialhint",
            "type" => "textarea");

        $options[] = array("name" => "Fifth Slider Button Text (Only Available In Premium Version)",
            "desc" => "Enter your button text for fifth slider",
            "id" => "inkthemes_btn_text_5",
            "std" => "",
            "class" => "trialhint",
            "type" => "text");

        $options[] = array("name" => "Fifth Slider Button Link URL (Only Available In Premium Version)",
            "desc" => "Enter your link url for fifth slider button",
            "id" => "",
            "std" => "",
            "class" => "trialhint",
            "type" => "text");

        $options[] = array("name" => "Fifth Slider Video Area (Only Available In Premium Version)",
            "desc" => "Upload your image or enter your video embed code for fifth slider.",
            "id" => "",
            "std" => "",
            "class" => "trialhint",
            "type" => "upload");

        $options[] = array("name" => "Homepage Settings",
            "type" => "heading");

        $options[] = array("name" => "Thumbnail Heading (Only Available In Premium Version)",
            "desc" => "Enter your thumbnail heading in homepage area",
            "id" => "",
            "std" => "",
            "class" => "trialhint",
            "type" => "text");

        $options[] = array("name" => "Numbers Of Thumbnails",
            "desc" => "Enter your numerical value to show number of thumbnails in homepage.",
            "id" => "inkthemes_thumb_number",
            "std" => "8",
            "type" => "text");

//****=============================================================================****//
//****-----------This code is used for creating color styleshteet options----------****//
//****=============================================================================****//
        $options[] = array("name" => "Styling Options",
            "type" => "heading");
        $options[] = array("name" => "Theme Stylesheet",
            "desc" => "Select any desired color for the theme from different available colors.",
            "id" => "inkthemes_altstylesheet",
            "std" => "black",
            "type" => "select",
            "options" => $alt_stylesheets);

        $options[] = array("name" => "Custom CSS",
            "desc" => "Quickly add some custom CSS code to your theme by writing the code in this block.",
            "id" => "inkthemes_customcss",
            "std" => "",
            "type" => "textarea");

//****=============================================================================****//
//****-------------This code is used for creating social logos options-------------****//
//****=============================================================================****//
        $options[] = array("name" => "Social Icons",
            "type" => "heading");

        $options[] = array("name" => "Twitter URL",
            "desc" => "Mention the URL of your Twitter here.",
            "id" => "inkthemes_twitter",
            "std" => "",
            "type" => "text");

        $options[] = array("name" => "Facebook URL",
            "desc" => "Mention the URL of your Facebook here.",
            "id" => "inkthemes_facebook",
            "std" => "",
            "type" => "text");

        $options[] = array("name" => "Rss Feed URL",
            "desc" => "Enter your Rss feed  URL",
            "id" => "inkthemes_rss",
            "std" => "",
            "type" => "text");

        $options[] = array("name" => "Google+ URL",
            "desc" => "Mention the URL of your Google+ here.",
            "id" => "inkthemes_google",
            "std" => "",
            "type" => "text");



//****=============================================================================****//
//****-------------This code is used for creating Bottom Footer Setting options-------------****//
//****=============================================================================****//
        $options[] = array("name" => "Footer Settings",
            "type" => "heading");
        $options[] = array("name" => "Footer Text",
            "desc" => "Write the text here that will be displayed on the footer i.e. at the bottom of the Website.",
            "id" => "inkthemes_footertext",
            "std" => "",
            "type" => "textarea");
        //------------------------------------------------------------------//
//-------------This code is used for creating SEO description-------//
//------------------------------------------------------------------//
        $options[] = array("name" => "SEO Options",
            "type" => "heading");
        $options[] = array("name" => "Meta Keywords (comma separated)",
            "desc" => "Meta keywords provide search engines with additional information about topics that appear on your site. This only applies to your home page. Keyword Limit Maximum 8",
            "id" => "inkthemes_keyword",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Meta Description",
            "desc" => "You should use meta descriptions to provide search engines with additional information about topics that appear on your site. This only applies to your home page.Optimal Length for Search Engines, Roughly 155 Characters",
            "id" => "inkthemes_description",
            "std" => "",
            "type" => "textarea");
        $options[] = array("name" => "Meta Author Name",
            "desc" => "You should write the full name of the author here. This only applies to your home page.",
            "id" => "inkthemes_author",
            "std" => "",
            "type" => "textarea");

        inkthemes_update_option('of_template', $options);
        inkthemes_update_option('of_themename', $themename);
        inkthemes_update_option('of_shortname', $shortname);
    }

}
?>
