<?php
/**
 * Template: Hero / Texto Izquierda, Imagen Centro
 *
 * @package HelloElementorChild
 */

	$nil_model_category = isset( $args['nil_model_category'] ) ? $args['nil_model_category'] : '';
	$stats              = isset( $args['stats'] ) ? $args['stats'] : array();
	$has_stats          = isset( $args['has_stats'] ) ? $args['has_stats'] : array();

?>
<section class="nil-modelo-hero" data-hero-mode="text-left-image-center">

	<div class="nil-modelo-photo nil-modelo-photo-target">
		<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'full' ); endif; ?>
	</div>

	<div class="nil-modelo-hero-layout">
		<div class="container">
			<div class="row align-items-center">

				<div class="col-md-5 nil-modelo-hero-text nil-modelo-hero-left">
					<h1 class="nil-modelo-name"><?php the_title(); ?></h1>
				</div>

				<div class="col-md-4 nil-modelo-photo-box"></div>

				<div class="col-md-3 nil-modelo-hero-text nil-modelo-hero-right">
					<?php // Espacio vacío ?>
				</div>

			</div>
			<?php if ( ! empty( $has_stats ) ) : ?>
				<div class="row nil-hero-stats-wrapper">
					<div class="col-12 d-flex flex-wrap justify-content-center">
						<?php foreach ( $stats as $label => $value ) :
							if ( ! $value ) continue;
							?>
							<div class="nil-spec-item px-sm">
								<span class="nil-spec-label me-2"><?php echo esc_html( $label ); ?>: <?php echo esc_html( $value ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>

</section>