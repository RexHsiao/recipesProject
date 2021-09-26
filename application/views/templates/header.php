<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>INFS7202 Demo</title>
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap.css">
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/dropzone.css">
      <link rel="stylesheet" href="https://unpkg.com/cropperjs/dist/cropper.css">
      <script src="https://cdn.ckeditor.com/ckeditor5/26.0.0/classic/ckeditor.js"></script>
      <script src="<?php echo base_url(); ?>assets/js/jquery-3.6.0.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
      <script src="<?php echo base_url(); ?>assets/js/dropzone-5.7.0/dist/dropzone.js"></script>
      <script src="https://unpkg.com/cropperjs"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <body>
  <?php
    if($this->session->userdata('logged_in')){
      if((time() - $this->session->userdata('logged_in_time')) > 900){
        redirect(base_url().'users/logout');
      };
    };
  ?>
  <nav class="navbar navbar-expand-lg navbar-dark py-3 nav-bg-gradient">
    <div class="container">
      <a class="navbar-brand mb-0 h1" href="<?php echo base_url(); ?>">
        <div class="navbar-header">
          <div>
            <img src="<?php echo base_url(); ?>assets/img/elements/chef.png" class="nav-logo" width="60vh" height="60vh">
          </div>
          <div>Recipe Blog</div>
        </div>
      </a>
      <ul class="navbar-nav me-auto justify-content-center mb-lg-2 container-fluid">
        <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>posts">Explore</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>about">About</a></li>
      </ul>
      <ul class="navbar-nav d-flex mb-lg-2">
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" id="navbarDropdown" 
          role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php if(!$this->session->userdata('logged_in')){?>
              User
            <?php } else{?>
              <div class="user-img d-flex align-items-center">
                <?php if($this->session->userdata('image')):?>
                  <img src="<?php echo base_url().'assets/img/users/'.$this->session->userdata('image');?>" id="uploaded_image_home" class="img-responsive img-circle" />
                <?php else:?>
                  <?php echo $this->session->userdata('username')[0]?>
                <?php endif;?>
              </div>
            <?php } ?>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <?php if(!$this->session->userdata('logged_in')){?>
              <a class="dropdown-item" href="<?php echo base_url(); ?>users/login">Sign in</a>
              <a class="dropdown-item" href="<?php echo base_url(); ?>users/register">Register</a>
            <?php } else{?>
              <a class="dropdown-item user-welcome" href="<?php echo base_url(); ?>users/profile/<?php echo $this->session->userdata('user_id');?>">
                Hi, <?php echo $this->session->userdata('username')?></a>
              <a class="dropdown-item" href="<?php echo base_url(); ?>users/myList/<?php echo $this->session->userdata('user_id');?>">My List</a>
              <a class="dropdown-item" href="<?php echo base_url(); ?>posts/create">Create</a>
              <div class="dropdown-divider"></div>
              
              <a class="dropdown-item" href="<?php echo base_url(); ?>users/logout">Sign out</a>
            <?php }?>
          </div>
        </li>
      </ul>
    </div>
  </nav>
  <div class="alert-massage">
    <?php if($this->session->flashdata('user_registered')){?>
      <?php echo '<p class="alert alert-success">'.$this->session->flashdata('user_registered').'</p>';?>
    <?php }; ?>
    <?php if($this->session->flashdata('user_login')){?>
      <?php echo '<p class="alert alert-success">'.$this->session->flashdata('user_login').'</p>';?>
    <?php }; ?>
    <?php if($this->session->flashdata('user_logout')){?>
      <?php echo '<p class="alert alert-success">'.$this->session->flashdata('user_logout').'</p>';?>
    <?php }; ?>
    <?php if($this->session->flashdata('login_failed')){?>
      <?php echo '<p class="alert alert-danger">'.$this->session->flashdata('login_failed').'</p>';?>
    <?php }; ?>
    <?php if($this->session->flashdata('post_created')){?>
      <?php echo '<p class="alert alert-success">'.$this->session->flashdata('post_created').'</p>';?>
    <?php }; ?>
    <?php if($this->session->flashdata('post_updated')){?>
      <?php echo '<p class="alert alert-success">'.$this->session->flashdata('post_updated').'</p>';?>
    <?php }; ?>
    <?php if($this->session->flashdata('post_deleted')){?>
      <?php echo '<p class="alert alert-success">'.$this->session->flashdata('post_deleted').'</p>';?>
    <?php }; ?>
    <?php if($this->session->flashdata('user_emailSendInfo')){?>
      <?php echo '<p class="alert alert-danger">'.$this->session->flashdata('user_emailSendInfo').'</p>';?>
    <?php }; ?>
  </div>
