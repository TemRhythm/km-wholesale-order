<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           KM Wholesale Order
 *
 * @wordpress-plugin
 * Plugin Name:       Оптовый заказ
 * Version:           1.0.0
 * Author:            Kodolex LLC
 * Author URI:        http://kodolex.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       km-wholesale-order
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_VERSION', '1.1.3' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-km-wholesale-order-activator.php
 */
function activate_km_wholesale_order() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-km-wholesale-order-activator.php';
	Km_Wholesale_Order_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-km-wholesale-order-deactivator.php
 */
function deactivate_km_wholesale_order() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-km-wholesale-order-deactivator.php';
	Km_Wholesale_Order_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_km_wholesale_order' );
register_deactivation_hook( __FILE__, 'deactivate_km_wholesale_order' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-km-wholesale-order.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_km_wholesale_order() {

	$plugin = new Km_Wholesale_Order();
	$plugin->run();

}
run_km_wholesale_order();
