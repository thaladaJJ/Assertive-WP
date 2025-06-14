<?php

// require_once( dirname(__FILE__) . '/CMB2/cmb2SobreNos.php' );
require_once( dirname(__FILE__) . '/CMB2/cmb2_inteligencia.php' );
require_once( dirname(__FILE__) . '/CMB2/cmb2_header.php' );
require_once( dirname(__FILE__) . '/CMB2/cmb2_footer.php' );

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

add_action ('wp_enqueue_scripts', 'header_footer_scripts');

add_action ( 'admin_footer', function() {
    $screen = get_current_screen();
    if ( $screen && $screen->post_type === 'page' ) :  
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rangeInput = document.querySelector('input[name="posicao_cor_imagem_hero"]');

    if (rangeInput) {
        
        const feedback = document.createElement('div');
        feedback.id = 'posicao_cor_feedback';
        feedback.style.marginTop = '5px';
        feedback.style.fontWeight = 'bold';
        rangeInput.parentNode.appendChild(feedback);

        function updateOutput() {
            feedback.innerHTML = 'Valor atual: <span>' + rangeInput.value + '%</span>';
        }

        rangeInput.addEventListener('input', updateOutput);
        updateOutput();
    }
});
</script>
<?php
    endif;
});

?>