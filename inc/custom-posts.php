<?php

add_action( 'init', 'nil_post_type' );

function nil_post_type() {

	$labels_modelos = array(
		'name'                  => __( 'Modelos', 'hello-elementor-child' ),
		'singular_name'         => __( 'Modelo', 'hello-elementor-child' ),
		'all_items'             => __( 'Todos los modelos', 'hello-elementor-child' ),
		'add_new_item'          => __( 'Añadir nuevo modelo', 'hello-elementor-child' ),
		'add_new'               => __( 'Añadir nuevo', 'hello-elementor-child' ),
		'new_item'              => __( 'Nuevo modelo', 'hello-elementor-child' ),
		'edit_item'             => __( 'Editar modelo', 'hello-elementor-child' ),
		'update_item'           => __( 'Actualizar modelo', 'hello-elementor-child' ),
		'view_item'             => __( 'Ver modelo', 'hello-elementor-child' ),
		'view_items'            => __( 'Ver modelos', 'hello-elementor-child' ),
		'search_items'          => __( 'Buscar modelos', 'hello-elementor-child' ),
		'not_found'             => __( 'No se encontraron modelos', 'hello-elementor-child' ),
		'not_found_in_trash'    => __( 'No se encontraron modelos en la papelera', 'hello-elementor-child' ),
		'featured_image'        => __( 'Imagen destacada', 'hello-elementor-child' ),
		'set_featured_image'    => __( 'Establecer imagen destacada', 'hello-elementor-child' ),
		'remove_featured_image' => __( 'Eliminar imagen destacada', 'hello-elementor-child' ),
		'use_featured_image'    => __( 'Usar como imagen destacada', 'hello-elementor-child' ),
	);

	register_post_type( 'modelos', array(
		'labels'              => $labels_modelos,
		'hierarchical'        => true,
		'public'              => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => false, // Desactiva el archivo del post type
		'menu_icon'           => 'dashicons-groups',
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'order', 'custom-fields', 'taxonomies' ),
		'taxonomies'          => array( 'tipo-modelo' ),
		'show_in_rest'        => true,
		'rewrite'             => false,
	) );

	$labels_tipo_modelo = array(
		'name'                       => __( 'Tipos de Modelo', 'hello-elementor-child' ),
		'singular_name'              => __( 'Tipo de Modelo', 'hello-elementor-child' ),
		'new_item_name'              => __( 'Nuevo tipo de modelo', 'hello-elementor-child' ),
		'add_new_item'               => __( 'Añadir nuevo tipo de modelo', 'hello-elementor-child' ),
		'edit_item'                  => __( 'Editar tipo de modelo', 'hello-elementor-child' ),
		'update_item'                => __( 'Actualizar tipo de modelo', 'hello-elementor-child' ),
		'view_item'                  => __( 'Ver tipo de modelo', 'hello-elementor-child' ),
		'separate_items_with_commas' => __( 'Separar tipos con comas', 'hello-elementor-child' ),
		'add_or_remove_items'        => __( 'Añadir o eliminar tipos', 'hello-elementor-child' ),
		'choose_from_most_used'      => __( 'Elegir de los más usados', 'hello-elementor-child' ),
		'popular_items'              => __( 'Tipos populares', 'hello-elementor-child' ),
		'search_items'               => __( 'Buscar tipos de modelo', 'hello-elementor-child' ),
		'not_found'                  => __( 'No se encontraron tipos', 'hello-elementor-child' ),
		'no_terms'                   => __( 'Sin tipos de modelo', 'hello-elementor-child' ),
	);

	register_taxonomy( 'tipo-modelo', array( 'modelos' ), array(
		'labels'             => $labels_tipo_modelo,
		'hierarchical'       => true,
		'show_ui'            => true,
		'show_in_nav_menus'  => true,
		'show_tagcloud'      => true,
		'show_in_quick_edit' => true,
		'show_admin_column'  => true,
		'show_in_rest'       => true,
		'publicly_queryable' => true,
		'query_var'          => 'tipo_modelo', // sin guión: PHP parse_str convierte - a _
		'rewrite'            => false,        // manejamos rewrite manualmente
	) );
}

add_action( 'init', 'nil_modelos_rewrite_rules', 20 );
function nil_modelos_rewrite_rules() {
	$terms = get_terms( array( 'taxonomy' => 'tipo-modelo', 'hide_empty' => false ) );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return;
	}
	$slugs = array_map( function( $t ) { return preg_quote( $t->slug, '/' ); }, $terms );
	$group = '(' . implode( '|', $slugs ) . ')';

	// Single modelo: /{term-slug}/{post-slug}/
	add_rewrite_rule( "^{$group}/([^/]+)/?$", 'index.php?modelos=$matches[2]', 'top' );

	// Archivo taxonomía: /{term-slug}/ (con paginación)
	// Usamos tipo_modelo (underscore) porque PHP parse_str convierte - a _
	add_rewrite_rule( "^{$group}/page/([0-9]+)/?$", 'index.php?tipo_modelo=$matches[1]&paged=$matches[2]', 'top' );
	add_rewrite_rule( "^{$group}/?$",               'index.php?tipo_modelo=$matches[1]', 'top' );
}

// Genera la URL correcta de cada término: /{term-slug}/
add_filter( 'term_link', 'nil_tipo_modelo_link', 10, 3 );
function nil_tipo_modelo_link( $url, $term, $taxonomy ) {
	if ( $taxonomy !== 'tipo-modelo' ) {
		return $url;
	}
	return home_url( '/' . $term->slug . '/' );
}

// Sin este hook WP busca post_type=post en el archivo de taxonomía
// y no encuentra los modelos.
add_action( 'pre_get_posts', 'nil_taxonomy_post_type' );
function nil_taxonomy_post_type( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_tax( 'tipo-modelo' ) ) {
		$query->set( 'post_type', 'modelos' );
	}
}

add_filter( 'post_type_link', 'nil_modelos_permalink', 10, 2 );
function nil_modelos_permalink( $link, $post ) {
	if ( $post->post_type !== 'modelos' ) {
		return $link;
	}
	$terms = get_the_terms( $post->ID, 'tipo-modelo' );
	$slug  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : 'modelo';
	return home_url( '/' . $slug . '/' . $post->post_name . '/' );
}

add_action( 'created_tipo-modelo', 'nil_flush_modelos_rewrite' );
add_action( 'deleted_tipo-modelo', 'nil_flush_modelos_rewrite' );
add_action( 'edited_tipo-modelo',  'nil_flush_modelos_rewrite' );
function nil_flush_modelos_rewrite() {
	flush_rewrite_rules();
}

add_filter( 'elementor/utils/get_public_post_types', 'nil_exclude_modelos_from_elementor' );
function nil_exclude_modelos_from_elementor( $post_types ) {
	unset( $post_types['modelos'] );
	return $post_types;
}

add_filter( 'use_block_editor_for_post_type', 'nil_disable_block_editor_modelos', 10, 2 );
function nil_disable_block_editor_modelos( $use_block_editor, $post_type ) {
	if ( $post_type === 'modelos' ) {
		return false;
	}
	return $use_block_editor;
}

function crear_metabox_datos_modelo() {
	add_meta_box( 'datos_modelo', __( 'Datos del Modelo', 'hello-elementor-child' ), 'mostrar_metabox_datos_modelo', 'modelos', 'side', 'high' );
}

function mostrar_metabox_datos_modelo( $post ) {
    // 1. Recuperamos los valores de la base de datos
    $height = get_post_meta( $post->ID, 'height', true );
    $bust   = get_post_meta( $post->ID, 'bust', true );
    $waist  = get_post_meta( $post->ID, 'waist', true );
    $hips   = get_post_meta( $post->ID, 'hips', true );
    $suit   = get_post_meta( $post->ID, 'suit', true );
    $shirt  = get_post_meta( $post->ID, 'shirt', true );
    $pants  = get_post_meta( $post->ID, 'pants', true );
    $shoe   = get_post_meta( $post->ID, 'shoe', true );
    $hair   = get_post_meta( $post->ID, 'hair', true );
    $eyes   = get_post_meta( $post->ID, 'eyes', true );
    ?>
    <p>
        <label><strong><?php _e( 'ALTURA', 'hello-elementor-child' ); ?></strong></label><br>
        <input type="text" name="height" value="<?php echo esc_attr( $height ); ?>" style="width: 100%;" placeholder="<?php esc_attr_e( 'Ej. 1.75', 'hello-elementor-child' ); ?>">
    </p>
    <p>
        <label><strong><?php _e( 'BUSTO', 'hello-elementor-child' ); ?></strong></label><br>
        <input type="text" name="bust" value="<?php echo esc_attr( $bust ); ?>" style="width: 100%;" placeholder="<?php esc_attr_e( 'Ej. 90', 'hello-elementor-child' ); ?>">
    </p>
    <p>
        <label><strong><?php _e( 'CINTURA', 'hello-elementor-child' ); ?></strong></label><br>
        <input type="text" name="waist" value="<?php echo esc_attr( $waist ); ?>" style="width: 100%;" placeholder="<?php esc_attr_e( 'Ej. 64', 'hello-elementor-child' ); ?>">
    </p>
    <p>
        <label><strong><?php _e( 'CADERA', 'hello-elementor-child' ); ?></strong></label><br>
        <input type="text" name="hips" value="<?php echo esc_attr( $hips ); ?>" style="width: 100%;" placeholder="<?php esc_attr_e( 'Ej. 94', 'hello-elementor-child' ); ?>">
    </p>
    <p>
        <label><strong><?php _e( 'SACO', 'hello-elementor-child' ); ?></strong></label><br>
        <input type="text" name="suit" value="<?php echo esc_attr( $suit ); ?>" style="width: 100%;" placeholder="<?php esc_attr_e( 'Ej. 40R', 'hello-elementor-child' ); ?>">
    </p>
    <p>
        <label><strong><?php _e( 'CAMISA', 'hello-elementor-child' ); ?></strong></label><br>
        <input type="text" name="shirt" value="<?php echo esc_attr( $shirt ); ?>" style="width: 100%;" placeholder="<?php esc_attr_e( 'Ej. 40', 'hello-elementor-child' ); ?>">
    </p>
    <p>
        <label><strong><?php _e( 'PANTALÓN', 'hello-elementor-child' ); ?></strong></label><br>
        <input type="text" name="pants" value="<?php echo esc_attr( $pants ); ?>" style="width: 100%;" placeholder="<?php esc_attr_e( 'Ej. 34', 'hello-elementor-child' ); ?>">
    </p>
    <p>
        <label><strong><?php _e( 'ZAPATO', 'hello-elementor-child' ); ?></strong></label><br>
        <input type="text" name="shoe" value="<?php echo esc_attr( $shoe ); ?>" style="width: 100%;" placeholder="<?php esc_attr_e( 'Ej. 26', 'hello-elementor-child' ); ?>">
    </p>
    <p>
        <label><strong><?php _e( 'CABELLO', 'hello-elementor-child' ); ?></strong></label><br>
        <input type="text" name="hair" value="<?php echo esc_attr( $hair ); ?>" style="width: 100%;" placeholder="<?php esc_attr_e( 'Ej. Castaño Claro', 'hello-elementor-child' ); ?>">
    </p>
    <p>
        <label><strong><?php _e( 'OJOS', 'hello-elementor-child' ); ?></strong></label><br>
        <input type="text" name="eyes" value="<?php echo esc_attr( $eyes ); ?>" style="width: 100%;" placeholder="<?php esc_attr_e( 'Ej. Verdes', 'hello-elementor-child' ); ?>">
    </p>
    <?php
}

function guardar_campos_datos_modelo( $post_id ) {
    // 2. Guardamos todos los campos unificados
    if ( isset( $_POST['height'] ) ) {
        update_post_meta( $post_id, 'height', sanitize_text_field( $_POST['height'] ) );
    }
    if ( isset( $_POST['bust'] ) ) {
        update_post_meta( $post_id, 'bust', sanitize_text_field( $_POST['bust'] ) );
    }
    if ( isset( $_POST['waist'] ) ) {
        update_post_meta( $post_id, 'waist', sanitize_text_field( $_POST['waist'] ) );
    }
    if ( isset( $_POST['hips'] ) ) {
        update_post_meta( $post_id, 'hips', sanitize_text_field( $_POST['hips'] ) );
    }
    if ( isset( $_POST['suit'] ) ) {
        update_post_meta( $post_id, 'suit', sanitize_text_field( $_POST['suit'] ) );
    }
    if ( isset( $_POST['shirt'] ) ) {
        update_post_meta( $post_id, 'shirt', sanitize_text_field( $_POST['shirt'] ) );
    }
    if ( isset( $_POST['pants'] ) ) {
        update_post_meta( $post_id, 'pants', sanitize_text_field( $_POST['pants'] ) );
    }
    if ( isset( $_POST['shoe'] ) ) {
        update_post_meta( $post_id, 'shoe', sanitize_text_field( $_POST['shoe'] ) );
    }
    if ( isset( $_POST['hair'] ) ) {
        update_post_meta( $post_id, 'hair', sanitize_text_field( $_POST['hair'] ) );
    }
    if ( isset( $_POST['eyes'] ) ) {
        update_post_meta( $post_id, 'eyes', sanitize_text_field( $_POST['eyes'] ) );
    }
}

add_action( 'add_meta_boxes', 'crear_metabox_datos_modelo' );
add_action( 'save_post', 'guardar_campos_datos_modelo' );

/*
 * --------------------------------------------------------------------------
 * GALERÍA MULTIMEDIA (FOTOS + VIDEOS)
 * --------------------------------------------------------------------------
 */


// Agregar el metabox para la galería
function agregar_metabox_galeria() {
  add_meta_box(
      'galeria_modelo', // ID único del metabox
      'Galería del Modelo', // Título del metabox
      'mostrar_metabox_galeria', // Función que muestra el contenido del metabox
      'modelos', // Tipo de post al que se aplica el metabox
      'side', // Contexto donde se muestra el metabox
      'high' // Prioridad del metabox
  );
}
add_action('add_meta_boxes', 'agregar_metabox_galeria');

// Mostrar los campos del metabox
function mostrar_metabox_galeria($post) {
  // Obtener los datos actuales del campo
  $galeria = get_post_meta($post->ID, '_galeria', true);
  ?>
  <p>
      <label for="galeria"><?php _e('Selecciona imágenes y videos', 'hello-elementor-child'); ?></label>
  </p>
  <input type="hidden" id="galeria" name="galeria" value="<?php echo esc_attr($galeria); ?>">
  <div id="galeria_preview"  style="margin-top:16px;">
      <?php if ($galeria) : ?>
          <?php
          $galeria_ids = explode(',', $galeria);
          foreach ($galeria_ids as $id) {
              $attachment = wp_get_attachment_url($id);
              $mime_type = get_post_mime_type($id);
              if (strpos($mime_type, 'image') !== false) {
                  echo '<img src="' . esc_url($attachment) . '" style="object-fit: cover;   width: 25%; aspect-ratio: 1;" />';
              } elseif (strpos($mime_type, 'video') !== false) {
                  echo '<video style="object-fit: cover;width: 50%;aspect-ratio: 1/1;" controls>
                            <source src="' . esc_url($attachment) . '" type="' . esc_attr($mime_type) . '">
                        </video>';
              }
          }
          ?>
      <?php endif; ?>
  </div>
  <button type="button" class="button" id="cargar_galeria"><?php _e('Agregar', 'hello-elementor-child'); ?></button>
  <?php
  // Añadir el script para la selección de archivos
  ?>
    <script>
        jQuery(document).ready(function($) {
            var frame;
            var selection = [];

            // Abrir el media frame
            $('#cargar_galeria').on('click', function(e) {
                e.preventDefault();

                // Si el frame ya está abierto, solo reabrirlo
                if (frame) {
                    frame.open();
                    return;
                }

                // Crear un nuevo media frame
                frame = wp.media({
                    title: 'Selecciona Imágenes y Videos',
                    button: {
                        text: 'Seleccionar',
                    },
                    multiple: true
                });

                // Cargar la selección previa si existe
                frame.on('open', function() {
                    var selectedIds = $('#galeria').val().split(',');
                    var selection = frame.state().get('selection');
                    
                    selectedIds.forEach(function(id) {
                        var attachment = wp.media.attachment(id);
                        attachment.fetch();
                        selection.add(attachment ? [attachment] : []);
                    });
                });

                // Actualizar el campo y el preview al seleccionar archivos
                frame.on('select', function() {
                    var attachments = frame.state().get('selection').toJSON();
                    var ids = [];

                    $('#galeria_preview').empty(); // Limpiar preview
                    attachments.forEach(function(attachment) {
                        ids.push(attachment.id);
                        var mime_type = attachment.mime;
                        if (mime_type.indexOf('image') !== -1) {
                            $('#galeria_preview').append('<img src="' + attachment.url + '" style="object-fit: cover; width: 25%; aspect-ratio: 1;" />');
                        } else if (mime_type.indexOf('video') !== -1) {
                            $('#galeria_preview').append('<video style="object-fit: cover;width: 100%;aspect-ratio: 2/1;" controls><source src="' + attachment.url + '" type="' + mime_type + '"></video>');
                        }
                    });

                    $('#galeria').val(ids.join(','));
                });

                frame.open();
            });
        });
    </script>
  <?php
}

// Guardar los campos personalizados cuando se guarda el post
function guardar_metabox_galeria($post_id) {
  if (isset($_POST['galeria'])) {
      update_post_meta($post_id, '_galeria', sanitize_text_field($_POST['galeria']));
  }
}
add_action('save_post', 'guardar_metabox_galeria');


/*
 * Fuerza que los metaboxes principales de Modelos siempre sean visibles,
 * aunque el usuario los haya ocultado desde "Opciones de pantalla".
 */
add_filter( 'hidden_meta_boxes', 'nil_force_visible_metaboxes', 10, 2 );
function nil_force_visible_metaboxes( $hidden, $screen ) {
	if ( isset( $screen->post_type ) && $screen->post_type === 'modelos' ) {
		$hidden = array_diff(
			$hidden,
			array(
				'datos_modelo',
				'galeria_media'
			)
		);
	}
	return $hidden;
}

/* Imagen para términos de tipo-modelo */
add_action( 'tipo-modelo_add_form_fields', 'nil_tipo_modelo_add_image_field' );
function nil_tipo_modelo_add_image_field() {
	?>
	<div class="form-field">
		<label for="tipo_modelo_image_id"><?php esc_html_e( 'Imagen', 'hello-elementor-child' ); ?></label>
		<input type="hidden" id="tipo_modelo_image_id" name="tipo_modelo_image_id" value="">
		<div class="nil-term-image-preview" style="margin-bottom:10px;"></div>
		<button type="button" class="button nil-upload-term-image"><?php esc_html_e( 'Seleccionar imagen', 'hello-elementor-child' ); ?></button>
	</div>
	<?php
}

add_action( 'tipo-modelo_edit_form_fields', 'nil_tipo_modelo_edit_image_field' );
function nil_tipo_modelo_edit_image_field( $term ) {
	$image_id = get_term_meta( $term->term_id, 'image_id', true );
	?>
	<tr class="form-field">
		<th scope="row">
			<label for="tipo_modelo_image_id"><?php esc_html_e( 'Imagen', 'hello-elementor-child' ); ?></label>
		</th>
		<td>
			<input type="hidden" id="tipo_modelo_image_id" name="tipo_modelo_image_id" value="<?php echo esc_attr( $image_id ); ?>">
			<div class="nil-term-image-preview"></div>
			<?php if ( $image_id ) : ?>
				<div style="margin-bottom:10px;">
					<?php echo wp_get_attachment_image( $image_id, 'medium', false, array( 'style' => 'max-width:200px;height:auto;' ) ); ?>
				</div>
			<?php endif; ?>
			<button type="button" class="button nil-upload-term-image"><?php esc_html_e( 'Seleccionar imagen', 'hello-elementor-child' ); ?></button>
		</td>
	</tr>
	<?php
}

add_action( 'created_tipo-modelo', 'nil_save_tipo_modelo_image' );
add_action( 'edited_tipo-modelo', 'nil_save_tipo_modelo_image' );
function nil_save_tipo_modelo_image( $term_id ) {
	if ( isset( $_POST['tipo_modelo_image_id'] ) ) {
		update_term_meta( $term_id, 'image_id', absint( $_POST['tipo_modelo_image_id'] ) );
	}
}

add_action( 'admin_enqueue_scripts', 'nil_tipo_modelo_media_scripts' );
function nil_tipo_modelo_media_scripts() {
	$screen = get_current_screen();

	if ( ! $screen || $screen->taxonomy !== 'tipo-modelo' ) {
		return;
	}

	wp_enqueue_media();

	wp_add_inline_script( 'jquery-core', "jQuery(function($){
		$(document).on('click','.nil-upload-term-image',function(e){
			e.preventDefault();
			var frame = wp.media({title:'Seleccionar imagen',multiple:false});
			frame.on('select',function(){
				var attachment = frame.state().get('selection').first().toJSON();
				$('#tipo_modelo_image_id').val(attachment.id);
				$('.nil-term-image-preview').html('<img src=\"'+attachment.url+'\" style=\"max-width:200px;height:auto;\" />');
			});
			frame.open();
		});
	});" );
}
