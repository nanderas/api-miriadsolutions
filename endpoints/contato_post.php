<?php

function api_contato_post($request) {
  $user = wp_get_current_user();
  $user_id = $user->ID;

  if($user_id > 0) {
    $contato_email = sanitize_email($request['contato_email']);
    $contato_cliente = sanitize_text_field($request['contato_cliente']);
    $contato_nome = sanitize_text_field($request['contato_nome']);
    $contato_cargo = sanitize_text_field($request['contato_cargo']);
    $usuario_id = $user->user_login;
  
    if( $contato_email && $contato_cliente ) {
      $response = array(
        'post_author' => $user_id,
        'post_type' => 'contato',
        'post_title' => $contato_email,
        'post_status' => 'publish',
        'files' => $files,
        'meta_input' => array(
          'contato_email' => $contato_email,
          'contato_cliente' => $contato_cliente,
          'contato_nome' => $contato_nome,
          'contato_cargo' => $contato_cargo,
          'usuario_id' => $usuario_id,
        ),
      );
  
      $contato_id = wp_insert_post($response);
      $response['id'] = get_post_field('post_name', $contato_id);
  
      $files = $request->get_file_params();
  
      if($files) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
  
        foreach ($files as $file => $array) {
          media_handle_upload($file, $contato_id);
        }
      }
    }  
  } else {
    $response = new WP_Error('permissao', 'Usuário não possui permissão.', array('status' => 401));
  }
  return rest_ensure_response($response);
}

function registrar_api_contato_post() {
  register_rest_route('api', '/contato', array(
    array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'api_contato_post',
    ),
  ));
}

add_action('rest_api_init', 'registrar_api_contato_post');


?>