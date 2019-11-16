<?php

class UploadToServerController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //header("Access-Control-Allow-Origin: *");
        $this->load->helper('url');
        $this->load->model(array('ServerModel'));
    }

    public function subirArchivo(){

        $uploadDir= 'uploads/'; //CAMBIAR POR CONFIG PARAM.
        // Check if the form was submitted
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            //MULTIPLE  :   https://www.geeksforgeeks.org/how-to-select-and-upload-multiple-files-with-html-and-php-using-http-post/

            

            // Check if file was uploaded without errors
            if(isset($_FILES['archivo']) && $_FILES['archivo']["error"] == 0){
               
                $filename = $_FILES['archivo']["name"];
                $filetype = $_FILES['archivo']["type"];
                $filesize = $_FILES['archivo']["size"];

                //DATOS PARA ENVIAR A LA DB

                $data = array(
                    'id'=> null,
                    'file_name_original' =>$filename,
                    'id_file' =>$filename,
                    'date_created' => date('Y-m-d H:i:s'),
                    'deleted'=> '0'
                    
                );
                

                $this->ServerModel->subir_archivo($data);
                $id_file=$this->ServerModel->listar_archivos_id($filename);                
                $this->ServerModel->new_estatus($id_file);
               
            
                // Verify file size - 5MB maximum
                $maxsize = 5 * 1024 * 1024;
                if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");
            
               
                
                    // Check whether file exists before uploading it
                    if(file_exists('uploads/' . $filename)){
                        echo $filename . " already exists.";
                    } else{
                        $this->ServerModel->inicio_subida_local_php($id_file);
                        move_uploaded_file($_FILES['archivo']["tmp_name"], 'uploads/' . $filename);
                        echo "Your file was uploaded successfully.";
                    } 
                
            } else{
                echo "Error: " . $_FILES['archivo']["error"];
            }
        }
    }
    

        

} 
