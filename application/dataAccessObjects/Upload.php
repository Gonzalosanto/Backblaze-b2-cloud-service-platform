<?php

require_once("Request.php");

Request();

/* if ($_SERVER["REQUEST_METHOD"] == "POST") { 
  // Check if userfile was uploaded without errors
  print_r($_FILES['userfile']);
  if (isset($_FILES['userfile']) && $_FILES['userfile']['error'] == 0) {

  $file_Name     = $_FILES['userfile']['name'];
  $file_type     = $_FILES['userfile']['type'];
  $file_size     = $_FILES['userfile']['size'];
  $file_tmp_name = $_FILES['userfile']['tmp_name'];
  $file_error    = $_FILES['userfile']['error'];
  }
  } */
//var_dump ($file_tmp_name);
//$fileNAME=str_replace ( " " , "_" , $file_Name);
//Request Upload URL

global $apiURL;
global $authToken;

$api_url = "$apiURL"; // From b2_authorize_account call
$auth_token = "$authToken"; // From b2_authorize_account call
$bucket_id = "47e4c831aec2ece463ca0017";  // The ID of the bucket you want to upload to

$session = curl_init($api_url . "/b2api/v2/b2_get_upload_url");

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
header('Content-Type: application/json');
echo ($server_output); // Tell me about the rabbits, George!
//$JSON = $server_output;
$objetoUpload = json_decode($server_output);
$uploadURL = $objetoUpload->uploadUrl;
header("Access-Control-Allow-Origin: *");
/*global $uploadURL;

$uploadAuthToken = $objetoUpload->authorizationToken;
$bucketID = $objetoUpload->bucketId;


$filesize = $file_size;
$type = $file_type;


if($file_error > 0) { 
    //$error_msg = 'You must upload a userfile';
}	else  {
//UPLOAD
$path = $file_tmp_name;
$file_name = $fileNAME;
$my_file = $path."\\".$file_name;
$handle = fopen($my_file, 'r');
$read_file = fread($handle,$filesize);

$upload_url = "$uploadURL"; // Provided by b2_get_upload_url
$upload_auth_token = "$uploadAuthToken"; // Provided by b2_get_upload_url
$bucket_id = "$bucketID";  // The ID of the bucket
$content_type = "$type";
$sha1_of_file_data = sha1_file($my_file);

$session = curl_init($upload_url);

// Add read userfile as post field
curl_setopt($session, CURLOPT_POSTFIELDS, $read_file); 

// Add headers
$headers = array();
$headers[] = "Authorization: " . $upload_auth_token;
$headers[] = "X-Bz-File-Name: " . $file_name;
$headers[] = "Content-Type: " . $content_type;
$headers[] = "X-Bz-Content-Sha1: " . $sha1_of_file_data;
curl_setopt($session, CURLOPT_HTTPHEADER, $headers); 

curl_setopt($session, CURLOPT_POST, true); // HTTP POST
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);  // Receive server response
$server_output = curl_exec($session); // Let's do this!
curl_close ($session); // Clean up
echo ($server_output); // Tell me about the rabbits, George!
}
*/