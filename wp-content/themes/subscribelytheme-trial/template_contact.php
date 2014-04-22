<?php
/**
 * Template Name: Contact
 *
 */
get_header();
sc_bred_crumbs();
sc_before_content();
?>
<?php
$fnameError = '';
$lnameError = '';
$emailError = '';
$commentError = '';
if (isset($_POST['submitted'])) {
    if (trim($_POST['firstName']) === '') {
        $fnameError = 'Please Enter your First Name.';
        $hasError = true;
    } else {
        $name = trim($_POST['firstName']);
    }

    if (trim($_POST['lastName']) === '') {
        $lnameError = 'Please Enter your Last Name.';
        $hasError = true;
    } else {
        $name = trim($_POST['lastName']);
    }



    if (trim($_POST['email']) === '') {
        $emailError = 'Please enter your email address.';
        $hasError = true;
    } else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
        $emailError = 'You entered an invalid email address.';
        $hasError = true;
    } else {
        $email = trim($_POST['email']);
    }
    if (trim($_POST['comments']) === '') {
        $commentError = 'Please enter a message.';
        $hasError = true;
    } else {
        if (function_exists('stripslashes')) {
            $comments = stripslashes(trim($_POST['comments']));
        } else {
            $comments = trim($_POST['comments']);
        }
    }
    if (!isset($hasError)) {
        $emailTo = get_option('tz_email');
        if (!isset($emailTo) || ($emailTo == '')) {
            $emailTo = get_option('admin_email');
        }
        $subject = '[PHP Snippets] From ' . $name;
        $body = "Name: $name \n\nEmail: $email \n\nComments: $comments";
        $headers = 'From: ' . $name . ' <' . $emailTo . '>' . "\r\n" . 'Reply-To: ' . $email;
        mail($emailTo, $subject, $body, $headers);
        $emailSent = true;
    }
}
?>
<script>
    jQuery(document).ready(function(){
        //jQuery("#contactForm").validate();
        var fname = jQuery('#firstName');
        function validate_fname(){
            if(fname.val() == ''){
                alert("Please enter the first name.");
                return false;
            }else{                
                return true;
            }
        }
        fname.blur(validate_fname);
        fname.keyup(validate_fname);
        var email = jQuery('#email');
        function validate_email(){
            if(email.val() == ''){
                alert("Please enter the email.");
                return false;
            }
            else{
                return true;
            }
        }
        email.blur(validate_email);
        email.keyup(validate_email);
        var message = jQuery('#commentsText');
        function validate_message(){
            if(message.val() == ''){
                alert('Please enter your message');
                return false;
            }else{
                return true;
            }
            
        }
        message.blur(validate_message);
        message.keyup(validate_message);
        var Form = jQuery('#contactForm');
        Form.submit(function(){
           if(validate_fname() & validate_email() & validate_message()){
               return true;
           } else{
               return false;
           }               
        });
        
    });
</script>
<!--Start Content Wrapper-->
<div id="content_wrapper">
    <div class="grid_17 alpha">
        <div class="content contact">
            <?php if (have_posts()) : the_post(); ?>
                <h1><?php the_title(); ?></h1> 
                <?php the_content(); ?>
            <?php endif; ?>
            <div class="border_strip"></div>
            <form action="#" id="contactForm" class="contactForm" method="post">
                <input type="text" name="firstName" id="firstName" value="" placeholder="First Name" class="required requiredField" />
                <input type="text" name="lastName" id="lastName" value="" placeholder="Last Name" class="required requiredField" />
                <input type="text" name="email" id="email" value="" placeholder="Your Email" class="required requiredField email" />
                <textarea name="comments" id="commentsText" rows="20" cols="30" placeholder="Message" class="required requiredField"></textarea>
                <div class="clear"></div>
                <input class="submit" type="submit" value=""/>
                <input type="hidden" name="submitted" id="submitted" value="true" />
            </form>
        </div>
        <div class="clear"></div>
    </div>
    <div class="grid_7 omega">
        <?php get_sidebar(); ?>
    </div>
    <div class="clear"></div>
</div>
<!--End Content Wrapper-->
<?php
sc_after_content();
get_footer();
?>