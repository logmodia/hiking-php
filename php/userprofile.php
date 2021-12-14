<?php
    require_once('dbconnection.php');
    require_once('logout.php');

    logout();

    if (isset($_POST['change_password'])) {
        $changePass = true;
    }elseif (isset($_POST['no_change_password'])){
        $changePass = false;
    }

    if (isset($_POST["save_profile"])) {  

        $profile_email = strip_tags($_POST['profile_email']);
        $userNickname = strip_tags($_POST['userNickname']);
        $current_password = strip_tags($_POST['current_password']);
        $new_password = strip_tags($_POST['new_password']);
        $confirm_new_password = strip_tags($_POST['confirm_new_password']);
        //$userCategory = strip_tags($_POST['user_category']);

            //Check if all fields are filled -------------------------------------------------------------
        if (!isset($userNickname) || empty($userNickname)){

            echo '<script>alert("All fields are mandatory. Please,complete the whole form.")</script>';
                
        }else{

            // check if email is valid ------------------------------
            if (!filter_var($signup_email, FILTER_VALIDATE_EMAIL)){

                echo '<script>alert("Email is not valid")</script>';

            }else {
                    
                try {
                    if ($changePass) {

                        if (!isset($current_password) || $new_password != $confirm_new_password) {
                            echo '<script>alert("Confirmation password is not correct")</script>';
                        }else {
                            // hash the password ----------------------------------------------------
                            $new_password = password_hash($new_password, PASSWORD_BCRYPT);

                            $reqInsert_user = $db->prepare("UPDATE users SET userNickname=:userNickname, password=:password)
                            values(:email,:userNickname, :password, :categry)");
                            //$reqInsert_user->bindParam(":email",$signup_email);
                            $reqInsert_user->bindParam(":userNickname",$userNickname);
                            $reqInsert_user->bindParam(":password",$new_password);
                        }
        
                    }else {
                        $reqInsert_user = $db->prepare("UPDATE users SET userNickname=:userNickname, password=:password)
                        values(:email,:userNickname, :password, :categry)");
        
                        //$reqInsert_user->bindParam(":email",$signup_email);
                        $reqInsert_user->bindParam(":userNickname",$userNickname);
                        $reqInsert_user->bindParam(":password",$new_password);
                    }
    
                    if (!$reqInsert_user->execute()){
                        echo '<script>Alert("Sorry, your request is not submitted")</script>';
    
                    }else {

                        # Give access
                        //store data of user in $_SESSION and then redirect to hikes list page when done ---------------------------------------------
                        $_SESSION["user"] = [
                            "userNickname" => $userNickname,
                            "email" => $profile_email,
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
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/style.min.css">
    <title>Hikingtrailshare | user profile</title>
</head>
<body>

    <?php include_once("header.php");?>

    <h1>User : <?php echo $_SESSION["user"]['userNickname'];?></h1>

    <form method="post" action="">
        <div>
            <label for="profile_email">Email *</label></br>
            <input type="email" name="profile_email" placeholder="example@gmail.com" value="<?php echo $_SESSION["user"]['email'];?>">
        </div>
        
        <div>
            <label for="nickname">Nickname *</label></br>
            <input type="text" name="userNickname" placeholder="Your nickname here" value="<?php echo $_SESSION["user"]['userNickname'];?>">
        </div>

        <div>
            <label for="user_category">Category *</label></br>
            <input type="text" name="user_category" list="usercategory" id="usercategory" value="<?php echo $_SESSION["user"]['category'];?>">
            <datalist id="usercategory">
                <option value="user">
                <option value="admin">
            </datalist>
        </div>
        <?php
            if ($changePass) {
            echo '
                <div>
                <label for="current_password">Current password *</label></br>
                <input type="password" name="current_password" placeholder="Current password">
                </div>
        
                <div>
                    <label for="new_password">New password *</label></br>
                    <input type="password" name="new_password" placeholder="New password">
                </div>
                <div>
                    <label for="confirm_new_password">Confirm new password *</label></br>
                    <input type="password" name="confirm_new_password" placeholder="Confirm new password">
                </div>
            ';
            }
        ?>
        <div>
            <?php
                if ($changePass) {
                    echo '
                    <button type="submit" name="no_change_password">Don\'t change password</button>
                    ';
                }else{
                    echo '
                    <button type="submit" name="change_password">Change password</button>
                    ';
                }
            ?>
            
        </div>

        <button type="submit" name="save_profile">Save</button>
        <button type="submit" name="back_to_readhikes">Cancel</button>

    </form>
    <?php include_once("footer.php");?>
</body>
</html>