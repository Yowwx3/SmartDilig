<?php 

session_start();

	include("connection.php");
	include("functions.php");


	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		//something was posted
		$username = $_POST['username'];
		$password = $_POST['password'];

		if(!empty($username) && !empty($password) && !is_numeric($username))
		{

			//read from database
			$query = "select * from users where username = '$username' limit 1";
			$result = mysqli_query($con, $query);

			if($result)
			{
				if($result && mysqli_num_rows($result) > 0)
				{

					$user_data = mysqli_fetch_assoc($result);
					
					if($user_data['password'] === $password)
					{

						$_SESSION['id'] = $user_data['id'];
						header("Location: index.php");
						die;
					}
				}
			}
			
			echo '<script>alert("Wrong username or password!");</script>';
		}else
		{
			echo '<script>alert("Wrong username or password!");</script>';
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
