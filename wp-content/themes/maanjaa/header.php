<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php wp_head(); ?>
    
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<div id="canvas-filter" class="canvas-filter">
	<?php if ( is_active_sidebar( 'canvas-sidebar' ) ) : ?>
		<?php dynamic_sidebar( 'canvas-sidebar' ); ?>
	<?php endif; ?>
</div>

<div id="canvas-overlay" class="canvas-overlay"></div>

<div class="top-bar clearfix">
	<div class="container">
		<a href="" class="topbar-links float-right">Become a seller</a>
		<a href="" class="topbar-links float-right">Request for quotation</a>
	</div>
</div>

<div class="mobile-header">
	<div class="container">
		<div class="mobile-topbar">
			<div class="row">
				<div class="col">
					<?php
					if ( is_user_logged_in() ){
						$user = wp_get_current_user();
						$admin_role = array( 'administrator' );
						$seller_role = array( 'wcfm_vendor' );
						$seller_and_admin_role = array( 'administrator', 'wcfm_vendor' );
						$customer_role = array( 'customer' );
						?>
						<div class="dropdown d-inline align-middle">
							<button class="btn-user-menu dropdown-toggle" type="button" id="usermenuDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="ti-face-smile"></span>
								<?php 
									if(empty($user->user_firstname)) {
										printf( __( 'Hi, %s', 'maanjaa' ), esc_html( $user->user_login ) );
									} else {
										printf( __( 'Hi, %s', 'maanjaa' ), esc_html( $user->user_firstname ) ); 
									}
								?>
							</button>
							<div class="dropdown-menu" aria-labelledby="usermenuDropdown">
								<?php if ( array_intersect( $admin_role, $user->roles ) ) : ?>
									<a class="dropdown-item" href="<?php echo get_option("siteurl"); ?>/wp-admin" target="_blank">Dashboard</a>
								<?php endif; ?>
								<?php if ( array_intersect( $seller_and_admin_role, $user->roles ) ) : ?>
									<a class="dropdown-item" href="<?php echo get_option("siteurl"); ?>/store-manager" target="_blank">Store manager</a>
								<?php endif; ?>
								<?php if ( array_intersect( $customer_role, $user->roles ) ) : ?>
								<a class="dropdown-item" href="<?php echo get_option("siteurl"); ?>/my-account">My account</a>
								<a class="dropdown-item" href="<?php echo get_option("siteurl"); ?>/my-account/orders">My orders</a>
								<a class="dropdown-item" href="<?php echo get_option("siteurl"); ?>/my-account/edit-account">Edit account</a>
								<?php endif; ?>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo esc_url(wp_logout_url( home_url() )); ?>">Logout</a>
							</div>
						</div>
						<?php
					} else { ?>
						<a href="<?php echo get_option("siteurl"); ?>/my-account" class="mobile-topbar-links">Login / Register</a>
					<?php } ?>
				</div>
				<div class="col">
					<div class="float-right">
						<a href="<?php echo get_option("siteurl"); ?>/wishlist" class="mobile-topbar-links">Wishlist</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row header-content d-flex align-items-center">
			<div class="col-3">
				<?php wp_nav_menu( array( 'theme_location' => 'primary-menu' ) ); ?>
			</div>
			<div class="col-6 text-center">
				<a class="navbar-brand" href="<?php echo get_option("siteurl"); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="logo" /></a>
			</div>
			<div class="col-3">
				<div class="float-right">
					<?php do_action( 'maanjaa_theme_cart_icon' ); ?>
				</div>
			</div>
		</div>
		<div class="search-header">
			<form name="myform" method="GET" action="<?php echo esc_url(home_url('/'));?>" class="d-inline w-100">
				<div class="input-group">
					<?php if (class_exists('WooCommerce')): ?>
						<?php
						if (isset($_REQUEST['product_cat']) && !empty($_REQUEST['product_cat'])) {
							$optsetlect = $_REQUEST['product_cat'];
						} else {
							$optsetlect = 0;
						}
						$args = array(
							'show_option_all' => esc_html__('All Categories', 'maanjaa'),
							'hierarchical' => 1,
							'class' => 'cat',
							'echo' => 1,
							'value_field' => 'slug',
							'selected' => $optsetlect,
						);
						$args['taxonomy'] = 'product_cat';
						$args['name'] = 'product_cat';
						$args['class'] = 'cate-dropdown hidden-xs';
						wp_dropdown_categories($args);

						?>
						<input type="hidden" value="product" name="post_type">
					<?php endif;?>
					<input type="text"  name="s" class="form-control search-big" maxlength="128" value="<?php echo get_search_query();?>" placeholder="<?php esc_attr_e('What are you looking for...', 'maanjaa');?>" required>
					<button type="submit" title="<?php esc_attr_e('Search', 'maanjaa');?>" class="btn btn-default btn-sm"><span><?php esc_attr_e('Search', 'maanjaa');?></span></button>
				</div>
				
			</form>
		</div>
	</div>
</div>

<header class="site-header">
	<div class="header-top">
		<div class="container">
			<div class="d-flex justify-content-between align-items-center">
				<div class="">
					<a class="navbar-brand" href="<?php echo get_option("siteurl"); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="logo" /></a>
				</div>
				<div class="search-header flex-grow-1">
					<form name="myform" method="GET" action="<?php echo esc_url(home_url('/'));?>" class="d-inline w-100">
						<div class="input-group">
							<?php if (class_exists('WooCommerce')): ?>
							<?php
							if (isset($_REQUEST['product_cat']) && !empty($_REQUEST['product_cat'])) {
								$optsetlect = $_REQUEST['product_cat'];
							} else {
								$optsetlect = 0;
							}
							$args = array(
								'show_option_all' => esc_html__('All Categories', 'maanjaa'),
								'hierarchical' => 1,
								'class' => 'cat',
								'echo' => 1,
								'value_field' => 'slug',
								'selected' => $optsetlect,
							);
							$args['taxonomy'] = 'product_cat';
							$args['name'] = 'product_cat';
							$args['class'] = 'cate-dropdown hidden-xs';
							wp_dropdown_categories($args);

							?>
							<input type="hidden" value="product" name="post_type">
							<?php endif;?>
							<input type="text"  name="s" class="form-control search-big" maxlength="128" value="<?php echo get_search_query();?>" placeholder="<?php esc_attr_e('What are you looking for...', 'maanjaa');?>" required>

							<button type="submit" title="<?php esc_attr_e('Search', 'maanjaa');?>" class="btn btn-default btn-sm"><span><?php esc_attr_e('Search', 'maanjaa');?></span></button>
						</div>
						
					</form>
				</div>
				<div class="flex-grow-2">
					<?php
					if ( is_user_logged_in() ){
						$user = wp_get_current_user();
						$admin_role = array( 'administrator' );
						$seller_role = array( 'wcfm_vendor' );
						$seller_and_admin_role = array( 'administrator', 'wcfm_vendor' );
						$customer_role = array( 'customer' );
						?>
						<div class="dropdown d-inline align-middle">
							<button class="btn-user-menu dropdown-toggle ml-3" type="button" id="usermenuDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="ti-face-smile"></span>
								<?php 
									if(empty($user->user_firstname)) {
										printf( __( 'Hi, %s', 'maanjaa' ), esc_html( $user->user_login ) );
									} else {
										printf( __( 'Hi, %s', 'maanjaa' ), esc_html( $user->user_firstname ) ); 
									}
								?>
							</button>
							<div class="dropdown-menu" aria-labelledby="usermenuDropdown">
								<?php if ( array_intersect( $admin_role, $user->roles ) ) : ?>
									<a class="dropdown-item" href="<?php echo get_option("siteurl"); ?>/wp-admin" target="_blank">Dashboard</a>
								<?php endif; ?>
								<?php if ( array_intersect( $seller_and_admin_role, $user->roles ) ) : ?>
									<a class="dropdown-item" href="<?php echo get_option("siteurl"); ?>/store-manager" target="_blank">Store manager</a>
								<?php endif; ?>
								<?php if ( array_intersect( $customer_role, $user->roles ) ) : ?>
								<a class="dropdown-item" href="<?php echo get_option("siteurl"); ?>/my-account">My account</a>
								<a class="dropdown-item" href="<?php echo get_option("siteurl"); ?>/my-account/orders">My orders</a>
								<a class="dropdown-item" href="<?php echo get_option("siteurl"); ?>/my-account/edit-account">Edit account</a>
								<?php endif; ?>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo esc_url(wp_logout_url( home_url() )); ?>">Logout</a>
							</div>
						</div>
						<?php
					} else { ?>
						<a href="<?php echo get_option("siteurl"); ?>/my-account" class="btn btn-default ml-3">Login / Register</a>
					<?php } ?>
					
					<?php do_action( 'maanjaa_theme_cart_icon' ); ?>
					<?php echo do_shortcode("[ti_wishlist_products_counter]"); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<?php wp_nav_menu( array( 'theme_location' => 'primary-menu' ) ); ?>
	</div>
	
	<div class="container">
		<hr />
	</div>
</header>

<!-- main layout -->
<main class="">

    <div class="container">