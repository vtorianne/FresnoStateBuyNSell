<?php
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    if(!isset($_SESSION))
        session_start();
    require_once "classes/UserAccount.php";
    require_once "classes/UserProfile.php";
    require_once "classes/PostRetrieval.php";
    require_once "classes/PostManagement.php";
    require_once "../../PHPMailer-master/PHPMailerAutoload.php";
    require_once "../../EmailPassword.php";
    require_once "views/email.php";
    require_once "views/passresetemail.php";
    require_once "views/acclocked.php";
    $userAccount = new UserAccount();
    $userProfile = new UserProfile();
    $postRetrieval = new PostRetrieval();
    $postManagement = new PostManagement();
    $option = (isset($_GET['option']) ? $_GET['option'] : null);
    if($option == 'send-validation-email' || $option == 'validate-email'){
        switch($option) {
            case 'send-validation-email':
                if($userAccount->sendValidationEmail()){
                    $message = "A validation email has been sent.";
                    $buttonText = "Resend Email";
                    $buttonLink = "http://localhost/FresnoStateBuyNSell/php/index.php?option=send-validation-email";
                    if(isset($_GET["user-id"])){
                        $buttonLink .= "&user-id=".$_GET['user-id'];
                    }
                    $buttonIcon = "fa fa-fw fa-envelope-open-o";
                    if(isset($_SESSION["Current_User"])){
                        include "../html/header_style1.html";
                        include "views/splash_page.php";
                        include "../html/footer2.html";
                    }
                    else{
                        include "../html/logged_out_header.html";
                        include "views/splash_page.php";
                        include "../html/footer.html";
                    }
                }
                else{
                    echo "Forbidden Access";
                }
                break;
            case 'validate-email':
                if(isset($_SESSION["Email_Validated"]) && $_SESSION["Email_Validated"] == true){
                    echo "Forbidden access. Email already validated";
                }
                else if($userAccount->validateEmail()){
                    //splash page saying "email validated" w/ button for "continue to site"
                    //if logged in, button link is to index, else button link is to login
                    $message = "Email has been validated. ";
                    $buttonText = "Buy/Sell";
                    if(isset($_SESSION["Current_User"])){
                        $buttonLink = "http://localhost/FresnoStateBuyNSell/php/index.php";
                    }
                    else{
                        $buttonLink = "http://localhost/FresnoStateBuyNSell/php/index.php?option=login";
                    }
                    $buttonIcon = "fa fa-fw fa-money";
                    if(isset($_SESSION["Current_User"])){
                        include "../html/header_style1.html";
                        include "views/splash_page.php";
                        include "../html/footer2.html";
                    }
                    else{
                        include "../html/logged_out_header.html";
                        include "views/splash_page.php";
                        include "../html/footer.html";
                    }
                }
                else{
                    //splash page with error message, should button be displayed?
                    //echo "email not validated";
                    $message = "Error validating email.";
                    $buttonText = "Resend Email";
                    $buttonLink = "http://localhost/FresnoStateBuyNSell/php/index.php?option=send-validation-email";
                    if(isset($_GET["user-id"])){
                        $buttonLink .= "&user-id=".$_GET['user-id'];
                    }
                    $buttonIcon = "fa fa-fw fa-envelope-open-o";
                    if(isset($_SESSION["Current_User"])){
                        include "../html/header_style1.html";
                        include "views/splash_page.php";
                        include "../html/footer2.html";
                    }
                    else{
                        include "../html/logged_out_header.html";
                        include "views/splash_page.php";
                        include "../html/footer.html";
                    }
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
                    /*if($user->login()){
                        header('Location: index.php'); //redirect to home page if success
                    }
                    else{
                        include "../html/signinerror.html";  //display login form with wrong username/password message
                    }*/
                    switch($userAccount->login()){
                        case "success":
                            header('Location: index.php');
                            break;
                        case "wrong_email_or_password":
                            include "../html/signinerror.html";
                            break;
                        case "account_locked":
                            echo "splash page will go here";
                            break;
                    }
                }
                else{
                    //display login form
                    include "../html/signin.html";
                }
                break;
            case "register":
                if(isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email']) && isset($_POST['password'])){
                    if($userAccount->register()){ //if success creating user
                        //display splash page for registration
                        $message = "Account has been created and a validation email has been sent.";
                        $buttonText = "Resend Email";
                        $buttonLink = "http://localhost/FresnoStateBuyNSell/php/index.php?option=send-validation-email";
                        $buttonIcon = "fa fa-fw fa-envelope-open-o";
                        if(isset($_SESSION["Current_User"])){
                            include "../html/header_style1.html";
                            include "views/splash_page.php";
                            include "../html/footer2.html";
                        }
                        else{
                            include "../html/logged_out_header.html";
                            include "views/splash_page.php";
                            include "../html/footer.html";
                        }
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
            case "send-password-reset":
                if($userAccount->sendPasswordResetEmail()){
                        $message = "Password request has been created and a validation email has been sent.";
                        $buttonText = "Resend Email";
                        $buttonLink = "http://localhost/FresnoStateBuyNSell/php/index.php?option=send-password-reset";
                        $buttonIcon = "fa fa-key";
                        include "views/splash_page.php";
                    if(isset($_SESSION["Current_User"])){
                        include "../html/header_style1.html";
                        include "views/splash_page.php";
                        include "../html/footer2.html";
                    }
                    else{
                        include "../html/logged_out_header.html";
                        include "views/splash_page.php";
                        include "../html/footer.html";
                    }
                }
                else{
                   // echo "email not found/try again";
                    $message = "No account was found for that email.";
                    $buttonText = "Try Again";
                    $buttonLink = "http://localhost/FresnoStateBuyNSell/html/psemail.html";
                    $buttonIcon = "fa fa-refresh";
                    if(isset($_SESSION["Current_User"])){
                        include "../html/header_style1.html";
                        include "views/splash_page.php";
                        include "../html/footer2.html";
                    }
                    else{
                        include "../html/logged_out_header.html";
                        include "views/splash_page.php";
                        include "../html/footer.html";
                    }
                }
                break;
            case "password-reset":
                if(isset($_POST["resetSubmit"])){
                    $userAccount->resetPassword();
                    $message = "Password has been reset. Start buying/selling.";
                    $buttonText = "Log in";
                    $buttonLink = "http://localhost/FresnoStateBuyNSell/php/index.php?option=login";
                    $buttonIcon = "fa fa-sign-in";
                    include "views/splash_page.php";
                    if(isset($_SESSION["Current_User"])){
                        include "../html/header_style1.html";
                        include "views/splash_page.php";
                        include "../html/footer2.html";
                    }
                    else{
                        include "../html/logged_out_header.html";
                        include "views/splash_page.php";
                        include "../html/footer.html";
                    }
                }
                else{
                    if($userAccount->checkHashToken()){ //or userID/hashToken GET parameters not set
                        //display form
                        require_once "../html/passreset.html";
                    }
                    else{
                        //display error/link to password reset
                        //echo "error/try again";
                        $message = "There was an issue processing the password reset request.";
                        $buttonText = "Try Again";
                        $buttonLink = "http://localhost/FresnoStateBuyNSell/html/psemail.html";
                        $buttonIcon = "fa fa-refresh";
                        if(isset($_SESSION["Current_User"])){
                            include "../html/header_style1.html";
                            include "views/splash_page.php";
                            include "../html/footer2.html";
                        }
                        else{
                            include "../html/logged_out_header.html";
                            include "views/splash_page.php";
                            include "../html/footer.html";
                        }
                    }
                }
                break;
            default:
                echo "Forbidden Access";
               break;
        }
    }
    else{
        if($option == "logout"){
            $userAccount->logout();
            header('Location: index.php');
        }
        else if((!isset($_SESSION["Email_Validated"]) || $_SESSION["Email_Validated"] == false)){
            //splash page saying "email needs to be validated", with button for "resend" email
            $message = "Email needs to be validated.";
            $buttonText = "Resend Email";
            $buttonLink = "http://localhost/FresnoStateBuyNSell/php/index.php?option=send-validation-email";
            $buttonIcon = "fa fa-fw fa-envelope-open-o";
            if(isset($_SESSION["Current_User"])){
                include "../html/header_style1.html";
                include "views/splash_page.php";
                include "../html/footer2.html";
            }
            else{
                include "../html/logged_out_header.html";
                include "views/splash_page.php";
                include "../html/footer.html";
            }
        }
        else{
            switch($option){
                case null:
                    $postRetrieval->getPosts();
                    break;
                case "my-listings":
                    $postRetrieval->getCurrUserPosts();
                    break;
                case "listing":
                    $postRetrieval->getPostDetails();
                    break;
                case "my-listing":
                    if(isset($_POST["editSubmit"])){
                        $postID = $_GET["post-id"];
                        $postManagement->editPost();
                        header("Location: index.php?option=my-listing&post-id=$postID");
                    }
                    else{
                        $postRetrieval->getCurrUserPostDetails();
                    }
                    break;
                case "create-post":
                    if(isset($_POST["createSubmit"])){
                        $postManagement->createPost();
                        header("Location: index.php");
                    }
                    else{
                        header("Location: createpost.php");
                    }
                    break;
                case "mark-if-sold":
                    $postID = $_GET["post-id"];
                    if($postManagement->markIfSold())
                        header("Location: index.php?option=my-listing&post-id=$postID"); //redirect back to same page
                    else
                        header("Location: index.php?option=forbidden");
                    break;
                case "add-comment":
                    $postID = $_GET["post-id"];
                    $postManagement->addComment();
                    header("Location: index.php?option=listing&post-id=$postID"); //redirect back to same page
                    break;
                case "delete-listing":
                    if($postManagement->deletePost()){
                        header("Location: index.php?option=my-listings");
                    }
                    else{
                        header("Location: index.php?option=forbidden");
                    }
                    break;
                case "update-listing-pic":
                    $postID = $_GET["post-id"];
                    $postManagement->updateListingPic();
                    header("Location: index.php?option=my-listing&post-id=$postID");
                    break;
                case "user-profile":
                    $userProfile->getUserProfile();
                    break;
                case "add-review":
                    $userProfile->review();
                    $profileID = $_GET["user-id"];
                    header("Location: index.php?option=user-profile&user-id=$profileID");
                    break;
                case "add-profile-pic":
                    $userProfile->addProfilePic();
                    header("Location: index.php?option=user-profile");
                    break;
                default:
                    echo "Forbidden Access";
                    break;
            }
        }
    }
