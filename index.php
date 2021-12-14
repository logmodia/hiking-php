<?php echo 'Salut Francis';
    if (!isset($_ENV['HOST'])){
        include_once($_SERVER['DOCUMENT_ROOT'].'/php/var_environ.php');
    }
    
    try {
    
        // We create a new instance of the class PDO
        $db = new PDO("mysql:host=".$_ENV['HOST'].";dbname=".$_ENV['DB'].";port=".$_ENV['PORT'], $_ENV['LOGIN'], $_ENV['PASSWORD']);
    
        //We want any issues to throw an exception with details, instead of a silence or a simple warning
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $q=$db->prepare("SELECT * FROM hikes");
        $q->execute();
        $x=$q->fetchAll(PDO::FETCH_ASSOC);
        print_r($x);
    } catch(Exception $e) {
        // We intantiate an Exception object in $e so we can use methods within this object to display errors nicely
        echo $e->getMessage();
        exit;
    }
?>