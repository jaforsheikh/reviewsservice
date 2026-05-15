<?php

/**
 * Premium Empty Cart
 * File: woocommerce/cart/cart-empty.php
 */

defined('ABSPATH') || exit;

?>

<!-- Empty Cart Start -->
<section class="bg-[#F3F4F6] px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
  <div class="mx-auto max-w-4xl">

    <div class="overflow-hidden rounded-[2.5rem] border border-gray-200 bg-white shadow-2xl">

      <!-- Top Gradient -->
      <div class="bg-gradient-to-r from-[#0D0F12] via-[#1E3A8A] to-[#00C853] px-6 py-14 text-center sm:px-10">

        <div class="mx-auto flex h-28 w-28 items-center justify-center rounded-full bg-white/10 backdrop-blur">
          <span class="text-5xl">🛒</span>
        </div>

        <h1 class="mt-8 font-[Poppins] text-4xl font-black tracking-tight text-white sm:text-5xl">
          Your Cart Is Empty
        </h1>

        <p class="mx-auto mt-5 max-w-2xl text-base font-semibold leading-8 text-white/80 sm:text-lg">
          Looks like you haven’t added any services yet.
          Explore our premium business growth services and start building your online reputation today.
        </p>

      </div>

      <!-- Content -->
      <div class="px-6 py-12 sm:px-10">

        <!-- Features -->
        <div class="grid gap-4 sm:grid-cols-3">

          <div class="rounded-3xl bg-[#F9FAFB] p-6 text-center">
            <div class="text-3xl">⚡</div>

            <h3 class="mt-4 font-[Poppins] text-lg font-black text-[#0D0F12]">
              Fast Delivery
            </h3>

            <p class="mt-2 text-sm leading-7 text-[#374151]">
              Quick turnaround for all digital services.
            </p>
          </div>

          <div class="rounded-3xl bg-[#F9FAFB] p-6 text-center">
            <div class="text-3xl">✅</div>

            <h3 class="mt-4 font-[Poppins] text-lg font-black text-[#0D0F12]">
              Trusted Services
            </h3>

            <p class="mt-2 text-sm leading-7 text-[#374151]">
              Business-focused services designed for growth.
            </p>
          </div>

          <div class="rounded-3xl bg-[#F9FAFB] p-6 text-center">
            <div class="text-3xl">💬</div>

            <h3 class="mt-4 font-[Poppins] text-lg font-black text-[#0D0F12]">
              Expert Support
            </h3>

            <p class="mt-2 text-sm leading-7 text-[#374151]">
              Get help choosing the right service package.
            </p>
          </div>

        </div>

        <!-- Buttons -->
        <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">

          <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"
            class="inline-flex items-center justify-center rounded-full bg-[#00C853] px-8 py-4 text-sm font-black text-white shadow-lg transition hover:bg-[#00A344]">
            Browse Services →
          </a>

          <a href="<?php echo esc_url(home_url('/contact/')); ?>"
            class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-8 py-4 text-sm font-black text-[#0D0F12] transition hover:border-[#00C853] hover:text-[#00A344]">
            Free Consultation
          </a>

        </div>

      </div>

    </div>

  </div>
</section>
<!-- Empty Cart End -->