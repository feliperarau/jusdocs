<?php
/**
 * Template name: Test
 *
 * Test template page router
 *
 * @package jusdocs
 */

use Theme\Pages;

get_header();

echo new Pages\Test();

get_footer();