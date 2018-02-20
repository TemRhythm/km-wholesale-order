<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Km_Wholesale_Order
 * @subpackage Km_Wholesale_Order/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Km_Wholesale_Order
 * @subpackage Km_Wholesale_Order/includes
 * @author     Your Name <email@example.com>
 */
class Km_Wholesale_Order_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        add_option('km_ws_euro_curse', 68);
        add_option('km_ws_delivery_price', 6);
        add_option('km_ws_docs_prepare_price', 12);
	}

}
