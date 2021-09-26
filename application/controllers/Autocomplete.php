<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autocomplete extends CI_Controller {
    
    public function get_search_posts(){
        $output = '';
        $keyword = $this->input->post('keyword');
        $results = $this->PostModel->get_posts_by($keyword);
        $output = '<ul class="list-unstyled txtpost">';
        if(sizeOf($results) > 0){
            foreach($results as $result){
                $output .= '<li class="dropdown-item search_li" id="'.$result['id'].'">'.$result['title'].'</li>';
            }
        }else{
            $output .= '<li class="dropdown-item search_li">Recipe Not Found</li>';
        }
        $output .= '</ul>';
        echo $output;
    }
}
    
?>