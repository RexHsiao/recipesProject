<?php
    class CategoryModel extends CI_Model{
        public function __construct(){
            $this->load->database();
        }

        public function create_category(){
            $data = array(
                'name' => $this->input->post('name')
            );

            return $this->db->insert('categories', $data);
        }
    }
?>