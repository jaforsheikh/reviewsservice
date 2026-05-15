<?php
/**
 * Professional My Account Navigation
 * File: woocommerce/myaccount/navigation.php
 */

defined('ABSPATH') || exit;

$icons = [
    'dashboard'       => '🏠',
    'orders'          => '📦',
    'downloads'       => '⬇️',
    'edit-address'    => '📍',
    'payment-methods' => '💳',
    'edit-account'    => '👤',
    'customer-logout' => '↪',
];
?>

<nav class="woocommerce-MyAccount-navigation">
    <ul class="m-0 grid list-none gap-2 p-0">
        <?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
            <?php
            $classes   = wc_get_account_menu_item_classes($endpoint);
            $is_active = strpos($classes, 'is-active') !== false;
            ?>

            <li class="<?php echo esc_attr($classes); ?>">
                <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"
                   class="<?php echo esc_attr($is_active
                       ? 'flex items-center justify-between rounded-2xl bg-[#0D0F12] px-4 py-3.5 text-sm font-black text-white shadow-sm'
                       : 'flex items-center justify-between rounded-2xl px-4 py-3.5 text-sm font-black text-[#374151] transition hover:bg-[#F3F4F6] hover:text-[#0D0F12]'
                   ); ?>">

                    <span class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#F3F4F6] text-base">
                            <?php echo esc_html($icons[$endpoint] ?? '•'); ?>
                        </span>

                        <span><?php echo esc_html($label); ?></span>
                    </span>

                    <span class="<?php echo esc_attr($is_active ? 'text-white/70' : 'text-gray-400'); ?>">
                        →
                    </span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>