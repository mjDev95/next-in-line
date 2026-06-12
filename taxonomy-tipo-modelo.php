<?php get_header(); ?>

<?php $term = get_queried_object(); ?>
<?php
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

<main class="nil-archive-modelos nil-taxonomy-modelos">

	<header class="nil-archive-header">
		<p class="nil-breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'hello-elementor-child' ); ?></a>
			<span>&nbsp;/&nbsp;</span>
			<span><?php echo esc_html( $term->name ); ?></span>
		</p>
		<h1 class="nil-archive-title text-uppercase"><?php echo esc_html( strtoupper( $term->name ) ); ?></h1>
		<?php if ( $term->description ) : ?>
			<p class="nil-archive-desc"><?php echo esc_html( $term->description ); ?></p>
		<?php endif; ?>
	</header>

    <div class="container-fluid mb-lg">
        <div class="row">
            
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                <?php
                $model_meta = array(
                	'Estatura' => get_post_meta( get_the_ID(), 'height', true ),
                	'Traje'    => get_post_meta( get_the_ID(), 'suit', true ),
                	'Cuello'   => get_post_meta( get_the_ID(), 'collar', true ),
                	'Cintura'  => get_post_meta( get_the_ID(), 'waist', true ),
                	'Entrepierna' => get_post_meta( get_the_ID(), 'inseam', true ),
                	'Calzado'  => get_post_meta( get_the_ID(), 'shoe', true ),
                	'Cabello'  => get_post_meta( get_the_ID(), 'hair', true ),
                	'Ojos'     => get_post_meta( get_the_ID(), 'eyes', true ),
                );
                ?>

                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <a href="<?php the_permalink(); ?>" class="position-relative overflow-hidden w-100 h-100 nil-model-card d-block text-decoration-none">

                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'large', array( 'class' => 'w-100 h-100 d-block object-fit-cover' ) ); ?>
                        <?php endif; ?>

                        <div class="nil-model-card-meta position-absolute">

                            <?php foreach ( $model_meta as $label => $value ) : ?>

                            	<?php if ( ! empty( $value ) ) : ?>
                            		<div>
                            			<span><?php echo esc_html( $label ); ?></span>
                            			<strong><?php echo esc_html( $value ); ?></strong>
                            		</div>
                            	<?php endif; ?>

                            <?php endforeach; ?>

                        </div>

                    </a>
					<a href="<?php the_permalink(); ?>" class="mt-xs text-start">
                        <h2 class="text-uppercase my-0 h5"><?php the_title(); ?></h2>
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

	<section class="nil-related-cats py-xl mt-xl" aria-label="<?php esc_attr_e( 'Otras divisiones de modelos', 'hello-elementor-child' ); ?>">

		<h3 class="nil-related-cats-title h6 text-center text-uppercase mb-lg"><?php esc_html_e( 'Descubre otras divisiones', 'hello-elementor-child' ); ?></h3>

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
						<span class="nil-related-cat-name h5 text-uppercase"><?php echo esc_html( strtoupper( $other->name ) ); ?></span>
					</div>

					</a>
				</div>

			<?php endforeach; ?>
		</div>

	</section>

	<?php endif; ?>

</main>

<?php get_footer('modelos'); ?>
