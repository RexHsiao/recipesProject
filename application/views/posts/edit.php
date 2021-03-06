<div class="container-fluid  bg-cover">
<br><br>
<div class="container">
<div class="col-8 offset-2">
<h2 class="d-flex justify-content-center title"><?= $title ?></h2><br>

<?php if(!$this->session->userdata('logged_in')){?>
    <br><br>
    <h3 class="sign-in-alert">Haven't log in yet, <a href="<?php echo base_url(); ?>users/login">SIGN IN</a> now!</h3>
<?php } else{?>
  <div class="image_area d-flex justify-content-center">
    <form method="post">
        <label for="upload_image">
          <div class="col-10 offset-1 form-div previous-img">
            <div class="view-img">
              <img id="post-img-view" class="post-img-view" src="<?php echo base_url();?>assets/img/posts/<?php echo $post['post_image'];?>">
            </div>
          </div>
          <div class="overlay overlay-cover">
            <div class="text text_cover">Click to upload cover image</div>
          </div>
          <input type="file" name="image" class="image" id="upload_image" style="display:none" />
        </label>
    </form>
  </div>
  <form action="<?php echo base_url().'posts/update'?>" method="post" id="form-upload" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $post['id'];?>">
    <div class="mb-3 d-flex justify-content-center">
      <!-- Title -->
      <input type="text" class="form-create title-entry" placeholder="Enter title" name="title" value="<?php echo $post['title'];?>">  
    </div><br>
    <!-- Cover Image -->
    <input type="hidden" name="cover_image" id="cover_image" value="<?php echo $post['post_image'];?>">
    
    <br>
    <div class="mb-3 form_item">
      <label class="form-label">Description</label>
      <textarea id="editor" rows="5" class="form-control" name="body"><?php echo $post['body'];?></textarea>
    </div>
    <div class="slections form_item">
      <div class="mb-3 col col-lg-6">
        <label class="form-label">Portion (person)</label>
        <select name="portion" id="portion" class="form-control">
          <option selected hidden><?php echo $post['portion']?></option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
        </select>
      </div>
      <div class="mb-3 col col-lg-6">
        <label class="form-label">Time (mins)</label>
        <select name="cook_time"  id="cook_time" class="form-control">
          <option selected hidden><?php echo $post['cook_time']?></option>
          <option value="15">15</option>
          <option value="30">30</option>
          <option value="60">60</option>
          <option value="90">90</option>
          <option value="120">120</option>
        </select>
      </div>
    </div>
    <hr>
    <div class="ingredients-field">
      <div class="container-fluid form_item" class="form1">
        <label>Ingredients</label>
        <br>
        <div id="dynamic_field1">
          <?php $json = $post['ingre_info'];$objects = json_decode($json, true); $x = 1;?>
          <?php if($objects  != null):$size = sizeOf($objects);?>
            <?php foreach($objects as $key => $value):?>
              <div id="<?php echo "row".$x;?>" class="d-flex justify-content-around">
                <div class="col-5"><input type="text" class="form-control form-create" placeholder="Enter name" name="<?php echo "ingre_name".$x;?>" value="<?php echo $key;?>"></div>
                <div class="col-5"><input type="text" class="form-control form-create" placeholder="Enter portion size" name="<?php echo "ingre_portion".$x;?>" value="<?php echo $value;?>"></div>
                <div><p class="btn btn-warning btn-remove" id="<?php echo $x;?>">Remove</p></div>
              </div>
              <?php if($x == $size):?>
                <input type="hidden" name="ingre_count" id="ingre_count" value="<?php echo $x;?>">
              <?php endif;?>
              <?php $x++;?>
            <?php endforeach;?>
          <?php else:?>
            <div id="row1" class="d-flex justify-content-around">
              <div class="col-5"><input type="text" class="form-control form-create" placeholder="Enter name" name="ingredient_name1"></div>
              <div class="col-5"><input type="text" class="form-control form-create" placeholder="Enter portion size" name="ingredient_portion1"></div>
              <div><p class="btn btn-warning btn-remove" id="1">Remove</p></div>
            </div>
            <input type="hidden" name="ingre_count" id="ingre_count" value="1">
          <?php endif;?>
        </div>
      </div>
      <div class="d-flex justify-content-end">
        <label>
          <p class="btn btn-upload btn-add" id="add1">Add</p>
        </label>
      </div>
    </div>
    <hr>
    <div class="steps-field">
      <div class="container-fluid form_item" class="form2">
        <label>Steps</label>
        <br>
        <div id="dynamic_field2">
          <?php $json = $post['steps'];$objects = json_decode($json);?>
          <?php if($objects != null):?>
            <?php $size = sizeOf($objects);?>
            <?php for($x = 0 ; $x < $size; $x++):?>
              <?php $n = $x+1;?>
              <div id="<?php echo "step-row".$n;?>" class="d-flex justify-content-start">
                <div class="col-1"><p><?php echo $n;?></p></div>
                <div class="col-8"><input type="text" class="form-control form-create" placeholder="Enter description" name="<?php echo "step_content".$n;?>" value="<?php echo $objects[$x]?>"></div>
                <div><p class="btn btn-warning btn-remove-step" id="<?php echo $n;?>">Remove</p></div>
              </div>
              <?php if($n == $size):?>
                <input type="hidden" name="steps_count" id="steps_count" value="<?php echo $n;?>">
              <?php endif;?>
            <?php endfor;?>
          <?php endif;?>
        </div>
      </div>
      <div class="d-flex justify-content-end">
        <label>
          <p class="btn btn-upload btn-add" id="add2">Add</p>
        </label>
      </div>
    </div>
    <?php echo validation_errors();?>
    <input type="submit" id="submit" class="btn btn-warning btn-lg col-12" value="Submit">
  </form>
<?php }?>
  <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">??</span>
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
<script>
  $('#submit').click(function(){
    var form = $("#form-upload"),
      // file_data = $('#upload-btn')[0].files[0],
      names = [], portions = [], steps = [],  
      url = form.attr('action'),
      method = form.attr('method'),
      data = new FormData(),
      // other_data = $("#form-upload").serialize();
    
    data.append('userfile',file_data);
  
    form.find('[name]').each(function(index, value){
      var item = $(this),
        name = item.attr('name'),
        value = item.val();
      
      data.append(name, value);
      
    });
  });
</script>
<script>
  var i = $("#ingre_count").val();
  $('#add1').click(function(){
    $("#ingre_count").remove();
    i++;
    $('#dynamic_field1').append('<div id="row'+i+'" class="d-flex justify-content-around"><div class="col-5"><input type="text" class="form-control form-create" placeholder="Enter name" name="ingre_name'+i+'"></div><div class="col-5"><input type="text" class="form-control form-create" placeholder="Enter portion size" name="ingre_portion'+i+'"></div><div><p class="btn btn-warning btn-remove" id="'+i+'">Remove</p></div></div><input type="hidden" name="ingre_count" id="ingre_count" value="'+i+'">');
  });

  $(document).on('click', '.btn-remove', function(){
    var button_id = $(this).attr("id");
    $("#row"+button_id+"").remove();
    i -= 1;
  });
</script>
<script>
  var n = $("#steps_count").val();
  $('#add2').click(function(){
    $("#steps_count").remove();
    n++;
    $('#dynamic_field2').append('<div id="step-row'+n+'" class="d-flex justify-content-start"><div class="col-1"><div><p>'+n+'</p></div></div><div class="col-8"><input type="text" class="form-control form-create" placeholder="Enter description" name="step_content'+n+'"></div><div><p class="btn btn-warning btn-remove-step" id="'+n+'">Remove</p></div></div><input type="hidden" name="steps_count" id="steps_count" value="'+n+'">');
  });

  $(document).on('click', '.btn-remove-step', function(){
    var button_id = $(this).attr("id");
    $("#step-row"+button_id+"").remove();
    n -= 1;
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
					url:'<?php echo base_url().'posts/upload_img'?>',
					method:'POST',
					data:{image:base64data},
					success:function(data)
					{
						$modal.modal('hide');
            $('#post-img-view').attr('src', "https://infs3202-0f5e381e.uqcloud.net/recipesProject/assets/img/posts/"+data);
            $('#cover_image').attr('value', data);
					}
				});
			};
		});
	});
	
});
</script>
