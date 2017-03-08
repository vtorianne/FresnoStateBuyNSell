<?php
    ini_set('display_errors',1); 
    error_reporting(E_ALL);
    session_start();
    require_once "classes/User.php";
    require_once "classes/Post.php";
    $user = new User();
    $post = new Post();
    $option = (isset($_GET['option']) ? $_GET['option'] : null);
    if($option == 'send-validation-email' || $option == 'validate-email'){
        switch($option) {
            case 'send-validation-email':
                if($user->sendValidationEmail()){
                    echo "validation email sent";
                }
                else{
                    echo "Forbidden Access";
                }
                break;
            case 'validate-email':
                if($user->validateEmail()){
                    //splash page
                    echo "email validated";
                }
                else{
                    //splash page
                    echo "email not validated";
                }
                break;
        }
    }
    else if(!(isset($_SESSION["Current_User"]) && $_SESSION["Logged_In"])){
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
        if((!isset($_SESSION["Email_Validated"]) || $_SESSION["Email_Validated"] == false) && $option != "logout"){
            //header('Location: ');  //redirect to splash page saying user needs to validate email w/ button for resend
            echo "Email needs to be validated.";
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
                    if($post->markSold())
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
    }