<div id="wrap">
    <div class="container">
        <div class="row product">
            <div class="col-md-5 col-md-offset-0"><img class="img-responsive" src="<?php echo $userImg; ?>"></div>
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-4 col-sm-6">
                        <h5><?php echo $return["FirstName"]; ?></h5>
                        <h5><?php echo $return["LastName"]; ?></h5></div>
                    </div>
                <p><?php echo $return["Email"]; ?></p>
                <?php
                    if($userID == $_SESSION["Current_User"]) {
                        echo <<<EOD
                        <p>Add/Update Profile Picture:</p> 
                        <form method="post" action="/FresnoStateBuyNSell/php/index.php?option=add-profile-pic" enctype="multipart/form-data">
                        <input type="file" name="pic" accept="image/*">
                        <button style="margin-top: 10px;" type="submit" class="btn btn-primary btn-sm">Upload</button>
                        </form>
EOD;
                    }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-sm-6">
                <h4>Overall Rating:</h4></div>
            <div style="margin-top: 13px;" class="col-sm-6 social-icons">
                <div> <span style="padding-right: 10px;">Average: <?php echo $averageRating; ?></span>
                    <?php
                        if($reviewedYet){
                            for($i=0; $i<$numWholeStars; ++$i){
                                echo "<i class=\"fa fa-star\"></i>";
                            }
                            if($halfStar >= 0.5) {
                                echo "<i class=\"fa fa-star-half\"></i>";
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <hr>
        <div class="page-header">
            <h3>Reviews</h3></div>
        <?php
            if($reviewedYet){
                foreach($reviews as $review){
                    echo <<<EOD
                    <div class="media">
                        <div class="media-body">
                            <div>
EOD;
                    for($i=0; $i<$review["StarRating"]; ++$i){
                        echo "<i class=\"fa fa-star\"></i>";
                    }
                    echo <<<EOD
                        </div>
                        <p>{$review["ReviewText"]}</p>
                        <p>
                            <span class="reviewer-name">
                                <strong>{$review["FirstName"]} {$review["LastName"]}</strong>
                            </span>
                            <span class="review-date">{$review["ReviewTimeStamp"]}</span>
                        </p>
                    </div>
                </div>
EOD;
                }
            }

            if($userID != $_SESSION["Current_User"]){
            //show review form
                echo <<<EOD
                <div>
                    <hr>
                    <h4>Review User:</h4>
                    <form action="index.php?option=add-review&user-id={$userID}" method="post">
                        <div>
                            <textarea name="comment" id="comment" style="font-family:sans-serif;font-size:15px; width: 100%; margin-top: 20px;">Write a Review
                            </textarea>
                            <fieldset>
                                <span class="star-cb-group">
                                  <input type="radio" id="rating-5" name="rating" value="5" /><label for="rating-5">5</label>
                                  <input type="radio" id="rating-4" name="rating" value="4" checked="checked" /><label for="rating-4">4</label>
                                  <input type="radio" id="rating-3" name="rating" value="3" /><label for="rating-3">3</label>
                                  <input type="radio" id="rating-2" name="rating" value="2" /><label for="rating-2">2</label>
                                  <input type="radio" id="rating-1" name="rating" value="1" /><label for="rating-1">1</label>
                                  <input type="radio" id="rating-0" name="rating" value="0" class="star-cb-clear" /><label for="rating-0">0</label>
                                </span>
                            </fieldset>
                        </div>
                        <input type="submit" value="Submit Review " class="btn btn-primary">
                    </form>
                </div>
EOD;
            }
        ?>
    <hr>
    </div>
    </br>
    </div><!-- /.container -->
    </div>