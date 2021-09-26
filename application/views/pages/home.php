<div class="container-fluid home-container">
    <div class="container home-info">
        <div class="home-text">
           <h2 class="d-flex justify-content-around align-items-center">
               <span>Explore</span>
               <div><span class="dot"></span></div>
               <span>Cook</span>
               <div><span class="dot"></span></div>
               <span>Satisfy</span>
            </h2>
        </div>
        <br><br><br>
        <div class="home-select">
            <form class="form-row search-area">
                <div class="col-9 search-box">
                    <input class="form-control mr-sm-2 form-control-lg" type="text" name="search_recipe" 
                    id="search_recipe" autocomplete="off" class="form-control" placeholder="Type to search recipes" aria-label="Search">
                    <div id="recipe_list"></div>
                </div>
                <div class="col-3"><a class="btn btn-warning btn-lg btn-search" id="search_btn" href="<?php echo base_url();?>posts/"><img src="<?php echo base_url();?>assets/img/elements/magnifier.png" width="40vw" height="40vw"/></a></div>
            </form>
            <br>
            <button type="button" class="btn btn-warning btn-lg col-12 btn-explore" onclick="location.href='<?php echo base_url(); ?>/posts'">EXPLORE</button>
        </div>
        
        
    </div>  
    <script>
        $("#search_recipe").keyup(function(){
            var keyword = $("#search_recipe").val();
            var data = { 'keyword': keyword };
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
            }else{
                $('#search_btn').attr('href', "<?php echo base_url(); ?>posts");
                $('#recipe_list').fadeOut();
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
