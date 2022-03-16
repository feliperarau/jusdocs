<?php
/**
 * Search page router
 *
 * @see Theme\Pages\Search
 *
 * @package joyjet
 */

use Theme\Pages;

echo new Pages\AuthenticatedPage(
    array(
		'page' => new Pages\Search(),
    )
);