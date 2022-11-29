<?php

function api_cliente_post($request) {
  $user = wp_get_current_user();
  $user_id = $user->ID;

  if($user_id > 0) {
    $cliente_email = sanitize_email($request['cliente_email']);
    $cliente_cnpj = sanitize_text_field($request['cliente_cnpj']);
    $cliente_nome = sanitize_text_field($request['cliente_nome']);
    $cliente_url = sanitize_text_field($request['cliente_url']);
    $cliente_cep = sanitize_text_field($request['cliente_cep']);
    $cliente_rua = sanitize_text_field($request['cliente_rua']);
    $cliente_numero = sanitize_text_field($request['cliente_numero']);
    $cliente_complemento = sanitize_text_field($request['cliente_complemento']);
    $cliente_bairro = sanitize_text_field($request['cliente_bairro']);
    $cliente_cidade = sanitize_text_field($request['cliente_cidade']);
    $cliente_estado = sanitize_text_field($request['cliente_estado']);
    $usuario_id = $user->user_login;
  
    if( $cliente_cnpj && $cliente_email ) {
      $response = array(
        'post_author' => $user_id,
        'post_type' => 'cliente',
        'post_title' => $cliente_nome . '-' . $cliente_cnpj,
        'post_status' => 'publish',
        'files' => $files,
        'meta_input' => array(
          'cliente_email' => $cliente_email,
          'cliente_cnpj' => $cliente_cnpj,
          'cliente_nome' => $cliente_nome,
          'cliente_url' => $cliente_url,
          'cliente_cep' => $cliente_cep,
          'cliente_rua' => $cliente_rua,
          'cliente_numero' => $cliente_numero,
          'cliente_complemento' => $cliente_complemento,
          'cliente_bairro' => $cliente_bairro,
          'cliente_cidade' => $cliente_cidade,
          'cliente_estado' => $cliente_estado,
          'usuario_id' => $usuario_id,
        ),
      );
  
      $cliente_id = wp_insert_post($response);
      $response['id'] = get_post_field('post_name', $cliente_id);
  
      $files = $request->get_file_params();
  
      if($files) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
  
        foreach ($files as $file => $array) {
          media_handle_upload($file, $cliente_id);
        }
      }
    }  
  } else {
    $response = new WP_Error('permissao', 'Usuário não possui permissão.', array('status' => 401));
  }
  return rest_ensure_response($response);
}

function registrar_api_cliente_post() {
  register_rest_route('api', '/cliente', array(
    array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'api_cliente_post',
    ),
  ));
}

add_action('rest_api_init', 'registrar_api_cliente_post');


?>