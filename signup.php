<?php 
session_start();

include("connection.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Something was posted
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if (!empty($username) && !empty($password) && !is_numeric($username)) {
        // Check if the username already exists
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
			echo '<script>alert("Username already exists. Please choose a different username.");</script>';
        } else {
            // Username is unique, so save to the database
            $user_id = random_num(20);
            $query = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
            mysqli_query($con, $query);
            echo '<script>alert("Account successfully created"); setTimeout(function(){ window.location = "login.php"; }, 100);</script>';

            die;
        }
    } else {
		echo '<script>alert("Please enter valid information!");</script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
<link rel="icon" type="image/x-icon" href="images/favicon.png">
<title>SmartDilig - Sign up</title>
</head>
<body>
	<div class="loginsignup">
		<div id="box">
			<form method="post">
				<div>Signup</div>
				<label for="username">Username:</label>
				<input id="username" type="text" name="username" required><br><br>
				<label for="password">Password:</label>
				<input id="password" type="password" name="password" autocomplete="off" required><br><br>
				<label for="email">Email:</label>
				<input id="email" type="text" name="email" required><br><br>
				<input id="button" type="submit" value="Signup"><br><br>
				<a href="login.php">Click to Login</a><br><br>
			</form>
		</div>
	</div>
</body>
</html>
