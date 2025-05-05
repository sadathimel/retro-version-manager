<?php
/**
 * Custom upgrader skin for Retro Version Manager plugin.
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
 * Custom upgrader skin to capture detailed errors.
 */
class Retrvema_Upgrader_Skin extends \WP_Upgrader_Skin {
    public $errors = [];

    public function error( $errors ) {
        if ( is_string( $errors ) ) {
            $this->errors[] = $errors;
        } elseif ( is_wp_error( $errors ) ) {
            foreach ( $errors->get_error_messages() as $message ) {
                $this->errors[] = $message;
            }
        } else {
            // Safely serialize unknown error types for production
            $error_output = wp_json_encode( $errors );
            $this->errors[] = 'Unknown error: ' . ( $error_output !== false ? $error_output : 'Unable to serialize error data' );
        }
    }

    public function feedback( $string, ...$args ) {
        // Suppress feedback to keep logs clean
    }
}
?>