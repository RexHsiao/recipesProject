<div class="container-fluid  bg-cover">
<div class="container explore-page">
<br><br>
<h2 class="title"><?= $title ?></h2><br>
<div class="container">
    <form class="container form-row search-area">
        <div class="col-10 search-box">
            <input class="form-control mr-sm-2" type="text" name="search_recipe" 
            id="search_recipe" autocomplete="off" class="form-control" placeholder="Type to search recipes" aria-label="Search" value="<?= $keyword ?>">
            <div id="recipe_list"></div>
        </div>
        <div class="col-2"><a class="btn btn-warning btn-search" type="submit" id="search_btn" href="<?php echo base_url();?>posts/search/<?= $keyword ?>"><img src="<?php echo base_url();?>assets/img/elements/magnifier.png" width="40vw" height="40vw"/></a></div>
    </form>
</div>
<br><br><br>
<div class="posts-box">
    <div class="posts-index">
        <?php if(sizeOf($posts) != 0):?>
            <?php foreach($posts as $post):?>
                <br>
                <div class="post">
                    <a href="<?php echo base_url();?>posts/<?php echo $post['id'];?>" class="btn each-post">
                        <div>
                            <div class="post-title"><h5><?php echo $post['title']?></h5></div>
                            <img class="post-thumb" width="200px" height="200px" src="<?php echo base_url();?>assets/img/posts/<?php echo $post['post_image'];?>">
                        </div>
                    </a>
                </div>
            <?php endforeach;?>
        <?php else:?>
            <h4 class="not-found">Recipe Not Found</h4>
        <?php endif;?>
        
    </div>  
</div>
</div>
</div>
<script>
    $("#search_recipe").keyup(function(){
        var keyword = $("#search_recipe").val(),
        data = { 'keyword': keyword };
        if(keyword != ''){
            $.ajax({
                type: "POST",
                url: '<?php echo base_url().'autocomplete/get_search_posts'?>',
                data: data,
                success: function(data){
                    $('#recipe_list').fadeIn();
                    $('#recipe_list').html(data);
                    $('#search_btn').attr('href', "<?php echo base_url(); ?>posts/search/"+keyword);
                }
            });
        };
        
    });
    $(document).on('click', 'li', function(){
        var id = $(this).attr('id');
        $('#search_recipe').val($(this).text());
        $('#search_btn').attr('href', "<?php echo base_url(); ?>posts/search/"+id);
        $('#recipe_list').fadeOut();
    });
    
</script>
<script>
    function search_start(){
        var keyword = $("#search_recipe").val(),
        data = { 'keyword': keyword };

    }
</script>