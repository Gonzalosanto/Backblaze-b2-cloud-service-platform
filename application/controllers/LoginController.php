<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends CI_Controller {
	function __construct()
    {
        // this is your constructor
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
    }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function validation()
        {
           
                
                $user=$_POST["inputUser"]; 
                $pass=$_POST["password"];

                if($pass == 'aAniNAq98H' and $user == 'admin'){
                session_start();
                
                $_SESSION['authorized'] = true;
                $_SESSION['user']=$user;
                $_SESSION['password']=$pass;
                //var_dump($_POST);
               // unset($_POST);
                //var_dump($_POST);
                
                  	
                  	redirect('LoginController/table');
                  
                }else{
                    //$this->load->view('pages/login');
                    //redirect('welcome/index', 'refresh');
                }                
	
		

		}
		public function table()
		{	 $this->load->helper('url');
			$this->load->view('pages/table');
			
		}

		
		
}


?>