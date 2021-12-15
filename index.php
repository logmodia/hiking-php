<?php

    session_start();
    
    require_once($_SERVER['DOCUMENT_ROOT'].'/php/dbconnection.php');
    
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
                                "password" => $user["password"],
                                //"sessionExpiration" => date('H:i:s')
                            ];
                            header("location: ./php/readhikes.php");
                        }
                    }
    
                }
            } catch (PDOException $e) {
                echo $e->getmessage();
                exit;
            }
        } 
    
    } elseif (isset($_POST['signup'])) { // Redirect to sign up page when sign up button is clicked from the sign in page
        header("location: ./php/user_signup.php");

    }
         
?>

<!--<!DOCTYPE html>
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
</html>-->


<!DOCTYPE html>
<html>
    <head>
        <!------ Include the above in your HEAD tag ---------->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<title>Login Page</title>
   <!--Made with love by Mutiullah Samim -->
   
	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="index.css">
    <title>Hiking | Sign in</title>
</head>
<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3>Sign In</h3>
				<div class="d-flex justify-content-end social_icon">
					<span><i class="fab fa-facebook-square"></i></span>
					<span><i class="fab fa-google-plus-square"></i></span>
					<span><i class="fab fa-twitter-square"></i></span>
				</div>
			</div>
			<div class="card-body">
				<form method="post" action="">
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" name="email" class="form-control" placeholder="your email here">
						
					</div>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" name="password" class="form-control" placeholder="password">
					</div>
					<!--<div class="row align-items-center remember">
						<input type="checkbox">Remember Me
					</div>-->
					<div class="form-group">
						<input type="submit" name="signin" value="Login" class="btn float-right login_btn">
					</div>
				</form>
			</div>
			<div class="card-footer">
				<div class="d-flex justify-content-center links">
					Don't have an account?<a href="php/user_signup.php">Sign Up</a>
				</div>
				<div class="d-flex justify-content-center">
					<a href="#">Forgot your password?</a>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>