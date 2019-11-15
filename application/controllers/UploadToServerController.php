<?php

class UploadToServerController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //header("Access-Control-Allow-Origin: *");
        $this->load->helper('url');
        $this->load->model(array('a_blog_model'));
    }

    public function subirArchivo(){

        $id_articulo = $this->a_blog_model->listar();
        echo "<pre>";
        var_dump($id_articulo);
        echo "</pre>";
        die();

        $uploadDir= 'uploads/';
        // Check if the form was submitted
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            //MULTIPLE  :   https://www.geeksforgeeks.org/how-to-select-and-upload-multiple-files-with-html-and-php-using-http-post/

            // Check if file was uploaded without errors
            if(isset($_FILES['archivo']) && $_FILES['archivo']["error"] == 0){
               
                $filename = $_FILES['archivo']["name"];
                $filetype = $_FILES['archivo']["type"];
                $filesize = $_FILES['archivo']["size"];          
               
            
                // Verify file size - 5MB maximum
                $maxsize = 5 * 1024 * 1024;
                if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
            
               
                
                    // Check whether file exists before uploading it
                    if(file_exists('uploads/' . $filename)){
                        echo $filename . " already exists.";
                    } else{
                        move_uploaded_file($_FILES['archivo']["tmp_name"], 'uploads/' . $filename);
                        echo "Your file was uploaded successfully.";
                    } 
                
            } else{
                echo "Error: " . $_FILES['archivo']["error"];
            }
        }
    }
    

        

} 
