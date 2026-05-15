<?php

/**
 * Footer Template
 *
 * @package reviewsservice
 */

defined('ABSPATH') || exit;

$footer_logo_url = get_template_directory_uri() . '/assets/images/footer-logo.png';

$service_links = [
    ['label' => 'Reputation Management', 'url' => home_url('/services/reputation-management/')],
    ['label' => 'Social Media Management', 'url' => home_url('/services/social-media-management/')],
    ['label' => 'Website Design', 'url' => home_url('/services/website-design/')],
    ['label' => 'Full Stack Development', 'url' => home_url('/services/full-stack-development/')],
    ['label' => 'Content Creation', 'url' => home_url('/services/content-creation/')],
    ['label' => 'Media Buying', 'url' => home_url('/services/media-buying/')],
];

$quick_links = [
    ['label' => 'Home', 'url' => home_url('/')],
    ['label' => 'Offers', 'url' => home_url('/offers/')],
    ['label' => 'Case Studies', 'url' => home_url('/case-studies/')],
    ['label' => 'Blog', 'url' => home_url('/blog/')],
    ['label' => 'About', 'url' => home_url('/about/')],
    ['label' => 'Contact', 'url' => home_url('/contact/')],
];

// Social links with inline SVGs
$social_links = [
    // Facebook
    ['icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 5.007 3.657 9.128 8.438 9.879v-6.987h-2.54v-2.892h2.54v-2.206c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.772-1.63 1.562v1.88h2.773l-.443 2.892h-2.33v6.987C18.343 21.128 22 17.007 22 12z"/></svg>', 'url' => 'https://www.facebook.com/reviewsservicepro/'],

    // Instagram (correct SVG)
    ['icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 512 512"><path d="M349.33 69.33H162.67C101.05 69.33 56 114.38 56 176v172c0 61.62 45.05 106.67 106.67 106.67h186.67C410.95 454.67 456 409.62 456 348V176c0-61.62-45.05-106.67-106.67-106.67zM256 354.67c-54.68 0-98.67-44-98.67-98.67s44-98.67 98.67-98.67 98.67 44 98.67 98.67-44 98.67-98.67 98.67zm104-178.67a24 24 0 11-24-24 24 24 0 0124 24z"/></svg>', 'url' => 'https://www.instagram.com/reviewsservicepro/'],

    // LinkedIn
    ['icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 0H2C.895 0 0 .895 0 2v20c0 1.105.895 2 2 2h20c1.105 0 2-.895 2-2V2c0-1.105-.895-2-2-2zM7.5 20H4v-9h3.5v9zM6 10.25a1.75 1.75 0 110-3.5 1.75 1.75 0 010 3.5zm14 9h-3.5v-4.5c0-1.072-.024-2.45-1.494-2.45-1.496 0-1.726 1.168-1.726 2.371V19H11v-9h3.364v1.22h.048c.468-.888 1.609-1.82 3.309-1.82 3.536 0 4.188 2.33 4.188 5.358V19z"/></svg>', 'url' => 'https://www.linkedin.com/company/reviewsservice/'],

    // X (Twitter) icon
    ['icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 512 512"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c14.182 7.877 30.355 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.877-2.599-15.753-2.599-24.157 0-57.828 46.782-104.934 104.934-104.934 30.355 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>', 'url' => 'https://x.com/ReviewsService_'],
];
?>

<!-- ========================= FOOTER ========================= -->
<footer class="bg-gray-900 text-gray-300 pt-20">
    <div class="max-w-7xl mx-auto px-6">

        <!-- Grid Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

            <!-- Brand + Social -->
            <div class="space-y-6">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="block">
                    <img src="<?php echo esc_url($footer_logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="h-12 w-auto">
                </a>

                <p class="text-gray-400 leading-relaxed text-sm">
                    Reviews Service helps businesses build trust, improve online reputation, grow visibility, and convert more customers through smart digital systems.
                </p>

                <div class="flex gap-2 mt-4">
                    <?php foreach ($social_links as $social): ?>
                        <a href="<?php echo esc_url($social['url']); ?>" target="_blank" rel="noopener noreferrer"
                            class="border border-gray-500 rounded-full p-2 hover:bg-green-600 hover:border-green-600 transition-all duration-200 flex items-center justify-center">
                            <?php echo $social['icon']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Services Links -->
            <div>
                <h3 class="text-white font-bold mb-4">Services</h3>
                <ul class="space-y-2">
                    <?php foreach ($service_links as $link): ?>
                        <li><a href="<?php echo esc_url($link['url']); ?>" class="hover:text-green-500 transition-colors"><?php echo esc_html($link['label']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-white font-bold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <?php foreach ($quick_links as $link): ?>
                        <li><a href="<?php echo esc_url($link['url']); ?>" class="hover:text-green-500 transition-colors"><?php echo esc_html($link['label']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- CTA Section -->
            <div class="space-y-4">
                <h3 class="text-white font-bold mb-2">Start Growing</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Get a clear reputation and growth plan for your business.
                </p>

                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="inline-block w-full py-3 px-6 rounded-full bg-green-600 text-gray-900 font-bold text-center hover:bg-green-500 transition-all">
                    Free Consultation
                </a>

                <a href="<?php echo esc_url(home_url('/my-account/')); ?>" class="inline-block w-full py-3 px-6 rounded-full border border-gray-500 text-gray-300 text-center hover:text-green-500 transition-all">
                    My Account
                </a>
            </div>

        </div>

        <!-- Bottom Footer -->
        <div class="mt-16 border-t border-gray-700 pt-6 pb-6 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-400">
            <p>© <?php echo date('Y'); ?> <?php echo esc_html(get_bloginfo('name')); ?>. All rights reserved.</p>
            <div class="flex gap-4">
                <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>" class="hover:text-green-500 transition-colors">Privacy Policy</a>
                <a href="<?php echo esc_url(home_url('/terms-conditions/')); ?>" class="hover:text-green-500 transition-colors">Terms & Conditions</a>
            </div>
        </div>

    </div>
</footer>

<?php wp_footer(); ?>
</body>

</html>