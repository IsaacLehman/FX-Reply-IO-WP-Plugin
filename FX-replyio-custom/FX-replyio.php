<?php
/**
 * Plugin Name: FX Reply IO
 * Description: Custom API intigration with Reply.IO
 * Version: 1.0
 * Author: WebFX
 * Author URI: https://www.webfx.com/
 * Text Domain: fxrio
 * License: GPL-2.0+
 * Github Plugin URI:
 */

// if this file is called directly, abort
if ( !defined('ABSPATH') ) {
    die;
}

//================================================================
// LOAD STYLE SHEET
//================================================================
function FXIO_stylesheet() {
	if ( apply_filters( 'FXIO_load_styles', true ) ) {
		wp_enqueue_style( 'FXIO_stylsheet', plugin_dir_url(__FILE__) . 'FX-replyio.css' );
	}
}
add_action( 'admin_print_styles', 'FXIO_stylesheet' );

//================================================================
// ADD API FILE
//================================================================
require_once 'partials/FX-replyio-api.php';

//================================================================
// ADD SETTINGS FILE
//================================================================
require_once 'partials/FX-replyio-settings.php';
