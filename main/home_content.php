
    <?php include('carousel.php'); ?>

    <!-- START THE FEATURETTES -->
    <div class="container">
          <div class="row featurette">
            <div class=" col-sm-7">
              <h2 class="featurette-heading">Welcome to <strong>Dance-X-Treme.</strong></h2>
              <p class="lead">Learn to dance ballroom and latin with the West Midlands premier dance school. 
              Come and join Sue & George and the Dance-X-Treme team, there is something for everyone to learn and the 
              emphasis is on <strong>enjoyment</strong> and <strong>fun</strong>.
            </div>
            <div class=" col-sm-5">
                            	<a class="twitter-timeline" data-widget-id="358887851666055168"
    		href="https://twitter.com/Dance_X_Treme" > Tweets by
    		@Dance_X_Treme</a>
    	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
    if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";
    fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </div>
          </div>
          <hr class="featurette-divider">
          
          <!-- Christmas Greetings Start -->
<!--          
            <div class="row featurette">
              
              <div class="col-sm-7 col-sm-push-5">
                <h2 class="featurette-heading">Your <strong>2017</strong> Christmas Greetings</h2>
                <p class="lead">Make your donation and submit your message in class. </p>  
              </div>
              
            <div class=" col-sm-5 col-sm-pull-7">

  <a class="twitter-timeline" href="https://twitter.com/search?q=%23merryxmas%20from%3ADance_X_Treme%20since%3A2017-10-24" data-widget-id="939100567078653952">Tweets about #merryxmas from:Dance_X_Treme since:2017-10-24</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </div>
            
            
            </div>
-->
          <!-- Christmas Greeting End --> 
          
          <!-- Newsletter Start
          
            <hr class="featurette-divider">
            <h2 class="featurette-heading">Our last newsletter</h2>
            <div ng-controller="NewsletterController" select-default-path>
                  <div class="ng-cloak" ng-if="!loading">
                    <div  ng-repeat="newsletter in newsletters" ng-if="selection == newsletter.date" class="album-animate">
                        <div class="panel panel-default" >
                            <div class="panel-heading"><div class="row"><div class="col-sm-8">{{newsletter.title}}</div><div class="col-sm-4 text-right"><small >{{newsletter.dateMonth}} {{newsletter.dateYear}}</small></div> </div></div>
                            <div class="panel-body"><p>{{newsletter.content}}</p></div>
                        </div>
                    </div>
                    
                </div>  
            </div>
            <p class="lead">Visit our <a href="/newsletters">newsletters page</a> to subscribe to the newsletter or view the archive.</p>
            
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
           <div id="img-cross-fade" style="height: 140px; width: 140px;" ng-controller="NextDanceImageController">
             <img class="img-cross-fade img-circle" ng-if="selected==$index" ng-repeat="image in images" ng-src="{{image.src}}" style="width: 140px; height: 140px; position: absolute; background: #55ACEE">
          </div>
          </a>
          <h2>Get Dancing</h2>
          <p>
          <div ng-controller="NextSocialDanceController">
            <p ng-repeat="event in events | orderBy:'+date' | limitTo:1" class="ng-cloak">Our next social event is <span ng-hide="event.hideMe">a </span>
              <strong>{{event.title}}</strong>
              <span ng-hide="event.hideMe"> on <strong>{{event.date | date:'EEEE d MMM yyyy'}}</strong></span>
            </p>
          </div>
          </p>
          <p><a class="btn btn-default" href="/events" role="button">View details &raquo;</a></p>
        </div><!-- /.col-lg-4 -->
        <div class=" col-sm-4">
          <div id="img-cross-fade" style="height: 140px; width: 140px;" ng-controller="SocialMediaImageController">
              <a ng-href="{{images[selected].href}}" >
                  <img class="img-cross-fade img-circle" ng-if="selected==$index" ng-repeat="image in images" ng-src="{{image.src}}" style="width: 140px; height: 140px; position: absolute; background: #55ACEE">
              </a>
          </div>
          <h2>Social Media</h2>
          <p>
            <div class="fb-like-box" data-href="http://www.facebook.com/Dance.X.Treme.UK" data-colorscheme="light" data-show-faces="false" data-header="false" data-stream="false" data-show-border="true"></div>
            <a href="https://twitter.com/Dance_X_Treme" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @Dance_X_Treme</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
          </p>
          <p><a class="btn btn-default" href="http://www.facebook.com/Dance.X.Treme.UK" role="button" target="_blank">View details &raquo;</a></p>
        </div><!-- /.col-lg-4 -->
        <div class=" col-sm-4">
          <a href="/venues">
          <img class="img-circle" src="img/venues-snapshot.png" alt="Generic placeholder image" style="width: 140px; height: 140px;">
          </a>
          <h2>Find Us</h2>
          <a href="/classes">Classes</a> start at just £5.00 and are held in Bromsgrove, Wolverhampton, Stourbridge, Halesowen, Walsall and Sutton Coldfield.</p>
          <p><a class="btn btn-default" href="/venues" role="button">View details &raquo;</a></p>
        </div><!-- /.col-lg-4 -->
      </div><!-- /.row -->
</div>
