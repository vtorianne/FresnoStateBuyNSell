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
                echo "Home, not logged in";
                ///include "";
                break;
            case "login":
                var_dump($_POST);
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
                        echo "wrong username/password. try to login again";
                        //include "";  //display login form with wrong username/password message
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
                        echo "Registered!";
                        echo "<a href=index.php>Go</a>";
                    }
                    else{
                        //error creating user or user email already exists
                        echo "error registering";
                        //include "";  //display registration form w/ existing username/email/error message
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
                $postID = $GET["postID"];
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
                $postID = $_POST["postID"];  //or from GET?
                if($post->markSold($postID))
                    header("Location: index.php?option=listing&postID=$postID"); //redirect back to same page
                else
                    header("Location: index.php?option=forbidden");
                break;
            case "add-comment":
                $post = new Post();
                $postID = $_POST["postID"];  //or from GET?
                $commentData = array(
                                                "comment" => $POST["comment"]
                                            );
                $post->addComment($postID, $commentData);
                 header("Location: index.php?option=listing&postID=$postID"); //redirect back to same page
                break;
            case "user-profile":
                $user = new User();
                //header
                $user->getUserProfile($_GET["userID"]);
                //footer
                break;
            case "review":
                $user = newUser();
                $profileID = $_POST["profileID"]; //or from GET?
                $reviewData = array(
                                            "comment" => $POST["comment"],
                                            "rating" => $POST["numStars"]
                                        );
                $user->review($reviewData);
                break;
            default:
                echo "Forbidden Access/Already Logged In.";
                //^- replace with message page?
                break;
        }
        
    }
