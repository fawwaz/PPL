<h2>Feedback Form</h2>
<?php
// display form if user has not clicked submit
if (!isset($_POST["submit"])) {
  ?>
  <form method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
  Subject: <input type="text" name="subject"><br>
  Message: <textarea rows="10" cols="40" name="message"></textarea><br>
  <input type="submit" name="submit" value="Submit Feedback">
  </form>
  <?php
} else {    // the user has submitted the form
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
	$mail->AddAddress("13511068@std.stei.itb.ac.id");
	$mail->Subject = $_POST['subject'];
	$mail->Body =  $_POST['message'];
	
	if(!$mail->Send())
	{
		echo 'Message was not sent.';
		echo 'Mailer error: ' . $mail->ErrorInfo;
	}
	else
	{
		echo 'Message has been sent.';
	}
}
?>