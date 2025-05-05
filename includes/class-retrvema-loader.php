<?php
/**
 * Loader for Retro Version Manager plugin.
 *
 * @package Retro_Version_Manager
 * @author sadathimel <sadathossen.cse@gmail.com>
 * @since 1.0.0
 */

namespace Retrvema;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Retrvema_Loader class.
 */
class Retrvema_Loader {

    /**
     * Instance of Retrvema_Admin.
     *
     * @var Retrvema_Admin
     */
    protected $admin;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->load_dependencies();
    }

    /**
     * Load required files.
     */
    private function load_dependencies() {
        require_once plugin_dir_path( __FILE__ ) . 'class-retrvema-admin.php';
        // Load API class if it exists
        if ( file_exists( plugin_dir_path( __FILE__ ) . 'class-retrvema-api.php' ) ) {
            require_once plugin_dir_path( __FILE__ ) . 'class-retrvema-api.php';
        }
    }

    /**
     * Initialize the plugin.
     */
    public function init() {
        $this->admin = new Retrvema_Admin();
        // Initialize other classes if needed
        // Example: $this->api = new Retrvema_Api();
    }
}
?>