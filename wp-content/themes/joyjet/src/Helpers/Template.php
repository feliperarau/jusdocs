<?php
/**
 * Template utils class
 *
 * @package joyjet
 */

namespace Theme\Helpers;

/**
 * Templated related helper class
 */
class Template {

    /**
     * Print HTML inline style tag with appropriate markup for background images
     *
     * @param array $bg_url Background image URL to be inlined
     *
     * @return string The HTML markup for the BG image
     */
    public static function inline_bg_image( $bg_url ) : string {
        $final_inline_style = '';

        if ( ! empty( $bg_url ) ) {
            $inline_style = "background: url('$bg_url') no-repeat; background-size: cover; background-position: center";

            $final_inline_style = "style=\"$inline_style\"";
        }

        return $final_inline_style;
    }
}