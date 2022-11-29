<?php
function registrar_cpt_comentario() {
  register_post_type('comentario', array(
    'label' => 'Comentario',
    'description' => 'Comentario',
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'rewrite' => array('slug' => 'comentario', 'with_front' => true),
    'query_var' => true,
    'supports' => array('custom-fields', 'author', 'title'),
    'publicly_queryable' => true
  ));
}
add_action('init', 'registrar_cpt_comentario');

?>