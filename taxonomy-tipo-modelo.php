<?php get_header(); ?>

<?php $term = get_queried_object(); ?>

<main class="nil-archive-modelos nil-taxonomy-modelos">

	<header class="nil-archive-header">
		<p class="nil-breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'hello-elementor-child' ); ?></a>
			<span>&nbsp;/&nbsp;</span>
			<span><?php echo esc_html( $term->name ); ?></span>
		</p>
		<h1 class="nil-archive-title"><?php echo esc_html( strtoupper( $term->name ) ); ?></h1>
		<?php if ( $term->description ) : ?>
			<p class="nil-archive-desc"><?php echo esc_html( $term->description ); ?></p>
		<?php endif; ?>
	</header>

	<div class="nil-models-grid">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<a href="<?php the_permalink(); ?>"
			   class="nil-model-card"
			   title="<?php echo esc_attr( sprintf(
			   		/* translators: 1: nombre del modelo, 2: nombre de la categoría */
			   		__( '%1$s — Modelo %2$s', 'hello-elementor-child' ),
			   		get_the_title(),
			   		$term->name
			   ) ); ?>">
				<div class="nil-model-thumb">
					<?php the_post_thumbnail( 'large' ); ?>
					<div class="nil-model-overlay">
						<span class="nil-model-name"><?php the_title(); ?></span>
					</div>
				</div>
			</a>
		<?php endwhile; endif; ?>
	</div>

	<?php the_posts_pagination( array( 'prev_text' => '&larr;', 'next_text' => '&rarr;' ) ); ?>

	<?php
	/* ── SEO Interlinking: otras categorías ── */
	$other_terms = get_terms( array(
		'taxonomy'   => 'tipo-modelo',
		'hide_empty' => true,
		'exclude'    => $term->term_id,
	) );

	if ( ! is_wp_error( $other_terms ) && ! empty( $other_terms ) ) :
	?>

	<section class="nil-related-cats" aria-label="<?php esc_attr_e( 'Otras divisiones de modelos', 'hello-elementor-child' ); ?>">

		<h2 class="nil-related-cats-title"><?php esc_html_e( 'Descubre otras divisiones', 'hello-elementor-child' ); ?></h2>

		<div class="nil-related-cats-grid">
			<?php foreach ( $other_terms as $other ) :

				$other_models = get_posts( array(
					'post_type'      => 'modelos',
					'posts_per_page' => -1,
					'tax_query'      => array( array(
						'taxonomy' => 'tipo-modelo',
						'terms'    => $other->term_id,
					) ),
				) );

				$bg_url = '';
				if ( $other_models && has_post_thumbnail( $other_models[0]->ID ) ) {
					$img    = wp_get_attachment_image_src( get_post_thumbnail_id( $other_models[0]->ID ), 'medium_large' );
					$bg_url = $img ? $img[0] : '';
				}
			?>

				<a href="<?php echo esc_url( get_term_link( $other ) ); ?>"
				   class="nil-related-cat-card"
				   title="<?php echo esc_attr( sprintf(
				   		/* translators: %s: nombre de la categoría */
				   		__( 'Modelos %s', 'hello-elementor-child' ),
				   		$other->name
				   ) ); ?>">

					<?php if ( $bg_url ) : ?>
						<div class="nil-cat-bg" style="background-image:url('<?php echo esc_url( $bg_url ); ?>')"></div>
					<?php endif; ?>
					<div class="nil-cat-overlay"></div>
					<div class="nil-related-cat-info">
						<span class="nil-related-cat-name"><?php echo esc_html( strtoupper( $other->name ) ); ?></span>
						<span class="nil-related-cat-count"><?php echo absint( $other->count ); ?> <?php esc_html_e( 'Modelos', 'hello-elementor-child' ); ?></span>
					</div>

				</a>

			<?php endforeach; ?>
		</div>

	</section>

	<?php endif; ?>

</main>

<?php get_footer(); ?>
