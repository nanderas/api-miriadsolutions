<?php

function api_comentario_post($request) {
  $user = wp_get_current_user();
  $user_id = $user->ID;

  if($user_id > 0) {
    $comentario_cliente = sanitize_text_field($request['comentario_cliente']);
    $comentario_oportunidade = sanitize_text_field($request['comentario_oportunidade']);
    $comentario_descricao = sanitize_text_field($request['comentario_descricao']);
    $comentario_url = sanitize_text_field($request['comentario_url']);
    $usuario_id = $user->user_login;
  
    if( $comentario_cliente && $comentario_oportunidade ) {
      $response = array(
        'post_author' => $user_id,
        'post_type' => 'comentario',
        'post_title' => $comentario_descricao . ' - ' . $comentario_oportunidade,
        'post_status' => 'publish',
        'files' => $files,
        'meta_input' => array(
          'comentario_cliente' => $comentario_cliente,
          'comentario_oportunidade' => $comentario_oportunidade,
          'comentario_descricao' => $comentario_descricao,
          'comentario_url' => $comentario_url,
          'usuario_id' => $usuario_id,
        ),
      );
  
      $comentario_id = wp_insert_post($response);
      $response['id'] = get_post_field('post_name', $comentario_id);
  
      $files = $request->get_file_params();
  
      if($files) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
  
        foreach ($files as $file => $array) {
          media_handle_upload($file, $comentario_id);
        }
      }
    }  
  } else {
    $response = new WP_Error('permissao', 'Usuário não possui permissão.', array('status' => 401));
  }
  return rest_ensure_response($response);
}

function registrar_api_comentario_post() {
  register_rest_route('api', '/comentario', array(
    array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'api_comentario_post',
    ),
  ));
}

add_action('rest_api_init', 'registrar_api_comentario_post');


?>