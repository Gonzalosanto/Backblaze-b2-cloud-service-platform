<?php

require_once("Request.php");

Request();

//global $api;
global $apiURL;
//global $auth;
global $authToken;

$api_url = "$apiURL"; // From b2_authorize_account call
$auth_token = "$authToken"; // From b2_authorize_account call
$bucket_id = "47e4c831aec2ece463ca0017";  // The ID of the bucket

$session = curl_init($api_url . "/b2api/v2/b2_list_file_names");

// Add post fields
$data = array("bucketId" => $bucket_id);
$post_fields = json_encode($data);
curl_setopt($session, CURLOPT_POSTFIELDS, $post_fields);

// Add headers
$headers = array();
$headers[] = "Authorization: " . $auth_token;
curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

curl_setopt($session, CURLOPT_POST, true); // HTTP POST
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);  // Receive server response
$server_output = curl_exec($session); // Let's do this!
curl_close($session); // Clean up
//echo ($server_output); // Tell me about the rabbits, George!
//Array<-JSON 
$objetoObj = json_decode($server_output, true);
//conteo de FileNames
$countFileName = count($objetoObj['files']);

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}
