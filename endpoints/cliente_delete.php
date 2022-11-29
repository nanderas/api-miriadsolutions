<?php

function api_cliente_delete($request) {
  $slug = $request['slug'];

  $cliente_id = get_cliente_id_by_slug($slug);
  $user = wp_get_current_user();

  $author_id = (int) get_post_field('post_author', $cliente_id);
  $user_id = (int) $user->ID;

  if($user_id === $author_id) {

    $images = get_attached_media('image', $cliente_id);
    if($images) {
      foreach($images as $key => $value) {
        wp_delete_attachment($value->ID, true);
      }
    }

    $response = wp_delete_post($cliente_id, true);

  } else {
    $response = new WP_Error('permissao', 'Usuário não possui permissão.', array('status' => 401));
  }
  return rest_ensure_response($response);
}

function registrar_api_cliente_delete() {
  register_rest_route('api', '/cliente/(?P<slug>[-\w]+)', array(
    array(
      'methods' => WP_REST_Server::DELETABLE,
      'callback' => 'api_cliente_delete',
    ),
  ));
}

add_action('rest_api_init', 'registrar_api_cliente_delete');


?>