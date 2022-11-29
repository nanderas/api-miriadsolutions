<?php
function registrar_cpt_oportunidade() {
  register_post_type('oportunidade', array(
    'label' => 'Oportunidade',
    'description' => 'Oportunidade',
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'rewrite' => array('slug' => 'oportunidade', 'with_front' => true),
    'query_var' => true,
    'supports' => array('custom-fields', 'author', 'title'),
    'publicly_queryable' => true
  ));
}
add_action('init', 'registrar_cpt_oportunidade');

?>