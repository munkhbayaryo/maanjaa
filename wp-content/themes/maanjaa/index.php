<?php 

	get_header();

		echo '<div class="row">';

			get_template_part( 'loop' );

			if( get_theme_mod('blog_sidebar', true) == true ) {
				echo '<div class="col-md-4">';
					get_sidebar();
				echo '</div>';

			}

		echo '</div>';

	get_footer(); 

?>