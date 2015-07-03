<!DOCTYPE html>
<html lang="en" ng-app="dancextremeApp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php $t_description ?>">
    <meta name="author" content="Shane Weaver">
    <meta name="google-site-verification" content="iuU87m1_t5kHxmS4dGSAkdAiOWXvZPvKb5zUwLDf4Gg" />
    <link rel="icon" href="favicon.ico">

    <title>Dance-X-Treme<?php $t_title_append ?></title>

    <!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <style> <?php include('./css/dancextreme.css');?> </style>
    <!--
    <link href="css/dancextreme.css" rel="stylesheet">
    -->
  </head>
<!-- NAVBAR
================================================== -->
  <body>
    <div class="navbar-wrapper">
      <div class="container">

        <nav class="navbar navbar-default navbar-fixed-top">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="/"><img src="img/logo-50.png"></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li id="nav-home"><a href="/">Home</a></li>
                <li id="nav-classes"><a href="/classes">Classes</a></li>
                <li id="nav-venues"><a href="/venues">Venues</a></li>
                <li id="nav-events"><a href="/events">Events</a></li>
                <li id="nav-gallery"><a href="/gallery">Gallery</a></li>
                <!--
                <li id="nav-about"><a href="/about">About</a></li>
                -->
                <li id="nav-contact"><a href="/contact">Contact</a></li>
              </ul>
            </div>
          </div>
        </nav>

      </div>
    </div>

    <?php include($t_content_inc); ?>





    <div class="container">
      <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; <?php echo date("Y") ?> Dance-X-Treme</p>
      </footer>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.28/angular-animate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-google-maps/2.1.5/angular-google-maps.min.js"></script>
    <?php include($t_head_inc); ?>
    <!--
    <script src="../../assets/js/docs.min.js"></script>
    -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!--
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    -->
    <script src="js/dancextreme.js"></script>
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-12475745-1']);
      _gaq.push(['_trackPageview']);
    
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    
    </script>
    <script>
      $(document).ready(function() {
          $('#<?php echo $t_navname;?>').addClass('active');
      });
    </script>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&appId=178227298914590&version=v2.0";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
  </body>
</html>

