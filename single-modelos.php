<?php
get_header();
the_post();

$nil_hero_animation = get_option( 'nil_modelos_hero_animation', 'scroll' );
if ( ! in_array( $nil_hero_animation, array( 'scroll', 'timelapse' ), true ) ) {
	$nil_hero_animation = 'scroll';
}

$nil_terms = get_the_terms( get_the_ID(), 'tipo-modelo' );
$nil_model_category = ( $nil_terms && ! is_wp_error( $nil_terms ) ) ? $nil_terms[0]->name : '';

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
?>

<main class="nil-single-modelo nil-modelo-hero-mode-<?php echo esc_attr( $nil_hero_animation ); ?>">

	<nav class="nil-breadcrumb d-none" aria-label="<?php esc_attr_e( 'Ruta de navegación', 'hello-elementor-child' ); ?>">
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

	<div class="nil-hero-scroll-wrapper">

		<section class="nil-modelo-hero" data-hero-mode="<?php echo esc_attr( $nil_hero_animation ); ?>">

			<div class="nil-modelo-photo nil-modelo-photo-target">
				<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'full' ); endif; ?>
			</div>

			<div class="nil-modelo-hero-layout">
				<div class="container">
					<div class="row align-items-center">

						<div class="col-md-3 nil-modelo-hero-text nil-modelo-hero-left">
							<h1 class="nil-modelo-name"><?php the_title(); ?></h1>
						</div>

						<div class="col-md-6 nil-modelo-photo-box"></div>

						<?php if ( $nil_model_category ) : ?>
							<div class="col-md-3 nil-modelo-hero-text nil-modelo-hero-right">
								<p class="nil-modelo-category"><?php echo esc_html( $nil_model_category ); ?></p>
							</div>
						<?php endif; ?>

					</div>
				</div>
			</div>

		</section>

	</div>

	<?php if ( $has_stats ) : ?>
		<div class="container mt-lg  mb-lg d-flex flex-wrap justify-content-center">
				<?php foreach ( $stats as $label => $value ) :
					if ( ! $value ) continue; 
				?>
						<div class="nil-spec-item px-sm">
								<span class="nil-spec-label me-2"><?php echo esc_html( $label ); ?></span>
								<span class="nil-spec-value"><?php echo esc_html( $value ); ?></span>
						</div>
				<?php endforeach; ?>
		</div>
	<?php endif; ?>

    <?php
    $ids_string = get_post_meta( get_the_ID(), 'galeria_fotos', true );
    if ( $ids_string ) :
        $ids = array_filter( explode( ',', $ids_string ) );
        if ( $ids ) : ?>
            <section class="nil-gallery-section container py-lg">
                <div class="row">
                    <?php foreach ( $ids as $id ) :
                        $src = wp_get_attachment_image_src( absint( $id ), 'large' );
                        if ( $src ) : ?>
                            <div class="col-12 col-md-4 nil-gallery-item mb-md">
                                <img src="<?php echo esc_url( $src[0] ); ?>" alt="<?php echo esc_attr( get_post_field( 'post_title', absint( $id ) ) ); ?>" class="w-100 d-block">
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
        <section class="nil-videos-section container py-lg">
            <div class="row">
                <?php foreach ( $videos as $url ) :
                    $embed = wp_oembed_get( esc_url_raw( $url ) );
                    if ( $embed ) : ?>
                        <div class="col-12 col-md-6 nil-video-item mb-md">
                            <div class="nil-video-wrapper">
                                <?php echo $embed; ?>
                            </div>
                        </div>
                    <?php endif;
                endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php
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
    <section class="nil-related-models container py-lg">
        <div class="row">
            <span class="col-12 col-md-3 nil-section-label mb-sm">
                <?php printf( esc_html__( 'More %s Models', 'hello-elementor-child' ), esc_html( $current_term->name ) ); ?>
            </span>
            
            <div class="col-12 col-md-9">
                <div class="row">
                    <?php foreach ( $related_models as $rel ) :
                        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $rel->ID ), 'medium_large' );
                    ?>
                        <div class="col-12 col-md-4 mb-sm">
                            <a href="<?php echo esc_url( get_permalink( $rel->ID ) ); ?>"
                               class="nil-related-model-card d-block"
                               title="<?php echo esc_attr( $rel->post_title ); ?>">
                                <?php if ( $thumb ) : ?>
                                    <img src="<?php echo esc_url( $thumb[0] ); ?>"
                                         alt="<?php echo esc_attr( $rel->post_title ); ?>"
                                         loading="lazy" class="w-100 d-block">
                                <?php endif; ?>
                                <div class="nil-related-model-info mt-sm">
                                    <h3><?php echo esc_html( $rel->post_title ); ?></h3>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; endif; ?>

    <?php
    $all_terms = get_terms( array(
        'taxonomy'   => 'tipo-modelo',
        'hide_empty' => true,
        'exclude'    => isset( $current_term ) ? $current_term->term_id : 0,
    ) );
    if ( ! is_wp_error( $all_terms ) && ! empty( $all_terms ) ) :
    ?>
    <nav class="nil-other-cats container py-xl" aria-label="<?php esc_attr_e( 'Other model categories', 'hello-elementor-child' ); ?>">
        <div class="row">
            <span class="col-12 col-md-3 nil-other-cats-label mb-sm"><?php _e( 'Explore', 'hello-elementor-child' ); ?></span>
            
            <div class="col-12 col-md-9">
                <div class="nil-other-cats-links d-flex flex-wrap gap-md">
                    <?php foreach ( $all_terms as $cat ) : ?>
                        <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>"
                           title="<?php echo esc_attr( $cat->name ); ?> Models">
                            <?php echo esc_html( strtoupper( $cat->name ) ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
