<?php

/**
 * Platform Grid
 * Display all platforms with Tailwind CSS cards and modal view
 *
 * @package reviewsservice
 */

// Query Platforms CPT
$platforms = get_posts([
  'post_type' => 'platform',
  'posts_per_page' => -1,
  'orderby' => 'menu_order',
  'order' => 'ASC',
]);

if (!$platforms) return;
?>

<div class="max-w-7xl mx-auto px-6 py-16">
  <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Platform We Support</h2>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
    <?php foreach ($platforms as $platform):
      $short_desc = get_post_meta($platform->ID, 'short_description', true);
      $full_desc = get_post_meta($platform->ID, 'full_description', true);
      $featured_img = get_the_post_thumbnail_url($platform->ID, 'medium');
      $external_link = get_post_meta($platform->ID, 'external_link', true);
    ?>
      <!-- Platform Card -->
      <div class="rs-card bg-white rounded-xl shadow-lg overflow-hidden" data-platform-id="<?php echo esc_attr($platform->ID); ?>">
        <?php if ($featured_img): ?>
          <img src="<?php echo esc_url($featured_img); ?>" alt="<?php echo esc_attr(get_the_title($platform->ID)); ?>" class="w-full h-40 object-cover">
        <?php endif; ?>
        <div class="p-4">
          <h3 class="font-bold text-lg"><?php echo esc_html(get_the_title($platform->ID)); ?></h3>
          <p class="text-gray-600 mt-2 text-sm"><?php echo esc_html($short_desc); ?></p>
          <button
            data-modal-target="modal-<?php echo esc_attr($platform->ID); ?>"
            class="mt-4 text-green-600 font-semibold hover:underline">
            View Details
          </button>
        </div>
      </div>

      <!-- Hidden Modal -->
      <div id="modal-<?php echo esc_attr($platform->ID); ?>" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl max-w-xl w-full p-6 relative">
          <button data-modal-close class="absolute top-3 right-3 text-gray-500 hover:text-gray-900 font-bold">&times;</button>
          <?php if ($featured_img): ?>
            <img src="<?php echo esc_url($featured_img); ?>" alt="<?php echo esc_attr(get_the_title($platform->ID)); ?>" class="rounded mb-4">
          <?php endif; ?>
          <h2 class="font-bold text-2xl mb-2"><?php echo esc_html(get_the_title($platform->ID)); ?></h2>
          <p class="text-gray-700"><?php echo esc_html($full_desc); ?></p>
          <?php if ($external_link): ?>
            <a href="<?php echo esc_url($external_link); ?>" class="inline-block mt-4 text-green-600 hover:underline">Visit Service</a>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Modal JS -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Open modal
    document.querySelectorAll("[data-modal-target]").forEach(btn => {
      btn.addEventListener("click", () => {
        const target = document.getElementById(btn.getAttribute("data-modal-target"));
        if (target) target.classList.remove("hidden");
      });
    });

    // Close modal
    document.querySelectorAll("[data-modal-close]").forEach(btn => {
      btn.addEventListener("click", () => {
        btn.closest("[id^='modal-']").classList.add("hidden");
      });
    });

    // Close modal on click outside
    document.querySelectorAll("[id^='modal-']").forEach(modal => {
      modal.addEventListener("click", e => {
        if (e.target === modal) modal.classList.add("hidden");
      });
    });
  });
</script>