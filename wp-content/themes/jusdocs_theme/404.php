<?php
/**
 * 404 page router
 *
 * @see Theme\Pages\PageNotFound
 *
 * @package jusdocs
 */

get_header();

echo new Theme\Pages\PageNotFound();

get_footer();