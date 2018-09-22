<?php
/*Plugin Name: MC Fonctions de base
Description: Améliorations à mettre en place sur tous les sites
Version: 1.3
License: GPLv2
Author: Magalie Castaing
*/

/*
Changelog
1.3 - Modif code pour mise à jour auto du thème
1.2 - 29/05/2018 - Affiche l'ID de l'objet dans l'admin
1.1 - 18/05/2018 - Mise à jour automatique de WordPress et du thème TwentySeventeen
*/

/***************************************************************
Remove WP compression for images - there's a plugin for that
***************************************************************/
add_filter( 'jpeg_quality', 'smashing_jpeg_quality' );
function smashing_jpeg_quality() {
return 100;
}

/***************************************************************
                 Remove image link
***************************************************************/
function wpb_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	
	if ($image_set !== 'none') {
		update_option('image_default_link_type', 'none');
	}
}
add_action('admin_init', 'wpb_imagelink_setup', 10);

/***************************************************************
    Enable shortcodes in widgets
/***************************************************************/
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter('widget_text','do_shortcode');

/***************************************************************
                        Clean header
/***************************************************************/
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
//Si on n'utilise pas les commentaires :
function clean_header(){ wp_deregister_script( 'comment-reply' ); } add_action('init','clean_header');

/***************************************************************
    Hide admin author page
/***************************************************************/
function bwp_template_redirect()
{
if (is_author())
{
wp_redirect( home_url() ); exit;
}
}
add_action('template_redirect', 'bwp_template_redirect');

/***************************************************************
        	Afficher l'adresse mail via un shortcode
***************************************************************/

function mc_adresse_email($atts) {
	extract( shortcode_atts( array(    
		'mail' => ' ',    
		), $atts) );
	
			return (antispambot($mail));
		}
		
add_shortcode( 'adresse-email', 'mc_adresse_email' );

/***************************************************************
    Mise à jour automatique des extensions
/***************************************************************/
add_filter( 'auto_update_plugin', '__return_true' );

add_filter( 'allow_minor_auto_core_updates', '__return_true' );         // Enable minor updates
add_filter( 'allow_major_auto_core_updates', '__return_true' );         // Enable major updates
function auto_update_specific_themes ( $update, $item ) {
    // Mettre à jour automatiquement les thèmes sauf le thème lapeyre
    if ( $item->slug == 'lapeyre') {
        return false; 
    } else {
        return true;
    }
}
add_filter( 'auto_update_theme', 'auto_update_specific_themes', 10, 2 );

/***************************************************************
    Affiche l'ID de l'objet dans l'admin
/***************************************************************/
/* cf. https://premium.wpmudev.org/blog/display-wordpress-post-page-ids/ */
add_filter( 'manage_posts_columns', 'revealid_add_id_column', 5 );
add_action( 'manage_posts_custom_column', 'revealid_id_column_content', 5, 2 );
add_filter( 'manage_pages_columns', 'revealid_add_id_column' , 5);
add_action( 'manage_pages_custom_column', 'revealid_id_column_content', 5, 2  );

$custom_post_types = get_post_types( 
    array( 
       'public'   => true, 
       '_builtin' => false 
    ), 
    'names'
 ); 
 
 foreach ( $custom_post_types as $post_type ) {
     add_action( 'manage_edit-'. $post_type . '_columns', 'revealid_add_id_column', 5 );
     add_filter( 'manage_'. $post_type . '_custom_column', 'revealid_id_column_content', 5, 2 );
 }

function revealid_add_id_column( $columns ) {
   $columns['revealid_id'] = 'ID';
   return $columns;
}

function revealid_id_column_content( $column, $id ) {
  if( 'revealid_id' == $column ) {
    echo $id;
  }
}

/***************************************************************
    Créer une révision quand un post est mis à jour même si le titre 
    ou le contenu n'a pas changé (permet de suivre les modifs des champs cmb2)
/***************************************************************/

add_filter('wp_save_post_revision_check_for_changes','mc_forcer_revisions', 10, 3);
function mc_forcer_revisions($check_for_changes, $last_revision, $post ) {
    return false;
}