<?php

function contato_scheme($slug) {
  $post_id = get_contato_id_by_slug($slug);
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
      "contato_fotos" => $images_array,
      "contato_email" => $post_meta['contato_email'][0],
      "contato_cnpj" => $post_meta['contato_cnpj'][0],
      "contato_nome" => $post_meta['contato_nome'][0],
      "contato_url" => $post_meta['contato_url'][0],
      "contato_cep" => $post_meta['contato_cep'][0],
      "contato_rua" => $post_meta['contato_rua'][0],
      "contato_numero" => $post_meta['contato_numero'][0],
      "contato_complemento" => $post_meta['contato_complemento'][0],
      "contato_bairro" => $post_meta['contato_bairro'][0],
      "contato_cidade" => $post_meta['contato_cidade'][0],
      "contato_estado" => $post_meta['contato_estado'][0],
      "usuario_id" => $post_meta['usuario_id'][0],
    );

  } else {
    $response = new WP_Error('naoexiste', 'Contato não encontrado.', array('status' => 404));
  }
  return $response;
}

function api_contato_get($request) {
  $response = contato_scheme($request["slug"]);
  return rest_ensure_response($response);
}

function registrar_api_contato_get() {
  register_rest_route('api', '/contato/(?P<slug>[-\w]+)', array(
    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'api_contato_get',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_contato_get');

// API contatoS
function api_contatos_get($request) {

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
    'post_type' => 'contato',
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

  $contatos = array();
  foreach ($posts as $key => $value) {
    $contatos[] = contato_scheme($value->post_name);
  }

  $response = rest_ensure_response($contatos);
  $response->header('X-Total-Count', $total);

  return $response;
}

function registrar_api_contatos_get() {
  register_rest_route('api', '/contato', array(
    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'api_contatos_get',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_contatos_get');


?>