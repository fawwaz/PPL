<?php
/*
Plugin Name: Divusi User
Plugin URI: http://facebook.com/fawwazmuhammad
Description: User management for divusi course
Version: 1.0
License: GPLv2
Author: Fawwaz Muhammad, Yogi Sinaga, Ridho Akbarisanto
Author URI: http://twitter.com/fawwaz_muhammad
*/

add_action('admin_menu','divusi_user_add_dashboard_menu');

function divusi_user_add_dashboard_menu(){
	add_menu_page( 'Atur User', 'Atur User', 'promote_users', 'Pelanggan', 'render_divusi_user_dashboard_menu', 'dashicons-groups', 99 );
}

function render_divusi_user_dashboard_menu(){
	if(isset( $_POST['divusi_user_submitted'] ) && wp_verify_nonce($_POST['divusi_user_submitted'], 'divusi_user_form') ){  
		// kalau aksi edit
		if(isset($_get['action']) && $_get['action']=='edit'){
			// keluarin form edit
		}
	}
	?> 
	lakukan queryin terhadap table disini
	<?php 
}