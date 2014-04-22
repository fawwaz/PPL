<?php
/*
Plugin Name: Divusi_course
Plugin URI: https://github.com/fawwaz/ppl
Description: Simple wordpress plugin for divusi (payment)
Version: 1.0
Author: Ridho Akbarisanto, Yogi Sinaga and Fawwaz Muhammad 
Author URI: http://www.kuliah.itb.ac.id/
License: GPL2
*/
/*
Copyright 2012  Francis Yaconiello  (email : francis@yaconiello.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!class_exists('Divusi_Course'))
{
	class Divusi_Course
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// Initialize Settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$Divusi_Course_Settings = new Divusi_Course_Settings();

			// Register custom post types
			require_once(sprintf("%s/post-types/divusi_course_post-type-template.php", dirname(__FILE__)));
			$Divusi_Course_Post_Type = new Divusi_Course_Post_Type();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate

		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=Divusi_Course">Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}


	} // END class Divusi_Course
} // END if(!class_exists('Divusi_Course'))

if(class_exists('Divusi_Course'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('Divusi_Course', 'activate'));
	register_deactivation_hook(__FILE__, array('Divusi_Course', 'deactivate'));

	// instantiate the plugin class
	$Divusi_Course = new Divusi_Course();

}
