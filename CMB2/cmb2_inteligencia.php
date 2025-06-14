<?php

function fields_inteligencia_hero() {
   $inteligencia_hero_box = new_cmb2_box([
        'id' => 'inteligencia_hero_box',
        'title' => 'Inteligência de Mercado - Início',
        'object_types' => ['page'],
        'show_on' => [
            'key' => 'page-template', 
            'value' => 'page-inteligencia.php'
        ]
    ]);
    
    $inteligencia_hero_box->add_field([
        'name' => 'Título de Entrada',
        'id' => 'titulo',
        'type' => 'text',    
    ]);

    $inteligencia_hero_box->add_field([
        'name' => 'Frase de Chamada',
        'id' => 'chamada-hero',
        'type' => 'text',    
    ]);

    $inteligencia_hero_box->add_field([
        'name' => 'Frase de Chamada no Botão',
        'id' => 'chamada-hero-button',
        'type' => 'text',    
    ]);

     $inteligencia_hero_box->add_field([
        'name' => 'Imagem de Entrada',
        'id' => 'imagem_hero',
        'type' => 'file',
        'options' => [
            'url' => false,
        ],
    ]);
}

add_action ('cmb2_admin_init', 'fields_inteligencia_hero');

function inteligencia_de_mercado_styles() {
    if (is_page_template('page-inteligencia.php')) {
        wp_enqueue_style (
            'inteligencia-de-mercado-style',
            get_stylesheet_directory_uri() . '/CSS/InteligenciaDeMercado.css',
            array(),
            '1.0'
        );
    }
}

function inteligencia_de_mercado_scripts() {
    if (is_page_template('page-inteligencia.php')) {
        wp_enqueue_script(
            'inteligencia-de-mercado-script',
            get_stylesheet_directory_uri() . '/JS/InteligenciaDeMercado.js',
            array(),
            '1.0',
            true
        );
    }
}

add_action('wp_enqueue_scripts', 'inteligencia_de_mercado_styles');
add_action('wp_enqueue_scripts', 'inteligencia_de_mercado_scripts');

?>