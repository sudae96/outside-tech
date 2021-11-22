<?php get_header(); ?>
	<div class="container">
		<div class="row">
			<?php  
				// If there are any posts
				if( have_posts() ):

					// Loads Posts Loop
					while( have_posts() ): the_post();
						get_template_part( 'template-parts/content' );
					endwhile;
					the_posts_pagination( array( 
						'prev_text' => __('Previous', 'fancy-lab'), 
                        'next_text' => __('Next', 'fancy-lab')
					) );
				else:
			?>
				<p><?php _e('Nothing to display', 'fancy-lab'); ?></p>
			<?php endif; ?>
		</div>
	</div>
<?php get_footer(); ?>