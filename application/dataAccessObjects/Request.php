<?php

function Request() {
    $application_key_id = "0007481e2c43a070000000002"; // Obtained from your B2 account page
    $application_key = "K0004e0jBPIHRPRHgk3d/48u3bJb0o4"; // Obtained from your B2 account page
    $credentials = base64_encode($application_key_id . ":" . $application_key);
    $url = "https://api.backblazeb2.com/b2api/v2/b2_authorize_account";

    $session = curl_init($url);

    // Add headers
    $headers = array();
    $headers[] = "Accept: application/json";
    $headers[] = "Authorization: Basic " . $credentials;
    curl_setopt($session, CURLOPT_HTTPHEADER, $headers);  // Add headers

    curl_setopt($session, CURLOPT_HTTPGET, true);  // HTTP GET
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true); // Receive server response
    $server_output = curl_exec($session);
    curl_close($session);
    //echo ($server_output);

    $objeto = json_decode($server_output);
    global $authToken;
    $authToken = $objeto->authorizationToken;
    //var_dump($authToken);
    global $apiURL;
    $apiURL = $objeto->apiUrl;
    //var_dump($apiURL);
}
