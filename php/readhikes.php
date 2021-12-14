<?php

  require_once('dbconnection.php');
  require_once('logout.php');

  logout();

  try {
      $qsel_hikes = $db->prepare("SELECT idhike,hikeName,dificulty,distance,TIME_FORMAT(duration,'%Hh %i') AS duration,elevationGain,
      DATE(creatDate),DATE(modifDate),userNickname FROM hikes");
      
      $qsel_hikes->execute();
      $hikes = $qsel_hikes->fetchall(PDO::FETCH_ASSOC);
      
  } catch (exception $e) {
    echo $e->getmessage();
    exit;
  }

  if (isset($_GET["idhike"]) && !empty($_GET["idhike"])){ //Delete hike--------------------------------
      try {
        $id=$_GET["idhike"];
        $reqdel_hike = $db->prepare("DELETE FROM hikes WHERE idhike=$id");
        
        $reqdel_hike->execute();
        
        header("location: readhikes.php");

    } catch (exception $e) {
      echo $e->getmessage();
      exit;
    }
  }

  if (isset($_POST["search"])){ //Search a trail -----------------------------------------------------------------------
      try {
        $search_Argument=$_POST["search_input"];
        $reqsel_hikes = $db->prepare("SELECT idhike,hikeName,dificulty,distance,TIME_FORMAT(duration,'%Hh %i') AS duration,elevationGain,
        DATE(creatDate),DATE(modifDate),userNickname FROM hikes WHERE hikeName LIKE  :param");

        $param = '%'.$search_Argument.'%';
        
        $reqsel_hikes->bindParam(":param",$param,PDO::PARAM_STR);
        $reqsel_hikes->execute();

        $hikes = $reqsel_hikes->fetchAll(PDO::FETCH_ASSOC);
        
        //header("location: index.php");
    } catch (exception $e) {
      echo $e->getmessage();
      exit;
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/style.min.css">
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet"/>
    <title>Hiking trails List</title>
  </head>
  <body>

    <?php include_once("header.php");?>

    <main class="hike__container">
      <form method="post" action = "" class="hike__search">
        <div class="hike__search-bar">
          <input class="hike__search-input" type="text"name="search_input" placeholder="Search trails" id="seacrh-input"/>
          <button class="hike__search-btn" id="searchBtn" type="submit" name="search">Search</button>
        </div>
      </form>
      <form action = "addhike.php" method="post" >
          <button class="hike__controls-btn btn add" type="submit">Add</button>
      </form>
      
      <?php foreach ($hikes as $hike):?>

        <h3><?php echo $hike['hikeName'] ?></h3>

        <div class="hike__tmpl-detailes">
          <div class="hike__tmpl-subdetailes">
            <p><?php echo $hike['dificulty'] ?></p>
            <i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAQRJREFUSEvdlN0RAUEQhL+LgAwQATIgAiUDQhABMpABMpABIZABGRAB1Wr36mrvZ2v37jyY19mZnp7u2YSWI2m5Py5AFzgDo0jgO7AELrbeBTgBs8jmtkwggyKADbAGXsAEuEYAvU1NOniWgU1uAYHFRCWAZfAExoCohkYlgJo1ocED6JeJLBfJAcPQ0c17NV9UucjXN7cCX0HoodUGkD332R36JnTysvYUkFG+4TKQc3qBTd3nMsq8DCB4BZnu+l5kkA6Q3pLLoA6AsOwtpdtpEkAM9FHK6itgV6SBxBHFOnE0t1Aoslx0qCH0zXyUpS7yTR6s0c8P7f8Z+Bjm8qEaBAN8AK4gLRn2hHwTAAAAAElFTkSuQmCC"/></i>
          </div>
          <div class="hike__tmpl-subdetailes">
            <p><?php echo $hike['distance'].'Km' ?></p>
            <i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAIJJREFUSEvtlEsOgDAIRKc305vryTQam1jSkoGAcdHuSApv+BYkv5IcHz3A8UBD4BrAk9wGYH07RgOu2E1MpkSyZCO7Cv8PQCpiM6EzSAdUJazy2QOwpTI32brRqYAdwMKeivRjNwF3K5hrap2i5r8E1Fnvrr2H9DnAI1L1CRlFjXACiUguGXB+n58AAAAASUVORK5CYII="/></i>
          </div>
          <div class="hike__tmpl-subdetailes">
            <p><?php echo $hike["duration"] ?></p>
            <i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAATVJREFUSEu1leFRAjEQhb+rwBKQCoQKHCtQKpAOkErACoAKlA4oQTuADugA5jmEWXIh2SCXmfuXvO9lN/uuoePVdKyPBzAGnoHB6ZOnn9P3DaxzJnOAN2AGPBZuuQWmgGCtdQ0wByaV5dMZgS5WCnCLeBD9BD4sIQaoLF8Z52H/IbNnZMsVA1TP3j8B0ugHDQvQa1kU6u65gSTOt7CAJfB+J8AKkOGLOdDbfroTQFrDGJBrXFzSPfDgMWNL5AHIldxpqjcFyJ+2BXhKJOcvDshviJXaJsuUB5JscmnIbMktRBn0GvUj+Uy1pzRoMUR9kDG7djYga6PCk3/ZqJBAp2EXHN4CaSVp/Ezj66u2AuXCT2dUc0V01Q/HwgTSp+EKUaJ3rrmRaFI4laaeBlbv8fz0q0XtgSM5RD8Z+f3jkQAAAABJRU5ErkJggg=="/></i>
          </div>
          <div class="hike__tmpl-subdetailes">
            <p><?php echo $hike['elevationGain'].'m' ?></p>
            </div>
          <div class="hike__tmpl-subdetailes">
            <p><?php echo "Created at ".$hike['DATE(creatDate)'] ?></p>
           </div>
          <div class="hike__tmpl-subdetailes">
            <p><?php echo "Updated at ".$hike['DATE(modifDate)'] ?></p>
            </div>
          <div class="hike__tmpl-subdetailes">
            <p><?php echo "By ".$hike['userNickname'] ?></p>
            <i><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAUtJREFUSEvVlEEyBDEYhb85ARu2OAFOgBOYG+AEWFliaccJOAInGDfADdiy4QTUV5V/qvWkOynTs5CqVDqd9Hsv/3udEQtuowXjMy/BEXCVRJ4Bt23B8xK8AysJ9ANY/XcEg5XoGtgBPoFH4Bl4AV5LIanx4DBnXgKWbK+PpIZAlWvAZVJ+AWw2QH3vu2wrEYT6N2A99SdguYU2Tmu7adyO9RLBHXAAaKbP98B+A/yhNY+ljfCnROAHqrLWjpMWuMoNwFYCdJ99an4NQWCGF86/GqC9Qaol0MTzBpJxVXkxrjUEmhvGqnwpI1my09xRagjCWA2NtFhzu744SmpyPNGvViIIY/tqrvpj4AY4KRF8dzjW9zN5AktoCIxn7wlyBFllLZxI2EyZSiUq3WWx3lmmoQiiTBGEqbChCAQ0zjPX95AEf7pNaz3o3PcDPHBEGTDOeqYAAAAASUVORK5CYII="/></i>
          </div>
          
        </div>

        <div class="hike__controls">
            <a href='/php/updatehike.php?modifhike=<?php echo $hike["idhike"]; ?>'>
              <button class="hike__controls-btn btn modify" type="button">Modify</button>
            </a>
            <a href='readhikes.php?idhike=<?php echo $hike["idhike"]; ?>'>
              <button class="hike__controls-btn btn delete" type="submit" name="delete">Delete</button>
            </a>
        </div>

      <?php endforeach ?>

    </main>

    <?php include_once("footer.php");?>

    <script src="./js/index.js"></script>
  </body>
</html>
