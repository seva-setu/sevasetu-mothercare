@include('template/admin_title')
<!DOCTYPE html>
<html class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Comming Soon Bootstrap Landing Page</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap and Font Awesome css-->
    <link rel="stylesheet" href="{{ url() }}/assets/coming_soon/css/font-awesome.css">
    <link rel="stylesheet" href="{{ url() }}/assets/coming_soon/css/bootstrap.min.css">
    <!-- Google fonts-->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Pacifico">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700">
    <!-- Theme stylesheet-->
    <link rel="stylesheet" href="{{ url() }}/assets//coming_soon/css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <!-- <link rel="stylesheet" href="css/custom.css"> -->
    <!-- Favicon-->
    <link rel="shortcut icon" href="{{ url() }}/assets/img/favicon.png">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
    <div style="background-image: url('{{ url() }}/assets/coming_soon/background.jpg')" class="main"> 
      <div class="overlay"></div>
      <div class="container">
        <h1 class="cursive">FAQs are comming soon.</h1>
        <div class="mailing-list">
          <h3 class="mailing-list-heading">Till then, know more about the program by watching this short video </h3>
          <div class="row">            
             <a href="https://www.youtube.com/embed/VeohOCDw2bc" <button class="btn btn-success">Take me to the video </button></a>
          </div>
        </div>
      </div>
      <div class="footer">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <p>&copy;Seva Setu' Mother care program</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- JAVASCRIPT FILES -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="javascripts/vendor/jquery-1.11.0.min.js"><\/script>')</script>
    <script src="{{ url() }}/assets/coming_soon/js/bootstrap.min.js"></script>
    <script src="{{ url() }}/assets/coming_soon/js/jquery.cookie.js"></script>
    <script src="{{ url() }}/assets/coming_soon/js/front.js"></script>
    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <script>
      (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
      function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
      e=o.createElement(i);r=o.getElementsByTagName(i)[0];
      e.src='//www.google-analytics.com/analytics.js';
      r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
      ga('create','UA-XXXXX-X');ga('send','pageview');
    </script>
  </body>
</html>