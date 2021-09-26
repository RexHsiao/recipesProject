<?php

class UserModel extends CI_Model{
    public function __construct(){
        $this->load->database();

    }

    public function register($username, $enc_password=FALSE, $email, $otp=FALSE){
        $data = array(
            'email' => $email,
            'username' => $username,
            'password' => $enc_password,
            'otp' => $otp
        );

        //Insert user
        return $this->db->insert('users', $data);
    }

    public function check_username_exists($username){
        $query = $this->db->get_where('users', array('username'=> $username));

        if(empty($query->row_array())){
            return true;
        }else{
            return false;
        }
    }

    public function check_email_exists($email){
        $query = $this->db->get_where('users', array('email'=> $email));

        if(empty($query->row_array())){
            return true;
        }else{
            return false;
        }
    }
    
    public function login($username=FALSE, $password=FALSE, $email=FALSE, $otp=FALSE){
        if($username){
            $this->db->where('username', $username);
        }
        if($password){
            $this->db->where('password', $password);
        }
        if($email){
            $this->db->where('email', $email);
        }
        if($otp){
            $this->db->where('otp', $otp);
        }
        $query = $this->db->get('users');

        if($query->num_rows() == 1){
            return $query->row(0)->id;
        }else{
            return false;
        }
    }

    public function get_user($user_id){
        $query = $this->db->get_where('users', array('id' => $user_id));
        return $query->row_array();
    }

    public function get_user_by($email){
        $query = $this->db->get_where('users', array('email' => $email));
        return $query->result_array();
    }

    public function reset_psw_as_token($token, $email){
        $this->db->where('email', $email);
        $this->db->update('users', array('password' => $token));
    }

    public function reset_password($token, $password){ 
        $this->db->where('password', $token);
        $this->db->update('users', array('password' => $password));
    }

    public function update_user(){
        $data = array(
            'email' => $this->input->post('email'),
            'phone' => $this->input->post('phone'),
            'birthdate' => $this->input->post('birthdate'),
        );
        $this->db->where('id', $this->input->post('user_id'));
        $this->db->update('users', $data);
        return $this->get_user($this->input->post('user_id'));
    }

    public function update_user_img($user_id, $image){
        $data = array(
            'image' => $image
        );
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
    }

    public function update_verification($id){
        $data = array(
            'otp' => '0',
            'status' => 'active',
        );
        $this->db->where('id', $id);

        $this->db->update('users', $data);
    }
}

?>