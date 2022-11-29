<?php
function registrar_cpt_contato() {
  register_post_type('contato', array(
    'label' => 'Contato',
    'description' => 'Contato',
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'rewrite' => array('slug' => 'contato', 'with_front' => true),
    'query_var' => true,
    'supports' => array('custom-fields', 'author', 'title'),
    'publicly_queryable' => true
  ));
}
add_action('init', 'registrar_cpt_contato');

?>