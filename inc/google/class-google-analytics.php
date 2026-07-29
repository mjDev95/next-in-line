<?php
/**
 * Google Integrations — Google Analytics 4
 *
 * Carga el script oficial gtag.js únicamente si existe un
 * Measurement ID válido configurado en el administrador.
 *
 * Es totalmente compatible con Yoast SEO: no genera ningún metadato
 * SEO ni schema estructurado; solo gestiona el rastreo de Analytics.
 *
 * @package HelloElementorChild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class NIL_Google_Analytics
 */
class NIL_Google_Analytics {

	/**
	 * Measurement ID de GA4.
	 *
	 * @var string
	 */
	private string $ga4_id;

	/**
	 * Constructor: carga el Measurement ID desde los ajustes.
	 */
	public function __construct() {
		$this->ga4_id = nil_google_get_option( 'ga4_id' );
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

		// Encola gtag.js en el <head> con alta prioridad.
		add_action( 'wp_head', array( $this, 'render_gtag_script' ), 2 );
	}

	/**
	 * Comprueba si GA4 está activo (ID válido y no vacío).
	 *
	 * @return bool
	 */
	private function is_active(): bool {
		return '' !== $this->ga4_id && nil_google_is_valid_ga4_id( $this->ga4_id );
	}

	/**
	 * Imprime el script de GA4 (gtag.js) directamente en el <head>.
	 *
	 * Se utiliza un inline script en lugar de wp_enqueue_script para:
	 * - Respetar el orden estricto requerido por gtag (async src + config inline).
	 * - Evitar que WordPress añada atributos extras que rompan el snippet.
	 *
	 * @return void
	 */
	public function render_gtag_script(): void {
		$id = esc_js( $this->ga4_id );
		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
<!-- Google Analytics 4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $id; ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?php echo $id; ?>');
</script>
<!-- End Google Analytics 4 -->
		<?php
		// phpcs:enable
	}
}
