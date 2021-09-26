<div class="container-fluid  bg-cover">
<br><br>
<div class="container">
<div class="col-md-6 offset-md-3 signup-page">
<h2 class="title"><?= $title;?></h2><br>
<?php echo validation_errors();?>

<?php echo form_open('users/active_acc');?>
<div class="input-area">
    <div class="md-3 d-flex justify-content-start">
        <div class="input-logo">
            <img width="50vw" height="50vw" src="<?php echo base_url(); ?>assets/img/elements/padlock.png">
        </div>        
        <input type="text" class="form-control  form-control-lg" name="otp-code" placeholder="OTP code">
    </div><br>
</div>
    <br>
    <br>
    <button type="submit" class="btn btn-warning btn-lg col-12">SUBMIT</button>
</form>
</div>
</div>