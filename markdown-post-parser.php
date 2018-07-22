<?php
/**
 * Plugin Name: Markdown Post Parser
 * Plugin URI:
 * Description: Markdown Post Parser plugin for WordPress
 * Version: 1.0
 * Author: Daren Wesolowski
 * Author URI:
 * License:     GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * Based on WP-Parsedown
 * Copyright (c) Peter Molnar
 * https://github.com/petermolnar/wp-parsedown
 * All rights reserved.
 *
 * Copyright (C) 2018  Daren Wesolowski
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // exit if accessed directly!
}

define( 'PLUGINVERSION', '1.0.0' );

/**
 * - Parsedown 1.7.1
 * - ParsedownExtra 0.7.1
 */
include_once( plugin_dir_path( __FILE__ ) . '/lib/parsedown/Parsedown.php' );
include_once( plugin_dir_path( __FILE__ ) . '/lib/parsedown-extra/ParsedownExtra.php' );

class MarkdownPostParser {

    public function __construct() {

        // Post filters
        remove_filter( 'the_content', 'wpautop' );
        remove_filter( 'the_excerpt', 'wpautop' );
        add_filter( 'the_content', array( $this, 'parseDown'), 8 );

        // Comment filters
        remove_filter( 'comment_text', 'wpautop' );
        add_filter( 'comment_text', array( $this, 'parseDown'), 8 );
    }

    public function errorLogger() {

        $message = sprintf ( __( 'Parsing post: %s' ), $post->ID );
        error_log(  __CLASS__ . ": " . $message );
    }

    public function parseDown( $markdown ) {
        $post = get_post();

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG == true ) {
            $this->errorLogger();
        }

        $parsedown = new ParsedownExtra();
        $parsedown->setBreaksEnabled( true );
        return $parsedown->text( $markdown );
    }
}

$markdown_post_parser = new MarkdownPostParser();
