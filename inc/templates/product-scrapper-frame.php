<div class="product-scrapper-class">
	<?php if (!empty( $imageurl && $title && $price && $store_logo && $store_url ) ) { ?>
		<?php if (!empty( $price && $old_price ) ) { ?>
			<span class="discount">- <?php echo $discount; ?> % </span>
		<?php } ?>	
		<div class="img-col same-height">
			<?php if( !empty( $store_logo ) ) { ?>
				<a href="<?php echo $store_url; ?>" target="_blank">
					<img src="<?php echo $store_logo; ?>">
				</a>
			<?php } else { echo '<span>This Product has no Store Image!</span>'; } ?>
		</div>
		<div class="img-col same-height">
			<?php if( !empty( $imageurl ) ) { ?>
				<a href="<?php echo $product_scrapper_url; ?>" target="_blank">
					<img src="<?php echo $imageurl; ?>">
				</a>
			<?php } else { echo '<span>This Product has no Image!</span>'; } ?>
		</div>
		<div class="content-col same-height">
			<?php if( !empty( $star_rating ) ) { ?>
				<a href="<?php echo $product_scrapper_url; ?>" class="star-rating" target="_blank"></a>
			<?php } ?>
			<?php if( !empty( $title ) ) { ?>
				<h3><a href="<?php echo $product_scrapper_url; ?>" target="_blank"><?php echo $title; ?></a></h3>
			<?php } else { echo '<span>This Product has no Title!</span>'; } ?>

			<?php if( !empty( $price ) ) { ?>
				<span class="price"><?php echo $price; ?> <?php if( !empty( $price_currency ) ) { echo $price_currency; } ?>
					<?php if( !empty( $old_price ) ) { ?>
					<span class="old-price"><?php echo $old_price; ?> <?php if( !empty( $price_currency ) ) { echo $price_currency; } ?></span>
					<?php } ?>
				</span>

			<?php } else { echo '<span>This Product has no Price!</span>'; } ?>

			<a href="<?php echo $product_scrapper_url; ?>" class="btn" target="_blank">Check Now <i class="fa fa-angle-right" aria-hidden="true"></i></i></a>
		</div>

	<?php }else { echo '<span>This Product was already removed!</span>'; } ?>	
	

</div>
<?php if( !empty( $star_rating ) ) { ?>
	<script type="text/javascript">
		jQuery(document).ready(function ($) { 
		  $(".star-rating").rateYo({
		 
		    rating    : <?php echo $star_rating; ?>,
		    spacing   : "5px",
		    numStars: 5,
		    starWidth: "20px",
		    multiColor: {
		 
		      "startColor": "#000000",
		      "endColor"  : "#CC0000" 
		    }
		  });
		});
	</script>
<?php } ?>