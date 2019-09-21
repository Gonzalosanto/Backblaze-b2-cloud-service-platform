<?php

//session_start();
require_once('Request.php');
require_once('DownloadAuth.php');
Request();

//global $data;

global $authToken;
global $fileName;

//downloadAUTH
// DownloadAuth();
//Publico
$download_url = "https://f000.backblazeb2.com"; // From b2_authorize_account call
$bucket_name = "archivo1992";  // The NAME of the bucket you want to download from
$file_name = "$fileName"; // The name of the file you want to download
$uri = $download_url . "/file/" . $bucket_name . "/" . $file_name;

$session = curl_init($uri);

curl_setopt($session, CURLOPT_HTTPGET, true); // HTTP GET
curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);  // Receive server response
$server_output = curl_exec($session); // Let's do this!
curl_close($session); // Clean up
//echo ($server_output); // Tell me about the rabbits, George!*/
// You will need to use the account authorization token if your bucket's type is allPrivate.
//Privado

$download_url = "https://f000.backblazeb2.com"; // From b2_authorize_account call
$bucket_name = "archivo1992";  // The NAME of the bucket you want to download from
$file_name = "$fileName"; // The name of the file you want to download
$auth_token = $authToken; // From b2_authorize_account call
$uri = $download_url . "/file/" . $bucket_name . "/" . $file_name;

$session = curl_init($uri);

// Add headers
$headers = array();
$headers[] = "Authorization: " . $auth_token;
curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

curl_setopt($session, CURLOPT_HTTPGET, true); // HTTP POST
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);  // Receive server response
$server_output = curl_exec($session); // Let's do this!
curl_close($session); // Clean up
//echo ($server_output); // Tell me about the rabbits, George!

header('Content-type:application/octet-stream'); //Acá le cambias el tipo de archivo (MimeType) por lo que quieras
header("Content-Transfer-Encoding: Binary");
header('Content-Disposition: attachment; filename =' . $file_name); //renombramos la descarga
echo($server_output);
exit();

