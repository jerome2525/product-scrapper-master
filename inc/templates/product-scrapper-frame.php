<div class="product-scrapper-class">
	<?php if (!empty( $imageurl && $title && $price ) ) { ?>
		<div class="img-col same-height">
			<?php if( !empty( $imageurl ) ) { ?>
				<a href="<?php echo $product_scrapper_url; ?>">
					<img src="<?php echo $imageurl; ?>">
				</a>
			<?php } else { echo '<span>This Product has no Image!</span>'; } ?>
		</div>
		<div class="content-col same-height">
			<?php if( !empty( $title ) ) { ?>
				<h3><a href="<?php echo $product_scrapper_url; ?>" target="_blank"><?php echo $title; ?></a></h3>
			<?php } else { echo '<span>This Product has no Title!</span>'; } ?>

			<?php if( !empty( $price ) ) { ?>
				<span class="price"><?php echo $price; ?> <?php if( !empty( $price_currency ) ) { echo $price_currency; } ?></span>
			<?php } else { echo '<span>This Product has no Price!</span>'; } ?>

			<a href="<?php echo $product_scrapper_url; ?>" class="btn" target="_blank">Check Now <i class="fa fa-angle-right" aria-hidden="true"></i></i></a>
		</div>

	<?php }else { echo '<span>This Product was already removed!</span>'; } ?>	
</div>