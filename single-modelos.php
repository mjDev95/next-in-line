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
	__( 'Altura', 'hello-elementor-child' )      => get_post_meta( get_the_ID(), 'height', true ),
	__( 'Traje', 'hello-elementor-child' )       => get_post_meta( get_the_ID(), 'suit', true ),
	__( 'Cuello', 'hello-elementor-child' )      => get_post_meta( get_the_ID(), 'collar', true ),
	__( 'Cintura', 'hello-elementor-child' )     => get_post_meta( get_the_ID(), 'waist', true ),
	__( 'Entrepierna', 'hello-elementor-child' ) => get_post_meta( get_the_ID(), 'inseam', true ),
	__( 'Calzado', 'hello-elementor-child' )     => get_post_meta( get_the_ID(), 'shoe', true ),
	__( 'Cabello', 'hello-elementor-child' )     => get_post_meta( get_the_ID(), 'hair', true ),
	__( 'Ojos', 'hello-elementor-child' )        => get_post_meta( get_the_ID(), 'eyes', true ),
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

						<div class="col-md-4 nil-modelo-hero-text nil-modelo-hero-left">
							<h1 class="nil-modelo-name"><?php the_title(); ?></h1>
						</div>

						<div class="col-md-4 nil-modelo-photo-box"></div>

						<?php if ( $nil_model_category ) : ?>
							<div class="col-md-4 nil-modelo-hero-text nil-modelo-hero-right">
							</div>
						<?php endif; ?>

					</div>
				</div>
			</div>

		</section>

	</div>

	<?php if ( $has_stats ) : ?>
		<div class="container  mb-lg d-flex flex-wrap justify-content-center">
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
    $nil_galeria_string = get_post_meta( get_the_ID(), '_galeria', true );

    if ( ! empty( $nil_galeria_string ) ) :
        $nil_media_ids = array_filter( explode( ',', $nil_galeria_string ) );
        
        if ( ! empty( $nil_media_ids ) ) : ?>
            
            <section class="container py-lg">
                <div class="row nil-gallery-grid-trigger" data-gallery-group="<?php echo esc_attr( $nil_galeria_string ); ?>">
                    
                    <?php 
                    $index_counter = 0;
                    // Placeholder transparente super ligero
                    $placeholder = 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';

                    foreach ( $nil_media_ids as $media_id ) :
                        $media_id = absint( $media_id );
                        $media_url = wp_get_attachment_url( $media_id );
                        if ( ! $media_url ) continue;

                        $mime_type = get_post_mime_type( $media_id );
                        $is_image  = ( strpos( $mime_type, 'image' ) !== false );
                        $is_video  = ( strpos( $mime_type, 'video' ) !== false );

                        // Metadatos
                        $title_text = get_the_title( $media_id );
                        $alt_text   = get_post_meta( $media_id, '_wp_attachment_image_alt', true );
                        if ( empty( $alt_text ) ) $alt_text = $title_text;

                        if ( $is_image ) : 
                            $img_src = wp_get_attachment_image_src( $media_id, 'large' );
                            if ( $img_src ) : ?>
                                <div class="col-12 col-md-4 nil-batch-item opacity-0">
                                    <div class="nil-gallery-item overflow-hidden w-100 position-relative cursor-pointer" data-index="<?php echo $index_counter; ?>">
                                        <img src="<?php echo $placeholder; ?>" 
                                             data-src="<?php echo esc_url( $img_src[0] ); ?>" 
                                             alt="<?php echo esc_attr( $alt_text ); ?>" 
                                             title="<?php echo esc_attr( $title_text ); ?>"
                                             class="nil-lazy-media w-100 h-100 d-block object-fit-cover will-change-transform">
                                    </div>
                                </div>
                            <?php $index_counter++; endif; ?>

                        <?php elseif ( $is_video ) : ?>
                            <div class="col-12 col-md-4 nil-batch-item opacity-0">
                                <div class="nil-video-item overflow-hidden w-100 position-relative cursor-pointer" data-index="<?php echo $index_counter; ?>">
                                    <div class="nil-video-wrapper w-100 position-relative">
                                        <video class="nil-lazy-media w-100 h-100 d-block object-fit-cover will-change-transform" controls preload="none" title="<?php echo esc_attr( $title_text ); ?>" data-src="<?php echo esc_url( $media_url ); ?>">
                                            <source src="" type="<?php echo esc_attr( $mime_type ); ?>">
                                        </video>
                                    </div>
                                </div>
                            </div>
                        <?php $index_counter++; endif; ?>

                    <?php endforeach; ?>

                </div> 
            </section>

        <?php endif;
    endif; ?>

    <div id="nil-lightbox-overlay" class="position-fixed inset-0 bg-primary d-none z-index-2 opacity-0 overflow-hidden">
        
        <button id="nil-lightbox-close" class="position-absolute text-white bg-transparent border-none text-uppercase letter-spacing-md cursor-pointer h6 z-index-2" style="top: var(--nil-s-md); right: var(--nil-s-md);">
            <?php _e('Close —', 'nil'); ?>
        </button>

        <div class="nil-lightbox-pagination position-absolute text-white h2 font-light z-index-2" style="bottom: var(--nil-s-md); left: var(--nil-s-md);"></div>

        <div class="swiper nil-lightbox-swiper w-100 h-100">
            <div class="swiper-wrapper">
                <?php 
                if ( ! empty( $nil_media_ids ) ) {
                    foreach ( $nil_media_ids as $media_id ) {
                        $media_id = absint( $media_id );
                        $media_url = wp_get_attachment_url( $media_id );
                        
                        if ( ! $media_url ) continue;

                        $mime_type = get_post_mime_type( $media_id );
                        $is_image  = ( strpos( $mime_type, 'image' ) !== false );
                        $img_alt   = get_post_field( 'post_title', $media_id );
                        
                        // ⚡ PASAMOS LA DATA DIRECTA: Guardamos la URL real en data-lightbox-src
                        echo '<div class="swiper-slide d-flex align-items-center justify-content-center p-lg" 
                                   data-lightbox-src="' . esc_url( $media_url ) . '" 
                                   data-media-type="' . ( $is_image ? 'image' : 'video' ) . '" 
                                   data-media-alt="' . esc_attr( $img_alt ) . '">';
                        
                        echo '<div class="nil-lightbox-media-holder w-100 h-100 d-flex align-items-center justify-content-center"></div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>

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
        <section class="container py-lg">
            <div class="row">
                <span class="col-12 col-md-3 nil-section-label mb-sm h5 text-uppercase">
                    <?php printf(
                        esc_html__( 'Más modelos de %s', 'hello-elementor-child' ),
                        esc_html( $current_term->name )
                    ); ?>
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
                                    <div class="nil-related-model-info mt-xs">
                                        <h3 class="h4 fw-normal text-uppercase"><?php echo esc_html( $rel->post_title ); ?></h3>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; 
    endif; ?>

</main>

<?php  get_footer( 'modelos' ); ?>
