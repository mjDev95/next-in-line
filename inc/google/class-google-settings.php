<?php
/**
 * Google Integrations — Panel de administración y Settings API
 *
 * Registra el menú principal "Google" en el sidebar de WordPress,
 * la subpágina "Integraciones" y los campos de configuración
 * mediante la Settings API nativa.
 *
 * La arquitectura está preparada para recibir sub-páginas adicionales
 * (Search Console, Merchant Center, Consent Mode, Conversiones, etc.)
 * sin necesidad de reestructurar el código.
 *
 * @package HelloElementorChild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class NIL_Google_Settings
 */
class NIL_Google_Settings {

	/**
	 * Slug del menú principal.
	 *
	 * @var string
	 */
	const MENU_SLUG = 'nil-google';

	/**
	 * Slug de la página de Integraciones.
	 *
	 * @var string
	 */
	const PAGE_INTEGRATIONS = 'nil-google-integrations';

	/**
	 * Nombre de la opción en wp_options.
	 *
	 * @var string
	 */
	const OPTION_NAME = 'nil_google_integrations';

	/**
	 * Nombre del grupo de opciones para Settings API.
	 *
	 * @var string
	 */
	const OPTION_GROUP = 'nil_google_integrations_group';

	/**
	 * Inicializa los hooks de WordPress.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'admin_menu', array( $this, 'register_menus' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Registra el menú principal y sus sub-páginas en el administrador.
	 *
	 * Para agregar nuevas sub-páginas en el futuro, basta con añadir
	 * una llamada a add_submenu_page() dentro de este método.
	 *
	 * @return void
	 */
	public function register_menus(): void {

		// ── Menú principal ──────────────────────────────────────────────────
		add_menu_page(
			__( 'Google', 'hello-elementor-child' ),         // Título de la página.
			__( 'Google', 'hello-elementor-child' ),         // Texto en el sidebar.
			'manage_options',                                  // Capacidad requerida.
			self::MENU_SLUG,                                   // Slug del menú.
			array( $this, 'render_integrations_page' ),       // Callback: redirige a Integraciones.
			'dashicons-google',                                // Icono (dashicon).
			80                                                 // Posición en el sidebar.
		);

		// ── Integraciones (primera sub-página) ──────────────────────────────
		add_submenu_page(
			self::MENU_SLUG,
			__( 'Integraciones de Google', 'hello-elementor-child' ),
			__( 'Integraciones', 'hello-elementor-child' ),
			'manage_options',
			self::PAGE_INTEGRATIONS,
			array( $this, 'render_integrations_page' )
		);

		// Elimina el ítem duplicado que WordPress crea automáticamente al usar
		// add_menu_page con el mismo slug que la primera sub-página.
		remove_submenu_page( self::MENU_SLUG, self::MENU_SLUG );

		/*
		 * ── Futuras sub-páginas ─────────────────────────────────────────────
		 * Ejemplo de cómo añadir Search Console en el futuro:
		 *
		 * add_submenu_page(
		 *     self::MENU_SLUG,
		 *     __( 'Search Console', 'hello-elementor-child' ),
		 *     __( 'Search Console', 'hello-elementor-child' ),
		 *     'manage_options',
		 *     'nil-google-search-console',
		 *     array( $this, 'render_search_console_page' )
		 * );
		 */
	}

	/**
	 * Registra los ajustes, secciones y campos mediante la Settings API.
	 *
	 * @return void
	 */
	public function register_settings(): void {

		register_setting(
			self::OPTION_GROUP,
			self::OPTION_NAME,
			array(
				'sanitize_callback' => array( $this, 'sanitize_options' ),
			)
		);

		// ── Sección principal ───────────────────────────────────────────────
		add_settings_section(
			'nil_google_section_tracking',
			__( 'IDs de seguimiento', 'hello-elementor-child' ),
			array( $this, 'render_section_tracking' ),
			self::PAGE_INTEGRATIONS
		);

		// ── Campo: Google Tag Manager ID ────────────────────────────────────
		add_settings_field(
			'gtm_id',
			__( 'Google Tag Manager ID', 'hello-elementor-child' ),
			array( $this, 'render_field_gtm_id' ),
			self::PAGE_INTEGRATIONS,
			'nil_google_section_tracking'
		);

		// ── Campo: Google Analytics 4 Measurement ID ───────────────────────
		add_settings_field(
			'ga4_id',
			__( 'Google Analytics 4 Measurement ID', 'hello-elementor-child' ),
			array( $this, 'render_field_ga4_id' ),
			self::PAGE_INTEGRATIONS,
			'nil_google_section_tracking'
		);
	}

	/**
	 * Sanitiza y valida los valores antes de guardarlos en la base de datos.
	 *
	 * @param  mixed $input Datos del formulario.
	 * @return array        Datos sanitizados.
	 */
	public function sanitize_options( $input ): array {

		$clean = array();

		// GTM ID: solo letras mayúsculas, números y guion (ej. GTM-XXXXXXX).
		if ( ! empty( $input['gtm_id'] ) ) {
			$gtm = strtoupper( sanitize_text_field( $input['gtm_id'] ) );
			if ( nil_google_is_valid_gtm_id( $gtm ) ) {
				$clean['gtm_id'] = $gtm;
			} else {
				add_settings_error(
					self::OPTION_NAME,
					'gtm_id_invalid',
					__( 'El ID de Google Tag Manager no es válido. Formato esperado: GTM-XXXXXXX', 'hello-elementor-child' ),
					'error'
				);
				// Conserva el valor anterior si existe.
				$prev             = get_option( self::OPTION_NAME, array() );
				$clean['gtm_id'] = $prev['gtm_id'] ?? '';
			}
		} else {
			$clean['gtm_id'] = '';
		}

		// GA4 ID: solo letras mayúsculas, números y guion (ej. G-XXXXXXXXXX).
		if ( ! empty( $input['ga4_id'] ) ) {
			$ga4 = strtoupper( sanitize_text_field( $input['ga4_id'] ) );
			if ( nil_google_is_valid_ga4_id( $ga4 ) ) {
				$clean['ga4_id'] = $ga4;
			} else {
				add_settings_error(
					self::OPTION_NAME,
					'ga4_id_invalid',
					__( 'El Measurement ID de Google Analytics 4 no es válido. Formato esperado: G-XXXXXXXXXX', 'hello-elementor-child' ),
					'error'
				);
				$prev             = get_option( self::OPTION_NAME, array() );
				$clean['ga4_id'] = $prev['ga4_id'] ?? '';
			}
		} else {
			$clean['ga4_id'] = '';
		}

		return $clean;
	}

	// ── Renderizado ──────────────────────────────────────────────────────────

	/**
	 * Renderiza la página de Integraciones.
	 *
	 * @return void
	 */
	public function render_integrations_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Google — Integraciones', 'hello-elementor-child' ); ?></h1>
			<p class="description">
				<?php esc_html_e( 'Configura los IDs de seguimiento de Google. Deja un campo vacío para desactivar esa integración.', 'hello-elementor-child' ); ?>
			</p>

			<?php settings_errors( self::OPTION_NAME ); ?>

			<form method="post" action="options.php">
				<?php
				settings_fields( self::OPTION_GROUP );
				do_settings_sections( self::PAGE_INTEGRATIONS );
				submit_button( __( 'Guardar cambios', 'hello-elementor-child' ) );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Renderiza el texto introductorio de la sección de rastreo.
	 *
	 * @return void
	 */
	public function render_section_tracking(): void {
		echo '<p>' . esc_html__( 'Introduce los IDs de las herramientas que deseas activar en el frontend del sitio.', 'hello-elementor-child' ) . '</p>';
	}

	/**
	 * Renderiza el campo de Google Tag Manager ID.
	 *
	 * @return void
	 */
	public function render_field_gtm_id(): void {
		$value = nil_google_get_option( 'gtm_id' );
		?>
		<input
			type="text"
			id="nil_gtm_id"
			name="<?php echo esc_attr( self::OPTION_NAME ); ?>[gtm_id]"
			value="<?php echo esc_attr( $value ); ?>"
			placeholder="GTM-XXXXXXX"
			class="regular-text"
			autocomplete="off"
		>
		<p class="description">
			<?php esc_html_e( 'Ejemplo: GTM-XXXXXXX. Deja vacío para desactivar Google Tag Manager.', 'hello-elementor-child' ); ?>
		</p>
		<?php
	}

	/**
	 * Renderiza el campo de Google Analytics 4 Measurement ID.
	 *
	 * @return void
	 */
	public function render_field_ga4_id(): void {
		$value = nil_google_get_option( 'ga4_id' );
		?>
		<input
			type="text"
			id="nil_ga4_id"
			name="<?php echo esc_attr( self::OPTION_NAME ); ?>[ga4_id]"
			value="<?php echo esc_attr( $value ); ?>"
			placeholder="G-XXXXXXXXXX"
			class="regular-text"
			autocomplete="off"
		>
		<p class="description">
			<?php esc_html_e( 'Ejemplo: G-XXXXXXXXXX. Deja vacío para desactivar Google Analytics 4.', 'hello-elementor-child' ); ?>
		</p>
		<?php
	}
}
