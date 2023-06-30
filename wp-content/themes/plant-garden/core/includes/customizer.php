<?php

if ( class_exists("Kirki")){

	// LOGO

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'slider',
		'settings'    => 'plant_garden_logo_resizer',
		'label'       => esc_html__( 'Adjust Your Logo Size ', 'plant-garden' ),
		'section'     => 'title_tagline',
		'default'     => 70,
		'choices'     => [
			'min'  => 10,
			'max'  => 300,
			'step' => 10,
		],
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_enable_logo_text',
		'section'     => 'title_tagline',
			'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'Enable / Disable Site Title and Tagline', 'plant-garden' ) . '</h3>',
		'priority'    => 10,
	] );

    Kirki::add_field( 'theme_config_id', [
		'type'        => 'switch',
		'settings'    => 'plant_garden_display_header_title',
		'label'       => esc_html__( 'Site Title Enable / Disable Button', 'plant-garden' ),
		'section'     => 'title_tagline',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'plant-garden' ),
			'off' => esc_html__( 'Disable', 'plant-garden' ),
		],
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'switch',
		'settings'    => 'plant_garden_display_header_text',
		'label'       => esc_html__( 'Tagline Enable / Disable Button', 'plant-garden' ),
		'section'     => 'title_tagline',
		'default'     => false,
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'plant-garden' ),
			'off' => esc_html__( 'Disable', 'plant-garden' ),
		],
	] );

	// FONT STYLE TYPOGRAPHY

	Kirki::add_panel( 'plant_garden_panel_id', array(
	    'priority'    => 10,
	    'title'       => esc_html__( 'Typography', 'plant-garden' ),
	) );

	Kirki::add_section( 'plant_garden_font_style_section', array(
		'title'      => esc_attr__( 'Typography Option',  'plant-garden' ),
		'priority'   => 2,
		'capability' => 'edit_theme_options',
	) );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_all_headings_typography',
		'section'     => 'plant_garden_font_style_section',
			'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'Heading Of All Sections',  'plant-garden' ) . '</h3>',
		'priority'    => 10,
	] );

	Kirki::add_field( 'global', array(
		'type'        => 'typography',
		'settings'    => 'plant_garden_all_headings_typography',
		'label'       => esc_attr__( 'Heading Typography',  'plant-garden' ),
		'description' => esc_attr__( 'Select the typography options for your heading.',  'plant-garden' ),
		'section'     => 'plant_garden_font_style_section',
		'priority'    => 10,
		'default'     => array(
			'font-family'    => '',
			'variant'        => '',
		),
		'output' => array(
			array(
				'element' => array( 'h1','h2','h3','h4','h5','h6', ),
			),
		),
	) );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_body_content_typography',
		'section'     => 'plant_garden_font_style_section',
			'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'Body Content',  'plant-garden' ) . '</h3>',
		'priority'    => 10,
	] );

	Kirki::add_field( 'global', array(
		'type'        => 'typography',
		'settings'    => 'plant_garden_body_content_typography',
		'label'       => esc_attr__( 'Content Typography',  'plant-garden' ),
		'description' => esc_attr__( 'Select the typography options for your content.',  'plant-garden' ),
		'section'     => 'plant_garden_font_style_section',
		'priority'    => 10,
		'default'     => array(
			'font-family'    => '',
			'variant'        => '',
		),
		'output' => array(
			array(
				'element' => array( 'body', ),
			),
		),
	) );

	// PANEL

	Kirki::add_panel( 'plant_garden_panel_id', array(
	    'priority'    => 10,
	    'title'       => esc_html__( 'Theme Options', 'plant-garden' ),
	) );

	// Additional Settings

	Kirki::add_section( 'plant_garden_additional_settings', array(
	    'title'          => esc_html__( 'Additional Settings', 'plant-garden' ),
	    'description'    => esc_html__( 'Scroll To Top', 'plant-garden' ),
	    'panel'          => 'plant_garden_panel_id',
	    'priority'       => 160,
	) );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'toggle',
		'settings'    => 'plant_garden_scroll_enable_setting',
		'label'       => esc_html__( 'Here you can enable or disable your scroller.', 'plant-garden' ),
		'section'     => 'plant_garden_additional_settings',
		'default'     => '1',
		'priority'    => 10,
	] );

	new \Kirki\Field\Select(
	[
		'settings'    => 'menu_text_transform_plant_garden',
		'label'       => esc_html__( 'Menus Text Transform', 'plant-garden' ),
		'section'     => 'plant_garden_additional_settings',
		'default'     => 'CAPITALISE',
		'placeholder' => esc_html__( 'Choose an option', 'plant-garden' ),
		'choices'     => [
			'CAPITALISE' => esc_html__( 'CAPITALISE', 'plant-garden' ),
			'UPPERCASE' => esc_html__( 'UPPERCASE', 'plant-garden' ),
			'LOWERCASE' => esc_html__( 'LOWERCASE', 'plant-garden' ),

		],
	]
	);

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'slider',
		'settings'    => 'plant_garden_container_width',
		'label'       => esc_html__( 'Theme Container Width', 'plant-garden' ),
		'section'     => 'plant_garden_additional_settings',
		'default'     => 100,
		'choices'     => [
			'min'  => 50,
			'max'  => 100,
			'step' => 1,
		],
	] );

	// Woocommerce Settings

	Kirki::add_section( 'plant_garden_woocommerce_settings', array(
			'title'          => esc_html__( 'Woocommerce Settings', 'plant-garden' ),
			'description'    => esc_html__( 'Shop Page', 'plant-garden' ),
			'panel'          => 'plant_garden_panel_id',
			'priority'       => 160,
	) );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'toggle',
		'settings'    => 'plant_garden_shop_sidebar',
		'label'       => esc_html__( 'Here you can enable or disable shop page sidebar.', 'plant-garden' ),
		'section'     => 'plant_garden_woocommerce_settings',
		'default'     => '1',
		'priority'    => 10,
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'toggle',
		'settings'    => 'plant_garden_product_sidebar',
		'label'       => esc_html__( 'Here you can enable or disable product page sidebar.', 'plant-garden' ),
		'section'     => 'plant_garden_woocommerce_settings',
		'default'     => '1',
		'priority'    => 10,
	] );

	// POST SECTION

	Kirki::add_section( 'plant_garden_section_post', array(
	    'title'          => esc_html__( 'Post Settings', 'plant-garden' ),
	    'description'    => esc_html__( 'Here you can get different post settings', 'plant-garden' ),
	    'panel'          => 'plant_garden_panel_id',
	    'priority'       => 160,
	) );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_enable_post_heading',
		'section'     => 'plant_garden_section_post',
		'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'Enable / Disable Post Settings.', 'plant-garden' ) . '</h3>',
		'priority'    => 10,
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'switch',
		'settings'    => 'plant_garden_blog_admin_enable',
		'label'       => esc_html__( 'Post Author Enable / Disable Button', 'plant-garden' ),
		'section'     => 'plant_garden_section_post',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'plant-garden' ),
			'off' => esc_html__( 'Disable', 'plant-garden' ),
		],
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'switch',
		'settings'    => 'plant_garden_blog_comment_enable',
		'label'       => esc_html__( 'Post Comment Enable / Disable Button', 'plant-garden' ),
		'section'     => 'plant_garden_section_post',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'plant-garden' ),
			'off' => esc_html__( 'Disable', 'plant-garden' ),
		],
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'slider',
		'settings'    => 'plant_garden_post_excerpt_number_1',
		'label'       => esc_html__( 'Post Content Range', 'plant-garden' ),
		'section'     => 'plant_garden_section_post',
		'default'     => 15,
		'choices'     => [
			'min'  => 10,
			'max'  => 100,
			'step' => 1,
		],
	] );

	// HEADER SECTION

	Kirki::add_section( 'plant_garden_section_header', array(
	    'title'          => esc_html__( 'Header Settings', 'plant-garden' ),
	    'description'    => esc_html__( 'Here you can add header information.', 'plant-garden' ),
	    'panel'          => 'plant_garden_panel_id',
	    'priority'       => 160,
	) );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_phone_text_heading',
		'section'     => 'plant_garden_section_header',
		'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'Phone Number', 'plant-garden' ) . '</h3>',
		'priority'    => 10,
	] );

    Kirki::add_field( 'theme_config_id', [
		'type'     => 'text',
		'label'       => esc_html__( 'Phone Text', 'plant-garden' ),
		'settings' => 'plant_garden_phone_text',
		'section'  => 'plant_garden_section_header',
		'default'  => '',
		'priority' => 10,
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'     => 'text',
		'label'       => esc_html__( 'Phone Number', 'plant-garden' ),
		'settings' => 'plant_garden_phone_number',
		'section'  => 'plant_garden_section_header',
		'default'  => '',
		'priority' => 10,
		'sanitize_callback' => 'plant_garden_sanitize_phone_number',
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_wishlist_link_heading',
		'section'     => 'plant_garden_section_header',
		'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'Wishlist URL', 'plant-garden' ) . '</h3>',
		'priority'    => 10,
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'     => 'url',
		'settings' => 'plant_garden_wishlist_link',
		'section'  => 'plant_garden_section_header',
		'default'  => '',
		'priority' => 10,
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_myaccount_link_heading',
		'section'     => 'plant_garden_section_header',
		'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'My Account URL', 'plant-garden' ) . '</h3>',
		'priority'    => 10,
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'     => 'url',
		'settings' => 'plant_garden_myaccount_link',
		'section'  => 'plant_garden_section_header',
		'default'  => '',
		'priority' => 10,
	] );

	// SLIDER SECTION

	Kirki::add_section( 'plant_garden_blog_slide_section', array(
        'title'          => esc_html__( ' Slider Settings', 'plant-garden' ),
        'description'    => esc_html__( 'You have to select post category to show slider.', 'plant-garden' ),
        'panel'          => 'plant_garden_panel_id',
        'priority'       => 160,
    ) );

    Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_enable_heading',
		'section'     => 'plant_garden_blog_slide_section',
			'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'Enable / Disable Slider', 'plant-garden' ) . '</h3>',
		'priority'    => 10,
	] );

    Kirki::add_field( 'theme_config_id', [
		'type'        => 'switch',
		'settings'    => 'plant_garden_blog_box_enable',
		'label'       => esc_html__( 'Section Enable / Disable', 'plant-garden' ),
		'section'     => 'plant_garden_blog_slide_section',
		'default'     => '0',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'plant-garden' ),
			'off' => esc_html__( 'Disable', 'plant-garden' ),
		],
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'switch',
		'settings'    => 'plant_garden_title_unable_disable',
		'label'       => esc_html__( 'Slide Title Enable / Disable', 'plant-garden' ),
		'section'     => 'plant_garden_blog_slide_section',
		'default'     => '0',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'plant-garden' ),
			'off' => esc_html__( 'Disable', 'plant-garden' ),
		],
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'switch',
		'settings'    => 'plant_garden_text_unable_disable',
		'label'       => esc_html__( 'Slide Text Enable / Disable', 'plant-garden' ),
		'section'     => 'plant_garden_blog_slide_section',
		'default'     => '0',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'plant-garden' ),
			'off' => esc_html__( 'Disable', 'plant-garden' ),
		],
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'switch',
		'settings'    => 'plant_garden_button_unable_disable',
		'label'       => esc_html__( 'Slide Button Enable / Disable', 'plant-garden' ),
		'section'     => 'plant_garden_blog_slide_section',
		'default'     => '0',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'plant-garden' ),
			'off' => esc_html__( 'Disable', 'plant-garden' ),
		],
	] );

    Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_slider_heading',
		'section'     => 'plant_garden_blog_slide_section',
			'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'Slider', 'plant-garden' ) . '</h3>',
		'priority'    => 10,
	] );

    Kirki::add_field( 'theme_config_id', [
		'type'        => 'number',
		'settings'    => 'plant_garden_blog_slide_number',
		'label'       => esc_html__( 'Number of slides to show', 'plant-garden' ),
		'section'     => 'plant_garden_blog_slide_section',
		'default'     => 3,
		'choices'     => [
			'min'  => 0,
			'max'  => 5,
			'step' => 1,
		],
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'select',
		'settings'    => 'plant_garden_blog_slide_category',
		'label'       => esc_html__( 'Select the category to show slider ( Image Dimension 1600 x 600 )', 'plant-garden' ),
		'section'     => 'plant_garden_blog_slide_section',
		'default'     => '',
		'placeholder' => esc_html__( 'Select an category...', 'plant-garden' ),
		'priority'    => 10,
		'choices'     => plant_garden_get_categories_select(),
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_slider_short_heading_12',
		'section'     => 'plant_garden_blog_slide_section',
			'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'Number of Text', 'plant-garden' ) . '</h3>',
		'priority'    => 10,
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'slider',
		'settings'    => 'plant_garden_post_excerpt_number',
		'label'       => esc_html__( 'Number of Slide Text To Show ', 'plant-garden' ),
		'section'     => 'plant_garden_blog_slide_section',
		'default'     => 22,
		'choices'     => [
			'min'  => 10,
			'max'  => 50,
			'step' => 1,
		],
	] );

	new \Kirki\Field\Select(
	[
		'settings'    => 'plant_garden_slider_content_alignment',
		'label'       => esc_html__( 'Slider Content Alignment', 'plant-garden' ),
		'section'     => 'plant_garden_blog_slide_section',
		'default'     => 'LEFT-ALIGN',
		'placeholder' => esc_html__( 'Choose an option', 'plant-garden' ),
		'choices'     => [
			'LEFT-ALIGN' => esc_html__( 'LEFT-ALIGN', 'plant-garden' ),
			'CENTER-ALIGN' => esc_html__( 'CENTER-ALIGN', 'plant-garden' ),
			'RIGHT-ALIGN' => esc_html__( 'RIGHT-ALIGN', 'plant-garden' ),

		],
	] );

	// HOT PRODUCTS SECTION

	Kirki::add_section( 'plant_garden_hot_products_section', array(
        'title'          => esc_html__( 'Hot Products Settings', 'plant-garden' ),
        'description'    => esc_html__( 'You have to select product category to show cosmetic section.', 'plant-garden' ),
        'panel'          => 'plant_garden_panel_id',
        'priority'       => 160,
    ) );

    Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_hot_products_section_enable_heading',
		'section'     => 'plant_garden_hot_products_section',
			'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'Enable / Disable Hot Products Section', 'plant-garden' ) . '</h3>',
		'priority'    => 1,
	] );

    Kirki::add_field( 'theme_config_id', [
		'type'        => 'switch',
		'settings'    => 'plant_garden_hot_products_section_enable',
		'label'       => esc_html__( 'Section Enable / Disable', 'plant-garden' ),
		'section'     => 'plant_garden_hot_products_section',
		'default'     => '0',
		'priority'    => 2,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'plant-garden' ),
			'off' => esc_html__( 'Disable', 'plant-garden' ),
		],
	] );

	Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_hot_products_heading',
		'section'     => 'plant_garden_hot_products_section',
			'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'Hot Products Headings',  'plant-garden' ) . '</h3>',
		'priority'    => 3,
	] );

    Kirki::add_field( 'theme_config_id', [
		'type'     => 'text',
		'settings' => 'plant_garden_hot_products_main_heading',
		'label'    => esc_html__( 'Main Heading', 'plant-garden' ),
		'section'  => 'plant_garden_hot_products_section',
		'priority' => 5,
    ] );

    Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_game_product_heading',
		'section'     => 'plant_garden_hot_products_section',
			'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'Hot Products', 'plant-garden' ) . '</h3>',
		'priority'    => 7,
	] );

    Kirki::add_field( 'theme_config_id', [
		'type'        => 'number',
		'settings'    => 'plant_garden_hot_products_per_page',
		'label'       => esc_html__( 'Number of products to show', 'plant-garden' ),
		'section'     => 'plant_garden_hot_products_section',
		'default'     => 8,
		'choices'     => [
			'min'  => 0,
			'max'  => 25,
			'step' => 1,
		],
	] );

	// FOOTER SECTION

	Kirki::add_section( 'plant_garden_footer_section', array(
        'title'          => esc_html__( 'Footer Settings', 'plant-garden' ),
        'description'    => esc_html__( 'Here you can change copyright text', 'plant-garden' ),
        'panel'          => 'plant_garden_panel_id',
        'priority'       => 160,
    ) );

    Kirki::add_field( 'theme_config_id', [
		'type'        => 'custom',
		'settings'    => 'plant_garden_footer_text_heading',
		'section'     => 'plant_garden_footer_section',
			'default'         => '<h3 style="color: #2271b1; padding:10px; background:#fff; margin:0; border-left: solid 5px #2271b1; ">' . __( 'Footer Copyright Text', 'plant-garden' ) . '</h3>',
		'priority'    => 10,
	] );

    Kirki::add_field( 'theme_config_id', [
		'type'     => 'text',
		'settings' => 'plant_garden_footer_text',
		'section'  => 'plant_garden_footer_section',
		'default'  => '',
		'priority' => 10,
	] );
}

add_action( 'customize_register', 'plant_garden_customizer_settings' );
function plant_garden_customizer_settings( $wp_customize ) {
	//Hot Products Section
	$plant_garden_args = array(
       'type'                     => 'product',
        'child_of'                 => 0,
        'parent'                   => '',
        'orderby'                  => 'term_group',
        'order'                    => 'ASC',
        'hide_empty'               => false,
        'hierarchical'             => 1,
        'number'                   => '',
        'taxonomy'                 => 'product_cat',
        'pad_counts'               => false
    );
	$categories = get_categories($plant_garden_args);
	$cat_posts = array();
	$m = 0;
	$cat_posts[]='Select';
	foreach($categories as $category){
		if($m==0){
			$default = $category->slug;
			$m++;
		}
		$cat_posts[$category->slug] = $category->name;
	}

	$wp_customize->add_setting('plant_garden_hot_products_category',array(
		'default'	=> 'select',
		'sanitize_callback' => 'plant_garden_sanitize_select',
	));
	$wp_customize->add_control('plant_garden_hot_products_category',array(
		'type'    => 'select',
		'choices' => $cat_posts,
		'label' => __('Select category to display games ','plant-garden'),
		'section' => 'plant_garden_hot_products_section',
	));
}
