<?php

class ServiceUploadController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function uploadFile() {
        
        $solicitarUrlFile= $this->solicitarUrlFile();

        $file_name = "deftones.mp3";
        $my_file = "/var/www/html/PlataformaWEB/assets/" . $file_name;
        $handle = fopen($my_file, 'r');
        $read_file = fread($handle, filesize($my_file));
        $content_type = mime_content_type($my_file);
        $sha1_of_file_data = sha1_file($my_file);

        $session = curl_init($solicitarUrlFile->uploadUrl);//Provided by b2_get_upload_url

// Add read file as post field
        curl_setopt($session, CURLOPT_POSTFIELDS, $read_file);

// Add headers
        $headers = array();
        $headers[] = "Authorization: " . $solicitarUrlFile->authorizationToken;//Provided by b2_get_upload_url
        $headers[] = "X-Bz-File-Name: " . $file_name;
        $headers[] = "Content-Type: " . $content_type;
        $headers[] = "X-Bz-Content-Sha1: " . $sha1_of_file_data;
        
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($session, CURLOPT_POST, true); // HTTP POST
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);  // Receive server response
        $server_output = curl_exec($session); // Let's do this!
        curl_close($session); // Clean up
        echo ($server_output); // Tell me about the rabbits, George!
    }

    public function solicitarUrlFile() {

        $datosAutorizacion = $this->autorization();
        $session = curl_init($datosAutorizacion->apiUrl . $this->config->item('url_upload'));

        // Add post fields
        $data = array("bucketId" => $this->config->item('bucket_id')); // The ID of the bucket you want to upload to

        $post_fields = json_encode($data);
        curl_setopt($session, CURLOPT_POSTFIELDS, $post_fields);

        // Add headers
        $headers = array();
        $headers[] = "Authorization: " . $datosAutorizacion->authorizationToken;
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($session, CURLOPT_POST, true); // HTTP POST
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);  // Receive server response
        $server_output = curl_exec($session); // Let's do this!
        curl_close($session); // Clean up
        header('Content-Type: application/json');
        
        return json_decode($server_output); // Tell me about the rabbits, George!
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