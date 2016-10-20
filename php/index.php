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
                    $user->login($loginData);
                    //what if error?
                    header('Location: index.php'); //redirect to home page if success
                }
                else{
                    //display login form
                    ///include "";
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
                    }
                    else{
                        //error creating user or user email already exists
                    }
                }
                else{
                    //display registration form
                    ///include "";
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