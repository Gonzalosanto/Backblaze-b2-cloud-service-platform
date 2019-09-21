<?php

class UploadController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        $this->load->helper('url');
    }

    public function index() {
        
    }

    public function uploadFile() {
        include('application/dataAccessObjects/Upload.php');
    }

}

?>