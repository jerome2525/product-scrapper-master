<?php
/**
 * Define the metabox and field configurations.
 */

if( class_exists('Ps_Meta_Boxes') ) {
	return;
}

class Ps_Meta_Boxes {

	public function __construct() {

		$this->load_includes();
		$this->load_hooks();
		$this->create_fields();

	}

	public function load_includes() {

		if ( file_exists( plugin_dir_path( __FILE__ ) . 'lib/acf/acf.php' ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'lib/acf/acf.php';
		}

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
		add_action('acf/save_post', array( $this, 'product_acf_save_post' ) );
		add_action('acf/save_post', array( $this, 'product_acf_save_option' ) );

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

		acf_add_local_field( array(
			'key'          => 'main_tab',
			'label'        => 'Main',
			'name'         => 'main_tab',
			'type'         => 'tab',
			'parent'       => 'store_fields',
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

		acf_add_local_field( array(
			'key'          => 'developer_tab',
			'label'        => 'For Developers',
			'name'         => 'developer_tab',
			'type'         => 'tab',
			'parent'       => 'store_fields',
			'instructions' => '',
		) );

		acf_add_local_field( array(
			'key'          => 'price_elem_class',
			'label'        => 'Price Element, ID, Class',
			'name'         => 'price_elem_class',
			'type'         => 'text',
			'parent'       => 'store_fields',
			'instructions' => 'This field is to get the price of the url based on website element,class and id.',
			'required'     => 1,
		) );

		acf_add_local_field( array(
			'key'          => 'old_price_elem_class',
			'label'        => 'Old Price Element, ID, Class',
			'name'         => 'old_price_elem_class',
			'type'         => 'text',
			'parent'       => 'store_fields',
			'instructions' => 'This field is to get the price of the url based on website element,class and id.',
			'required'     => 0,
		) );


		if( function_exists('acf_add_options_page') ) {
	
			acf_add_options_page( array(
				'page_title' 	=> 'Product Status',
				'menu_title'	=> 'Product Status',
				'menu_slug' 	=> 'product-status',
				'capability'	=> 'edit_posts',
				'redirect'		=> false,
				'icon_url' => 'dashicons-chart-bar',
				'update_button'		=> __('Filter', 'acf'),
				'updated_message'	=> __('Filtered Done!', 'acf'),
				'redirect' => true
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
				'key'          	=> 'product_filter',
				'label'        	=> 'Product Filter',
				'name'         	=> 'product_filter',
				'type'         	=> 'select',
				'parent'       	=> 'product_status_group',
				'required'		=> 0,
				'choices' => array(
					''	=> 'Show All',
					'active'	=> 'Active',
					'remove'	=> 'Removed'
				)
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
		$items_per_page = 2;
		//$status = $_GET['status'];
		$status = get_field( 'product_filter','option');
		$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;

		if( $status ) {
			
		}

		$offset = ( $page * $items_per_page ) - $items_per_page;
		if( $status ) {

			$query = $wpdb->get_results("
				SELECT $wpdb->posts.* 
				FROM $wpdb->posts, $wpdb->postmeta
				WHERE $wpdb->posts.post_status = 'publish' 
				AND $wpdb->posts.post_type = 'product-scrapper'
				AND $wpdb->postmeta.post_id = $wpdb->posts.ID
				AND $wpdb->postmeta.meta_key = 'product_status'
				AND $wpdb->postmeta.meta_value = '$status'
				LIMIT $offset, $items_per_page
			");

			$total = $wpdb->get_var( "
				SELECT COUNT(*) 
				FROM $wpdb->posts, $wpdb->postmeta
				WHERE $wpdb->posts.post_status = 'publish' 
				AND $wpdb->posts.post_type = 'product-scrapper'
				AND $wpdb->postmeta.post_id = $wpdb->posts.ID
				AND $wpdb->postmeta.meta_key = 'product_status'
				AND $wpdb->postmeta.meta_value = '$status'" );

		}
		else {

			$query = $wpdb->get_results("
				SELECT ID, post_title 
				FROM $wpdb->posts
				WHERE post_status = 'publish' 
				AND post_type = 'product-scrapper'
				LIMIT $offset, $items_per_page
			");

			$total = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' 
				AND post_type = 'product-scrapper'" );

		}

		if( !empty( $query ) ) {
			$val = '';
			foreach ( $query as $value ) {
				$title = $value->post_title;
				$id = $value->ID;
				$pstat = get_field( 'product_status', $id ); 
				if( $pstat == 'active') {
					$status = '<span style="color: green;">This Product is Active!</span>';
				}
				else {
					$status = '<span style="color: red;">This Product has been Removed!</span>';
				}

				$val .= '<p>'.$title.': '.$status.'</p>';
					
				
			}

			$pages = new Paginator( $items_per_page, 'cpage' );
			$pages->set_total( $total );
			$val .= $pages->page_links(); 
		
			return $val;
		}
		else {
			echo 'No Result Found!';
		}

	} 

	public function product_acf_save_post( $post_id ) {

	    $product_scrapper_url = get_field( 'product_scrapper_url' , $post_id );
	    if( !empty( $product_scrapper_url ) ) {

		    if( $this->check_product_exist( $product_scrapper_url ) ) {
		    	$status = 'active';
		    }
		    else {
		    	$status = 'remove';
		    }

		    update_post_meta( $post_id, 'product_status', $status );

    	}

	}

	public function product_acf_save_option() {
		$screen = get_current_screen();
		if ( strpos( $screen->id, "product-status") == true ) {

		}
	}

}