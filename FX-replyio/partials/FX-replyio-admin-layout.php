<?php
//================================================================
// BUILD SETTINGS LAYOUT
//================================================================
?>
<div class="wrap">
  <h2>FX Reply IO</h2>
  <p>
    <strong>Overview:</strong> The forms being tracked will have the owner of each
    <br>
    submission sent as a new contact to the Reply IO campaign givin.
  </p>
  <form action='options.php' method='post'>

      <?php
      settings_fields( 'FXIO_plugin' );
      do_settings_sections( 'FXIO_plugin' );
      ?>
      <h3>Available Contact forms to track</h3>
      <ul name="field-name" id="field-id">
          <?php
          $dbValue = get_option('field-name'); //example!
          $posts = get_posts(array(
              'post_type'     => 'wpcf7_contact_form',
              'numberposts'   => -1
          ));
          foreach ( $posts as $p ) {
              echo '<li>- (ID = '.$p->ID.') '.$p->post_title.'</li>';
          }
          ?>
      </ul>

      <?php
      submit_button();
      ?>

  </form>
</div>
