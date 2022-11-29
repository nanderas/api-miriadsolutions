<?php

$template_diretorio = get_template_directory();

require_once($template_diretorio . "/custom-post-type/atividade.php");
require_once($template_diretorio . "/custom-post-type/citacao.php");
require_once($template_diretorio . "/custom-post-type/colaborador.php");
require_once($template_diretorio . "/custom-post-type/comentario.php");
require_once($template_diretorio . "/custom-post-type/contato.php");
require_once($template_diretorio . "/custom-post-type/cliente.php");
require_once($template_diretorio . "/custom-post-type/oportunidade.php");
require_once($template_diretorio . "/custom-post-type/servico.php");

require_once($template_diretorio . "/endpoints/usuario_post.php");
require_once($template_diretorio . "/endpoints/usuario_get.php");
require_once($template_diretorio . "/endpoints/usuario_put.php");

require_once($template_diretorio . "/endpoints/cliente_post.php");
require_once($template_diretorio . "/endpoints/cliente_get.php");
require_once($template_diretorio . "/endpoints/cliente_delete.php");

require_once($template_diretorio . "/endpoints/contato_post.php");
require_once($template_diretorio . "/endpoints/contato_get.php");
require_once($template_diretorio . "/endpoints/contato_delete.php");

require_once($template_diretorio . "/endpoints/oportunidade_post.php");
//require_once($template_diretorio . "/endpoints/oportunidade_get.php");
//require_once($template_diretorio . "/endpoints/oportunidade_delete.php");


function get_cliente_id_by_slug($slug) {
  $query = new WP_Query(array(
    'name' => $slug,
    'post_type' => 'cliente',
    'numberposts' => 1,
    'fields' => 'ids'
  ));
  $posts = $query->get_posts();
  return array_shift($posts);
}

function get_contato_id_by_slug($slug) {
  $query = new WP_Query(array(
    'name' => $slug,
    'post_type' => 'contato',
    'numberposts' => 1,
    'fields' => 'ids'
  ));
  $posts = $query->get_posts();
  return array_shift($posts);
}


add_action('rest_pre_serve_request', function() {
  header('Access-Control-Expose-Headers: X-Total-Count');
});

function expire_token() {
  return time() + (60 * 60 * 24);
}
add_action('jwt_auth_expire', 'expire_token');

function my_login_screen() { ?>
<style type="text/css">
#login h1 a {
  background-image: none;
}
#backtoblog {
  display: none;
}
</style>
<?php }
add_action('login_enqueue_scripts', 'my_login_screen');

?>