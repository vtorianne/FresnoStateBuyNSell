<!DOCTYPE html>
<html>
  <head>

		<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title></title>


    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="../css/main.css">

    <!-- Custom Fonts -->
    <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
  </head>
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
                    <a href="/FresnoStateBuyNSell/php/index.php">Home</a>
                </li>
                <li>
                    <a href="/FresnoStateBuyNSell/php/index.php?option=user-profile">My Profile</a>
                </li>
                <li>
                    <a href="/FresnoStateBuyNSell/php/index.php?option=create-post">Create Post</a>
                </li>
                <li>
                    <a href="/FresnoStateBuyNSell/php/index.php?option=logout">Log Out</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>


    <!--login modal-->
<div style="top: 50px; left: 0px; right: 0px; bottom: 0px;"  id="loginModal" class="modal show bgpicture" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h1 class="text-center">Create a Listing</h1>
      </div>
      <div class="modal-body">
          <form class="form col-md-12 center-block" action="index.php?option=create-post" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <input class="form-control" type="text" placeholder="Title" name="title" required>
            </div>
            <div class="form-group">
              <select name="category" required>
                  <option value="">Select Category</option>
                  <?php
                    require_once("classes/DB.php");
                    $db = new DB();
                    $sql = "SELECT * FROM categories;";
                    $return = $db->execute($sql);
                    while($row = $return->fetch(PDO::FETCH_ASSOC)){
                        echo "<option value='".$row["CategoryID"]."'>".$row["CategoryName"]."</option>'";
                    }
                  ?>
                </optgroup>
              </select>
            </div>
            <div class="form-group">
              <textarea class="form-control" placeholder="Description" name="desc"></textarea>
            </div>
            <div class="form-group">
              <input class="form-control" type="number" min="0.00" name="price" placeholder="Price $0.00">
            </div>
            <div class="form-group">
             <input type="file" name="pic" accept="image/*" required>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit" name="createSubmit">Submit</button>
            </div>
          </form>
      </div>
      <div class="modal-footer">
          <div class="col-md-12">
              <a href="/FresnoStateBuyNSell/php/index.php" class="btn btn-default">Cancel</a>
          </div>
      </div>
  </div>
  </div>
</div>

    <!-- Footer -->
    <footer style="z-index: 9999;" class="navbar navbar-fixed-bottom">
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
    <script src="../js/jquery.js"></script>


  <!-- script references -->
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>