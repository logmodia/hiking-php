<?php
 require_once('dbconnection.php');
 require_once('logout.php');

 logout();
try {
    $search=$_GET['search']??'';

    if ($search) {
        $qsel_hikes = $db->prepare("SELECT idhike,hikeName,dificulty,distance,TIME_FORMAT(duration,'%Hh %i') AS duration,elevationGain,
                                    DATE(creatDate) AS creatDate,DATE(modifDate) AS modifDate,userNickname FROM hikes
                                    WHERE hikeName LIKE :hikeName ORDER BY hikeName");

        $qsel_hikes->bindValue(':hikeName', "%$search%");
    } else {
        $qsel_hikes = $db->prepare("SELECT idhike,hikeName,dificulty,distance,TIME_FORMAT(duration,'%Hh %i') AS duration,elevationGain,
        DATE(creatDate) AS creatDate,DATE(modifDate) AS modifDate,userNickname FROM hikes ORDER BY hikeName");
    }

    $qsel_hikes->execute();
    $hikes = $qsel_hikes->fetchall(PDO::FETCH_ASSOC);
}catch (exception $e) {
    echo $e->getmessage();
    exit;
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
      <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet" />
      <link rel="stylesheet" href="/style.min.css">
    <title>Hiking List</title>
  </head>
  <body>

  <?php include_once("header.php");?>

    <h1>All Hikes List</h1>

    <p><a href="addhike.php"class="btn btn-success">Add New Hike</a></p>

<!-- search bar -->
  <form action="" method="get">
    <div class="input-group mb-3">
    <input type="text" class="form-control" placeholder="Search hikes..." name="search" value="<?php echo $search?>">
    <div class="input-group-append">
      <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
  </form>

</div>

    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Hike name</th>
          <th scope="col">Dificulty</th>
          <th scope="col">Distance</th>
          <th scope="col">Duration</th>
          <th scope="col">Elevation gain</th>
          <th scope="col">Create Date</th>
          <th scope="col">Modified Date</th>
          <th scope="col">Created by</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($hikes as $i => $hike):?>
    <tr>
          <th scope="row"><?php echo $i + 1 ?></th>
          <td><?php echo$hike['hikeName'] ?></td>
          <td><?php echo$hike['dificulty'] ?></td>
          <td><?php echo$hike['distance'] ?></td>
          <td><?php echo$hike['duration'] ?></td>
          <td><?php echo$hike['elevationGain'] ?></td>
          <td><?php echo "Created at ".$hike['creatDate'] ?></td>
          <td><?php echo "Updated at ".$hike['modifDate'] ?></td>
          <td><?php echo $hike['userNickname']?></td>
          <td>
           <a href="updatehike.php?idhike=<?php echo$hike['idhike']?>" class="btn btn-sm btn-outline-primary">Edit</a>

          <form action="delete.php" method="post" style="display: inline-block">
            <input type="hidden" name="idhike" value="<?php echo$hike['idhike']?>">
            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
            
          </form>

          </td>
          
        </tr>
        <?php endforeach; ?>

      </tbody>
    </table>

    <?php include_once("footer.php");?>

  </body>
</html>