<?php
session_start();
error_reporting(0);
include_once('include/config.php');
if(isset($_POST['submit']))
{
	$fname=$_POST['fullname'];
	//$lname=$_POST['lname'];
	$address=$_POST['address'];
	$city=$_POST['city'];
	$gender=$_POST['gender'];
	$email=$_POST['email'];
	$token = strtolower($_POST['token']);

	//$password=md5($_POST['password']);
	$password=$_POST['password'];
	$password=password_hash($password, PASSWORD_DEFAULT);



	$has_at_least_6_char = '/(?=.*[a-zA-Z0-9]{6})/';
	$has_a_capital_letter = '/(?=.*[A-Z])/'; 
	$has_a_digit = '/(?=.*\d)/'; 


	function has_at_least_6_char($str) {
		return preg_match('/(?=.*[a-zA-Z0-9]{6})/', $str);
	}

	function has_a_capital_letter($str) {
		return preg_match('/(?=.*[A-Z])/', $str);
	}

	function has_a_digit($str) {
		return preg_match('/(?=.*\d)/', $str);
	}


		if (empty($token)) {
			
			$_SESSION['errmsg']="Enter Captcha please";
			$extra="registration.php";
			$host  = $_SERVER['HTTP_HOST'];
			$uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
			header("location:http://$host$uri/$extra");
			exit();		

		}

		else{


				if ($_SESSION['captcha_token'] == $token) {

					$query=mysqli_query($con,"insert into users(fullName,address,city,gender,email,password) values('$fname','$address','$city','$gender','$email','$password')");
					//$query=mysqli_query($con,"insert into admin(username,password,city,gender,email,password) values('$fname','$address','$city','$gender','$email','$password')");
					if($query)
					{


						
						$_SESSION['regmsg']="Successfully Registered.";
						$extra="registration.php";
						$host  = $_SERVER['HTTP_HOST'];
						$uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
						header("location:http://$host$uri/$extra");

						
						exit();
	
					//echo "<script>alert('Successfully Registered. You can login now');</script>";
					//header('location:user-login.php');
					} 

					else {
					
					echo "Failed to connect to MySQL: " . mysqli_error($con);
					mysqli_close($con);
					}

				}

				else{
					
					$_SESSION['errmsg']="Invalid Captcha, Enter again";
					$extra="registration.php";
					$host  = $_SERVER['HTTP_HOST'];
					$uri  = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
					header("location:http://$host$uri/$extra");
					exit();
				}


		}




}
?>


<!DOCTYPE html>
<html lang="en">

	<head>
		<title>User Registration</title>
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
		
		<script type="text/javascript">
function valid()
{
 if(document.registration.password.value!= document.registration.password_again.value)
{
alert("Password and Confirm Password Fields do not match!");
document.registration.password_again.focus();
return false;
}
return true;
}
</script>
		

	</head>

	<body class="login">
		<!-- start: REGISTRATION -->
		<div class="row">
			<div class="main-login col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
				<div class="logo margin-top-30">
				<a href="../index.html"><h2>Medview+ | Patient Registration</h2></a>
				</div>
				<!-- start: REGISTER BOX -->
				<div class="box-register">
					<form name="registration" id="registration"  method="post" onSubmit="return valid();">
						<fieldset>
							<legend>
								Sign Up
							</legend>
							<p><span style="color:red;"><?php echo $_SESSION['errmsg']; ?><?php echo $_SESSION['errmsg']="";?></span><br> <span style="color:green;"><?php echo $_SESSION['regmsg']; ?><?php echo $_SESSION['regmsg']="";?></span><br><br>
								Enter your personal details below:
							</p>
							


							<div class="form-group row">
							
							<div class="col mb-1">
							<input type="text" class="form-control" name="fullname" placeholder="Full Name" required>
							</div>


							
							</div>



							<div class="form-group">
								<input type="text" class="form-control" name="address" placeholder="Address" required>
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="city" placeholder="City" required>
							</div>
							<div class="form-group">
								<label class="block">
									Gender
								</label>
								<div class="clip-radio radio-primary">
									<input type="radio" id="rg-female" name="gender" value="female" >
									<label for="rg-female">
										Female
									</label>
									<input type="radio" id="rg-male" name="gender" value="male">
									<label for="rg-male">
										Male
									</label>
								</div>
							</div>
							<p>
								Enter your account details below:
							</p>
							<div class="form-group">
								<span class="input-icon">
									<input type="email" class="form-control" name="email" id="email" onBlur="userAvailability()"  placeholder="Email" required>
									<i class="fa fa-envelope"></i> </span>
									 <span id="user-availability-status1" style="font-size:12px;"></span>
							</div>
							<div class="form-group">
								<span class="input-icon">
									<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
									<i class="fa fa-lock"></i> </span>
							</div>
							<div class="form-group">
								<span class="input-icon">
									<input type="password" class="form-control"  id="password_again" name="password_again" placeholder="Password Again" required>
									<i class="fa fa-lock"></i> </span>
							</div>



							<br>
							<p>Enter captcha details</p>
							<div class="form-group row">
							
							
							
							
							<div class="col mb-1">
							<input type="text" class="form-control" name="token" id="token" placeholder="Captcha" style="min-width: 150px;">
							</div>

							<div class="col mb-1">
							<img src="../captcha/image.php?12325" alt="CAPTCHA" id="image-captcha">
							<a href="#" id="refresh-captcha" class="align-middle" title="refresh"><i class="material-icons align-middle">refresh</i></a>
							</div>

						

							
							</div>




							<br>







							<div class="form-group">
								<div class="checkbox clip-check check-primary">
									<input type="checkbox" id="agree" value="agree" checked="true" readonly=" true">
									<label for="agree">
										I agree to <a href="">Terms and conditions.</a>
									</label>
								</div>
							</div>



							<div class="form-actions">
								<p>
									Already have an account?
									<a href="user-login.php">
										Log-in
									</a>
								</p>
								<button type="submit" class="btn btn-primary pull-right" id="submit" name="submit">
									Submit <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</fieldset>
					</form>

					<div class="copyright">
						&copy; <span class="current-year"></span><span class="text-bold text-uppercase"> Medview+</span>. <span>All rights reserved</span>
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
function userAvailability() {
$("#loaderIcon").show();
jQuery.ajax({
url: "check_availability.php",
data:'email='+$("#email").val(),
type: "POST",
success:function(data){
$("#user-availability-status1").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}
</script>	
		<script>
		var refreshButton = document.getElementById("refresh-captcha");
		var captchaImage = document.getElementById("image-captcha");

		refreshButton.onclick = function(event) {
			event.preventDefault();
			captchaImage.src = '../captcha/image.php?' + Date.now();
		};
	</script>
	</body>
	<!-- end: BODY -->
</html>