<?php
   require_once('dbconnection.php');
   require_once('logout.php');

  logout();

   if (isset($_GET["modifhike"]) && !empty($_GET["modifhike"]) && is_numeric($_GET["modifhike"])){
    try {
        $currentIDHIKE = $_GET["modifhike"];

        $qsel_hikes = $db->prepare("SELECT idhike,hikeName,dificulty,distance,TIME_FORMAT(duration,'%Hh %i') AS duration,elevationGain,
        DATE(creatDate),DATE(modifDate),userNickname FROM hikes WHERE idhike = $currentIDHIKE ");
        
        $qsel_hikes->execute();
        $hikes = $qsel_hikes->fetchall(PDO::FETCH_ASSOC);
        
    } catch (exception $e) {
      echo $e->getmessage();
      exit;
    }
   }
   

   if (isset($_POST["update_hike"])) { //Update the current hike -----------------------------------

    $hikeName = ucfirst($_POST["hikeName"]);
     $dificulty = ucfirst($_POST["dificulty"]);
     $distance = $_POST["distance"];
     $hour = intval($_POST["hour"]);
     $minute = intval($_POST["minute"]);
     $elevationGain = $_POST["elevationGain"];
     $duration = "$hour:$minute";

     //Nickname,difficulty and distance are mandatory (they can't be empty)
     if (!isset($hikeName, $dificulty,$distance) || empty($hikeName) || empty($dificulty) || empty($distance)){
         echo '<script>alert("Nickname,difficulty and distance are mandatory. They can not be empty")</script>';

     }elseif (isset($hour,$minute) && (!is_int($hour) || !is_int($minute))){
         //Hour and minute must have INT data type, and distance and elevationGain must have Numerics (or decimals) data type
         echo '<script>alert("Hour and minute have to be integers")</script>';

     }elseif (isset($distance,$elevationGain) && (!is_numeric($distance) || !is_numeric($elevationGain))){
         echo '<script>alert("Distance and elevationGain have to be Numerics (or decimals)")</script>';

     }elseif ($distance < 0 || $elevationGain < 0 || $hour < 0 || $minute < 0){
         //Disatance, elevation gain, hour and minute must not be below zero
         echo '<script>alert("Disatance, elevation gain, hour and minute must not be below zero")</script>';
     }elseif ($minute > 60) {
         echo'<script>alert("Minutes must not be giger than 60")</script>';

     }else {
         $reqInsert_hike = $db->prepare("UPDATE hikes SET hikeName = :hikeName,dificulty = :dificulty,
         distance = :distance,duration = :duration,elevationGain = :elevationGain
         WHERE idhike = $currentIDHIKE");
 
         $reqInsert_hike->bindParam(":hikeName",  $hikeName,PDO::PARAM_STR);
         $reqInsert_hike->bindParam(":dificulty", $dificulty,PDO::PARAM_STR);
         $reqInsert_hike->bindParam(":distance", $distance);
         $reqInsert_hike->bindParam(":duration", $duration);
         $reqInsert_hike->bindParam(":elevationGain", $elevationGain);
 
         $reqInsert_hike->execute();
         // redirect to index when done
         header("location: readhikes.php");
     }
}
         
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style.min.css">
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet"/>
    <title>Hikingtrailshare | Update hike trails</title>
</head>
<body>

    <?php include_once("header.php");?>

    <h1>Update trail</h1>

    <form method="post" action="">
        <div>
            <label for="hikeName">Trail name *</label></br>
            <input type="text" name="hikeName" value='<?php echo $hikes[0]["hikeName"]; ?>'>
        </div>

        <div>
            <label for="dificulty">Difficulty *</label></br>
            <input type="text" name="dificulty" list="dificultyTypes" id="dificulty" value="<?php echo $hikes[0]["dificulty"]?>">
            <datalist id="dificultyTypes">
                <option value="Easy">
                <option value="Medium">
                <option value="Difficult">
            </datalist>
        </div>

        <div>
            <label for="distance">Distance *</label></br>
            <input type="text" name="distance" value="<?php echo $hikes[0]["distance"] ?>">
            <span>Km</span>
        </div>
        <div>
            <label for="hour">Duration</label></br>
            <span>H</span>
            <input type="number" name="hour" min="0" value="<?php echo substr($hikes[0]["duration"],0,2) ?>">
            <span>min</span>
            <input type="number" name="minute" min="0" max="60" value="<?php echo substr($hikes[0]["duration"],-2) ?>">
        </div>
        <div>
            <label for="elevationGain">Elevation gain</label></br>
            <input type="text" name="elevationGain" value="<?php echo $hikes[0]["elevationGain"] ?>">
            <span>m</span>
        </div>
        <button type="submit" name="update_hike">Confirm</button>
        <button type="reset" name="Reset">Reset</button>
        <a href="readhikes.php">
            <button type="button" name="cancel">Cancel</button>
        </a>

    </form>

    <?php include_once("footer.php");?>

</body>
</html>