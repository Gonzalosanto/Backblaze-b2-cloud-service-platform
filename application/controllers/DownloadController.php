<?php
class DownloadController extends CI_Controller {
   
    /**
     * Manage __construct
     *
     * @return Response
    */
    public function __construct() { 
       parent::__construct(); 
       $this->load->helper('url'); 
    }
   
    /**
     * Manage index
     *
     * @return Response
    */
    public function index() { 

    } 
     
    /**
     * Manage upload
     *
     * @return Response
    */
    public function downloadFile() {
      global $fileName;
      $fileName = $this->input->get('filename');
      
      
      include('application/dataAccessObjects/Download.php');
      
      
      //$this->load->view('pages/table'); 
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
      $server_output = curl_exec($session);
      $datosAutorizacion = json_decode($server_output);
      curl_close($session);
      echo $datosAutorizacion;
      
      //die();
      //return $datosAutorizacion;
  }

    
   
 } 
?>