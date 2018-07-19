<?php
/**
 * Define the metabox and field configurations.
 */

if( class_exists('Meta_Boxes') ) {
	return;
}

class Meta_Boxes {

	public function __construct() {

		$this->load_includes();
		$this->load_hooks();
		$this->create_fields();

	}

	public function load_includes() {

		if ( file_exists( plugin_dir_path( __FILE__ ) . 'lib/acf/acf.php' ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'lib/acf/acf.php';
		}

		include_once( plugin_dir_path( __FILE__ ) . 'lib/simple_html_dom.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'lib/paginator.php' );

	}

	public function check_product_exist( $url ) {

		if( !empty( $url ) ) {
			$html = file_get_html( $url );
			$meta = 'meta[property="og:title"]';
			foreach( $html->find( $meta ) as $e ) {
				return true;
			}
		}	

	}

	public function load_hooks() {

		add_filter('acf/settings/path', array( $this, 'new_acf_settings_path') );
		add_filter('acf/settings/dir', array( $this, 'new_acf_settings_dir') );
		//add_filter('acf/settings/show_admin', '__return_false' );

	}
	 
	public function new_acf_settings_path( $path ) { 

	    $path = plugin_dir_path( __FILE__ ) . 'lib/acf/';
	    return $path;    

	}
	 
	public function new_acf_settings_dir( $dir ) {

	    $dir = plugin_dir_url( __FILE__ ) . 'lib/acf/';
	    return $dir;

	}

	public function create_fields() {

		// Start CTA Button fields
		$pid = $_GET['post'];
		if( !empty( $pid ) ) {

			$product_scrapper_url = get_field('product_scrapper_url', $pid );

			if( $this->check_product_exist( $product_scrapper_url ) ) {
				$product_existed = '<br/><strong style="color:green;">This Product is Active</strong>'; 
			}
			else {
				$product_existed = '<br/><strong style="color:red;">This Product has been removed!</strong>'; 
			}
			
			$shortcode_paste = '[product_scrapper id=' . $pid . ']';
			$shortcode_paste  = 'Place here the product url to get its data and use this shortcode <strong style="font-size: 20px;">'.$shortcode_paste.'</strong>'.$product_existed; 
		}

		acf_add_local_field_group( array (
			'key'      => 'product_scrapper_fields',
			'title'    => 'Product Scrapper Fields',
			'location' => array (
				array (
					array (
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'product-scrapper',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => array('the_content'),
		) );

		acf_add_local_field( array(
			'key'          => 'main_tab',
			'label'        => 'Main',
			'name'         => 'main_tab',
			'type'         => 'tab',
			'parent'       => 'product_scrapper_fields',
		) );

		acf_add_local_field( array(
			'key'          => 'product_scrapper_url',
			'label'        => 'Product URL',
			'name'         => 'product_scrapper_url',
			'type'         => 'url',
			'parent'       => 'product_scrapper_fields',
			'instructions' => $shortcode_paste,
			'required'     => 1,
		) );

		acf_add_local_field( array(
			'key'          => 'product_aff_url',
			'label'        => 'Affiliate URL',
			'name'         => 'product_aff_url',
			'type'         => 'url',
			'parent'       => 'product_scrapper_fields',
			'instructions' => 'This will replace the button url. You can use it on affiliate.',
			'required'     => 0,
		) );

		acf_add_local_field( array(
			'key'          => 'price_currency',
			'label'        => 'Price Currency',
			'name'         => 'price_currency',
			'type'         => 'text',
			'parent'       => 'product_scrapper_fields',
			'instructions' => 'This is optional, if you wish to add a currency besides the price, if the plugin doesnt capture any price currency',
			'required'     => 0,
		) );

		acf_add_local_field( array(
			'key'          => 'star_rating',
			'label'        => 'Star Rating',
			'name'         => 'star_rating',
			'type'         => 'number',
			'parent'       => 'product_scrapper_fields',
			'instructions' => 'This is for the Star rating',
			'required'     => 0,
		) );

		acf_add_local_field( array(
			'key'          => 'store',
			'label'        => 'Store',
			'name'         => 'store',
			'type'         => 'post_object',
			'parent'       => 'product_scrapper_fields',
			'instructions' => 'Please choose Store to link up',
			'required'     => 1,
			'post_type'		=> 'store',
			'return_format' => 'id',
		) );

		acf_add_local_field( array(
			'key'          => 'developer_tab',
			'label'        => 'For Developers',
			'name'         => 'developer_tab',
			'type'         => 'tab',
			'parent'       => 'product_scrapper_fields',
			'instructions' => '',
		) );

		acf_add_local_field( array(
			'key'          => 'price_elem_class',
			'label'        => 'Price Element, ID, Class',
			'name'         => 'price_elem_class',
			'type'         => 'text',
			'parent'       => 'product_scrapper_fields',
			'instructions' => 'This field is to get the price of the url based on website element,class and id.',
			'required'     => 1,
		) );

		acf_add_local_field( array(
			'key'          => 'old_price_elem_class',
			'label'        => 'Old Price Element, ID, Class',
			'name'         => 'old_price_elem_class',
			'type'         => 'text',
			'parent'       => 'product_scrapper_fields',
			'instructions' => 'This field is to get the price of the url based on website element,class and id.',
			'required'     => 0,
		) );

		
		
		// End CTA Button fields


		// Store fields
		acf_add_local_field_group( array (
			'key'      => 'store_fields',
			'title'    => 'Store Fields',
			'location' => array (
				array (
					array (
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'store',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => array('the_content'),
		) );

		acf_add_local_field(
			array(
				'key'          => 'store_logo',
				'label'        => 'Store Logo',
				'name'         => 'store_logo',
				'type'         => 'image',
				'parent'       => 'store_fields',
				'instructions' => 'Place the Store Logo',
				'required'     => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'url',
				'preview_size' => 'large',
				'library' => 'all',
				'min_width' => '',
				'min_height' => '',
				'min_size' => '',
				'max_width' => '',
				'max_height' => '',
				'max_size' => '',
				'mime_types' => ''
			)
		);

		acf_add_local_field( array(
			'key'          	=> 'store_url',
			'label'        	=> 'Store Main URL',
			'name'         	=> 'store_url',
			'type'         	=> 'url',
			'parent'       	=> 'store_fields',
			'instructions' 	=> 'Place the Store URL',
			'required'		=> 1
		) );


		if( function_exists('acf_add_options_page') ) {
	
			acf_add_options_page( array(
				'page_title' 	=> 'Product Status',
				'menu_title'	=> 'Product Status',
				'menu_slug' 	=> 'product-status',
				'capability'	=> 'edit_posts',
				'redirect'		=> false,
				'icon_url' => 'dashicons-chart-bar'
			) );

			acf_add_local_field_group( array (
				'key'      => 'product_status_group',
				'title'    => 'Product Status',
				'location' => array (
					array (
						array (
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'product-status',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => array('the_content'),
			) );

			acf_add_local_field( array(
				'key'          	=> 'status',
				'label'        	=> '',
				'name'         	=> 'status',
				'type'         	=> 'message',
				'parent'       	=> 'product_status_group',
				'message'	=> $this->show_product_status(),
			) );
			
		}

	}

	public function show_product_status() {

		global $wpdb;
		$items_per_page = 10;
		$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
		$offset = ( $page * $items_per_page ) - $items_per_page;
		$query = $wpdb->get_results("
			SELECT ID, post_title 
			FROM $wpdb->posts
			WHERE post_status = 'publish' 
				AND post_type = 'product-scrapper'
				LIMIT $offset, $items_per_page
		");

		$total = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' 
				AND post_type = 'product-scrapper'" );

		if( !empty( $query ) ) {
			$val = '';
			foreach ( $query as $value ) {
				$title = $value->post_title;
				$id = $value->ID;
				$url = get_field( 'product_scrapper_url', $id ); 
				if( $this->check_product_exist( $url ) ) {
					$status = '<span style="color: green;">This Product is Active!</span>';
				}
				else {
					$status = '<span style="color: red;">This Product was Removed!</span>';
				}
				$val .= '<p>'.$title.': '.$status.'</p>';
			}

			$pages = new Paginator( $items_per_page,'cpage');
			$pages->set_total( $total );
			$val .= $pages->page_links(); 
		
			return $val;
		}
		else {
			echo 'No Result Found!';
		}

	} 

}