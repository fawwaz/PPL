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

/*Customization Start*/
?>

<h2>Daftar Free Trial</h2>
<?php
// display form if user has not clicked submit
if (!isset($_POST["submit"])) {
  ?>
<form method="post" action="<?php get_permalink();?>">
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
	</select> 
	<input type="submit" name="submit" value="Submit Feedback">
</form>
 <?php
} else {
		//echo "a";
		$sql = "UPDATE wp_users set ".$_POST['kategori']." = 1 where ID = ".get_current_user_id( );
		
		//$n_interest = sizeof($wpdb->get_results("SELECT user_email FROM wp_users"));
	
	$result = $wpdb->get_results($sql) or die(mysql_error());
	$echo "Selamat anda telah terdaftar untuk Free Trial selama 30 Hari";
}
?>

<?php
sc_after_content();
get_footer();
?>