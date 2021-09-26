<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller {

	public function index($offset = 0){
        //Pagination Config
        $config['base_url'] = base_url().'posts/index/';
        $config['total_rows'] = $this->db->count_all('posts');
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        $config['attributes'] = array('class' => 'pagination-links');

        //Init Pagination
        $this->pagination->initialize($config);

        $data['title'] = 'Explore';
        $data['posts'] = $this->PostModel->get_posts(FALSE, $config['per_page'], $offset);
        $data['popular_posts'] = $this->PostModel->get_popular_posts();

        $this->load->view('templates/header');
        $this->load->view('posts/index', $data);
        $this->load->view('templates/footer');
    }

    public function view($id = NULL){
        $data['post'] = $this->PostModel->get_posts($id);
        $post_id = $data['post']['id'];
        $post_user_id = $data['post']['user_id'];
        $data['comments'] = $this->CommentModel->get_comments($post_id);
        $data['creator'] = $this->UserModel->get_user($post_user_id);
        $data['likes'] = $this->PostModel->get_post_likes($id);
        $data['collectors'] = $this->PostModel->get_post_collections($id);
        if(empty($data['post'])){
            show_404();
        }
        $this->PostModel->record_visits($id);
        $data['title'] = $data['post']['title'];
        $this->load->view('templates/header');
        $this->load->view('posts/view', $data);
        $this->load->view('templates/footer');
    }

    public function create(){
        $data['title'] = 'Create';
        
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('body', 'recipe description', 'required');

        $ingre_count = $this->input->post('ingre_count');
        $steps_count = $this->input->post('steps_count');

        $ingre_info = array();
        $steps = array();
        
        for($x=1;$x<=intval($ingre_count);$x++){
            $name = $this->input->post('ingre_name'.$x);
            $portion = $this->input->post('ingre_portion'.$x);
            $ingre_info[$name] = $portion;
        }
        for($x=1;$x<=intval($steps_count);$x++){
            $item = $this->input->post('step_content'.$x);
            array_push($steps, $item);
        }

        $ingre_info_j = json_encode($ingre_info, true);
        $steps_j = json_encode($steps, true);
        

        if($this->form_validation->run() === FALSE){
            $this->load->view('templates/header');
            $this->load->view('posts/create', $data);
            $this->load->view('templates/footer');
        }else{
            
            $this->PostModel->create_post($ingre_info_j, $steps_j);

            // set message
			$this->session->set_flashdata('post_created', 'Your post has been created');
            
            redirect('posts');
        }
    }

    public function delete($id){
        if(!$this->session->userdata('logged_in')){
            redirect('users/login');
        }

        $data['post'] = $this->PostModel->get_posts($id);

        if($this->session->userdata('user_id' != $data['post']['user_id'])){
            redirect('posts');
        }
        $this->PostModel->delete_post($id);

        // set message
		$this->session->set_flashdata('post_deleted', 'Your post has been deleted');

        redirect('posts');
    }

    public function edit($id){
        if(!$this->session->userdata('logged_in')){
            redirect('users/login');
        }

        $data['post'] = $this->PostModel->get_posts($id);

        if($this->session->userdata('user_id' != $data['post']['user_id'])){
            redirect('posts');
        }

        if(empty($data['post'])){
            show_404();
        }

        $data['title'] = 'Edit Post';
        $this->load->view('templates/header');
        $this->load->view('posts/edit', $data);
        $this->load->view('templates/footer');
    }

    public function update(){
        $data['title'] = 'edit post';
        $id = $this->input->post('id');
        $data['post'] = $this->PostModel->get_posts($id);

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('body', 'recipe description', 'required');

        //pack data
        $ingre_count = $this->input->post('ingre_count');
        $steps_count = $this->input->post('steps_count');

        $ingre_info = array();
        $steps = array();
        
        for($x=1;$x<=intval($ingre_count);$x++){
            $name = $this->input->post('ingre_name'.$x);
            $portion = $this->input->post('ingre_portion'.$x);
            $ingre_info[$name] = $portion;
        }
        for($x=1;$x<=intval($steps_count);$x++){
            $item = $this->input->post('step_content'.$x);
            array_push($steps, $item);
        }

        $ingre_info_j = json_encode($ingre_info, true);
        $steps_j = json_encode($steps, true);
        //end of packing data


        if($this->form_validation->run() === FALSE){
            $this->load->view('templates/header');
            $this->load->view('posts/edit', $data);
            $this->load->view('templates/footer');
        }else{
            
            $this->PostModel->update_post($ingre_info_j, $steps_j);
            
            // set message
            $this->session->set_flashdata('post_updated', 'Your post has been updated');

            redirect('posts');
        }
    }

    public function load_posts(){
        $output='';
        $results = $this->PostModel->get_posts(FALSE, $this->input->post('limit'), $this->input->post('start'),'posts.id');
        if(sizeOf($results) > 0){
            foreach($results as $result){
                $title = $result['title'];
                $post_id = $result['id'];
                $post_image = $result['post_image'];
                $output .= 
                '
                    <br>
                    <div class="post">
                        <a href="https://infs3202-0f5e381e.uqcloud.net/recipesProject/index.php/posts/'.$post_id.'" class="btn each-post">
                            <div>
                                <div class="post-title"><h5>'.$title.'</h5></div>
                                <img class="post-thumb" width="200px" height="200px" src="https://infs3202-0f5e381e.uqcloud.net/recipesProject/assets/img/posts/'.$post_image.'">
                            </div>
                        </a>
                    </div>
                ';
            }
        }
        echo $output;
    }

    public function like_post($id){
        
        $this->PostModel->like_post($id);
        
        echo $this->PostModel->count_post_like($id);
    }

    public function dislike_post($id){
        
        $this->PostModel->dislike_post($id);
        
        echo $this->PostModel->count_post_like($id);
    }

    public function collect_post($id){
        
        $this->PostModel->collect_post($id);
        
        echo $this->PostModel->count_post_like($id);
    }

    public function uncollect_post($id){
        
        $this->PostModel->uncollect_post($id);
        
        echo $this->PostModel->count_post_like($id);
    }

    public function upload_img(){
		if(isset($_POST['image'])){
			
			$data = $_POST['image'];
			$image_array_1 = explode(";", $data);
			$image_array_2 = explode(",", $image_array_1[1]);
			$data = base64_decode($image_array_2[1]);
			$image_name = './assets/img/posts/' . time() . '.png';
			file_put_contents($image_name, $data);
			$image = explode("/", $image_name)[4];
			
			
			echo $image;
		}
	}

    public function search($post_keyword){
        $data['title'] = 'search';
        if(is_numeric($post_keyword)){
            $data['posts'] = $this->PostModel->get_posts_by(FALSE, $post_keyword);
            $data['keyword'] = $this->PostModel->get_posts($post_keyword)['title'];
        }else{
            $post_keyword = str_replace("%20", " ", $post_keyword);
            $data['posts'] = $this->PostModel->get_posts_by($post_keyword, FALSE);
            $data['keyword'] = $post_keyword;
        }
        

        
        $this->load->view('templates/header');
        $this->load->view('posts/search', $data);
        $this->load->view('templates/footer');
    }
    
}