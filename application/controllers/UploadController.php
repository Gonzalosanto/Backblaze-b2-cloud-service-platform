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
       header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");*/
       
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
   
 } 
?>