<?php
/**
 * Index page router
 *
 * @see Theme\Pages\Index
 *
 * @package jusdocs
 */

use Theme\Pages;

echo new Pages\AuthenticatedPage(
    array(
		'page' => new Pages\Index(),
    )
);
