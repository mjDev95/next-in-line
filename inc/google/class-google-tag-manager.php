<?php
/**
 * Google Integrations — Google Tag Manager
 *
 * Inyecta el snippet de GTM en el <head> y el bloque <noscript>
 * inmediatamente después de abrir el <body>, respetando la integración
 * con Yoast SEO (no duplica metadatos ni schema).
 *
 * Solo se imprime código si existe un GTM ID válido configurado.
 *
 * @package HelloElementorChild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class NIL_Google_Tag_Manager
 */
class NIL_Google_Tag_Manager {

	/**
	 * ID de GTM obtenido desde los ajustes.
	 *
	 * @var string
	 */
	private string $gtm_id;

	/**
	 * Constructor: carga el ID de GTM.
	 */
	public function __construct() {
		$this->gtm_id = nil_google_get_option( 'gtm_id' );
	}

	/**
	 * Inicializa los hooks de WordPress.
	 *
	 * @return void
	 */
	public function init(): void {
		if ( ! $this->is_active() ) {
			return;
		}

		// Script en el <head> (prioridad alta para que GTM capture todos los eventos).
		add_action( 'wp_head', array( $this, 'render_head_script' ), 1 );

		// Bloque <noscript> inmediatamente después de <body>.
		add_action( 'wp_body_open', array( $this, 'render_body_noscript' ), 1 );
	}

	/**
	 * Comprueba si GTM está activo (ID válido y no vacío).
	 *
	 * @return bool
	 */
	private function is_active(): bool {
		return '' !== $this->gtm_id && nil_google_is_valid_gtm_id( $this->gtm_id );
	}

	/**
	 * Imprime el script de GTM dentro del <head>.
	 *
	 * @return void
	 */
	public function render_head_script(): void {
		$id = esc_js( $this->gtm_id );
		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo $id; ?>');</script>
<!-- End Google Tag Manager -->
		<?php
		// phpcs:enable
	}

	/**
	 * Imprime el bloque <noscript> de GTM tras la apertura del <body>.
	 *
	 * @return void
	 */
	public function render_body_noscript(): void {
		$id = esc_attr( $this->gtm_id );
		?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $id; ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		<?php
	}
}
