<div class="container-fluid  bg-cover">
<br><br>
<div class="container">
<div class="col-md-6 offset-md-3 singin-page">
    <h2 class="title"><?= $title ?></h2><br>
    <div class="login-img">
        <img width="150vw" height="150vw" src="<?php echo base_url(); ?>assets/img/elements/protect.png">
    </div>
<br><br><br>
<?php echo validation_errors();?>
<?php echo form_open('users/login', 'id="signin-form"');?>
    <div class="input-area">
        <div class="form-group d-flex justify-content-start">
            <div class="input-logo">
                <img width="50vw" height="50vw" src="<?php echo base_url(); ?>assets/img/elements/user.png">
            </div>
            <input type="text" name="username" id="username" class="form-control form-control-lg" placeholder="username"
                value="<?php if(isset($_COOKIE['username'])){echo $_COOKIE['username'];}?>"/>
        </div>
        <br>
        <div class="form-group d-flex justify-content-start">
            <div class="input-logo">
                <img width="50vw" height="50vw" src="<?php echo base_url(); ?>assets/img/elements/padlock.png">
            </div>
            <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="password"
            value="<?php if(isset($_COOKIE['password'])){echo $_COOKIE['password'];}?>"/>
        </div>
    </div>
    <br>
    <div class="form-group">
        <div class="g-recaptcha" data-sitekey="6Lc21M8aAAAAAE84iTk3Pgae6fgqn3vsG45Ahrub"></div>
        <span id="captcha_error" class="text-danger"></span>
    </div>
    <br>
    <div class="form-group">
        <?php echo $this->session->flashdata('error');?>
        <input type="submit" name="insert" value="LOG IN" class="btn btn-warning btn-lg col-12"/>
    </div>
    <div class="clearfix d-flex justify-content-between">
        <label class="form-check-label"><input type="checkbox" name="remember" id="remember" value="1"
        <?php if(isset($_COOKIE['username'])){ echo "checked='checked'";};?>> Remember me</label>
        <a href="<?=base_url('users/forgetPassword')?>" >Forget Password?</a>
    </div>
    <br><br>
    <div class="form-group">
        <a type="button" href="<?=$loginURL?>"><img width="200vw" id="login_with_google" src="<?php echo base_url(); ?>assets/img/elements/btn_google_signin_light_normal_web@2x.png"></a>
    </div>
</form>
</div>
</div>
