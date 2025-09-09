<?php

/**
 *  cabinet functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package cabinet
 */

if (!defined('CABINET_VERSION')) {
    // Replace the version number of the theme on each release.
    define('CABINET_VERSION', '1.0.0');
}


function cabinet_scripts()
{
    wp_enqueue_style('cabinet-style', get_stylesheet_uri(), array(), CABINET_VERSION);
    wp_enqueue_script('cabinet-script', get_template_directory_uri() . '/assets/js/global.js', array(), CABINET_VERSION, true);
    wp_enqueue_script('iconify', 'https://code.iconify.design/2/2.1.2/iconify.min.js', array(), true);
    wp_enqueue_script('imagesloaded-js', 'https://cdn.jsdelivr.net/npm/imagesloaded@5.0.0/imagesloaded.pkgd.min.js', array(), CABINET_VERSION, true);
    wp_enqueue_script('gsap','https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',array(),CABINET_VERSION,true);
    wp_enqueue_script('scrolltrigger','https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',array('gsap'),CABINET_VERSION,true);

    //sliders
    wp_enqueue_style('splide-css', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css', array(), CABINET_VERSION);
    wp_enqueue_script('splide-js', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js', array(), true);
}

add_action('wp_enqueue_scripts', 'cabinet_scripts');



if (!function_exists('setupcabinet')):
    function setupcabinet()
    {
        add_theme_support('post-thumbnails');
        add_post_type_support('page', 'excerpt');
        add_theme_support('post-formats', array('aside', 'gallery', 'quote', 'image', 'video'));

        add_theme_support(
            'custom-logo',
            array(
                'height' => 400,
                'width' => 400,
                'flex-height' => true,
                'flex-width' => true,
            )
        );
        register_nav_menus(
            array(
                'Primary' => esc_html__('Menu principal', 'cabinet'),
                'Secondary' => esc_html__('Menu mobile', 'cabinet'),
                'Footer' => esc_html__('Menu footer', 'cabinet')
            )
        );
    }
endif;

add_action('after_setup_theme', 'setupcabinet');


function custom_excerpt_length($excerpt, $charlength)
{
    $charlength++;
    if (mb_strlen($excerpt) > $charlength) {
        $subex = mb_substr($excerpt, 0, $charlength - 3);
        $exwords = explode(' ', $subex);
        $excut = - (mb_strlen($exwords[count($exwords) - 1]));
        if ($excut < 0) {
            return mb_substr($subex, 0, $excut) . '...';
        } else {
            return $subex . '...';
        }
    } else {
        return $excerpt;
    }
}

if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Informations',
        'menu_title' => 'Informations',
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}

// Custom post Types below

// Register Custom Post Type "Membre d'équipe" and taxonomy "Type"
add_action('init', function () {
    // CPT : Membre d’équipe
    register_post_type('membre_equipe', [
        'labels' => [
            'name'               => 'Membres d’équipe',
            'singular_name'      => 'Membre d’équipe',
            'add_new'            => 'Ajouter',
            'add_new_item'       => 'Ajouter un membre',
            'edit_item'          => 'Modifier le membre',
            'new_item'           => 'Nouveau membre',
            'view_item'          => 'Voir le membre',
            'search_items'       => 'Rechercher des membres',
            'not_found'          => 'Aucun membre trouvé',
            'not_found_in_trash' => 'Aucun membre dans la corbeille',
            'all_items'          => 'Tous les membres',
            'menu_name'          => 'Équipe',
        ],
        'public'            => true,
        'has_archive'       => true,
        'rewrite'           => ['slug' => 'equipe'],
        'show_in_rest'      => true, // Gutenberg + API
        'menu_icon'         => 'dashicons-groups',
        'supports'          => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'page-attributes'],
    ]);

    // Taxonomy : Type (Praticien / Assistante)
    register_taxonomy('type_membre', ['membre_equipe'], [
        'labels' => [
            'name'          => 'Types',
            'singular_name' => 'Type',
            'search_items'  => 'Rechercher des types',
            'all_items'     => 'Tous les types',
            'edit_item'     => 'Modifier le type',
            'update_item'   => 'Mettre à jour le type',
            'add_new_item'  => 'Ajouter un type',
            'new_item_name' => 'Nouveau type',
            'menu_name'     => 'Type',
        ],
        'public'        => true,
        'show_in_rest'  => true,
        'hierarchical'  => false, // true = hiérarchie comme catégories
        'rewrite'       => ['slug' => 'type-membre'],
    ]);
});

// Register Custom Post Type "Soins"
add_action('init', function () {
    register_post_type('soin', [
        'labels' => [
            'name'               => 'Soins',
            'singular_name'      => 'Soin',
            'add_new'            => 'Ajouter',
            'add_new_item'       => 'Ajouter un soin',
            'edit_item'          => 'Modifier le soin',
            'new_item'           => 'Nouveau soin',
            'view_item'          => 'Voir le soin',
            'search_items'       => 'Rechercher des soins',
            'not_found'          => 'Aucun soin trouvé',
            'not_found_in_trash' => 'Aucun soin dans la corbeille',
            'all_items'          => 'Tous les soins',
            'menu_name'          => 'Soins',
        ],
        'public'            => true,
        'has_archive'       => true,
        'rewrite'           => ['slug' => 'soins'],
        'show_in_rest'      => true, // Gutenberg + API REST
        'menu_icon'         => 'dashicons-heart',
        'supports'          => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'page-attributes'],
    ]);
});




// Copy everything below this line //
add_filter('kses_allowed_protocols', 'my_allowed_protocols');
/**
 *  Description
 *
 *  @since 1.0.0
 *
 *  @param $protocols
 *
 *  @return array
 */
function my_allowed_protocols($protocols)
{
    $protocols[] = "javascript";
    return $protocols;
}
