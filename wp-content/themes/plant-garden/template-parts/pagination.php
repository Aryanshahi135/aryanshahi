<?php
	the_posts_pagination( array(
		'prev_text' => esc_html__( 'Previous page', 'plant-garden' ),
		'next_text' => esc_html__( 'Next page', 'plant-garden' ),
	) );