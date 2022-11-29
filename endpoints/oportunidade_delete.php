<?php

function api_oportunidade_delete($request) {
  $slug = $request['slug'];

  $oportunidade_id = get_oportunidade_id_by_slug($slug);
  $user = wp_get_current_user();

  $author_id = (int) get_post_field('post_author', $oportunidade_id);
  $user_id = (int) $user->ID;

  if($user_id === $author_id) {

    $images = get_attached_media('image', $oportunidade_id);
    if($images) {
      foreach($images as $key => $value) {
        wp_delete_attachment($value->ID, true);
      }
    }

    $response = wp_delete_post($oportunidade_id, true);

  } else {
    $response = new WP_Error('permissao', 'Usuário não possui permissão.', array('status' => 401));
  }
  return rest_ensure_response($response);
}

function registrar_api_oportunidade_delete() {
  register_rest_route('api', '/oportunidade/(?P<slug>[-\w]+)', array(
    array(
      'methods' => WP_REST_Server::DELETABLE,
      'callback' => 'api_oportunidade_delete',
    ),
  ));
}

add_action('rest_api_init', 'registrar_api_oportunidade_delete');


?>