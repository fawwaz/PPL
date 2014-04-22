<?php
//Expiry term for older and admin's listings. 
global $wpdb, $im_tbl_expiry;
$users_query = "SELECT * FROM $im_tbl_expiry";
$users_result = $wpdb->get_results($users_query);
if (!empty($users_result)) {
    foreach ($users_result as $user) {
        //getting listing status
        $expire = im_has_member_expired($user->uid,$user->member_key);
        //if listing expired
        if ($expire == true) {
            $site_name = get_option('blogname');
            $email = get_option('admin_email');

            $login_url = site_url("/wp-login.php?action=login");
            $user_name = get_the_author_meta( 'user_login', $user->uid);
            $message .= "--------------------------------------------------------------------------------\r";
            $message .= "Dear $user_name \r";
            $message .= "Your membership has been expired, \r";      
            $message .= "Login On: $login_url \r";
            $message .= "--------------------------------------------------------------------------------\r";
            $message = __($message, IM_SLUG);
            //get listing author email
            $to = get_the_author_meta( 'user_email', $user->uid);
            $subject = 'Membership expiration notice';
            $headers = 'From: Site Admin <' . $email . '>' . "\r\n" . 'Reply-To: ' . $email;
            wp_mail($to, $subject, $message, $headers);
        }
    }
}