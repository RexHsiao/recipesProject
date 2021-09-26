<div class="container-fluid  bg-cover">
<br><br>
<div class="container">
<div class="col-md-6 offset-md-3 singin-page">
    <h2 class="title forgot-title"><?= $title ?></h2><br>
<?php
    
    $token = $_GET['token'];

    if(empty($token)){
        echo 'Could not validate your request!';
    }else{
        
        ?>
        <form action="<?=base_url('users/resetPassword')?>" method="post">
            <div class="input-area">
                <div class="md-3 d-flex justify-content-start">
                    <div class="input-logo">
                        <img width="50vw" height="50vw" src="<?php echo base_url(); ?>assets/img/elements/padlock.png">
                    </div>        
                    <input type="password" class="form-control  form-control-lg" name="password" placeholder="Password">
                </div><br>
                <div class="md-3 d-flex justify-content-start">
                    <div class="input-logo">
                        <img width="50vw" height="50vw" src="<?php echo base_url(); ?>assets/img/elements/padlock.png">
                    </div>        
                    <input type="password" class="form-control  form-control-lg" name="password2" placeholder="Confirm Password">
                </div><br>
            </div>
            <br><br>
            
            <input type="hidden" name="token" value="<?php echo $token;?>">
            <div class="form-group">
                <?php echo $this->session->flashdata('error');?>
                <input type="submit" name="insert" value="Reset password" class="btn btn-warning col-12"/>
            </div>
        </form>
        <?php
        
    }
?>
    
</div>
</div>