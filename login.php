<?php
require_once "pdo.php";
session_start();
$failure=false;



if (isset($_POST["email"]) && isset($_POST["password"])){
	unset($_SESSION["email"]);
	if($_POST['email'] == "" || $_POST['password'] == ""){
		// $failure="Please enter password and email";
		$_SESSION['error'] = "Please enter password and email";
		header("Location: login.php");
		return;
	}
	elseif (strpos($_POST['email'], '@') == false) {
		// $failure="Email must have an at-sign (@)";
		$_SESSION['error'] = "Email must have an at-sign (@)";
		header("Location: login.php");
		return;
		}
	else{
		$sql = "SELECT * FROM user WHERE email = :em AND password = :pw";

		$stmt = $pdo -> prepare($sql);
		$stmt -> execute(array(
			 ':em' => $_POST['email'], 
			':pw' => $_POST['password']
		));

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['lastname'] = $row['lastname'];

		// change the name of users table if there are problems
		$sqlAd = "SELECT * FROM `users` WHERE email = :em AND password = :pw";
	
		$stmt = $pdo -> prepare($sqlAd);
		$stmt -> execute(array(
			 ':em' => $_POST['email'], 
			':pw' => $_POST['password']
		));

		$rowAd = $stmt->fetch(PDO::FETCH_ASSOC);
		$_SESSION['lastname'] = $row['lastname'];
		
		if ( $row === FALSE ) {
			// if user is not normal user check whether user is admin
			if ($rowAd === FALSE){
				$_SESSION['error'] = "Incorrect password";
				header("Location: login.php");
				return;
			} else {
				$_SESSION['success'] = "logged in successfully";
				header("Location: index.php?email=".urlencode($_POST['email']));
				return;
			}
	   } 
	   else { 
			error_log("Login success ".$_POST['email']);
			$_SESSION['success'] = "logged in successfully";
			// echo "<p>Login success</p>";
			$_SESSION['imeis'] = $row['imei'];
			header("Location: login.php?email=".urlencode($_POST['email']));
			return;
				
		}
  	}
}
// SIGNUP
if ( isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['imei']) && isset($_POST['pass_real']) && isset($_POST['pass_conf']) && isset($_POST['location'])) {
  $make = $_POST['email'];
  $immei = $_POST['imei'];
  	if (strlen($make) > 1) {
    	if (is_numeric($_POST['imei'])  && strlen($immei) == 15) {
      	$sql = "INSERT INTO user (firstname, lastname, email, imei, serialno, password)
				VALUES (:firstname, :lastname, :email, :imei, :serialno, :password);INSERT INTO device_locations (imei, location_name) VALUES (:imei, :loc);";
	//   echo ("<pre>\n".$sql."\n</pre>\n");
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':firstname' => htmlentities($_POST['firstname']),
				':lastname'=>  htmlentities($_POST['lastname']),
				':email' =>  htmlentities($_POST['email']),
				':imei' =>  htmlentities($_POST['imei']),
				':serialno' =>  htmlentities($_POST['serialno']),
				':loc' => htmlentities($_POST['location']),
				':password' =>  htmlentities($_POST['pass_real']),
			));

			//$row = $stmt->fetch(PDO::FETCH_ASSOC);
			//$_SESSION['lastname'] = $row['lastname'];
			
			
			header("Location: login.php");
			return;
			
      } else {
        echo("imei must be numeric and 15 characters long");
		  header("Location: login.php");
		return;
      }
    } else {
      echo ("Email is required.");
		header("Location: login.php");
		return;
    }
}
// End of sign up
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">

		<!-- Bootstrap CSS -->
		<title>imeis</title>
		<link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
		<link href="css/styles.css" rel="stylesheet">
		<?php require_once "pdo.php"; ?>
	</head>
	<body>

		<div class="mb-5">
			<nav class="navbar navbar-dark fixed-top ">
				<div class="container">
					<a class="navbar-brand mr-auto" href="#"><img src="img/logo.jpg" height="50" width="50"></a>
					<div class="text-end" id="Navbar"> 
						<span class="navbar-text">
						<?php
							if (!isset($_REQUEST['email'])){
								
							echo '<button class="btn btn-outline-warning mx-3"><a id="loginLink">
						<span class="fa fa-sign-in"></span>Login</a></button><button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#registermodal"><a id="registerLink">
						<span class="fa fa-sign-in"></span> Register</a><button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#adminModal"><a id="loginLink">
						<span class="fa fa-sign-in"></span>Admin</a>
						</button>';} elseif (isset($_REQUEST['email'])){
							echo "greetings ".$_SESSION['lastname'] ."! <a href='logout.php'>Logout</a";
						}
						?>
						</span>
					</div>
				</div>
			</nav>
		</div>


		
		<div id="loginModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg" role="content">
				<!-- Modal content-->
				<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Login </h4>
							<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
						</div>
					<div class="modal-body">
						<form method="post">
							<div class="row g-3">
								<div class="col-sm-4">
									<label class="visually-hidden" for="exampleInputEmail3">Email address</label>
									<input type="text"  name="email" class="form-control form-control-sm mr-1" id="exampleInputEmail3" placeholder="name@gmail.com">
								</div>
								<div class="col-sm-4">
									<label class="visually-hidden" for="exampleInputPassword3">Password</label>
									<input type="password" name="password" class="form-control form-control-sm mr-1" id="exampleInputPassword3" placeholder="Password">
								</div>
								<div class="col-sm-4">
									<input class="form-check-input" type="checkbox">
									<label class="form-check-label"> Remember me
									</label>
								</div>
								<div class="mr-0">
									<button type="button" class="btn btn-secondary btn-sm ml-auto" data-bs-dismiss="modal">Cancel</button>
									<button type="submit" class="btn btn-warning btn-sm ml-1">Sign in</button>
									
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>



		<!--admin modal-->


		<div id="adminModal" class="modal fade" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg" role="content">
				<!-- Modal content-->
				<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Admin</h4>
							<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
						</div>
					<div class="modal-body">
						<form method="post">
							<div class="row g-3">
								<div class="col-sm-4">
									<label class="visually-hidden" for="exampleInputEmail3">Email address</label>
									<input type="text"  name="email" class="form-control form-control-sm mr-1" id="exampleInputEmail3" placeholder="name@gmail.com">
								</div>
								<div class="col-sm-4">
									<label class="visually-hidden" for="exampleInputPassword3">Password</label>
									<input type="password" name="password" class="form-control form-control-sm mr-1" id="exampleInputPassword3" placeholder="Password">
								</div>
								<div class="col-sm-4">
									<input class="form-check-input" type="checkbox">
									<label class="form-check-label"> Remember me
									</label>
								</div>
								<div class="mr-0">
									<button type="button" class="btn btn-secondary btn-sm ml-auto" data-bs-dismiss="modal">Cancel</button>
									<button type="submit" class="btn btn-warning btn-sm ml-1">Sign in</button>
									
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<!-- The register modal -->
		<div id="registerModal" class="modal fade" role="dialog" tabindex="-2">
    		<div class="modal-dialog modal-lg" role="content">
        		<!-- Modal content-->
        		<div class="modal-content">
            		<div class="modal-header">
                		<h4 class="modal-title">Register </h4>
                		<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            		</div>
            	<div class="modal-body">
					<form method="post">
						<div class="row g-3">
						<div class="row mb-3">
                      <label for="firstname" class="col-md-2 col-form-label">First Name</label>
                      <div class="col-md-10">
                          <input type="text" class="form-control" name="firstname" placeholder="First Name">
                      </div>
                  </div>
                  <div class="row mb-3">
                      <label for="lastname" class="col-md-2 col-form-label">Last Name</label>
                      <div class="col-md-10">
                          <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">
                      </div>
                  </div>
                  
						<div class="row mb-3">
							<label for="email" class="col-md-2 col-form-label">Email</label>
							<div class="col-md-10">
									<input type="email" class="form-control" id="email" name="email" placeholder="Email">
							</div>
						</div>
						
						<div class="row mb-1">
							<label for="imei" class="col-md-2 col-form-label">Imei</label>
							<div class="col-md-10">
								<input type="number" class="form-control" id="imei" name="imei" placeholder="0000000000000000">
							</div>
                  </div>
						<div class="row mb-1">
							<label for="serialno" class="col-md-2 col-form-label">Serial No</label>
							<div class="col-md-10">
								<input type="number" class="form-control" id="serialno" name="serialno" placeholder="0000000000000000">
							</div>
                  </div>
						<div class="row mb-3">
                      <label for="location" class="col-md-2 col-form-label">Location</label>
                      <div class="col-md-10">
                          <input type="text" class="form-control" id="location" name="location" placeholder="Location">
                      </div>
                  </div>
						<div class="row mb-3">
							<label for="pass_real" class="col-md-2 col-form-label">Password</label>
							<div class="col-md-10">
								<input type="password" class="form-control" id="pass_real" name="pass_real" placeholder="pass">
							</div>
                  </div>
				  		<div class="row mb-3">
                      <label for="pass_conf" class="col-md-2 col-form-label">Confirm Password</label>
                      <div class="col-md-10">
                          <input type="password" class="form-control" id="pass_conf" name="pass_conf" placeholder="pass">
                      </div>
                  </div>
					
							
							<div class="mr-0">
							<button type="button" class="btn btn-secondary btn-sm ml-auto" data-bs-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-warning btn-sm ml-1">Register</button>
						</div>
						</div>
				</div>
				
			</form>
            </div>
        </div>
    </div>
</div>

		<!-- The register modal -->

		<div class="container">
			<div class="row row-header d-flex justify-content-center">
				<div class="col-12 col-sm-6">
					<?php
					if (isset($_SESSION["error"])){
								echo('<p style="color: red">'.$_SESSION["error"]. "</p>\n");
								unset($_SESSION["error"]);
							}
					if (isset($_SESSION["success"])){
								echo('<p style="color: green">'.$_SESSION["success"]. "</p>\n");
								unset($_SESSION["success"]);
							}
					?>
				</div>
				<div class="col-12 col-sm-6"></div>
			</div>
		</div>
			
				
		<div class="container m-auto mt-4">
			<div class="mt-4" >
				<p class="text-justify p-5">An IMEI tracking is a method where the current device location can be found using IMEI Number. Our software will allow you to search for the location of your device by giving it the IMEI of your device.
				The IMEI (International Mobile Equipment Identity) is a unique number to identify GSM, WCDMA, and iDEN mobile phones, as well as some satellite phones. Mostly phone have one IMEI number, but in dual SIM phones are two.</p>
			</div>
			<div class="row">
				<div class="col-md-6">
					<form method="post">
						<div class="mt-4 align-items-center g-3">
							<div class="">
								<label class="mb-3" for="imei">Enter device IMEI</label>
								<input type='text'  name='imei' class='form-control form-control-sm mr-1' id='imei' placeholder="1234567890" >
							</div>
							
							<div class="mr-0 mt-3">
								<button type="submit" class="btn btn-warning btn-sm ml-1">Retrieve</button>
							</div>
						</div>

					</form>

					<!-- retrieve data -->
					<?php 
					if (isset($_POST["imei"]) && $_POST["imei"] == $_SESSION['imeis']){
						unset($_SESSION["imei"]);
						$device =  $_POST["imei"];

							// $sql = "SELECT username FROM user WHERE email = :em AND pass_real = :pw";
							$sql = "SELECT * FROM device_locations WHERE imei = :im";

							$stmt = $pdo -> prepare($sql);
							$stmt -> execute(array(
								':im' => $_POST['imei']
							));
							// echo $stmt
							$row = $stmt->fetch(PDO::FETCH_ASSOC);
							$loc = $row['location_name'];
							
							if ($loc){
								echo "<h5 style='color: green' class='mt-4'>Location of device " . $device . " is " . $loc. "</h5>";
							} else{
								echo "<h5 style='color: red' class='mt-4'>Location of device " . $device . " Not found!</h5>";
							}
						}
					?>
					<?php
					if (isset($_REQUEST['email'])){
						echo '
					<form method="post">
						<div class="mt-4 align-items-center d-flex justify-content-around g-3">
							<div class="">
								<input type="text"  name="lock-device" class="form-control form-control-sm mr-1" id="lock-device" placeholder="1234567890" value='.$_SESSION['imeis'].' >
							</div>
							
							<div class="mr-0 mt-3">
								<button type="submit" class="btn btn-danger btn-sm ml-1">Lock Device</button>
							</div>
						</div>
					</form>
					
					<form method="post">
						<div class="mt-4 align-items-center d-flex justify-content-around g-3">
							<div class="">
								<input type="text"  name="set-alarm" class="form-control form-control-sm mr-1" id="set-alarm" placeholder="1234567890" value='.$_SESSION['imeis'].'>
							</div>
							
							<div class="mr-0 mt-3">
								<button type="submit" class="btn btn-danger btn-sm ml-1">Alarm Device</button>
							</div>
						</div>
					</form>
					';
					}
					?>
					
					<?php 
					if (isset($_POST["lock-device"]) && $_POST["lock-device"] == $_SESSION['imeis']){
						$sql = "SELECT * FROM device_locations WHERE imei = :ld";
                
						$stmt = $pdo -> prepare($sql);
						$stmt -> execute(array(
								':ld' => $_POST['lock-device']
						));
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
                  $loc = $row['locked'];
						if ($loc == 0){
							$info = "Device locked";
							// $sql101 = "INSERT INTO reports (detail, created_at, FK_UserReport) VALUES "."('{$info}', CURRENT_TIMESTAMP, 123456789987654321)";       
							// $stmt102 = $pdo -> prepare($sql101);
							// $stmt102 -> execute();
							$sql2 = "UPDATE device_locations SET locked = 1 WHERE imei = :ld; INSERT INTO reports (detail, created_at, FK_UserReport) VALUES "."('{$info}', CURRENT_TIMESTAMP, {$_SESSION['imeis']});";         
							$stmt2 = $pdo -> prepare($sql2);
							$stmt2 -> execute(array(
								':ld' => $_POST['lock-device']
							));
							echo "<h5 style='color: red' class='mt-4'>You just locked your device</h5>";
						} 
						if ($loc == 1) {
							$info2 = "Device Unlocked";
							$sql3 = "UPDATE device_locations SET locked = 0 WHERE imei = :ld; INSERT INTO reports (detail, created_at, FK_UserReport) VALUES "."('{$info2}', CURRENT_TIMESTAMP, {$_SESSION['imeis']});";         
							$stmt3 = $pdo -> prepare($sql3);
							$stmt3 -> execute(array(
								':ld' => $_POST['lock-device']
							));
							echo "<h5 style='color: green' class='mt-4'>You just unlocked your device</h5>";
						}
						
					} 
					if (isset($_POST["set-alarm"])  && $_POST["set-alarm"] == $_SESSION['imeis']){
						$sql = "SELECT * FROM device_locations WHERE imei = :ld";
                
						$stmt = $pdo -> prepare($sql);
						$stmt -> execute(array(
								':ld' => $_POST['set-alarm']
						));
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
                  $loc = $row['alarm'];
						if ($loc == 0){
							$info4 = "Alarm enabled";
							$sql4 = "UPDATE device_locations SET alarm = 1 WHERE imei = :ld; INSERT INTO reports (detail, created_at, FK_UserReport) VALUES "."('{$info4}', CURRENT_TIMESTAMP, {$_SESSION['imeis']});";         
							$stmt4 = $pdo -> prepare($sql4);
							$stmt4 -> execute(array(
								':ld' => $_POST['set-alarm']
							));
							echo "<h5 style='color: red' class='mt-4'>You just set the alarm for your device</h5>";
						} 
						if ($loc == 1) {
							$info3 = "Alarm disabled";
							$sql5 = "UPDATE device_locations SET alarm = 0 WHERE imei = :ld; INSERT INTO reports (detail, created_at, FK_UserReport) VALUES "."('{$info3}', CURRENT_TIMESTAMP, {$_SESSION['imeis']});";        
							$stmt5 = $pdo -> prepare($sql5);
							$stmt5 -> execute(array(
								':ld' => $_POST['set-alarm']
							));
							echo "<h5 style='color: green' class='mt-4'>You just unalarmed your device</h5>";
						}
						
					}
					?>
				</div>
				<div class="col-md-6 mb-4">
					<img src="img/IMEI.jpg" class="" style="width: 100%; height=50%" alt="">
				</div>
			</div>
		</div>

		<footer class="footer poo">
				<div class="container">
					<div class="row">        
							<div class="col-4 col-sm-3">
								<h5>Links</h5>
								<ul class="list-unstyled">
									<li><a href="#">Home</a></li>
									<li><a href="#">About</a></li>
									<li><a href="#">Menu</a></li>
									<li><a href="#">Contact</a></li>
								</ul>
							</div>
							<div class="col-7 col-sm-5">
								<h5>Our Address</h5>
								<address>
									121, Nakuru-Kabarak road<br>
									Imei Tracker, Nakuru, Kenya<br>
									Tel.: +254 712-345-678<br>
									Email: <a href="mailto:imeitracker@gmail.com">imeitracker@gmail.com</a>
								</address>
							</div>
							<div class="col-12 col-sm-4 align-self-center">
								<div class="text-center">
									<a href="http://google.com/+">Google+</a>
									<a href="http://www.facebook.com/profile.php?id=">Facebook</a>
									<a href="http://www.linkedin.com/in/">LinkedIn</a>
									<a href="http://twitter.com/">Twitter</a>
									<a href="http://youtube.com/">YouTube</a>
									<a href="mailto:">Mail</a>
								</div>
							</div>
					</div>
					<div class="row justify-content-center">             
							<div class="col-auto">
								<p>© Copyright 2022 IMEI TRACKER</p>
							</div>
					</div>
				</div>
		</footer>
		<script src="node_modules/jquery/dist/jquery.slim.min.js"></script>
    	<script src="node_modules/@popperjs\core/dist/umd/popper.min.js"></script>
		 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
		<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="js/scripts.js"></script>
	</body>
</html>