<?php
echo <<<EOD
  <div id="wrap">
    <!-- Page Content -->
    <div class="container">
        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">{$postReturn["ProductName"]}
                <input name="title" type="text" value="ProductName">
                </input>
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
                <!---<p>{$postReturn["Description"]}</p>---->
                <textarea name="" type="text" value="Description">Description
                </textarea>
                <h4>Item Condition</h4>
                <p>{$conditionReturn["ConditionName"]}</p>
                <select class="" name="condition">
                    <optgroup label="Condition">
                        <option value="">New</option>
                        <option value="">Used</option>
                    </optgroup>
                </select>
                <h4>Item Category</h4>
                <select class="" name="Filter">
                    <optgroup label="Categories">
                        <option selected value="">---</option>
EOD;
        foreach($categories as $category){
            echo "<option value='".$category['CategoryID']."'>".$category['CategoryName']."</option>";
        }
echo <<<EOD
                    </optgroup>
                </select>
                
                <p>Add/Update Profile Picture:</p> 
                        <form method="post" action="/FresnoStateBuyNSell/php/index.php?option=add-profile-pic" enctype="multipart/form-data">
                        <input type="file" name="pic" accept="image/*">
                        <button style="margin-top: 10px;" type="submit" class="btn btn-primary btn-sm">Upload</button>
                        </form>
            </div>
            
            <div class="col-md-4">
            
                <h3>\${$postReturn["Price"]}</h3>
                <input type="text" name="" value="Price">
                </input>
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
                        <strong class="text-success">MARK AS SOLD/UNSOLD </strong> <small></small><i class="glyphicon glyphicon-unchecked"></i>
                    </button>
                    
                    <button type="submit">
                        <strong>Add/Edit Changes</strong>
                    </button>
                </form>
                
                <button type="submit">
                    <strong>Delete Post</strong>
                </button>
EOD;
                }
                echo <<<EOD
            </div>
        </div>
        <!-- /.row -->
EOD;

        echo <<<EOD
           
        </div>
  </div>
EOD;
