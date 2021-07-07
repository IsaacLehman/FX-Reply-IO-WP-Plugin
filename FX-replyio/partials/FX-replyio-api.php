<?php

//================================================================
// HOOK RIGHT BEFORE MAIL SEND
// - grabs form info and fires api call
//================================================================
add_action("wpcf7_before_send_mail", "FXIO_post_submit", 10, 3);
function FXIO_post_submit($contact_form, $abort, $submission) {
  $name = $submission->get_posted_data('your-name');
  $email = $submission->get_posted_data('your-email');

  $form_id = $contact_form->id();
  $form_name = $contact_form->name();

  $post_id = $submission->get_meta('container_post_id');
  $post_came_from = get_the_title($post_id);

  // TODO: Do something with response (i.e. error check?)
  $response = FXIO_send_new_contact($name, $email, $post_came_from, $form_name, $form_id);

  return $contact_form;
}


//================================================================
// BUILD API CALLER
// - only sends if form id is one of the selected in
//   the settings page.
//================================================================
function FXIO_send_new_contact($name, $email, $post_came_from, $form_name, $form_id) {

  // check if a valid form id to track
  if( !in_array($form_id, getCF7_IDs()) ) {
    return;
  }

  // start the curl to the api -> build the carrier
  $curl = curl_init();
  // build the payload
  $options = array(
    CURLOPT_URL => 'https://api.reply.io/v1/actions/addandpushtocampaign',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
      "campaignId": ' . getCampaign_ID() . ',
      "email": "' . $email . '",
      "firstName": "' . $name . '",

      "customFields": [
      {
      "key": "Post-Form-Was-On",
      "value": "' . $post_came_from . '"
      },
      {
      "key": "Form-Name",
      "value": "' . $form_name . '"
      },
      {
      "key": "Form-ID",
      "value": "' . $form_id . '"
      }
      ]
      }',
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json',
      'x-api-key: ' . getAPI_Key(),
    ),
  );
  // add the payload to the carrier
  curl_setopt_array($curl, $options);
  // send the payload on the carrier and wait for a response
  $response = curl_exec($curl);
  // terminate the carrier
  curl_close($curl);
  // all done :)
  return $response;
}
