<?php

class UploadController extends CI_Controller {

    /**
     * Manage __construct
     *
     * @return Response
     */
    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");

        /* header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
          header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE"); */

        $this->load->helper('url');
    }

    /**
     * Manage index
     *
     * @return Response
     */
    public function index() {
        //$this->load->view('table'); 
    }

    /**
     * Manage upload
     *
     * @return Response
     */
    public function uploadFile() {


        include('application/dataAccessObjects/Upload.php');

        //redirect('welcome/index', 'refresh');
    }

    public function uploadFile2() {

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
        echo $server_output; // Tell me about the rabbits, George!
        die();
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