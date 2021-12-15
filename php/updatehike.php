<?php
  require_once('dbconnection.php');
  require_once('logout.php');

 logout();

    $idhike=$_GET['idhike'] ?? null;

    if(!$idhike){
        header('Location:readhikes.php');
        exit;
    };

    try {
        //fetching and preparing the target product based on ID
        $qsel_hikes=$db->prepare("SELECT idhike,hikeName,dificulty,distance,TIME_FORMAT(duration,'%Hh %i') AS duration,elevationGain,
        DATE(creatDate) AS creatDate,DATE(modifDate) AS modifDate,userNickname FROM hikes WHERE idhike=:idhike");
        $qsel_hikes->bindValue(':idhike',$idhike);
        $qsel_hikes->execute();
        $hike=$qsel_hikes->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
    
    /* 
    <input type="number" name="hour" min="0" value="<?php echo substr($hikes[0]["duration"],0,2) ?>">
    <span>min</span>
    <input type="number" name="minute" min="0" max="60" value="<?php echo substr($hikes[0]["duration"],-2) ?>"> */

    $errors=[];

    //we are puting a condition if the request method is post then execute(insert)the below stated data into the database
    if ($_SERVER['REQUEST_METHOD']==='POST') {

    $hikeName = ucfirst($_POST["hikeName"]);
    $dificulty = ucfirst($_POST["dificulty"]);
    $distance = $_POST["distance"];
    $hour = intval($_POST["hour"]);
    $minute = intval($_POST["minute"]);
    $elevationGain = $_POST["elevationGain"];
    $userNickname = ucfirst($_POST["userNickname"]);
    $duration = "$hour:$minute";

        if(!$hikeName){
            $errors[]='Hike Name is required';
        
        }elseif(!$dificulty){
            $errors[]='Hike dificulty is required';
        
        }elseif(!$distance){
            $errors[]='Hike distance is required';
            
        }elseif (isset($hour,$minute) && (!is_int($hour) || !is_int($minute))) {
                //Hour and minute must have INT data type, and distance and elevationGain must have Numerics (or decimals) data type
                $errors[]='Hour and minute have to be integers';

        }elseif (isset($distance,$elevationGain) && (!is_numeric($distance) || !is_numeric($elevationGain))){
            $errors[]='Distance and elevationGain have to be Numerics (or decimals)';
        
        }elseif ($distance < 0 || $elevationGain < 0 || $hour < 0 || $minute < 0) {
            //Disatance, elevation gain, hour and minute must not be below zero
            $errors[]='Disatance, elevation gain, hour and minute must not be below zero';

        }elseif ($minute > 60) {
            $errors[]='Minutes must not be giger than 60';

        }else try {
            $reqInsert_hike = $db->prepare("UPDATE hikes SET hikeName = ':hikeName',dificulty = ':dificulty',
            distance = ':distance',duration = ':duration',elevationGain = ':elevationGain'
            WHERE idhike = $idhike");

            $reqInsert_hike->bindParam(":hikeName",  $hikeName,PDO::PARAM_STR);
            $reqInsert_hike->bindParam(":dificulty", $dificulty,PDO::PARAM_STR);
            $reqInsert_hike->bindParam(":distance", $distance);
            $reqInsert_hike->bindParam(":duration", $duration);
            $reqInsert_hike->bindParam(":elevationGain", $elevationGain);

            $reqInsert_hike->execute();
            // redirect to index when done
            header("location: readhikes.php");

        }catch (exception $e) {
            echo $e->getmessage();
            exit;
        }
                
    };

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
      <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet" />
      <link rel="stylesheet" href="/style.min.css">
    <title>Update Hike</title>
  </head>
  <body>
        <?php include_once("header.php");?>

        <p><a href="readhikes.php" class="btn btn-secondary">Get Back to Hikes list</a></p>
        <h1><?php echo $hike['hikeName']?></h1>

        <!-- //if errors are not empty(if there are errors) then it will execute the following code -->
        <?php if(!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach($errors as $error):?>
                    <div><?php echo $error ?></div>
                <?php endforeach;?>
            </div>
        <?php endif;?>

    <!-- when uploading files in php we have to specify the "enctype"attribute which helps our form to accept file formats like images -->
    <form action="updatehike.php" method="post" enctype="multipart/form-data">
    
        <label>Hike name</label>
        <input type="text" name="hikeName"class="form-control" value="<?php echo $hike['hikeName'];?>">
        <label>Difficulty *</label></br>
        <input type="text" name="dificulty" class="form-control" list="dificultyTypes" id="dificulty" value="<?php echo $hike['dificulty'];?>">
        <datalist id="dificultyTypes">
            <option value="Easy">
            <option value="Medium">
            <option value="Difficult">
        </datalist>
        <label>Distance (km) *</label>
        <input type="number" step="0.1" class="form-control" name="distance" value="<?php echo $hike['distance'];?>">
        <label for="hour">Duration</label>
        <span>H</span>
        <input type="number" class="form-control" name="hour" min="0" max="60" placeholder="minute(s)" value="<?php echo substr($hike['duration'],0,2);?>" >
        <span>min</span>
        <input type="number" class="form-control" name="minute" min="0" max="60" placeholder="minute(s)" value="<?php echo substr($hike['duration'],-2);?>" >
    
        <label>Elevation gain (m)</label></br>
        <input type="number" step="0.1" class="form-control" name="elevationGain" value="<?php echo $hike['elevationGain'];?>">
        <div class="m-3"></div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <?php include_once("footer.php");?>

  </body>
</html>

