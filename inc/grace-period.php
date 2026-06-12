<?php
/**
 * Grace Period — si un modelo publicado no tiene fotos en la galería
 * pasado el periodo configurado, se manda a borradores automáticamente.
 */

// ─── Ajustes ──────────────────────────────────────────────────────────────────

add_action( 'admin_menu', 'nil_modelos_settings_menu' );
function nil_modelos_settings_menu() {
	add_submenu_page(
		'edit.php?post_type=modelos',
		__( 'Ajustes de Modelos', 'hello-elementor-child' ),
		__( 'Ajustes', 'hello-elementor-child' ),
		'manage_options',
		'nil-modelos-settings',
		'nil_modelos_settings_page'
	);
}

add_action( 'admin_init', 'nil_modelos_register_settings' );
function nil_modelos_register_settings() {
	register_setting( 'nil_modelos_settings_group', 'nil_modelos_grace_period', array(
		'type'              => 'integer',
		'default'           => 7,
		'sanitize_callback' => 'absint',
	) );
	register_setting( 'nil_modelos_settings_group', 'nil_modelos_hero_animation', array(
		'type'              => 'string',
		'default'           => 'none',
		'sanitize_callback' => 'nil_sanitize_modelos_hero_animation',
	) );
}

function nil_sanitize_modelos_hero_animation( $value ) {
	$value = sanitize_key( $value );
	return in_array( $value, array( 'none', 'scroll', 'timelapse' ), true ) ? $value : 'none';
}

function nil_modelos_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Ajustes de Modelos', 'hello-elementor-child' ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'nil_modelos_settings_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">
						<label for="nil_modelos_grace_period">
							<?php esc_html_e( 'Periodo de gracia (días)', 'hello-elementor-child' ); ?>
						</label>
					</th>
					<td>
						<input
							type="number"
							id="nil_modelos_grace_period"
							name="nil_modelos_grace_period"
							value="<?php echo esc_attr( get_option( 'nil_modelos_grace_period', 7 ) ); ?>"
							min="1"
							max="365"
							class="small-text"
						>
						<p class="description">
							<?php esc_html_e( 'Días que tiene un modelo publicado para añadir fotos a su galería. Si el plazo vence sin fotos, el perfil se mueve a Borradores automáticamente.', 'hello-elementor-child' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="nil_modelos_hero_animation">
							<?php esc_html_e( 'Animación del hero en perfiles', 'hello-elementor-child' ); ?>
						</label>
					</th>
					<td>
						<?php $hero_animation = get_option( 'nil_modelos_hero_animation', 'none' ); ?>
						<select id="nil_modelos_hero_animation" name="nil_modelos_hero_animation">
							<option value="none" <?php selected( $hero_animation, 'none' ); ?>>
								<?php esc_html_e( 'Sin animación', 'hello-elementor-child' ); ?>
							</option>
							<option value="scroll" <?php selected( $hero_animation, 'scroll' ); ?>>
								<?php esc_html_e( 'Por scroll', 'hello-elementor-child' ); ?>
							</option>
							<option value="timelapse" <?php selected( $hero_animation, 'timelapse' ); ?>>
								<?php esc_html_e( 'Automática', 'hello-elementor-child' ); ?>
							</option>
						</select>
						<p class="description">
							<?php esc_html_e( 'Este ajuste aplica globalmente a todos los perfiles de modelos.', 'hello-elementor-child' ); ?>
						</p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

// ─── Cron ─────────────────────────────────────────────────────────────────────

add_action( 'init', 'nil_schedule_grace_period_check' );
function nil_schedule_grace_period_check() {
	if ( ! wp_next_scheduled( 'nil_check_modelos_grace_period' ) ) {
		// Programar en la zona horaria configurada del sitio.
		$timezone = wp_timezone();
		$now      = new DateTimeImmutable( 'now', $timezone );
		$target   = DateTimeImmutable::createFromFormat( 'H:i', '23:59', $timezone );

		// Si ya pasaron las 23:59 de hoy, mover al día siguiente.
		if ( $now >= $target ) {
			$target = $target->modify( '+1 day' );
		}

		wp_schedule_event( $target->getTimestamp(), 'daily', 'nil_check_modelos_grace_period' );
	}
}

// Limpiar el cron al cambiar de tema.
add_action( 'switch_theme', 'nil_clear_grace_period_cron' );
function nil_clear_grace_period_cron() {
	$timestamp = wp_next_scheduled( 'nil_check_modelos_grace_period' );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, 'nil_check_modelos_grace_period' );
	}
}

add_action( 'nil_check_modelos_grace_period', 'nil_run_grace_period_check' );
function nil_run_grace_period_check() {
	$grace_days = absint( get_option( 'nil_modelos_grace_period', 7 ) );
	if ( $grace_days < 1 ) {
		return;
	}

	// Calculamos el corte en GMT para compararlo con post_date_gmt.
	$cutoff = gmdate( 'Y-m-d H:i:s', time() - $grace_days * DAY_IN_SECONDS );

	$query = new WP_Query( array(
		'post_type'      => 'modelos',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'date_query'     => array(
			array(
				'before'    => $cutoff,
				'inclusive' => true,
				'column'    => 'post_date_gmt',
			),
		),
		'meta_query'     => array(
			'relation' => 'OR',
			array(
				'key'     => '_galeria',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => '_galeria',
				'value'   => '',
				'compare' => '=',
			),
		),
	) );

	foreach ( $query->posts as $post_id ) {
		wp_update_post( array(
			'ID'          => $post_id,
			'post_status' => 'draft',
		) );
		// Guardamos cuándo se movió a borrador para referencia.
		update_post_meta( $post_id, '_nil_drafted_no_photos', current_time( 'mysql' ) );
	}
}

// ─── Aviso en el editor del modelo ────────────────────────────────────────────

add_action( 'admin_notices', 'nil_grace_period_admin_notice' );
function nil_grace_period_admin_notice() {
	$screen = get_current_screen();
	if ( ! $screen || $screen->post_type !== 'modelos' || $screen->base !== 'post' ) {
		return;
	}

	global $post;
	if ( ! $post || $post->post_status !== 'publish' ) {
		return;
	}

	$ids_string = get_post_meta( $post->ID, '_galeria', true );
	if ( ! empty( $ids_string ) ) {
		return; // Tiene fotos: sin aviso.
	}

	$grace_days = absint( get_option( 'nil_modelos_grace_period', 7 ) );
	$published  = strtotime( $post->post_date_gmt . ' UTC' );
	$expires_at = $published + ( $grace_days * DAY_IN_SECONDS );
	$remaining  = (int) ceil( ( $expires_at - time() ) / DAY_IN_SECONDS );

	if ( $remaining > 0 ) {
		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			wp_kses(
				sprintf(
					/* translators: %d: días restantes */
					__( '<strong>Periodo de gracia:</strong> este modelo no tiene fotos en la galería. Tiene <strong>%d día(s)</strong> para añadir fotos antes de que el perfil se mueva automáticamente a Borradores.', 'hello-elementor-child' ),
					$remaining
				),
				array( 'strong' => array() )
			)
		);
	} else {
		printf(
			'<div class="notice notice-error"><p>%s</p></div>',
			wp_kses(
				__( '<strong>Periodo de gracia vencido:</strong> este modelo no tiene fotos en la galería. Será movido a Borradores en la próxima revisión automática.', 'hello-elementor-child' ),
				array( 'strong' => array() )
			)
		);
	}
}
