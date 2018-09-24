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
		'name'       => __( 'Objectif du projet', 'cmb2' ),
		'id'         => CMB_PREFIX . '_portfolio_objectif',
		'type'       => 'text',
	) );
	$cmb_portfolio->add_field( array(
		'name'       => __( 'URL du site live', 'cmb2' ),
		'id'         => CMB_PREFIX . '_portfolio_url',
		'type'       => 'text_url',
	) );
    $cmb_portfolio->add_field( array(
		'name'       => __( 'Texte du lien', 'cmb2' ),
		'id'         => CMB_PREFIX . '_portfolio_texte_url',
		'type'       => 'text',
	) );
	$cmb_portfolio->add_field( array(
		'name'       => __( 'Date', 'cmb2' ),
		'id'         => CMB_PREFIX . '_portfolio_date',
		'type'       => 'text',
    ) );
    $cmb_portfolio->add_field( array(
		'name'       => __( 'Note', 'cmb2' ),
		'id'         => CMB_PREFIX . '_portfolio_note',
		'type'       => 'textarea',
	) );
        /*
	$cmb_portfolio->add_field( array(
		'name'       => __( 'Mettre en avant dans les archives', 'cmb2' ),
		'id'         => CMB_PREFIX . '_portfolio_star',
		'type'       => 'checkbox',
		'default'	=> 1,
    ) );*/
    
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


/***************************************************************
    Affichage des projets en mosaique 
/***************************************************************/
//Fonction qui retourne les métas du projet
function k_meta_portfolio($ID,$couleur_icone,$toutes) {
    $tags = get_the_term_list($ID,'portfolio-tag','',',&ensp;','');
    $portfolio_url=esc_url(get_post_meta($ID, CMB_PREFIX.'_portfolio_url', true ));
    $portfolio_texte_url=esc_html(get_post_meta($ID, CMB_PREFIX.'_portfolio_texte_url', true ));
    $date=$objectif=$note='';
    if($toutes) {
        $date= esc_html(get_post_meta($ID, CMB_PREFIX.'_portfolio_date', true ));
        $objectif= esc_html(get_post_meta($ID, CMB_PREFIX.'_portfolio_objectif', true ));
        $note= esc_html(get_post_meta($ID, CMB_PREFIX.'_portfolio_note', true ));
    }
    

    ob_start();
    ?>
    <p class="meta">
        <?php if(!(empty($date))):?>
        <span>
            <img src="https://icongr.am/clarity/calendar.svg?color=<?php echo $couleur_icone;?>&size=24" alt="Date&nbsp;: "/>
            <?php echo $date; ?>
        </span>
        <?php endif; ?>
        
        <?php if(!(empty($objectif))):?>
        <span>
            <img src="https://icongr.am/clarity/bullseye.svg?color=<?php echo $couleur_icone;?>&size=24" alt="Objectif&nbsp;: "/>
            <?php echo $objectif; ?>
        </span>
        <?php endif; ?>
        
        <?php if(!(empty($tags))):?>
        <span>
            <img src="https://icongr.am/clarity/tags.svg?color=<?php echo $couleur_icone;?>&size=24" alt="Tags&nbsp;: "/>
            <?php echo $tags; ?>
        </span>
        <?php endif; ?>
        
        <?php if(!(empty($portfolio_url))):?>
        <span>
            <img src="https://icongr.am/clarity/link.svg?color=<?php echo $couleur_icone;?>&size=24" alt="URL du projet&nbsp;: "/>
            <a href="<?php echo $portfolio_url;?>" target="_blank">
                <?php
                if(!empty($portfolio_texte_url)) {
                    echo $portfolio_texte_url;
                } else {
                    echo __('URL du projet','kasutan');
                }?>
            </a>
        </span>
        <?php endif; ?>
       
        <?php if(!(empty($note))):?>
        <span>
            <img src="https://icongr.am/clarity/pin.svg?color=<?php echo $couleur_icone;?>&size=24" alt="Note&nbsp;: "/>
            <?php echo $note; ?>
        </span>
        <?php endif; ?>
    </p>
    <?php return ob_get_clean();
}
//Fonction qui _retourne_ une mosaique - à utiliser dans un shortocde pour la page d'accueil ou _avec un echo_ pour la page d'archive

function k_mosaique_portfolio($nombre_projets) {
    $args = array(
		'post_type' => 'portfolio',
		'posts_per_page'=> $nombre_projets,
		'order' => 'DESC',
		'orderby' => 'date',
    );
    
	$k_projets_recents = new WP_Query( $args );
	
    ob_start();

	if( $k_projets_recents->have_posts() ) {
        //Compteur pour afficher une image plus grande dans la mosaique si c'est le premier projet
        $i=0;
        echo '<div class="mosaique portfolio">';
        while ( $k_projets_recents->have_posts() ) : $k_projets_recents->the_post(); 
            $taille_image = 0==$i ? 'carre-800' : 'carre-400';
            ?>
            <figure tabindex="1">
                <?php 
					if ( has_post_thumbnail() ) {
                        ?>
                        <a href="<?php the_permalink();?>" class="lien-img">
                        <?php
                        the_post_thumbnail($taille_image);
                        ?>
                        </a>
                        <?php
					}
                ?>
                <figcaption>
                    <a href="<?php the_permalink();?>">
                    <?php if (is_front_page()) : ?>
                        <h3 class="titre"><?php the_title(); ?></h3>
                    <?php else : ?>
                        <h2 class="titre"><?php the_title(); ?></h2>
                    <?php endif; ?>
                    </a>
                    <?php echo k_meta_portfolio(get_the_ID(),'ffffff', false); ?>
                </figcaption>
        <?php
        $i++;
		endwhile;
        echo '</div>';
	}

	wp_reset_postdata();

	return ob_get_clean();
}

//Affiche la mosaique avec un shortcode
//utilisation : [mosaique_portfolio nombre_projets="2"]
function k_shortcode_mosaique_portfolio($atts) {
	extract( shortcode_atts( array(    
		'nombre_projets' => 6,    
		), $atts) );
	
			return k_mosaique_portfolio($nombre_projets) ;
		}
		
add_shortcode( 'mosaique_portfolio', 'k_shortcode_mosaique_portfolio' );

/***************************************************************
    Affichage des projets en colonne dans la barre latérale
/***************************************************************/
function k_widget_portfolio($nombre_projets) {
    //Si on est sur la page single d'un projet, ne pas afficher le projet dans la barre latérale
    $exclure = array();
    if (is_single() && 'portfolio' == get_post_type()) :
        $exclure[]= get_the_ID();
    endif;
    $args = array(
		'post_type' => 'portfolio',
		'posts_per_page'=> $nombre_projets,
		'order' => 'DESC',
        'orderby' => 'date',
        'post__not_in' => $exclure,
    );
    
	$k_projets_recents = new WP_Query( $args );
	
    ob_start();

	if( $k_projets_recents->have_posts() ) {
        echo '<div class="portfolio">';
        while ( $k_projets_recents->have_posts() ) : $k_projets_recents->the_post();
        ?>
        <figure>
                <?php 
					if ( has_post_thumbnail() ) {
                        ?>
                        <a href="<?php the_permalink();?>" class="lien-img">
                        <?php
                        the_post_thumbnail('thumbnail');
                        ?>
                        </a>
                        <?php
					}
                ?>
                <figcaption>
                    <a href="<?php the_permalink();?>">                    
                        <strong class="titre"><?php the_title(); ?></strong>          
                    </a>
                    <?php echo k_meta_portfolio(get_the_ID(),'222222', false); ?>
                </figcaption>
        <?php
		endwhile;
        echo '</div>';
	}

	wp_reset_postdata();

	return ob_get_clean();
}

//Affiche le widget avec un shortcode
//utilisation : [widget_portfolio nombre_projets="2"]
function k_shortcode_widget_portfolio($atts) {
	extract( shortcode_atts( array(    
		'nombre_projets' => 3,    
		), $atts) );
	
			return k_widget_portfolio($nombre_projets) ;
		}
		
add_shortcode( 'widget_portfolio', 'k_shortcode_widget_portfolio' );