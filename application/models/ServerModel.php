<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ServerModel extends CI_Model {

        public function __construct() {
            parent::__construct();        
            $this->load->database();
        }

            
        public function subir_archivo($data) { 
                  
            //INSERTAR ARCHIVO A DB            
            $this->db->insert('files', $data);
        }

        
        public function listar_archivos_id($filename) {
            $this->db->select('id');
            $this->db->from('files');
            $this->db->where('file_name_original', $filename);
            $this->db->limit(1);
            $query = $this->db->get();
    //SELECT * FROM space_plataforma.files    
    //WHERE file_name_original=filename    
    //LIMIT 1
            if ($query->num_rows() >= 1) {
                return $query->row()->id;
            }
        }

       

        public function new_estatus($id_file){     
            //Insertar Estatus Actual
            
            $data["files_id"] = $id_file;
            $data["estatus_id"] = 1;
            $this->db->insert('files_estatus', $data);
        }


        public function inicio_subida_local_php($id_file) {

            // Actualizando es estatus actual
            $data["estatus_actual"] = 0;
            $this->db->where('files_id', $id_file);
            $this->db->where('estatus_id', 1);
            $this->db->where('estatus_actual', 1);
            $this->db->update('files_estatus', $data);

            unset($data);
            $data["files_id"] = $id_file;
            $data["estatus_id"] = 2;
            $this->db->insert('files_estatus', $data);  
           
           
        }
        

}