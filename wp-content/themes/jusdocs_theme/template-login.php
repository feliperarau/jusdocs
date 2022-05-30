<?php
/**
 * Template name: Login
 *
 * Login page router
 *
 * @package jusdocs
 */

$login_page = new Theme\Pages\Login();

get_header();

echo $login_page;

get_footer();