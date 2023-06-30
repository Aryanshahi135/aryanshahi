<?php


$plant_garden_custom_css = '';

/*---------------------------text-transform-------------------*/

$plant_garden_text_transform = get_theme_mod( 'menu_text_transform_plant_garden','CAPITALISE');
if($plant_garden_text_transform == 'CAPITALISE'){

	$plant_garden_custom_css .='#main-menu ul li a{';

		$plant_garden_custom_css .='text-transform: capitalize ;';

	$plant_garden_custom_css .='}';

}else if($plant_garden_text_transform == 'UPPERCASE'){

	$plant_garden_custom_css .='#main-menu ul li a{';

		$plant_garden_custom_css .='text-transform: uppercase ;';

	$plant_garden_custom_css .='}';

}else if($plant_garden_text_transform == 'LOWERCASE'){

	$plant_garden_custom_css .='#main-menu ul li a{';

		$plant_garden_custom_css .='text-transform: lowercase ;';

	$plant_garden_custom_css .='}';
}

/*---------------------------Container Width-------------------*/

$plant_garden_container_width = get_theme_mod('plant_garden_container_width');

	$plant_garden_custom_css .='body{';

		$plant_garden_custom_css .='width: '.esc_attr($plant_garden_container_width).'%; margin: auto;';

	$plant_garden_custom_css .='}';

	/*---------------------------Slider-content-alignment-------------------*/

	$plant_garden_slider_content_alignment = get_theme_mod( 'plant_garden_slider_content_alignment','LEFT-ALIGN');

	 if($plant_garden_slider_content_alignment == 'LEFT-ALIGN'){

			$plant_garden_custom_css .='.blog_box{';

				$plant_garden_custom_css .='text-align:left;';

			$plant_garden_custom_css .='}';


		}else if($plant_garden_slider_content_alignment == 'CENTER-ALIGN'){

			$plant_garden_custom_css .='.blog_box{';

				$plant_garden_custom_css .='text-align:center;';

			$plant_garden_custom_css .='}';


		}else if($plant_garden_slider_content_alignment == 'RIGHT-ALIGN'){

			$plant_garden_custom_css .='.blog_box{';

				$plant_garden_custom_css .='text-align:right;';

			$plant_garden_custom_css .='}';

		}
