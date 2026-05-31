<?php get_header(); the_post(); ?>

<main class="nil-single-modelo">

	<nav class="nil-breadcrumb" aria-label="<?php esc_attr_e( 'Ruta de navegación', 'hello-elementor-child' ); ?>">
		<?php if ( function_exists( 'yoast_breadcrumb' ) ) :
			yoast_breadcrumb( '<div class="nil-breadcrumb-inner">', '</div>' );
		else : ?>
			<div class="nil-breadcrumb-inner">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'hello-elementor-child' ); ?></a>
				<?php
				$bc_terms = get_the_terms( get_the_ID(), 'tipo-modelo' );
				if ( $bc_terms && ! is_wp_error( $bc_terms ) ) : ?>
					<span class="nil-bc-sep" aria-hidden="true">/</span>
					<a href="<?php echo esc_url( get_term_link( $bc_terms[0] ) ); ?>"><?php echo esc_html( $bc_terms[0]->name ); ?></a>
				<?php endif; ?>
				<span class="nil-bc-sep" aria-hidden="true">/</span>
				<span class="nil-bc-current" aria-current="page"><?php the_title(); ?></span>
			</div>
		<?php endif; ?>
	</nav>

	<div class="nil-modelo-hero">

		<div class="nil-modelo-photo">
			<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'full' ); endif; ?>
		</div>

		<div class="nil-modelo-info">
			<h1 class="nil-modelo-name"><?php the_title(); ?></h1>

			<?php
			$stats = array(
				'HEIGHT' => get_post_meta( get_the_ID(), 'height', true ),
				'SUIT'   => get_post_meta( get_the_ID(), 'suit',   true ),
				'COLLAR' => get_post_meta( get_the_ID(), 'collar', true ),
				'WAIST'  => get_post_meta( get_the_ID(), 'waist',  true ),
				'INSEAM' => get_post_meta( get_the_ID(), 'inseam', true ),
				'SHOE'   => get_post_meta( get_the_ID(), 'shoe',   true ),
				'HAIR'   => get_post_meta( get_the_ID(), 'hair',   true ),
				'EYES'   => get_post_meta( get_the_ID(), 'eyes',   true ),
			);
			$has_stats = array_filter( $stats );
			if ( $has_stats ) : ?>
				<div class="nil-modelo-stats">
					<?php foreach ( $stats as $label => $value ) :
						if ( ! $value ) continue; ?>
						<div class="nil-stat-row">
							<span class="nil-stat-label"><?php echo esc_html( $label ); ?></span>
							<span class="nil-stat-value"><?php echo esc_html( $value ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( get_the_content() ) : ?>
				<div class="nil-modelo-bio">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>
		</div>

	</div>

	<?php
	$ids_string = get_post_meta( get_the_ID(), 'galeria_fotos', true );
	if ( $ids_string ) :
		$ids = array_filter( explode( ',', $ids_string ) );
		if ( $ids ) : ?>
			<section class="nil-gallery-section">
				<div class="nil-gallery-grid">
					<?php foreach ( $ids as $id ) :
						$src = wp_get_attachment_image_src( absint( $id ), 'large' );
						if ( $src ) : ?>
							<div class="nil-gallery-item">
								<img src="<?php echo esc_url( $src[0] ); ?>" alt="<?php echo esc_attr( get_post_field( 'post_title', absint( $id ) ) ); ?>">
							</div>
						<?php endif;
					endforeach; ?>
				</div>
			</section>
		<?php endif;
	endif; ?>

	<?php
	$videos = get_post_meta( get_the_ID(), 'galeria_videos', true );
	if ( is_array( $videos ) && ! empty( $videos ) ) : ?>
		<section class="nil-videos-section">
			<div class="nil-videos-grid">
				<?php foreach ( $videos as $url ) :
					$embed = wp_oembed_get( esc_url_raw( $url ) );
					if ( $embed ) : ?>
						<div class="nil-video-item">
							<?php echo $embed; ?>
						</div>
					<?php endif;
				endforeach; ?>
			</div>
		</section>
	<?php endif; ?>

	<?php
	/* ── SEO Interlinking: more models in same category ── */
	$current_terms = get_the_terms( get_the_ID(), 'tipo-modelo' );
	if ( $current_terms && ! is_wp_error( $current_terms ) ) :
		$current_term  = $current_terms[0];
		$related_models = get_posts( array(
			'post_type'      => 'modelos',
			'posts_per_page' => 3,
			'post__not_in'   => array( get_the_ID() ),
			'tax_query'      => array( array(
				'taxonomy' => 'tipo-modelo',
				'terms'    => $current_term->term_id,
			) ),
		) );
		if ( $related_models ) :
	?>
	<section class="nil-related-models">
		<span class="nil-section-label">
			<?php printf( esc_html__( 'More %s Models', 'hello-elementor-child' ), esc_html( $current_term->name ) ); ?>
		</span>
		<div class="nil-related-models-grid">
			<?php foreach ( $related_models as $rel ) :
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $rel->ID ), 'medium_large' );
			?>
				<a href="<?php echo esc_url( get_permalink( $rel->ID ) ); ?>"
				   class="nil-related-model-card"
				   title="<?php echo esc_attr( $rel->post_title ); ?>">
					<?php if ( $thumb ) : ?>
						<img src="<?php echo esc_url( $thumb[0] ); ?>"
						     alt="<?php echo esc_attr( $rel->post_title ); ?>"
						     loading="lazy">
					<?php endif; ?>
					<div class="nil-related-model-info">
						<h3><?php echo esc_html( $rel->post_title ); ?></h3>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; endif; ?>

	<?php
	/* ── SEO Interlinking: other categories ── */
	$all_terms = get_terms( array(
		'taxonomy'   => 'tipo-modelo',
		'hide_empty' => true,
		'exclude'    => isset( $current_term ) ? $current_term->term_id : 0,
	) );
	if ( ! is_wp_error( $all_terms ) && ! empty( $all_terms ) ) :
	?>
	<nav class="nil-other-cats" aria-label="<?php esc_attr_e( 'Other model categories', 'hello-elementor-child' ); ?>">
		<span class="nil-other-cats-label"><?php _e( 'Explore', 'hello-elementor-child' ); ?></span>
		<div class="nil-other-cats-links">
			<?php foreach ( $all_terms as $cat ) : ?>
				<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>"
				   title="<?php echo esc_attr( $cat->name ); ?> Models">
					<?php echo esc_html( strtoupper( $cat->name ) ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	</nav>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
