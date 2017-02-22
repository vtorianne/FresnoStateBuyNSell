<?php
echo <<<EOD
  <div id="wrap">
    <!-- Page Content -->
    <div class="container">
        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">{$postReturn["ProductName"]}
                </h1>
            </div>
        </div>
        <!-- /.row -->
        <!-- Portfolio Item Row -->
        <div class="row">

            <div class="col-md-8">
                <img class="img-responsive" src="{$postReturn["PicturePath"]}" alt="">
            </div>
            <div class="col-md-4">
                <a href="/FresnoStateBuyNSell/php/index.php?option=user-profile&user-id={$userReturn["UserID"]}">
                <h3>Seller: {$userReturn["FirstName"]} {$userReturn["LastName"]}</h3></a>
                <h4>Item Description</h4>
                <p>{$postReturn["Description"]}</p>
            </div>
            <div class="col-md-4">
                <h3>\${$postReturn["Price"]}</h3>
EOD;
                if($postReturn["Sold"] == 1){
                //display Sold icon
                echo '<strong class="text-success">SOLD </strong><small> </small><i class="glyphicon glyphicon-check"></i>';
                }
                else if($userID == $_SESSION["Current_User"]){
                //display mark as sold form
                echo <<<EOD
                <form action="/FresnoStateBuyNSell/php/index.php?option=mark-sold&post-id={$postID}" method="post">
                    <button type="submit">
                        <strong class="text-success">MARK AS SOLD </strong> <small></small><i class="glyphicon glyphicon-unchecked"></i>
                    </button>
                </form>
EOD;
                }
                echo <<<EOD
            </div>
        </div>
        <!-- /.row -->
        <hr>
        <h4><u>Comments</u></h4>
EOD;
        foreach($comments as $comment){
            echo <<<EOD
                <div class="media">
                    <div class="media-body">
                        <p>{$comment["Comment"]}</p>
                        <p>
                            <span class="reviewer-name"><strong>{$comment["FirstName"]} {$comment["LastName"]} </strong></span>
                            <span class="review-date">{$comment["CommentTimeStamp"]}</span>
                        </p>
                    </div>
                    </hr>
                </div>
EOD;
        }

        echo <<<EOD
            <div>
                <div>
                    <form action="/FresnoStateBuyNSell/php/index.php?option=add-comment&post-id={$postID}" method="post">
                        <div>
                            <textarea name="comment" id="comment" style="font-family:sans-serif;font-size:15px; width: 100%; margin-top: 20px;">Write a Comment
                            </textarea>
                        </div>
                        <input type="submit" value="Submit Comment">
                    </form>
                </div>
            </div>
            </br>
            <hr>
        </div>
  </div>
EOD;
