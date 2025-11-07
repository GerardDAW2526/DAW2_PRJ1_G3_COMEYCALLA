<?php

session_start();

if($_SESSION['username']){
    
    header("Location: ./view/home.php");
    exit();

} else {

    header("Location: ./view/principal.php");
    exit();

}

?>