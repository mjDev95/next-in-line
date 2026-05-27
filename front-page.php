<?php get_header( 'home' ); ?>

<header class="nil-home" role="banner">

	<?php
	$terms = get_terms( array( 'taxonomy' => 'tipo-modelo', 'hide_empty' => false ) );
	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) :
	?>

	<div class="nil-categories-hero">

		<?php foreach ( $terms as $term ) :

			$models = get_posts( array(
				'post_type'      => 'modelos',
				'posts_per_page' => 1,
				'tax_query'      => array( array(
					'taxonomy' => 'tipo-modelo',
					'terms'    => $term->term_id,
				) ),
			) );

			$bg_url = '';
			if ( $models && has_post_thumbnail( $models[0]->ID ) ) {
				$img    = wp_get_attachment_image_src( get_post_thumbnail_id( $models[0]->ID ), 'large' );
				$bg_url = $img ? $img[0] : '';
			}
		?>

			<a href="<?php echo esc_url( get_term_link( $term ) ); ?>"
			   class="nil-cat-panel"
			   title="<?php echo esc_attr( $term->name ); ?> Models">

				<?php if ( $bg_url ) : ?>
					<div class="nil-cat-bg" style="background-image:url('<?php echo esc_url( $bg_url ); ?>')"></div>
				<?php endif; ?>

				<div class="nil-cat-overlay"></div>

				<div class="nil-cat-content">
					<?php if ( $term->count ) : ?>
						<span class="nil-cat-count">
							<?php echo absint( $term->count ); ?> <?php _e( 'Models', 'hello-elementor-child' ); ?>
						</span>
					<?php endif; ?>
					<h2 class="nil-cat-name"><?php echo esc_html( strtoupper( $term->name ) ); ?></h2>
					<span class="nil-cat-cta"><?php _e( 'View all', 'hello-elementor-child' ); ?> &rarr;</span>
				</div>

			</a>

		<?php endforeach; ?>

	</div>

	<?php endif; ?>

</header>

<?php get_footer( 'home' ); ?>
