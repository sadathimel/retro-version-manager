jQuery(document).ready(function ($) {
  console.log("Retro Version Manager admin JS loaded.");

  // Handle Install Now clicks
  $(".rvm-install-link").on("click", function (e) {
    const $link = $(this);
    const isInstalled = $link.data("installed") === true;
    const slug = $link.data("slug");
    const version = $link.data("version");

    if (isInstalled) {
      e.preventDefault();

      // Create dialog
      const dialog = $(`
                <div class="rvm-confirm-dialog">
                    <div class="rvm-confirm-dialog-content">
                        <h3>${wp.i18n.__(
                          "Plugin Already Installed",
                          "retro-version-manager"
                        )}</h3>
                        <p>${wp.i18n.__(
                          "The plugin",
                          "retro-version-manager"
                        )} <strong>${slug}</strong> ${wp.i18n.__(
        "is already installed. Please deactivate and delete it manually from the Plugins page before installing version",
        "retro-version-manager"
      )} <strong>${version}</strong>.</p>
                        <div class="rvm-confirm-dialog-buttons">
                            <button class="rvm-confirm">${wp.i18n.__(
                              "Go to Plugins",
                              "retro-version-manager"
                            )}</button>
                            <button class="rvm-cancel">${wp.i18n.__(
                              "Cancel",
                              "retro-version-manager"
                            )}</button>
                        </div>
                    </div>
                </div>
            `);

      // Append dialog to body
      $("body").append(dialog);

      // Handle confirm
      dialog.find(".rvm-confirm").on("click", function () {
        window.location.href =
          "<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>";
      });

      // Handle cancel
      dialog.find(".rvm-cancel").on("click", function () {
        dialog.remove();
      });
    }
  });
});
