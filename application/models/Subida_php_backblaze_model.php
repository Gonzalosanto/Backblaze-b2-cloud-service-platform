<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subida_php_backblaze_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function listar_archivos_a_subir() {
        $this->db->select('*');
        $this->db->from('files_estatus');
        $this->db->join('files', 'files.id = files_estatus.files_id');
        $this->db->where('estatus_id', 2);
        $this->db->where('files.deleted', 0);
        $this->db->where('files_estatus.estatus_actual', 1);
      //  $this->db->where('files_estatus.estatus_backblaze', 0);
        $this->db->order_by('files.date_created', 'ASC');
//        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        }
    }
    
    public function seleccionar_archivo_a_subir() {
        $this->db->select('*');
        $this->db->from('files_estatus');
        $this->db->join('files', 'files.id = files_estatus.files_id');
        $this->db->where('estatus_id', 2);
        $this->db->where('files.deleted', 0);
        $this->db->where('files_estatus.estatus_actual', 1);
       // $this->db->where('files_estatus.estatus_backblaze', 0);
        $this->db->order_by('files.date_created', 'ASC');
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result_array();
        }
    }

    public function contar_archivos_a_subir() {
        $this->db->select('*');
        $this->db->from('files_estatus');
        $this->db->join('files', 'files.id = files_estatus.files_id');
        $this->db->where('estatus_id', 2);
        $this->db->where('files.deleted', 0);
        $this->db->where('files_estatus.estatus_actual', 1);
      //  $this->db->where('files_estatus.estatus_backblaze', 0);
        $this->db->order_by('files.date_created', 'ASC');
        return $this->db->count_all_results();
    }

    public function inicio_subida_php_backblaze($id_file) {

        // Actualizando es estatus actual
        $data["estatus_actual"] = 0;
        $this->db->where('files_id', $id_file);
        $this->db->where('estatus_id', 2);
        $this->db->where('estatus_actual', 1);
        $this->db->update('files_estatus', $data);

        unset($data); //Borrando variable
        // Insertando un nuevo estatus
        $data["files_id"] = $id_file;
        $data["estatus_id"] = 3;
        $this->db->insert('files_estatus', $data);
    }
    
    

    public function final_subida_php_backblaze($id_file) {

        // Actualizando es estatus actual
        $data["estatus_actual"] = 0;
        $this->db->where('files_id', $id_file);
        $this->db->where('estatus_id', 3);
        $this->db->where('estatus_actual', 1);
        $this->db->update('files_estatus', $data);

        unset($data); //Borrando variable
        // Insertando un nuevo estatus
        $data["files_id"] = $id_file;
        $data["estatus_id"] = 4;
        $this->db->insert('files_estatus', $data);
    }

    public function inicio_eliminar_archivo_php($id_file) {

        // Actualizando es estatus actual
        $data["estatus_actual"] = 0;
        $this->db->where('files_id', $id_file);
        $this->db->where('estatus_id', 4);
        $this->db->where('estatus_actual', 1);
        $this->db->update('files_estatus', $data);

        unset($data); //Borrando variable
        // Insertando un nuevo estatus
        $data["files_id"] = $id_file;
        $data["estatus_id"] = 5;
        $this->db->insert('files_estatus', $data);
    }

    public function final_eliminar_archivo_php($id_file) {
        // Actualizando es estatus actual
        $data["estatus_actual"] = 0;
        $this->db->where('files_id', $id_file);
        $this->db->where('estatus_id', 5);
        $this->db->where('estatus_actual', 1);
        $this->db->update('files_estatus', $data);

        unset($data); //Borrando variable
        // Insertando un nuevo estatus
        $data["files_id"] = $id_file;
        $data["estatus_id"] = 6;
        $this->db->insert('files_estatus', $data);
    }
    

//    public function ver_articulo($id) {
//        $this->db->select('*');
//        $this->db->from('articles');
//        $this->db->where('id', $id);
//
//        $query = $this->db->get();
//        if ($query->num_rows() >= 1) {
//            return $query->result_array();
//        }
//    }
//
//    public function editar_articulo($data) {
////        $data['date_added'] = date('Y-m-d H:i:s');
//        $data['published'] = 'no';
//
//        if ($data['optionsRadios'] == 'Copita Menstrual') {
//            $data['link1_titulo'] = 'Copita Menstrual';
//            $data['link1_link'] = 'https://www.copitamenstrual.com';
//            $data['link2_titulo'] = 'https://www.copitamenstrual.com';
//            $data['link2_link'] = null;
//        } else {
//            $data['link1_titulo'] = $data['nombre_autor'];
//            $data['link1_link'] = $data['titulo_autor'];
//            $data['link2_titulo'] = $data['url_autor'];
//            $data['link2_link'] = null;
//        }
//        unset($data['optionsRadios']);
//        unset($data['nombre_autor']);
//        unset($data['titulo_autor']);
//        unset($data['url_autor']);
//        unset($data['contenido_hidden']);
//
//        $this->db->where('id', $data['id']);
//        $this->db->update('articles', $data);
//    }
//
//    public function eliminar_articulo($id) {
//        $this->db->delete('articles', array('id' => $id));
//    }
//
//    public function publicar_articulo($id) {
////        $data['date_added'] = date('Y-m-d H:i:s');
//        $data['published'] = 'yes';
//        $this->db->where('id', $id);
//        $this->db->update('articles', $data);
//    }
//
//    public function despublicar_articulo($id) {
////        $data['date_added'] = date('Y-m-d H:i:s');
//        $data['published'] = 'no';
//        $this->db->where('id', $id);
//        $this->db->update('articles', $data);
//    }
//
//    public function listar_articulo($row = 0, $row_pages = 6, $categoria = null) {
//        $this->db->select('id , image , desc , slug , date_added , title, link1_titulo, link1_link, link2_titulo, link2_link');
//        $this->db->where('published', 'yes');
//        if ($categoria != null) {
//            $this->db->where('categoria', $categoria);
//        }
//        $this->db->order_by('id', 'desc');
//        $query = $this->db->get('articles', $row_pages, $row);
//        if ($query->num_rows() >= 1) {
//            return $query->result_array();
//        }
//    }
//
//    public function contar_articulos($categoria = null) {
//        $this->db->where('published', 'yes');
//        if ($categoria != null) {
//            $this->db->where('categoria', $categoria);
//        }
//        $query = $this->db->get('articles');
//        if ($query->num_rows() >= 1) {
//            return $query->result_array();
//        }
//    }
//
//    public function registrar_visita($slug, $ip_address) {
//        $this->db->select('*');
//        $this->db->from('articles');
//        $this->db->where('slug', $slug);
//        $this->db->where('published', 'yes');
//        $query = $this->db->get();
//        $articulo_atributo = $query->result_array();
//        $id_articulo = $articulo_atributo[0]['id'];
//        $data = array(
//            'article_id' => $id_articulo,
//            'fecha' => date('Y-m-d'),
//            'ip' => $ip_address
//        );
//        $this->db->insert('articuloslog', $data);
//    }
//
//    public function subir_foto($nombre_foto, $id_article = null) {
//
//        $data = array(
//            'video' => null,
//            'video_lista' => null,
//            'image' => $nombre_foto,
//        );
//
//        if ($id_article != null) {
//            $this->db->where('id', $id_article);
//        } else {
//            $this->db->select_max('id');
//            $query = $this->db->get('articles');
//            if ($query->num_rows() >= 1) {
//                $id_max = $query->result_array();
//                $this->db->where('id', $id_max[0]['id']);
//            }
//        }
//
//        $this->db->update('articles', $data);
//    }
//
//    public function categoria_articulo() {
//        $this->db->distinct();
//        $this->db->select('categoria');
//        $this->db->from('articles');
//        $query = $this->db->get();
//        if ($query->num_rows() >= 1) {
//            return $query->result_array();
//        }
//    }
//
//    public function subir_video($data, $id = null) {
//        $this->db->where('id', $id);
//        $this->db->update('articles', $data);
//    }
//
//    public function list_all($categoria = null) {
//        $this->db->select('*');
////        $this->db->where('published', 'yes');
//        if ($categoria != null) {
//            $this->db->where('categoria', $categoria);
//        }
//        $this->db->order_by('id', 'desc');
//        $query = $this->db->get('articles');
//        if ($query->num_rows() >= 1) {
//            return $query->result_array();
//        }
//    }
}
