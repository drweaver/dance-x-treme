<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dance-X-Treme<?php $t_title_append ?></title>
  <meta name="description" content="<?php $t_description ?>">
  <meta name="google-site-verification" content="iuU87m1_t5kHxmS4dGSAkdAiOWXvZPvKb5zUwLDf4Gg" />
  <link rel="icon" href="favicon.ico">
  <style>
    ul.nav {
      padding: 0px 20px;
      border-radius: 10px;  
      list-style: none;
      position: relative;
      display: inline;
    }
    ul.nav li {
        float: left;
        padding-top: 5px;
    }
    ul.nav li a {
      display: block; 
      padding: 0px 32px;
      padding-bottom: 3px;
      color: #990000; 
      text-decoration: none;
    }
    .hidden {
    	display: none; 
    } 
    #big-img-slideshow {
    	height: 300px;
    	overflow: hidden;
    }
    
    <?php include($t_altcss_inc); ?>
  </style>
  <link rel="stylesheet" href="css/template.css" type="text/css">
  <link rel="stylesheet" href="css/style.css" type="text/css">
  <?php include($t_head_inc); ?>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/dancextreme.js"></script>
</head>
<body>
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

  <!--Wrapper Starts here -->
  <div id="wrapper">

    <!--header Starts here -->
    <div id="header">
      <!--logo Starts here -->
      <div class="logo">
      	<a href="/">
        	<img src="img/logo.png" width="356" height="84" alt="logo" />
      	</a>
      </div>
      <!--logo ends here -->

      <!--tollnumber Starts here -->
      <div class="toll-number">
        Tel: 01384 352564 <br /> Mob: 07971 579626<br /> Email: <a
          href="mailto:sue@dance-x-treme.co.uk">sue@dance-x-treme.co.uk</a>
      </div>
      <!--tollnumber ends here -->
      <div class="clear"></div>

      <div id="banner-txt"><?php print $t_banner; ?></div>
      <div id="banner-img">
        <img src="img/main_image<?php print $t_banner_num; ?>.jpg" border="0" width="400" height="170" />
      </div>

      <div class="clear"></div>



        <ul class="nav">
          <li><a href="classes">Classes</a></li>
          <li><a href="events">Events</a></li>
          <li><a href="venues">Venues</a></li>
          <li><a href="gallery">Gallery</a></li>
          <li><a href="about">About</a>
        </ul>

      <!-- Nav ends -->

    </div>
    <!-- Header ends -->


    <div id="mid-container">

      <div id="mid-left">

<?php include($t_content_inc); ?>

      </div>


      <!--rightcontainer starts here -->
      <div id="mid-right">

<?php include($t_rightbox_inc); ?>

      </div>
      <!--rightcontainer ends here -->

      <div class="clear"></div>

    </div>
    <!-- Mid Container ends -->

    <br/><br/><br/>

    <div align="center" style="border-top: 1px solid #981A1E;">
      Copyright &copy;&nbsp;Dance-X-Treme 2014 &nbsp;&nbsp; &nbsp;&nbsp;
      &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
      &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
      &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
      &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
      &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
      &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; <a
        href="http://www.jonoliver.com/" target="_blank">web design
        Jon Oliver Communications Ltd</a>
    </div>

  </div>
  <!-- Wrapper end -->



</body>
</html>
