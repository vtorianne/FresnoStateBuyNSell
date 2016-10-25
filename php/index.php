<?php
    session_start();
    require_once "classes/User.php";
    $option = (isset($_GET['option']) ? $_GET['option'] : null);
    if(!(isset($_SESSION["Current_User"]) && $_SESSION["Logged_In"])){
        switch($option){
            case null:
                //Home page (for users not logged in)
                echo "Home, not logged in";
                ///include "";
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
                        //header('Location: index.php');  //this needs to be after the cookie/session variable has been set
                        //maybe go to log in form next?
                        echo "Registered!";
                    }
                    else{
                        //error creating user or user email already exists
                        echo "error registering";
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
                break;
            case "logout":
                $user = new User();
                $user->logout();
                header('Location: index.php');
                break;
            case "e.g. create post":
                ;
                break;
            default:
                echo "Forbidden Access/Already Logged In.";
                break;
        }
        
    }
