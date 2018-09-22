<?php
/*Plugin Name: K Portfolio
Description: CPT Portfolio avec custom fields CMB2
Version: 1.0
License: GPLv2
Author: Magalie Castaing
*/

/***************************************************************
    Custom Taxonomy 
/***************************************************************/

//hook into the init action and call the function when it fires
add_action( 'init', 'create_type_portfolio_tag', 0 );
function create_type_portfolio_tag() {
  register_taxonomy('portfolio-tag','portfolio',array(
    'hierarchical' => false,
    'show_ui' => true,
    'show_admin_column' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'show_in_rest' => true,
  ));
}

/***************************************************************
    Custom Post Type 
/***************************************************************/

function k_register_portfolio_post_type() {
    $args = array(
        'public' => true,
        'label'  => 'Portfolio',
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-portfolio',
        'has_archive'  => true,
        'taxonomies'  => array('portfolio-tag'),
        'supports'  => array('title','editor','thumbnail','revisions','author'),
        'template' => array(
           
            array( 'core/paragraph', array(
                'placeholder' => 'Intro',
            ) ),
            array( 'core/heading', array(
                'content'   => 'Détail de la mission',
            ) ),
            array( 'core/list', array(
                'placeholder' => 'Détails',
            ) ),
            array( 'core/heading', array(
                'content'   => 'Témoignage du client',

            ) ),
            array( 'core/quote', array(
                'placeholder' => 'Témoignage',
            ) ),
            array( 'core/gallery', array(
                'placeholder' => 'Galerie',
                
            ) ),
        ),
    );
    register_post_type( 'portfolio', $args );
}
add_action( 'init', 'k_register_portfolio_post_type' );


/***************************************************************
    Custom Fields avec CMB2 
/***************************************************************/
if ( ! defined( 'CMB_PREFIX' ) ) {
    define( 'CMB_PREFIX', 'knew_' );
}

add_action( 'cmb2_admin_init', function() {

	$cmb_portfolio = new_cmb2_box( array(
		'id'            => 'portfolio',
		'title'         => __( 'Détails du projet', 'cmb2' ),
		'object_types' => array( 'portfolio' ), // post type
		'context'       => 'side',
		'show_names'    => true, 
		'closed'     => false,
	) );

	$cmb_portfolio->add_field( array(
		'name'       => __( 'URL du site live', 'cmb2' ),
		'id'         => CMB_PREFIX . '_portfolio_url',
		'type'       => 'text_url',
	) );

	$cmb_portfolio->add_field( array(
		'name'       => __( 'Date', 'cmb2' ),
		'id'         => CMB_PREFIX . '_portfolio_date',
		'type'       => 'text',
	) );

	$cmb_portfolio->add_field( array(
		'name'       => __( 'Mettre en avant dans les archives', 'cmb2' ),
		'id'         => CMB_PREFIX . '_portfolio_star',
		'type'       => 'checkbox',
		'default'	=> 1,
    ) );
    
    /*
    //https://github.com/alexis-magina/cmb2-field-post-search-ajax/blob/master/example-field-setup.php
    $cmb_portfolio->add_field( array(
		'name'      	=> __( 'Lier un témoignage', 'cmb2' ),
		'id'        	=> CMB_PREFIX . '_portfolio_temoignage',
		'type'      	=> 'post_search_ajax',
		'desc'			=> __( '(Start typing post title)', 'cmb2' ),
		// Optional :
		'limit'      	=> 1, 		// Limit selection to X items only (default 1)
		'sortable' 	 	=> false, 	// Allow selected items to be sortable (default false)
		'query_args'	=> array(
			'post_type'			=> array( 'temoignage' ),
			'post_status'		=> array( 'publish' ),
			'posts_per_page'	=> -1
		)
	) );*/
	
});


