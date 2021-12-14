<?php

function logout()
{   
        session_start();
        $varx = $_SESSION;
        if (count($varx) === 0){
        header("location: ./index.php");
        }
    }
    if (isset($_GET['logout_status']) && $_GET['logout_status'] == true) {
        session_start();
        unset($_SESSION["user"]);
        header("location: /index.php");
    }
    
?>