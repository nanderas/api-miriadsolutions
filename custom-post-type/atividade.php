<?php
function registrar_cpt_atividade() {
  register_post_type('atividade', array(
    'label' => 'Atividade',
    'description' => 'Atividade',
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'rewrite' => array('slug' => 'atividade', 'with_front' => true),
    'query_var' => true,
    'supports' => array('custom-fields', 'author', 'title'),
    'publicly_queryable' => true
  ));
}
add_action('init', 'registrar_cpt_atividade');

?>