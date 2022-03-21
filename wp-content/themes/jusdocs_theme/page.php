<?php
/**
 * Page route
 *
 * @package jusdocs
 */

use Theme\Pages;

echo new Pages\AuthenticatedPage(
    array(
		'page' => new Pages\Page(),
    )
);