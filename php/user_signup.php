<?php
    session_start();
    
    require_once('dbconnection.php');

   if (isset($_POST["create_user"])) {  

        $signup_email = strip_tags($_POST['signup_email']);
        $userNickname = strip_tags($_POST['userNickname']);
        $signup_password = strip_tags($_POST['signup_password']);
        $confirm_signup_password = strip_tags($_POST['confirm_signup_password']);
        $userCategory = 'user';

            //Check if all fields are filled -------------------------------------------------------------
        if (!isset($signup_email,$userNickname,$signup_password,$confirm_signup_password) ||
            empty($signup_email) || empty($userNickname) || empty($signup_password) || empty($signup_password)){

            echo '<script>alert("All fields are mandatory. Please,complete the whole form.")</script>';
                
        }else{

            // check if email is valid ------------------------------
            if (!filter_var($signup_email, FILTER_VALIDATE_EMAIL)){

                echo '<script>alert("Email is not valid")</script>';

            }else {

                if($signup_password != $confirm_signup_password) {
                    echo '<script>alert("Confirmation password is not correct")</script>';
        
                }else {
                    // hash the password ----------------------------------------------------
                    $signup_password = password_hash($signup_password, PASSWORD_BCRYPT);

                    try {

                        $reqInsert_user = $db->prepare("INSERT INTO users (email, userNickname, password,category)
                        values(:email,:userNickname, :password, :categry)");
        
                        $reqInsert_user->bindParam(":email",$signup_email);
                        $reqInsert_user->bindParam(":userNickname",$userNickname);
                        $reqInsert_user->bindParam(":password",$signup_password);
                        $reqInsert_user->bindParam(":categry",$userCategory);
        
                        if (!$reqInsert_user->execute()){
                            echo '<script>Alert("Sorry, your request is not submitted")</script>';
        
                        }else {

                            # Give access
                            //store data of user in $_SESSION and then redirect to hikes list page when done ---------------------------------------------
                            $_SESSION["user"] = [
                                "userNickname" => $userNickname,
                                "email" => $signup_email,
                                "category" => $userCategory,
                                //"sessionExpiration" => date('H:i:s')
                            ];
                            header("location: readhikes.php");
                        }
        
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }

                }
                
            }
        }

    }elseif (isset($_POST["back_to_signin"])) { // Go back to sign in page when cancel button is clicked -----------------
        header("location: /index.php");

    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
</head>
<body>

<h1>Sign up</h1>

    <form method="post" action="">
        <div>
            <label for="signup_email">Email *</label></br>
            <input type="email" name="signup_email" placeholder="example@gmail.com">
        </div>
        
        <div>
            <label for="nickname">Nickname *</label></br>
            <input type="text" name="userNickname" placeholder="Your nickname here">
        </div>

        <div>
            <label for="signup_password">Password *</label></br>
            <input type="password" name="signup_password" placeholder="Your password here">
        </div>

        <div>
            <label for="confirm_signup_password">Password *</label></br>
            <input type="password" name="confirm_signup_password" placeholder="Confirm password">
        </div>

        <button type="submit" name="create_user">Confirm</button>
        <button type="submit" name="back_to_signin">Cancel</button>

    </form>

</body>
</html>