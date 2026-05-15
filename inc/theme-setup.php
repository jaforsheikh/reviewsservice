<?php

/**
 * Theme Setup
 *
 * @package reviewsservice
 */

if (! defined('ABSPATH')) {
    exit;
}

function reviewsservice_theme_setup()
{
    add_theme_support('title-tag');

    add_theme_support('post-thumbnails');

    add_theme_support('woocommerce');

    add_theme_support(
        'html5',
        [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]
    );

    register_nav_menus([
        'primary_menu' => __('Primary Menu', 'reviewsservice'),
    ]);
}

add_action('after_setup_theme', 'reviewsservice_theme_setup');
