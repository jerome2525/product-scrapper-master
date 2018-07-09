<?php
/**
 * This controls the plugin
 * @package  Cta_Btn
 */

if( class_exists('Product_Scrapper') ) {
	return;
}

class Product_Scrapper {

	public function __construct() {

		$this->load_includes();
		new Product_Scrapper_Admin;
		new Product_Scrapper_Public;
		$this->load_assets();

	}

	//Include admin and Public files
	public function load_includes() {

		include_once( plugin_dir_path( __FILE__ ) . 'admin/product-scrapper-admin.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'public/product-scrapper-public.php' );

	}

	//Load Assets
	public function load_assets() {

		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );

	}

	public function register_assets() {

		//css
		wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
		wp_enqueue_style( 'google-font', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i' );
		wp_enqueue_style( 'product-scrapper-style', plugin_dir_url( __FILE__ ) . 'css/product-scrapper-style.css', array(), '1.0' );
		
		//js
		wp_enqueue_script( 'same-h', plugin_dir_url( __FILE__ ) . 'js/jquery.matchHeight.min.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'product-scrapper-js', plugin_dir_url( __FILE__ ) . 'js/main.js', array('jquery'), '1.0', true );

	}

}