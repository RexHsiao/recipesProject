<?php
    class PostModel extends CI_Model{
        public function __construct(){
            $this->load->database();

        }

        public function get_posts($id = FALSE, $limit = FALSE, $offset = FALSE, $sortby = 'posts.id'){
            if($limit){
                $this->db->limit($limit, $offset);
            }
            if($id === FALSE){
                $this->db->order_by($sortby, 'DESC');
                $query = $this->db->get('posts');
                return $query->result_array();
            }

            $query = $this->db->get_where('posts', array('id' => $id));
            return $query->row_array();
        }

        public function create_post($ingre_info, $steps){
            $slug = url_title($this->input->post('title'));

            $data = array(
                'title' => $this->input->post('title'),
                'slug' => $slug,
                'body' => $this->input->post('body'),
                'portion' => $this->input->post('portion'),
                'cook_time' => $this->input->post('cook_time'),
                'user_id' => $this->session->userdata('user_id'),
                'post_image' => $this->input->post('cover_image'),
                'ingre_info' => $ingre_info,
                'steps' => $steps
            );
            return $this->db->insert('posts', $data);

        }

        public function delete_post($id){
            $this->db->where('id', $id);
            $this->db->delete('posts');
            return true;
        }

        public function update_post($ingre_info, $steps){
            $slug = url_title($this->input->post('title'));
            $data = array(
                'title' => $this->input->post('title'),
                'slug' => $slug,
                'body' => $this->input->post('body'),
                'portion' => $this->input->post('portion'),
                'cook_time' => $this->input->post('cook_time'),
                'user_id' => $this->session->userdata('user_id'),
                'post_image' => $this->input->post('cover_image'),
                'ingre_info' => $ingre_info,
                'steps' => $steps
            );
            
            $this->db->where('id', $this->input->post('id'));
            return $this->db->update('posts', $data);
        }

        public function get_popular_list(){
            $this->db->select('post_id, count(*) as count');
            $this->db->from('visits');
            $this->db->group_by('post_id');
            $this->db->order_by('count', 'DESC');
            $this->db->limit(4);
            $query = $this->db->get();
            return $query->result_array();
        }

        public function get_popular_posts(){
            $items = $this->get_popular_list();
            $results = array();
            foreach($items as $item){
                $x = $this->db->get_where('posts', array('id' => $item['post_id']));
                $post = $x->result_array();
                $results = array_merge($results, $post);
            }
            return $results;
        }

        public function like_post($id){
            $data = array(
                'post_id' => $id,
                'user_id' => $this->input->post('user_id'),
            );
            return $this->db->insert('likes', $data);
        }

        public function dislike_post($id){
            $data = array(
                'post_id' => $id,
                'user_id' => $this->input->post('user_id'),
            );
            return $this->db->delete('likes', $data);
        }

        public function count_post_like($id){
            $query = $this->db->get_where('likes', array('post_id' => $id));
            $count = $query->result_array();
            return count($count);
        }

        public function get_post_likes($id){
            $query = $this->db->get_where('likes', array('post_id' => $id));
            
            return $query->result_array();
        }

        public function collect_post($id){
            $data = array(
                'post_id' => $id,
                'user_id' => $this->input->post('user_id'),
            );
            return $this->db->insert('collections', $data);
        }

        public function uncollect_post($id){
            $data = array(
                'post_id' => $id,
                'user_id' => $this->input->post('user_id'),
            );
            return $this->db->delete('collections', $data);
        }

        public function get_post_collections($id){
            $query = $this->db->get_where('collections', array('post_id' => $id));
            
            return $query->result_array();
        }

        public function get_mylist_posts($user_id, $limit=FALSE, $offset=FALSE, $mode=FALSE){
            if($limit){
                $this->db->limit($limit, $offset);
            }
            if($mode == 'myupload'){
                $this->db->order_by('posts.id', 'DESC');
                $this->db->where('user_id', $user_id);
                $query = $this->db->get('posts');
                
                return $query->result_array();
            }

            $this->db->order_by('collections.create_at', 'DESC');
            $this->db->from('posts');
            $this->db->where('collections.user_id', $user_id);
            $this->db->join('collections', 'collections.post_id=posts.id', 'Left');
            $query = $this->db->get();
            
            return $query->result_array();
        }

        public function record_visits($id){
            $data = array(
                'post_id' => $id,
                'user_id' => $this->session->userdata('user_id'),
            );
            if(!$this->session->userdata('user_id')){
                unset($data['user_id']);
            }
            return $this->db->insert('visits', $data);
        }

        public function get_posts_by($keyword = FALSE, $post_id = FALSE){
            $keywords = explode(" ", $keyword);
            $this->db->order_by('id', 'DESC');

            if(!$keyword){
                $keyword = $this->get_posts($post_id)['title'];
                $this->db->like("title", $keyword);
                return $this->db->get('posts')->result_array();
            }
            
            foreach($keywords as $key){
                $this->db->like("title", $key);
            }
            return $this->db->get('posts')->result_array();
        }

    }
?>