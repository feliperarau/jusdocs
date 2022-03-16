<?php
/**
 * Index page router
 *
 * @see Theme\Pages\Index
 *
 * @package joyjet
 */

use Theme\Pages;

echo new Pages\AuthenticatedPage(
    array(
		'page' => new Pages\Index(),
    )
);
