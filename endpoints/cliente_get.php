<?php

function cliente_scheme($slug) {
  $post_id = get_cliente_id_by_slug($slug);
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
      "cliente_fotos" => $images_array,
      "cliente_email" => $post_meta['cliente_email'][0],
      "cliente_cnpj" => $post_meta['cliente_cnpj'][0],
      "cliente_nome" => $post_meta['cliente_nome'][0],
      "cliente_url" => $post_meta['cliente_url'][0],
      "cliente_cep" => $post_meta['cliente_cep'][0],
      "cliente_rua" => $post_meta['cliente_rua'][0],
      "cliente_numero" => $post_meta['cliente_numero'][0],
      "cliente_complemento" => $post_meta['cliente_complemento'][0],
      "cliente_bairro" => $post_meta['cliente_bairro'][0],
      "cliente_cidade" => $post_meta['cliente_cidade'][0],
      "cliente_estado" => $post_meta['cliente_estado'][0],
      "usuario_id" => $post_meta['usuario_id'][0],
    );

  } else {
    $response = new WP_Error('naoexiste', 'Cliente não encontrado.', array('status' => 404));
  }
  return $response;
}

function api_cliente_get($request) {
  $response = cliente_scheme($request["slug"]);
  return rest_ensure_response($response);
}

function registrar_api_cliente_get() {
  register_rest_route('api', '/cliente/(?P<slug>[-\w]+)', array(
    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'api_cliente_get',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_cliente_get');

// API clienteS
function api_clientes_get($request) {

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
    'post_type' => 'cliente',
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

  $clientes = array();
  foreach ($posts as $key => $value) {
    $clientes[] = cliente_scheme($value->post_name);
  }

  $response = rest_ensure_response($clientes);
  $response->header('X-Total-Count', $total);

  return $response;
}

function registrar_api_clientes_get() {
  register_rest_route('api', '/cliente', array(
    array(
      'methods' => WP_REST_Server::READABLE,
      'callback' => 'api_clientes_get',
    ),
  ));
}
add_action('rest_api_init', 'registrar_api_clientes_get');


?>