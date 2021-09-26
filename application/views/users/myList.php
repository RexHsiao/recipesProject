<div class="container-fluid  bg-cover">
<br><br>
<div class="container mylist-page">
    <div class="d-flex justify-content-center">
        <h2  class="title">My List</h2>
    </div>
    <br>
    <?php if(!$this->session->userdata('logged_in')){?>
        <br>
        <h3 class="sign-in-alert">Haven't log in yet, <a href="<?php echo base_url(); ?>users/login">SIGN IN</a> now!</h3>
    <?php } else{?>
        <div class="collection-field">
            <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons">
                <label class="btn btn-lg btn-warning active" onclick="switch_mode_to('mylist')">
                    <input type="radio" name="options" id="collections" autocomplete="off" checked> Collections
                </label>
                <label class="btn btn-lg btn-warning" onclick="switch_mode_to('myupload')">
                    <input type="radio" name="options" id="option2" autocomplete="off"> My uploads
                </label>
            </div>
            <br><br>
            <div class="posts-box"><div id="load-data" class="posts-index"></div></div>
            <div id="load-message" class="d-flex"></div>
        </div>
        <br><br>
    <?php }?>
    
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    var mode = 'mylist';
    var limit = 8;
    var start = 0;
    var action = 'inactive';
    
    if(action == 'inactive'){
        action = 'active';
        load_posts_data(limit, start, mode);
    }

    function switch_mode_to(choice){
        $("#load-data").html('');
        mode = choice;
        limit = 8;start = 0;
        action = 'active';
        load_posts_data(limit, start, mode);
    }
    
    function lazzy_loader(limit){
        var output = '';
        for(var count = 0;count<limit; count++){
            output += '<div class="post_data">';
            output += '<p><span class="content-placeholder" \
                style="width:200px;height:200px;">&nbsp;</span></p>';
            output += '<p><span class="content-placeholder" \
                style="width:200px;height:200px;">&nbsp;</span></p>';
            output += '</div>';
        }
        $("#load-message").html(output);
    }

    function load_posts_data(limit, start, mode){
        var url = "<?php echo site_url();?>/users/load_mylist_posts/<?php echo $this->session->userdata('user_id');?>";
        // alert(mode == 1);
        {
            $.ajax({
                url:url,
                method:"POST",
                data:{'limit':limit, 'start':start, 'mode':mode},
                cache: false,
                success:function(data){
                    // alert(data);
                    if(data == ""){
                        $("#load-message").html("<label>No more posts...</label>");
                        action = 'active';
                    }else{
                        $("#load-data").append(data);
                        $("#load-message").html("<label>Loading...</label>");
                        action = 'inactive';
                    }
                }
            });
        }
    }

    

    $(window).scroll(function(){
        if($(window).scrollTop() + $(window).height() > $("#load-data").height()
            && action == "inactive"){
            lazzy_loader(limit);
            action = 'active';
            start = start + limit;
            setTimeout(function(){
                load_posts_data(limit, start, mode);
            }, 1000);
        }
    });
</script>
