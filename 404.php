<?php

/**
 * 404 Page Template
 *
 * @package reviewsservice
 */

get_header();
?>

<main class="min-h-screen flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-900 py-20 px-6 text-center">

  <!-- 404 Icon / Image -->
  <div class="mb-8">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/404-hero.png"
      alt="Page not found"
      class="mx-auto w-64 md:w-96 lg:w-[500px]">
  </div>

  <!-- 404 Title -->
  <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 dark:text-white mb-4">
    Oops! Page Not Found
  </h1>

  <!-- 404 Description -->
  <p class="text-gray-600 dark:text-gray-300 text-lg md:text-xl mb-8 max-w-xl mx-auto">
    The page you are looking for might have been removed, had its name changed, or is temporarily unavailable. Let's get you back on track!
  </p>

  <!-- Search / CTA -->
  <div class="flex flex-col md:flex-row gap-4 justify-center items-center">
    <a href="<?php echo esc_url(home_url('/')); ?>"
      class="px-6 py-3 rounded-full bg-green-600 text-gray-900 font-bold hover:bg-green-500 transition-all">
      Go to Homepage
    </a>

    <a href="<?php echo esc_url(home_url('/contact/')); ?>"
      class="px-6 py-3 rounded-full border border-gray-300 text-gray-900 hover:border-green-500 hover:text-green-500 transition-all">
      Contact Support
    </a>
  </div>

  <!-- Optional: Search Form -->
  <div class="mt-10 w-full max-w-md">
    <?php get_search_form(); ?>
  </div>

</main>

<?php get_footer(); ?>