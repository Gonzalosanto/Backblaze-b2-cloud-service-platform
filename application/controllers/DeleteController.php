<?php

class DeleteController extends CI_Controller {

    /**
     * Manage __construct
     *
     * @return Response
     */
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
    }

    public function deleteFile() {

        $datosAutorizacion = $this->autorization();
        $api_url = $datosAutorizacion->apiUrl; // From b2_authorize_account call
        $auth_token = $datosAutorizacion->authorizationToken; // From b2_authorize_account call

        $session = curl_init($api_url . "/b2api/v2/b2_delete_file_version");

        // Add post fields
        $data = array("fileId" => $this->input->post('fileId'), "fileName" => $this->input->post('fileName'));
        $post_fields = json_encode($data);
        curl_setopt($session, CURLOPT_POSTFIELDS, $post_fields);

        // Add headers
        $headers = array();
        $headers[] = "Authorization: " . $auth_token;
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($session, CURLOPT_POST, true); // HTTP POST
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);  // Receive server response
        $server_output = curl_exec($session); // Let's do this!
        curl_close($session); // Clean up
        echo ($server_output); // Tell me about the rabbits, George!
        die();
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

?>
