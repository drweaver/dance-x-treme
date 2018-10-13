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
    <?php include($t_head_inc); ?>

    <title>Dance-X-Treme<?php $t_title_append ?></title>

    <!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <style> <?php include('./css/dancextreme.css');?> </style>
    <!--
    <link href="css/dancextreme.css" rel="stylesheet">
    -->
  </head>
<!-- NAVBAR
================================================== -->
  <body>
    
    <?php include($t_navbar_inc); ?>

    <?php include($t_content_inc); ?>

    <div class="container">
      <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; <?php echo date("Y"), ' ', $t_copyright; ?></p>
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
    <?php include($t_script_inc); ?>
    <!--
    <script src="../../assets/js/docs.min.js"></script>
    -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!--
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    -->
    <script src="js/dancextreme.js?_=20181013"></script>
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

