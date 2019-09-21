<?php

require_once('Request.php');

Request();

function DownloadAuth() {
    global $authToken;

    $api_url = "https://api000.backblazeb2.com"; // From b2_authorize_account call
    $auth_token = "$authToken"; // From b2_authorize_account call
    $bucket_id = "47e4c831aec2ece463ca0017"; // The bucket that files can be downloaded from
    $valid_duration = 86400; // The number of seconds the authorization is valid for
    $file_name_prefix = ""; // The file name prefix of files the download authorization will allow

    $session = curl_init($api_url . "/b2api/v2/b2_get_download_authorization");

// Add post fields
    $data = array("bucketId" => $bucket_id,
        "validDurationInSeconds" => $valid_duration,
        "fileNamePrefix" => $file_name_prefix);
    $post_fields = json_encode($data);
    curl_setopt($session, CURLOPT_POSTFIELDS, $post_fields);

// Add headers
    $headers = array();
    $headers[] = "Authorization: " . $auth_token;
    curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
//var_dump($auth_token);
    curl_setopt($session, CURLOPT_POST, true); // HTTP POST
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);  // Receive server response
    $server_output = curl_exec($session); // Let's do this!
    curl_close($session); // Clean up
    echo ($server_output); // Tell me about the rabbits, George!
}
