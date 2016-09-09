<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
get_header(); 
// gu_login_check();

?>

	<div id="primary" class="content-area">
		<!-- <main id="main" class="site-main" role="main"> -->

		<?php if ( have_posts() ) : ?>
			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php

			// Start the loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				// get_template_part( 'template-parts/content', get_post_format() );
				// get_template_part( 'template-parts/content-postmember', htmlspecialchars(get_post_format(), ENT_QUOTES, 'utf-8'));
				// get_template_part( 'template-parts/content-postmember', wp_kses_post(get_post_format()) );
				
				get_template_part( 'template-parts/content-postmember', htmlspecialchars(get_post_format()) );
				
				// get_template_part( 'template-parts/content-postmember', wp_specialchars(esc_html(get_post_format()), ENT_QUOTES, 'utf-8'));
				
			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'twentysixteen' ),
				'next_text'          => __( 'Next page', 'twentysixteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			// Get the templatefile from the content.php
			get_template_part( 'template-parts/content', 'none' );
		endif;
		?>
		<!-- </main> --><!-- .site-main -->
	</div><!-- .content-area -->
	
<?php get_sidebar(); ?></div>

<br>
<!-- MEMBERS SHOW: -->
<div class="container">
	<ul class="media-list" id="userlist">
	<div class="row">
	<?php
		// instantiate class rowbuilder and build rows from class first time while loading page
		echo gu_wp_show_members();
	?>
	</div>
	</ul>
</div>
<!-- END MEMBERS SHOW: -->

<?php get_footer(); ?>
