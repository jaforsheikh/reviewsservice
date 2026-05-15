<?php

/**
 * Header Template
 *
 * @package reviewsservice
 */

defined('ABSPATH') || exit;

$logo_url = get_template_directory_uri() . '/assets/images/reviews-service-logo.png';
?>

<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class('bg-white text-[#0D0F12] antialiased'); ?>>
  <?php wp_body_open(); ?>

  <header class="sticky top-0 z-50 bg-white shadow-sm">
    <div class="max-w-[1280px] mx-auto px-5 py-4 flex items-center justify-between">

      <!-- Logo -->
      <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center">
        <img src="<?php echo esc_url($logo_url); ?>"
          alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
          class="h-10 w-auto">
      </a>

      <!-- Desktop Menu -->
      <nav class="hidden lg:flex items-center space-x-4 text-base">
        <?php
        wp_nav_menu([
          'theme_location' => 'primary_menu',
          'container'      => false,
          'menu_class'     => 'flex items-center space-x-4 text-base ',
          'fallback_cb'    => false,
          'depth'          => 2,
        ]);
        ?>
      </nav>

      <!-- Action Buttons -->
      <div class="hidden lg:flex items-center space-x-3">
        <a href="<?php echo esc_url(home_url('/my-account/')); ?>"
          class="px-4 py-2 rounded-lg border border-gray-200 hover:border-gray-900 text-gray-700 hover:text-black transition">
          My Account
        </a>
        <a href="<?php echo esc_url(home_url('/contact/')); ?>"
          class="px-5 py-2 rounded-full bg-green-600 hover:bg-green-500 text-white font-bold transition">
          Free Consultation
        </a>
      </div>

      <!-- Mobile Toggle -->
      <button class="lg:hidden flex flex-col justify-between w-10 h-10" data-rs-menu-button>
        <span class="block h-1 w-full bg-black rounded"></span>
        <span class="block h-1 w-full bg-black rounded"></span>
        <span class="block h-1 w-full bg-black rounded"></span>
      </button>

    </div>

    <!-- Mobile Menu -->
    <div class="hidden lg:hidden bg-white shadow-md" data-rs-mobile-menu>
      <nav class="flex flex-col p-4 space-y-2">
        <?php
        wp_nav_menu([
          'theme_location' => 'primary_menu',
          'container'      => false,
          'menu_class'     => 'flex flex-col space-y-1',
          'fallback_cb'    => false,
          'depth'          => 2,
        ]);
        ?>
        <div class="mt-3 flex flex-col space-y-2">
          <a href="<?php echo esc_url(home_url('/my-account/')); ?>"
            class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:text-black">My Account</a>
          <a href="<?php echo esc_url(home_url('/contact/')); ?>"
            class="px-5 py-2 rounded-full bg-green-600 text-white font-bold hover:bg-green-500">Free Consultation</a>
        </div>
      </nav>
    </div>
  </header>

  <?php wp_footer(); ?>
</body>

</html>