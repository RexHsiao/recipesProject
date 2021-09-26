<div class="container-fluid  bg-cover">
<div class="container explore-page">
<br><br>
<h2 class="title"><?= $title ?></h2><br>
    <div class="container">
        <div class="container d-flex justify-content-start header1">
            <h4>Popular Recipes</h4>
        </div>
    </div>
    <br>
    <div class="d-flex flex-wrap justify-content-start posts-index slideshow-container" mouseenter="pauseSlides()" mouseleave="resumeSlide()">
        <?php $slide_index = 0;?>
        <?php foreach($popular_posts as $popular_post):?>
            <?php $slide_index++;?>
                <div class="post popular-post mySlides fade">
                    <a href="<?php echo site_url('/posts/'.$popular_post['id']);?>" class="btn each-post">
                        <div>
                            <div class="post-title popular-post-title"><h5><?php echo $popular_post['title'];?></h5></div>
                            <img class="post-thumb popular-post-thumb" src="<?php echo base_url();?>assets/img/posts/<?php echo $popular_post['post_image'];?>">
                        </div>
                    </a>
                </div>
        <?php endforeach;?>
        <div class="d-flex justify-content-between slide-arrows">
            <a class="prev" onclick="plusSlides(-1)">&lsaquo;</a>
            <a class="next" onclick="plusSlides(1)">&rsaquo;</a>
        </div>
    </div>
    <div style="text-align:center">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
        <span class="dot" onclick="currentSlide(4)"></span>
    </div>
    <br><br>
    <div class="container">
        <div class="container header1">
            <h4>New</h4>
        </div>
    </div>
    <br>
    <div>
        <div class="d-flex justify-content-center posts-box"><div id="load-newposts" class="posts-index"></div></div>
        <div id="load-data-message" class="d-flex"></div>    
    </div>
    
    
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    var slideIndex = 1;
    var millis = 3000;
    var interval;
    
    startSlides();
    
    function startSlides(){
        pauseSlides();
        nextSlide();
        interval = setInterval(nextSlide, millis);
    }

    function resumeSlides() {
        nextSlide();
    }

    function pauseSlides() {
        clearInterval(interval);
    }

    function nextSlide() {
        showSlides();
        slideIndex++;
    }

    function plusSlides(n) {
        pauseSlides();
        slideIndex += n;
        showSlides();
        interval = setInterval(nextSlide, millis);
    }

    function currentSlide(n) {
        pauseSlides();
        slideIndex = n;
        resumeSlides();
        interval = setInterval(nextSlide, millis);
    }

    function showSlides() {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("dot");
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        if (slideIndex < 1) {
            slideIndex = slides.length;
        }
        if (slideIndex > slides.length) {slideIndex = 1}
        slides[slideIndex-1].style.display = "block";
        dots[slideIndex - 1].className += " active";
        // setTimeout(showSlides, 5000); // Change image every 2 seconds
    }
</script>
<script>
    var limit = 8;
    var start = 0;
    var action = 'inactive';

    function lazzy_loader(limit){
        var output = '';
        for(var count = 0;count<limit; count++){
            output += '<div class="comment_data">';
            output += '<p><span class="content-placeholder" \
                style="width:200px;height:200px;">&nbsp;</span></p>';
            output += '<p><span class="content-placeholder" \
                style="width:200px;height:200px;">&nbsp;</span></p>';
            output += '</div>';
        }
        $("#load-data-message").html(output);
    }
    

    function load_posts_data(limit, start){
        {
            $.ajax({
                url: "<?php echo site_url();?>/posts/load_posts",
                method:"POST",
                data:{'limit':limit, 'start':start},
                cache: false,
                success:function(data){
                    // alert(data.lrngth);
                    if(data == ""){
                        $("#load-data-message").html("<label>No more posts...</label>");
                        action = 'active';
                    }else{
                        $("#load-newposts").append(data);
                        $("#load-data-message").html("<label>Load more...</label>");
                        action = 'inactive';
                    }
                }
            });
        }
    }

    if(action == 'inactive'){
        action = 'active';
        load_posts_data(limit, start);
    }

    $(window).scroll(function(){
        if($(window).scrollTop() + $(window).height() > $("#load-newposts").height()
            && action == "inactive"){
            lazzy_loader(limit);
            action = 'active';
            start = start + limit;
            setTimeout(function(){
                load_posts_data(limit, start);
            }, 1000);
        }
    });
</script>