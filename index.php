<?php

session_start();

if($_SESSION['username']){
    
    header("Location: ./view/sala.php");
    exit();

} else {

    header("Location: ./view/principal.php");
    exit();

}

?>