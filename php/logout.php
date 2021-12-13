<?php
function logout()
{   
    session_start();
    $varx = $_SESSION;
    if (count($varx) === 0){
    header("location: /index.php");
    }
}
    
?>