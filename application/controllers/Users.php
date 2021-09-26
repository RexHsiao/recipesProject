<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function register(){
		$data['title'] = 'Sgin Up';

		$this->form_validation->set_rules('username', 'Username', 'required|callback_check_username_exists');
		$this->form_validation->set_rules('email', 'Email', 'required|callback_check_email_exists');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('password2', 'Confirm Password', 'matches[password]');

		if($this->form_validation->run() === FALSE){
			$this->load->view('templates/header');
			$this->load->view('users/register', $data);
			$this->load->view('templates/footer');
		}else{
			// Encript password
			$enc_password = md5($this->input->post('password'));
			$email = $this->input->post('email');
			$username = $this->input->post('username');
			$otp = rand(10000, 99999);
			$this->UserModel->register($username, $enc_password, $email, $otp);
			$this->verify_code_send($email, $otp);
			// set message
			$this->session->set_flashdata('user_registered', 'You are now registered! But still need to verify through OTP code.');
			
			redirect('users/otp_verification');
			
		}
	}

	public function otp_verification(){
		$data['title'] = 'Account Verification';

		$this->load->view('templates/header');
		$this->load->view('users/otpVerification', $data);
		$this->load->view('templates/footer');
	}

	public function active_acc(){
		$otp = $this->input->post('otp-code');
		$user_id = $this->UserModel->login(FALSE, FALSE, FALSE, $otp);
		if($user_id){
			$this->UserModel->update_verification($user_id);
			$this->session->set_flashdata('user_registered', 'Your account has been activated');
			redirect('users/login');
		}else{
			$this->session->set_flashdata('login_failed', 'Invalid OTP code');
			redirect('users/otp_verification');
		}
	}

	public function verify_code_send($email, $otp){
		
		$to = $email;
		$subject = "Email Verification";
		$message = "Your verification OTP code: ".$otp;
		$message = wordwrap($message, 70);

		$mail = $this->email;

		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'mailhub.eait.uq.edu.au'; 
		$config['smtp_port'] = '25';
		$config['mailtype'] = 'html';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['newline'] = "\r\n"; 

		//init email function
		$mail->initialize($config);
		try {
			
			//Recipients
			$mail->from('f9281056@gmail.com', 'RecipeBlog');
			$mail->to($to);     //Add a recipient
			$mail->subject($subject);
			$mail->message($message);
			
		
			$mail->send();
			echo $otp;
		} catch (Exception $e) {
			echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
			
		
	}

	public function login(){
		$data['title']= "Sign in";
		
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

		$clientID = '422121774358-55cpkosr2sbeu20ks9r610fqvic3ea9e.apps.googleusercontent.com';
		$clientSecret = 'bBbdm31pfQi7q7eyrtTGn8Rq';
		$redirectUri = 'https://infs3202-0f5e381e.uqcloud.net/recipesProject/users/login';

		require_once "vendor/autoload.php";

		$client = new Google_Client();
		$client->setClientID($clientID);
		$client->setClientSecret($clientSecret);
		$client->setRedirectUri($redirectUri);
		$client->addScope('profile');
		$client->addScope('email');

		$loginURL = $client->createAuthUrl();
		$data['loginURL'] = $loginURL;

		//get the authorization code
		$code = isset($_GET['code'])?$_GET['code']:NULL;
		if(isset($code)){
			try {

				$token = $client->fetchAccessTokenWithAuthCode($code);
				$client->setAccessToken($token);
				$_SESSION['access_token'] = $token;
				
				$google_service = new Google_Service_Oauth2($client);
				$user_data = $google_service->userinfo->get();

				if(!empty($user_data['given_name'])){
					$username = $user_data['given_name'];
				}

				if(!empty($user_data['email'])){
					$email = $user_data['email'];
				}
		
			}catch (Exception $e){
				echo $e->getMessage();
			}
			$user_id = $this->UserModel->login($username, FALSE, $email);
			if($user_id){
				$user = $this->UserModel->get_user($user_id);
			}else{
				$this->UserModel->register($username, FALSE, $email);
				$user_id = $this->UserModel->login($username, FALSE, $email);
				$user = $this->UserModel->get_user($user_id);
			}
			$image = $user['image'];
			$user_data = array(
				'user_id' => $user_id,
				'username' => $username,
				'image' => $image,
				'logged_in' => true,
				'logged_in_time' => time(),
			);
			$this->session->set_userdata($user_data);

			// set message
			$this->session->set_flashdata('user_login', 'Hi '.$user_data['username'].', you are now logged in');
			redirect(base_url());

		}else if($this->form_validation->run() === FALSE || !isset($_SESSION['access_token'])){
			$this->load->view('templates/header');
			$this->load->view('users/login', $data);
			$this->load->view('templates/footer');
		}else{
			$username = $this->input->post('username');
            $password = $this->input->post('password');
			$remember = $this->input->post('remember');
			$cookie_time = (60*60*24*30);
			
			$user_id = $this->UserModel->login($username, md5($password));

			$response = $_POST["g-recaptcha-response"];
			$url = 'https://www.google.com/recaptcha/api/siteverify';
			$data = array(
				'secret' => '6Lc21M8aAAAAAKONayCgv7q4aIVZg0guzMxgOK4G',
				'response' => $_POST["g-recaptcha-response"]
			);
			$options = array(
				'http' => array (
					'method' => 'POST',
					'header'  => 'Content-type: application/x-www-form-urlencoded',
					'content' => http_build_query($data)
				)
			);

			$context  = stream_context_create($options);
			$verify = file_get_contents($url, false, $context);
			$captcha_success=json_decode($verify);

			if ($captcha_success->success==false) {
				$this->session->set_flashdata('login_failed', 'Captcha is required');
				redirect('users/login');
			} else if ($captcha_success->success==true) {
				if($user_id){
					$user = $this->UserModel->get_user($user_id);
					if($user['status'] != 'active'){
						$this->session->set_flashdata('login_failed', 'Not activated account');
						redirect('users/login');
					}else{
						$image = $user['image'];
						$user_data = array(
							'user_id' => $user_id,
							'username' => $username,
							'image' => $image,
							'logged_in' => true,
							'logged_in_time' => time(),
						);
						
						if($remember == 1){
							$cookie_time_onset = time() + $cookie_time;
							setcookie('username', $username, $cookie_time_onset, '/');
							setcookie('password', $password, $cookie_time_onset, '/');
							
						}else{
							$cookie_time_offset = time() - $cookie_time;
							setcookie('username', '', $cookie_time_offset, '/');
							setcookie('password', '', $cookie_time_offset, '/');
						}
						$this->session->set_userdata($user_data);

						// set message
						$this->session->set_flashdata('user_login', 'Hi '.$user_data['username'].', you are now logged in');
						redirect(base_url());
					}
				} else{
					$this->session->set_flashdata('login_failed', 'Invalid username or password');
					redirect('users/login');
				}
			}

			

		}

	}

	public function logout(){
		//unset user data
		$this->session->unset_userdata('logged_in');
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('user_id');

		$this->session->set_flashdata('user_logout', 'You are now logged out');
    	redirect(base_url());
	}

	//check if username exists
	function check_username_exists($username){
		$this->form_validation->set_message('check_username_exists', 'That username is taken. Please choose another one');

		if($this->UserModel->check_username_exists($username)){
			return true;
		} else{
			return false;
		}
	}

	//check if email exists
	function check_email_exists($email){
		$this->form_validation->set_message('check_email_exists', 'That email is taken. Please choose another one');

		if($this->UserModel->check_email_exists($email)){
			return true;
		} else{
			return false;
		}
	}

	public function forgetPassword(){
		$data['title'] = 'Forgot Password?';

		$this->load->view('templates/header');
		$this->load->view('users/forgetPassword', $data);
		$this->load->view('templates/footer');
	}

	public function profile($user_id){
		if(!$this->session->userdata('logged_in')){
            redirect('users/login');
        }
		$data['title'] = 'Profile';
		$data['user'] = $this->UserModel->get_user($user_id);
		$this->load->view('templates/header');
		$this->load->view('users/profile', $data);
		$this->load->view('templates/footer');
	}

	public function resetLink(){
		$email = $this->input->post('email');
		$result = $this->UserModel->get_user_by($email);

		if(sizeOf($result)>0){
			
			$token = uniqid(md5(time()));

			

			$this->UserModel->reset_psw_as_token($token, $email);

			$to = $email;
			$subject = "Reset Password Link";
			$message = "Please click on password reset link <br> <a href='".base_url('users/reset?token=').$token."'>Reset Password</a>";
			$message = wordwrap($message, 70);

			$mail = $this->email;

			$config['protocol'] = 'smtp';
			$config['smtp_host'] = 'mailhub.eait.uq.edu.au'; 
			$config['smtp_port'] = '25';
			$config['mailtype'] = 'html';
			$config['charset'] = 'iso-8859-1';
			$config['wordwrap'] = TRUE;
			$config['newline'] = "\r\n"; 

			//init email function
			$mail->initialize($config);
			try {
				
				//Recipients
				$mail->from('f9281056@gmail.com', 'RecipeBlog');
				$mail->to($to);     //Add a recipient
				$mail->subject($subject);
				$mail->message($message);
				
			
				$mail->send();
				echo "Please check your email box.";
			} catch (Exception $e) {
				echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}

			$this->session->set_flashdata('user_emailSendInfo', 'Reset link has been sent');
			
			redirect(base_url());
		}else{
			$this->session->set_flashdata('user_emailSendInfo', 'Entered email not registered');
			
			redirect('users/forgetPassword');
			
		}
	}

	public function reset(){
		$data['title'] = 'Reset Password';

		$this->load->view('templates/header');
		$this->load->view('users/resetPassword', $data);
		$this->load->view('templates/footer');
	}

	public function resetPassword(){
		
		$token = $this->input->post('token');
		$password = $this->input->post('password');
		$password2 = $this->input->post('password2');

		if(empty($password) || empty($password2)){
			redirect(base_url('users/reset?token=').$token."&password=empty");
			exit();
		}else if($password != $password2){
			redirect(base_url('users/reset?token=').$token."&password=notsame");
			exit();
		}
		
		$this->UserModel->reset_password($token, md5($password));
		redirect('users/login');
		
	}

	public function success(){
		$data['title'] = 'Reset link sent';

		$this->load->view('templates/header');
		$this->load->view('users/success', $data);
		$this->load->view('templates/footer');
	}

	public function myList($user_id){
		//Pagination Config
        $config['base_url'] = base_url().'users/myList/$1';
        $config['total_rows'] = $this->db->count_all('posts');
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;
        $config['attributes'] = array('class' => 'pagination-links');

        //Init Pagination
        $this->pagination->initialize($config);

		$data['title'] = 'My List';
		$data['posts'] = $this->PostModel->get_mylist_posts($user_id);

		$this->load->view('templates/header');
		$this->load->view('users/myList', $data);
		$this->load->view('templates/footer');
	}

	public function load_mylist_posts($user_id){
        $output = '';
		$results = $this->PostModel->get_mylist_posts($user_id, $this->input->post('limit'), $this->input->post('start'), $this->input->post('mode'));
		if(sizeOf($results) > 0){
            foreach($results as $result){
				$title = $result['title'];
				if($this->input->post('mode')=='mylist'){
					$post_id = $result['post_id'];
				}else{
					$post_id = $result['id'];
				}
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

	public function update(){
		$output = '';
		$results = $this->UserModel->update_user();
		echo $output;
	}

	public function upload_img($user_id){
		if(isset($_POST['image'])){
			
			$data = $_POST['image'];
			$image_array_1 = explode(";", $data);
			$image_array_2 = explode(",", $image_array_1[1]);
			$data = base64_decode($image_array_2[1]);
			$image_name = './assets/img/users/' . time() . '.png';
			file_put_contents($image_name, $data);
			$image = explode("/", $image_name)[4];
			$this->UserModel->update_user_img($user_id, $image);
			$this->session->set_userdata(array('image' => $image));
			
			echo $image;
		}
	}
    
}