<?php

if (!isset($_ENV['HOST'])){
    include_once('var_environ.php');
}

try {

    // We create a new instance of the class PDO
    $db = new PDO("mysql:host=".$_ENV['HOST'].";dbname=".$_ENV['DB'].";port=".$_ENV['DB_PORT'],  $_ENV['LOGIN'], $_ENV['PASSWORD']);

    //We want any issues to throw an exception with details, instead of a silence or a simple warning
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(Exception $e) {
    // We intantiate an Exception object in $e so we can use methods within this object to display errors nicely
    echo $e->getMessage();
    exit;
}