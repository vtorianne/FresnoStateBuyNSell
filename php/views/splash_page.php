<?php /*this section will be moved elsewhere later on,
        I just have it here to help for making/testing this. */
   /* $message = "Hello World";
    $buttonText = "Buy/Sell";
    $buttonLink = "#";
    $buttonIcon = "fa fa-fw fa-money";*/

?>
<html>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="../../css/style.css">
    <link href="../css/main.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

<body>
<!-- Navigation -->
<nav style="z-index: 9999" class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
    <div class="container topnav">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">

                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
                <span class="navbar-brand topnav">Fresno State Buy N' Sell</span>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="listings.html">Home</a>
                    </li>
                    <li>
                        <a href="profile.html">My Profile</a>
                    </li>
                    <li>
                        <a href="createpost.html">Create Post</a>
                    </li>
                    <li>
                        <a href="">Log Out</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    <!-- Header -->
    <div class="registered div-background">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-message">
                        <h1>
                              <img style=" width: 50%; " ;="" src="../img/buynsell.png">
                            </h1>
                        <h1><?php echo $message; ?></h1>
                        <hr class="intro-divider">
                        <ul class="list-inline intro-social-buttons">
                            <center>
                              <a href="<?php echo $buttonLink;?>"
                                 class="btn btn-default btn-lg"><i
                                      class="<?php echo $buttonIcon;?>"></i><?php echo $buttonText;?></a>
                          </li>
                            </center>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container -->
    </div>
    <!-- /.intro-header -->

    <!-- Footer -->
    <footer style="z-index: 9999; background: none;">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <ul class="list-inline">
              <li>
                 <a href="/FresnoStateBuyNSell/php/index.php">Home</a>
              </li>
              <li class="footer-menu-divider">⋅</li>
              <li>
                <a href="/FresnoStateBuyNSell/php/index.php?option=user-profile">My Profile</a>
              </li>
              <li class="footer-menu-divider">⋅</li>
              <li>
                <a href="/FresnoStateBuyNSell/php/index.php?option=create-post">Create Post</a>
              </li>
              <li class="footer-menu-divider">⋅</li>
              <li>
                <a href="/FresnoStateBuyNSell/php/index.php?option=logout">Log Out</a>
              </li>
            </ul>
            <p class="copyright text-muted small">Copyright © Fresno State Buy N' Sell 2016. All Rights Reserved</p>
          </div>
        </div>
      </div>
    </footer>
        <!-- jQuery -->

        <script src="../js/jquery.js "></script>
        <!-- Bootstrap Core JavaScript -->
        <script src="../js/bootstrap.min.js "></script>

        <script src="../js/jquery.js"></script>
        <!-- Bootstrap Core JavaScript -->
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>
