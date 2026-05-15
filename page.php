<?php

/**
 * Dynamic Page Template
 *
 * Loads clean page files from:
 * templates/pages/{page-slug}.php
 *
 * Example:
 * /services/     -> templates/pages/services.php
 * /about/        -> templates/pages/about.php
 * /contact/      -> templates/pages/contact.php
 * /case-studies/ -> templates/pages/case-studies.php
 *
 * @package reviewsservice
 */

if (! defined('ABSPATH')) {
  exit;
}

get_header();

$page_slug     = get_post_field('post_name', get_the_ID());
$template_path = 'templates/pages/' . $page_slug;

?>

<main id="primary" class="site-main">

  <?php
  if (locate_template($template_path . '.php')) {
    get_template_part($template_path);
  } else {
    get_template_part('template-parts/content/content-page');
  }
  ?>

</main>

<?php
get_footer();
