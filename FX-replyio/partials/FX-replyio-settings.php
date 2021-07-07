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

    add_settings_field(
        'FXIO_campain_id',
        'Reply IO - Campaign ID <br> - where new contacts are sent',
        'FXIO_campain_id_render',
        'FXIO_plugin',
        'FXIO_plugin_section'
    );

    add_settings_field(
        'FXIO_Contact_Form_ID',
        'Contact Form 7 - Form ID <br> - Comma seperated',
        'FXIO_Form_ID_render',
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
    <input type='text' name='FXIO_plugin_settings[FXIO_API_Key]' value='<?php echo $options['FXIO_API_Key']; ?>' placeholder="XXXXXXX...">
    <?php
}

function FXIO_campain_id_render(  ) {
    $options = get_option( 'FXIO_plugin_settings' );
    ?>
    <input type='text' name='FXIO_plugin_settings[FXIO_campain_id]' value='<?php echo $options['FXIO_campain_id']; ?>' placeholder="ex. 1234">
    <?php
}

function FXIO_Form_ID_render(  ) {
    $options = get_option( 'FXIO_plugin_settings' );
    ?>
    <input type='text' name='FXIO_plugin_settings[FXIO_Contact_Form_ID]' value='<?php echo $options['FXIO_Contact_Form_ID']; ?>' placeholder="ex. 12,55,34">
    <?php
}

//================================================================
// BUILD SETTINGS GETTERS
// - grabs info from the settings page
//================================================================
function getAPI_Key() { // integer
  $FXIO_options = get_option( 'FXIO_plugin_settings' );
  return $FXIO_options['FXIO_API_Key'] ?? -1;
}
function getCampaign_ID() { // integer
  $FXIO_options = get_option( 'FXIO_plugin_settings' );
  return  $FXIO_options['FXIO_campain_id'] ?? -1;
}
function getCF7_IDs() { // returns an array of strings
  $FXIO_options = get_option( 'FXIO_plugin_settings' );
  $FXIO_CF7_ids  = $FXIO_options['FXIO_Contact_Form_ID'] ?? "-1";
  return explode(',', $FXIO_CF7_ids);
}
