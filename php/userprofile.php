<?php
    require_once('dbconnection.php');
    require_once('logout.php');

    logout();
    if (isset($_POST['change_password'])) {
        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hikingtrailshare | user profile</title>
</head>
<body>

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
            <label for="confirm_signup_password">Category *</label></br>
            <input type="text" name="profile_category" list="usercategory" id="usercategory" value="<?php echo $_SESSION["user"]['category'];?>">
            <datalist id="usercategory">
                <option value="user">
                <option value="admin">
            </datalist>
        </div>
        <?php
            if (isset($_POST['change_password'])) {
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
            <button type="submit" name="change_password">Change password</button>
            <button type="submit" name="no_change_password">Don't change password</button>
        </div>

        <button type="submit" name="save_profile">Save</button>
        <button type="submit" name="back_to_readhikes">Cancel</button>

    </form>

</body>
</html>