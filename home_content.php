
<?php include('carousel.php'); ?>



      <!-- START THE FEATURETTES -->
<div class="container">
      <div class="row featurette">
        <div class=" col-sm-7 col-md-7">
          <h2 class="featurette-heading">Welcome to <strong>Dance-X-Treme.</strong></h2>
          <p class="lead">Learn to dance ballroom and latin with the West Midlands premier dance school. 
          Come and join Sue & George and the Dance-X-Treme team, there is something for everyone to learn and the 
          emphasis is on <strong>enjoyment</strong> and <strong>fun</strong>.
        </div>
        <div class=" col-sm-5 col-md-5">
                        	<a class="twitter-timeline" data-widget-id="358887851666055168"
		href="https://twitter.com/Dance_X_Treme" > Tweets by
		@Dance_X_Treme</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";
fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
      </div>
<!--
      <hr class="featurette-divider">

      <div class="row featurette">
        <div class="col-md-5">
                        <a class="twitter-timeline" href="https://twitter.com/search?q=%23merryxmas%20from%3Adance_x_treme" data-widget-id="537625733439709184">Tweets about #merryxmas from:dance_x_treme</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
        <div class="col-md-7">
          <h2 class="featurette-heading">Your 2014 Christmas Messages</h2>
          <p class="lead">

          </p>
        </div>
      </div>
-->
      <hr class="featurette-divider">
</div>

      <!-- /END THE FEATURETTES -->



    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container marketing">

      <!-- Three columns of text below the carousel -->
      <div class="row">
        <div class=" col-sm-4">
          <a href="/events">
            <div id="next-event-slideshow" style="height: 140px; width: 140px;">
              
            <?php

              $gallery_data = json_decode( file_get_contents('data/dance_galleries.txt'), true );
              $active = 'active';
              $display = 'inline';
              $start = rand(0,count($gallery_data)-5);
              $end = $start + 5;
              for( $i=$start; $i<$end; $i++) {
                $g = $gallery_data[$i];
              // foreach ($gallery_data as $g) {
              	print('<img class="img-circle '.$active.'" style="width: 140px; height: 140px; display:'.$display.'; position:absolute" src="'.$g['thumbnail'].'">');
              	$active = '';
              	$display = 'none';
              }
            
            ?>
            </div>
            <!--
          <img class="img-circle" src="https://lh6.googleusercontent.com/-CUUxHM_bsLc/VJTKV7aDLGE/AAAAAAABfGY/-DOfKtUlghU/s160-c/ChristmasBallPelsall2014.jpg" alt="Generic placeholder image" style="width: 140px; height: 140px;">
          -->
          </a>
          <h2>Get Dancing</h2>
          <p>
          <div id="next-event"></div>
          </p>
          <p><a class="btn btn-default" href="/events" role="button">View details &raquo;</a></p>
        </div><!-- /.col-lg-4 -->
        <div class=" col-sm-4">
          <div style="height: 140px; width: 140px;" id="social-media-icon">
            <a href="https://twitter.com/Dance_X_Treme">
              <img class="img-circle active" src="img/twitter_logo_white2.png" alt="Generic placeholder image" style="width: 140px; height: 140px; position: absolute; background: #55ACEE">
              <img class="img-circle" src="img/facebook_logo_blue_144.png" alt="Generic placeholder image" style="width: 140px; height: 140px; display:none; position: absolute; ">
            </a>
          </div>
          <h2>Social Media</h2>
          <p>
<div class="fb-like-box" data-href="http://www.facebook.com/Dance.X.Treme.UK" data-colorscheme="light" data-show-faces="false" data-header="false" data-stream="false" data-show-border="true"></div>
<a href="https://twitter.com/Dance_X_Treme" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @Dance_X_Treme</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
          </p>
          <p><a class="btn btn-default" href="http://www.facebook.com/Dance.X.Treme.UK" role="button">View details &raquo;</a></p>
        </div><!-- /.col-lg-4 -->
        <div class=" col-sm-4">
          <a href="/venues">
          <img class="img-circle" src="img/venues-snapshot.png" alt="Generic placeholder image" style="width: 140px; height: 140px;">
          </a>
          <h2>Find Us</h2>
          <a href="/classes">Classes</a> start at just £4.50 and are held in Wolverhampton, Stourbridge, Halesowen, Walsall and Sutton Coldfield.</p>
          <p><a class="btn btn-default" href="/venues" role="button">View details &raquo;</a></p>
        </div><!-- /.col-lg-4 -->
      </div><!-- /.row -->
</div>
