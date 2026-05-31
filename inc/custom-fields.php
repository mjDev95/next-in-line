<?php
/**
 * Campos personalizados (meta boxes) para tipos de contenido nativos de WordPress.
 * Este archivo NO contiene lógica de CPTs — eso está en custom-posts.php.
 */

/* ══════════════════════════════════════════
   PÁGINAS — Hero Video de portada
══════════════════════════════════════════ */

add_action( 'add_meta_boxes', 'nil_hero_video_meta_box' );
function nil_hero_video_meta_box() {
	add_meta_box(
		'nil_hero_video',
		__( 'Hero Video (portada)', 'hello-elementor-child' ),
		'nil_hero_video_meta_box_html',
		'page',
		'side',
		'high'
	);
}

function nil_hero_video_meta_box_html( $post ) {
	wp_nonce_field( 'nil_save_hero_video', 'nil_hero_video_nonce' );
	$value = get_post_meta( $post->ID, 'nil_home_bg_video', true );
	?>
	<p>
		<label for="nil_home_bg_video" style="display:block;margin-bottom:4px;font-weight:600;">
			<?php esc_html_e( 'URL del vídeo (.mp4)', 'hello-elementor-child' ); ?>
		</label>
		<input
			type="url"
			id="nil_home_bg_video"
			name="nil_home_bg_video"
			value="<?php echo esc_attr( $value ); ?>"
			placeholder="https://example.com/video.mp4"
			style="width:100%;"
		/>
		<span class="description" style="font-size:11px;">
			<?php esc_html_e( 'Vídeo de fondo del hero. Solo se usa cuando esta página es la portada.', 'hello-elementor-child' ); ?>
		</span>
	</p>
	<?php
}

add_action( 'save_post_page', 'nil_save_hero_video_meta' );
function nil_save_hero_video_meta( $post_id ) {
	if (
		! isset( $_POST['nil_hero_video_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( $_POST['nil_hero_video_nonce'] ), 'nil_save_hero_video' ) ||
		( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
		! current_user_can( 'edit_post', $post_id )
	) {
		return;
	}

	if ( isset( $_POST['nil_home_bg_video'] ) && '' !== $_POST['nil_home_bg_video'] ) {
		update_post_meta( $post_id, 'nil_home_bg_video', esc_url_raw( wp_unslash( $_POST['nil_home_bg_video'] ) ) );
	} else {
		delete_post_meta( $post_id, 'nil_home_bg_video' );
	}
}
