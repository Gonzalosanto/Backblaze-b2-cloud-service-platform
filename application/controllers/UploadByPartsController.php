<?php 


class UploadByPartsController extends CI_Controller {

   
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model(array('subida_php_backblaze_model'));
    }

    public function subirArchivoPartido(){
        echo "Inicio de proceso por partes ";
        $cantidad_archivos_subir = $this->subida_php_backblaze_model->contar_archivos_a_subir();
        if ($cantidad_archivos_subir > 0) {
            $archivo_subir = $this->subida_php_backblaze_model->seleccionar_archivo_a_subir();
            $file_name= $archivo_subir[0]['file_name_original'];
            $id= $archivo_subir[0]['id'];
            $this->subidaPorPartes($id, $file_name);
            echo "Fin de proceso por partes";
            die();
        }else{
            die();
        }

    }

    public function subidaPorPartes($id, $file_name){  //EJECUCION DE SUBIDA POR PARTES

        
        $this->getUploadURLPart($file_name);
        $this->uploadPart($file_name);
        $this->finishLargeFiles();
        echo "Subido";



    }

    public function startLargeFiles($file_name){ //ejecutarlo primero

        $datosAutorizacion=$this->autorization();
        $file_name = $namefile; // File to be uploaded
        $bucket_id = $this->config->item('bucket_id'); // Provided by b2_create_bucket, b2_list_buckets
        $content_type = "application/octet-stream"; // The content type of the file. See b2_start_large_file documentation for more information.
        // Construct the JSON to post
        $data = array("fileName" => $file_name, "bucketId" => $bucket_id, "contentType" => $content_type);
        $post_fields = json_encode($data);
        
        // Setup headers
        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = "Authorization: " . $datosAutorizacion->authorizationToken;
        
        // Setup curl to do the post
        $session = curl_init($api_url . "/b2api/v2/b2_start_large_file");
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);  // Add headers
        curl_setopt($session, CURLOPT_POSTFIELDS, $post_fields); 
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true); // Receive server response
        // Post the data
        $server_output = curl_exec($session);
        curl_close ($session);
        return $server_output;

    }

    public function getUploadURLPart($file_name){ //Ejecutar segundo                
      
        $responseStartLargeFile = $this->startLargeFiles($file_name);

        $file_id = $responseStartLargeFile->fileId; // Obtained from b2_start_large_file
        $account_auth_token = $datosAutorizacion->authorizationToken; // Obtained from b2_authorize_account
        $data = array("fileId" => $file_id);
        $post_fields = json_encode($data);

        // Setup headers
        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = "Authorization: " . $account_auth_token;

        //  Setup curl to do the post
        $session = curl_init($datosAutorizacion->apiUrl . "/b2api/v2/b2_get_upload_part_url");
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);  // Add headers
        curl_setopt($session, CURLOPT_POSTFIELDS, $post_fields); 
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true); // Receive server response
        $server_output = curl_exec($session);
        curl_close ($session);
        return $server_output;
    }

    

    public function uploadPart($file_name){ //SUBIDA DE file POR PARTES

        
        function myReadFile($curl_rsrc, $file_pointer, $length) {
            return fread($file_pointer, $length);
        }
        
        // Upload parts
        $minimum_part_size = 50 * (1000 * 1000); // Obtained from b2_authorize_account. The default is 100 MB
        $local_file = $this->config->item('dir_uploads') . $file_name;
        $local_file_size = filesize($local_file);
        $total_bytes_sent = 0;
        $bytes_sent_for_part = 0;
        $bytes_sent_for_part = $minimum_part_size;
        $sha1_of_parts = Array();
        $part_no = 1;
        $file_handle = fopen($local_file, "r");
        while($total_bytes_sent < $local_file_size) {
        
            // Determine the number of bytes to send based on the minimum part size	
            if (($local_file_size - $total_bytes_sent) < $minimum_part_size) {
                $bytes_sent_for_part = ($local_file_size - $total_bytes_sent);
            }
        
            // Get a sha1 of the part we are going to send	
            fseek($file_handle, $total_bytes_sent);
            $data_part = fread($file_handle, $bytes_sent_for_part);
            array_push($sha1_of_parts, sha1($data_part));
            fseek($file_handle, $total_bytes_sent);
            
            // Send it over th wire
            $session = curl_init($this->config->item('url_upload'));
            // Add headers
            $headers = array();
            $headers[] = "Accept: application/json";
            $headers[] = "Authorization: " . $datosAutorizacion->authorizationToken;
            $headers[] = "Content-Length: " . $bytes_sent_for_part;
            $headers[] = "X-Bz-Part-Number: " . $part_no;
            $headers[] = "X-Bz-Content-Sha1: " . $sha1_of_parts[$part_no - 1];
            curl_setopt($session, CURLOPT_POST, true);
            curl_setopt($session, CURLOPT_HTTPHEADER, $headers);  // Add headers
            curl_setopt($session, CURLOPT_INFILE, $file_handle);
            curl_setopt($session, CURLOPT_INFILESIZE, (int)$bytes_sent_for_part);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true); // Receive server response
            curl_setopt($session, CURLOPT_READFUNCTION, "myReadFile");
            $server_output = curl_exec($session);
            curl_close ($session);
            return $server_output . "\n";
            
            
            // Prepare for the next iteration of the loop
            $part_no++;
            $total_bytes_sent = $bytes_sent_for_part + $total_bytes_sent;
            $read_file_bytes_read = 0;
        }
        
        fclose($file_handle);
        return $sha1_of_parts;
        
        
    }

    

    public function finishLargeFiles(){ //Al final ejecutarlo
       

                        
        $api_url = $datosAutorizacion->apiUrl; // Obtained from b2_authorize_account
        $file_id = $responseLargeFile->fileId; // Obtained from b2_start_large_file
        $sha1_of_parts = $shaArray;  // See b2_upload_part 
        $session = curl_init($api_url . "/b2api/v2/b2_finish_large_file");

        //POST FIELDS


        // Add headers
        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = "Authorization: " . $datosAutorizacion->authorizationToken;

        // Send over the wire
        curl_setopt($session, CURLOPT_POSTFIELDS, $post_fields); 
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);  // Add headers
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true); // Receive server response
        $server_output = curl_exec($session);
        curl_close ($session);
        print $server_output;

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