<?php

define('FUNCTIONPATH', TEMPLATEPATH . '/functions/');
define('LIBPATH', FUNCTIONPATH . 'lib/');
define('LIBURL', get_template_directory_uri() . '/functions/lib/');
define('THEME_SLUG', 'subscribely');

include_once (FUNCTIONPATH . 'inkthemes-functions.php');
include_once (FUNCTIONPATH . 'theme_functions.php');
require_once (FUNCTIONPATH . 'admin-functions.php');
require_once (FUNCTIONPATH . 'admin-interface.php');
require_once (FUNCTIONPATH . 'theme-options.php');
require_once (FUNCTIONPATH . 'dynamic-image.php');
require_once (FUNCTIONPATH . 'shortcodes.php');
require_once (FUNCTIONPATH . 'widget_popular.php');
require_once (FUNCTIONPATH . 'inkthemes-member-plugin.php');
require_once (FUNCTIONPATH . 'theme_actions.php');
require_once (FUNCTIONPATH . 'theme_hooks.php');
require_once (FUNCTIONPATH . 'category_widget.php');
require_once dirname(__FILE__) . '/plugin-activation.php';
require_once (LIBPATH . 'scv.php');
add_action('tgmpa_register', 'inkthemes_member_plugins');
