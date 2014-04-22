<?php

function im_install() {
    $userid = get_current_user_id();
//Creating all ads page
    $pages = get_option('im_login');
    if (empty($pages)) {
        $my_page = array(
            'ID' => false,
            'post_type' => 'page',
            'post_name' => 'login',
            'ping_status' => 'closed',
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'post_author' => $userid,
            'post_content' => '[login_reg]',
            'post_title' => __('Login', THEME_SLUG),
            'post_excerpt' => ''
        );
        $pages_id = wp_insert_post($my_page);
        if ($pages_id) {
            update_option('im_login', $pages_id);
        }
    }
    //set login page url 
    $login_page = get_option('im_login');
    $login_url = site_url('?page_id=' . $login_page);
    update_option('im_order_sign_page', $login_url);
}

add_action('init', 'im_install');

function im_update_member($user_id, $member_id, $member_key) {
    update_user_meta($user_id, $member_id, $member_key);
    /**
     * call img_set_member_label function to 
     * Set the member_access_label
     */
    img_set_member_label($user_id);
}

function im_the_content_filter($content) {
    global $post;
    $user_id = get_current_user_id();
    $user_key = im_get_user_member_key($user_id);
    $post_key = im_get_post_lavel($post->ID);
    //if (is_user_logged_in() && $post->post_type != 'page') {
    if ($post_key && $user_key)
        $result = array_intersect($user_key, $post_key);
    if (!empty($result)) {
        return $content;
    } elseif (empty($post_key)) {
        $extrn_content = apply_filters('im_pt_content', $content);
        return $extrn_content;
    } else {
        im_content_lock($post_key);
    }
}

add_filter('the_content', 'im_the_content_filter');

function im_content_lock($post_key) {
    print '<img src="' . IM_PLUGIN_PATH . 'images/content-lock.png"/><br/><br/>';
    print '<h3>Get Package below to access the Content.</h3>';
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        global $wpdb, $im_tbl_product;
        if ($post_key) {
            foreach ($post_key as $val) {
                $querys[] = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $im_tbl_product . ' WHERE member_key = %s', $val), ARRAY_A);
                foreach ($querys as $ke) {
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
                        $subscription_price = "<b>Pricing: {$ke['product_price']} {$ke['currency']}</b><br/><br/>";
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

                    $str = "Product Name: {$ke['product_name']}<br/>";
                    $str .= $subscription_price;
                    if ($ke['billing_option'] == 'recurring') {
                        $str .= $billing_terms . $second_billing_terms;
                    }
                    $form = im_paypal_getway_process($order);
                }
                echo '<div id="im_pricing">' . $str . $form . '</div>';
            }
        }
    } else {
        im_autho_form('none', 'im_log_form');
        print '<p>You are not logged in. <a id="im_log_pop" href="' . IM_LOGING_PAGE . '&redirect_to=' . get_permalink() . '">Please Login/Register</a> to purchase and access the content.</p>';
    }
}

function im_get_member_key() {
    global $wpdb, $im_tbl_product;
    $results = $wpdb->get_col("SELECT member_key FROM $im_tbl_product");
    return $results;
}

function im_get_private_key($pid) {
    global $wpdb, $im_tbl_product;
    $sql = $wpdb->prepare("SELECT member_key FROM $im_tbl_product WHERE PID = %d", $pid);
    $private_key = $wpdb->get_col($sql);
    if ($private_key) {
        return $private_key[0];
    }
}

function im_get_products($pid) {
    global $wpdb, $im_tbl_product;
    $sql = $wpdb->prepare("SELECT * FROM $im_tbl_product WHERE PID = %d", $pid);
    $private_key = $wpdb->get_results($sql, ARRAY_A);
    if ($private_key) {
        return $private_key;
    }
}

function im_get_post_lavel($post_id = null) {
    $membership = im_get_poducts();
    $member_lavel = array();
    if ($membership) {
        foreach ($membership as $member) {
            $member_id = IM_MEMBER_ID . $member->PID;
            if (get_post_meta($post_id, $member_id, true))
                $member_lavel[] = get_post_meta($post_id, $member_id, true);
        }
        return $member_lavel;
    }
}

/**
 * Function: img_set_member_label()
 * Description: This function gets user id and set member_access_label
 * key if members keys are set true 
 * 
 * @param type $user_id = membership subscribed user id 
 * @uses $user_id - User id for identify
 * @since 1.0
 */
function img_set_member_label($user_id) {
    $membership = im_get_poducts();
    $member_key = array();
    foreach ($membership as $member) {
        $member_id = IM_MEMBER_ID . $member->PID;
        $data = get_user_meta($user_id, $member_id, true);
        if ($data)
            $member_key[] = $data;
    }
    //delete_user_meta($user_id, 'member_access_label');
    update_user_meta($user_id, 'member_access_label', $member_key);
}

function im_get_user_member_key($user_id) {
    $user_key = get_user_meta($user_id, 'member_access_label', true);
    return $user_key;
}

/**
 * Add setting menu on admin panel 
 */
function im_admin_menu() {
    add_menu_page(__('InkMember', IM_SLUG), __('InkMember', IM_SLUG), 'manage_options', 'inkmember', 'im_setting', plugins_url('inkmember/images/icon.png'));
    add_submenu_page('inkmember', __('Settings', IM_SLUG), __('Settings', IM_SLUG), 'manage_options', 'setting', 'im_setting_page');
    add_submenu_page('inkmember', __('Transaction', IM_SLUG), __('Transaction', IM_SLUG), 'manage_options', 'transation', 'im_transaction');
    add_submenu_page('inkmember', __('Help', IM_SLUG), __('Help', IM_SLUG), 'manage_options', 'help', 'im_help');
}

add_action('admin_menu', 'im_admin_menu');

function im_setting_page() {

    print '<div id="inkmember_wrap">';
    print '<div class="member_head"><div id="icon-options-general" class="icon32">	
	</div>
	<h2>InkMember Settings</h2></div>';
    echo '<p class="warinng"><i>This is trial version. In this version, payment reccuring system would not work.</i></p>';
    if (isset($_POST['submit'])) {
        update_option('im_order_sign_page', wp_kses($_POST['order_sign_page'], array()));
        update_option('im_pricing_page', wp_kses($_POST['default_pricing_page'], array()));
        update_option('im_return_page', wp_kses($_POST['return_member_page'], array()));
        update_option('im_paypal_email', wp_kses($_POST['paypal_email'], array()));
        update_option('im_sabdbox_mode', wp_kses($_POST['sandbox_mode'], array()));
        update_option('im_mailling_list_type', wp_kses($_POST['mailling_list_type'], array()));
        update_option('im_subs_email', wp_kses($_POST['subs_email'], array()));
        update_option('im_debut_ipn', wp_kses($_POST['debug_ipn'], array()));
    }
    ?>
    <div id="member_form">
        <form method="post" id="product_form" action="">
            <table>
                <tbody>
    <!--                    <tr><td class="label"><label for="order_sign_page">Order/Signup Page:</label><a class="tooltip" title="" href="#"></a></td><td>
                            <select id="order_sign_page" name="order_sign_page"> 
                                <option value=""><?php echo esc_attr(__('Select One')); ?></option> 
                    <?php
                    $sign_page = get_option('im_order_sign_page');
                    $pages = get_pages();
                    foreach ($pages as $page) {
                        if ($sign_page == get_page_link($page->ID)) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        $option = '<option ' . $selected . ' value="' . get_page_link($page->ID) . '">';
                        $option .= $page->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                    ?></select>
                        </td>
                    </tr>-->
    <!--                    <tr><td class="label"><label for="default_member_page">Default Pricing Page:</label></td><td>
                            <select id="default_member_page" name="default_pricing_page"> 
                                <option value=""><?php echo esc_attr(__('Select One')); ?></option> 
                    <?php
                    $default_page = get_option('im_pricing_page');
                    $defaultpages = get_pages();
                    foreach ($defaultpages as $page) {
                        if ($default_page == get_page_link($page->ID)) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        $option = '<option ' . $selected . ' value="' . get_page_link($page->ID) . '">';
                        $option .= $page->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                    ?></select>
                        </td>
                    </tr>-->
    <!--                    <tr><td><label for="return_member_page">After Payment Return Page:</label></td><td>
                            <select id="return_member_page" name="return_member_page"> 
                                <option value=""><?php echo esc_attr(__('Select One')); ?></option> 
                    <?php
                    $return_page = get_option('im_return_page');
                    $return_pages = get_pages();
                    foreach ($return_pages as $page) {
                        if ($return_page == get_page_link($page->ID)) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = '';
                        }
                        $option = '<option ' . $selected . ' value="' . get_page_link($page->ID) . '">';
                        $option .= $page->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                    ?></select>
                        </td>
                    </tr>-->
                    <tr><td class="label"><label for="paypal_email">Paypal Email:</label><a class="tooltip" title="Your Paypal Email for receiving payments. Eg: payments@yoursite.com" href="#"></a></td><td><input type="text" name="paypal_email" id="paypal_email" value="<?php echo get_option('im_paypal_email'); ?>"/></td></tr>            
                    <tr><td class="label"><label for="sandbox">Sandbox Mode:</label><a class="tooltip" title="Choose Yes if you are in testing phase through Paypal Sandbox Account. Else Choose No for real Paypal Transactions." href="#"></a></td><td>
                            <select id="sandbox" name="sandbox_mode"> 
                                <?php
                                $sandbox = get_option('im_sabdbox_mode');
                                ?>
                                <option <?php if ($sandbox == false) echo 'selected="selected"'; ?> value="0"><?php echo esc_attr(__('No')); ?></option> 
                                <option <?php if ($sandbox == true) echo 'selected="selected"'; ?>  value="1"><?php echo esc_attr(__('Yes')); ?></option> 
                            </select>
                        </td>
                    </tr>
    <!--                    <tr><td class="label"><label for="sandbox">Debug IPN:</label></td><td>
                            <select id="sandbox" name="debug_ipn"> 
                    <?php
                    $ipn = get_option('im_debut_ipn');
                    ?>
                                <option <?php if ($ipn == false) echo 'selected="selected"'; ?> value="0"><?php echo esc_attr(__('No')); ?></option> 
                                <option <?php if ($ipn == true) echo 'selected="selected"'; ?>  value="1"><?php echo esc_attr(__('Yes')); ?></option> 
                            </select>
                        </td>
                    </tr>-->
    <!--                <tr><td><label for="mailling_list_type">Autoresponder List:</label></td><td>
                            <select id="mailling_list_type" name="mailling_list_type"> 
                                <option value=""><?php echo esc_attr(__('None')); ?></option> 
                                <option <?php if (get_option('im_mailling_list_type') == 'AW') echo 'selected="selected"'; ?> value="AW">AWeber</option>
                            </select><br/>
                            <div class="mail_listen"><label for="subs_email">List Email Address:</label><input type="text" name="subs_email" value="<?php echo get_option('im_subs_email'); ?>" id="subs_email"/></div>
                        </td>
                    </tr>-->
                </tbody>
            </table>
            <input type="submit" name="submit" value="Save &raquo;" class="button-primary" />
        </form>
    </div>
    <?php
    print '</div>';
}

/**
 * Function: im_currency
 * @return type array
 */
function im_currency() {
    $currency = array(
        'USD' => __('U.S. Dollar', IM_SLUG),
        'AUD' => __('Australian Dollar', IM_SLUG),
        'BRL' => __('Brazilian Real', IM_SLUG),
        'CAD' => __('Canadian Dollar', IM_SLUG),
        'CZK' => __('Czech Koruna', IM_SLUG),
        'DKK' => __('Danish Krone', IM_SLUG),
        'EUR' => __('Euro', IM_SLUG),
        'HKD' => __('Hong Kong Dollar', IM_SLUG),
        'HUF' => __('Hungarian Forint', IM_SLUG),
        'ILS' => __('Israeli New Sheqel', IM_SLUG),
        'JPY' => __('Japanese Yen', IM_SLUG),
        'MYR' => __('Malaysian Ringgit', IM_SLUG),
        'MXN' => __('Mexican Peso', IM_SLUG),
        'NOK' => __('Norwegian Krone', IM_SLUG),
        'NZD' => __('New Zealand Dollar', IM_SLUG),
        'PHP' => __('Philippine Peso', IM_SLUG),
        'PLN' => __('Polish Zloty', IM_SLUG),
        'GBP' => __('Pound Sterling', IM_SLUG),
        'SGD' => __('Singapore Dollar', IM_SLUG),
        'SEK' => __('Swedish Krona', IM_SLUG),
        'CHF' => __('Swiss Franc', IM_SLUG),
        'TWD' => __('Taiwan New Dollar', IM_SLUG),
        'THB' => __('Thai Baht', IM_SLUG),
        'TRY' => __('Turkish Lira', IM_SLUG),
    );
    return $currency;
}

function im_get_poducts() {
    global $wpdb, $im_tbl_product;
    $results = $wpdb->get_results("SELECT * FROM $im_tbl_product");
    return $results;
}

function im_get_poducts_key($where) {
    global $wpdb, $im_tbl_product;
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $im_tbl_product WHERE member_key = %s", $where));
    return $results;
}

function im_setting() {

    print '<div id="inkmember_wrap">';
    print '<div class="member_head"><div id="icon-options-general" class="icon32">	
	</div>
	<h2>InkMember</h2></div>';
        echo '<p class="warinng"><i>This is trial version. In this version, payment reccuring system would not work.</i></p>';
    //Delete Product
    if ($_REQUEST['page'] == 'inkmember' && $_REQUEST['action'] == 'delete') {
        $pid = $_REQUEST['pid'];
        global $wpdb, $im_tbl_product;
        $wpdb->query($wpdb->prepare("DELETE FROM $im_tbl_product WHERE PID = %d", $pid));
    }
    //Edit Product
    if ($_REQUEST['page'] == 'inkmember' && $_REQUEST['action'] == 'edit') {
        print '<a title="Back to membership product list" class="member_navi" href="' . admin_url('/admin.php?page=inkmember&action=reset') . '"><img src="' . plugins_url('inkmember/images/back.png') . '"/></a>';
        $pid = $_REQUEST['pid'];
        im_edit_product($pid);
        global $wpdb, $im_tbl_product;
        $results = $wpdb->get_row($wpdb->prepare("SELECT * FROM $im_tbl_product WHERE PID = %d", $pid));
        include_once dirname(__FILE__) . '/edit-product.php';
    }
    if ($_REQUEST['page'] == 'inkmember' && $_REQUEST['action'] == 'add_new') {
        //Add product
        im_add_product();
        print '<a title="Back to membership product list" class="member_navi" href="' . admin_url('/admin.php?page=inkmember&action=reset') . '"><img src="' . plugins_url('inkmember/images/back.png') . '"/></a>';
        include_once dirname(__FILE__) . '/add-product.php';
    }
    //List Product
    if ($_REQUEST['page'] == 'inkmember' && !isset($_REQUEST['action']) || $_REQUEST['action'] == 'reset' || $_REQUEST['action'] == 'delete') {
        print '<a title="Add membership product" class="member_navi" href="' . admin_url('/admin.php?page=inkmember&action=add_new') . '"><img src="' . plugins_url('inkmember/images/add.png') . '"/></a>';
        ?>
        <div id="add_form">
            <table>
                <thead>
                    <tr><th><?php _e('Product Name', IM_SLUG); ?></th><th><?php _e('Billing Type', IM_SLUG); ?></th><th><?php _e('Action', IM_SLUG); ?></th></tr>
                </thead>
                <tbody>
                    <?php
                    $results = im_get_poducts();
                    $str = '';
                    if ($results) {
                        foreach ($results as $result) {
                            if ($result->billing_option == 'one_time')
                                $billing_option = "One Time";
                            elseif ($result->billing_option == "recurring")
                                $billing_option = "Recurring";
                            elseif ($result->billing_option == "free_mem")
                                $billing_option = "Free Member";
                            $str .= '<tr>';
                            $str .= '<td>' . $result->product_name . '</td><td>' . $billing_option . '</td>
                                <td><a class="edit" title="Edit" href="' . admin_url('/admin.php?page=inkmember&action=edit&pid=' . $result->PID) . '"><img src="' . plugins_url('inkmember/images/edit.png') . '"/></a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a onclick="return confirm("Click OK to reset. Any settings will be lost!");" class=delete title="Delete" href="' . admin_url('/admin.php?page=inkmember&action=delete&pid=' . $result->PID) . '"><img src="' . plugins_url('inkmember/images/delete.png') . '"/></a>';
                            $str .= '</td></tr>';
                        }
                        echo $str;
                    }else {
                        echo "<tr><td colspan=\"4\">You don't have any membership. Please create a membership</td></tr>";
                    }
                    ?>
                </tbody>            
            </table>
        </div>
        <?php
    }
    echo '</div>';
}

function im_add_product() {
    if (isset($_POST['add'])) {
        $prevent_redunt = $_POST['prevent_redunt'];
        if (get_option('im_prevent_redunt') != $prevent_redunt) {
            $my_product = array();
            $my_product['product_name'] = wp_kses($_POST['product_name'], array());
            $my_product['billing_option'] = wp_kses($_POST['billing_option'], array());
            $my_product['p_button_img'] = wp_kses($_POST['payment_button'], array());
            $my_product['currency'] = wp_kses($_POST['currency'], array());
            $my_product['product_price'] = wp_kses($_POST['product_price'], array());
            $my_product['payment_period'] = wp_kses($_POST['payment_period'], array());
            $my_product['payment_period_cycle'] = wp_kses($_POST['payment_period_cycle'], array());
            $my_product['no_of_payment'] = wp_kses($_POST['no_of_payment'], array());
            $my_product['trial_select'] = wp_kses($_POST['trial_select'], array());
            $my_product['trial_price'] = wp_kses($_POST['trial_price'], array());
            $my_product['trial_period'] = wp_kses($_POST['trial_period'], array());
            $my_product['trial_period_cycle'] = wp_kses($_POST['trial_period_cycle'], array());
            $my_product['subs_period'] = wp_kses($_POST['subs_period'], array());
            $my_product['subs_period_cycle'] = wp_kses($_POST['subs_period_cycle'], array());
            $my_product['member_key'] = wp_kses($_POST['member_key'], array());
            global $wpdb, $im_tbl_product;
            $wpdb->insert($im_tbl_product, $my_product);
            if (get_option('im_prevent_redunt') == '')
                add_option('im_prevent_redunt', $prevent_redunt);
            else
                update_option('im_prevent_redunt', $prevent_redunt);
        }
    }
}

function im_edit_product($pid) {
    if (isset($_POST['update'])) {
        $my_product = array();
        $my_product['product_name'] = wp_kses($_POST['product_name'], array());
        $my_product['billing_option'] = wp_kses($_POST['billing_option'], array());
        $my_product['p_button_img'] = wp_kses($_POST['payment_button'], array());
        $my_product['currency'] = wp_kses($_POST['currency'], array());
        $my_product['product_price'] = wp_kses($_POST['product_price'], array());
        $my_product['payment_period'] = wp_kses($_POST['payment_period'], array());
        $my_product['payment_period_cycle'] = wp_kses($_POST['payment_period_cycle'], array());
        $my_product['no_of_payment'] = wp_kses($_POST['no_of_payment'], array());
        $my_product['trial_select'] = wp_kses($_POST['trial_select'], array());
        $my_product['trial_price'] = wp_kses($_POST['trial_price'], array());
        $my_product['trial_period'] = wp_kses($_POST['trial_period'], array());
        $my_product['trial_period_cycle'] = wp_kses($_POST['trial_period_cycle'], array());
        $my_product['subs_period'] = wp_kses($_POST['subs_period'], array());
        $my_product['subs_period_cycle'] = wp_kses($_POST['subs_period_cycle'], array());
        global $wpdb, $im_tbl_product;
        $wpdb->update($im_tbl_product, $my_product, array('PID' => $pid));
    }
}

function im_notify_head() {
    $body = <<<EOF
        <!DOCTYPE html>
        <xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="default">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>WordPress &rsaquo; Notification</title>	
        </head>
        <body id="error-page">
EOF;
    echo $body;
    echo '<link rel="stylesheet" href="' . WP_PLUGIN_URL . '/inkmember/css/error.css"/>';
}

function im_notify_footer() {
    if (!is_user_logged_in()) {
        im_login_css();
        im_autho_form();
    }
    echo '<a href="' . site_url() . '">Back to site?</a>';
    echo ' </body></html>';
}

function im_login_css() {
    ?>
    <style type="text/css">
        input[type="submit"]:hover{
            cursor: pointer;
        }
        #im_authform td,
        #im_authform th{
            border: none;
        }
        #im_authform .form_tag{
            border-bottom: 1px solid #cbcbcb;
            padding-bottom: 15px;
            margin-bottom:20px;
        }
        #im_authform label{
            margin-bottom:5px;
            display: block;
        }
        #login_form{
            margin-right: 50px;
        }
        #login_form input[type="text"],
        #login_form input[type="password"],
        #register input[type="text"],
        #register input[type="password"]{
            border: 1px solid #cbcbcb;
            height: 40px;
            width: 250px;
            background-color: #f2f2f2;
            padding-left: 5px;
            margin-bottom: 10px;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
        }
        #login_form .submit,
        #register .submit{
            background: url('<?php echo IM_PLUGIN_PATH; ?>/images/login.png') no-repeat;
            border: none;
            width: 103px;
            height: 38px;
            font-size: 14px;
            color:#fff;
        }
        #login_form{
            float: left;
            margin-bottom: 20px;
        }
    </style>
    <?php
}

function im_payment_period($period, $period_cycle) {
    if ($period_cycle == "D") {
        if ($period > 1)
            return "Days";
        else
            return "Day";
    }
    elseif ($period_cycle == "W") {
        if ($period > 1)
            return "Weeks";
        else
            return "Week";
    }
    elseif ($period_cycle == "M") {
        if ($period > 1)
            return "Months";
        else
            return "Month";
    }
    elseif ($period_cycle == "Y") {
        if ($period > 1)
            return "Years";
        else
            return "Year";
    }
}

// pass strings in to clean
function inkthemes_clean($string) {
    $string = stripslashes($string);
    $string = trim($string);
    return $string;
}

/**
 * Used for set expiry duration of membership 
 * @global type $wpdb
 * @global type $im_tbl_expiry
 * @global type $wpdb
 * @global type $im_tbl_expiry
 * @param type $user_id
 * @param type $where
 * @param type $member_id
 */
function im_set_member_expiry($user_id, $where, $member_id) {

    $members = im_get_poducts_key($where);
    if ($members) {
        foreach ($members as $member) {

            if ($member->billing_option == 'one_time') {
                $subs_period = $member->subs_period;
                $subs_period_cycle = $member->subs_period_cycle;
                //Set expiry period for one_time payment
                if ($subs_period_cycle == "D")
                    $member_length = $subs_period;
                elseif ($subs_period_cycle == "W")
                    $member_length = $subs_period * 7;
                elseif ($subs_period_cycle == "M")
                    $member_length = $subs_period * 30;
                elseif ($subs_period_cycle == "Y")
                    $member_length = $subs_period * 365;
                //check if lifetime
                elseif ($subs_period_cycle == "U")
                //for lifetime membership
                    $member_length = 36500;
                $membership_duration = date_i18n('m/d/Y H:i:s', strtotime('+' . $member_length . ' days'));
                //Add membership duration
                add_user_meta($user_id, 'im_member_duration_' . $member_id, $membership_duration);
                global $wpdb, $im_tbl_expiry;
                add_post_meta($user_id, $member_id, $where);
                $my_expiry['uid'] = $user_id;
                $my_expiry['meta_key'] = "im_membership";
                $my_expiry['member_key'] = $member_id;
                $my_expiry['member_value'] = $where;
                $wpdb->insert($im_tbl_expiry, $my_expiry);
            }
            if ($member->billing_option == 'recurring') {
                $payment_period = $member->payment_period;
                $payment_period_cycle = $member->payment_period_cycle;
                //Values calculation for first period
                if ($payment_period_cycle == "D")
                    $first_billing = $payment_period;
                elseif ($payment_period_cycle == "W")
                    $first_billing = $payment_period * 7;
                elseif ($payment_period_cycle == "M")
                    $first_billing = $payment_period * 30;
                elseif ($payment_period_cycle == "Y")
                    $first_billing = $payment_period * 365;
                //Values calculating for second period
                if ($member->trial_select == true) {
                    $trial_period = $member->trial_period;
                    $trial_period_cycle = $member->trial_period_cycle;
                    if ($trial_period_cycle == "D")
                        $second_billing = $trial_period;
                    elseif ($trial_period_cycle == "W")
                        $second_billing = $trial_period * 7;
                    elseif ($trial_period_cycle == "M")
                        $second_billing = $trial_period * 30;
                    elseif ($trial_period_cycle == "Y")
                        $second_billing = $trial_period * 365;
                }
                $member_length = $first_billing + $second_billing;
                $membership_duration = date_i18n('m/d/Y H:i:s', strtotime('+' . $member_length . ' days'));
                add_user_meta($user_id, 'im_member_duration_' . $member_id, $membership_duration);
                global $wpdb, $im_tbl_expiry;
                add_post_meta($user_id, $member_id, $where);
                $my_expiry['uid'] = $user_id;
                $my_expiry['meta_key'] = "im_membership";
                $my_expiry['member_key'] = $member_id;
                $my_expiry['member_value'] = $where;
                $wpdb->insert($im_tbl_expiry, $my_expiry);
            }
        }
    }
}

/**
 * Used for showing expire time left
 * @param type $theTime
 * @return type
 */
function im_timeleft($theTime) {
    $now = strtotime("now");
    $timeLeft = $theTime - $now;

    $days_label = __('days', IM_SLUG);
    $day_label = __('day', IM_SLUG);
    $hours_label = __('hours', IM_SLUG);
    $hour_label = __('hour', IM_SLUG);
    $mins_label = __('mins', IM_SLUG);
    $min_label = __('min', IM_SLUG);
    $secs_label = __('secs', IM_SLUG);
    $r_label = __('remaining', IM_SLUG);
    $expired_label = __('Membership has expired', IM_SLUG);

    if ($timeLeft > 0) {
        $days = floor($timeLeft / 60 / 60 / 24);
        $hours = $timeLeft / 60 / 60 % 24;
        $mins = $timeLeft / 60 % 60;
        $secs = $timeLeft % 60;

        if ($days == 01) {
            $d_label = $day_label;
        } else {
            $d_label = $days_label;
        }
        if ($hours == 01) {
            $h_label = $hour_label;
        } else {
            $h_label = $hours_label;
        }
        if ($mins == 01) {
            $m_label = $min_label;
        } else {
            $m_label = $mins_label;
        }

        if ($days) {
            $theText = $days . " " . $d_label;
            if ($hours) {
                $theText .= ", " . $hours . " " . $h_label . " left";
            }
        } elseif ($hours) {
            $theText = $hours . " " . $h_label;
            if ($mins) {
                $theText .= ", " . $mins . " " . $m_label . " left";
            }
        } elseif ($mins) {
            $theText = $mins . " " . $m_label;
            if ($secs) {
                $theText .= ", " . $secs . " " . $secs_label . " left";
            }
        } elseif ($secs) {
            $theText = $secs . " " . $secs_label . " left";
        }
    } else {
        $theText = $expired_label;
    }
    return $theText;
}

/**
 * Used to check membsership expired
 * @param type $user_id
 * @param type $member_id
 * @return boolean
 */
function im_has_member_expired($user_id, $member_id) {

    $expire_date = get_user_meta($user_id, 'im_member_duration_' . $member_id, true);

    // debugging variables
    // echo date_i18n('m/d/Y H:i:s') . ' <-- current date/time GMT<br/>';
    // echo $expire_date . ' <-- expires date/time<br/>';
    // if current date is past the expires date, change post status to draft
    if ($expire_date) {
        if (strtotime(date('Y-m-d H:i:s')) > (strtotime($expire_date))) :
            $success = delete_user_meta($user_id, $member_id);
            return $success;
        else:
            return false;
        endif;
    }
}

/**
 * Used to expire or delete the user's membership when 
 * Ipn returns expired, canceled, refunded value.
 * @param type $user_id - user's id
 * @param type $member_id - member id 
 */
function im_member_expire($user_id, $member_id) {
    if ($member_id) {
        delete_user_meta($user_id, $member_id);
        update_user_meta($user_id, 'im_member_duration_' . $member_id, '');
    }
}

/**
 * Used for set default expiry
 * @global type $wpdb
 * @global type $im_tbl_product
 * @param type $post_id
 */
function im_set_default_expiry($post_id) {
    global $wpdb, $im_tbl_product;
    $sql = "SELECT * FROM $im_tbl_product WHERE package_type = 'pkg_free'";
    $QUERY = $wpdb->get_results($sql);
    foreach ($QUERY as $q) {
        $ad_length = $q->validity;
        if ($q->validity_per == 'D') {
            
        } elseif ($q->validity_per == 'M') {
            $ad_length = $ad_length * 30;
        } elseif ($q->validity_per == 'Y') {
            $ad_length = $ad_length * 365;
        }
    }
    if ($ad_length > 0) {
        $admin_ad_duration = date_i18n('m/d/Y H:i:s', strtotime('+' . $ad_length . ' days'));
        add_post_meta($post_id, 'im_listing_duration', $admin_ad_duration, true);
    }
}

/**
 * Used for checking expired membership 
 * @global type $wpdb
 * @global type $im_tbl_expiry
 */
function im_expiry() {
    global $wpdb, $im_tbl_expiry;
    $users_query = "SELECT * FROM $im_tbl_expiry";
    $users_result = $wpdb->get_results($users_query);
    if (!empty($users_result)) {
        foreach ($users_result as $user) {
            //getting members status
            $expire = im_has_member_expired($user->uid, $user->member_key);
            //if member expired
            if ($expire === true) {
                $site_name = get_option('blogname');
                $email = get_option('admin_email');
                $login_url = site_url("/wp-login.php?action=login");
                $user_name = get_the_author_meta('user_login', $user->uid);
                $message .= "--------------------------------------------------------------------------------\r";
                $message .= "Dear $user_name \r";
                $message .= "Your membership has been expired, \r";
                $message .= "Login On: $login_url \r";
                $message .= "--------------------------------------------------------------------------------\r";
                $message = __($message, IM_SLUG);
                //get member author email
                $to = get_the_author_meta('user_email', $user->uid);
                $subject = 'Membership expiration notice';
                $headers = 'From: Site Admin <' . $email . '>' . "\r\n" . 'Reply-To: ' . $email;
                wp_mail($to, $subject, $message, $headers);
            }
        }
    }
}

add_action('init', 'im_expiry');

function im_transaction() {
    if (isset($_REQUEST['id']) && $_REQUEST['page'] = 'transation') {
        $id = $_REQUEST['id'];
        im_delete_trans($id);
    }
    print '<div id="inkmember_wrap" style="width:950px;">';
    print '<div class="member_head"><div id="icon-options-general" class="icon32">	
	</div>
	<h2>InkMember Transaction</h2></div>';
    ?>
    <div id="add_form">
        <table>
            <thead>
                <tr><th><?php _e('User Name', IM_SLUG); ?></th><th><?php _e('Transaction Title', IM_SLUG); ?></th><th><?php _e('Transaction ID', IM_SLUG); ?></th><th><?php _e('Transaction Type', IM_SLUG); ?></th><th><?php _e('Payment Date', IM_SLUG); ?></th><th><?php _e('Payer Email', IM_SLUG); ?></th><th><?php _e('Action', IM_SLUG); ?></th></tr>
            </thead>
            <tbody>
                <?php
                global $im_transaction, $wpdb;
                $query = "SELECT * FROM $im_transaction";
                $values = $wpdb->get_results($query);
                if ($values) {
                    foreach ($values as $value) {
                        $user_login = get_the_author_meta('user_login', $value->uid);
                        if ($value->txn_type == 'web_accept')
                            $txn_type = 'One Time';
                        elseif ($value->txn_type == 'subscr_pay')
                            $txn_type = 'Recurring';
                        echo '<tr><td>' . $user_login . '</td><td>' . $value->transaction_subject . '</td><td>' . $value->txn_id . '</td><td>' . $txn_type . '</td><td>' . $value->payment_date . '</td><td>' . $value->payer_email . '</td><td><a href="' . admin_url('/admin.php?page=transation&id=' . $value->id) . '"><img src="' . plugins_url('inkmember/images/delete.png') . '"/></a></td></tr>';
                    }
                }else {
                    echo "<tr><td colspan='7'>You don't have any transaction yet</td></tr>";
                }
                ?>
            </tbody>            
        </table>
    </div>
    <?php
    echo '</div>';
}

function im_delete_trans($id) {
    global $wpdb, $im_transaction;
    $wpdb->query($wpdb->prepare('DELETE FROM ' . $im_transaction . ' WHERE id = %d', $id));
}

function im_help() {
    ?>
    <div id="helper">
        <br/>
        <br/>
        <h1>InkMember Shortcodes Reminder</h1>
        <p>InkMember plugin has following shortcodes to use.</p>
        <hr/>
        <ol>
            <li><b>[login_reg]</b> - This shortcode is used for creating login register page.</li>
            <li><b>[im_pricing]</b> - This shortcode is used for creating pricing page. You can also place this shortcode in anywhere of video content area. Also can be assigned and seperate the pricing by assigning membership level id. Use method is: <b>[im_pricing id= membership level id]</b> e.g. <b>[im_pricing id=1]</b>. By default it takes first membership level.</li>
            <li><b>[private_content]</b> - This shortcode is used for protecting selected contents. Use method is: <b>[private_content] your content [/private_content]</b>. You can even assign it different membership level id same as pricing. e.g. <b>[private_content id=membership level id] your contents [/private_content]</b></li>
        </ol>
    </div>
    <?php
}

