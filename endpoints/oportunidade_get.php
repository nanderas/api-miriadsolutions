<?php

function oportunidade_scheme($slug) {
  $post_id = get_oportunidade_id_by_slug($slug);
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
      "oportunidade_fotos" => $images_array,
      "oportunidade_cliente" => $post_meta['oportunidade_cliente'][0],
      "oportunidade_contato" => $post_meta['oportunidade_contato'][0],
      "oportunidade_descricao" => $post_meta['oportunidade_descricao'][0],
      "oportunidade_servico" => $post_meta['oportunidade_servico'][0],
      "oportunidade_status" => $post_meta['oportunidade_status'][0],
      "oportunidade_valor" => $post_meta['oportunidade_valor'][0],
      "usuario_id" => $post_meta['usuario_id'][0],
    );

  } else {
    $response = new WP_Error('naoexiste', 'Oportunidade não encontrado.', array('status' => 404));
  }
  return $response;
}

function api_oportunidade_get($request) {
  $response = oportunidade_scheme($request["slug"]);
  return rest_ensure_response($response);
}

function registrar_api_oportunidade_get() {
  register_rest_route('api', '/oportunidade/(?P<slug>[-\w]+)', array(
    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'api_oportunidade_get',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_oportunidade_get');

// API oportunidadeS
function api_oportunidades_get($request) {

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
    'post_type' => 'oportunidade',
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

  $oportunidades = array();
  foreach ($posts as $key => $value) {
    $oportunidades[] = oportunidade_scheme($value->post_name);
  }

  $response = rest_ensure_response($oportunidades);
  $response->header('X-Total-Count', $total);

  return $response;
}

function registrar_api_oportunidades_get() {
  register_rest_route('api', '/oportunidade', array(
    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'api_oportunidades_get',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_oportunidades_get');


?>