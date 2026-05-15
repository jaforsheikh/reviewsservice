<?php

/**
 * Global FAQ Section
 *
 * @package reviewsservice
 */

defined('ABSPATH') || exit;

$faq_badge = $args['badge'] ?? 'FAQ';
$faq_title = $args['title'] ?? 'Frequently Asked Questions';
$faq_description = $args['description'] ?? 'Find quick answers to common questions about our services, process, and support.';

$faqs = $args['faqs'] ?? [];

if (empty($faqs)) {
  return;
}
?>

<section class="rs-section bg-[#F3F4F6]">
  <div class="rs-container">

    <div class="rs-section-header">
      <span class="rs-badge">
        <?php echo esc_html($faq_badge); ?>
      </span>

      <h2 class="rs-section-title">
        <?php echo esc_html($faq_title); ?>
      </h2>

      <p class="rs-section-description">
        <?php echo esc_html($faq_description); ?>
      </p>
    </div>

    <div class="mx-auto mt-12 grid max-w-4xl gap-4">

      <?php foreach ($faqs as $faq) : ?>
        <details class="rs-card p-6">
          <summary class="cursor-pointer text-lg font-black text-[#0D0F12]">
            <?php echo esc_html($faq['question'] ?? ''); ?>
          </summary>

          <p class="mt-4 text-base font-medium leading-8 text-[#4B5563]">
            <?php echo esc_html($faq['answer'] ?? ''); ?>
          </p>
        </details>
      <?php endforeach; ?>

    </div>

  </div>
</section>