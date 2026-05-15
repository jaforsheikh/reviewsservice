<?php
/**
 * Professional My Account Layout
 * File: woocommerce/myaccount/my-account.php
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$display_name = $current_user->display_name ?: __('Client', 'reviewsservice');
?>

<!-- My Account Page Start -->
<section class="bg-[#F3F4F6] px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
    <div class="mx-auto w-full max-w-[1140px]">

        <!-- Dashboard Header Start -->
        <div class="mb-8 rounded-[28px] bg-gradient-to-br from-[#0D0F12] via-[#1E3A8A] to-[#00C853] p-6 shadow-xl sm:p-8 lg:p-10">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">

                <div>
                    <span class="inline-flex rounded-full bg-white/10 px-4 py-2 text-xs font-black uppercase tracking-wider text-[#00E676]">
                        Client Dashboard
                    </span>

                    <h1 class="mt-5 font-[Poppins] text-3xl font-black leading-tight text-white sm:text-4xl lg:text-5xl">
                        My Account
                    </h1>

                    <p class="mt-4 max-w-2xl text-sm font-semibold leading-7 text-white/75 sm:text-base">
                        Welcome back, <?php echo esc_html($display_name); ?>. Manage your orders, billing details, addresses, downloads, and account security.
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3 sm:min-w-[360px]">
                    <div class="rounded-2xl bg-white/10 p-4 text-white backdrop-blur">
                        <p class="text-2xl">📦</p>
                        <p class="mt-2 text-xs font-black uppercase tracking-wide text-white/60">Orders</p>
                    </div>

                    <div class="rounded-2xl bg-white/10 p-4 text-white backdrop-blur">
                        <p class="text-2xl">🛡️</p>
                        <p class="mt-2 text-xs font-black uppercase tracking-wide text-white/60">Secure</p>
                    </div>

                    <div class="rounded-2xl bg-white/10 p-4 text-white backdrop-blur">
                        <p class="text-2xl">💬</p>
                        <p class="mt-2 text-xs font-black uppercase tracking-wide text-white/60">Support</p>
                    </div>
                </div>

            </div>
        </div>
        <!-- Dashboard Header End -->

        <!-- Dashboard Body Start -->
        <div class="grid gap-6 lg:grid-cols-[280px_1fr] lg:gap-8">

            <!-- Account Navigation Start -->
            <aside class="h-fit rounded-[28px] border border-gray-200 bg-white p-4 shadow-sm lg:sticky lg:top-28">
                <?php do_action('woocommerce_account_navigation'); ?>
            </aside>
            <!-- Account Navigation End -->

            <!-- Account Content Start -->
            <div class="min-w-0 rounded-[28px] border border-gray-200 bg-white p-5 shadow-sm sm:p-7 lg:p-8">
                <?php do_action('woocommerce_account_content'); ?>
            </div>
            <!-- Account Content End -->

        </div>
        <!-- Dashboard Body End -->

    </div>
</section>
<!-- My Account Page End -->