<?php

class UploadToServerController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //header("Access-Control-Allow-Origin: *");
        $this->load->helper('url');
        $this->load->model(array('ServerModel'));
        $this->load->model(array('subida_php_backblaze_model'));
    }

    public function subirArchivo() {

        // Check if the form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //MULTIPLE  :   https://www.geeksforgeeks.org/how-to-select-and-upload-multiple-files-with-html-and-php-using-http-post/
            // Check if file was uploaded without errors
            if (isset($_FILES['archivo']) && $_FILES['archivo']["error"] == 0) {

                $filename = $_FILES['archivo']["name"];
                $filetype = $_FILES['archivo']["type"];
                $filesize = $_FILES['archivo']["size"];

                //DATOS PARA ENVIAR A LA DB

                $data = array(
//                    'id' => null,
                    'file_name_original' => $filename,
                    'id_file' => $filename,
//                    'date_created' => date('Y-m-d H:i:s'),
//                    'deleted' => '0'
                );


                $this->ServerModel->subir_archivo($data);
                $id_file = $this->ServerModel->listar_archivos_id($filename);
                $this->ServerModel->new_estatus($id_file);


                // Verify file size - 5MB maximum
                $maxsize = 5 * 1024 * 1024;
                if ($filesize > $maxsize)
                    die("Error: File size is larger than the allowed limit.");



                // Check whether file exists before uploading it
                if (file_exists($this->config->item('dir_uploads') . $filename)) {
                    echo $filename . " already exists.";
                } else {
                    $this->ServerModel->inicio_subida_local_php($id_file);
                    move_uploaded_file($_FILES['archivo']["tmp_name"], $this->config->item('dir_uploads') . $filename);
                    echo "Your file was uploaded successfully.";
                    
                    $this->uploadFile($id_file, $filename);
                    
                }
            } else {
                echo "Error: " . $_FILES['archivo']["error"];
            }
        }
    }

    public function uploadFile($id_file, $file_name) {

        /* Para Pruebas */
//        $id_file = 1;
//        $file_name = "amoroso.jpg";
        /* Fin Para Pruebas */

        $my_file = $this->config->item('dir_uploads') . $file_name;

        if (!empty($id_file) && !empty($file_name) && file_exists($my_file)) {

            $solicitarUrlFile = $this->solicitarUrlFile();

            $this->subida_php_backblaze_model->inicio_subida_php_backblaze($id_file);

            $handle = fopen($my_file, 'r');
            $read_file = fread($handle, filesize($my_file));
            $content_type = mime_content_type($my_file);
            $sha1_of_file_data = sha1_file($my_file);

            $session = curl_init($solicitarUrlFile->uploadUrl); //Provided by b2_get_upload_url
// Add read file as post field
            curl_setopt($session, CURLOPT_POSTFIELDS, $read_file);

// Add headers
            $headers = array();
            $headers[] = "Authorization: " . $solicitarUrlFile->authorizationToken; //Provided by b2_get_upload_url
            $headers[] = "X-Bz-File-Name: " . $file_name;
            $headers[] = "Content-Type: " . $content_type;
            $headers[] = "X-Bz-Content-Sha1: " . $sha1_of_file_data;

            curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($session, CURLOPT_POST, true); // HTTP POST
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);  // Receive server response
            $server_output = curl_exec($session); // Let's do this!
            curl_close($session); // Clean up
            echo ($server_output); // Tell me about the rabbits, George!

            $this->subida_php_backblaze_model->final_subida_php_backblaze($id_file);
            $this->eliminar_archivo_php($my_file, $id_file);
        }
    }

    public function eliminar_archivo_php($filepath, $id_file) {
        $this->subida_php_backblaze_model->inicio_eliminar_archivo_php($id_file);
//        chmod($filepath, 0755);
        unlink($filepath);
        $this->subida_php_backblaze_model->final_eliminar_archivo_php($id_file);
    }

    public function solicitarUrlFile() {

        $datosAutorizacion = $this->autorization();
        $session = curl_init($datosAutorizacion->apiUrl . $this->config->item('url'
                        . '_upload'));

        // Add post fields
        $data = array("bucketId" => $this->config->item('bucket_id')); // The ID of the bucket you want to upload to

        $post_fields = json_encode($data);
        curl_setopt($session, CURLOPT_POSTFIELDS, $post_fields);

        // Add headers
        $headers = array();
        $headers[] = "Authorization: " . $datosAutorizacion->authorizationToken;
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($session, CURLOPT_POST, true); // HTTP POST
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);  // Receive server response
        $server_output = curl_exec($session); // Let's do this!
        curl_close($session); // Clean up
        header('Content-Type: application/json');

        return json_decode($server_output); // Tell me about the rabbits, George!
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
        $datosAutorizacion = json_decode(curl_exec($session));
        curl_close($session);
        return $datosAutorizacion;
    }

}
