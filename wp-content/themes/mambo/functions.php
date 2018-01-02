<?php
add_action( 'wp_enqueue_scripts', 'mambo_theme_css',999);
function mambo_theme_css() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'parent-style' ) );
	wp_enqueue_style( 'child-default', get_stylesheet_directory_uri()."/css/default.css" );
}

add_action( 'after_setup_theme', 'mambo_setup' ); 	
   	function mambo_setup()
   	{	
		load_theme_textdomain( 'mambo', get_stylesheet_directory() . '/languages' );
	} 
?>