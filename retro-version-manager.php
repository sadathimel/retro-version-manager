<?php
/**
 * Plugin Name: Retro Version Manager
 * Plugin URI: https://github.com/sadathimel
 * Description: Manage and install previous versions of WordPress plugins, starting with Contact Form 7.
 * Version: 1.0.2
 * Author: sadathimel
 * Author URI: https://github.com/sadathimel
 * Author Email: sadathossen.cse@gmail.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: retro-version-manager
 * Domain Path: /languages
 *
 * @package Retro_Version_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define plugin constants
define( 'RETRVEMA_PLUGIN_FILE', __FILE__ );
define( 'RETRVEMA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'RETRVEMA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'RETRVEMA_VERSION', '1.0.2' );

// Load the plugin loader
require_once RETRVEMA_PLUGIN_DIR . 'includes/class-retrvema-loader.php';

use Retrvema\Retrvema_Loader;

// Instantiate and initialize the loader
$loader = new Retrvema_Loader();
$loader->init();
?>