<?php
echo <<<EOD
            <div id="wrap">
            <div class="container">
            <div style="float:right; margin-top: 20px;" class="row">
                <div class="col-md-12">
                    <div class="input-group" id="adv-search">
                        <input type="disabled" name="keywords" form="searchform" class="form-control" placeholder="Search for listings"/>
                        <div class="input-group-btn">
                            <div class="btn-group" role="group">
                                <div class="dropdown dropdown-lg">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded=""><span class="caret"></span></button>
                                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                                    
                                    <form id="searchform" class="form-horizontal" role="form" action="/FresnoStateBuyNSell/php/index.php" method="post">
                                          
    <!------START FILTER BY FORM GROUP----->  
    <div class="filter-formbuy">
        <label for="minprice">Min Price: </label>
        <input class="" type="" name="Min"/>
    
        <label for="maxprice">Max Price: </label>
        <input class="" type="" name="Max" />
    </div>
    
    <br />
    <br />
    
    <div class="new">
    <input type="checkbox" name="New" value="1"/> New
    </div>
    <!-------END new-------->
    
    <div class="used">
    <input type="checkbox" name="Used" value="2"/> Used
    </div>

    
    <br />
    <br />
    
    <div class="filtercategory">
    <div class="category">
    <span>Categories:</span>
    <select class="category" name="category">
    <optgroup label="Categories">
        <option selected value="">---</option>
EOD;
        foreach($categories as $category){
            echo "<option value='".$category['CategoryID']."'>".$category['CategoryName']."</option>";
        }
echo<<<EOD
    </optgroup>
    </select>
    </div>
    
    <div class="sortby">
    <span>Sort By:</span>
    <select class="filter" name="Filter">
    <optgroup label="Filters">
    <option value="Most Recent" selected>Most Recent</option>
    <option value="Price low to high">Price Low to High</option>
    <option value="Best User rating">Best User Rating</option>
    <option value="Last Updated">Last Updated</option>
    </optgroup>
    </select>
    </div>
    <!-------END sortby--------->
    </div>
    <!-------END filtercategory------>
    
    <br />              
                                        
                                    <!------END PRICE LOW TO HIGH FORM GROUP----->
                                    </div>
                                </div>
                                <button type="submit" name="searchSubmit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                            </div>
                            </form>
                            <!---------END FORM--------->
                            
                            
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
                        <a href="/FresnoStateBuyNSell/php/index.php?option=listing&post-id={$post["ProductID"]}">
                        <h4>{$post["ProductName"]}<small> - \${$post["Price"]} {$sold}</small></h4>
                        </a>
                    </div>
EOD;
                }
                else{
                    echo <<<EOD
                                <div class="col-md-3 portfolio-item">
                                    <a href="/FresnoStateBuyNSell/php/index.php?option=listing&post-id={$post["ProductID"]}">
                                        <img style="padding-top: 10px;" class="img-responsive" src="{$post["PicturePath"]}" alt="">
                                    </a>
                                    <a href="/FresnoStateBuyNSell/php/index.php?option=listing&post-id={$post["ProductID"]}">
                                    <h4>{$post["ProductName"]}<small> - \${$post["Price"]} {$sold}</small></h4>
                                    </a>
                                </div>
                                </div>
EOD;
                }
                $count++;
            }
            if($count%4 != 0)
                echo "</div>";
echo "</div></div>";