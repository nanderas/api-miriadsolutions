<?php
function registrar_cpt_citacao() {
  register_post_type('citacao', array(
    'label' => 'Citacao',
    'description' => 'Citacao',
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'rewrite' => array('slug' => 'citacao', 'with_front' => true),
    'query_var' => true,
    'supports' => array('custom-fields', 'author', 'title'),
    'publicly_queryable' => true
  ));
}
add_action('init', 'registrar_cpt_citacao');

?>