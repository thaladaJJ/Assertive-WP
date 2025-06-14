<?php

// require_once( dirname(__FILE__) . '/CMB2/cmb2SobreNos.php' );
require_once( dirname(__FILE__) . '/CMB2/cmb2_inteligencia.php' );
require_once( dirname(__FILE__) . '/CMB2/cmb2_header-footer.php' );

function get_field($field, $page = NULL, $unique = true) {
  $page = is_null($page) ? get_the_ID() : $page;
  return get_post_meta($page, $field, $unique);
}

function the_field($field, $page = NULL, $unique = true) {
  $page = is_null($page) ? get_the_ID() : $page;
  echo get_field($field, $page);
}

function get_field_array( string $field, $page = null ): array {
    $page = is_null( $page ) ? get_the_ID() : $page;
    $values = get_post_meta( $page, $field, false );

    return is_array( $values ) ? $values : [];
}

function get_field_group( string $field, $page = null ): array {
    $page = is_null( $page ) ? get_the_ID() : $page;
    // true no 3º argumento faz retornar o array de items do group
    $group = get_post_meta( $page, $field, true );

    // Se ainda for string serializada, deserializa
    if ( is_string( $group ) ) {
        $maybe = maybe_unserialize( $group );
        $group = is_array( $maybe ) ? $maybe : [];
    }

    return is_array( $group ) ? $group : [];
}

function header_footer_scripts() {
    wp_enqueue_script('header-footer', get_stylesheet_directory_uri() . '/JS/header-footer.js', array(), null, true);
}

add_action('wp_enqueue_scripts', 'header_footer_scripts');

?>