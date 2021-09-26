<div class="container-fluid  bg-cover">
<br><br>
<div class="container profile-page">
<div class="container">
    <h2  class="title"><?= $title ?></h2><br><br>
    <form action="<?php echo base_url().'users/update'?>" method="post" id="form-update" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="<?php echo $user['id'];?>">
    
    <div class="image_area d-flex justify-content-center">
        <form method="post">
            <label for="upload_image">
                <div class="user-img personal-info d-flex align-items-center" id="user-img">
                    <?php if($user['image']):?>
                        <img src="<?php echo base_url().'assets/img/users/'.$user['image'];?>" id="uploaded_image" class="img-responsive img-circle" />
                    <?php else:?>
                        <?php echo $this->session->userdata('username')[0];?>
                    <?php endif;?>
                </div>
                <div class="overlay">
                    <div class="text">Click</div>
                </div>
                <input type="file" name="image" class="image" id="upload_image" style="display:none" />
            </label>
        </form>
    </div>
    <div class="username d-flex justify-content-center">
        <h3><?php echo $user['username']?></h3>
    </div>
    <br><br>
    <div class="container">
        <div class="other-info input-area">
            <div class="other-info-col email d-flex justify-content-start align-items-center">
                <div class="input-logo">
                    <img width="40vw" height="40vw" src="<?php echo base_url(); ?>assets/img/elements/email.png">
                </div>
                <input  class="col col-md-9" type="email" name="email" id="email" value="<?php if($user['email']){echo $user['email'];}?>">
            </div>
            <div class="other-info-col phone d-flex justify-content-start align-items-center">
                <div class="input-logo">
                    <img width="40vw" height="40vw" src="<?php echo base_url(); ?>assets/img/elements/telephone.png">
                </div>
                <input  class="col col-md-9" type="text" name="phone" id="phone" value="<?php if($user['phone']){echo $user['phone'];}?>">
            </div>
            <div class="other-info-col birthdate d-flex justify-content-start align-items-center">
                <div class="input-logo">
                    <img width="40vw" height="40vw" src="<?php echo base_url(); ?>assets/img/elements/cake.png">
                </div>
                <input  class="col col-md-9" type="date" name="birthdate" id="birthdate" value="<?php if($user['birthdate']){echo $user['birthdate'];}?>">
            </div>
        </div>
    </div>
    <br>
    <div class="btn-submit d-flex justify-content-center">
        <input type="button" id="update" class="btn btn-warning btn-md col-4" value="Update">
    </div>
    </form>
    <div class="row">
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Crop Image</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="img-container">
                            <div class="row">
                                <div class="col-md-10">
                                    <img src="" id="sample_image" />
                                </div>
                                <div class="col-md-2">
                                    <div class="preview"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="crop" class="btn btn-warning">Crop</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>		

</div>
</div>
<script>
    $('#update').click(function(){
        var form = $("#form-update"),
        url = form.attr('action'),
        method = form.attr('method'),
        user_id = $("#user_id").val(),
        email = $("#email").val(),
        phone = $("#phone").val(),
        birthdate = $("#birthdate").val(),
        data = {
            'user_id': user_id,
            'email': email,
            'phone':phone,
            'birthdate': birthdate
        }
        $.ajax({
            url:url,
            method:method,
            data:data,
            success: function(val){
                alert('Your profile has been updated!');
            }
        })
        
    });
</script>
<script>
// https://www.webslesson.info/2020/08/php-crop-image-while-uploading-with-cropper-js.html
$(document).ready(function(){

	var $modal = $('#modal');

	var image = document.getElementById('sample_image');

	var cropper;

	$('#upload_image').change(function(event){
		var files = event.target.files;

		var done = function(url){
			image.src = url;
			$modal.modal('show');
		};

		if(files && files.length > 0)
		{
			reader = new FileReader();
			reader.onload = function(event)
			{
				done(reader.result);
			};
			reader.readAsDataURL(files[0]);
		}
	});

	$modal.on('shown.bs.modal', function() {
		cropper = new Cropper(image, {
			aspectRatio: 1,
			viewMode: 3,
			preview:'.preview'
		});
	}).on('hidden.bs.modal', function(){
		cropper.destroy();
   		cropper = null;
	});
    $('#crop').click(function(){
		canvas = cropper.getCroppedCanvas({
			width:400,
			height:400
		});

		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
				$.ajax({
					url:'<?php echo base_url().'users/upload_img/'.$user['id']?>',
					method:'POST',
					data:{image:base64data},
					success:function(data)
					{
						$modal.modal('hide');
                        $('#uploaded_image').attr('src', "https://infs3202-0f5e381e.uqcloud.net/recipesProject/assets/img/users/"+data);
                        $('#uploaded_image_home').attr('src', "https://infs3202-0f5e381e.uqcloud.net/recipesProject/assets/img/users/"+data);
					}
				});
			};
		});
	});
	
});
</script>
