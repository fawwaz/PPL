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

//TES DATABASE
//$customers = $wpdb->get_results("SELECT * FROM wp_users;");
//print_r($customers);
$n_interest = sizeof($wpdb->get_results("SELECT * FROM interest"));
//echo $n_interest ;

$interests;
for($i = 1;$i<=$n_interest;$i++){
	$interests[$i] = $wpdb->get_row("SELECT COUNT(interest" . $i .  ") AS n FROM wp_users WHERE interest" . $i . " = TRUE")->n;
};

$interests_name;
$interests_name = $wpdb->get_col("SELECT name FROM interest");
// print_r($interests_name);
// echo $interests_name[0];
// echo $interests_name[1];
// echo $interests_name[2];

?>

<script>
	var n_interest = <?php echo $n_interest ?>;
	var interests = [];
	var interests_name = [];
	<?php 
		for($i = 1;$i<=$n_interest;$i++){
	?>
			interests[
			<?php
				echo $i;
			?>
			] = 
			<?php
				echo $interests[$i];
			?>
			;
			<?php
		}
			?>

	<?php 
		for($i = 0;$i<$n_interest;$i++){
	?>
			interests_name[
			<?php
				echo $i;
			?>
			] = "<?php
				echo $interests_name[$i];
			?>";
			<?php
		}
			?>
</script>

	

<!--Start Content Wrapper-->
<div id="content_wrapper">
    <div class="grid_17 alpha">
        <div class="content">
            <?php if (have_posts()) : the_post(); ?>
                <h1 class="post_title"><?php the_title(); ?></h1>
				
				<script src""></script>
				<script src="js/Chart/Chart.js"></script>
				<canvas id="canvas" height="450" width="600"></canvas>

				<script>

					var barChartData = {
						labels : [<?php 
							for($i = 0;$i<$n_interest;$i++){
								echo '"'.$interests_name[$i].'"';
								if($i!=$n_interest-1){
									echo ",";
								}
							}
						?>],
						datasets : [
							{
								fillColor : "rgba(220,220,220,0.5)",
								strokeColor : "rgba(220,220,220,1)",
								data : [<?php
									for($i = 1;$i<=$n_interest;$i++){
										echo $interests[$i];
										if($i!=$n_interest){
											echo ",";
										}
									}
								?>]
							}
						]
						
					}

				var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Bar(barChartData);
				
				</script>
	
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