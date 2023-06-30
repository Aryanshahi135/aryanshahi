<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<meta http-equiv="Content-Type" content="<?php echo esc_attr(get_bloginfo('html_type')); ?>; charset=<?php echo esc_attr(get_bloginfo('charset')); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.2, user-scalable=yes" />

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php
	if ( function_exists( 'wp_body_open' ) )
	{
		wp_body_open();
	}else{
		do_action('wp_body_open');
	}
?>

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'plant-garden' ); ?></a>

<div class="main-header-box">
	<div class="top-header text-center text-md-left py-2">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 align-self-center top-info-box">
					<?php if ( get_theme_mod('plant_garden_phone_text') || get_theme_mod('plant_garden_phone_number') ) : ?>
						<div class="row">
							<div class="col-lg-2 col-md-3 col-sm-3 align-self-center">
								<i class="fas fa-phone"></i>
							</div>
							<div class="col-lg-10 col-md-9 col-sm-9 align-self-center">
								<h6 class="mb-0"><?php echo esc_html( get_theme_mod('plant_garden_phone_text' ) ); ?></h6>
								<p class="mb-0"><?php echo esc_html( get_theme_mod('plant_garden_phone_number' ) ); ?></p>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 align-self-center">
		    		<div class="logo text-center">
			    		<div class="logo-image mr-3">
			    			<?php echo the_custom_logo(); ?>
				    	</div>
				    	<div class="logo-content">
					    	<?php
					    		if ( get_theme_mod('plant_garden_display_header_title', true) == true ) :
						      		echo '<a href="' . esc_url(home_url('/')) . '" title="' . esc_attr(get_bloginfo('name')) . '">';
						      			echo esc_attr(get_bloginfo('name'));
						      		echo '</a>';
						      	endif;

						      	if ( get_theme_mod('plant_garden_display_header_text', false) == true ) :
					      			echo '<span>'. esc_attr(get_bloginfo('description')) . '</span>';
					      		endif;
				    		?>
						</div>
					</div>
			   	</div>
				<div class="col-lg-4 col-md-4 col-sm-4 align-self-center text-md-right">
					<?php if ( class_exists( 'woocommerce' ) ) {?>
						<a class="cart-customlocation mr-3" href="<?php if(function_exists('wc_get_cart_url')){ echo esc_url(wc_get_cart_url()); } ?>" title="<?php esc_attr_e( 'View Shopping Cart','plant-garden' ); ?>"><i class="fas fa-shopping-cart mr-2"></i><?php esc_html_e('Cart','plant-garden'); ?></a>
					<?php }?>
					<?php if ( get_theme_mod('plant_garden_wishlist_link') ) : ?>
						<a href="<?php echo esc_url( get_theme_mod('plant_garden_wishlist_link' ) ); ?>" class="myacunt-url mr-3"><i class="far fa-heart mr-2"></i><?php esc_html_e('Wishlist','plant-garden'); ?></a>
					<?php endif; ?>
					<?php if ( get_theme_mod('plant_garden_myaccount_link') ) : ?>
						<a href="<?php echo esc_url( get_theme_mod('plant_garden_myaccount_link' ) ); ?>" class="myacunt-url mr-3"><i class="far fa-user mr-2"></i><?php esc_html_e('Login','plant-garden'); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<header id="site-navigation" class="header text-center text-md-left">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-8 col-sm-8 align-self-center">
					<?php if(has_nav_menu('main-menu')){ ?>
						<button class="menu-toggle my-2 py-2 px-3" aria-controls="top-menu" aria-expanded="false" type="button">
							<span aria-hidden="true"><?php esc_html_e( 'Menu', 'plant-garden' ); ?></span>
						</button>
						<nav id="main-menu" class="close-panal">
							<?php
								wp_nav_menu( array(
									'theme_location' => 'main-menu',
									'container' => 'false'
								));
							?>
							<button class="close-menu my-2 p-2" type="button">
								<span aria-hidden="true"><i class="fa fa-times"></i></span>
							</button>
						</nav>
					<?php }?>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 align-self-center pb-md-3 pb-lg-0">
			        <?php if(class_exists('woocommerce')){ ?>
		          		<form method="get" class="woocommerce-product-search" action="<?php echo esc_url(home_url('/')); ?>">
			            	<label class="screen-reader-text" for="woocommerce-product-search-field"><?php esc_html_e('Search for:', 'plant-garden'); ?></label>
				            <input type="search" id="woocommerce-product-search-field" class="search-field " placeholder="<?php echo esc_html('Search Here','plant-garden'); ?>" value="<?php echo get_search_query(); ?>" name="s"/>
				            <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
				            <input type="hidden" name="post_type" value="product"/>
			          	</form>
			        <?php }?>
				</div>
			</div>
		</div>
	</header>
</div>