<div class="container-fluid  bg-cover">
<br><br>
<div class="container">
<div class="col-md-6 offset-md-3 signup-page">
<h2 class="title"><?= $title;?></h2><br>
<?php echo validation_errors();?>

<?php echo form_open('users/register');?>
<div class="input-area">
    <div class="md-3 d-flex justify-content-start">
        <div class="input-logo">
            <img width="50vw" height="50vw" src="<?php echo base_url(); ?>assets/img/elements/user.png">
        </div>        
        <input type="text" class="form-control  form-control-lg" name="username" placeholder="Username">
    </div><br>
    <div class="md-3 d-flex justify-content-start">
        <div class="input-logo">
            <img width="50vw" height="50vw" src="<?php echo base_url(); ?>assets/img/elements/email.png">
        </div>
        <input type="email" class="form-control  form-control-lg" name="email" placeholder="Email">
    </div><br>
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
    <br>
    <br>
    <button type="submit" class="btn btn-warning btn-lg col-12">SUBMIT</button>
</form>
</div>
</div>