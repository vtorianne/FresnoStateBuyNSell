<?php
echo <<<EOD
            <div id="wrap">
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
            foreach($categories as $category){
                echo "<option value='".$category["CategoryID"]."'>".$category["CategoryName"]."</option>'";
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
            foreach($posts as $post){
                if($post["Sold"] == 1){
                    $sold = '<div style="white-space: nowrap;"><strong class="text-success">SOLD </strong><small> </small><i class="glyphicon glyphicon-check"></i></div>';
                }
                else{
                    $sold = '';
                }
                if($count%4 == 0)
                    echo '<div class="row">';
                if($count%4 != 3){//not last one in row
                    echo <<<EOD
                    <div style="border-right: 1px solid #aaa;" class="col-md-3 portfolio-item">
                        <a href="/FresnoStateBuyNSell/php/index.php?option=listing&post-id={$post["ProductID"]}">
                            <img style="padding-top: 10px;" class="img-responsive" src="{$post["PicturePath"]}" alt="">
                        </a>
                        <h4>{$post["ProductName"]}<small> - \${$post["Price"]} {$sold}</small></h4>
                    </div>
EOD;
                }
                else{
                    echo <<<EOD
                                <div class="col-md-3 portfolio-item">
                                    <a href="/FresnoStateBuyNSell/php/index.php?option=listing&post-id={$post["ProductID"]}">
                                        <img style="padding-top: 10px;" class="img-responsive" src="{$post["PicturePath"]}" alt="">
                                    </a>
                                    <h4>{$post["ProductName"]}<small> - \${$post["Price"]} {$sold}</small></h4>
                                </div>
                                </div>
EOD;
                }
                $count++;
            }
            if($count%4 != 0)
                echo "</div>";
echo "</div></div>";