<?php

class DownloadController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function downloadFile() {
        $fileName = $this->input->get('filename');
        $datosAutorizacion = $this->autorization();

        //downloadAUTH
        // DownloadAuth();
        //Publico
        $download_url = "https://f000.backblazeb2.com"; // From b2_authorize_account call
        $bucket_name = "archivo1992";  // The NAME of the bucket you want to download from
        $uri = $download_url . "/file/" . $bucket_name . "/" . $fileName;


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
        $auth_token = $datosAutorizacion->authorizationToken; // From b2_authorize_account call
        $uri = $download_url . "/file/" . $bucket_name . "/" . $fileName;

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
        header('Content-Disposition: attachment; filename =' . $fileName); //renombramos la descarga
        echo($server_output);
        exit();
    }

    public function autorization() {
        $credentials = base64_encode($this->config->item('application_key_id') . ":" . $this->config->item('application_key'));
        $session = curl_init($this->config->item('url_authorization'));

        // Add headers
        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = "Authorization: Basic " . $credentials;
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);  // Add headers

        curl_setopt($session, CURLOPT_HTTPGET, true);  // HTTP GET
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true); // Receive server response
        $datosAutorizacion = json_decode(curl_exec($session));
        curl_close($session);
        return $datosAutorizacion;
    }

}

?>