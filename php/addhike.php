<?php
    require_once('dbconnection.php');

    require_once('logout.php');
    // logout();

$errors=[];
//setting the input fileds into empty by default and at refresh
$hikeName='';
$distance='';
$description='';
//we are puting a condition if the request method is post then execute(insert)the below stated data into the database
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $hikeName=ucfirst($_POST['hikeName']);
    $dificulty = ucfirst($_POST["dificulty"]);
    $distance=$_POST['distance'];
    $hour = intval($_POST["hour"]);
    $minute = intval($_POST["minute"]);
    $elevationGain = $_POST["elevationGain"];
    //TODO i don't know where & how we are getthing the nickname
    $userNickname = $_SESSION["user"]['userNickname'];
    $duration = "$hour:$minute";
// !validation ! marking some conditions to comunicate with the user when the forms are not filed out 


if(!$hikeName){
  $errors[]='Hike Name is required';

}
if(!$dificulty){
  $errors[]='Hike dificulty is required';

}

if(!$distance){
    $errors[]='Hike distance is required';
  
  }

  if (isset($hour,$minute) && (!is_int($hour) || !is_int($minute))) {
      //Hour and minute must have INT data type, and distance and elevationGain must have Numerics (or decimals) data type
      $errors[]='Hour and minute have to be integers';
  }

  if (isset($distance,$elevationGain) && (!is_numeric($distance) || !is_numeric($elevationGain))){
    $errors[]='Distance and elevationGain have to be Numerics (or decimals)';

}
if ($distance < 0 || $elevationGain < 0 || $hour < 0 || $minute < 0) {
    //Disatance, elevation gain, hour and minute must not be below zero
    $errors[]='Disatance, elevation gain, hour and minute must not be below zero';
}
if ($minute > 60) {
    $errors[]='Minutes must not be giger than 60';
}
  //here is better solution a fucntion generating random string while saving the images
  function randomString($n){
    $characters='0123456789abcdefghijklmnsopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str='';
    for($i=0;$i<$n;$i++){
      $index= rand(0, strlen($characters)-1);
      $str.=$characters[$index];
    
    }
    return $str;
    };

//!if there is no existing "images" folder in our storage then we autmatically generate one
//TODO we have to create a colomun for images
if(!is_dir('images')){
  mkdir('images');

}
//if there are no errors then execute the below segment of code
if(empty($errors)){  
  
  //uploading images getting images using the $_FILES super global variable
  $image=$_FILES['image'] ?? null; //if there is no image it is going to be set to null
  //setting the default of our imagePath in to empty string
  $imagePath='';
  if($image && $image['tmp_name']){
    //by default apatche saves the uploded image in a temporary place which is called tmp and we have to move this tmp_name into a permanent place
    //the move_uploaded_file() takes to arguments the tmp_name and the newname we want to assign while saving ex. below
    
    $imagePath='images/'.randomString(8).'/'.$image['name'];

    //then we create a subfolder inside the image that will contain the acctual images
    mkdir((dirname($imagePath)));

    move_uploaded_file($image['tmp_name'], $imagePath);
  
    
  } else 
  try {
            //TODO check the old version with mine    
    $reqInsert_hike = $db->prepare("INSERT INTO hikes(hikeName,dificulty,distance,duration,elevationGain,userNickname)
    values(:hikeName, :dificulty, :distance, :duration, :elevationGain, :userNickname)");

    $reqInsert_hike->bindParam(":hikeName",  $hikeName,PDO::PARAM_STR);
    $reqInsert_hike->bindParam(":dificulty", $dificulty,PDO::PARAM_STR);
    $reqInsert_hike->bindParam(":distance", $distance);
    $reqInsert_hike->bindParam(":duration", $duration);
    $reqInsert_hike->bindParam(":elevationGain", $elevationGain);
    $reqInsert_hike->bindParam(":userNickname", $userNickname);

    $reqInsert_hike->execute();
    // redirect to index when done
    header("location: readhikes.php");

} catch (PDOException $e ) {
    echo $e->getMessage();
}


  //Doing "prepare" and then "execute" separately protects us form being exposed to security threates since there could be occured code injections from hackers
    //!"named parameters" are futures of the PDO and are set with colon followed by the property name :example
    // $statement =$pdo->prepare("INSERT INTO products (hikeName,image,description,distance,create_date)
    //           VALUES (:hikeName,:image,:description,:distance,:date)");
    // //then we bind the "named parameter" with the declared variables
    // $statement->bindValue(':hikeName', $hikeName);
    // $statement->bindValue(':image', $imagePath);
    // $statement->bindValue(':description', $description);
    // $statement->bindValue(':distance', $distance);
    // $statement->bindValue(':date', $date);
    // //then we execute the data to be submitted to the database
    // $statement->execute();
    // //finally redirecting user to the landing page inde.php
    // header('Location:index.php');
  };
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
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="app.css" />
    <title>Add Trail</title>
  </head>
  <body>
    <h1>Add new Trail</h1>

<!-- //if errors are not empty(if there are errors) then it will execute the following code -->
  <?php   if(!empty($errors)): ?>
    <!-- //!displaying error messages for each error encountered  -->
<div class="alert alert-danger">
  <?php foreach($errors as $error):?>
    <div><?php echo $error ?></div>
    <?php endforeach;?>
</div>
<?php endif;?>
<!-- when uploading files in php we have to specify the "enctype"attribute which helps our form to accept file formats like images -->
<form action="readhikes.php" method="post" enctype="multipart/form-data">
<div class="mb-3">
    <label>Trail Image</label></br>
    <br>
    <input type="file" name="image">
  </div>
  <div class="mb-3">
    <label>Trail name</label>
    <input type="text" name="hikeName"class="form-control" value="<?php echo $hikeName;?>">
  </div>

  <div class="mb-3">
      //! $dificulty should be included in the varaiable declaration section
        <label>Difficulty *</label></br>
        <input type="text" name="dificulty" class="form-control" list="dificultyTypes" id="dificulty">
        <datalist id="dificultyTypes">
            <option value="Easy">
            <option value="Medium">
            <option value="Difficult">
        </datalist>
    </div>


 <div class="mb-3">
    <label>Distance *</label>
    <input type="number" step="0.1" class="form-control" name="distance" value="<?php echo $distance;?>">
    <span>Km</span>
  </div>

  <div class="mb-3">
        <label for="hour">Duration</label></br>
        <span>H</span>
        <input type="number" class="form-control" name="hour" min="0" placeholder="Hour(s)" value="<?php echo $hour;?>" >
        <span>min</span>
        <input type="number" class="form-control" name="minute" min="0" max="60" placeholder="minute(s)" value="<?php echo $minute;?>" >
    </div>

  <div class="mb-3">
        <label>Elevation gain</label></br>
        <input type="text" class="form-control" name="elevationGain" value="<?php echo $elevationGain;?>">
        <span>m</span>
    </div>

  <button type="submit" name="addhike" class="btn btn-primary">Confirm</button>
  <!-- check button name for the reset the first latter is in uppercase  -->
  <button type="submit" name="Reset"class="btn btn-primary">Reset</button>
  <a href="readhikes.php">
  <button type="button" name="cancel" name="Reset"class="btn btn-primary">Cancel</button>
  </a>
</form>
</form>
  </body>
</html>
