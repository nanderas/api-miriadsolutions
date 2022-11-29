<?php
function registrar_cpt_servico() {
  register_post_type('servico', array(
    'label' => 'Servico',
    'description' => 'Servico',
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'rewrite' => array('slug' => 'servico', 'with_front' => true),
    'query_var' => true,
    'supports' => array('custom-fields', 'author', 'title'),
    'publicly_queryable' => true
  ));
}
add_action('init', 'registrar_cpt_servico');

?>