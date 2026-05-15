<?php

/**
 * Template Name: Reputation Management
 * @package reviewsservice
 */
defined('ABSPATH') || exit;
get_header();
?>

<!-- HERO SECTION -->
<section class="relative bg-gray-900 text-white">
  <div class="max-w-7xl mx-auto px-6 py-20 lg:flex lg:items-center lg:justify-between gap-12">
    <div class="lg:w-1/2">
      <span class="text-sm font-bold text-green-500 uppercase mb-3 inline-block">Multi-Platform Reputation Management</span>
      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">Build Stronger Trust Across Every Review Platform</h1>
      <p class="text-gray-300 mb-6 text-lg">We help businesses improve online reputation, collect genuine customer feedback, respond professionally, and build stronger trust across Google, Trustpilot, Sitejabber, BBB, Clutch, G2, Capterra, and more.</p>
      <div class="flex flex-wrap gap-4">
        <a href="#platforms" class="bg-green-500 hover:bg-green-600 text-gray-900 font-bold py-3 px-6 rounded-full shadow-md transition">Get Free Reputation Audit</a>
        <a href="#packages" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-full shadow-md transition">View Packages</a>
      </div>
    </div>

    <div class="lg:w-1/2 relative mt-12 lg:mt-0">
      <!-- Hero Image -->
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/reputation-mana-4.png" alt="Reputation Management" class="w-full rounded-xl shadow-xl">
    </div>
  </div>
</section>

<!-- PLATFORM STATISTICS -->
<section id="platforms" class="max-w-7xl mx-auto px-6 py-16">
  <h2 class="text-3xl font-bold text-center mb-12">Manage Reputation Across The Platforms Customers Trust</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php
    $platforms = new WP_Query(array(
      'post_type' => 'platform',
      'posts_per_page' => -1
    ));
    if ($platforms->have_posts()):
      while ($platforms->have_posts()): $platforms->the_post();
    ?>
        <div class="bg-white rounded-2xl shadow-md p-4 flex flex-col items-center text-center hover:shadow-xl transition">
          <?php if (has_post_thumbnail()): ?>
            <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>" class="w-24 h-24 object-cover rounded-lg mb-4">
          <?php endif; ?>
          <h3 class="font-bold text-lg mb-2"><?php the_title(); ?></h3>
          <p class="text-gray-600 text-sm"><?php echo wp_trim_words(get_the_content(), 20); ?></p>
          <button data-modal-target="modal-<?php the_ID(); ?>" class="text-green-500 font-semibold hover:underline mt-3">View Details</button>

          <!-- MODAL -->
          <div id="modal-<?php the_ID(); ?>" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-2xl max-w-xl w-full p-6 relative">
              <button data-modal-close class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
              <?php if (has_post_thumbnail()): ?>
                <img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php the_title(); ?>" class="w-full h-auto rounded-xl mb-4">
              <?php endif; ?>
              <h2 class="text-2xl font-bold mb-3"><?php the_title(); ?></h2>
              <div class="text-gray-700"><?php the_content(); ?></div>
            </div>
          </div>
        </div>
    <?php
      endwhile;
      wp_reset_postdata();
    endif;
    ?>
  </div>
</section>

<!-- FAQ SECTION -->
<section class="max-w-7xl mx-auto px-6 py-16">
  <h2 class="text-3xl font-bold text-center mb-12">Reputation Management Questions</h2>
  <div class="space-y-4 max-w-2xl mx-auto">
    <?php
    $faqs = array(
      "Do you create fake reviews?",
      "Can you remove negative reviews?",
      "Which platforms do you support?",
      "Who is this service best for?",
      "How long does reputation improvement take?"
    );
    foreach ($faqs as $faq):
    ?>
      <div x-data="{ open: false }" class="border rounded-lg p-4 cursor-pointer" @click="open = !open">
        <div class="flex justify-between items-center">
          <h3 class="font-semibold"><?php echo $faq; ?></h3>
          <span x-show="!open">+</span>
          <span x-show="open">-</span>
        </div>
        <p x-show="open" class="mt-2 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php get_footer(); ?>