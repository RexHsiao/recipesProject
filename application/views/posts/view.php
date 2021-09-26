<div class="container-fluid  bg-cover">
<div class="container view-page">
<div class="container form-div">
<?php $liked = false;?>
<?php $collected = false?>
<?php if($likes != null):?>
    <?php foreach($likes as $like):?>
        <?php if($like['user_id'] == $this->session->userdata('user_id')):
            $liked = true;
        endif;?>
    <?php endforeach;?>
<?php endif;?>
<?php if($collectors != null):?>
    <?php foreach($collectors as $collector):?>
        <?php if($collector['user_id'] == $this->session->userdata('user_id')):
            $collected = true;
        endif;?>
    <?php endforeach;?>
<?php endif;?>
    <br><br>
    <h2 class="d-flex justify-content-center"><?php echo $post['title']; ?></h2>
    <input type="hidden" name="id" value="<?php echo $post['id'];?>">
    <div class="d-flex justify-content-end">
        <div id="like-item">
            <?php if($liked == true):?>
                <span style="cursor: pointer" id='post_like' onclick="dislike_post()">
                    <img src="<?php echo base_url();?>assets/img/elements/like.png" width="40vw" height="40vw"/>
                </span>
            <?php else:?>
                <span style="cursor: pointer" id='post_like' onclick="like_post()">
                    <img src="<?php echo base_url();?>assets/img/elements/like-white.png" width="40vw" height="40vw"/>
                </span>
            <?php endif;?>
        </div>
        <div id="like_count_box" class="d-flex align-content-center"><span id="like_count"><?php echo sizeOf($likes);?></span></div>
        <?php if($this->session->userdata('logged_in')){?>
            <div id="collection">
                <?php if($collected == true):?>
                    <span style="cursor: pointer" id='uncollect_post' onclick="uncollect_post()">
                        <img src="<?php echo base_url();?>assets/img/elements/bookmark-colored.png" width="40vw" height="40vw"/>
                    </span>
                <?php else:?>
                    <span style="cursor: pointer" id='collect_post' onclick="collect_post()">
                        <img src="<?php echo base_url();?>assets/img/elements/bookmark.png" width="40vw" height="40vw"/>
                    </span>
                <?php endif;?>
            </div>
        <?php }?>
    </div><br>
    
    <div class="col-8 offset-2 form-div">
        <div class="view-img">
            <img class="post-img-view" src="<?php echo base_url();?>assets/img/posts/<?php echo $post['post_image'];?> ">
        </div>
    </div><br>
    <div class="create-info d-flex justify-content-between">
        <div class="d-flex col-5 justify-content-start">
            <div>
                <div class="user-img d-flex align-items-center">
                    <?php if($creator['image']):?>
                        <img src="<?php echo base_url().'assets/img/users/'.$creator['image'];?>" id="uploaded_image_home" class="img-responsive img-circle" />
                    <?php else:?>
                        <?php echo $creator['username'][0]?>
                    <?php endif;?>
                </div>
            </div>
            <div class="create-text-info col-7">
                <?php echo $creator['username']?><br>
                <small><?php echo explode(" ", $post['created_at'])[0]?></small>
            </div>
        </div>
        <?php if($this->session->userdata('user_id') == $post['user_id']){?>
            <div class="d-flex justify-content-end user-edit-btns">
                <div class="user-edit-btn1"><a class="btn btn-secondary" href="<?php echo base_url();?>posts/edit/<?php echo $post['id']?>">Edit</a></div>
                <div class="user-edit-btn2">
                    <?php echo form_open('/posts/delete/'.$post['id']); ?>
                        <input type="submit" value="Delete" class="btn btn-danger">
                    </form>
                </div>
            </div>
        <?php }?>
    </div>
    <br><br>
    <div class="post-body">
        <h4>Description</h4>
        <?php echo $post['body'];?>
    </div>
    <br><hr>
    <div class="ingredients-view">
        <h4>Ingredients</h4>
        <div class="ingredients-view-area d-flex flex-wrap align-content-start justify-content-start">
            <?php $json = $post['ingre_info'];$objects = json_decode($json);?>
            <?php if($objects  != null):?>
                <?php foreach($objects as $key => $value):?>
                    <div class="per-ingre d-flex justify-content-between ingre-col">
                        <div class="ingre-name col-7">
                            <div class="d-flex align-items-center justify-content-start">
                                <input class="col-2" type="checkbox" aria-label="Checkbox for following text input">
                                <div class="d-flex align-items-center justify-content-start"><h6><?php echo $key;?></h6></div>
                            </div>
                        </div>  
                        <div class="ingre-portion">
                            <h6><?php echo $value;?></h6>
                        </div>
                    </div>
                <?php endforeach;?>
            <?php endif;?>
        </div>
    </div>
    <br><hr>
    <div class="steps">
        <h4>Start Cooking</h4><br>
        <div class="steps-view-area">
            <?php $json = $post['steps'];$objects = json_decode($json);?>
            <?php if($objects != null):?>
                <?php $size = sizeOf($objects);?>
                <?php for($x = 0 ; $x < $size; $x++):?>
                    <div class="per-step d-flex align-items-center justify-content-start">
                        <div><p><?php echo $x+1?></p></div>
                        <div class="col-12"><h5 class="form-control form-create"><?php echo $objects[$x]?></h5></div>
                    </div>
                <?php endfor;?>
            <?php endif;?>
        </div>
    </div>
    <br><hr>
    
    <?php if(!$this->session->userdata('logged_in')){?>
        <br><br>
        <h3 class="sign-in-alert"><a href="<?php echo base_url(); ?>users/login">SIGN IN</a>, and leave comments!</h3>
    <?php } else{?>
        <br>
        <form id="comment-insert">
            <div class="d-flex justify-content-start comment-field">
                <div>
                    <div class="user-img d-flex align-items-center" id="current_user">
                        <?php if($this->session->userdata('image')):?>
                            <img src="<?php echo base_url().'assets/img/users/'.$this->session->userdata('image');?>" id="uploaded_image_home" class="img-responsive img-circle" />
                        <?php else:?>
                            <?php echo $this->session->userdata('username')[0]?>
                        <?php endif;?>
                    </div>
                </div>
                <input name="body" rows="1" id="comment-text" class="form-control comment-area" placeholder="Leave your comment..."></textarea>
                <button class="btn btn-warning" type="button" id="send-comment">Send</button>
            </div>
            <span id="err_comment" class="text-danger ms-3"></span>
            
            <input type="hidden" class="slug" name='slug' value="<?php echo $post['slug'];?>">
            <input type="hidden" class="username" name="username" value="<?php echo $this->session->userdata('username');?>">
            <input type="hidden" class="user_id" name="user_id" value="<?php echo $this->session->userdata('user_id');?>">
        </form>      
    <?php }?><br>

    <h4 id="count-comments"><span><?php echo sizeof($comments)?></span> Comments</h4>
    <div id ="show-comments">
        <br>
        <div id = "new-comments"><div id="newest"></div></div>
        <?php if($comments):?>
            <div id="load-data"></div>
            <div id="load-data-message"></div>
        <?php else: ?>
            <div id="no-comment" class="no-comment">
                <div><img width="50vw" height="50vw" src="<?php echo base_url(); ?>assets/img/elements/chat-bubble.png"></div>
                <div>No Comments yet</div>
            </div>
        <?php endif; ?>
    </div>
    <br>
</div>    
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $("#send-comment").click(function(){
        var comment = $("#comment-text").val();
        var slug = $(".slug").val();
        var username = $(".username").val();
        var user_id = $(".user_id").val();
        if(comment == ""){
            $("#err_comment").html("No comment entered");
        }else{
            var data = {
                "body":comment,
                "slug": slug,
                "username": username,
                "user_id": user_id
            }
            $.ajax({
                url:"<?php echo base_url()?>/comments/create/<?php echo $post['id']?>",
                method:"POST",
                data: data,
                success:function(response){
                    $("#comment-text").val('');

                    var obj = JSON.parse(response);
                    var newest = obj[0];
                    var count = obj.length;
                    if(obj.length>0){
                        var username = newest["name"];
                        var id = newest["id"];
                        var user_info = document.getElementById('current_user').innerHTML;
                        var create_at = newest["create_at"].split(":")[0]+":"+newest["create_at"].split(":")[1];
                        var body = newest["body"];
                        $("#newest").after(
                            '<div id="newest"></div>\
                            <div class="d-flex justify-content-between comment-box" id="comment-'+id+'">\
                                <div class="d-flex justify-content-start"> \
                                    <div class="user-img d-flex align-items-center">'+user_info+'</div>\
                                    <div class="comment-info"><h5>'+username+' <small>'+create_at+'</small></h5><h6>'+body+'</h6></div>\
                                </div>\
                                <div class="d-flex align-items-center">\
                                    <span style="cursor: pointer" id="delete_comment" onclick="delete_comment\('+id+'\)">\
                                        <img src="<?php echo base_url();?>assets/img/elements/trash.png" width="30vw" height="30vw"/>\
                                    </span>\
                                </div>\
                            </div>');
                        $("#newest").remove();
                        $("#no-comment").remove();
                        $("#count-comments").remove();
                        $("#show-comments").before('<h4 id="count-comments"><span>'+count+'</span> Comments</h4>')
                    }
                    
                }
            });
        }
    });
</script>
<script>
    function like_post(){
        var user_id = $(".user_id").val();
        // alert(user_id);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url();?>posts/like_post/<?php echo $post['id']?>",
            data: {'user_id': user_id},
            cache:false,
            success: function(val){
                // alert(val);
                $("#like_count").remove();
                $("#post_like").remove();
                $("#like_count_box").html('<span id="like_count">'+val+'</span>');
                $("#like-item").html('<span style="cursor: pointer" id="post_dislike" onclick="dislike_post()"><img src="<?php echo base_url();?>assets/img/elements/like.png" width="40vw" height="40vw"/></span>')
            }
        });
    }
    function dislike_post(){
        var user_id = $(".user_id").val();
        $.ajax({
            method: "POST",
            url: "<?php echo base_url();?>posts/dislike_post/<?php echo $post['id']?>",
            data: {'user_id': user_id},
            cache:false,
            success: function(val){
                // alert(val);
                $("#like_count").remove();
                $("#post_dislike").remove();
                $("#like_count_box").html('<span id="like_count">'+val+'</span>');
                $("#like-item").html('<span style="cursor: pointer" id="post_like" onclick="like_post()"><img src="<?php echo base_url();?>assets/img/elements/like-white.png" width="40vw" height="40vw"/></span>')
            }
        });
    }
    function collect_post(){
        var user_id = $(".user_id").val();
        $.ajax({
            method: "POST",
            url: "<?php echo base_url();?>posts/collect_post/<?php echo $post['id']?>",
            data: {'user_id': user_id},
            cache:false,
            success: function(val){
                // alert(val);
                $("#collect_post").remove();
                $("#collection").html('<span style="cursor: pointer" id="uncollect_post" onclick="uncollect_post()"><img src="<?php echo base_url();?>assets/img/elements/bookmark-colored.png" width="40vw" height="40vw"/></span>')
            }
        });
    }
    function uncollect_post(){
        var user_id = $(".user_id").val();
        $.ajax({
            method: "POST",
            url: "<?php echo base_url();?>posts/uncollect_post/<?php echo $post['id']?>",
            data: {'user_id': user_id},
            cache:false,
            success: function(val){
                // alert(val);
                $("#uncollect_post").remove();
                $("#collection").html('<span style="cursor: pointer" id="collect_post" onclick="collect_post()"><img src="<?php echo base_url();?>assets/img/elements/bookmark.png" width="40vw" height="40vw"/></span>')
            }
        });
    }
    function delete_comment(e,i){
        var user_id = $(".user_id").val();
        var comment_id = $(e)[0];
        var count = $("#count-comments span").html() - 1;
        $.ajax({
            method: "POST",
            url: "<?php echo base_url();?>comments/delete",
            data: {'user_id': user_id, 'comment_id': comment_id},
            cache:false,
            success: function(val){
                $("#comment-"+comment_id+"").remove();
                $("#count-comments").remove();
                $("#show-comments").before('<h4 id="count-comments"><span>'+count+'</span> Comments</h4>')
            }
        });
    }
</script>
<script>
    var limit = 7;
    var start = 0;
    var action = 'inactive';

    function lazzy_loader(limit){
        var output = '';
        for(var count = 0;count<limit; count++){
            output += '<div class="comment_data">';
            output += '<p><span class="content-placeholder" \
                style="width:100%;height:30px;">&nbsp;</span></p>';
            output += '<p><span class="content-placeholder" \
                style="width:100%;height:30px;">&nbsp;</span></p>';
            output += '</div>';
        }
        $("#load-data-message").html(output);
    }
    

    function load_comments_data(limit, start){
        {
            $.ajax({
                url:"<?php echo base_url();?>comments/load_comments/<?php echo $post['id']?>",
                method:"POST",
                data:{'limit':limit, 'start':start},
                cache: false,
                success:function(data){
                    // alert(data);
                    if(data == ""){
                        $("#load-data-message").html("<label>No more comments...</label>");
                        action = 'active';
                    }else{
                        $("#load-data").append(data);
                        $("#load-data-message").html("<label>Loading...</label>");
                        action = 'inactive';
                    }
                }
            });
        }
    }

    if(action == 'inactive'){
        action = 'active';
        load_comments_data(limit, start);
    }

    $(window).scroll(function(){
        if($(window).scrollTop() + $(window).height() > $("#load-data").height()
            && action == "inactive"){
            lazzy_loader(limit);
            action = 'active';
            start = start + limit;
            setTimeout(function(){
                load_comments_data(limit, start);
            }, 1000);
        }
    });
</script>

