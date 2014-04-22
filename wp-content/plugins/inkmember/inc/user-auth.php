<?php
global $pagenow;
// check to prevent php "notice: undefined index" msg
if (isset($_GET['action']))
    $theaction = $_GET['action']; else
    $theaction = '';

// if the user is on the login page, then let the site begin
if ($pagenow == 'wp-login.php' && $theaction != 'logout' && !isset($_GET['key'])) :

add_action('init', 'im_login_init', 98);
endif;

function im_login_init() {
    nocache_headers(); //cache clear

    if (isset($_REQUEST['action'])) :
        $action = $_REQUEST['action'];
    else :
        $action = 'login';
    endif;
    switch ($action) :
        case 'lostpassword' :
        case 'retrievepassword' :
            im_show_password();
            break;
        case 'register':
        default:
            im_show_login();
            break;
    endswitch;
    exit;
}

function im_autho_form($display = null, $class = null) {
    if (is_user_logged_in()) {
        wp_redirect(site_url());
    }
    echo <<<EOF
    <div class="$class" style="display:$display;" id="im_authform">
        <a id="close_x" class="close sprited" href="#"></a>        
EOF;
    im_loginform();
    echo '<table><tr><td>';
    im_reg_form();
    echo '</td></tr></table>';
    echo <<<EOF

EOF;
    echo '</div>';
}

function im_login_proceed_form() {

    global $posted;
    if (isset($_REQUEST['redirect_to']))
        $redirect_to = $_REQUEST['redirect_to'];
    else
        $redirect_to = admin_url();
    if (is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ))
        $secure_cookie = false;
    else
        $secure_cookie = '';

    $user = wp_signon('', $secure_cookie);
    $redirect_to = apply_filters('login_redirect', $redirect_to, isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '', $user);
    if (!is_wp_error($user)) {
        if (user_can($user, 'manage_options')) :
            $redirect_to = admin_url();
        endif;
        wp_safe_redirect($redirect_to);
        exit;
    }
    $errors = $user;
    return $errors;
}

function im_reg_proceed_form($success_redirect = '') {

    if (!$success_redirect)
        $success_redirect = site_url();
    $multi_site = WP_ALLOW_MULTISITE;
    if (get_option('users_can_register') || $multi_site == true) :

        global $posted;

        $posted = array();
        $errors = new WP_Error();

        if (isset($_POST['register']) && $_POST['register']) {

            require_once( ABSPATH . WPINC . '/registration.php');

            // Get (and clean) data
            $fields = array(
                'your_username',
                'your_email',
                'your_password',
                'your_password_2'
            );
            foreach ($fields as $field) {
                $posted[$field] = stripslashes(trim($_POST[$field]));
            }

            $user_login = sanitize_user($posted['your_username']);
            $user_email = apply_filters('user_registration_email', $posted['your_email']);

            // Check the username
            if ($posted['your_username'] == '')
                $errors->add('empty_username', __('<strong>ERROR</strong>: ' . "Please enter a username.", IM_SLUG));
            elseif (!validate_username($posted['your_username'])) {
                $errors->add('invalid_username', __('<strong>ERROR</strong>: ' . "This username is invalid.  Please enter a valid username.", IM_SLUG));
                $posted['your_username'] = '';
            } elseif (username_exists($posted['your_username']))
                $errors->add('username_exists', __('<strong>ERROR</strong>: ' . "This username is already registered, please choose another one.", IM_SLUG));

            // Check the e-mail address
            if ($posted['your_email'] == '') {
                $errors->add('empty_email', __('<strong>ERROR</strong>: ' . "Please type your e-mail address.", IM_SLUG));
            } elseif (!is_email($posted['your_email'])) {
                $errors->add('invalid_email', __('<strong>ERROR</strong>: ' . "The email address isn&#8217;t correct.", IM_SLUG));
                $posted['your_email'] = '';
            } elseif (email_exists($posted['your_email']))
                $errors->add('email_exists', __('<strong>ERROR</strong>: ' . "This email is already registered, please choose another one.", IM_SLUG));

            // Check Passwords match
            if ($posted['your_password'] == '')
                $errors->add('empty_password', __('<strong>ERROR</strong>: ' . "Please enter a password.", IM_SLUG));
            elseif ($posted['your_password_2'] == '')
                $errors->add('empty_password', __('<strong>ERROR</strong>: ' . "Please enter password twice.", IM_SLUG));
            elseif ($posted['your_password'] !== $posted['your_password_2'])
                $errors->add('wrong_password', __('<strong>ERROR</strong>: ' . "Passwords do not match.", IM_SLUG));

            do_action('register_post', $posted['your_username'], $posted['your_email'], $errors);
            $errors = apply_filters('registration_errors', $errors, $posted['your_username'], $posted['your_email']);

            if (!$errors->get_error_code()) {
                $user_pass = $posted['your_password'];
                $user_id = wp_create_user($posted['your_username'], $user_pass, $posted['your_email']);
                if (!$user_id) {
                    $errors->add('registerfail', sprintf(__('<strong>ERROR</strong>: ' . "Couldn&#8217;t register you... please contact the webmaster" . '</a> !', IM_SLUG), get_option('admin_email')));
                    return array('errors' => $errors, 'posted' => $posted);
                }

                // Change role
                wp_update_user(array('ID' => $user_id, 'role' => 'contributor'));

                wp_new_user_notification($user_id, $user_pass);

                $secure_cookie = is_ssl() ? true : false;

                wp_set_auth_cookie($user_id, true, $secure_cookie);

                ### Redirect
                wp_redirect($success_redirect);
                exit;
            } else {
                return array('errors' => $errors, 'posted' => $posted);
            }
        }
    endif;
}

function im_show_login() {
    global $posted, $errors;

    if (isset($_POST['register']) && $_POST['register']) {
        $result = im_reg_proceed_form();

        $errors = $result['errors'];
        $posted = $result['posted'];
    } elseif (isset($_POST['login']) && $_POST['login']) {

        $errors = im_login_proceed_form();
    }

    // Clear errors if loggedout is set.
    if (!empty($_GET['loggedout']))
        $errors = new WP_Error();

    // If cookies are disabled we can't log in even with a valid user+pass
    if (isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]))
        $errors->add('test_cookie', TEST_COOKIE);

    if (isset($_GET['loggedout']) && TRUE == $_GET['loggedout'])
        $notify = "You are now logged out.";

    elseif (isset($_GET['registration']) && 'disabled' == $_GET['registration'])
        $errors->add('registerdisabled', "User registration is currently not allowed.");

    elseif (isset($_GET['checkemail']) && 'confirm' == $_GET['checkemail'])
        $notify = "Check your email for the confirmation link.";

    elseif (isset($_GET['checkemail']) && 'newpass' == $_GET['checkemail'])
        $notify = "Check your email for your new password.";

    elseif (isset($_GET['checkemail']) && 'registered' == $_GET['checkemail'])
        $notify = "Registration complete. Please check your e-mail.";
    if (is_user_logged_in()) {
        wp_redirect(site_url());
    }
    if (is_user_logged_in()) {
        global $wpdb, $current_user;
        $userRole = ($current_user->data->wp_capabilities);
        $role = key($userRole);
        unset($userRole);
        $edit_anchr = '';
        switch ($role) {
            case ('administrator' || 'editor' || 'contributor' || 'author'):
                break;
            default:
                break;
        }
    }
    im_notify_head();
    if (isset($notify) && !empty($notify)) {
        echo '<p class="success">' . $notify . '</p>';
    }
    //Showing login or register error
    if (isset($errors) && sizeof($errors) > 0 && $errors->get_error_code()) :
        echo '<ul id="error" class="error">';
        foreach ($errors->errors as $error) {
            echo '<li>' . $error[0] . '</li>';
        }
        echo '</ul>';
    endif;
    im_notify_footer();
}

function im_show_password() {
    $errors = new WP_Error();

    if (isset($_POST['user_login']) && $_POST['user_login']) {
        $errors = retrieve_password();

        if (!is_wp_error($errors)) {
            wp_redirect('wp-login.php?checkemail=confirm');
            exit();
        }
    }

    if (isset($_GET['error']) && 'invalidkey' == $_GET['error'])
        $errors->add('invalidkey', "Sorry, that key does not appear to be valid.");

    do_action('lost_password');
    do_action('lostpassword_post');

    if (isset($notify) && !empty($notify)) {
        echo '<p class="success">' . $notify . '</p>';
    }
    ?>
    <?php
    if ($errors && sizeof($errors) > 0 && $errors->get_error_code()) :
        echo '<ul class="error">';
        foreach ($errors->errors as $error) {
            echo '<li>' . $error[0] . '</li>';
        }
        echo '</ul>';
    endif;
    im_notify_head();
    im_lost_pw();
    im_notify_footer();
}

function im_loginform() {
    ?>
    <div id="login_form">
        <form id="loginform" action="<?php bloginfo('url') ?>/wp-login.php" method="post">
            <h1 class="form_tag"><?php echo "Sign In"; ?></h1>
            <div class="label">
                <label for="username"><?php echo "User Name:"; ?><span class="required">*</span></label>
            </div>
            <div class="row">
                <input type="text" name="log" id="username" value="<?php echo esc_attr(stripslashes($user_login)); ?>"/>
            </div>
            <div class="label">
                <label for="password"><?php echo "Password:"; ?><span class="required">*</span></label>
            </div>
            <div class="row">
                <input type="password" name="pwd" id="password"/>
            </div>
            <input class="submit" type="submit" name="login" value="Log In"/>
    <!--          <a href="<?php echo site_url('wp-login.php?action=lostpassword'); ?>" class="forgot_password" ><?php echo "Lost your password"; ?></a>-->
            <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
            <input type="hidden" name="user-cookie" value="1" />
        </form>
    </div> 
    <?php
}

function im_reg_form() {
    global $posted;
    $multi_site = WP_ALLOW_MULTISITE;
    if (get_option('users_can_register') || $multi_site == true) :
        if (!$action)
            $action = site_url('wp-login.php?action=register');
        ?>
        <div id="register">
            <form id="register" name="registration" action="<?php echo $action; ?>" method="post">
                <h1 class="form_tag"><?php echo "Create a Account"; ?></h1>
                <div class="label">
                    <label for="username"><?php echo "User Name:"; ?><span class="required">*</span></label>
                </div>
                <div class="row">
                    <input type="text" name="your_username" id="username1" value="<?php if (isset($posted['your_username'])) echo $posted['your_username']; ?>"/>
                </div>
                <div class="label">
                    <label for="email"><?php echo "Email:"; ?><span class="required">*</span></label>
                </div>
                <div class="row">
                    <input type="text" name="your_email" id="email" value="<?php if (isset($posted['your_email'])) echo $posted['your_email']; ?>"/>
                    <p class="user_email_error"></p>
                </div>
                <div class="label">
                    <label for="password1"><?php echo "Enter Password:"; ?><span class="required">*</span></label>
                </div>
                <div class="row">
                    <input type="password" name="your_password" id="password1" value=""/>
                </div>
                <div class="label">
                    <label for="password2"><?php echo "Enter Password Again"; ?><span class="required">*</span></label>
                </div>
                <div class="row">
                    <input type="password" name="your_password_2" id="password2" value=""/>
                    <p class="perror"></p>
                </div>
                <input type="submit" name="register" value="<?php echo "Register"; ?>" class="submit" tabindex="103" />
                <input type="hidden" name="user-cookie" value="1" />
            </form>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                               
                var username = jQuery("#username1"); 	
                function validate_username(){
                    if(username.val() == ''){
                        username.addClass('error');
                        username.css('border', 'solid 1px red');
                        username.attr("placeholder","Required");
                        return false;
                    }else{
                        username.removeClass('error');
                        username.css("border","1px solid #c3c3c3");
                        return true;
                    }
                }
                username.blur(validate_username);
                username.keyup(validate_username);
                var email = jQuery('#email');
                var user_email_error = jQuery('.user_email_error');
                function validate_email(){
                    if(jQuery("#email").val() == "")
                                			
                    {
                        email.addClass('error');
                        email.css('border', 'solid 1px red');
                        email.attr("placeholder","Required");
                        return false;
                    }
                    else
                                			
                    if(jQuery("#email").val() != "")
                {
                    var a = jQuery("#email").val();
                    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                    if(jQuery("#email").val() == "") {
                        email.addClass("error");
                        user_email_error.text("Please provide your email address");
                        user_email_error.addClass("error");
                        return false;
                    } else if(!emailReg.test(jQuery("#email").val())) {
                        email.addClass("error");
                        user_email_error.text("Please provide valid email address");
                        user_email_error.addClass("error");
                        return false;
                    } else {
                        email.removeClass("error");
                        user_email_error.text("");
                        user_email_error.removeClass("error");
                        email.css("border","1px solid #c3c3c3");
                        return true;
                    }
                                				
                                				
                }else
                {
                    email.removeClass("error");
                    user_email_error.text("");
                    user_email_error.removeClass("error");
                    return true;
                }  
            }
            //email.blur(validate_email);
            //email.keyup(validate_email);
                                    
            var pass1 = jQuery('#password1');
            var pass2 = jQuery('#password2');
            var error = jQuery('.perror');
            function validate_password(){
                if(pass1 != pass2){                            
                    error.addClass('error');
                    error.text("Password does not match!");
                    return false;
                }else{
                    error.removeClass('error');
                    return true;  
                }
            }
            var reg_form = jQuery('#register');              
//            reg_form.submit(function()
//            {
//                if(validate_username() & validate_email())
//                {
//                    return true;
//                }
//                else
//                {
//                    return false;
//                }
//            });
        });
        </script>
        <?php
    endif;
}

function im_lost_pw() {
    ?>
    <div id="fotget_pw">
        <h3><?php echo "Forgot your password?"; ?></h3>        
        <form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" class="wp-user-form">
            <div class="row">
                <label for="user_login" class="hide"><?php echo "'Enter your email or username."; ?>: </label><br/>               
                <input type="text" name="user_login" value="" size="20" id="user_login" />
            </div>
            <div class="row">
                <?php do_action('login_form', 'resetpass'); ?>
                <input type="submit" name="user-submit" value="<?php echo "Reset my password"; ?>" class="user-submit" />
                <?php
                $reset = $_GET['reset'];
                if ($reset == true) {
                    echo '<p>' . 'A message will be sent to your email address.' . '</p>';
                }
                ?>
                <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?reset=true" />
                <input type="hidden" name="user-cookie" value="1" />
            </div>
        </form>
    </div>
    <?php
}