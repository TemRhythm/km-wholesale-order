<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Km_Wholesale_Order
 * @subpackage Km_Wholesale_Order/admin
 */
include_once 'soap/class-km-wholesale-order-service.php';
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Km_Wholesale_Order
 * @subpackage Km_Wholesale_Order/admin
 * @author     Your Name <email@example.com>
 */
class Km_Wholesale_Order_Admin {

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

	private $soap_service;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $km_wholesale_order       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $km_wholesale_order, $version ) {

		$this->km_wholesale_order = $km_wholesale_order;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

        global $pagenow, $typenow;
        if (empty($typenow) && !empty($_GET['page'])) {
            $typenow = $_GET['page'];
        }
        if (is_admin() && $typenow=='wholesale-order') {
            if ($pagenow=='tools.php') {
                wp_enqueue_style( $this->km_wholesale_order, plugin_dir_url( __FILE__ ) . 'css/km-wholesale-order-admin.css', array(), $this->version, 'all' );
                wp_enqueue_style( 'km_wholesale_vendor_css', plugin_dir_url( __FILE__ ) . 'assets/vendor.css', array(), $this->version, 'all' );

            }
        }
	}

	/**
	 * Register the JavaScript for the admin area.
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

        global $pagenow, $typenow;
        if (empty($typenow) && !empty($_GET['page'])) {
            $typenow = $_GET['page'];
        }
        if (is_admin() && $typenow=='wholesale-order') {
            if ($pagenow=='tools.php') {
                wp_enqueue_script( 'km_wholesale_vendor_js', plugin_dir_url( __FILE__ ) . 'assets/vendor.js', array( 'jquery' ), $this->version, false );
                wp_enqueue_script( $this->km_wholesale_order, plugin_dir_url( __FILE__ ) . 'js/km-wholesale-order-admin.js', array( 'jquery' ), $this->version, false );
            }
        }

    }

	public function km_wholesale_order_menu() {
        add_submenu_page(
			'tools.php',
			'Оптовый заказ',
			'Оптовый заказ',
			'manage_options',
            'wholesale-order',
			array($this, 'km_wholesale_order_page') );
	}

    public function km_wholesale_order_page(){
        $this->soap_service = new Class_Km_Wholesale_Order_Service();
	    if(isset($_GET['create'])){
            $euro_curse = get_option('km_ws_euro_curse');
            $delivery_price = get_option('km_ws_delivery_price');
            $docs_prepare_price = get_option('km_ws_docs_prepare_price');
            $wc_products = [];
	        if($_GET['create'] !== '') {
                $order_products = $this->soap_service->getOrder($_GET['create'])->WProduct;
                foreach ($order_products as $product) {
                    $wc_product = wc_get_products(['sku' => $product->Sku])[0];
                    if ($wc_product)
                        $wc_products[] = [
                            'quantity' => $product->Quantity,
                            'product' => $wc_product
                        ];
                }
            }
            $products = wc_get_products([]);
            include_once 'partials/km-wholesale-order-create-display.php';
        }
        else {
            $orders = $this->soap_service->getOrders();
            include_once 'partials/km-wholesale-order-admin-display.php';
        }
    }

    public function km_wholesale_order_save_to_one_s(){
        $this->soap_service = new Class_Km_Wholesale_Order_Service();
        $data = $_POST['data'];

        update_option('km_ws_euro_curse', $_POST['euro_curse']);
        update_option('km_ws_delivery_price', $_POST['delivery_price']);
        update_option('km_ws_docs_prepare_price', $_POST['docs_prepare_price']);

        $products = [];
        foreach ($data as $data_item){
            $soap_product = new Soap_Product();
            $soap_product->Sku = $data_item['sku'];
            $soap_product->Price = $data_item['price'];
            $soap_product->Quantity = $data_item['quantity'];
            $products[] = $soap_product;
        }
        $order = new Soap_Order();
        $order->WProduct = $products;
        try {
            $saved_order = $this->soap_service->saveOrder($order);
            if ($saved_order->Id)
                echo '1';
            else
                echo '0';
        }
        catch (Exception $e){
            print_r($e);
        }
        wp_die();
    }

    public function km_wholesale_order_search_products(){
        add_filter('woocommerce_product_search_fields', ['_custom_product_model']);

        $this->soap_service = new Class_Km_Wholesale_Order_Service();
        $term = $_GET['search_term'];
        $unlisted_products_sku = $_GET['unlisted_products_sku'];
        $ids = $this->search_products( $term, '', false );
        $products = [];
        if(count($ids) !== 0) {
            $product_objects = wc_get_products(apply_filters('electro_wc_live_search_query_args', array('status' => array('publish'), 'orderby' => 'date', 'order' => 'DESC', 'limit' => 10, 'include' => $ids)));
            foreach ($product_objects as $product_object) {
                if (in_array($product_object->get_sku(), $unlisted_products_sku))
                    continue;
                $products[] = [
                    'name' => $product_object->get_name(),
                    'sku' => $product_object->get_sku(),
                    'model' => get_post_meta($product_object->get_id(), '_custom_product_model', true),
                    'price' => $product_object->get_price(),
                    'weight' => $product_object->get_weight()
                ];
            }
        }
        header('Content-Type: application/json');
        echo json_encode($products);
        wp_die();
    }

    private function search_products( $term, $type = '', $include_variations = false ) {
        global $wpdb;

        $search_fields = array_map( 'wc_clean', apply_filters( 'woocommerce_product_search_fields', array(
            '_sku',
        ) ) );
        $like_term     = '%' . $wpdb->esc_like( $term ) . '%';
        $post_types    = $include_variations ? array( 'product', 'product_variation' ) : array( 'product' );
        $post_statuses = current_user_can( 'edit_private_products' ) ? array( 'private', 'publish' ) : array( 'publish' );
        $type_join     = '';
        $type_where    = '';

        if ( $type ) {
            if ( in_array( $type, array( 'virtual', 'downloadable' ) ) ) {
                $type_join  = " LEFT JOIN {$wpdb->postmeta} postmeta_type ON posts.ID = postmeta_type.post_id ";
                $type_where = " AND ( postmeta_type.meta_key = '_{$type}' AND postmeta_type.meta_value = 'yes' ) ";
            }
        }

        $product_ids = $wpdb->get_col(
            $wpdb->prepare( "
				SELECT DISTINCT posts.ID FROM {$wpdb->posts} posts
				LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
				$type_join
				WHERE (
					(
						postmeta.meta_key = '_sku' AND postmeta.meta_value LIKE %s
					)
					OR (
					    postmeta.meta_key = '_custom_product_model' AND postmeta.meta_value LIKE %s
					)
				)
				AND posts.post_type IN ('" . implode( "','", $post_types ) . "')
				AND posts.post_status IN ('" . implode( "','", $post_statuses ) . "')
				$type_where
				ORDER BY posts.post_parent ASC, posts.post_title ASC
				",
                $like_term,
                $like_term,
                $like_term
            )
        );

        if ( is_numeric( $term ) ) {
            $post_id   = absint( $term );
            $post_type = get_post_type( $post_id );

            if ( 'product_variation' === $post_type && $include_variations ) {
                $product_ids[] = $post_id;
            } elseif ( 'product' === $post_type ) {
                $product_ids[] = $post_id;
            }

            $product_ids[] = wp_get_post_parent_id( $post_id );
        }

        return wp_parse_id_list( $product_ids );
    }

}
