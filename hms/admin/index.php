<?php
session_start();
include("include/config.php");
error_reporting(0);
require "mail.php";
	require "functions.php";

/////////////////////
if(isset($_POST['submit']))
{
	$username=$_POST['username'];
	$password=$_POST['password'];
	////////
	$token = strtolower($_POST['token']);

	if ($_SESSION['captcha_token'] == $token) {


		//$ret=mysqli_query($con,"SELECT * FROM doctors WHERE docEmail='".$_POST['username']."' and password='".md5($_POST['password'])."'");
		$ret=mysqli_query($con,"SELECT * FROM admini WHERE username='".$_POST['username']."'");
		$num=mysqli_fetch_array($ret);
		if($num>0)
		{

		
			if(password_verify($password, $num['password'])){
			
                $_SESSION['login']=$_POST['username'];
                
                //send email
                $vars['code'] =  rand(10000,99999);

                //save to database
                $vars['expires'] = (time() + (60 * 10));
                $vars['email'] = $_SESSION['login'];

                $query = "insert into verify (code,expires,email) values (:code,:expires,:email)";
                database_run($query,$vars);

                $message = "your code is " . $vars['code'];
                $subject = "Medview+ login verification";
                $recipient = $vars['email'];
                send_mail($recipient,$subject,$message);
                
     
				$extra="verify.php";
            
				
				$_SESSION['id']=$num['id'];
				$uip=$_SERVER['REMOTE_ADDR'];
				$status=1;
				//$log=mysqli_query($con,"insert into doctorslog(uid,username,userip,status) values('".$_SESSION['id']."','".$_SESSION['dlogin']."','$uip','$status')");
				$host=$_SERVER['HTTP_HOST'];
				$uri=rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
				header("location:http://$host$uri/$extra");
				exit();



			
	
			}
		}
	
		else{



			$host  = $_SERVER['HTTP_HOST'];
			$_SESSION['login']=$_POST['username'];
			$uip=$_SERVER['REMOTE_ADDR'];
			$status=0;
			//mysqli_query($con,"insert into doctorslog(username,userip,status) values('".$_SESSION['login']."','$uip','$status')");
			$_SESSION['errmsg']="Invalid username or password";
			$extra="index.php";
			$uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
			header("location:http://$host$uri/$extra");
			exit();



		//$_SESSION['login']=$_POST['username'];	
		//$uip=$_SERVER['REMOTE_ADDR'];
		//$status=0;
		//mysqli_query($con,"insert into userlog(username,userip,status) values('".$_SESSION['login']."','$uip','$status')");
		//$_SESSION['errmsg']="Invalid captcha";
		//$extra="user-login.php";
		//$host  = $_SERVER['HTTP_HOST'];
		//$uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
		//header("location:http://$host$uri/$extra");
		//exit();
		//}

	}

	}else{

		$_SESSION['login']=$_POST['username'];	
		$uip=$_SERVER['REMOTE_ADDR'];
		$status=0;
		mysqli_query($con,"insert into doctorslog(username,userip,status) values('".$_SESSION['login']."','$uip','$status')");
		$_SESSION['errmsg']="Invalid Captcha";
		$extra="index.php";
		$host  = $_SERVER['HTTP_HOST'];
		$uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
		header("location:http://$host$uri/$extra");
		exit();



	}
}



?>



<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin-Login</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta content="" name="description" />
		<meta content="" name="author" />
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
		<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
		<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
		<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="assets/css/styles.css">
		<link rel="stylesheet" href="assets/css/plugins.css">
		<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
	</head>
	<body class="login">
		<div class="row">
			<div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
				<div class="logo margin-top-30">
				<h2>Admin Login</h2>
				</div>

				<div class="box-login">
					<form class="form-login" method="post">
						<fieldset>
							<legend>
								Sign in to your account
							</legend>
							<p>
								Please enter your name and password to log in.<br />
								<span style="color:red;"><?php echo htmlentities($_SESSION['errmsg']); ?><?php echo htmlentities($_SESSION['errmsg']="");?></span>
							</p>
							<div class="form-group">
								<span class="input-icon">
									<input type="text" class="form-control" name="username" placeholder="Username">
									<i class="fa fa-user"></i> </span>
							</div>
							<div class="form-group form-actions">
								<span class="input-icon">
									<input type="password" class="form-control password" name="password" placeholder="Password"><i class="fa fa-lock"></i>
									 </span>
							</div>

							<br>

							<div class="form-group row">
				<label class="col-sm-2 col-form-label">Enter Captcha</label>
				<div class="col-sm-10">
					<div class="form-row align-items-center">
						<div class="col mb-3">
							<input type="text" class="form-control" name="token" id="token" placeholder="Captcha" style="min-width: 150px;">
						</div>

						<div class="col mb-3">
							<img src="../../captcha/image.php?12325" alt="CAPTCHA" id="image-captcha">
							<a href="#" id="refresh-captcha" class="align-middle" title="refresh"><i class="material-icons align-middle">refresh</i></a>
						</div>

					</div>

				</div>
			</div>




							<br>



							<div class="form-actions">
								
								<button type="submit" class="btn btn-primary pull-right" name="submit">
									Login <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
							
						</fieldset>
					</form>

					<div class="copyright">
						&copy; <span class="current-year"></span><span class="text-bold text-uppercase"> medview+</span>. <span>All rights reserved</span>
					</div>
			
				</div>

			</div>
		</div>
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/modernizr/modernizr.js"></script>
		<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
		<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="vendor/switchery/switchery.min.js"></script>
		<script src="vendor/jquery-validation/jquery.validate.min.js"></script>
	
		<script src="assets/js/main.js"></script>

		<script src="assets/js/login.js"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				Login.init();
			});
		</script>
	<script>
		var refreshButton = document.getElementById("refresh-captcha");
		var captchaImage = document.getElementById("image-captcha");

		refreshButton.onclick = function(event) {
			event.preventDefault();
			captchaImage.src = '../../captcha/image.php?' + Date.now();
		};
	</script>
	</body>
	<!-- end: BODY -->
</html>