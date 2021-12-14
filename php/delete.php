<?php
 require_once('dbconnection.php');
 require_once('logout.php');

 logout();

$idhike=$_POST['idhike'] ?? null;
if(!$idhike){
    header('Location:index.php');
    exit;
};


$reqdel_hike = $db->prepare('DELETE FROM hikes WHERE idhike= :idhike');
$reqdel_hike->bindValue(':idhike',$idhike);
$reqdel_hike->execute();

header('Location:readhikes.php');

?>