<?php /* Template Name: Page Brands */ get_header(); ?>

<?php

$terms = get_terms("pa_brands");

?>

<div class="row brands-list">
<?php

    foreach ( $terms as $term ) {

        $image_id = get_term_meta( $term->term_id, 'image', true );
        $image_data = wp_get_attachment_image_src( $image_id, 'full' );

        echo '<div class="col-6 col-md-4 col-lg-2"><div class="logo-item"><div class="inner">';
        echo '<a href="'.get_term_link($term->slug, $term->taxonomy).'">';

        if ( $image_data ) {
            echo '<img src="' . esc_url( $image_data[0] ) . '">';
        }
        echo '<span class="term-name">' . $term->name . '</span>';
        echo '</a>';
        echo '</div></div></div>';
    }

?>

</div>

<?php get_footer(); ?>