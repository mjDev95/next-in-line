<?php

function crear_metabox_galeria_fotos() {
	add_meta_box( 'galeria_fotos', __( 'Galería de Fotos', 'hello-elementor-child' ), 'mostrar_metabox_galeria_fotos', 'modelos', 'normal' );
}

function mostrar_metabox_galeria_fotos( $post ) {
	$ids_string = get_post_meta( $post->ID, 'galeria_fotos', true );
	$ids        = $ids_string ? explode( ',', $ids_string ) : array();
	?>
	<div id="nil-gallery-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px;">
		<?php foreach ( $ids as $id ) :
			$src = wp_get_attachment_image_src( $id, 'thumbnail' );
			if ( $src ) : ?>
				<div class="nil-gallery-item" data-id="<?php echo esc_attr( $id ); ?>" style="position:relative;">
					<img src="<?php echo esc_url( $src[0] ); ?>" style="width:80px;height:80px;object-fit:cover;display:block;">
					<span class="nil-remove-img dashicons dashicons-trash" style="position:absolute;top:2px;right:2px;cursor:pointer;background:#fff;border-radius:50%;padding:2px;"></span>
				</div>
			<?php endif;
		endforeach; ?>
	</div>
	<input type="hidden" id="galeria_fotos_ids" name="galeria_fotos" value="<?php echo esc_attr( $ids_string ); ?>">
	<button type="button" id="btn-galeria-fotos" class="button"><?php _e( 'Añadir imágenes', 'hello-elementor-child' ); ?></button>
	<?php
}

function guardar_campos_galeria_fotos( $post_id ) {
	if ( isset( $_POST['galeria_fotos'] ) ) {
		update_post_meta( $post_id, 'galeria_fotos', sanitize_text_field( $_POST['galeria_fotos'] ) );
	}
}

function crear_metabox_galeria_videos() {
	add_meta_box( 'galeria_videos', __( 'Galería de Videos', 'hello-elementor-child' ), 'mostrar_metabox_galeria_videos', 'modelos', 'normal' );
}

function mostrar_metabox_galeria_videos( $post ) {
	$videos = get_post_meta( $post->ID, 'galeria_videos', true );
	if ( ! is_array( $videos ) ) {
		$videos = array();
	}
	?>
	<div id="nil-videos-list">
		<?php foreach ( $videos as $url ) : ?>
			<div class="nil-video-row" style="margin-bottom:6px;">
				<input type="text" name="galeria_videos[]" value="<?php echo esc_attr( $url ); ?>" placeholder="https://..." style=" width: 90%; ">
				<button type="button" class="btn-remove-video button">✕</button>
			</div>
		<?php endforeach; ?>
	</div>
	<button type="button" id="btn-add-video" class="button" style="margin-top:8px;"><?php _e( 'Añadir video', 'hello-elementor-child' ); ?></button>
	<?php
}

function guardar_campos_galeria_videos( $post_id ) {
	if ( isset( $_POST['galeria_videos'] ) ) {
		$videos = array_map( 'sanitize_url', $_POST['galeria_videos'] );
		$videos = array_values( array_filter( $videos ) );
		update_post_meta( $post_id, 'galeria_videos', $videos );
	} else {
		delete_post_meta( $post_id, 'galeria_videos' );
	}
}

add_action( 'add_meta_boxes', 'crear_metabox_galeria_fotos' );
add_action( 'save_post', 'guardar_campos_galeria_fotos' );
add_action( 'add_meta_boxes', 'crear_metabox_galeria_videos' );
add_action( 'save_post', 'guardar_campos_galeria_videos' );
