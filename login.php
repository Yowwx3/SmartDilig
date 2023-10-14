<?php
session_start();
include("connection.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Something was posted
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Check if the username exists
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) == 1) {
            $user_data = mysqli_fetch_assoc($result);

            // Check if the password is correct
            if($user_data['password'] === $password) {
                // Password is correct, so log in the user
                $_SESSION['id'] = $user_data['id'];
                header("Location: index.php");
                die;
            } else {
                echo '<script>alert("Wrong password. Please try again.");</script>';
            }
        } else {
            echo '<script>alert("Username does not exist. Please sign up for an account.");</script>';
        }
    } else {
        echo '<script>alert("Please enter both username and password.");</script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
<link rel="icon" type="image/x-icon" href="images/favicon.png">
<title>SmartDilig - Login</title>
</head>
<body>
	<div class="loginsignup">
		<div id="box">
			<form method="post">
				<div>Login</div>
				<label for="username">Username:</label>
				<input id="username" type="text" name="username" required><br><br>
				<label for="password">Password:</label>
				<input id="password" type="password" name="password" autocomplete="current-password" required><br><br>
				<input id="button" type="submit" value="Login"><br><br>
				<a href="signup.php">Click to Signup</a><br><br>
			</form>
		</div>
	</div>
</body>
</html>
