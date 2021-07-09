<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
require_once PGC_SGB_PATH . '/blocks/simply_post.php';
require_once PGC_SGB_PATH . '/blocks/simply_widget.php';
require_once PGC_SGB_PATH . '/blocks/class-elementor.php';
require_once PGC_SGB_PATH . '/blocks/simply_dashboard_widget.php';
function pgc_sgb_amp_item( $item )
{
    $assetsFolder = PGC_SGB_URL . 'assets/';
    $itemElemant = null;
    $image = array();
    
    if ( $item['type'] === 'video' || $item['type'] === 'audio' ) {
        
        if ( isset( $item['image'] ) && isset( $item['image']['width'] ) && intval( $item['image']['width'] ) >= 150 ) {
            $image['src'] = $item['image']['src'];
            $image['width'] = ( isset( $item['image']['width'] ) ? $item['image']['width'] : '300' );
            $image['height'] = ( isset( $item['image']['height'] ) ? $item['image']['height'] : '300' );
        } else {
            
            if ( $item['type'] === 'audio' ) {
                $image['src'] = $assetsFolder . 'holder-mp3.png';
                $image['width'] = '300';
                $image['height'] = '300';
            }
        
        }
    
    } else {
        if ( $item['type'] === 'image' ) {
            
            if ( isset( $item['sizes'] ) && isset( $item['sizes']['medium'] ) ) {
                $image['src'] = $item['sizes']['medium']['url'];
                $image['width'] = ( isset( $item['sizes']['medium']['width'] ) ? $item['sizes']['medium']['width'] : '300' );
                $image['height'] = ( isset( $item['sizes']['medium']['height'] ) ? $item['sizes']['medium']['height'] : '300' );
            } else {
                $image['src'] = $item['url'];
                $image['width'] = ( isset( $item['width'] ) ? $item['width'] : '300' );
                $image['height'] = ( isset( $item['height'] ) ? $item['height'] : '300' );
            }
        
        }
    }
    
    
    if ( $item['type'] === 'image' || $item['type'] === 'audio' ) {
        $itemElemant = '<img alt="' . (( isset( $item['alt'] ) ? $item['alt'] : '' )) . '" width="' . $image['width'] . '" height="' . $image['height'] . '" loading="auto" data-lazy-src="" class="skip-lazy no-lazyload noLazy" ' . 'src="' . $image['src'] . '"/>';
        
        if ( $item['type'] === 'audio' ) {
            $audioEl = '<audio controls src="' . $item['url'] . '"></audio>';
            $itemElemant = $itemElemant . $audioEl;
        } else {
            $itemElemant = '<a href="' . $item['url'] . '">' . $itemElemant . '</a>';
        }
    
    } else {
        
        if ( $item['type'] === 'video' ) {
            $poster = ( $image ? 'poster="' . $image['src'] . '"' : '' );
            $itemElemant = '<video controls ' . $poster . ' src="' . $item['url'] . '"></video>';
        }
    
    }
    
    if ( isset( $itemElemant ) ) {
        return $itemWrap = '<div class="sgb-item">' . $itemElemant . '</div>';
    }
    return '';
}

function pgc_sgb_noscript( $items )
{
    if ( !$items ) {
        return '';
    }
    $noscript = '';
    foreach ( $items as $item ) {
        $noscript = $noscript . pgc_sgb_amp_item( $item );
    }
    return $noscript;
}

function pgc_sgb_render_callback( $atr, $content )
{
    wp_enqueue_style( PGC_SGB_SLUG . '-frontend' );
    wp_enqueue_script( PGC_SGB_SLUG . '-script' );
    /** galleryType-1.1.0  galleryData-1.7.0 */
    if ( isset( $atr['galleryType'] ) === false || isset( $atr['galleryData'] ) === false ) {
        return $content;
    }
    $galleryData = $atr['galleryData'];
    $galleryDataArr = json_decode( $galleryData, true );
    $galleryQueryData = null;
    $skinType = substr( $atr['galleryType'], 8 );
    $align = '';
    if ( isset( $atr['align'] ) ) {
        $align = ' ' . $align . 'align' . $atr['align'];
    }
    $className = PGC_SGB_BLOCK_PREF . $skinType . $align;
    if ( isset( $atr['className'] ) ) {
        $className = $className . ' ' . $atr['className'];
    }
    $noscript = '<noscript><div class="simply-gallery-amp pgc_sgb_slider ' . $align . '"><div class="sgb-gallery">' . pgc_sgb_noscript( $atr['images'] ) . '</div></div></noscript>';
    $preloaderColor = ( $galleryDataArr['galleryPreloaderColor'] ? $galleryDataArr['galleryPreloaderColor'] : '#d4d4d4' );
    $preloder = '<div class="sgb-preloader">
	<div class="sgb-square" style="background:' . $preloaderColor . '"></div>
	<div class="sgb-square" style="background:' . $preloaderColor . '"></div>
	<div class="sgb-square" style="background:' . $preloaderColor . '"></div>
	<div class="sgb-square" style="background:' . $preloaderColor . '"></div></div>';
    $html = '<div class="pgc-sgb-cb ' . $className . '" data-gallery-id="' . $atr['galleryId'] . '">
		<script type="application/json" class="sgb-data">' . $galleryData . '</script>' . '<script type="text/javascript">(function(){if(window.PGC_SGB && window.PGC_SGB.searcher){window.PGC_SGB.searcher.initBlocks()}})()</script>' . $noscript . $preloder . '</div>';
    return $html;
}

function pgc_sgb_action_customize_preview_init()
{
    wp_enqueue_style( PGC_SGB_SLUG . '-frontend' );
    wp_enqueue_script( PGC_SGB_SLUG . '-script' );
}

function pgc_sgb_ajaxQueryAttachmentsArgs( $query )
{
    
    if ( isset( $_REQUEST['query']['pgc_sgb'] ) && isset( $_REQUEST['query']['terms'] ) && isset( $_REQUEST['query']['taxonomy'] ) ) {
        $taxonomy = $_REQUEST['query']['taxonomy'];
        $terms = $_REQUEST['query']['terms'];
        
        if ( is_array( $terms ) ) {
            $terms = array_map( 'intval', $terms );
        } else {
            $terms = intval( $terms );
        }
        
        $tax_query = array( array(
            'taxonomy' => $taxonomy,
            'field'    => 'term_id',
            'terms'    => $terms,
        ) );
    }
    
    $query['tax_query'] = $tax_query;
    return $query;
}

function pgc_sgb_block_assets()
{
    global  $pgc_sgb_skins_list, $pgc_sgb_skins_presets ;
    /** Searcher */
    wp_register_script(
        PGC_SGB_SLUG . '-script',
        PGC_SGB_URL . 'blocks/pgc_sgb.min.js',
        array(),
        PGC_SGB_VERSION,
        true
    );
    
    if ( is_admin() ) {
        register_post_meta( 'attachment', 'pgc_sgb_link', array(
            'show_in_rest'      => true,
            'type'              => 'string',
            'single'            => true,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function () {
            return current_user_can( 'edit_posts' );
        },
        ) );
        register_post_meta( 'attachment', 'pgc_sgb_tag', array(
            'show_in_rest'      => true,
            'type'              => 'string',
            'single'            => false,
            'sanitize_callback' => 'sanitize_text_field',
            'auth_callback'     => function () {
            return current_user_can( 'edit_posts' );
        },
        ) );
        $globalJS = array(
            'ajaxurl'       => admin_url( 'admin-ajax.php' ),
            'adminurl'      => get_admin_url(),
            'nonce'         => wp_create_nonce( 'pgc-sgb-nonce' ),
            'assets'        => PGC_SGB_URL . 'assets/',
            'postType'      => PGC_SGB_POST_TYPE,
            'taxonomy'      => PGC_SGB_TAXONOMY,
            'skinsFolder'   => PGC_SGB_URL . 'blocks/skins/',
            'searcher'      => PGC_SGB_URL . 'blocks/pgc_sgb.min.js' . '?ver=' . PGC_SGB_VERSION,
            'skinsList'     => $pgc_sgb_skins_list,
            'skinsSettings' => $pgc_sgb_skins_presets,
            'admin'         => is_admin(),
        );
        wp_localize_script( PGC_SGB_SLUG . '-script', 'PGC_SGB_ADMIN', $globalJS );
        wp_localize_script( PGC_SGB_SLUG . '-script', 'PGC_SGB', $globalJS );
    }
    
    /** Blocks Styles */
    wp_register_style(
        PGC_SGB_SLUG . '-editor',
        PGC_SGB_URL . 'dist/blocks.build.style.css',
        array( 'code-editor' ),
        PGC_SGB_VERSION
    );
    /** Main Blocks Script */
    wp_register_script(
        PGC_SGB_SLUG . '-js',
        PGC_SGB_URL . 'dist/blocks.build.js',
        array(
        'wp-blocks',
        'wp-i18n',
        'wp-element',
        'wp-editor',
        'wplink',
        'wp-data',
        'media',
        'media-grid',
        'backbone',
        'code-editor',
        'csslint',
        PGC_SGB_SLUG . '-script'
    ),
        PGC_SGB_VERSION,
        false
    );
    /** Main Blocks Translatrion */
    if ( function_exists( 'wp_set_script_translations' ) ) {
        wp_set_script_translations( PGC_SGB_SLUG . '-js', 'simply-gallery-block', PGC_SGB_URL . 'languages' );
    }
    /** Main Blocks */
    register_block_type( 'pgcsimplygalleryblock/masonry', array(
        'style'           => PGC_SGB_SLUG . '-frontend',
        'editor_script'   => PGC_SGB_SLUG . '-js',
        'editor_style'    => PGC_SGB_SLUG . '-editor',
        'render_callback' => 'pgc_sgb_render_callback',
    ) );
    register_block_type( 'pgcsimplygalleryblock/justified', array(
        'style'           => PGC_SGB_SLUG . '-frontend',
        'editor_script'   => PGC_SGB_SLUG . '-js',
        'editor_style'    => PGC_SGB_SLUG . '-editor',
        'render_callback' => 'pgc_sgb_render_callback',
    ) );
    register_block_type( 'pgcsimplygalleryblock/grid', array(
        'style'           => PGC_SGB_SLUG . '-frontend',
        'editor_script'   => PGC_SGB_SLUG . '-js',
        'editor_style'    => PGC_SGB_SLUG . '-editor',
        'render_callback' => 'pgc_sgb_render_callback',
    ) );
    register_block_type( 'pgcsimplygalleryblock/slider', array(
        'style'           => PGC_SGB_SLUG . '-frontend',
        'editor_script'   => PGC_SGB_SLUG . '-js',
        'editor_style'    => PGC_SGB_SLUG . '-editor',
        'render_callback' => 'pgc_sgb_render_callback',
    ) );
}

add_action( 'init', 'pgc_sgb_block_assets' );
add_action(
    'customize_preview_init',
    'pgc_sgb_action_customize_preview_init',
    10,
    1
);