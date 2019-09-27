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
        echo ($server_output);
        die();
    }

}

?>