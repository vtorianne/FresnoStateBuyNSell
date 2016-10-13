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
                ;
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