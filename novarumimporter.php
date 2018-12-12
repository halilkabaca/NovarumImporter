<?php

/**
 * The plugin bootstrap file
 *
 * This file is Novarum Importer plugin bootstrap file. It also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.novarumsoftware.com
 * @since             1.0.0
 * @package           Novarumimporter
 *
 * @wordpress-plugin
 * Plugin Name:       Novarum JSON Importer
 * Plugin URI:        https://github.com/halilkabaca/NovarumImporter
 * Description:       This free plugin helps you call any endpoint and import JSON data to wordpress
 * Version:           1.0.1
 * Author:            Halil Kabaca
 * Author URI:        www.novarumsoftware.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       novarumimporter
 * Domain Path:       /languages
 */

 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'NOVARUM_IMPORTER_VERSION', '1.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-novarumimporter-activator.php
 */
function activate_novarumimporter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-novarumimporter-activator.php';
	Novarumimporter_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-novarumimporter-deactivator.php
 */
function deactivate_novarumimporter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-novarumimporter-deactivator.php';
	Novarumimporter_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_novarumimporter' );
register_deactivation_hook( __FILE__, 'deactivate_novarumimporter' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-novarumimporter.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_novarumimporter() {

	$plugin = new Novarumimporter();
	$plugin->run();

}
run_novarumimporter();
