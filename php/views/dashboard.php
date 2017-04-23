<?php
echo <<<EOD
  <div id="wrap">
    <!-- Page Content -->
    <div class="container">
        <form method="post" action="/FresnoStateBuyNSell/php/index.php?option=my-listing&post-id={$postID}">
        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                <input name="title" type="text" value="{$postReturn["ProductName"]}">
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
                <h4>Item Description</h4>
                <textarea name="desc" type="text" value="{$postReturn["Description"]}">Description
                </textarea>
                <h4>Item Condition</h4>
                <select class="" name="condition">
                    <optgroup label="Condition">
                        <option value="1">New</option>
                        <option value="2">Used</option>
                    </optgroup>
                </select>
                <h4>Item Category</h4>
                <select class="" name="category">
                    <optgroup label="Categories">]
EOD;
foreach($categories as $category){
    echo "<option value='".$category['CategoryID']."'>".$category['CategoryName']."</option>";
}
echo <<<EOD
                    </optgroup>
                </select>
                
                <h3>
                \$<input type="text" name="price" value="{$postReturn["Price"]}">
                </input></h3>
                
                <button type="submit" name="editSubmit">
                    <strong>Add/Edit Changes</strong>
                </button>
                </form>
            </div>
            
            <div class="col-md-4">
EOD;
if($postReturn["Sold"] == 1){
    //display Sold icon
    echo '<strong class="text-success">SOLD </strong><small> </small><i class="glyphicon glyphicon-check"></i>';
    echo <<<EOD
         <form action="/FresnoStateBuyNSell/php/index.php?option=mark-if-sold&post-id={$postID}&sold=0" method="post">
            <button type="submit">
                <strong class="text-success">MARK AS UNSOLD </strong> <small></small><i class="glyphicon glyphicon-unchecked"></i>
            </button>
        </form>
EOD;

}
else{
    //display mark as sold form
    echo <<<EOD
                <form action="/FresnoStateBuyNSell/php/index.php?option=mark-if-sold&post-id={$postID}&sold=1" method="post">
                    <button type="submit">
                        <strong class="text-success">MARK AS SOLD </strong> <small></small><i class="glyphicon glyphicon-unchecked"></i>
                    </button>
                </form>
EOD;
}
echo <<<EOD
            <button type="submit">
                    <strong>Delete Post</strong>
                </button>
             <p>Update Listing Picture:</p> 
            <form method="post" action="/FresnoStateBuyNSell/php/index.php?option=add-profile-pic" enctype="multipart/form-data">
                <input type="file" name="pic" accept="image/*">
                <button style="margin-top: 10px;" type="submit" class="btn btn-primary btn-sm">Upload</button>
            </form>
            </div>
        </div>
        <!-- /.row -->
EOD;

echo <<<EOD
           
        </div>
  </div>
EOD;
