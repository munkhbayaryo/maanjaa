<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

?>

<li <?php wc_product_class( 'product-list clearfix', $product ); ?>>

		<div class="inner-left">

			<?php if ( has_post_thumbnail() ) {
				echo '<div class="product-thumb">'; woocommerce_show_product_loop_sale_flash(); echo '<span class="helper">';
					echo '<a href="'; the_permalink(); echo '">'; the_post_thumbnail('maanjaa-thumb'); echo '</a>';
				echo '</span></div>';
			} ?>

			<div class="product-details">
				<h3 class="name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

				<?php do_action( 'express_shop_title' ); ?>
				
				<?php if($product->get_stock_quantity()>0) {
					echo '<p class="stock-in-loop">'; echo 'Only '; echo $product->get_stock_quantity(); echo ' left in stock - order soon.';  echo '</p>';
					}
				?>

				<div class="rating">
					<?php
						if ($rating_html = wc_get_rating_html( $product->get_average_rating() )) {
							echo trim( wc_get_rating_html( $product->get_average_rating() ) );
						}
					?>
				</div>

			</div>

		</div>

		<div class="inner-right">
			<div class="inner">
				<div class="price"><?php echo ($product->get_price_html()); ?></div>
				<div class="addcart">
					<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
				</div>
			</div>
		</div>
</li>
