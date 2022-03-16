<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package joyjet
 */

?>

<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <meta name="theme-color" content="#ffffff">
    <!-- WP AJAX ENDPOINT -->
    <script>
    // prettier-ignore
    window.wpAdminAjax = '<?php echo admin_url(); ?>';
    </script>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>