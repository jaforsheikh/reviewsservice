<?php

/**
 * Services Page Template Content
 *
 * @package reviewsservice
 */

if (! defined('ABSPATH')) {
  exit;
}
?>

<!-- Services Page Hero Start -->
<section class="relative overflow-hidden bg-gradient-to-br from-[#0D0F12] via-[#111827] to-[#1F2937] px-4 py-24 text-white sm:px-6 lg:px-8 lg:py-32">

  <div class="absolute -left-32 top-10 h-96 w-96 rounded-full bg-[#1E3A8A]/35 blur-3xl"></div>
  <div class="absolute -right-32 bottom-10 h-96 w-96 rounded-full bg-[#00C853]/20 blur-3xl"></div>

  <div class="relative mx-auto max-w-screen-xl">

    <div class="max-w-4xl">
      <span class="inline-flex rounded-full border border-[#14B8A6]/30 bg-[#14B8A6]/10 px-5 py-2 text-sm font-black text-[#14B8A6]">
        Reviews Service Solutions
      </span>

      <h1 class="mt-6 text-4xl font-black leading-[1.12] tracking-[-0.018em] text-white sm:text-5xl lg:text-6xl">
        Digital Growth Services Built to Earn Trust, Attract Leads & Convert Customers
      </h1>

      <p class="mt-6 max-w-3xl text-lg font-semibold leading-8 text-white/80">
        Explore our complete service system for reputation management, social media management, WordPress website design, full stack development, content creation, and performance-focused media buying.
      </p>

      <div class="mt-9 flex flex-wrap gap-4">
        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="inline-flex rounded-full bg-gradient-to-r from-[#00C853] to-[#00E676] px-8 py-4 text-sm font-black text-[#0D0F12] shadow-[0_10px_30px_rgba(0,200,83,0.35)] transition hover:-translate-y-1">
          Get Free Consultation →
        </a>

        <a href="<?php echo esc_url(home_url('/shop/')); ?>" class="inline-flex rounded-full border border-white/20 bg-white/10 px-8 py-4 text-sm font-black text-white backdrop-blur transition hover:border-[#FFC107] hover:text-[#FFC107]">
          View Micro Services
        </a>
      </div>
    </div>

  </div>
</section>
<!-- Services Page Hero End -->

<!-- Services Detail Section Start -->
<section class="bg-gradient-to-b from-white to-[#F8FAFC] px-4 py-20 sm:px-6 lg:px-8">

  <div class="mx-auto max-w-screen-xl">

    <div class="mx-auto max-w-3xl text-center">
      <span class="inline-flex rounded-full bg-[#1E3A8A]/10 px-5 py-2 text-sm font-black text-[#1E3A8A]">
        What We Offer
      </span>

      <h2 class="mt-5 text-3xl font-black leading-[1.18] tracking-[-0.015em] text-[#0D0F12] sm:text-4xl lg:text-5xl">
        Choose the Right Service for Your Business Growth Stage
      </h2>

      <p class="mt-5 text-base font-semibold leading-8 text-[#374151] sm:text-lg">
        Every service is designed to solve a specific business problem — from trust and visibility to conversion, content, and paid growth.
      </p>
    </div>

    <div class="mt-14 grid gap-6 lg:grid-cols-2">

      <?php
      $services = array(
        array(
          'title'    => 'Reputation Management',
          'image'    => 'card-01-reputation-management.jpg',
          'hook'     => 'Turn your online reputation into a client magnet.',
          'desc'     => 'We help businesses collect more positive reviews, respond professionally, improve Google trust signals, and build a stronger online reputation.',
          'best_for' => 'Local businesses, clinics, restaurants, salons, real estate, and service providers.',
          'items'    => array(
            'Review generation workflow',
            'Negative review response strategy',
            'Google Business Profile trust optimization',
            'Reputation monitoring guidance',
          ),
        ),
        array(
          'title'    => 'Social Media Management',
          'image'    => 'card-02-social-media-management.jpg',
          'hook'     => 'Stay visible, consistent, and trusted online.',
          'desc'     => 'We plan, design, write, schedule, and manage social media content so your brand stays active and professional every week.',
          'best_for' => 'Businesses that need consistent brand presence without managing everything manually.',
          'items'    => array(
            'Content strategy and calendar',
            'Post design and caption writing',
            'Engagement direction',
            'Monthly performance improvement',
          ),
        ),
        array(
          'title'    => 'WordPress Website Design & Development',
          'image'    => 'card-03-wordpress-website-design.jpg',
          'hook'     => 'Convert website visitors into real inquiries.',
          'desc'     => 'We build fast, mobile-friendly, SEO-ready WordPress websites focused on trust, clarity, and conversion.',
          'best_for' => 'Service businesses needing a professional website that explains services and drives leads.',
          'items'    => array(
            'Custom WordPress design',
            'Mobile-first responsive layout',
            'SEO-friendly structure',
            'Conversion-focused CTA flow',
          ),
        ),
        array(
          'title'    => 'Full Stack Web Development',
          'image'    => 'card-04-full-stack-development.jpg',
          'hook'     => 'Build scalable websites and web applications.',
          'desc'     => 'We develop custom web platforms using modern technologies such as React, Next.js, TypeScript, APIs, and secure backend workflows.',
          'best_for' => 'Businesses needing custom dashboards, platforms, portals, or advanced web systems.',
          'items'    => array(
            'React / Next.js development',
            'API and backend systems',
            'Custom web application architecture',
            'Performance and scalability planning',
          ),
        ),
        array(
          'title'    => 'Content Creation & Posting',
          'image'    => 'card-05-content-creation.jpg',
          'hook'     => 'Create content that builds trust and attention.',
          'desc'     => 'We create branded visuals, captions, and content systems that help your business look active, credible, and professional online.',
          'best_for' => 'Brands that need better social posts, consistent creatives, and stronger content quality.',
          'items'    => array(
            'Social media creative design',
            'Caption and copywriting',
            'Brand visual consistency',
            'Content posting support',
          ),
        ),
        array(
          'title'    => 'Media Buying',
          'image'    => 'card-06-media-buying.jpg',
          'hook'     => 'Run paid campaigns that focus on real leads.',
          'desc'     => 'We help businesses plan, launch, track, and optimize Facebook, Instagram, and Google campaigns for better performance.',
          'best_for' => 'Businesses ready to invest in targeted ads and measurable lead generation.',
          'items'    => array(
            'Campaign strategy',
            'Audience and offer planning',
            'Conversion tracking setup',
            'ROI-focused optimization',
          ),
        ),
      );

      foreach ($services as $service) :
      ?>
        <article class="group overflow-hidden rounded-[32px] border border-[#E5E7EB]/80 bg-white shadow-[0_20px_70px_rgba(13,15,18,0.08)] transition-all duration-300 hover:-translate-y-2 hover:scale-[1.01] hover:border-[#00C853]/35 hover:shadow-[0_30px_90px_rgba(13,15,18,0.14)]">

          <div class="grid md:grid-cols-[220px_1fr]">
            <div class="relative min-h-64 overflow-hidden md:min-h-full">
              <img
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/' . $service['image']); ?>"
                alt="<?php echo esc_attr($service['title']); ?>"
                class="h-full w-full object-cover transition duration-700 group-hover:scale-110"
                loading="lazy">
              <div class="absolute inset-0 bg-gradient-to-t from-[#0D0F12]/65 to-transparent"></div>
            </div>

            <div class="p-6 sm:p-7">
              <h3 class="text-2xl font-black leading-tight tracking-[-0.015em] text-[#0D0F12]">
                <?php echo esc_html($service['title']); ?>
              </h3>

              <p class="mt-3 text-lg font-black leading-7 text-[#1E3A8A]">
                <?php echo esc_html($service['hook']); ?>
              </p>

              <p class="mt-4 text-sm font-semibold leading-7 text-[#374151]">
                <?php echo esc_html($service['desc']); ?>
              </p>

              <div class="mt-5 rounded-2xl bg-[#F3F4F6] p-4">
                <p class="text-xs font-black uppercase tracking-[0.12em] text-[#374151]">Best For</p>
                <p class="mt-2 text-sm font-semibold leading-6 text-[#374151]">
                  <?php echo esc_html($service['best_for']); ?>
                </p>
              </div>

              <ul class="mt-5 grid gap-3 sm:grid-cols-2">
                <?php foreach ($service['items'] as $item) : ?>
                  <li class="flex gap-3 text-sm font-bold leading-6 text-[#374151]">
                    <span class="mt-1 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#00C853]/15 text-xs text-[#00A844]">✓</span>
                    <?php echo esc_html($item); ?>
                  </li>
                <?php endforeach; ?>
              </ul>

              <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="mt-6 inline-flex rounded-full bg-gradient-to-r from-[#00C853] to-[#00E676] px-6 py-4 text-sm font-black text-[#0D0F12] shadow-[0_10px_30px_rgba(0,200,83,0.35)] transition hover:-translate-y-1">
                Discuss This Service →
              </a>
            </div>
          </div>

        </article>
      <?php endforeach; ?>

    </div>

  </div>
</section>
<!-- Services Detail Section End -->