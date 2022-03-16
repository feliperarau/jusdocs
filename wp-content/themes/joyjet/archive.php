<?php
/**
 * Archive page router
 *
 * @see Theme\Pages\Archive
 *
 * @package joyjet
 */

use Theme\Pages;

echo new Pages\AuthenticatedPage(
    array(
		'page' => new Pages\PageNotFound(),
    )
);