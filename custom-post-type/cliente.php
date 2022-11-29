<?php
function registrar_cpt_cliente() {
  register_post_type('cliente', array(
    'label' => 'Cliente',
    'description' => 'Cliente',
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'rewrite' => array('slug' => 'cliente', 'with_front' => true),
    'query_var' => true,
    'supports' => array('custom-fields', 'author', 'title'),
    'publicly_queryable' => true
  ));
}
add_action('init', 'registrar_cpt_cliente');

?>