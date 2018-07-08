<?php
/*
Plugin Name: Product Scrapper 
Description: A Wordpress plugin that get a Product information from url.
Plugin URI: http://wp-needs.com
Author: Jerome Anyayahan
Author URI: http://wp-needs.com
Version: 1.0
License: GPL2
Text Domain: Text Domain
Domain Path: Domain Path
*/

require plugin_dir_path( __FILE__ ) . 'inc/product-scrapper.php';

new Product_Scrapper();



