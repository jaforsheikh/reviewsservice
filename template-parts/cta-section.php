<?php

/**
 * Global CTA Section
 *
 * @package reviewsservice
 */

defined('ABSPATH') || exit;

$cta_badge = $args['badge'] ?? 'Need Better Results?';

$cta_title = $args['title'] ?? 'Grow Your Brand With A Smarter Digital Strategy';

$cta_description = $args['description'] ?? 'We help businesses improve visibility, build trust, increase conversions, and create stronger customer confidence through modern reputation and growth systems.';

$cta_primary_button = $args['primary_button'] ?? [
  'label' => 'Free Consultation',
  'url'   => home_url('/contact/'),
];

$cta_secondary_button = $args['secondary_button'] ?? [
  'label' => 'View Services',
  'url'   => home_url('/services/'),
];
?>

<section class="rs-section">
  <div class="rs-container">

    <div class="rs-cta-box">

      <div class="rs-badge">
        <?php echo esc_html($cta_badge); ?>
      </div>

      <h2 class="mt-6">
        <?php echo esc_html($cta_title); ?>
      </h2>

      <p>
        <?php echo esc_html($cta_description); ?>
      </p>

      <div class="mt-10 flex flex-wrap gap-4">

        <a
          href="<?php echo esc_url($cta_primary_button['url']); ?>"
          class="rs-btn-primary">
          <?php echo esc_html($cta_primary_button['label']); ?>
        </a>

        <a
          href="<?php echo esc_url($cta_secondary_button['url']); ?>"
          class="rs-btn-outline bg-white text-[#0D0F12]">
          <?php echo esc_html($cta_secondary_button['label']); ?>
        </a>

      </div>

    </div>

  </div>
</section>