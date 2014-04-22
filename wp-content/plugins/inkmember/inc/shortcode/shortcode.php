<?php

//Shortcode for private content. Private content may protected by also multiple ids
add_shortcode('private_content', 'im_shortcode_button');

function im_shortcode_button($atts, $content = null) {
    //extracts our attrs . if not set set default
    extract(shortcode_atts(array('id' => '1'), $atts));

    $user_id = get_current_user_id();
    $user_key = im_get_user_member_key($user_id);
    $private_keys = explode(',', $id);
    $keys = array();
    foreach ($private_keys as $key) {
        $keys[] = im_get_private_key($key);
    }
    if (is_user_logged_in()) {
        if ($keys && $user_key)
            $result = array_intersect($user_key, $keys);
        if (!empty($result)) {
            return $content;
        } else {
            $str = '<img src="' . IM_PLUGIN_PATH . 'images/content-lock.png"/><br/><br/>';
            $str .= '<h3>Get Package below to access the Content.</h3>';
            return $str;
        }
    } else {
        $str = '<img src="' . IM_PLUGIN_PATH . 'images/content-lock.png"/><br/><br/>';
        $str .= '<h3>Get Package below to access the Content.</h3>';
        $str .= 'You are not logged in. <a id="im_log_pop" href="' . IM_LOGING_PAGE . '">Please Login/Register</a> to purchase and access the content.';
        return $str;
    }
}

//Login / register form shortcode
add_shortcode('login_reg', 'im_autho_form');

//Shortcode for pricing list, It may listing multiple pricing list 
add_shortcode('im_pricing', 'im_pricing_shortcode');

function im_pricing_shortcode($atts, $content = null) {
    //extracts our attrs . if not set set default
    extract(shortcode_atts(array('id' => '1'), $atts));
    $ids = explode(',', $id);
    $keys = array();
    if (!empty($ids)) {
        foreach ($ids as $id) {
            $keys[] = im_get_products($id);
        }
    }
    if (is_user_logged_in()) {
        if ($keys) {
            $user_id = get_current_user_id();
            $order = array();
            foreach ($keys as $key) {
                if ($key) {
                    foreach ($key as $ke) {
                        $order['item_name'] = $ke['product_name'];
                        $order['currency_code'] = $ke['currency'];
                        $order['billing_type'] = $ke['billing_option'];
                        $order['p_button'] = $ke['p_button_img'];
                        $order['amount'] = $ke['product_price'];
                        $order['installment'] = $ke['no_of_payment'];
                        $order['f_period'] = $ke['payment_period'];
                        $order['f_cycle'] = $ke['payment_period_cycle'];
                        $order['trial_select'] = $ke['trial_select'];
                        $order['s_price'] = $ke['trial_price'];
                        $order['s_period'] = $ke['trial_period'];
                        $order['s_cycle'] = $ke['trial_period_cycle'];
                        $order['subs_period'] = $ke['subs_period'];
                        $order['subs_period_cycle'] = $ke['subs_period_cycle'];
                        $order['j_button_img'] = $ke['j_button_img'];
                        $order['member_key'] = $ke['member_key'];
                        $order['user_id'] = $user_id;
                        $order['product_key'] = IM_MEMBER_ID . $ke['PID'];
                        if ($ke['billing_option'] == 'one_time') {
                            $subscription_price = "Your subscription price is: {$ke['product_price']} <br/>";
                        } else {
                            $subscription_price = '';
                        }
                        if ($ke['billing_option'] == 'recurring') {
                            $period = im_payment_period($ke['payment_period'], $ke['payment_period_cycle']);
                            $second_period = im_payment_period($ke['trial_period'], $ke['trial_period_cycle']);
                            if ($ke['billing_option'] == 'recurring') {
                                $billing_terms = "Billing Terms: {$ke['product_price']}&nbsp;{$ke['currency']} for {$ke['payment_period']}&nbsp;$period";
                            } else {
                                $billing_terms = '';
                            }
                            if ($ke['no_of_payment'] > 0) {
                                $installment = ", for {$ke['no_of_payment']} Installments";
                            } else {
                                $installment = '';
                            }
                            $second_billing_terms = ",Then&nbsp;{$ke['trial_price']}&nbsp;{$ke['currency']} for each {$ke['trial_period']}&nbsp;$second_period $installment";
                        }

                        $str = "Your subscription Name is: {$ke['product_name']} <br/>";
                        $str .= $subscription_price;
                        if ($ke['billing_option'] == 'recurring') {
                            $str .= $billing_terms . $second_billing_terms;
                        }

                        $form = im_paypal_getway_process($order);
                        return '<div id="im_pricing">' . $str . $form . '</div>';
                    }
                }
            }
        }
    } else {
        $login_url = IM_LOGING_PAGE;
        return sprintf('<a class="nofity" href="%s">' . __("Please login or signup to see pricing", IM_SLUG) . '</a>', $login_url);
    }
}