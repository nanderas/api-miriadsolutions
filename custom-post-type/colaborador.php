<?php
function registrar_cpt_colaborador() {
  register_post_type('colaborador', array(
    'label' => 'Colaborador',
    'description' => 'Colaborador',
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'rewrite' => array('slug' => 'colaborador', 'with_front' => true),
    'query_var' => true,
    'supports' => array('custom-fields', 'author', 'title'),
    'publicly_queryable' => true
  ));
}
add_action('init', 'registrar_cpt_colaborador');

?>