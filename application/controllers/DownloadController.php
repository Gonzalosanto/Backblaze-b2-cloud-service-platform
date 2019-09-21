<?php

class DownloadController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index() {
        
    }

    public function downloadFile() {
        global $fileName;
        $fileName = $this->input->get('filename');
        include('application/dataAccessObjects/Download.php');
    }

}

?>