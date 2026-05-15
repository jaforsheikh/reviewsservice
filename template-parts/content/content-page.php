<?php

/**
 * Default Page Content
 *
 * @package reviewsservice
 */

if (! defined('ABSPATH')) {
  exit;
}
?>

<!-- Default Page Content Start -->
<section class="bg-gradient-to-b from-white to-[#F8FAFC] px-4 py-20 sm:px-6 lg:px-8">
  <div class="mx-auto max-w-screen-xl">
    <?php
    while (have_posts()) :
      the_post();
    ?>

      <article id="post-<?php the_ID(); ?>" <?php post_class('mx-auto max-w-3xl'); ?>>
        <h1 class="text-4xl font-black leading-tight tracking-[-0.018em] text-[#0D0F12] sm:text-5xl">
          <?php the_title(); ?>
        </h1>

        <div class="mt-6 text-base font-semibold leading-8 text-[#374151]">
          <?php the_content(); ?>
        </div>
      </article>

    <?php endwhile; ?>
  </div>
</section>
<!-- Default Page Content End -->