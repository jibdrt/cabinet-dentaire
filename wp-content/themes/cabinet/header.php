<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset='<?php bloginfo('charset'); ?>'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='profile' href='https://gmpg.org/xfn/11'>
    <?php if (function_exists('wp_get_document_title')): ?>
        <title><?php echo wp_get_document_title(); ?></title>
    <?php else: ?>
        <title><?php bloginfo('name'); ?><?php wp_title('|', true, 'left'); ?></title>
    <?php endif; ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open();
$logo_id = get_theme_mod('custom_logo');
$logo_url = wp_get_attachment_image_url($logo_id, 'full');
?>
<div id='page'>

<header class="cdcc__header
<?php if (is_page('le-cabinet')) echo ' cabinet'; ?>" role="banner">
  <div class="cdcc__header___inner">
    <!-- Logo (left) -->
    <a href="<?php echo esc_url(home_url('/')); ?>" class="cdcc__header___logo" aria-label="<?php bloginfo('name'); ?>">
      <?php
      if (is_page('le-cabinet-temp')) {
      // Use custom logo for "le-cabinet" page
      $custom_logo_path = get_template_directory_uri() . '/assets/images/logo_horizontal_1_blanc.png';
      ?>
      <img src="<?php echo esc_url($custom_logo_path); ?>" alt="<?php bloginfo('name'); ?>">
      <?php
      } elseif ($logo_url) {
      ?>
      <img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>">
      <?php } else { ?>
      <span class="cdcc__header___logoText"><?php bloginfo('name'); ?></span>
      <?php } ?>
    </a>

    <!-- Desktop nav (right) -->
    <nav class="cdcc__header___nav" role="navigation" aria-label="<?php esc_attr_e('Main', 'your-textdomain'); ?>">
      <?php
      wp_nav_menu([
        'theme_location' => 'primary',
        'menu'           => 'Menu principal',
        'container'      => false,
        'menu_class'     => 'cdcc__header___menu',
        'fallback_cb'    => false,
        'depth'          => 2,
        'link_before'    => '',
        'link_after'     => '',
      ]);
      ?>
    </nav>

    <!-- Burger (mobile) -->
    <button
      class="cdcc__header___burger"
      aria-label="<?php esc_attr_e('Open menu', 'your-textdomain'); ?>"
      aria-controls="mobileMenu"
      aria-expanded="false"
      type="button">
      <span class="iconify" data-icon="mdi:menu" aria-hidden="true"></span>
    </button>
  </div>

  <!-- Mobile drawer -->
  <nav id="mobileMenu" class="cdcc__header___mobile" role="navigation" aria-label="<?php esc_attr_e('Mobile', 'your-textdomain'); ?>" aria-hidden="true">
    <div class="cdcc__header___mobileHeader">
      <button
        class="cdcc__header___close"
        aria-label="<?php esc_attr_e('Close menu', 'your-textdomain'); ?>"
        aria-controls="mobileMenu"
        aria-expanded="true"
        type="button">
        <span class="iconify" data-icon="mdi:close" aria-hidden="true"></span>
      </button>
    </div>

    <?php
    // Same menu for mobile, different class
    wp_nav_menu([
      'theme_location' => 'primary',
      'menu'           => 'Menu principal',
      'container'      => false,
      'menu_class'     => 'cdcc__header___mobileMenu',
      'fallback_cb'    => false,
      'depth'          => 1,
    ]);
    ?>
  </nav>
</header>
