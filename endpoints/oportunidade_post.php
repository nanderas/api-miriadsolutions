<?php

function api_oportunidade_post($request) {
  $user = wp_get_current_user();
  $user_id = $user->ID;

  if($user_id > 0) {
    $oportunidade_cliente = sanitize_text_field($request['oportunidade_cliente']);
    $oportunidade_contato = sanitize_text_field($request['oportunidade_contato']);
    $oportunidade_descricao = sanitize_text_field($request['oportunidade_descricao']);
    $oportunidade_servico = sanitize_text_field($request['oportunidade_servico']);
    $oportunidade_status = sanitize_text_field($request['oportunidade_status']);
    $oportunidade_valor = sanitize_text_field($request['oportunidade_valor']);
    $usuario_id = $user->user_login;
  
    if( $oportunidade_cliente && $oportunidade_servico  ) {
      $response = array(
        'post_author' => $user_id,
        'post_type' => 'oportunidade',
        'post_title' => $oportunidade_descricao . ' - ' . $oportunidade_cliente,
        'post_status' => 'publish',
        'files' => $files,
        'meta_input' => array(
          'oportunidade_cliente' => $oportunidade_cliente,
          'oportunidade_contato' => $oportunidade_contato,
          'oportunidade_descricao' => $oportunidade_descricao,
          'oportunidade_servico' => $oportunidade_servico,
          'oportunidade_status' => $oportunidade_status,
          'oportunidade_valor' => $oportunidade_valor,
          'usuario_id' => $usuario_id,
        ),
      );
  
      $oportunidade_id = wp_insert_post($response);
      $response['id'] = get_post_field('post_name', $oportunidade_id);
  
      $files = $request->get_file_params();
  
      if($files) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
  
        foreach ($files as $file => $array) {
          media_handle_upload($file, $oportunidade_id);
        }
      }
    }  
  } else {
    $response = new WP_Error('permissao', 'Usuário não possui permissão.', array('status' => 401));
  }
  return rest_ensure_response($response);
}

function registrar_api_oportunidade_post() {
  register_rest_route('api', '/oportunidade', array(
    array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'api_oportunidade_post',
    ),
  ));
}

add_action('rest_api_init', 'registrar_api_oportunidade_post');


?>