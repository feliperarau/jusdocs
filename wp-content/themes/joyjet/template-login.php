<?php
/**
 * Template name: Login
 *
 * Login page router
 *
 * @package joyjet
 */

$login_page = new Theme\Pages\Login();

get_header();

echo $login_page;

get_footer();