<?php

include('./default_vars.php');

$t_title_append = ' - Home';
$t_description = 'Learn to dance ballroom and latin with the West Midlands premier dance school';
$t_content_inc = './home_content.php';
$t_navname = 'nav-home';

include('./master.php');

?>
<script>

     setInterval(function() {
        var id = 'next-event-slideshow';
        // console.log(' doing swap ');
        var $active = $('#'+id+' .active');
        var $next = ($('#'+id+' .active').next().length > 0) ? $('#' + id + ' .active').next() : $('#' + id + ' img:first');
        $active.fadeOut(600, function() {
            $active.removeClass('active');
        });
        $next.fadeIn(600).addClass('active');
    }, 3000);
    
    setInterval(function() {
        var id = 'social-media-icon';
        // console.log(' doing swap ');
        var $active = $('#'+id+' .active');
        var $next = ($('#'+id+' .active').next().length > 0) ? $('#' + id + ' .active').next() : $('#' + id + ' img:first');
        $active.fadeOut(600, function() {
            $active.removeClass('active');
        });
        $next.fadeIn(600).addClass('active');
        $('#'+id+' a').attr('href', function(index,value) { return value.indexOf("facebook") == -1 ? "http://www.facebook.com/Dance.X.Treme.UK" : "https://twitter.com/Dance_X_Treme"});
        
    }, 7000);
    
</script>