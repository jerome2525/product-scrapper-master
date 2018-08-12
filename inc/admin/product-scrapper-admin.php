<?php 

if( class_exists('Product_Scrapper_Admin') ) {
	return;
}

class Product_Scrapper_Admin {

	public function __construct() {

		$this->load_includes();

		$this->register_post_type();

		new Ps_Meta_Boxes;

		$this->load_admin_assets();

		$this->load_hooks();
	}

	public function load_includes() {

		include_once( plugin_dir_path( __DIR__ ) . 'lib/CPT.php' );
		include_once( plugin_dir_path( __DIR__ ) . 'lib/paginator.php' );
		include_once( plugin_dir_path( __DIR__ ) . 'meta-boxes.php' );

	}

	public function load_hooks() {


	}

	//Load Assets
	public function load_admin_assets() {

		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) );

	}

	public function register_admin_assets() {

		//css
		wp_enqueue_style( 'admin-style', plugin_dir_url( __DIR__ ) . 'css/admin.css', array(), '1.1' );
		
		//js
		//wp_enqueue_script( 'product-scrapper-js', plugin_dir_url( __FILE__ ) . 'js/main.js', array('jquery'), '1.2', true );

	}

	public function register_post_type() {

		$cta = new CPT(array(
			'post_type_name' => 'product-scrapper',
			'singular' => 'Product Scrapper',
			'plural' => 'Product Scrapper',
			'slug' => 'product-scrapper'
		));

		$cta->menu_icon( 'dashicons-admin-links' );

		$store = new CPT(array(
			'post_type_name' => 'store',
			'singular' => 'Store',
			'plural' => 'Stores',
			'slug' => 'store'
		));

		$store->menu_icon( 'dashicons-store' );

	}

}