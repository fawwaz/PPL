<?php
/*
Plugin Name: oneTarek Add Custom Ueser Meta
Description: Demo plugin to add new custom user meta field with WordPress user profile.
Author: oneTarek
Author URI: http://onetarek.com
Version: 1.0.0
Source: http://onetarek.com/wordpress/how-to-add-extra-meta-field-for-user-profile-in-wordpress-plugin/
*/


//================== Add Extra TWITTER Field with user profile =========================

add_action( 'show_user_profile', 'oneTarek_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'oneTarek_extra_user_profile_fields' );
add_action( 'personal_options_update', 'oneTarek_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'oneTarek_save_extra_user_profile_fields' );

function oneTarek_save_extra_user_profile_fields( $user_id )
	{
	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
	update_user_meta( $user_id, 'oneTarek_twitter', $_POST['oneTarek_twitter'] );
	}
#Developed By oneTarek , http://oneTarek.com
function oneTarek_extra_user_profile_fields( $user ) 
	{ ?>
	<h3>Extra Custom Meta Fields</h3>
	 
	<table class="form-table">
		<tr>
			<th><label for="oneTarek_twitter">Twitter  User Name</label></th>
			<td>
			<input type="text" id="oneTarek_twitter" name="oneTarek_twitter" size="20" value="<?php echo esc_attr( get_the_author_meta( 'oneTarek_twitter', $user->ID )); ?>">
			<span class="description">Please enter your Twitter Account User name, eg: oneTarek</span>
			</td>
		</tr>
	</table>
<?php }?>