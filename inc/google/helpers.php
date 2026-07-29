<?php
/**
 * Google Integrations — Helpers
 *
 * Funciones auxiliares compartidas por todos los módulos de Google.
 *
 * @package HelloElementorChild
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Obtiene el valor de una opción de Google de forma segura.
 *
 * @param  string $key     Clave de la opción (sin prefijo).
 * @param  string $default Valor por defecto.
 * @return string
 */
function nil_google_get_option( string $key, string $default = '' ): string {
	$options = get_option( 'nil_google_integrations', array() );
	return isset( $options[ $key ] ) ? (string) $options[ $key ] : $default;
}

/**
 * Comprueba si un ID de GTM tiene formato válido (GTM-XXXXXXX).
 *
 * @param  string $id ID a validar.
 * @return bool
 */
function nil_google_is_valid_gtm_id( string $id ): bool {
	return (bool) preg_match( '/^GTM-[A-Z0-9]+$/', $id );
}

/**
 * Comprueba si un Measurement ID de GA4 tiene formato válido (G-XXXXXXXXXX).
 *
 * @param  string $id ID a validar.
 * @return bool
 */
function nil_google_is_valid_ga4_id( string $id ): bool {
	return (bool) preg_match( '/^G-[A-Z0-9]+$/', $id );
}
