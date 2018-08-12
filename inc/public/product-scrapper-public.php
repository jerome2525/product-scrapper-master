<?php 
if( class_exists('Product_Scrapper_Public') ) {
	return;
}
class Product_Scrapper_Public {

	public function __construct() {
		
		$this->load_includes();
		$this->register_shortcodes();

	}

	//Include admin and Public files
	public function load_includes() {

	}

	// Register Shortcodes 
	public function register_shortcodes() {

		add_shortcode( 'product_scrapper', array( $this, 'create_product_scrapper' ) );

	}

	public function create_product_scrapper( $atts ) {

		$atts = shortcode_atts(
			array(
				'id' => ''
			), 
			$atts, 'product_scrapper' );
		
		$id = $atts['id'];

		ob_start();
			if( !empty( $id && get_field('product_scrapper_url', $id ) ) ) {
				$product_scrapper_url  = get_field('product_scrapper_url', $id );
				$store = get_field('store', $id );
				$price_elem_class  = get_field('price_elem_class', $store );
				$old_price_elem_class  = get_field('old_price_elem_class', $store );
				$price_currency  = get_field('price_currency', $id );
				$html = file_get_html( $product_scrapper_url );
				$imageurl = $this->get_product_data( $html, 'image' );
				$title = $this->get_product_data( $html, 'title' );
				$price = $this->get_price( $html, $price_elem_class );
				$old_price = $this->get_price( $html, $old_price_elem_class );
				if( !empty( $old_price && $price ) ) {
					$num_price = preg_replace('~\D~', '', $price );
					$num_old_price = preg_replace('~\D~', '', $old_price );
					$total_price = $num_price / $num_old_price * 100;
					$discount = round( $total_price );
				}
				if( !empty( get_field('product_aff_url', $id ) ) ) {
					$product_scrapper_url  = get_field('product_aff_url', $id );
				}
				$star_rating  = get_field('star_rating', $id );
				include( plugin_dir_path( __DIR__ ) . 'templates/product-scrapper-frame.php');
			}
			echo get_post_meta( 115, 'product_status', true ); 
		return ob_get_clean();
	}

	public function get_product_data( $html, $type ) {

		if( !empty( $html && $type ) ) {
			if( $type == 'image' ) {
				$meta = 'meta[property="og:image"]';
			}
			elseif( $type == 'title' ) {
				$meta = 'meta[property="og:title"]';
			}

			foreach( $html->find( $meta ) as $e ) {
				return $e->content;
			}
		}
	}

	public function get_price( $html, $elem ) {

		foreach( $html->find( $elem ) as $e ) {
		    return $e->innertext;
		}

	}	
} 