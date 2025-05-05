=== Retro Version Manager === 
Contributors: sadathimel 
Tags: plugin version, downgrade plugin, wordpress plugin manager, version control, wordpress-seo 
Requires at least: 6.3 
Tested up to: 6.8
Stable tag: 1.0.2 
Requires PHP: 7.4 
License: GPLv2 or later 
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily browse, download, or install older versions of WordPress plugins from WordPress.org. Perfect for testing or restoring plugin compatibility.

== Description ==

Retro Version Manager simplifies managing WordPress plugin versions. Whether you need to downgrade wordpress-seo or any plugin due to compatibility issues, this tool lets you search, download, or install specific versions directly from the WordPress.org repository.

**Key Features:**

- **Search Plugin Versions:** Enter a plugin slug (e.g., wordpress-seo) to view all available versions, with the installed version shown at the top if applicable.
- **Install Older Versions:** Install any version with one click, provided the plugin is not already installed.
- **Download ZIP Files:** Save plugin ZIPs for manual installation or backups.
- **User-Friendly Interface:** Clear instructions and a clean design inspired by modern tools.
- **Safe and Secure:** Uses WordPress core APIs for reliable downloads and installations.

**Important Note:** If a plugin is already installed, you must manually deactivate and delete it from the Plugins page before installing a different version.

Ideal for developers, site admins, and anyone needing precise control over plugin versions. Support development at Buy Me a Coffee.

== Installation ==

1. Upload the retro-version-manager folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **Tools > Retro Version Manager** in your dashboard.
4. Enter a plugin slug (e.g., wordpress-seo), search versions, and install or download as needed.
5. If the plugin is already installed, deactivate and delete it manually from the Plugins page before installing a different version.

== Frequently Asked Questions ==

= Can I downgrade any WordPress plugin? =
Yes, as long as the plugin is hosted on WordPress.org and has older versions available (e.g., wordpress-seo).

= Will installing a new version replace the old one? =
No, you must manually deactivate and delete the existing plugin from the Plugins page before installing a different version.

= Is it safe to downgrade plugins? =
Downgrading can resolve compatibility issues but may introduce security risks. Always back up your site first.

= Can I download plugin versions without installing? =
Absolutely! Use the "Download ZIP" option to save any version for later use.

= How do I know which version is installed? =
When you search for a plugin, the currently installed version is displayed at the top of the list if the plugin is installed.

== Screenshots ==

1. **Admin Interface**: Search for plugin versions with a clean, user-friendly design.
2. **Version List**: View available versions with the installed version at the top, plus options to install or download.
3. **Success Notice**: Confirmation after installing a version, with activation instructions.

== Changelog ==

= 1.0.2 =
- Addressed WordPress.org review issues:
  - Removed hardcoded WP_PLUGIN_DIR/WP_CONTENT_DIR; used plugin_dir_path().
  - Removed automatic plugin deactivation/deletion; requires manual action.
  - Removed debug logging to WP_CONTENT_DIR.
  - Added nonce and permission checks for all inputs.
- Updated admin interface to clarify manual deactivation/deletion requirement.

= 1.0.1 =
- Fixed issues identified in WordPress.org plugin review.
- Improved security with nonces and input validation.
- Removed unnecessary translation loading.
- Updated prefix to retrvema for uniqueness.

= 1.0.0 =
- Initial release with version search, install, and download features.
- Added display of currently installed version at the top of the version list.
- Supports plugins like wordpress-seo.

== Upgrade Notice ==

= 1.0.2 =
Updated to address WordPress.org review issues. Requires manual deactivation/deletion of existing plugins before installing new versions. Recommended for compliance and security.

= 1.0.1 =
Updated to address WordPress.org review issues. Recommended for improved security and compliance.

= 1.0.0 =
First version of Retro Version Manager. No upgrades needed yet!

== External Services ==

This plugin uses the WordPress.org Plugin API to retrieve plugin information, including available versions, for downgrading plugins.

- **Service**: WordPress.org Plugin API
- **Purpose**: Fetches plugin data (e.g., version information) based on the plugin slug provided by the user.
- **Data Sent**: The plugin slug (e.g., "wordpress-seo") is sent to the API when a user searches for plugin versions.
- **When**: Data is sent when the user submits a plugin slug via the Retro Version Manager admin interface.
- **Terms of Service**: https://wordpress.org/about/terms/
- **Privacy Policy**: https://wordpress.org/about/privacy/

== Support ==
Buy Me a Coffee to support development or report issues via the WordPress.org support forum.