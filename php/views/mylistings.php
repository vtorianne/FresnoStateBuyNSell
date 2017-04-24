<?php
echo <<<EOD
            <div id="wrap">
             <!-- Page Content -->
            <div class="container">
        
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">My Listings
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
                        <a href="/FresnoStateBuyNSell/php/index.php?option=my-listing&post-id={$post["ProductID"]}">
                            <img style="padding-top: 10px;" class="img-responsive" src="{$post["PicturePath"]}" alt="">
                        </a>
                        <a href="/FresnoStateBuyNSell/php/index.php?option=my-listing&post-id={$post["ProductID"]}">
                        <h4>{$post["ProductName"]}<small> - \${$post["Price"]} {$sold}</small></h4>
                        </a>
                    </div>
EOD;
                }
                else{
                    echo <<<EOD
                                <div class="col-md-3 portfolio-item">
                                    <a href="/FresnoStateBuyNSell/php/index.php?option=my-listing&post-id={$post["ProductID"]}">
                                        <img style="padding-top: 10px;" class="img-responsive" src="{$post["PicturePath"]}" alt="">
                                    </a>
                                    <a href="/FresnoStateBuyNSell/php/index.php?option=my-listing&post-id={$post["ProductID"]}">
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