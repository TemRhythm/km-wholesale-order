<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Km_Wholesale_Order
 * @subpackage Km_Wholesale_Order/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Km_Wholesale_Order
 * @subpackage Km_Wholesale_Order/public
 * @author     Your Name <email@example.com>
 */
class Km_Wholesale_Order_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $km_wholesale_order    The ID of this plugin.
	 */
	private $km_wholesale_order;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $km_wholesale_order       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $km_wholesale_order, $version ) {

		$this->km_wholesale_order = $km_wholesale_order;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Km_Wholesale_Order_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Km_Wholesale_Order_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->km_wholesale_order, plugin_dir_url( __FILE__ ) . 'css/km-wholesale-order-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Km_Wholesale_Order_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Km_Wholesale_Order_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->km_wholesale_order, plugin_dir_url( __FILE__ ) . 'js/km-wholesale-order-public.js', array( 'jquery' ), $this->version, false );

	}

}
