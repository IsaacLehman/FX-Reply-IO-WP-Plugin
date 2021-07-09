<?php

//================================================================
// BUILD SETTINGS MENU
//================================================================

// render call back for add_menu_page
function FXIO_display_admin() {
  require_once 'FX-replyio-admin-layout.php';
}

function FXIO_addPluginAdminMenu() {
  add_menu_page(  'FX Reply IO', 'FX Reply IO', 'manage_options', 'fx-replyio', 'FXIO_display_admin');
}

add_action('admin_menu', 'FXIO_addPluginAdminMenu');

//================================================================
// REGISTER SETTINGS OPTIONS
//================================================================
add_action( 'admin_init', 'FXIO_settings_init' );
function FXIO_settings_init(  ) {
    register_setting( 'FXIO_plugin', 'FXIO_plugin_settings' );
    add_settings_section(
        'FXIO_plugin_section',
        'API Admin Page',
        '',
        'FXIO_plugin'
    );

    add_settings_field(
        'FXIO_API_Key',
        'Reply IO - API KEY',
        'FXIO_API_Key_render',
        'FXIO_plugin',
        'FXIO_plugin_section'
    );
}

//================================================================
// BUILD SETTINGS RENDERER
//================================================================
function FXIO_API_Key_render(  ) {
    $options = get_option( 'FXIO_plugin_settings' );
    ?>
    <input class="FXIO_API_key" type='text' name='FXIO_plugin_settings[FXIO_API_Key]' value='<?php echo $options['FXIO_API_Key']; ?>' placeholder="XXXXXXX...">
    <?php
}


//================================================================
// BUILD SETTINGS GETTERS
// - grabs info from the settings page
//================================================================
function getAPI_Key() { // integer or -1
  $FXIO_options = get_option( 'FXIO_plugin_settings' );
  $FXIO_api_key = $FXIO_options['FXIO_API_Key'] ?? -1;
  if ($FXIO_api_key == '') {
    $FXIO_api_key = -1;
  }
  return $FXIO_api_key;
}
