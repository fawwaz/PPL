<?php
/*
Plugin Name: Easy Graphs
Plugin Author: Aaron Brazell
Version: 1.0
Description: Allows the safe inclusion of pie and bar graphs in a post, page or custom post type
*/

class Easy_Graphs {
	
	public function __construct()
	{
		add_shortcode( 'easy_graphs', array( $this, 'shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'js' ) );
	}
		
	public function js()
	{
		wp_register_script( 'jquery-piety', plugins_url( 'jquery.peity.min.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'jquery-piety' );
		wp_print_scripts();
	}
	
	public function shortcode( $atts )
	{
		$default = array( 
			'type' => 'bar', 
			'data' => '', 
			'width' => get_option( 'embed_size_w' ), 
			'height' => get_option( 'embed_size_h' ), 
			'color' => '#4d89f9', 
			'color1' => '#fff4dd', 
			'color2' => '#ff9900', 
			'delimiter' => ',', 
			'diameter' => 200 );
		$a = shortcode_atts( $default, $atts );
		extract( $a, EXTR_SKIP );
		if( $data == '' )
			return false;
		
		$chart_types = apply_filters( 'wp_graphs_chart_types', array( 'bar', 'pie', 'line' ) );
		if( !in_array( $type, $chart_types ) )
			return false;
		
		if( $type == 'pie' )
		{
			$data = str_replace( ',', '/', $data );
			$diameter = (int) $diameter;
			$params = 'diameter : ' . $diameter . ', colours : ["' . $color1 . '", "' . $color2 . '"]';
		}
		if( $type == 'bar' )
		{
			$width = (int) $width;
			$height = (int) $height;
			$params = 'width : ' . $width . ', height : ' . $height . ', colour : "' . $color . '"';
		}
		if( $type == 'line' )
		{
			$height = (int) $height;
			$width = (int) $width;
			$params = 'colour : "' . $color1 . '", strokeColour: "' . $color2 . '", strokeWidth : 1, height : ' . $height . ', width : ' . $width;
		}
		$html = '<span class="' . $type . '">' . $data . '</span>';
		$html .= <<<SCRIPT
		<script>
		jQuery(document).ready(function(){
			jQuery('span.$type').peity('$type', { $params });
		});
		</script>
SCRIPT;
		return $html;
	}
}
$easy_graphs = new Easy_Graphs;
