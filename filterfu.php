<?php
/*
Plugin Name: FilterFu
Plugin URI: http://github.com/nathancarnes/filterfu/
Description: A drop in replacement for KSES on WordPress using htmlLawed to aggressively enforce semantic, valid output (even against Microsoft Word)
Version: 1.0
Author: Nathan Carnes
Author URI: http://carnesmedia.com
*/

//Include htmLawed (or we're not going to get very far)
require_once("htmLawed.php");

//Get rid of silly things like empty <p></p> Word inserts after it zeros out <p> margins
function remove_empty_tags($html_replace){
	$pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";
	return preg_replace($pattern, '', $html_replace);
}

//This is the special sauce right here
function clean_content($the_content){
	$config = array(
		clean_ms_char => 0,
		comment => 1,
		css_expression => 0,
		deny_attribute => "style, width",
		keep_bad => 2,
		named_entity => 0,
		hexdec_entity => 0
	);
	
	$output = remove_empty_tags(htmLawed($the_content, $config)); 
	
	return $output;
}

// Clean content when it's displayed in the editor or on the page
add_action('the_content', 'clean_content');
add_action('the_editor_content', 'clean_content');
?>
