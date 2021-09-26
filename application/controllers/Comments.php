<?php
    class comments extends CI_Controller{
        public function create($post_id){
            $this->CommentModel->create_comment($post_id);
            $query = $this->CommentModel->get_comments($post_id);
            echo json_encode($query);
        }

        public function delete(){
            // echo $this->input->post('comment_id');
            $this->CommentModel->delete();
        }

        public function load_comments($post_id){
            $output = '';
            $results = $this->CommentModel->get_comments($post_id,$this->input->post('limit'), $this->input->post('start'));
            if(sizeOf($results) > 0){
                foreach($results as $result){
                    $id = $result['id'];
                    $username = $result['name'];
                    $user_id = $result['user_id'];
                    $user = $this->UserModel->get_user($user_id);
                    $user_image = $user['image'];
                    $user_info;
                    if($user_image){
                        $user_info = '<img src="https://infs3202-0f5e381e.uqcloud.net/recipesProject/assets/img/users/'.$user_image.'" id="uploaded_image_home" class="img-responsive img-circle" />';
                    }else{
                        $user_info = $username[0];
                    };
                    $create_at_date = explode(" ",explode(":",$result['create_at'])[0])[0];
                    $create_at_time = explode(" ",explode(":",$result['create_at'])[0])[1].':'.explode(":",$result['create_at'])[1];
                    $body = $result['body'];
                    $output .= '
                        <div class="d-flex justify-content-between comment-box" id="comment-'.$id.'">
                            <div class="d-flex justify-content-start">
                                <div class="user-img d-flex align-items-center">'
                                    .$user_info.
                                '</div>
                                <div class="comment-info"><h5>'.$username.' <small>'.$create_at_date.' '.$create_at_time.'</small></h5><h6>'.$body.'</h6></div>
                            </div>
                    ';
                    if($user_id == $this->session->userdata("user_id")){
                        $output .= '
                            <div class="d-flex align-items-center">
                                <span style="cursor: pointer" id="delete_comment" onclick="delete_comment('.$id.')">
                                    <img src="https://infs3202-0f5e381e.uqcloud.net/recipesProject/assets/img/elements/trash.png" width="30vw" height="30vw"/>
                                </span>
                            </div>
                        ';
                    }
                    $output .= '</div>';
                };
            }
            echo $output;
        }
    }
?>
