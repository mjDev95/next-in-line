jQuery( function ( $ ) {

	// ── Photo gallery ───────────────────────────────────────────────

	var mediaFrame;

	$( document ).on( 'click', '#btn-galeria-fotos', function ( e ) {
		e.preventDefault();

		if ( mediaFrame ) {
			mediaFrame.open();
			return;
		}

		mediaFrame = wp.media( {
			title: 'Seleccionar imágenes',
			button: { text: 'Añadir a la galería' },
			multiple: true,
		} );

		mediaFrame.on( 'select', function () {
			var attachments = mediaFrame.state().get( 'selection' ).toArray();
			var newIds      = [];
			var html        = '';

			attachments.forEach( function ( attachment ) {
				var att   = attachment.toJSON();
				var thumb = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
				newIds.push( att.id );
				html +=
					'<div class="nil-gallery-item" data-id="' + att.id + '" style="position:relative;">' +
					'<img src="' + thumb + '" style="width:80px;height:80px;object-fit:cover;display:block;">' +
					'<span class="nil-remove-img dashicons dashicons-trash" style="position:absolute;top:2px;right:2px;cursor:pointer;background:#fff;border-radius:50%;padding:2px;"></span>' +
					'</div>';
			} );

			var existing = $( '#galeria_fotos_ids' ).val();
			var allIds   = existing ? existing.split( ',' ).concat( newIds ) : newIds;
			$( '#galeria_fotos_ids' ).val( allIds.join( ',' ) );
			$( '#nil-gallery-preview' ).append( html );
		} );

		mediaFrame.open();
	} );

	$( document ).on( 'click', '.nil-remove-img', function () {
		var item   = $( this ).closest( '.nil-gallery-item' );
		var id     = String( item.data( 'id' ) );
		var allIds = $( '#galeria_fotos_ids' ).val().split( ',' ).filter( function ( v ) {
			return v !== id;
		} );
		$( '#galeria_fotos_ids' ).val( allIds.join( ',' ) );
		item.remove();
	} );

	// ── Video repeater ──────────────────────────────────────────────

	$( document ).on( 'click', '#btn-add-video', function ( e ) {
		e.preventDefault();
		var row =
			'<div class="nil-video-row" style="margin-bottom:6px;">' +
			'<input type="text" name="galeria_videos[]" placeholder="https://..." style=" width: 90%; ">' +
			' <button type="button" class="btn-remove-video button">✕</button>' +
			'</div>';
		$( '#nil-videos-list' ).append( row );
	} );

	$( document ).on( 'click', '.btn-remove-video', function () {
		$( this ).closest( '.nil-video-row' ).remove();
	} );

} );
