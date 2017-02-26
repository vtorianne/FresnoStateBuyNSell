<?php
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    session_start();
    require_once "classes/User.php";
    require_once "classes/Post.php";
    $user = new User();
    $post = new Post();
    $option = (isset($_GET['option']) ? $_GET['option'] : null);
    if(!(isset($_SESSION["Current_User"]) && $_SESSION["Logged_In"])){
        switch($option){
            case null:
                //Home page (for users not logged in)
                include "../html/index.html";
                break;
            case "login":
                if(isset($_POST['email']) && isset($_POST['password'])){
                    if($user->login()){
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
                    if($user->register()){ //if success creating user
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
                $post->getPosts();
                break;
            case "listing":
                $post->getPostDetails();
            break;
            case "logout":
                $user->logout();
                header('Location: index.php');
                break;
            case "create-post":
                if(isset($_POST["createSubmit"])){
                    $post->createPost();
                    header("Location: index.php");
                }
                else{
                    header("Location: createpost.php");
                }
                break;
            case "mark-sold":
                $postID = $_GET["post-id"];
                if($post->markSold($postID))
                    header("Location: index.php?option=listing&post-id=$postID"); //redirect back to same page
                else
                    header("Location: index.php?option=forbidden");
                break;
            case "add-comment":
                $postID = $_GET["post-id"];
                $post->addComment();
                header("Location: index.php?option=listing&post-id=$postID"); //redirect back to same page
                break;
            case "user-profile":
                $user->getUserProfile();
                break;
            case "add-review":
                $user->review();
                $profileID = $_GET["user-id"];
                header("Location: index.php?option=user-profile&user-id=$profileID");
                break;
            case "add-profile-pic":
                $user->addProfilePic();
                header("Location: index.php?option=user-profile");
                break;
            default:
                echo "Forbidden Access/Already Logged In.";
                break;
        }
        
    }
