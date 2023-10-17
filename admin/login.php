<?php
session_start();
date_default_timezone_set("Asia/Phnom_Penh");
include_once '../config_db/config_db.php';
?>


<!DOCTYPE html>
<html lang="en"> 
<head>
    <title>Log In Page</title>
    
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">    
    <link rel="shortcut icon" href="favicon.ico"> 
    
    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
    
    <!-- App CSS -->  
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">

</head> 

<body class="app app-login p-0">    	
    <div class="row g-0 app-auth-wrapper">
	    <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
		    <div class="d-flex flex-column align-content-end">
			    <div class="app-auth-body mx-auto">	
				    <div class="app-auth-branding mb-4"><a class="app-logo" href="index.php"><img class="logo-icon me-2" src="assets/images/X-ComShop-Logo.ico" alt="logo"></a></div>
					<h2 style="font-family: Kantumruy Pro;" class="auth-heading text-center mb-5">កម្មវិធីគ្រប់គ្រងហាងលក់ Computer</h2>
					<?php
						if(isset($_POST['login'])){
							$username = $conn->real_escape_string(trim($_POST['txtusername']));
							$password = $conn->real_escape_string(trim($_POST['txtpassword']));
							$sql = "select * from tbl_user where username = '$username' and password = '".md5($password)."'";
							$result = $conn->query($sql);
							// echo $sql;
							// echo mysqli_num_rows($result);
							if(mysqli_num_rows($result) > 0){
								$row = mysqli_fetch_array($result);
								$_SESSION['user_login'] = $username;
								$_SESSION['user_role'] = $row['role'];
								$_SESSION['user_people_id'] = $row['id'];
								header("location: index.php");
							}
							else{
								echo '<p class="text-danger">Wrong Username or Password</p>';
							}
						}
					?>
					
			        <div class="auth-form-container text-start">
						<form class="auth-form login-form" method="post">         
							<div class="email mb-3">
								<label class="sr-only" for="signin-email">Username</label>
								<input id="signin-email" name="txtusername" type="text" class="form-control signin-email" placeholder="Username" required="required">
							</div><!--//form-group-->
							<div class="password mb-3">
								<label class="sr-only" for="signin-password">Password</label>
								<input id="signin-password" name="txtpassword" type="password" class="form-control signin-password" placeholder="Password" required="required">
								<div class="extra mt-3 row justify-content-between">
									<div class="col-6">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" value="" id="RememberPassword">
											<label class="form-check-label" for="RememberPassword">
											Remember me
											</label>
										</div>
									</div><!--//col-6-->
									<div class="col-6">
										<!-- <div class="forgot-password text-end">
											<a href="reset-password.html">Forgot password?</a>
										</div> -->
									</div><!--//col-6-->
								</div><!--//extra-->
							</div><!--//form-group-->
							<div class="text-center">
								<button type="submit" name="login" class="btn app-btn-primary w-100 theme-btn mx-auto">Log In</button>
							</div>
						</form>
						
						<!-- <div class="auth-option text-center pt-5">No Account? Sign up <a class="text-link" href="signup.html" >here</a>.</div> -->
					</div><!--//auth-form-container-->	

			    </div><!--//auth-body-->
		    </div><!--//flex-column-->   
	    </div><!--//auth-main-col-->
	    <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
		    <div class="auth-background-holder">
		    </div>
		    <div class="auth-background-mask"></div>
		    
	    </div><!--//auth-background-col-->
    
    </div><!--//row-->


</body>
</html> 

