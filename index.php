<?php

    session_start();
    
    require_once('./php/dbconnection.php');
    
   if (isset($_POST['signin'])){

       $signin_email = strip_tags($_POST['email']);
       $signin_password = strip_tags($_POST['password']);
       //$userCategory = 'user';

       if (!isset($signin_email, $signin_password) ||
            empty($signin_email) || empty($signin_password)){ //Check if all fields are filled ----------

            echo '<script>alert("Email and password fields are mandatory. They can not be empty")</script>';
    
        }else {
    
            try {

                $reqSel_user = $db->prepare("SELECT email,password,userNickname,category From users WHERE email=:email");
                $reqSel_user->bindParam(":email", $signin_email,PDO::PARAM_STR);
    
                if (!$reqSel_user->execute()){//check if the resquest failed-----------------------------------
                    echo '<script>alert("Sorry, Your form has not been submitted")</script>';
    
                }else{
    
                    $user=$reqSel_user->fetch(PDO::FETCH_ASSOC);
                    //var_dump($user);
    
                    if (!$user){ //Check if user mail doesn't exist--------------------------------------------
                        echo '<script>alert("Sorry, user mail doesn not exist")</script>';

                    }else {
                        //check  if the input password matches with the password in db -------------------------
                        if (!password_verify($signin_password,$user['password'])){ 

                            echo '<script>alert("Wrong password")</script>';
        
                        }else {
                            # Give access
                            //store data of user in $_SESSION and then redirect to hikes list page when done ---------------------------------------------
                            $_SESSION["user"] = [
                                "userNickname" => $user["userNickname"],
                                "email" => $user["email"],
                                "category" => $user["category"],
                                //"sessionExpiration" => date('H:i:s')
                            ];
                            header("location: php/readhikes.php");
                        }
                    }
    
                }
            } catch (PDOException $e) {
                echo $e->getmessage();
                exit;
            }
        } 
    
    } elseif (isset($_POST['signup'])) { // Redirect to sign up page when sign up button is clicked from the sign in page
        header("location: php/user_signup.php");

    }
         
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiking | Sign in</title>
</head>
<body>
    
    <h1>Sign in</h1>

    <form method="post" action="">
        <div>
            <label for="email">Email *</label></br>
            <input type="email" name="email" placeholder="example@gmail.com">
        </div>

        <div>
            <label for="password">Password *</label></br>
            <input type="password" name="password" placeholder="Your password here">
        </div>

        <button type="submit" name="signin">Sign in</button>
        <button type="submit" name="signup">Sign up</button>

    </form>

</body>
</html>