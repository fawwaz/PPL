<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="utf-8" />
        <title><?php
/*
 * Print the <title> tag based on what is being viewed.
 */
wp_title('|', true, 'right');
?>
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
        <?php if (inkthemes_get_option('inkthemes_keyword') != '') { ?>
            <meta name="keywords" content="<?php echo inkthemes_get_option('inkthemes_keyword'); ?>" />
            <?php
        }
        ?>
        <?php if (inkthemes_get_option('inkthemes_description') != '') { ?>
            <meta name="description" content="<?php echo inkthemes_get_option('inkthemes_description'); ?>" />
            <?php
        }
        ?>
        <?php if (inkthemes_get_option('inkthemes_author') != '') { ?>
            <meta name="author" content="<?php echo inkthemes_get_option('inkthemes_author'); ?>" />
            <?php
        }
        /* Always have wp_head() just before the closing </head>
         * tag of your theme, or you will break many plugins, which
         * generally use this hook to add elements to <head> such
         * as styles, scripts, and meta tags.
         */
        wp_head();
        ?>     
    </head>
    <body <?php body_class(); ?>>
        <!--Start Header Wrapper-->
        <div id="header_wrapper">
            <!--Start Container-->
            <div class="container_24">
                <div class="grid_24">
                    <!--Start Header-->
                    <div class="header">
                        <div class="grid_10 alpha">
                            <div class="logo"><a href="<?php echo esc_url(home_url()); ?>"><img src="<?php if (inkthemes_get_option('inkthemes_logo') != '') { ?><?php echo inkthemes_get_option('inkthemes_logo'); ?><?php } else { ?><?php echo get_template_directory_uri(); ?>/images/logo.png<?php } ?>" alt="<?php bloginfo('name'); ?>" /> </div>
                        </div>
                        <div class="grid_14 omega">
                            <!--Start Menu wrapper-->
                            <div class="menu_wrapper">
                                <a href="#" class="mobile_nav closed"><?php _e('Page Navigation',THEME_SLUG); ?><span></span></a>
                                <?php inkthemes_nav(); ?>
                            </div>
                            <!--End Menu wrapper-->
                        </div>
                        <div class="clear"></div>

                    </div>
                    <!--End Header-->
                </div>
                <div class="clear"></div>
            </div>
            <!--End Container-->
        </div>
        <!--End Header Wrapper-->
        <div class="clear"></div>