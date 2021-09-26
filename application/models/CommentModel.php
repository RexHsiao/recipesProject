<?php
    class CommentModel extends CI_Model{
        public function __construct(){
            $this->load->database();
        }

        public function create_comment($post_id){
            $data = array(
                'post_id' => $post_id,
                'user_id' => $this->input->post('user_id'),
                'name' => $this->input->post('username'),
                'body' => $this->input->post('body'),
            );
            
            return $this->db->insert('comments', $data);
        }

        public function get_comments($post_id, $limit= FALSE, $offset=FALSE){
            if($limit){
                $this->db->limit($limit, $offset);
            }
            $this->db->order_by('comments.id', 'DESC');
            
            $query = $this->db->get_where('comments', array('post_id' => $post_id));
            
            return $query->result_array();
        }

        public function delete(){
            $id = $this->input->post('comment_id');
            $this->db->where('id', $id);
            $this->db->delete('comments');
        }

    }