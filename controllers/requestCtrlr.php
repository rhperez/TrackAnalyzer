<?php

function getAccessToken() {

  $token_time = selectTokenTime();
  if ($token_time['last_update'] + $token_time['expires_in'] >= $token_time['time_now']) {
    return $token_time['access_token'];
  }
  $payload = base64_encode(getOAuth_ClientID().":".getOAuth_ClientSecret());

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://accounts.spotify.com/api/token",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "grant_type=client_credentials",
    CURLOPT_HTTPHEADER => array(
      "Authorization: Basic ".$payload
    ),
  ));
  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
    return null;
  } else {
    updateToken(json_decode($response));
    return json_decode($response)->access_token;
  }
}

function executeRequest($url, $access_token, $id = null) {
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url.$id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Accept: application/json",
      "Authorization: Bearer ".$access_token
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    return null;
  } else {
    return $response;
  }
}

function requestAudioFeatures($track_id) {
  $access_token = getAccessToken();
  return executeRequest("https://api.spotify.com/v1/audio-features/", $access_token, $track_id);
}

function requestAudioAnalysis($track_id) {
  $access_token = getAccessToken();
  return executeRequest("https://api.spotify.com/v1/audio-analysis/", $access_token, $track_id);
}

function requestTrack($track_id) {
  $access_token = getAccessToken();
  return executeRequest("https://api.spotify.com/v1/tracks/", $access_token, $track_id);
}
?>
