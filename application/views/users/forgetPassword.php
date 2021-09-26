<div class="container-fluid  bg-cover">
<br><br>
<div class="container">
<div class="col-md-6 offset-md-3 singin-page">
    <h2 class="title forgot-title"><?= $title ?></h2><br>
    <form action="<?=base_url('users/resetLink')?>" method="post">
        <div class="input-area">
            <h5>Enter yout email address below and we we'll send you a link to reset your password.</h5><br>
            <div class="md-3 d-flex justify-content-start">
                <div class="input-logo">
                    <img width="50vw" height="50vw" src="<?php echo base_url(); ?>assets/img/elements/email.png">
                </div>
                <input type="email" class="form-control  form-control-lg" name="email" placeholder="Email">
            </div>
            <br>
            <?php 
                if(isset($_GET['reset'])){
                    if($_GET['reset'] == 'success'){
                        echo '<p>Check your E-mail</p>';
                    }else{
                        echo '<p>Failed</p>';
                    }
                }
            ?>
        </div>
        <br><br>
        <div class="form-group">
            <?php echo $this->session->flashdata('error');?>
            <input type="submit" name="insert" value="Send reset link" class="btn btn-warning col-12"/>
        </div>
    </form>
</div>
</div>