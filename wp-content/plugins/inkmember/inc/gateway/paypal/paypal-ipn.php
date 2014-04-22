<?php

// this is the paypal ipn listener which waits for the request
function inkthemes_ipn_listener() {

    // validate the paypal request by sending it back to paypal
    function inkthemes_ipn_request_check() {

        define('SSL_P_URL', 'https://www.paypal.com/cgi-bin/webscr');
        define('SSL_SAND_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');

        $hostname = gethostbyaddr($_SERVER ['REMOTE_ADDR']);
        if (!preg_match('/paypal\.com$/', $hostname)) {
            $ipn_status = 'Validation post isn\'t from PayPal';
            if (get_option('im_debut_ipn') == true) {
                wp_mail(get_option('admin_email'), $ipn_status, 'fail');
            }
            return false;
        }

        // parse the paypal URL
        $paypal_url = ($_REQUEST['test_ipn'] == 1) ? SSL_SAND_URL : SSL_P_URL;
        $url_parsed = parse_url($paypal_url);


        $post_string = '';
        foreach ($_REQUEST as $field => $value) {
            $post_string .= $field . '=' . urlencode(stripslashes($value)) . '&';
        }
        $post_string.="cmd=_notify-validate"; // append ipn command
        // get the correct paypal url to post request to
        $paypal_mode_status = get_option('im_sabdbox_mode');
        
        if ($paypal_mode_status == true)
            $fp = fsockopen('ssl://www.sandbox.paypal.com', "443", $err_num, $err_str, 60);
        else
            $fp = fsockopen('ssl://www.paypal.com', "443", $err_num, $err_str, 60);


        $ipn_response = '';


        if (!$fp) {
            // could not open the connection.  If loggin is on, the error message
            // will be in the log.
            $ipn_status = "fsockopen error no. $err_num: $err_str";
            if (get_option('im_debut_ipn') == true) {
                wp_mail(get_option('admin_email'), $ipn_status, 'fail');
            }
            return false;
        } else {
            // Post the data back to paypal
            fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n");
            fputs($fp, "Host: $url_parsed[host]\r\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: " . strlen($post_string) . "\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $post_string . "\r\n\r\n");

            // loop through the response from the server and append to variable
            while (!feof($fp)) {
                $ipn_response .= fgets($fp, 1024);
            }
            fclose($fp); // close connection
        }

        // Invalid IPN transaction.  Check the $ipn_status and log for details.
        if (!preg_match("/VERIFIED/s", $ipn_response)) {
            $ipn_status = 'IPN Validation Failed';
            if (get_option('im_debut_ipn') == true) {
                wp_mail(get_option('admin_email'), $ipn_status, 'fail');
            }
            return false;
        } else {
            $ipn_status = "IPN VERIFIED";
            if (get_option('im_debut_ipn') == true) {
                wp_mail(get_option('admin_email'), $ipn_status, 'SUCCESS');
            }
            return true;
        }
    }

    // if the test variable is set (sandbox mode), send a debug email with all values
    if (isset($_REQUEST['test_ipn'])) {
        $_REQUEST = stripslashes_deep($_REQUEST);
        if (get_option('im_debut_ipn') == true) {
            wp_mail(get_option('admin_email'), 'PayPal IPN Debug Email Test IPN', "" . print_r($_REQUEST, true));
        }
    }

    // make sure the request came from (pid) or paypal (txn_id refund, update)
    if (isset($_REQUEST['txn_id']) || isset($_REQUEST['invoice'])) {
        $_REQUEST = stripslashes_deep($_REQUEST);

        // if paypal sends a response code back let's handle it
        if (inkthemes_ipn_request_check()) {

            // send debug email to see paypal ipn post vars
            if (get_option('im_debut_ipn') == true) {
                wp_mail(get_option('admin_email'), 'PayPal IPN Debug Email Main', "" . print_r($_REQUEST, true));
            }
            // process the membership since paypal gave us a valid response
            inkthemes_handle_ipn_response($_REQUEST);            
        }
    }
}

add_action('init', 'inkthemes_ipn_listener');

function inkthemes_handle_ipn_response($request) {
    global $wpdb;

    //step functions required to process orders
    // make sure the membership unique trans id (stored in invoice var) is included
    if (!empty($request['txn_id'])) {

        // process the membership based on the paypal response
        switch (strtolower($request['payment_status'])) :

            // payment was made so we can approve the membership
            case 'completed' :
                
                do_action('im_process_transaction_entry', $_REQUEST);
                
                //admin email confirmation
                //TODO - move into wordpress options panel and allow customization
                //wp_mail(get_option('admin_email'), 'Payment Receive', "A membership payment has been completed. Check to make sure this is a valid order by comparing this messages Paypal Transaction ID to the respective ID in the Paypal payment receipt email.");

                //Mail details to admin email
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
                $mailto = get_option('admin_email');
                $subject = __("$blogname Payment Received.", THEME_SLUG);
                $headers = 'From: ' . __('Admin', THEME_SLUG) . ' <' . get_option('admin_email') . '>' . "\r\n";
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
                $message = __('Dear Admin,', THEME_SLUG) . "\r\n\r\n";
                $message .= sprintf(__('Payment received successfully from %s %s.', THEME_SLUG), $request['first_name'],$request['last_name']) . "\r\n\r\n";
                $message .= __('Transaction Details', THEME_SLUG) . "\r\n";
                $message .= __('-------------------------') . "\r\n";
                $message .= __('Product Name: ', THEME_SLUG) . $request['item_name'] . "\r\n";
                $message .= __('Amount Received: ', THEME_SLUG) . $request['mc_gross'] . "(" . $request['mc_currency'] . ")\r\n\r\n";
                $message .= __('PayPal Email: ', THEME_SLUG) . $request['payer_email'] . "\r\n";
                $message .= __('Transaction ID: ', THEME_SLUG) . $request['txn_id'] . "\r\n";
                $message .= __('Payment type: ', THEME_SLUG) . $request['payment_type'] . "\r\n\n\n";
                $message .= __('Warm Regards,',THEME_SLUG) . "\r\n";
                $message .= __($blogname,THEME_SLUG) . "\r\n";
                $message .= __(site_url(),THEME_SLUG) . "\r\n\n\n";
                $message .= __('Full Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------',THEME_SLUG) . "\r\n";
                $message .= print_r($request, true) . "\r\n";
                //admin email
                wp_mail($mailto, $subject, $message, $headers);
                //Users email
                $subject = __("$blogname Thanks for your purchase.", THEME_SLUG);
                $headers = 'From: ' . __('Admin', THEME_SLUG) . ' <' . get_option('admin_email') . '>' . "\r\n";
                $content .= __("Hello {$request['first_name']}", THEME_SLUG) . "\r\n\n";
                $content .= sprintf(__('Your purchase of %s is successful.', THEME_SLUG), $request['item_name']) . "\r\n\r\n";
                $content .= __('Transaction Details', THEME_SLUG) . "\r\n";
                $content .= __('--------------------') . "\r\n";
                $content .= __('Product Name: ', THEME_SLUG) . $request['item_name'] . "\r\n";
                $content .= __('Amount Received: ', THEME_SLUG) . $request['mc_gross'] . "(" . $request['mc_currency'] . ")\r\n";
                $content .= __('Transaction ID: ', THEME_SLUG) . $request['txn_id'] . "\r\n\n";
                $content .= __('Warm Regards,',THEME_SLUG) . "\r\n";
                $content .= __($blogname,THEME_SLUG) . "\r\n";
                $content .= __(site_url(),THEME_SLUG) . "\r\n\n\n";
                $user_email = get_the_author_meta('user_email', $request['uid']);
                $headers = 'From: ' . 'Admin' . ' <' . $user_email . '>' . "\r\n" . 'Reply-To: ' . get_option('admin_email');
                wp_mail($user_email, $subject, $content, $headers); //email to client

                break;

            case 'pending' :
                
                // send an email if payment is pending
                $mailto = get_option('admin_email');
                $subject = __('PayPal IPN - payment pending', THEME_SLUG);
                $headers = 'From: ' . __('Admin', THEME_SLUG) . ' <' . get_option('admin_email') . '>' . "\r\n";
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

                $message = __('Dear Admin,', THEME_SLUG) . "\r\n\r\n";
                $message .= sprintf(__('The following payment is pending on your %s website.', THEME_SLUG), $blogname) . "\r\n\r\n";
                $message .= __('Payment Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= __('Payer PayPal address: ', THEME_SLUG) . $request['payer_email'] . "\r\n";
                $message .= __('Transaction ID: ', THEME_SLUG) . $request['txn_id'] . "\r\n";
                $message .= __('Payer first name: ', THEME_SLUG) . $request['first_name'] . "\r\n";
                $message .= __('Payer last name: ', THEME_SLUG) . $request['last_name'] . "\r\n";
                $message .= __('Payment type: ', THEME_SLUG) . $request['payment_type'] . "\r\n";
                $message .= __('Amount: ', THEME_SLUG) . $request['mc_gross'] . " (" . $request['mc_currency'] . ")\r\n\r\n";
                $message .= __('Full Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= print_r($request, true) . "\r\n";

                wp_mail($mailto, $subject, $message, $headers);

                break;

            // payment failed so don't approve the memmership
            case 'denied' :
            case 'expired' :
            case 'failed' :
            case 'voided' :
             //Set expire membership
                im_member_expire($request['uid'], $request['product_key']);
                // send an email if payment didn't work
                $mailto = get_option('admin_email');
                $subject = __('PayPal IPN - payment failed', THEME_SLUG);
                $headers = 'From: ' . __('Admin', THEME_SLUG) . ' <' . get_option('admin_email') . '>' . "\r\n";
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

                $message = __('Dear Admin,', THEME_SLUG) . "\r\n\r\n";
                $message .= sprintf(__('The following payment has failed on your %s website.', THEME_SLUG), $blogname) . "\r\n\r\n";
                $message .= __('Payment Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= __('Payer PayPal address: ', THEME_SLUG) . $request['payer_email'] . "\r\n";
                $message .= __('Transaction ID: ', THEME_SLUG) . $request['txn_id'] . "\r\n";
                $message .= __('Payer first name: ', THEME_SLUG) . $request['first_name'] . "\r\n";
                $message .= __('Payer last name: ', THEME_SLUG) . $request['last_name'] . "\r\n";
                $message .= __('Payment type: ', THEME_SLUG) . $request['payment_type'] . "\r\n";
                $message .= __('Amount: ', THEME_SLUG) . $request['mc_gross'] . " (" . $request['mc_currency'] . ")\r\n\r\n";
                $message .= __('Full Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= print_r($request, true) . "\r\n";

                wp_mail($mailto, $subject, $message, $headers);

                break;

            case 'refunded' :
            case 'reversed' :
            case 'chargeback' :
                //Set expire membership
                im_member_expire($request['uid'], $request['product_key']);
                // send an email if payment was refunded
                $mailto = get_option('admin_email');
                $subject = __('PayPal IPN - payment refunded/reversed', THEME_SLUG);
                $headers = 'From: ' . __('Admin', THEME_SLUG) . ' <' . get_option('admin_email') . '>' . "\r\n";
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

                $message = __('Dear Admin,', THEME_SLUG) . "\r\n\r\n";
                $message .= sprintf(__('The following payment has been marked as refunded on your %s website.', THEME_SLUG), $blogname) . "\r\n\r\n";
                $message .= __('Payment Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= __('Payer PayPal address: ', THEME_SLUG) . $request['payer_email'] . "\r\n";
                $message .= __('Transaction ID: ', THEME_SLUG) . $request['txn_id'] . "\r\n";
                $message .= __('Payer first name: ', THEME_SLUG) . $request['first_name'] . "\r\n";
                $message .= __('Payer last name: ', THEME_SLUG) . $request['last_name'] . "\r\n";
                $message .= __('Payment type: ', THEME_SLUG) . $request['payment_type'] . "\r\n";
                $message .= __('Reason code: ', THEME_SLUG) . $request['reason_code'] . "\r\n";
                $message .= __('Amount: ', THEME_SLUG) . $request['mc_gross'] . " (" . $request['mc_currency'] . ")\r\n\r\n";
                $message .= __('Full Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= print_r($request, true) . "\r\n";

                wp_mail($mailto, $subject, $message, $headers);

                break;
        endswitch;
    }
}

add_action('inkthemes_init_ipn_response', 'inkthemes_handle_ipn_response');
?>