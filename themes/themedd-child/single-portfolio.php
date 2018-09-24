<?php
/**
 * Adapté de single.php
 */

get_header();
themedd_page_header();
?>

<?php do_action( 'themedd_single_start' ); ?>

<div class="content-wrapper<?php echo themedd_wrapper_classes(); ?>">
    <div id="primary" class="content-area<?php echo themedd_primary_classes(); ?>">
    	<main id="main" class="site-main" role="main">
		<div class="entry-content">

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();
				?>
				<header class="portfolio-header">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail('medium');
					}
					if(function_exists('k_meta_portfolio')) {
						echo k_meta_portfolio(get_the_ID(),'CE9FFC',$afficher_toutes_les_metas=true);
					}?>
				</header>
				<?php
				the_content();
				
					// Previous/next post navigation.
					the_post_navigation( array(
						'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'themedd' ) . '</span> ' .
							'<span class="screen-reader-text">' . __( 'Next post:', 'themedd' ) . '</span> ' .
							'<span class="post-title">%title</span>',
						'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'themedd' ) . '</span> ' .
							'<span class="screen-reader-text">' . __( 'Previous post:', 'themedd' ) . '</span> ' .
							'<span class="post-title">%title</span>',
					) );
			
				// End of the loop.
			endwhile;
			?>
		</div>

	</main>

    </div>

	<?php themedd_get_sidebar(); ?>

</div>

<?php get_footer(); ?>
