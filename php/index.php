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
                echo "Home page/default posts view (for users logged in)";
                var_dump($_SESSION);
                $post = new Post();
                //include ""; //header
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
                    $post->getPosts();
                }
                //include ""; //footer
                break;
            case "listing":
                $postID = $_GET["post-id"];
                $post = new Post();
                //header?
                $post->getPostDetails($postID);
               //$post->getComments($postID);  //either here or as helper function for the above
                //footer?
            break;
            case "logout":
                $user = new User();
                $user->logout();
                header('Location: index.php');
                break;
            case "create-post":
                ;
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
                //header
                $profileID = (isset($_GET["user-id"]) ? $_GET["user-id"] : ($_SESSION["Current_User"]));
                $user->getUserProfile($profileID);
                //footer
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
            default:
                echo "Forbidden Access/Already Logged In.";
                //^- replace with message page?
                break;
        }
        
    }
