<?php

/**
 * Template Name: Reputation Management
 *
 * Page: Reputation Management
 *
 * @package reviewsservice
 */

defined('ABSPATH') || exit;

get_header();

/* =========================================================
   PAGE DATA: SUPPORTED PLATFORMS
========================================================= */

$rm_platforms = [
  [
    'id'          => 'google-business-profile',
    'name'        => 'Google Business Profile',
    'short'       => 'Local trust, Google Maps visibility, and customer confidence.',
    'best_for'    => 'Local businesses, clinics, restaurants, agencies, contractors.',
    'icon'        => 'G',
    'accent'      => '#00C853',
    'overview'    => 'Google Business Profile is one of the most important reputation platforms for local businesses. Customers often check ratings, reviews, photos, and business information before calling or visiting.',
    'problem'     => 'Low ratings, missing responses, outdated profile information, and weak review volume can reduce calls, website visits, and customer trust.',
    'solution'    => 'We help improve your Google reputation system through profile audit, review request workflow, response strategy, and local trust optimization.',
    'included'    => [
      'Google Business Profile review audit',
      'Review request link setup',
      'Customer review request messaging',
      'Professional review response templates',
      'Negative review response guidance',
      'Monthly reputation improvement checklist',
    ],
    'outcome'     => 'Stronger local trust, improved customer confidence, better profile quality, and more conversion-ready visitors.',
  ],
  [
    'id'          => 'google-local-services',
    'name'        => 'Google Local Services',
    'short'       => 'Build trust for service-based businesses using verified review systems.',
    'best_for'    => 'Home services, repair services, local professionals, agencies.',
    'icon'        => 'LS',
    'accent'      => '#1E3A8A',
    'overview'    => 'Google Local Services reviews can influence trust when customers compare service providers. A clean and professional reputation helps improve lead confidence.',
    'problem'     => 'Many service providers lose leads because their profile looks inactive, has few reviews, or lacks strong trust signals.',
    'solution'    => 'We help build a review workflow and response strategy designed for service businesses that rely on local leads.',
    'included'    => [
      'Local service reputation audit',
      'Review request workflow',
      'Lead trust improvement checklist',
      'Response templates for service reviews',
      'Profile trust improvement recommendations',
    ],
    'outcome'     => 'More confident prospects, better trust signals, and stronger local service credibility.',
  ],
  [
    'id'          => 'trustpilot',
    'name'        => 'Trustpilot',
    'short'       => 'Improve brand trust for ecommerce and service businesses.',
    'best_for'    => 'Ecommerce, SaaS, service brands, online agencies.',
    'icon'        => 'TP',
    'accent'      => '#00B67A',
    'overview'    => 'Trustpilot is commonly used by customers to check brand credibility before buying online. A strong profile can support conversion and reduce hesitation.',
    'problem'     => 'Weak or unmanaged Trustpilot profiles can create doubt, especially for ecommerce and online service brands.',
    'solution'    => 'We help structure your Trustpilot presence, review request process, response strategy, and reputation monitoring workflow.',
    'included'    => [
      'Trustpilot profile audit',
      'Review request flow planning',
      'Response tone guideline',
      'Negative review response support',
      'Monthly reputation tracking',
    ],
    'outcome'     => 'A stronger trust profile, better buyer confidence, and improved brand credibility.',
  ],
  [
    'id'          => 'sitejabber',
    'name'        => 'Sitejabber',
    'short'       => 'Strengthen credibility for online stores and digital brands.',
    'best_for'    => 'Ecommerce, online service providers, digital businesses.',
    'icon'        => 'SJ',
    'accent'      => '#14B8A6',
    'overview'    => 'Sitejabber helps online buyers evaluate business credibility. It is especially useful for ecommerce and digital service businesses.',
    'problem'     => 'If your profile is incomplete, unmanaged, or has weak feedback, customers may choose a competitor instead.',
    'solution'    => 'We help optimize your profile, build review collection systems, and manage review responses professionally.',
    'included'    => [
      'Sitejabber profile review',
      'Customer feedback workflow',
      'Review response templates',
      'Trust improvement recommendations',
      'Monthly monitoring',
    ],
    'outcome'     => 'Improved trust perception and better confidence for online buyers.',
  ],
  [
    'id'          => 'bbb',
    'name'        => 'BBB',
    'short'       => 'Support business credibility and professional reputation presence.',
    'best_for'    => 'Established businesses, agencies, contractors, local companies.',
    'icon'        => 'BBB',
    'accent'      => '#005A9C',
    'overview'    => 'BBB presence can support business credibility, especially for customers who want to verify company trust and professionalism.',
    'problem'     => 'Unmanaged business profiles, unresolved complaints, and poor response strategy can damage customer confidence.',
    'solution'    => 'We help businesses improve profile quality, response systems, complaint communication, and reputation presentation.',
    'included'    => [
      'BBB profile review',
      'Business credibility checklist',
      'Complaint response guidance',
      'Review response support',
      'Trust-building recommendations',
    ],
    'outcome'     => 'Stronger business credibility, better trust signals, and a more professional public presence.',
  ],
  [
    'id'          => 'clutch',
    'name'        => 'Clutch',
    'short'       => 'Build authority for agencies, B2B services, and software firms.',
    'best_for'    => 'Agencies, B2B companies, software teams, consultants.',
    'icon'        => 'CL',
    'accent'      => '#FF3D2E',
    'overview'    => 'Clutch is important for B2B buyers comparing agencies and service providers. Strong reviews and case-style proof help improve authority.',
    'problem'     => 'Agencies often lose high-value leads because their public proof is weak, outdated, or not presented clearly.',
    'solution'    => 'We help improve your Clutch reputation presence with review strategy, profile optimization, and trust-building content direction.',
    'included'    => [
      'Clutch profile audit',
      'Client review request workflow',
      'Service positioning suggestions',
      'Review response support',
      'Authority improvement checklist',
    ],
    'outcome'     => 'Better B2B trust, stronger agency authority, and more qualified prospect confidence.',
  ],
  [
    'id'          => 'g2-capterra',
    'name'        => 'G2 & Capterra',
    'short'       => 'Improve trust for SaaS, software, and product-led businesses.',
    'best_for'    => 'SaaS, software companies, product teams, B2B tools.',
    'icon'        => 'SaaS',
    'accent'      => '#FFC107',
    'overview'    => 'G2 and Capterra are important review platforms for software buyers. Strong reputation helps reduce buyer hesitation and improves product trust.',
    'problem'     => 'Software buyers often compare reviews before requesting demos or starting trials. Weak review presence can reduce conversion.',
    'solution'    => 'We help SaaS brands plan review collection workflows, response systems, and reputation monitoring across software review platforms.',
    'included'    => [
      'SaaS review platform audit',
      'Customer review request workflow',
      'Review response templates',
      'Buyer trust improvement suggestions',
      'Monthly reputation reporting',
    ],
    'outcome'     => 'Stronger product trust, better demo confidence, and improved buyer perception.',
  ],
  [
    'id'          => 'facebook-yelp-tripadvisor',
    'name'        => 'Facebook, Yelp & Tripadvisor',
    'short'       => 'Manage social proof for local, food, travel, and service brands.',
    'best_for'    => 'Restaurants, hotels, local services, experience-based businesses.',
    'icon'        => 'FY',
    'accent'      => '#1877F2',
    'overview'    => 'Social and local review platforms help customers decide where to eat, visit, stay, or book services.',
    'problem'     => 'Unanswered reviews, poor rating perception, or inconsistent business information can reduce customer confidence.',
    'solution'    => 'We help improve review response systems, profile trust signals, and customer feedback workflows across local and social platforms.',
    'included'    => [
      'Platform presence audit',
      'Response templates',
      'Customer feedback workflow',
      'Rating improvement roadmap',
      'Monthly monitoring suggestions',
    ],
    'outcome'     => 'Improved public trust, better customer confidence, and stronger local reputation.',
  ],
];

/* =========================================================
   PAGE DATA: PROBLEMS
========================================================= */

$rm_problems = [
  [
    'title' => 'Low Rating Trust Problem',
    'text'  => 'Customers often compare ratings before contacting a business. A weak rating can silently reduce calls, bookings, and sales.',
  ],
  [
    'title' => 'No Review System',
    'text'  => 'Happy customers usually stay silent unless you have a clear review request process that makes feedback easy.',
  ],
  [
    'title' => 'Negative Review Damage',
    'text'  => 'Unanswered negative reviews can create fear and doubt for new customers who are researching your business.',
  ],
  [
    'title' => 'Weak Platform Presence',
    'text'  => 'Incomplete profiles, missing details, and inconsistent brand presentation reduce credibility across review platforms.',
  ],
];

/* =========================================================
   PAGE DATA: SOLUTIONS
========================================================= */

$rm_solutions = [
  'Review growth strategy built around real customer feedback',
  'Google Business Profile and multi-platform reputation audit',
  'Professional review response templates and tone guidelines',
  'Negative review response and dispute support for policy issues',
  'Customer feedback routing system for better experience control',
  'Monthly monitoring, reporting, and reputation improvement roadmap',
];

/* =========================================================
   PAGE DATA: PACKAGES
========================================================= */

$rm_packages = [
  [
    'name'        => 'Starter Reputation Audit',
    'tag'         => 'Foundation',
    'description' => 'Best for businesses that want to understand current reputation problems and get a clear improvement roadmap.',
    'features'    => [
      'Google Business Profile audit',
      'Review platform health check',
      'Reputation weakness report',
      'Review request message templates',
      'Basic improvement checklist',
    ],
    'cta'         => 'Start Audit',
    'featured'    => false,
  ],
  [
    'name'        => 'Review Growth System',
    'tag'         => 'Most Popular',
    'description' => 'Best for businesses that need a practical system to collect more genuine customer reviews consistently.',
    'features'    => [
      'Everything in Starter',
      'Review request workflow setup',
      'SMS / Email / WhatsApp scripts',
      'Positive feedback routing',
      'Review response templates',
      'Weekly reputation check',
    ],
    'cta'         => 'Build Review System',
    'featured'    => true,
  ],
  [
    'name'        => 'Multi-Platform Management',
    'tag'         => 'Advanced',
    'description' => 'Best for brands that want to manage reputation across Google, Trustpilot, Sitejabber, BBB, Clutch, and more.',
    'features'    => [
      'Multi-platform audit',
      'Platform-by-platform strategy',
      'Review response management',
      'Negative review guidance',
      'Monthly reputation report',
      'Competitor trust comparison',
    ],
    'cta'         => 'Manage Reputation',
    'featured'    => false,
  ],
  [
    'name'        => 'Enterprise Reputation Suite',
    'tag'         => 'Scale',
    'description' => 'Best for multi-location businesses, agencies, and enterprise brands that need structured reputation operations.',
    'features'    => [
      'Multi-location monitoring',
      'Team response guideline',
      'Monthly strategy call',
      'Advanced reporting',
      'Platform growth roadmap',
      'Priority support',
    ],
    'cta'         => 'Book Consultation',
    'featured'    => false,
  ],
];

/* =========================================================
   PAGE DATA: PROCESS
========================================================= */

$rm_process = [
  ['step' => '01', 'title' => 'Audit', 'text' => 'We review your current ratings, profiles, responses, visibility, and platform trust signals.'],
  ['step' => '02', 'title' => 'Strategy', 'text' => 'We create a reputation roadmap based on your business type, platforms, and customer journey.'],
  ['step' => '03', 'title' => 'System Setup', 'text' => 'We prepare review request workflows, response templates, and customer feedback routing.'],
  ['step' => '04', 'title' => 'Manage & Improve', 'text' => 'We monitor reputation, support review responses, and improve your public trust presentation.'],
];

/* =========================================================
   PAGE DATA: FAQ
========================================================= */

$rm_faqs = [
  [
    'question' => 'Do you create fake reviews?',
    'answer'   => 'No. We focus on ethical reputation management, genuine customer feedback systems, review request workflows, response strategy, and platform-safe improvement.',
  ],
  [
    'question' => 'Can you remove negative reviews?',
    'answer'   => 'We can help identify reviews that may violate platform policies and guide reporting or dispute steps. If a review is genuine and policy-compliant, we focus on professional response and trust recovery.',
  ],
  [
    'question' => 'Which platforms do you support?',
    'answer'   => 'We support Google Business Profile, Google Local Services, Trustpilot, Sitejabber, BBB, Clutch, G2, Capterra, Facebook Reviews, Yelp, Tripadvisor, and other industry-specific platforms.',
  ],
  [
    'question' => 'Who is this service best for?',
    'answer'   => 'It is best for local businesses, agencies, ecommerce brands, SaaS companies, restaurants, clinics, law firms, contractors, and service-based businesses.',
  ],
  [
    'question' => 'How long does reputation improvement take?',
    'answer'   => 'It depends on your current rating, customer volume, platform condition, and consistency. Most businesses need an ongoing system rather than a one-time fix.',
  ],
];

/* =========================================================
   SCHEMA DATA
========================================================= */

$rm_schema = [
  '@context' => 'https://schema.org',
  '@type'    => 'Service',
  'name'     => 'Reputation Management Services',
  'provider' => [
    '@type' => 'Organization',
    'name'  => get_bloginfo('name'),
    'url'   => home_url('/'),
  ],
  'serviceType' => 'Multi-Platform Reputation Management',
  'areaServed'  => 'Worldwide',
  'description' => 'Professional reputation management services for businesses across Google Business Profile, Trustpilot, Sitejabber, BBB, Clutch, G2, Capterra, Facebook Reviews, Yelp, Tripadvisor, and more.',
];
?>

<script type="application/ld+json">
  <?php echo wp_json_encode($rm_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<main id="primary" class="site-main rm-page" role="main">

  <!-- =====================================================
         HERO SECTION
    ====================================================== -->
  <section class="relative overflow-hidden bg-[#0D0F12] px-5 py-24 text-white sm:px-6 lg:px-8 lg:py-32">
    <div class="absolute -left-32 top-0 h-[420px] w-[420px] rounded-full bg-[#1E3A8A]/40 blur-3xl"></div>
    <div class="absolute -right-32 bottom-0 h-[520px] w-[520px] rounded-full bg-[#00C853]/25 blur-3xl"></div>

    <div class="relative mx-auto grid max-w-screen-xl items-center gap-12 lg:grid-cols-[1.15fr_0.85fr]">

      <div>
        <span class="inline-flex rounded-full border border-[#00C853]/30 bg-[#00C853]/10 px-5 py-2 text-sm font-bold text-[#00E676]">
          Multi-Platform Reputation Management
        </span>

        <h1 class="mt-7 max-w-4xl text-5xl font-black leading-tight tracking-[-0.04em] text-white sm:text-6xl lg:text-[76px]">
          Build Stronger Trust Across Every Review Platform
        </h1>

        <p class="mt-6 max-w-2xl text-lg font-semibold leading-8 text-white/78">
          We help businesses improve online reputation, collect genuine customer feedback, respond professionally, and build stronger trust across Google, Trustpilot, Sitejabber, BBB, Clutch, G2, Capterra, and more.
        </p>

        <div class="mt-9 flex flex-wrap gap-4">
          <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="inline-flex items-center justify-center rounded-full bg-[#00C853] px-7 py-4 text-sm font-bold text-[#0D0F12] shadow-[0_10px_30px_rgba(0,200,83,0.35)] transition hover:-translate-y-1 hover:bg-[#00A344]">
            Get Free Reputation Audit
            <span class="ml-2">→</span>
          </a>

          <a href="#rm-packages" class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/10 px-7 py-4 text-sm font-bold text-white backdrop-blur transition hover:border-[#FFC107] hover:text-[#FFC107]">
            View Packages
          </a>
        </div>

        <div class="mt-10 grid gap-4 sm:grid-cols-3">
          <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <strong class="block text-3xl font-black text-[#FFC107]">10+</strong>
            <span class="mt-2 block text-sm font-semibold text-white/70">Review Platforms</span>
          </div>

          <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <strong class="block text-3xl font-black text-[#00E676]">24/7</strong>
            <span class="mt-2 block text-sm font-semibold text-white/70">Reputation Monitoring</span>
          </div>

          <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
            <strong class="block text-3xl font-black text-[#14B8A6]">100%</strong>
            <span class="mt-2 block text-sm font-semibold text-white/70">Trust-Focused Strategy</span>
          </div>
        </div>
      </div>

      <div class="relative">
        <div class="rounded-[32px] border border-white/10 bg-white/10 p-6 shadow-[0_25px_80px_rgba(0,0,0,0.25)] backdrop-blur-xl">
          <div class="rounded-[28px] bg-white p-6 text-[#0D0F12]">
            <div class="flex items-center justify-between gap-4 border-b border-[#E5E7EB] pb-5">
              <div>
                <p class="text-sm font-bold text-[#00A344]">Reputation Snapshot</p>
                <h2 class="mt-2 text-2xl font-black tracking-[-0.03em]">Platform Trust Health</h2>
              </div>
              <span class="rounded-full bg-[#00C853]/10 px-4 py-2 text-sm font-bold text-[#00A344]">Audit Ready</span>
            </div>

            <div class="mt-6 space-y-4">
              <div class="rounded-2xl bg-[#F3F4F6] p-5">
                <div class="flex justify-between text-sm font-bold">
                  <span>Google Trust</span>
                  <span>Needs Review</span>
                </div>
                <div class="mt-3 h-3 overflow-hidden rounded-full bg-white">
                  <div class="h-full w-[72%] rounded-full bg-[#00C853]"></div>
                </div>
              </div>

              <div class="rounded-2xl bg-[#F3F4F6] p-5">
                <div class="flex justify-between text-sm font-bold">
                  <span>Response Quality</span>
                  <span>Improving</span>
                </div>
                <div class="mt-3 h-3 overflow-hidden rounded-full bg-white">
                  <div class="h-full w-[64%] rounded-full bg-[#FFC107]"></div>
                </div>
              </div>

              <div class="rounded-2xl bg-[#F3F4F6] p-5">
                <div class="flex justify-between text-sm font-bold">
                  <span>Multi-Platform Coverage</span>
                  <span>Opportunity</span>
                </div>
                <div class="mt-3 h-3 overflow-hidden rounded-full bg-white">
                  <div class="h-full w-[48%] rounded-full bg-[#1E3A8A]"></div>
                </div>
              </div>
            </div>

            <p class="mt-6 rounded-2xl bg-[#0D0F12] p-5 text-sm font-semibold leading-7 text-white/80">
              A stronger reputation system helps customers trust your business before they call, book, or buy.
            </p>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- =====================================================
         PLATFORMS SECTION
    ====================================================== -->
  <!-- Platforms Section -->
  <section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 text-center">
      <span class="text-green-600 font-semibold text-sm">Platform We Support</span>
      <h2 class="text-3xl md:text-4xl font-extrabold mt-2">Manage Reputation Across The Platforms Customers Trust</h2>

      <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <?php
        $platforms = new WP_Query(array(
          'post_type'      => 'platform',
          'posts_per_page' => -1,
          'orderby'        => 'menu_order',
          'order'          => 'ASC',
        ));

        if ($platforms->have_posts()) :
          while ($platforms->have_posts()) : $platforms->the_post(); ?>
            <div class="bg-white rounded-2xl p-6 shadow-md">
              <?php if (has_post_thumbnail()) : ?>
                <div class="mb-4">
                  <?php the_post_thumbnail('medium', ['class' => 'mx-auto h-12']); ?>
                </div>
              <?php endif; ?>
              <h3 class="font-bold text-lg mb-2"><?php the_title(); ?></h3>
              <p class="text-gray-500 text-sm"><?php the_excerpt(); ?></p>
              <a href="<?php the_permalink(); ?>" class="mt-4 inline-block text-green-600 font-semibold hover:underline">View Details</a>
            </div>
        <?php
          endwhile;
        endif;
        wp_reset_postdata();
        ?>
      </div>
    </div>
  </section>

  <!-- =====================================================
         PROBLEMS SECTION
    ====================================================== -->
  <section class="bg-[#F3F4F6] px-5 py-20 sm:px-6 lg:px-8 lg:py-28">
    <div class="mx-auto max-w-screen-xl">
      <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
        <div>
          <span class="inline-flex rounded-full bg-[#FFC107]/20 px-5 py-2 text-sm font-bold text-[#0D0F12]">
            Reputation Problems
          </span>

          <h2 class="mt-6 text-4xl font-black leading-tight tracking-[-0.03em] text-[#0D0F12] sm:text-5xl">
            Your Online Reputation Impacts Every Customer Decision
          </h2>

          <p class="mt-6 text-lg font-medium leading-8 text-[#374151]">
            Before customers contact you, they check your reviews, ratings, responses, and public trust signals. A weak reputation can reduce sales even when your service is good.
          </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
          <?php foreach ($rm_problems as $problem) : ?>
            <article class="rounded-[28px] border border-[#E5E7EB] bg-white p-7 shadow-[0_20px_70px_rgba(13,15,18,0.06)]">
              <h3 class="text-2xl font-black tracking-[-0.03em] text-[#0D0F12]">
                <?php echo esc_html($problem['title']); ?>
              </h3>

              <p class="mt-4 text-base font-medium leading-8 text-[#374151]">
                <?php echo esc_html($problem['text']); ?>
              </p>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- =====================================================
         SOLUTIONS SECTION
    ====================================================== -->
  <section class="bg-white px-5 py-20 sm:px-6 lg:px-8 lg:py-28">
    <div class="mx-auto grid max-w-screen-xl gap-10 lg:grid-cols-[1fr_1fr] lg:items-center">
      <div class="rounded-[32px] bg-[#0D0F12] p-8 text-white lg:p-10">
        <span class="inline-flex rounded-full bg-[#00C853]/15 px-5 py-2 text-sm font-bold text-[#00E676]">
          Our Reputation System
        </span>

        <h2 class="mt-6 text-4xl font-black leading-tight tracking-[-0.03em] text-white sm:text-5xl">
          We Build A Trust System, Not Just A Review Page
        </h2>

        <p class="mt-6 text-lg font-medium leading-8 text-white/75">
          Our system is designed to help businesses collect genuine feedback, respond professionally, improve customer confidence, and build reputation across multiple platforms.
        </p>
      </div>

      <div class="grid gap-4">
        <?php foreach ($rm_solutions as $solution) : ?>
          <div class="flex items-start gap-4 rounded-2xl border border-[#E5E7EB] bg-[#F9FAFB] p-5">
            <span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#00C853] text-sm font-black text-white">✓</span>
            <p class="text-base font-semibold leading-7 text-[#374151]">
              <?php echo esc_html($solution); ?>
            </p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- =====================================================
         PACKAGES SECTION
    ====================================================== -->
  <section id="rm-packages" class="bg-[#F3F4F6] px-5 py-20 sm:px-6 lg:px-8 lg:py-28">
    <div class="mx-auto max-w-screen-xl">
      <div class="mx-auto max-w-3xl text-center">
        <span class="inline-flex rounded-full bg-[#00C853]/10 px-5 py-2 text-sm font-bold text-[#00A344]">
          Reputation Packages
        </span>

        <h2 class="mt-6 text-4xl font-black leading-tight tracking-[-0.03em] text-[#0D0F12] sm:text-5xl lg:text-6xl">
          Choose The Reputation System That Fits Your Business
        </h2>

        <p class="mt-6 text-lg font-medium leading-8 text-[#374151]">
          Start with an audit, build a review system, or manage your full multi-platform reputation with ongoing support.
        </p>
      </div>

      <div class="mt-14 grid gap-6 lg:grid-cols-4">
        <?php foreach ($rm_packages as $package) : ?>
          <article class="<?php echo $package['featured'] ? 'border-[#00C853] bg-[#0D0F12] text-white shadow-[0_30px_90px_rgba(0,200,83,0.18)]' : 'border-[#E5E7EB] bg-white text-[#0D0F12] shadow-[0_20px_70px_rgba(13,15,18,0.06)]'; ?> rounded-[28px] border p-7">
            <span class="<?php echo $package['featured'] ? 'bg-[#00C853] text-[#0D0F12]' : 'bg-[#00C853]/10 text-[#00A344]'; ?> inline-flex rounded-full px-4 py-2 text-xs font-black uppercase tracking-[0.12em]">
              <?php echo esc_html($package['tag']); ?>
            </span>

            <h3 class="mt-6 text-2xl font-black tracking-[-0.03em] <?php echo $package['featured'] ? 'text-white' : 'text-[#0D0F12]'; ?>">
              <?php echo esc_html($package['name']); ?>
            </h3>

            <p class="mt-4 text-sm font-medium leading-7 <?php echo $package['featured'] ? 'text-white/70' : 'text-[#374151]'; ?>">
              <?php echo esc_html($package['description']); ?>
            </p>

            <ul class="mt-7 space-y-4">
              <?php foreach ($package['features'] as $feature) : ?>
                <li class="flex items-start gap-3 text-sm font-semibold leading-6 <?php echo $package['featured'] ? 'text-white/80' : 'text-[#374151]'; ?>">
                  <span class="mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#00C853] text-xs font-black text-white">✓</span>
                  <?php echo esc_html($feature); ?>
                </li>
              <?php endforeach; ?>
            </ul>

            <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="<?php echo $package['featured'] ? 'bg-[#00C853] text-[#0D0F12] hover:bg-[#00E676]' : 'bg-[#0D0F12] text-white hover:bg-[#00C853] hover:text-[#0D0F12]'; ?> mt-8 inline-flex w-full items-center justify-center rounded-full px-5 py-4 text-sm font-bold transition">
              <?php echo esc_html($package['cta']); ?>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- =====================================================
         PROCESS SECTION
    ====================================================== -->
  <section class="bg-white px-5 py-20 sm:px-6 lg:px-8 lg:py-28">
    <div class="mx-auto max-w-screen-xl">
      <div class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr]">
        <div>
          <span class="inline-flex rounded-full bg-[#1E3A8A]/10 px-5 py-2 text-sm font-bold text-[#1E3A8A]">
            Our Process
          </span>

          <h2 class="mt-6 text-4xl font-black leading-tight tracking-[-0.03em] text-[#0D0F12] sm:text-5xl">
            Simple Process. Stronger Reputation.
          </h2>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
          <?php foreach ($rm_process as $process) : ?>
            <article class="rounded-[28px] border border-[#E5E7EB] bg-[#F9FAFB] p-7">
              <span class="text-sm font-black text-[#00A344]">
                <?php echo esc_html($process['step']); ?>
              </span>

              <h3 class="mt-4 text-2xl font-black tracking-[-0.03em] text-[#0D0F12]">
                <?php echo esc_html($process['title']); ?>
              </h3>

              <p class="mt-4 text-base font-medium leading-8 text-[#374151]">
                <?php echo esc_html($process['text']); ?>
              </p>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- =====================================================
         FAQ SECTION
    ====================================================== -->
  <section class="bg-[#F3F4F6] px-5 py-20 sm:px-6 lg:px-8 lg:py-28">
    <div class="mx-auto max-w-4xl">
      <div class="text-center">
        <span class="inline-flex rounded-full bg-[#00C853]/10 px-5 py-2 text-sm font-bold text-[#00A344]">
          FAQ
        </span>

        <h2 class="mt-6 text-4xl font-black leading-tight tracking-[-0.03em] text-[#0D0F12] sm:text-5xl">
          Reputation Management Questions
        </h2>
      </div>

      <div class="mt-12 space-y-4">
        <?php foreach ($rm_faqs as $faq) : ?>
          <details class="rounded-3xl border border-[#E5E7EB] bg-white p-6 shadow-sm">
            <summary class="cursor-pointer text-lg font-black text-[#0D0F12]">
              <?php echo esc_html($faq['question']); ?>
            </summary>

            <p class="mt-4 text-base font-medium leading-8 text-[#374151]">
              <?php echo esc_html($faq['answer']); ?>
            </p>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- =====================================================
         FINAL CTA SECTION
    ====================================================== -->
  <section class="relative overflow-hidden bg-[#0D0F12] px-5 py-20 text-white sm:px-6 lg:px-8 lg:py-28">
    <div class="absolute -left-32 top-0 h-96 w-96 rounded-full bg-[#1E3A8A]/40 blur-3xl"></div>
    <div class="absolute -right-32 bottom-0 h-96 w-96 rounded-full bg-[#00C853]/25 blur-3xl"></div>

    <div class="relative mx-auto max-w-4xl text-center">
      <span class="inline-flex rounded-full bg-[#00C853]/15 px-5 py-2 text-sm font-bold text-[#00E676]">
        Ready To Build More Trust?
      </span>

      <h2 class="mt-6 text-4xl font-black leading-tight tracking-[-0.03em] text-white sm:text-5xl lg:text-6xl">
        Start With A Free Reputation Audit
      </h2>

      <p class="mx-auto mt-6 max-w-2xl text-lg font-medium leading-8 text-white/75">
        Let us review your current reputation, platform presence, customer trust signals, and improvement opportunities.
      </p>

      <div class="mt-9 flex justify-center">
        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="inline-flex items-center justify-center rounded-full bg-[#00C853] px-8 py-4 text-sm font-bold text-[#0D0F12] shadow-[0_10px_30px_rgba(0,200,83,0.35)] transition hover:-translate-y-1 hover:bg-[#00E676]">
          Book Free Consultation
          <span class="ml-2">→</span>
        </a>
      </div>
    </div>
  </section>

  <!-- =====================================================
         PLATFORM MODAL SYSTEM
    ====================================================== -->
  <div class="fixed inset-0 z-[999] hidden items-center justify-center bg-[#0D0F12]/75 px-5 backdrop-blur-sm" data-rm-modal>
    <div class="relative max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-[32px] bg-white p-7 shadow-2xl lg:p-9">
      <button
        type="button"
        class="absolute right-5 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-[#F3F4F6] text-xl font-black text-[#0D0F12] transition hover:bg-[#0D0F12] hover:text-white"
        data-rm-modal-close
        aria-label="<?php esc_attr_e('Close modal', 'reviewsservice'); ?>">
        ×
      </button>

      <div data-rm-modal-content></div>
    </div>
  </div>

  <script type="application/json" id="rm-platform-data">
    <?php echo wp_json_encode($rm_platforms, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
  </script>

</main>

<?php get_footer(); ?>