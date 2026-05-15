<?php
/**
 * Main fallback template
 *
 * This file should not contain homepage design.
 * Homepage design is controlled by front-page.php.
 *
 * @package ReviewsService
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main class="rs-page-main">
    <section class="rs-page-section">
        <div class="rs-container">
            <?php if (have_posts()) : ?>
                <div class="rs-page-content">
                    <?php
                    while (have_posts()) :
                        the_post();
                        ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class('rs-content-card'); ?>>
                            <header class="rs-content-header">
                                <h1><?php the_title(); ?></h1>
                            </header>

                            <div class="rs-content-body">
                                <?php the_content(); ?>
                            </div>
                        </article>

                        <?php
                    endwhile;
                    ?>
                </div>
            <?php else : ?>
                <div class="rs-content-card">
                    <h1>Content not found</h1>
                    <p>The content you are looking for is not available.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();