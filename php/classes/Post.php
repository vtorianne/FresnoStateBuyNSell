<?php
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    require_once "DB.php";
    class Post{
        
        public function getPosts($filters){
            $db = new DB();
            if($filters == NULL) {
                $sql = "SELECT * FROM products ORDER BY PostTime DESC;";
            }
            else{
                $sql = "SELECT * FROM products WHERE ";
                $searchfilterscount=0;
                if(array_key_exists("keywords", $filters) && $filters["keywords"] != ""){
                    $keywords = explode(" ", $filters["keywords"]);
                    $search_term = "%";
                    foreach($keywords as $keyword){
                        $search_term .= $keyword."%";
                    }
                    if ($searchfilterscount == 0 ){
                        $sql .= "'$search_term' LIKE ProductName OR '$search_term' LIKE Description";
                    }
                    else{
                        $sql .= " AND '$search_term' LIKE ProductName OR '$search_term' LIKE Description";
                    }
                    $searchfilterscount++;
                }
                if(array_key_exists("categoryID", $filters) && $filters["categoryID"] != ""){
                    $categoryID = $filters["categoryID"];
                    if ($searchfilterscount==0){
                        $sql .= "'$categoryID' = CategoryID";
                    }
                    else {
                        $sql .= " AND '$categoryID' = CategoryID";
                    }
                    $searchfilterscount++;
                }

                if($searchfilterscount == 0)
                    $sql.= " 1";

                if(array_key_exists("priceSort", $filters)){
                    $sql .= " ORDER BY Price ASC, PostTime DESC";
                }
                else {
                    $sql .= " ORDER BY PostTime DESC";
                }
                $sql .= ";";
            }

            $return = $db->query($sql);
            echo <<<EOD
             <div class="container">
            <div style="float:right; margin-top: 20px;" class="row">
                <div class="col-md-12">
                    <div class="input-group" id="adv-search">
                        <input type="disabled" class="form-control" placeholder="Search for listings"/>
                        <div class="input-group-btn">
                            <div class="btn-group" role="group">
                                <div class="dropdown dropdown-lg">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
                                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                                     <form class="form-horizontal" role="form" action="/FresnoStateBuyNSell/php/index.php" method="post">
                                          
                                     <!------START FILTER BY FORM GROUP----->
                                     
                                     <!------START CATEGORY GROUP----->
                                        <div class="form-group">
                                            <label for="Categories">Categories</label>
                                                    <select class="form-control" name="category">
                                                    <optgroup label="Categories">
                                                    <option value="">All Categories</option>
EOD;
                                                    $sql = "SELECT * FROM categories;";
                                                    $catReturn = $db->execute($sql);
                                                    while($row = $catReturn->fetch(PDO::FETCH_ASSOC)){
                                                        echo "<option value='".$row["CategoryID"]."'>".$row["CategoryName"]."</option>'";
                                                    }
                                                    echo <<<EOD
                                                    </optgroup>
                                            </select>
                                        </div>
                                    <!------END CATEGORY FORM GROUP----->
                                     
                                        <input type="checkbox" name="priceSort" value="lowtohigh">  Sort by price low to high
                                        
                                        </br>
                                        </br>
                                    <!------END PRICE LOW TO HIGH FORM GROUP----->
                
                
                                    <!-------START CONTAINS WORDS FORM GROUP---->
                                          <div class="form-group">
                                            <label for="contain">Contains the words</label>
                                            <input class="form-control" type="text" name="keywords"/>
                                          </div> 
                                    <!-------END CONTAINS WORDS FORM GROUP----->
                                          
                                          <button type="submit" class="btn btn-primary" name="searchSubmit"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                       
                                        </form>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                            </div>
                           <!-----------DIV ABOVE THIS LINE ENDS CLASS BTN GROUP role="group"---------------->
                        </div>
                    <!-----------DIV ABOVE THIS LINE ENDS CLASS "input-group-btn"---------------->
                    </div>
                    <!-------------------DIV ABOVE THIS LINE ENDS "input-group adv-search"---------------->
                  </div>
                <!-----------DIV ABOVE THIS LINE ENDS "col-md-12"---------------->
                </div>
            <!-------------------DIV ABOVE THIS LINE ENDS THE CLASS "row"-------------------->
            </div>
            <!---------------------DIV ENDS THE CLASS CONTAINER--------------------------->
             <!-- Page Content -->
            <div class="container">
        
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Listings
                        </h1>
                    </div>
                </div>
EOD;
            $count = 0;
            while($row = $return->fetch(PDO::FETCH_ASSOC)){
                //display post html
                //if sold, show sold icon
                //if($row["Sold"] == 1){
                    //display Sold icon/text
                //}
                if($count%4 == 0)
                    echo '<div class="row">';
                if($count%4 != 3){//not last one in row
                    echo <<<EOD
                    <div style="border-right: 1px solid #aaa;" class="col-md-3 portfolio-item">
                        <a href="/FresnoStateBuyNSell/php/index.php?option=listing&post-id={$row["ProductID"]}">
                            <img style="padding-top: 10px;" class="img-responsive" src="{$row["PicturePath"]}" alt="">
                        </a>
                        <h4>{$row["ProductName"]}<small> - Random Item Stuff</small></h4>
                    </div>
EOD;
                }
                else{
                    echo <<<EOD
                    <div class="col-md-3 portfolio-item">
                        <a href="/FresnoStateBuyNSell/php/index.php?option=listing&post-id={$row["ProductID"]}">
                            <img style="padding-top: 10px;" class="img-responsive" src="{$row["PicturePath"]}" alt="">
                        </a>
                        <h4>{$row["ProductName"]}<small> - Random Item Stuff</small></h4>
                    </div>
                    </div>
EOD;
                }
                $count++;
            }
            if($count%4 != 0)
                echo "</div>";
        }
        
        public function getPostDetails($postID){ 
            $db = new DB();
            $sql = "SELECT * FROM products WHERE ProductID = $postID;"; //get all post fields
            $postReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            $userID = $postReturn["UserID"];
            $sql = "SELECT UserID, FirstName, LastName FROM users WHERE UserID = $userID;"; //get User details (probably just name fields and id)
            $userReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            //display logic here
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
EOD;
            $this->getComments($postID);

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

        }
        
        public function createPost($postData){
            $db = new DB();
            $userID = $_SESSION["Current_User"];
            $productname = $postData["title"];
            $categoryID = $postData["category"];
            $price = $postData["price"];
            $description = $postData["desc"];
            $picturepath = $postData["pic"];
            $sql = "INSERT INTO products (UserID, ProductName, CategoryID, Price, Description, PicturePath) VALUES ($userID, '$productname', $categoryID, $price, '$description', '$picturepath'); "; //insert new posts
            $db->execute($sql);
        }
        
        public function markSold($postID){
            $currUserID = $_SESSION["Current_User"];
            $db = new DB();
            $sql = "SELECT UserID FROM products WHERE ProductID = $postID;"; //get Post's userID
            $return = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
            if($return["UserID"] != $currUserID ){ //check if the current userID matches the post creator's ID
                return false;
            }
            else{
                $sql = "UPDATE products SET Sold=1 WHERE ProductID = $postID;"; //update "Sold" to true
                $db->execute($sql);
                return true;
            }
        }
        
        public function addComment($postID, $commentData){
            $db = new DB();
            $currUserID = $_SESSION["Current_User"];
            $comment = $commentData["comment"];
            $sql = "INSERT INTO comments (ProductID, UserID, Comment) VALUES ($postID, $currUserID, '$comment');";
            $db->execute($sql);
        }
        
        public function getComments($postID){
            $db = new DB();
            $sql = "SELECT * FROM comments WHERE ProductID = $postID ORDER BY CommentTimeStamp ASC;"; //get all comments for a post
            $return = $db->query($sql);
            echo "<h4><u>Comments</u></h4>";
            while($row = $return->fetch(PDO::FETCH_ASSOC)){
                $commenterID = $row["UserID"];
                $sql = "SELECT * FROM users WHERE UserID = $commenterID;"; //get name of commenter
                $userReturn = $db->query($sql)->fetch(PDO::FETCH_ASSOC);
                echo <<<EOD
                <div class="media">
                    <div class="media-body">
                        <p>{$row["Comment"]}</p>
                        <p><span class="reviewer-name"><strong>{$userReturn["FirstName"]} {$userReturn["LastName"]}</strong></span><span class="review-date">{$row["CommentTimeStamp"]}</span></p>
                    </div>
                </hr>
                </div>
EOD;

            }
        }
        
    }