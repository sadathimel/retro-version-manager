# Retro Version Manager

**Retro Version Manager** is a WordPress plugin that simplifies managing plugin versions. It allows users to browse, download, or install older versions of WordPress plugins hosted on WordPress.org. Ideal for resolving compatibility issues or testing specific versions (e.g., `wordpress-seo`, `contact-form-7`).

**Current Version:** 1.0.2

---

## 🚀 Features

- 🔍 **Search Plugin Versions** – View all available versions by entering a plugin slug (e.g., `contact-form-7`). Installed version is highlighted.
- 📥 **Install Older Versions** – One-click install for any version (if not already installed).
- 💾 **Download ZIP Files** – Save plugin ZIPs for manual installation or backup.
- 🖥️ **User-Friendly Interface** – Clean and intuitive UI with helpful instructions.
- 🔐 **Secure and Reliable** – Utilizes WordPress core APIs for safe operations.

> **Note:** If a plugin is already installed, deactivate and delete it manually from the Plugins page before installing another version.

---

## 📦 Installation

### 1. Download the Plugin

- Clone the repository:
  ```bash
  git clone https://github.com/sadathimel/retro-version-manager.git
  ```

- Or download the ZIP from the [Releases](https://github.com/sadathimel/retro-version-manager/releases) page.

### 2. Install in WordPress

- Copy the `retro-version-manager` folder to `/wp-content/plugins/`.
- Or upload the ZIP via **Plugins > Add New > Upload Plugin** in the WordPress dashboard.

### 3. Activate

- Go to **Plugins** in the dashboard and activate **Retro Version Manager**.

### 4. Use the Plugin

- Navigate to **Tools > Retro Version Manager**.
- Enter a plugin slug, search for versions, and install or download as needed.

---

## 🧩 Requirements

- WordPress: **6.3** or higher  
- PHP: **7.4** or higher  
- Tested up to: **WordPress 6.8**

---

## 🛠️ Usage

### 🔍 Search for Versions

1. Go to **Tools > Retro Version Manager**.
2. Enter a plugin slug (e.g., `wordpress-seo`) and click **Search Versions**.
3. View all available versions; installed version is highlighted.

### ⚙️ Install a Version

1. Click **Install Now** next to the desired version.
2. If already installed, a popup will guide you to deactivate and delete the existing plugin.
3. Activate from the Plugins page if needed.

### 💾 Download a Version

- Click **Download ZIP** to save for manual use or backup.

### 🧠 Best Practices

- **Backup**: Always back up your site before downgrading.
- **Manual Steps**: Deactivate and delete existing plugins manually before installing older versions.

---

## 👨‍💻 Development

For developers who want to contribute or test locally:

### 🔧 Setup

1. **Install XAMPP** (PHP 7.4+, Apache, MySQL).
2. Extract WordPress to `C:\xampp\htdocs\wordpress`.
3. Complete installation at `http://localhost/wordpress`.

### 📂 Clone the Repository

```bash
cd C:\xampp\htdocs\wordpress\wp-content\plugins
git clone https://github.com/sadathimel/retro-version-manager.git
```

### ⚙️ Configure XAMPP

Edit `C:\xampp\php\php.ini`:
```ini
extension=curl
extension=openssl
upload_tmp_dir="C:\xampp\tmp"
max_execution_time=60
memory_limit=256M
```

Set permissions (Windows):
```cmd
icacls "C:\xampp\htdocs\wordpress\wp-content\plugins\retro-version-manager" /grant Everyone:F /T
icacls "C:\xampp\tmp" /grant Everyone:F /T
```

Restart Apache.

### 🐞 Enable Debugging

Edit `wp-config.php`:
```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

### ✅ Test the Plugin

- Activate **Retro Version Manager**
- Example: Install `contact-form-7` version `6.0.6`
- Verify popup on conflict with existing version
- Test:
  - Nonces and permission checks
  - Read-only directory behavior
  - Nonexistent version errors

Use WP-CLI:
```bash
wp plugin check retro-version-manager
```

### 🌐 Verify Connectivity

Test temp directory (`test.php`):
```php
<?php
echo sys_get_temp_dir();
var_dump(is_writable(sys_get_temp_dir()));
?>
```

Test cURL (`curl-test.php`):
```php
<?php
$ch = curl_init('https://downloads.wordpress.org/plugin/contact-form-7.6.0.6.zip');
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo 'HTTP Code: ' . $http_code;
if ($http_code !== 200) {
    echo ' cURL Error: ' . curl_error($ch);
}
curl_close($ch);
?>
```

---

## 🤝 Contributing

Contributions are welcome!

### 📥 Getting Started

1. Fork the repository.
2. Create a branch:
   ```bash
   git checkout -b feature/your-feature
   ```

3. Follow these guidelines:
   - Use `plugin_dir_path()` and defined constants.
   - Avoid `deactivate_plugins()` or `delete_plugins()`.
   - Use nonces and permission (`install_plugins`) checks.
   - Follow WordPress Coding Standards.

4. Test thoroughly:
   - `wp plugin check retro-version-manager`
   - WordPress 6.3+ / PHP 7.4+
   - Use [WordPress Playground](https://developer.wordpress.org/playground/) for testing

5. Submit a Pull Request:
   - Push to your fork.
   - Open a PR to `main`.
   - Reference issues (e.g., `#123`)

---

## 📜 License

Licensed under the [GNU General Public License v2.0 or later](LICENSE).

---

## 💬 Support

- **Issues**: Report bugs or feature requests on [GitHub Issues](https://github.com/sadathimel/retro-version-manager/issues).
- **WordPress.org**: Once published, support will also be available in the forum.
- **Buy Me a Coffee**: [Support the developer](https://www.buymeacoffee.com/) ☕.

---

## 📝 Changelog

### 1.0.2
- Fixed WordPress.org review issues:
  - Replaced `WP_PLUGIN_DIR/WP_CONTENT_DIR` with `plugin_dir_path()`.
  - Removed automatic plugin deactivation/deletion.
  - Removed debug logging.
  - Added nonce and permission checks.
- Updated UI to clarify manual deactivation/deletion.

### 1.0.1
- Improved security with nonces and input validation.
- Removed unnecessary translation loading.
- Updated prefix to `retrvema`.

### 1.0.0
- Initial release with version search, install, and download features.

---

**Last updated:** May 5, 2025