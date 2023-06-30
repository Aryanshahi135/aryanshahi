<?php if ( get_theme_mod('plant_garden_hot_products_section_enable', true) == true ) : ?>
<div id="hot_products" class="py-5">
	<div class="container">
    <?php if ( get_theme_mod('plant_garden_hot_products_main_heading') ) : ?>
		  <h3 class="mt-3 text-center"><?php echo esc_html(get_theme_mod('plant_garden_hot_products_main_heading'));?></h3>
    <?php endif; ?>
    <div class="row">
      <?php
      $plant_garden_product_data = get_theme_mod('plant_garden_hot_products_category');
      if ( class_exists( 'WooCommerce' ) ) {
        $plant_garden_args = array(
          'post_type' => 'product',
          'posts_per_page' => get_theme_mod('plant_garden_hot_products_per_page'),
          'product_cat' => $plant_garden_product_data,
          'order' => 'ASC'
        );
        $loop = new WP_Query( $plant_garden_args );
        while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
        	<div class="col-xl-3 col-lg-4 col-md-6">
            <div class="tab-product">
              <div class="product-image my-4">
                <figure>
                  <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.esc_url(woocommerce_placeholder_img_src()).'" />'; ?>
                </figure>
                <div class="cart-button">
                  <span class="icon" href="<?php if(function_exists('wc_get_cart_url')){ echo esc_url(wc_get_cart_url()); } ?>"><span class="button1"><?php if( $product->is_type( 'simple' ) ) { woocommerce_template_loop_add_to_cart(  $loop->post, $product );} ?></span></span>
                </div>
              </div>
              <div class="product-details">
          	    <h4 class="product-text mt-2 "><a href="<?php echo esc_url(get_permalink( $loop->post->ID )); ?>"><?php the_title(); ?></a></h4>
                <h5 class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $product->get_price_html(); ?></h5>
              </div>
            </div>
          </div>
        <?php endwhile; wp_reset_query(); ?>
      <?php } ?>
	  </div>
	</div>
</div>
<?php endif; ?>
