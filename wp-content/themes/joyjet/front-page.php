<?php
/**
 * FrontPage
 *
 * @see Theme\Pages\FrontPage
 *
 * @package joyjet
 */

use Theme\Components;
use Theme\Pages;

echo new Components\Header\Header();

echo new Pages\FrontPage();

echo new Components\Footer\Footer();