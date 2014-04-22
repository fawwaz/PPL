<?php

function im_paypal_getway_process($order_val){
    //Is this a test transaction? 
    if ($order_val['billing_type'] !== 'free_mem') {
        if (get_option('im_sabdbox_mode') == true)
            $paypalurl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        else
            $paypalurl = 'https://www.paypal.com/cgi-bin/webscr';
        //Return page
        $return_url = get_option('im_return_page');
        //Paypal email
        $merchant_id = get_option('im_paypal_email');
        $return_url = site_url();
        //Paypal notification url for ipn
        $notify_url = "$return_url?notify=notify&amp;product_key={$order_val['product_key']}&amp;member_key={$order_val['member_key']}&amp;uid={$order_val['user_id']}";
        //Paypal cancelation url when user cancel the paypal payment
        $cancel_url = "$return_url?notify=cancel&amp;product_key={$order_val['product_key']}&amp;member_key={$order_val['member_key']}&amp;uid={$order_val['user_id']}";
        //Paypal 
        $return_url = "$return_url?notify=return&amp;product_key={$order_val['product_key']}&amp;member_key={$order_val['member_key']}&amp;uid={$order_val['user_id']}";
        $form = '';

        $form .= '<form name="frm_payment_method" action="' . $paypalurl . '" method="post">';
        $form .= '<input type="hidden" name="business" value="' . $merchant_id . '" />';
        // Instant Payment Notification & Return Page Details /
        $form .= '<input type="hidden" name="notify_url" value="' . $notify_url . '" />';
        $form .= '<input type="hidden" name="cancel_return" value="' . $cancel_url . '" />';
        $form .= '<input type="hidden" name="return" value="' . $return_url . '" />';
        $form .= '<input type="hidden" name="rm" value="2" />';
        // Configures Basic Checkout Fields -->
        $form .= '<input type="hidden" name="lc" value="" />';
        $form .= '<input type="hidden" name="no_shipping" value="1" />';
        $form .= '<input type="hidden" name="no_note" value="1" />';
        // <input type="hidden" name="custom" value="localhost" />-->
        $form .= '<input type="hidden" name="currency_code" value="' . $order_val['currency_code'] . '" />';
        $form .= '<input type="hidden" name="page_style" value="paypal" />';
        $form .= '<input type="hidden" name="charset" value="utf-8" />';
        $form .= '<input type="hidden" name="item_name" value="' . $order_val['item_name'] . '" />';
        if ($order_val['billing_type'] == 'recurring') {
            // <!-- <input type="hidden" name="amount" value="<?php echo $paypalamount; 
            // <input type="hidden" name="item_number" value="2" />
            $form .= '<input type="hidden" name="cmd" value="_xclick-subscriptions" />';
            //<!-- Customizes Prices, Payments & Billing Cycle -->
            $form .= '<input type="hidden" name="src" value="1" />';
            // <!-- Value for each installments -->
            $form .= '<input type="hidden" name="srt" value="' . $order_val['installment'] . '" />';
            //<!-- <input type="hidden" name="sra" value="5" />-->
            //<!-- First Price -->
            $form .= '<input type="hidden" name="a1" value="' . $order_val['amount'] . '" />';
            //<!-- First Period -->
            $form .= '<input type="hidden" name="p1" value="' . $order_val['f_period'] . '" />';
            //<!-- First Period Cycle e.g: Days,Months-->
            $form .= '<input type="hidden" name="t1" value="' . $order_val['f_cycle'] . '" />';
      
                //<!-- Second Period Price-->
                $form .= '<input type="hidden" name="a3" value="' . $order_val['s_price'] . '" />';
                //<!-- Second Period -->
                $form .= '<input type="hidden" name="p3" value="' . $order_val['s_period'] . '" />';
                //<!-- Second Period Cycle -->
                $form .= '<input type="hidden" name="t3" value="' . $order_val['s_cycle'] . '" />';

            //<!-- Displays The PayPal® Image Button -->
        } else {
            $form .= '<input type="hidden" value="_xclick" name="cmd"/>';
            $form .= '<input type="hidden" name="amount" value="' . $order_val['amount'] . '" />';
        }
        if(empty($order_val['p_button']))
            $form .= '<input type="image" src="' . IM_PLUGIN_PATH . 'images/pp-order-button.png" name="image"/>';
            else
        $form .= '<input type="image" src="' . $order_val['p_button'] . '" name="image"/>';
        $form .= '</form>';
    }
    return $form;
}

