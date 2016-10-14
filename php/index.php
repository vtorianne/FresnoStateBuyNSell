<?php
    require_once "classes/User.php";
    $option = (isset($_GET['option']) ? $_GET['option'] : null);
    if(!$logged_in){
        switch($option){
            case null:
                echo "Home page (for users not logged in)";
                break;
            case "login":
                ;
                break;
            case "register":
                if(isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email']) && isset($_POST['password']){
                    $regData = array(
                                        'firstName' => $_POST['firstName'],
                                        'lastName' => $_POST['lastName'],
                                        'email' => $_POST['email'],
                                        'password' => $_POST['password']
                                    );
                    $user = new User();
                    if($user->register($regData)){
                        //success creating user
                        header('Location: index.php');  //this needs to be after the cookie/session variable has been set
                    }
                    else{
                        //error creating user or user email already exists
                    }
                }
                else{
                    //display registration form
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
            case "e.g. create post":
                ;
                break;
            default:
                echo "Forbidden Access/Already Logged In.";
                break;
        }
        
    }