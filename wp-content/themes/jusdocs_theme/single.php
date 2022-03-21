<?php
/**
 * Single route
 *
 * @package jusdocs
 */

use Theme\Pages;
use Theme\Components;

echo new Components\Header\Header();

echo new Pages\Single();

echo new Components\Footer\Footer();