<?php

function im_process_paypal_transaction($request) {
    global $wpdb,$im_transaction;

        // since paypal sends over the date as a string, we need to convert it
        // into a mysql date format. There will be a time difference due to PayPal's
        // US pacific time zone and your server time zone
        $payment_date = strtotime($request['payment_date']);
        $payment_date = strftime('%Y-%m-%d %H:%M:%S', $payment_date);


        //setup some values that are not always sent
        if (isset($request['uid']))
            $uid = $request['uid'];
        else
            $uid = '';

        if (isset($request['reason_code']))
            $reason_code = $request['reason_code'];
        else
            $reason_code = '';

        // check and make sure this transaction hasn't already been added
        $results = $wpdb->get_var($wpdb->prepare("SELECT txn_id FROM $im_transaction WHERE txn_id = %s LIMIT 1", inkthemes_clean($request['txn_id'])));

        if (!$results) :

            // @todo Change to Insert
        $order_val = array();
        $order_val['uid'] = inkthemes_clean($uid);
        $order_val['first_name'] = inkthemes_clean($request['first_name']);
        $order_val['last_name'] = inkthemes_clean($request['last_name']);
        $order_val['payer_email'] = inkthemes_clean($request['payer_email']);
        $order_val['residence_country'] = inkthemes_clean($request['residence_country']);
        $order_val['transaction_subject'] = inkthemes_clean($request['transaction_subject']);
        $order_val['item_name'] = inkthemes_clean($request['item_name']);
        $order_val['item_number'] = inkthemes_clean($request['item_number']);
        $order_val['payment_type'] = inkthemes_clean($request['payment_type']);
        $order_val['payer_status'] = inkthemes_clean($request['payer_status']);
        $order_val['payer_id'] = inkthemes_clean($request['payer_id']);
        $order_val['receiver_id'] = inkthemes_clean($request['receiver_id']);
        $order_val['parent_txn_id'] = inkthemes_clean($request['parent_txn_id']);
        $order_val['txn_id'] = inkthemes_clean($request['txn_id']);
        $order_val['mc_gross'] = inkthemes_clean($request['mc_gross']);
        $order_val['mc_fee'] = inkthemes_clean($request['mc_fee']);
        $order_val['payment_status'] = inkthemes_clean($request['payment_status']);
        $order_val['pending_reason'] = inkthemes_clean($request['pending_reason']);
        $order_val['txn_type'] = inkthemes_clean($request['txn_type']);
        $order_val['tax'] = inkthemes_clean($request['tax']);
        $order_val['mc_currency'] = inkthemes_clean($request['mc_currency']);
        $order_val['reason_code'] = inkthemes_clean($reason_code);
        $order_val['custom'] = inkthemes_clean($request['custom']);
        $order_val['test_ipn'] = inkthemes_clean($request['test_ipn']);
        $order_val['payment_date'] = $payment_date;
        $order_val['create_date'] = current_time('mysql');
        $wpdb->insert($im_transaction,$order_val);
        // ad transaction already exists so it must be an update via PayPal IPN (refund, etc)
        // @todo send through prepare
        else:

            $update = "UPDATE " . $im_transaction.
                    " payment_status = '" . $wpdb->escape(inkthemes_clean($request['payment_status'])) . "'," .
                    " mc_gross = '" . $wpdb->escape(inkthemes_clean($request['mc_gross'])) . "'," .
                    " txn_type = '" . $wpdb->escape(inkthemes_clean($request['txn_type'])) . "'," .
                    " reason_code = '" . $wpdb->escape(inkthemes_clean($reason_code)) . "'," .
                    " mc_currency = '" . $wpdb->escape(inkthemes_clean($request['mc_currency'])) . "'," .
                    " test_ipn = '" . $wpdb->escape(inkthemes_clean($request['test_ipn'])) . "'," .
                    " create_date = '" . $wpdb->escape($payment_date) . "'" .
                    " WHERE txn_id ='" . $wpdb->escape($request['txn_id']) . "'";

            //Updating transaction that was already found
            $results = $wpdb->query($update);

        endif;         
        if($request['payment_status'] == 'Completed'){
            $member_key = $request['member_key'];
            $member_id = $request['product_key'];
            //An updation for make valid member
            im_update_member($uid,$member_id,$member_key);
            //Set membership expiry
            im_set_member_expiry($uid,$member_key,$member_id);
        }    
}
add_action('im_process_transaction_entry', 'im_process_paypal_transaction');
