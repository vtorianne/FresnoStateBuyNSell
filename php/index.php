<?php
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    session_start();
    require_once "classes/User.php";
    require_once "classes/Post.php";
    $option = (isset($_GET['option']) ? $_GET['option'] : null);
    if(!(isset($_SESSION["Current_User"]) && $_SESSION["Logged_In"])){
        switch($option){
            case null:
                //Home page (for users not logged in)
                include "../html/index.html";
                break;
            case "login":
                if(isset($_POST['email']) && isset($_POST['password'])){
                    $loginData = array(
                                         'email' => $_POST['email'],
                                         'password' => $_POST['password']
                                       );
                    $user = new User();
                    if($user->login($loginData)){
                        header('Location: index.php'); //redirect to home page if success
                    }
                    else{
                        include "../html/signinerror.html";  //display login form with wrong username/password message
                    }
                }
                else{
                    //display login form
                    include "../html/signin.html";
                }
                break;
            case "register":
                if(isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email']) && isset($_POST['password'])){
                    $regData = array(
                                        'firstName' => $_POST['firstName'],
                                        'lastName' => $_POST['lastName'],
                                        'email' => $_POST['email'],
                                        'password' => $_POST['password']
                                    );
                    $user = new User();
                    if($user->register($regData)){
                        //success creating user
                        //display splash page for registration
                        include "../html/registered.html";
                    }
                    else{
                        //error creating user or user email already exists
                        include "../html/registererror.html";  //display registration form w/ existing username/email/error message
                    }
                }
                else{
                    //display registration form
                    include "../html/register.html";
                }
                break;
            default:
                echo "Forbidden Access";
               break;
        }
    }
    else{
        switch($option){
            case null:
                $post = new Post();
                include "../html/header_style2.html"; //header
                if(isset($_POST['searchSubmit'])){
                    //get filters
                    $filters = array();
                    if(isset($_POST["priceSort"]))
                        $filters["priceSort"] = $_POST["priceSort"];
                    if(isset($_POST["keywords"]))
                        $filters["keywords"] = $_POST["keywords"];
                    if(isset($_POST["category"]))
                        $filters["categoryID"] = $_POST["category"];
                    
                    $post->getPosts($filters);
                }
                else{
                    $post->getPosts(null);
                }
                include "../html/footer.html"; //footer
                break;
            case "listing":
                $postID = $_GET["post-id"];
                $post = new Post();
                include "../html/header_style2.html"; //header
                $post->getPostDetails($postID);
                include "../html/footer.html"; //footer
            break;
            case "logout":
                $user = new User();
                $user->logout();
                header('Location: index.php');
                break;
            case "create-post":
                if(isset($_POST["createSubmit"])){
                    $target_file = "/FresnoStateBuyNSell/uploads/listing_pics/".basename($_FILES["pic"]["name"]);
                    $target_dir =  $_SERVER['DOCUMENT_ROOT'].$target_file;
                    move_uploaded_file($_FILES["pic"]["tmp_name"], $target_dir);
                    $postData = array(
                       "title" => $_POST["title"],
                       "desc" => (isset($_POST["desc"]) ? $_POST["desc"] : ""),
                       "category" => $_POST["category"],
                       "price" => $_POST["price"],
                       "pic" => $target_file
                    );
                    $post = new Post();
                    $post->createPost($postData);
                    header("Location: index.php");
                }
                else{
                    header("Location: createpost.php");
                }
                break;
            case "mark-sold":
                //get post data
                $post = new Post();
                $postID = $_GET["post-id"];  //or from GET?
                if($post->markSold($postID))
                    header("Location: index.php?option=listing&post-id=$postID"); //redirect back to same page
                else
                    header("Location: index.php?option=forbidden");
                break;
            case "add-comment":
                $post = new Post();
                $postID = $_GET["post-id"];
                $commentData = array(
                                    "comment" => $_POST["comment"]
                                );
                $post->addComment($postID, $commentData);
                header("Location: index.php?option=listing&post-id=$postID"); //redirect back to same page
                break;
            case "user-profile":
                $user = new User();
                include "../html/header_style2.html"; //header
                $profileID = (isset($_GET["user-id"]) ? $_GET["user-id"] : ($_SESSION["Current_User"]));
                $user->getUserProfile($profileID);
                include "../html/footer.html"; //footer
                break;
            case "add-review":
                $user = new User();
                $profileID = $_GET["user-id"];
                $reviewData = array(
                                    "comment" => $_POST["comment"],
                                    "rating" => $_POST["rating"]
                                );
                $user->review($profileID, $reviewData);
                header("Location: index.php?option=user-profile&user-id=$profileID");
                break;
            case "add-profile-pic":
                $target_file = "/FresnoStateBuyNSell/uploads/profile_pics/".basename($_FILES["pic"]["name"]);
                $target_dir =  $_SERVER['DOCUMENT_ROOT'].$target_file;
                move_uploaded_file($_FILES["pic"]["tmp_name"], $target_dir);
                $user = new User();
                $user->addProfilePic($target_file);
                header("Location: index.php?option=user-profile");
                break;
            default:
                echo "Forbidden Access/Already Logged In.";
                break;
        }
        
    }