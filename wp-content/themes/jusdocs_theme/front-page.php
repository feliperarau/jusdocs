<?php
/**
 * FrontPage
 *
 * @see Theme\Pages\FrontPage
 *
 * @package jusdocs
 */

use Theme\Components;
use Theme\Pages;

echo new Components\Header\Header();

echo new Pages\FrontPage();

echo new Components\Footer\Footer();