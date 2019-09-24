<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
    }
    
    public function index() {
        echo "<pre>";
        var_dump("hola");
        echo "</pre>";
        die();
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
            
        }
    }

    public function table() {
        $this->load->helper('url');
        $this->load->view('pages/table');
    }

}

?>