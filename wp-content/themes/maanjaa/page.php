<?php get_header(); ?>

	<div class="row">

	<div class="col-md-12">
		
	<section class="padding-30 shadow-dark bg-white rounded">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>  

		<h1 class="mt-0 mb-4"><?php the_title(); ?></h1>

			<?php the_content(); ?>

			<?php endwhile;?>

			<?php else : ?>

			<p><?php esc_attr_e( 'No entry founds.', 'maanjaa' ); ?></p>   

			<?php endif; ?>

	</section>

	</div>

	</div>

<?php get_footer(); ?>