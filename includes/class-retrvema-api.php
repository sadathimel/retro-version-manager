<?php
/**
 * API handler for fetching plugin versions.
 *
 * @package Retro_Version_Manager
 */

namespace Retrvema;

if (!defined('ABSPATH')) {
    exit;
}

class Retrvema_API {
    public function register() {
        // No hooks needed for API class yet.
    }

    public function get_plugin_versions($plugin_slug) {
        if (empty($plugin_slug) || !preg_match('/^[a-z0-9-]+$/i', $plugin_slug)) {
            return new \WP_Error('invalid_slug', __('Invalid plugin slug.', 'retro-version-manager'));
        }
        $url = 'https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]=' . esc_attr($plugin_slug);
        $response = wp_remote_get($url, ['timeout' => 15]);

        if (is_wp_error($response)) {
            return new \WP_Error('api_error', __('Failed to connect to WordPress.org API.', 'retro-version-manager'));
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);

        if (empty($data) || !isset($data->versions)) {
            return new \WP_Error('no_versions', __('No versions found for this plugin.', 'retro-version-manager'));
        }

        // Convert stdClass to array if necessary
        $versions_data = is_object($data->versions) ? (array) $data->versions : $data->versions;

        $versions = array_keys(array_filter($versions_data, function($key) {
            return $key !== 'trunk';
        }, ARRAY_FILTER_USE_KEY));
        rsort($versions);

        return [
            'versions' => $versions,
            'data' => $data,
        ];
    }
}