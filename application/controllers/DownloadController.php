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

    
   
 } 
?>