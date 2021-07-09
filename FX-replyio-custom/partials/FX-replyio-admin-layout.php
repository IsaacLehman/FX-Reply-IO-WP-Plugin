<?php
//================================================================
// BUILD SETTINGS LAYOUT
//================================================================
?>
<div class="wrap">
  <h2>FX Reply IO</h2>

  <form action='options.php' method='post'>

    <?php
    settings_fields( 'FXIO_plugin' );
    do_settings_sections( 'FXIO_plugin' );
    submit_button();
    ?>

    <hr>
    <p>
      <strong>Overview:</strong> The One-Sheeter-Resource being tracked will have the owner
      <br>
      of each submission sent as a new contact to the Reply IO campaign givin.
      <br>
    </p>
    <p>
      <strong>3 easy steps for linking a resource to a campaign:</strong>
      <ol>
        <li>Go to the One-Sheeter-Resource Tab and select the Resource you would like to link.</li>
        <li>Scroll down until you see the field titled: "Reply IO Campaign ID".</li>
        <li>Add the desired ID (found in the reference below) and save.</li>
      </ol>
    </p>
    <hr>

    <?php
      // add a list of all availible campaigns and their IDs
      $FXIO_campaigns = FXIO_get_all_campaigns();
    ?>
    <h3>Available Reply IO Campaigns Reference</h3>
    <ul>
      <?php
        if($FXIO_campaigns) {
          foreach( $FXIO_campaigns as $FXIO_cp) {
            echo '<li>- (ID = '.$FXIO_cp->id.') <strong>'.$FXIO_cp->name.'</strong></li>';
          }
        }
      ?>
    </ul>

  </form>
</div>
