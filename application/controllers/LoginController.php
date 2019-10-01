<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends CI_Controller {

    function __construct() {
        // this is your constructor
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
    }

    public function validation() {
        $user = $_POST["inputUser"];
        $pass = $_POST["password"];

        if ($pass == 'aAniNAq98H' and $user == 'admin') {
            session_start();

            $_SESSION['authorized'] = true;
            $_SESSION['user'] = $user;
            $_SESSION['password'] = $pass;

            redirect('LoginController/table');
        } else {
            //$this->load->view('pages/login');
            //redirect('welcome/index', 'refresh');
        }
    }

    public function listarArchivos() {
        $this->load->view('pages/vista-archivos');
    }

}

?>