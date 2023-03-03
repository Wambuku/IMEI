<?php
require_once "pdo.php";
session_start();

$fail=false;

// Demand a GET parameter
if ( ! isset($_GET['email']) || strlen($_GET['email']) < 1  ) {
    header("Location: login.php");
    
    // die('<h3>please login first <a href="login.php">login.php</a></h3>');
}
// send token

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/styles.css" rel="stylesheet">
    <title>imei admin</title>
</head>

<body>

    <div class="mb-5">
        <nav class="navbar navbar-dark fixed-top ">
            <div class="container">
                <a class="navbar-brand mr-auto" href="#">
                    <h3 id="good_blue">IMEI Admin</h3>
                </a>
                <?php 
                    if (isset($_SESSION["success"])){
                        echo('<p style="color: green">'.$_SESSION["success"]. "</p>");
                        unset($_SESSION["success"]);
                    }
                    
                ?>        
                <div class="text-end" id="Navbar">
                    <span class="navbar-text">
                        <img src="img/logo.jpg" height="50" width="50">
                    </span>
                </div>
            </div>
        </nav>
    </div>

    <header>
        <div class="container">
            <div>
                <div>
                    <div class="mt-10">                    
                        <div class="intro pt-3 mt-3">
                            <h3>Admin Duties</h3>
                            <p>
                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Deserunt sequi doloribus quasi. Veniam quo officiis minima recusandae minus. Itaque earum quos labore ipsa nesciunt perferendis, accusantium perspiciatis recusandae soluta maiores.
                                
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. A rerum ducimus ab modi voluptatum. Porro vitae praesentium quaerat exercitationem! Debitis doloremque quis ex labore voluptas esse dignissimos aspernatur ad quia!

                                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Id excepturi, vel voluptatibus numquam veritatis ipsam iste fuga sapiente doloribus consectetur incidunt repellat quaerat dicta sunt. Nam sint quia voluptas sit!

                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam inventore adipisci minima culpa nisi placeat, repellendus temporibus harum! Ipsam illo nulla aspernatur quos expedita saepe voluptatum quae libero illum fuga! 

                            </p>
                        </div>                   
                    </div>
                <div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-center">
        
            <div class="col-md-3 m-md-3 step">
                <h4 class="text-success">Step 1</h4>
                <p>Enter IMEI <br> Please ensure that the IMEI is correct before hitting the "Create/Update" button </p>
            </div>
            <div class="col-md-3 m-md-3 step">
                <h4 class="text-success">Step 2</h4>
                <p>Enter location: If the IMEI is already registered it will just update its location, else it will create a new IMEI with the current location</p>
            </div>
            <div class="col-md-3 m-md-3 step">
                <h4 class="text-success">Step 3</h4>
                <p>Click "Create/Update" button <br> This will return the device IMEI and location </p>
            </div>
            
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                            
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus enim, qui quasi repudiandae tempore voluptatum doloribus consequatur ad tempora et non distinctio deleniti quo nulla eaque corrupti id quod nihil. Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui eius blanditiis corrupti rem? Consequatur doloribus quisquam adipisci autem sunt impedit aliquid reprehenderit vel repellat, beatae quia facilis suscipit natus vero!</p>
                <!-- retrieve data -->
                <?php
                    if (isset($_POST["imei"]) && isset($_POST["location"])){
                        unset($_SESSION["imei"]);

                        $device =  $_POST["imei"];
                        $location = $_POST["location"];
                        // echo $device . " " . $location;

                        // $sql = "SELECT username FROM users WHERE email = :em AND pass_real = :pw";
                        $sql = "SELECT * FROM device_locations WHERE imei = :im";
                
                        $stmt = $pdo -> prepare($sql);
                        $stmt -> execute(array(
                            ':im' => $_POST['imei']
                        ));
                        // echo $stmt
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $loc = $row['location_name'];
                        if (strlen($location) > 1){
                            if ($loc){
                                $sql2 = "UPDATE device_locations SET location_name = :loc WHERE imei = :im";
                                
                                $stmt2 = $pdo -> prepare($sql2);
                                $stmt2 -> execute(array(
                                    ':im' => $_POST['imei'],
                                    ':loc' => $_POST['location']
                                ));
                                
                                echo "<h5 style='color: green' class='mt-4'>Location of device " . $device . " is " . $location. "</h5>";
                            } else{
                                $sql3 = "INSERT INTO device_locations (imei, location_name) VALUES (:im, :loc)";
                                
                                $stmt3 = $pdo -> prepare($sql3);
                                $stmt3 -> execute(array(
                                    ':im' => $_POST['imei'],
                                    ':loc' => $_POST['location']
                                ));
                                echo "<h5 style='color: orange' class='mt-4'>IMEI of " . $device . " stored</h5>";
                            }
                        } else {
                            echo "<p style='color: red'>Please follow instructions carefully";
                        }
                    }
                ?>
    
            </div>
            <div class="col-md-6">
                <form method="post">
                    <div class="form-group row mt-2">
                        <label for="imei" class="col-12 col-md-2 col-form-label">Enter IMEI:</label>
                        <div class="col-12 col-md-6 mt-2">
                            <input type="number" class="form-control" id="imei" name="imei" placeholder="xxxxxxxxxxx">
                        </div>
                    </div>
                    <div class="form-group row mt-2">
                        <label for="location" class="col-12 col-md-2 col-form-label">Enter Location:</label>
                        <div class="col-12 col-md-6 mt-2">
                            <input type="text" class="form-control" id="location" name="location" placeholder="Nakuru">
                        </div>
                        <?php
                        // Note triple not equals and think how badly double
                        // not equals would work here...
                        if ( $fail !== false ) {
                            // Look closely at the use of single and double quotes
                            echo(' <p style="color: red;">'.htmlentities($fail)."</p>\n");
                        }
                        ?>
                    </div>
                    <div class="form-group row m-2">
                        <div class="offset-md-2 col-md-10">
                            <button type="submit" class="btn btn-primary">Create/Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <p>Please <a href="logout.php">log out</a> when you are done</p>
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
</body>

</html>