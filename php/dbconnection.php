<?php

if (!isset($_ENV['HOST'])){
    include_once('var_environ.php');
    define("HOST", $_ENV['HOST']);
    define("DB", $_ENV['DB']);
    define("PORT", $_ENV['PORT']);
    define("LOGIN", $_ENV['LOGIN']);
    define("PASSWORD", $_ENV['PASSWORD']);
}

try {

    // We create a new instance of the class PDO
    $db = new PDO("mysql:host=".HOST.";dbname=".DB.";port=".PORT, LOGIN, PASSWORD);

    //We want any issues to throw an exception with details, instead of a silence or a simple warning
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(Exception $e) {
    // We intantiate an Exception object in $e so we can use methods within this object to display errors nicely
    echo $e->getMessage();
    exit;
}