<?php

function comentario_scheme($slug) {
  $post_id = get_comentario_id_by_slug($slug);
  if($post_id) {
    $post_meta = get_post_meta($post_id);

    $images = get_attached_media('image', $post_id);
    $images_array = null;

    if($images) {
      $images_array = array();
      foreach($images as $key => $value) {
        $images_array[] = array(
          'titulo' => $value->post_name,
          'src' => $value->guid,
        );
      }
    }

    $response = array(
      "id" => $slug, 
      "comentario_fotos" => $images_array,
      "comentario_cliente" => $post_meta['comentario_cliente'][0],
      "comentario_oportunidade" => $post_meta['comentario_oportunidade'][0],
      "comentario_descricao" => $post_meta['comentario_descricao'][0],
      "comentario_url" => $post_meta['comentario_url'][0],
      "usuario_id" => $post_meta['usuario_id'][0],
    );

  } else {
    $response = new WP_Error('naoexiste', 'comentario não encontrado.', array('status' => 404));
  }
  return $response;
}

function api_comentario_get($request) {
  $response = comentario_scheme($request["slug"]);
  return rest_ensure_response($response);
}

function registrar_api_comentario_get() {
  register_rest_route('api', '/comentario/(?P<slug>[-\w]+)', array(
    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'api_comentario_get',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_comentario_get');

// API comentarioS
function api_comentarios_get($request) {

  $q = sanitize_text_field($request['q']) ?: '';
  $_page = sanitize_text_field($request['_page']) ?: 0;
  $_limit = sanitize_text_field($request['_limit']) ?: 9;
  $usuario_id = sanitize_text_field($request['usuario_id']);

  $usuario_id_query = null;
  if($usuario_id) {
    $usuario_id_query = array(
      'key' => 'usuario_id',
      'value' => $usuario_id,
      'compare' => '='
    );
  }

  $query = array(
    'post_type' => 'comentario',
    'posts_per_page' => $_limit,
    'paged' => $_page,
    's' => $q,
    'meta_query' => array(
      $usuario_id_query
    )
  );

  $loop = new WP_Query($query);
  $posts = $loop->posts;
  $total = $loop->found_posts;

  $comentarios = array();
  foreach ($posts as $key => $value) {
    $comentarios[] = comentario_scheme($value->post_name);
  }

  $response = rest_ensure_response($comentarios);
  $response->header('X-Total-Count', $total);

  return $response;
}

function registrar_api_comentarios_get() {
  register_rest_route('api', '/comentario', array(
    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'api_comentarios_get',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_comentarios_get');


?>