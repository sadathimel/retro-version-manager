<?php
/**
 * Admin functionality for Retro Version Manager plugin.
 *
 * @package Retro_Version_Manager
 * @author sadathimel <sadathossen.cse@gmail.com>
 * @since 1.0.0
 */

namespace Retrvema;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! defined( 'RETRVEMA_PLUGIN_DIR' ) ) {
    exit; // Exit if plugin constants not defined.
}

/**
 * Retrvema_Admin class.
 */
class Retrvema_Admin {

    /**
     * Constructor.
     */
    public function __construct() {
        add_action( 'admin_notices', array( $this, 'show_notices' ) );
        add_action( 'admin_init', array( $this, 'handle_install' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_filter( 'retrvema_install_version', array( $this, 'install_plugin_version' ), 10, 3 );
    }

    /**
     * Register admin menu.
     */
    public function admin_menu() {
        add_submenu_page(
            'tools.php',
            esc_html__( 'Retro Version Manager', 'retro-version-manager' ),
            esc_html__( 'Retro Version Manager', 'retro-version-manager' ),
            'install_plugins',
            'retrvema-retro-version-manager',
            array( $this, 'render_admin_page' )
        );
    }

    /**
     * Enqueue scripts and styles.
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'retrvema-admin',
            RETRVEMA_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            RETRVEMA_VERSION
        );
        wp_enqueue_script(
            'retrvema-admin',
            RETRVEMA_PLUGIN_URL . 'assets/js/admin.js',
            array( 'jquery' ),
            RETRVEMA_VERSION,
            true
        );
    }

    /**
     * Display admin notices for installation results.
     */
    public function show_notices() {
        if ( ! isset( $_GET['page'], $_GET['installed'], $_GET['slug'], $_GET['version'], $_GET['retrvema_nonce'] ) || 
             $_GET['page'] !== 'retrvema-retro-version-manager' || 
             ! current_user_can( 'install_plugins' ) ) {
            return;
        }

        $nonce = sanitize_text_field( wp_unslash( $_GET['retrvema_nonce'] ) );
        if ( ! wp_verify_nonce( $nonce, 'retrvema_notice_' . $_GET['slug'] . '_' . $_GET['version'] ) ) {
            return;
        }

        $slug = sanitize_text_field( wp_unslash( $_GET['slug'] ) );
        $version = sanitize_text_field( wp_unslash( $_GET['version'] ) );

        if ( ! preg_match( '/^[a-z0-9-]+$/i', $slug ) || ! preg_match( '/^[0-9.]+$/i', $version ) ) {
            return;
        }

        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <?php
                printf(
                    esc_html__( 'Version %1$s of %2$s installed successfully! Activate it from the Plugins page.', 'retro-version-manager' ),
                    esc_html( $version ),
                    esc_html( $slug )
                );
                ?>
            </p>
        </div>
        <?php
    }

    /**
     * Handle plugin version installation.
     */
    public function handle_install() {
        if ( ! isset( $_GET['action'], $_GET['slug'], $_GET['version'], $_GET['_wpnonce'] ) || 
             'install' !== $_GET['action'] || 
             ! current_user_can( 'install_plugins' ) ) {
            return;
        }

        $nonce = sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) );
        $slug = sanitize_text_field( wp_unslash( $_GET['slug'] ) );
        $version = sanitize_text_field( wp_unslash( $_GET['version'] ) );

        if ( ! wp_verify_nonce( $nonce, 'retrvema_install_' . $slug . '_' . $version ) ) {
            wp_safe_redirect(
                admin_url(
                    'tools.php?page=retrvema-retro-version-manager&install_error=' . 
                    urlencode( __( 'Invalid nonce. Please try again.', 'retro-version-manager' ) )
                )
            );
            exit;
        }

        if ( ! preg_match( '/^[a-z0-9-]+$/i', $slug ) || ! preg_match( '/^[0-9.]+$/i', $version ) ) {
            wp_safe_redirect(
                admin_url(
                    'tools.php?page=retrvema-retro-version-manager&install_error=' . 
                    urlencode( __( 'Invalid version format.', 'retro-version-manager' ) )
                )
            );
            exit;
        }

        // Initialize filesystem
        require_once ABSPATH . 'wp-admin/includes/file.php';
        $creds = request_filesystem_credentials( admin_url(), '', false, false, null );
        if ( ! WP_Filesystem( $creds ) ) {
            wp_safe_redirect(
                admin_url(
                    'tools.php?page=retrvema-retro-version-manager&install_error=' . 
                    urlencode( __( 'Failed to initialize filesystem. Check server permissions or provide FTP credentials.', 'retro-version-manager' ) )
                )
            );
            exit;
        }

        $install_result = apply_filters( 'retrvema_install_version', false, $slug, $version );

        if ( $install_result === true ) {
            wp_safe_redirect(
                admin_url(
                    'tools.php?page=retrvema-retro-version-manager&installed=1&slug=' . $slug . 
                    '&version=' . $version . '&retrvema_nonce=' . 
                    wp_create_nonce( 'retrvema_notice_' . $slug . '_' . $version )
                )
            );
            exit;
        } else {
            $error_message = is_wp_error( $install_result ) ? 
                             $install_result->get_error_message() : 
                             __( 'Failed to install the plugin version.', 'retro-version-manager' );
            wp_safe_redirect(
                admin_url(
                    'tools.php?page=retrvema-retro-version-manager&install_error=' . 
                    urlencode( $error_message ) . '&retrvema_nonce=' . 
                    wp_create_nonce( 'retrvema_error_notice' )
                )
            );
            exit;
        }
    }

    /**
     * Install a specific plugin version.
     */
    public function install_plugin_version( $result, $slug, $version ) {
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
        require_once RETRVEMA_PLUGIN_DIR . 'includes/class-retrvema-upgrader-skin.php';

        // Check if Retrvema_Upgrader_Skin class exists
        if ( ! class_exists( 'Retrvema\Retrvema_Upgrader_Skin' ) ) {
            return new \WP_Error( 
                'upgrader_skin_missing', 
                __( 'Custom upgrader skin class not found. Please check plugin files.', 'retro-version-manager' ) 
            );
        }

        global $wp_filesystem;

        // Initialize filesystem
        require_once ABSPATH . 'wp-admin/includes/file.php';
        if ( ! WP_Filesystem() ) {
            return new \WP_Error( 
                'filesystem_error', 
                __( 'Failed to initialize filesystem. Check server permissions.', 'retro-version-manager' ) 
            );
        }

        // Check plugins directory writability
        $plugins_dir = WP_PLUGIN_DIR;
        if ( ! $wp_filesystem->is_writable( $plugins_dir ) ) {
            return new \WP_Error( 
                'plugins_dir_error', 
                sprintf( 
                    __( 'The plugins directory (%s) is not writable. Please grant write permissions.', 'retro-version-manager' ), 
                    $plugins_dir 
                ) 
            );
        }

        // Check temp directory writability
        $temp_dir = sys_get_temp_dir();
        if ( ! $wp_filesystem->is_writable( $temp_dir ) ) {
            return new \WP_Error( 
                'temp_dir_error', 
                sprintf( 
                    __( 'The temporary directory (%s) is not writable. Check PHP temp_dir settings.', 'retro-version-manager' ), 
                    $temp_dir 
                ) 
            );
        }

        $download_url = sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $slug, $version );

        // Verify ZIP download URL
        $response = wp_remote_head( $download_url, array( 'timeout' => 60, 'sslverify' => false ) );
        if ( is_wp_error( $response ) ) {
            return new \WP_Error( 
                'download_verify_error', 
                __( 'Failed to verify plugin ZIP: ', 'retro-version-manager' ) . $response->get_error_message() 
            );
        }
        if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
            return new \WP_Error( 
                'download_verify_error', 
                __( 'Plugin ZIP not found (HTTP ', 'retro-version-manager' ) . 
                wp_remote_retrieve_response_code( $response ) . ').' 
            );
        }

        $upgrader = new \Plugin_Upgrader( new \Retrvema\Retrvema_Upgrader_Skin() );
        $plugin_path = $slug . '/' . $slug . '.php';

        // Check for existing plugin
        $plugins = get_plugins();
        if ( isset( $plugins[ $plugin_path ] ) ) {
            return new \WP_Error( 
                'plugin_exists', 
                sprintf( 
                    __( 'The plugin %s is already installed. Please deactivate and delete it manually from the Plugins page before installing version %s.', 'retro-version-manager' ), 
                    $slug, 
                    $version 
                ) 
            );
        }

        // Install the plugin
        $install = $upgrader->install( $download_url );

        if ( is_wp_error( $install ) ) {
            return $install;
        }
        if ( ! $install ) {
            $error_message = ! empty( $upgrader->skin->errors ) ? 
                             implode( '; ', $upgrader->skin->errors ) : 
                             __( 'Unknown installation failure.', 'retro-version-manager' );
            return new \WP_Error( 
                'install_error', 
                __( 'Failed to install plugin: ', 'retro-version-manager' ) . $error_message 
            );
        }

        return true;
    }

    /**
     * Render the admin page.
     */
    public function render_admin_page() {
        if ( ! current_user_can( 'install_plugins' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'retro-version-manager' ) );
        }

        $slug = '';
        $error_message = '';
        $install_error = '';
        $versions = array();
        $current_version = '';
        $is_installed = false;

        // Check for install error
        if ( isset( $_GET['install_error'], $_GET['retrvema_nonce'] ) ) {
            $nonce = sanitize_text_field( wp_unslash( $_GET['retrvema_nonce'] ) );
            if ( wp_verify_nonce( $nonce, 'retrvema_error_notice' ) ) {
                $install_error = sanitize_text_field( wp_unslash( $_GET['install_error'] ) );
            }
        }

        // Initialize API
        $api = new Retrvema_API();

        if ( isset( $_POST['plugin_slug'], $_POST['retrvema_nonce'] ) ) {
            if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['retrvema_nonce'] ) ), 'retrvema_search' ) ) {
                $error_message = esc_html__( 'Nonce verification failed. Please try again.', 'retro-version-manager' );
            } elseif ( ! current_user_can( 'install_plugins' ) ) {
                $error_message = esc_html__( 'You do not have permission to perform this action.', 'retro-version-manager' );
            } else {
                $slug = sanitize_text_field( wp_unslash( $_POST['plugin_slug'] ) );
                if ( ! preg_match( '/^[a-z0-9-]+$/i', $slug ) ) {
                    $error_message = esc_html__( 'Invalid plugin slug format. Use letters, numbers, and hyphens only.', 'retro-version-manager' );
                    $slug = '';
                } else {
                    $result = $api->get_plugin_versions( $slug );
                    if ( ! is_wp_error( $result ) ) {
                        $versions = $result['versions'];
                    } else {
                        $error_message = esc_html( $result->get_error_message() );
                    }
                    if ( empty( $versions ) && empty( $error_message ) ) {
                        $error_message = sprintf(
                            esc_html__( 'No versions found for %s. Please check the slug and try again.', 'retro-version-manager' ),
                            esc_html( $slug )
                        );
                    }
                }
            }
        }

        // Check for installed plugin
        if ( $slug ) {
            $plugins = get_plugins();
            $plugin_path = $slug . '/' . $slug . '.php';
            if ( isset( $plugins[ $plugin_path ] ) ) {
                $is_installed = true;
                $current_version = $plugins[ $plugin_path ]['Version'];
            }
        }

        ?>
        <div class="wrap rvm-wrap">
            <h1><?php esc_html_e( 'Retro Version Manager - Downgrade WordPress Plugins', 'retro-version-manager' ); ?></h1>
            <div class="rvm-instructions">
                <p><?php esc_html_e( 'Retro Version Manager lets you browse, download, or install older versions of WordPress plugins from WordPress.org to fix compatibility issues or test specific versions. Follow these steps:', 'retro-version-manager' ); ?></p>
                <ol>
                    <li><?php esc_html_e( 'Enter a plugin slug (e.g., wordpress-seo) in the field below.', 'retro-version-manager' ); ?></li>
                    <li><?php esc_html_e( 'Click "Search Versions" to list all available plugin versions.', 'retro-version-manager' ); ?></li>
                    <li><?php esc_html_e( 'Click "Install Now" to install a version or "Download ZIP" to save it for later.', 'retro-version-manager' ); ?></li>
                    <li><?php esc_html_e( 'After installing, activate the plugin from the Plugins page if needed.', 'retro-version-manager' ); ?></li>
                </ol>
                <p class="rvm-warning"><strong><?php esc_html_e( 'Warning:', 'retro-version-manager' ); ?></strong> <?php esc_html_e( 'Always back up your site before downgrading plugins, as older versions may have compatibility or security issues.', 'retro-version-manager' ); ?></p>
                <p class="rvm-notice"><?php esc_html_e( 'Note: If a plugin is already installed, you must deactivate and delete it manually from the Plugins page before installing a different version.', 'retro-version-manager' ); ?></p>
            </div>

            <h3><?php esc_html_e( 'Plugin Slug (e.g., wordpress-seo):', 'retro-version-manager' ); ?></h3>
            <form method="post" class="rvm-form">
                <?php wp_nonce_field( 'retrvema_search', 'retrvema_nonce' ); ?>
                <label for="plugin-slug">
                    <input type="text" id="plugin-slug" name="plugin_slug" value="<?php echo esc_attr( $slug ); ?>" placeholder="<?php esc_attr_e( 'e.g., wordpress-seo', 'retro-version-manager' ); ?>">
                </label>
                <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Search Versions', 'retro-version-manager' ); ?>">
            </form>

            <?php if ( $error_message ) : ?>
                <p class="retrvema-error"><?php echo wp_kses_post( $error_message ); ?></p>
            <?php endif; ?>

            <?php if ( $install_error ) : ?>
                <p class="retrvema-error"><?php echo wp_kses_post( $install_error ); ?></p>
            <?php endif; ?>

            <?php if ( $slug && $is_installed ) : ?>
                <div class="rvm-installed-version">
                    <span class="rvm-installed-label"><?php esc_html_e( 'Currently Installed Version:', 'retro-version-manager' ); ?></span>
                    <?php echo esc_html( $current_version ); ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $versions ) ) : ?>
                <h2>
                    <?php
                    printf( esc_html__( 'Available Versions for %s', 'retro-version-manager' ), esc_html( $slug ) );
                    ?>
                </h2>
                <ul class="rvm-versions-list" aria-label="<?php
                    echo esc_attr( sprintf( __( 'Versions of %s', 'retro-version-manager' ), $slug ) );
                    ?>">
                    <?php foreach ( $versions as $version ) : ?>
                        <li>
                            <span class="rvm-version"><?php echo esc_html( $version ); ?></span>
                            <a
                                href="<?php
                                echo esc_url(
                                    admin_url(
                                        'tools.php?page=retrvema-retro-version-manager&action=install&slug=' . $slug . 
                                        '&version=' . $version . '&_wpnonce=' . 
                                        wp_create_nonce( 'retrvema_install_' . $slug . '_' . $version )
                                    )
                                );
                                ?>"
                                class="rvm-install-link"
                                data-installed="<?php echo $is_installed ? 'true' : 'false'; ?>"
                                data-slug="<?php echo esc_attr( $slug ); ?>"
                                data-version="<?php echo esc_attr( $version ); ?>"
                                aria-label="<?php
                                echo esc_attr( sprintf( __( 'Install version %1$s of %2$s', 'retro-version-manager' ), $version, $slug ) );
                                ?>"
                            >
                                <?php esc_html_e( 'Install Now', 'retro-version-manager' ); ?>
                            </a>
                            |
                            <a
                                href="<?php echo esc_url( 'https://downloads.wordpress.org/plugin/' . $slug . '.' . $version . '.zip' ); ?>"
                                class="rvm-download-link"
                                aria-label="<?php
                                echo esc_attr( sprintf( __( 'Download version %1$s of %2$s as ZIP', 'retro-version-manager' ), $version, $slug ) );
                                ?>"
                            >
                                <?php esc_html_e( 'Download ZIP', 'retro-version-manager' ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
    }
}
?>