<?php

//================================================================
// HOOK RIGHT BEFORE MAIL SEND
// - grabs form info and fires api call
//================================================================
add_action("wpcf7_before_send_mail", "FXIO_post_submit", 10, 3);
function FXIO_post_submit($contact_form, $abort, $submission) {

  // get a bunch of info
  $name = $submission->get_posted_data('your-name-pdf');
  $email = $submission->get_posted_data('your-email');

  $form_id = $contact_form->id();
  $form_name = $contact_form->name();

  $post_id = $submission->get_posted_data('post-id') ?? -1;
  $download_post = get_field('download', $post_id) ?? -1;
  $campaign_id = get_field("reply_io_campaign_id", $download_post) ?? -1;

  $post_came_from = get_the_title($post_id);

  // returns false if failed
  $response = FXIO_send_new_contact($name, $email, $post_came_from, $form_name, $form_id, $campaign_id);

  return $contact_form;
}


//================================================================
// BUILD API CALLER
// - You must supply the options array
// - Returns the response
//
// API DOCS: https://apidocs.reply.io/
//================================================================
function FXIO_api_call($options) {
  // start the curl to the api -> build the carrier
  $curl = curl_init();
  // add the payload to the carrier
  curl_setopt_array($curl, $options);
  // send the payload on the carrier and wait for a response
  $response = curl_exec($curl);

  // check if carrier made it safely
  if($response === FALSE) {
    echo curl_error($curl); // this will show in the ajax response
  }

  // terminate the carrier
  curl_close($curl);
  // all done :)
  return $response;
}


//================================================================
// BUILD API CALLER -> CREATE CONTACT/LEAD
// - Only sends if the API key has been set and
//   the form has an associated campaign ID.
//================================================================
function FXIO_send_new_contact($name, $email, $post_came_from, $form_name, $form_id, $campaign_id) {

  $FXIO_api_key = getAPI_Key();

  // check if a valid form id to track
  if( $FXIO_api_key == -1 ) {
    return NULL;
  } else if(!$campaign_id || $campaign_id == -1) {
    return NULL;
  }

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
      "campaignId": ' . $campaign_id . ',
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
      'x-api-key: ' . $FXIO_api_key,
    ),
  );

  // send the payload on the carrier and wait for a response
  $response = FXIO_api_call( $options );

  // all done :)
  return $response;
}


//================================================================
// BUILD API CALLER -> GET ALL CAMPAINGS
// - returns JSON like format array(objects)
//================================================================
function FXIO_get_all_campaigns() {

  $FXIO_api_key = getAPI_Key();

  // check if a valid form id to track
  if( $FXIO_api_key == -1 ) {
    return NULL;
  }

  // build the payload
  $options = array(
    CURLOPT_URL => 'https://api.reply.io/v1/campaigns',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false, // TODO: maybe turn off in production?
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
      'x-api-key: ' . $FXIO_api_key
    ),
  );

  // send the payload on the carrier and wait for a response
  $response = FXIO_api_call( $options );

  // all done :)
  return json_decode($response);
}
