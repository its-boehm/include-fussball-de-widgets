<?php
/**
 * Plugin Name:    Include Fussball.de Widgets
 * Description:    Easy integration of the Fussball.de widgets (currently in the version since season 2016). Use it like: [fubade id="{DIV-ID}" api="{32-digit API}" notice="description"]
 * Version:        2.0.0
 * Author:         IT-Service Böhm -- Alexander Böhm
 * Author URI:     http://profiles.wordpress.org/mheob
 * License:        GPL-2.0-or-later
 * License URI:    https://www.gnu.org/licenses/gpl.html
 *
 * @package Include_Fussball_De_Widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/class-ifdw-shortcode.php';
new Ifdw_Shortcode();

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init-blocks.php';
