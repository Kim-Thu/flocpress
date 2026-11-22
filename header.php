<?php // header.php
declare(strict_types=1);
defined('ABSPATH') || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="l-header">
    <div class="l-container l-header__inner">
        <a class="c-brand" href="<?php echo esc_url(home_url('/')); ?>">
            <?php bloginfo('name'); ?>
        </a>
        <?php wp_nav_menu(['theme_location' => 'primary', 'container' => 'nav', 'menu_class' => 'c-nav']); ?>
    </div>
</header>

<main id="main" class="l-main">

