<?php
/*Plugin Name: K Témoignages
Description: CPT Témoignages avec template Gutenberg
Version: 1.0
License: GPLv2
Author: Magalie Castaing
*/

function k_register_temoignage_post_type() {
    $args = array(
        'public' => true,
        'label'  => 'Témoignages',
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-thumbs-up',
        'has_archive'  => true,
        'template' => array(
            array( 'core/quote', array(
                'className' => 'texte',
                'placeholder' => 'Contenu du témoignage',
            ) ),
            array( 'core/paragraph', array(
                'placeholder' => 'Nom du client',
                'className' => 'nom',
                'align' => 'right',

            ) ),
            array( 'core/paragraph', array(
                'className' => 'site',
                'placeholder' => 'Site du client',
                'align' => 'right',
                
            ) ),
        ),
    );
    register_post_type( 'temoignage', $args );
}
add_action( 'init', 'k_register_temoignage_post_type' );

/* Scripts du slider */
function k_slider_scripts() {
    wp_register_script( 'slider-siema', plugins_url( 'siema.min.js', __FILE__ ), array('jquery'), true);
    wp_register_script( 'slider', plugins_url( 'k-slider-scripts.js', __FILE__ ), array('jquery','slider-siema'), true);
    if (is_front_page()) {
        wp_enqueue_script( 'slider-siema');
        wp_enqueue_script( 'slider');
    }
  
}
add_action('wp_enqueue_scripts', 'k_slider_scripts');


/* Shortcode pour afficher le slider */
//Afficher les temoignages dans un slider sur la page d'accueil

add_shortcode( 'temoignages_accueil', 'k_temoignages_accueil' );

function k_temoignages_accueil() {

	$args = array(

		'post_type' => 'temoignage',

		'posts_per_page'=> 8,

		'order' => 'DESC',

		'orderby' => 'date',

	);

	$k_temoignages_recents = new WP_Query( $args );

	
    ob_start();

	if( $k_temoignages_recents->have_posts() ) {
        echo '<div class="siema">';

		while ( $k_temoignages_recents->have_posts() ) : $k_temoignages_recents->the_post();

            echo '<div class="temoignage">';
            the_content();
            echo '</div>';

		endwhile;

        echo '</div>';
        

	}

	wp_reset_postdata();

	return ob_get_clean();

	

}