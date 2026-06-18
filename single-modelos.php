<?php
get_header();
the_post();

$nil_hero_animation = get_option( 'nil_modelos_hero_animation', 'text-left-image-center' );
if ( ! in_array( $nil_hero_animation, array( 'text-left-image-center', 'name-surname-image-center', 'text-left-image-right' ), true ) ) {
	$nil_hero_animation = 'text-left-image-center'; // Nuevo valor por defecto
}

$nil_terms = get_the_terms( get_the_ID(), 'tipo-modelo' );
$nil_model_category = ( $nil_terms && ! is_wp_error( $nil_terms ) ) ? $nil_terms[0]->name : '';

$stats = array(
    __( 'Altura', 'hello-elementor-child' )   => get_post_meta( get_the_ID(), 'height', true ),
    __( 'Busto', 'hello-elementor-child' )    => get_post_meta( get_the_ID(), 'bust', true ),
    __( 'Cintura', 'hello-elementor-child' )  => get_post_meta( get_the_ID(), 'waist', true ),
    __( 'Cadera', 'hello-elementor-child' )   => get_post_meta( get_the_ID(), 'hips', true ),
    __( 'Saco', 'hello-elementor-child' )     => get_post_meta( get_the_ID(), 'suit', true ),
    __( 'Camisa', 'hello-elementor-child' )   => get_post_meta( get_the_ID(), 'shirt', true ),
    __( 'Pantalón', 'hello-elementor-child' ) => get_post_meta( get_the_ID(), 'pants', true ),
    __( 'Zapato', 'hello-elementor-child' )   => get_post_meta( get_the_ID(), 'shoe', true ),
    __( 'Cabello', 'hello-elementor-child' )  => get_post_meta( get_the_ID(), 'hair', true ),
    __( 'Ojos', 'hello-elementor-child' )     => get_post_meta( get_the_ID(), 'eyes', true ),
);
$has_stats = array_filter( $stats );
?>

<main class="nil-single-modelo nil-modelo-hero-mode-<?php echo esc_attr( $nil_hero_animation ); ?>"  data-layout="compact">


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
		<?php
        $hero_args = array(
            'nil_model_category' => $nil_model_category,
            'stats'              => $stats,
            'has_stats'          => $has_stats,
        );
		// Carga el template part correspondiente a la opción de hero seleccionada.
		// Los archivos se encuentran en /template-parts/hero-layouts/
		get_template_part( 'template-parts/hero-layouts/' . $nil_hero_animation, null, $hero_args );
        ?>
	</div>

    <?php
    $nil_galeria_string = get_post_meta( get_the_ID(), '_galeria', true );

    if ( ! empty( $nil_galeria_string ) ) :
        $nil_media_ids = array_filter( explode( ',', $nil_galeria_string ) );
        
        if ( ! empty( $nil_media_ids ) ) : ?>
            
            <section class="container py-sm">
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

        <div class="nil-lightbox-pagination position-absolute text-white h2 font-light z-index-2 d-none" style="bottom: var(--nil-s-md); left: var(--nil-s-md);"></div>

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
                    <?php esc_html_e( 'Más Talento', 'hello-elementor-child' ); ?>
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
                                        <h3 class="h5 fw-normal text-uppercase text-center"><?php echo esc_html( $rel->post_title ); ?></h3>
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
