<?php
// Register Platforms CPT
function reviewsservice_register_platforms_cpt()
{
  $labels = array(
    'name'               => _x('Platforms', 'post type general name', 'reviewsservice'),
    'singular_name'      => _x('Platform', 'post type singular name', 'reviewsservice'),
    'menu_name'          => _x('Platforms', 'admin menu', 'reviewsservice'),
    'name_admin_bar'     => _x('Platform', 'add new on admin bar', 'reviewsservice'),
    'add_new'            => _x('Add New', 'platform', 'reviewsservice'),
    'add_new_item'       => __('Add New Platform', 'reviewsservice'),
    'new_item'           => __('New Platform', 'reviewsservice'),
    'edit_item'          => __('Edit Platform', 'reviewsservice'),
    'view_item'          => __('View Platform', 'reviewsservice'),
    'all_items'          => __('All Platforms', 'reviewsservice'),
    'search_items'       => __('Search Platforms', 'reviewsservice'),
    'not_found'          => __('No platforms found.', 'reviewsservice'),
    'not_found_in_trash' => __('No platforms found in Trash.', 'reviewsservice')
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array('slug' => 'platforms'),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => 5,
    'menu_icon'          => 'dashicons-admin-site',
    'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
  );

  register_post_type('platform', $args);
}
add_action('init', 'reviewsservice_register_platforms_cpt');
// Register Platform Category Taxonomy
function reviewsservice_register_platform_taxonomy()
{
  register_taxonomy(
    'platform_category',
    'platform',
    array(
      'label' => __('Platform Category', 'reviewsservice'),
      'rewrite' => array('slug' => 'platform-category'),
      'hierarchical' => true,
      'show_admin_column' => true,
    )
  );
}
add_action('init', 'reviewsservice_register_platform_taxonomy');

// Add duplicate link to Platforms CPT row actions
function reviewsservice_platform_duplicate_link($actions, $post)
{
  if ($post->post_type === 'platform') {
    $url = wp_nonce_url(
      admin_url('admin.php?action=duplicate_post&post=' . $post->ID),
      'duplicate_post_' . $post->ID
    );
    $actions['duplicate'] = '<a href="' . $url . '" title="Duplicate this Platform">Duplicate</a>';
  }
  return $actions;
}
add_filter('post_row_actions', 'reviewsservice_platform_duplicate_link', 10, 2);

// Add taxonomy column for Platforms CPT
function reviewsservice_platform_columns($columns)
{
  $columns['platform_category'] = __('Category', 'reviewsservice');
  return $columns;
}
add_filter('manage_platform_posts_columns', 'reviewsservice_platform_columns');

function reviewsservice_platform_column_content($column, $post_id)
{
  if ($column === 'platform_category') {
    $terms = get_the_terms($post_id, 'platform_category');
    if (!empty($terms) && !is_wp_error($terms)) {
      $out = [];
      foreach ($terms as $term) {
        $out[] = sprintf(
          '<a href="%s">%s</a>',
          esc_url(add_query_arg(['post_type' => 'platform', 'platform_category' => $term->slug], 'edit.php')),
          esc_html($term->name)
        );
      }
      echo join(', ', $out);
    } else {
      echo __('No Category', 'reviewsservice');
    }
  }
}
add_action('manage_platform_posts_custom_column', 'reviewsservice_platform_column_content', 10, 2);
// Handle duplication of Platforms CPT
function reviewsservice_duplicate_platform()
{
  if (!isset($_GET['post']) || !isset($_GET['action']) || $_GET['action'] !== 'duplicate_post') {
    return;
  }

  $post_id = intval($_GET['post']);
  $post = get_post($post_id);

  if ($post && $post->post_type === 'platform') {
    // Duplicate post
    $new_post = array(
      'post_title'    => $post->post_title . ' (Copy)',
      'post_content'  => $post->post_content,
      'post_status'   => 'draft',
      'post_type'     => $post->post_type,
    );

    $new_post_id = wp_insert_post($new_post);

    // Duplicate taxonomy terms
    $terms = wp_get_object_terms($post_id, 'platform_category', array('fields' => 'ids'));
    if ($terms) {
      wp_set_object_terms($new_post_id, $terms, 'platform_category');
    }

    // Duplicate featured image
    $thumb_id = get_post_thumbnail_id($post_id);
    if ($thumb_id) {
      set_post_thumbnail($new_post_id, $thumb_id);
    }

    // Redirect to edit screen of new post
    wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
    exit;
  }
}
add_action('admin_init', 'reviewsservice_duplicate_platform');
