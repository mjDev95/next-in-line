<?php get_header(); ?>

<?php $term = get_queried_object(); ?>
<?php
$card_hover_style = get_option( 'nil_modelos_card_hover_style', 'centered' );

$GLOBALS['wp_query'] = new WP_Query( array(
	'post_type'      => 'modelos',
	'posts_per_page' => -1,
	'tax_query'      => array(
		array(
			'taxonomy' => 'tipo-modelo',
			'field'    => 'term_id',
			'terms'    => $term->term_id,
		),
	),
) );
?>

<main class="nil-archive-modelos nil-taxonomy-modelos" data-layout="compact">

	<header class="nil-archive-header">
		<p class="nil-breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'hello-elementor-child' ); ?></a>
			<span>&nbsp;/&nbsp;</span>
			<span><?php echo esc_html( $term->name ); ?></span>
		</p>
		<h1 class="nil-archive-title text-uppercase fw-bold"><?php echo esc_html( strtoupper( $term->name ) ); ?></h1>
		<?php if ( $term->description ) : ?>
			<p class="nil-archive-desc"><?php echo esc_html( $term->description ); ?></p>
		<?php endif; ?>
	</header>

    <div class="container-fluid mb-lg">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5">
            
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                <?php
                $model_meta = array(
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
				
                ?>

                <div class="d-flex flex-column">
                    <a href="<?php the_permalink(); ?>" class="nil-model-card position-relative overflow-hidden w-100 d-flex flex-column flex-1 text-decoration-none">

                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'large', array( 'class' => 'w-100 h-100 d-block object-fit-cover' ) ); ?>
                        <?php endif; ?>

						<?php
						$is_left_aligned = ( 'left-aligned' === $card_hover_style );

						$wrapper_classes = 'nil-model-card-meta position-absolute d-flex flex-column';
						$wrapper_classes .= ' align-items-center justify-content-center text-center';

						$inner_wrapper_classes = 'd-flex flex-column justify-content-center w-100 h-100';
						$inner_wrapper_classes .= ' align-items-center';
						?>
                        <div class="<?php echo esc_attr( $wrapper_classes ); ?>">
							<div class="<?php echo esc_attr( $inner_wrapper_classes ); ?>">
								<?php if ( $is_left_aligned ) : ?>
									<?php foreach ( $model_meta as $label => $value ) : ?>
										<?php if ( ! empty( $value ) ) : ?>
											<p class="text-uppercase my-0 h6">
												<span><?php echo esc_html( $label ); ?>: <?php echo esc_html( $value ); ?></span>
											</p>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php else : ?>
									<?php foreach ( $model_meta as $label => $value ) : ?>
										<?php if ( ! empty( $value ) ) : ?>
											<p class="text-uppercase d-flex align-items-center my-0 w-100 justify-content-center gap-2 h6">
												<span class="d-block flex-1 w-100 text-end"><?php echo esc_html( $label ); ?></span>
												<span class="d-block flex-1 w-100 text-start"><?php echo esc_html( $value ); ?></span>
											</p>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>


                    </a>
					<a href="<?php the_permalink(); ?>" class="py-xs text-start">
                        <h2 class="text-uppercase h5 text-center mb-0"><?php the_title(); ?></h2>
					</a>
                </div>

            <?php endwhile; endif; ?>

        </div>
    </div>


	<?php
	/* ── SEO Interlinking: otras categorías ── */
	$other_terms = get_terms( array(
		'taxonomy'   => 'tipo-modelo',
		'hide_empty' => true,
		'exclude'    => $term->term_id,
		'orderby'    => 'name',
		'order'      => 'ASC',
		'number'     => 4,
	) );

	if ( ! is_wp_error( $other_terms ) && ! empty( $other_terms ) ) :
	?>

	<section class="nil-related-cats py-xl mt-xl" aria-label="<?php esc_attr_e( 'Otras divisiones', 'hello-elementor-child' ); ?>">

		<p class="nil-related-cats-title h6 text-center text-uppercase mb-md"><?php esc_html_e( 'Descubre otras divisiones', 'hello-elementor-child' ); ?></p>

		<div class="nil-related-cats-grid row">
			<?php foreach ( $other_terms as $other ) :

				$image_id = get_term_meta( $other->term_id, 'image_id', true );
				$bg_url   = '';

				if ( $image_id ) {
					$img    = wp_get_attachment_image_src( $image_id, 'medium_large' );
					$bg_url = $img ? $img[0] : '';
				}
			?>

				<div class="col-6 col-md-4 col-lg-3">
					<a href="<?php echo esc_url( get_term_link( $other ) ); ?>"
					   class="nil-related-cat-card position-relative overflow-hidden text-decoration-none d-block"
					   title="<?php echo esc_attr( sprintf(
					   		/* translators: %s: nombre de la categoría */
					   		__( 'Modelos %s', 'hello-elementor-child' ),
					   		$other->name
					   ) ); ?>">

					<?php if ( $bg_url ) : ?>
						<div class="nil-cat-bg" style="background-image:url('<?php echo esc_url( $bg_url ); ?>')"></div>
					<?php endif; ?>
					<div class="nil-cat-overlay position-absolute"></div>
					<div class="nil-related-cat-info position-absolute">
						<h3>
							<span class="nil-related-cat-name h5 text-uppercase fw-bold"><?php echo esc_html( strtoupper( $other->name ) ); ?></span>
						</h3>
					</div>

					</a>
				</div>

			<?php endforeach; ?>
		</div>

	</section>

	<?php endif; ?>

</main>

<?php get_footer('modelos'); ?>
