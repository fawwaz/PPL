<?php



/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query. 
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 */
get_header();
sc_bred_crumbs();
sc_before_content();
/* customization start*/
global $wpdb;
?>
test
<h2>Feedback Form</h2>
<?php
// display form if user has not clicked submit
if (!isset($_POST["submit"])) {
  ?>
  <form method="post" action="<?php get_permalink();?>">
  Subject: <input type="text" name="subject"><br>
  Kategori: 
  <select id = "kategi" name="kategori">
	<?php 
		global $wpdb;
		
		$n_interest = sizeof($wpdb->get_results("SELECT * FROM interest"));
	
		$interests_name;
		$interests_name = $wpdb->get_col("SELECT name FROM interest");
		
		for($i = 1;$i<=$n_interest;$i++){
			echo "<option value='interest" .$i. "'>" . $interests_name[$i-1] . "</option>";
		}
	?>
    <option value="All">All</option>
  </select> 
  <br>
  Message: <textarea rows="10" cols="40" name="message"></textarea><br>
  <input type="submit" name="submit" value="Submit Feedback">
  </form>
  <?php
} else {
	global $wpdb;
	if ($_POST['kategori'] == "All")
	{
		echo "a";
		$sql = "SELECT user_email FROM wp_users";
		//$n_interest = sizeof($wpdb->get_results("SELECT user_email FROM wp_users"));
	}
	else
	{
		echo "b";
		$sql = "SELECT user_email FROM wp_users WHERE ".$_POST['kategori']." = 1";
		//$n_interest = sizeof($wpdb->get_results("SELECT user_email FROM wp_users WHERE ".$_POST['kategori']." = 1"));
	}
	$result = $wpdb->get_results($sql) or die(mysql_error());
	//$interests;
	//echo $_POST['subject'];
    // the user has submitted the form
	// Check if the "from" input field is filled out
    require("wp-includes\PHPMailer-master\PHPMailerAutoload.php");

    $mail = new PHPMailer();

	$mail->SMTPAuth = true; // enable SMTP authentication
	$mail->SMTPSecure = "ssl"; 
	$mail->IsSMTP();
	$mail->Host = "plus.smtp.mail.yahoo.com";
	$mail->Port = 465; // set the SMTP port
	$mail->Username = "divusi@yahoo.com";
	$mail->Password = "Ampas1234"; 
	$mail->From = "divusi@yahoo.com";
	$mail->FromName = "Belajar";
	
	$mail->Subject = $_POST['subject'];
	$mail->Body =  $_POST['message'];
	//echo $_POST['kategori'];
	
	//$row = [];
	//$row = $wpdb->get_row("SELECT * FROM wp_users");
	
	
	
	//print_r($mails);
	
	foreach( $result as $results ) {
		//echo $results->user_email;
		$mail->AddAddress($results->user_email);	
		
	}
	if(!$mail->Send())
		{
			echo 'Message '.$results->user_email.' was not sent.';
			echo 'Mailer error: ' . $mail->ErrorInfo;
		}
		else
		{
			echo 'Message '.$results->user_email.' has been sent.';
		}
}
?>

<!--Start Content Wrapper
<div id="content_wrapper">
    <div class="grid_17 alpha">
        <div class="content">
            <?php if (have_posts()) : the_post(); ?>
                <h1 class="post_title"><?php the_title(); ?></h1>
                <div class="border_strip"></div>
                <?php the_content(); ?>	
                <div class="clear"></div>
                <?php wp_link_pages(array('before' => '<div class="page-link"><span>' . 'Pages:' . '</span>', 'after' => '</div>')); ?>
                <?php edit_post_link('Edit', '', ''); ?>
            <?php endif; ?>	
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