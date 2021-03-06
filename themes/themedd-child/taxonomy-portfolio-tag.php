<?php
/**
 * Adapté de Archive-portfolio.php
 * 
 */

get_header(); 
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );?>

<header class="page-header pv-xs-2 pv-sm-3 pv-lg-4 center-xs">
	<div class="wrapper">
			<h1 class="title">
				<?php echo __('Nos réalisations','kasutan').'&nbsp;: '.$term->name;?>
			</h1>
		</div>
</header>

<div class="content-wrapper<?php echo themedd_wrapper_classes(); ?>">

	<div id="primary" class="content-area col-xs-12">
		<main id="main" class="site-main" >
			<?php
				if (function_exists('k_mosaique_portfolio')) :
					echo k_mosaique_portfolio(-1,$term->slug);
				
				elseif ( have_posts() ) :
					// Start the Loop.
					while ( have_posts() ) : the_post();

						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );

					endwhile;

					themedd_paging_nav();

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'template-parts/content', 'none' );

				endif;
			?>
		</main>
	</div>
</div>

<?php
get_footer();
