<?php
/**
 * Page route
 *
 * @package joyjet
 */

use Theme\Pages;

echo new Pages\AuthenticatedPage(
    array(
		'page' => new Pages\Page(),
    )
);