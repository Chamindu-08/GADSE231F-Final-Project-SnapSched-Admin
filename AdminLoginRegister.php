<?php
session_start();

$signupError = $loginError = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //get database connection
    include 'DBConnection/DBConnection.php';

    //check connection
    if (!$connection) {
        echo "Connection failed";
    }

    if (isset($_POST['signup'])) {
        // Sign Up functionality
        $firstName = $_POST["firstName"];
        $nic = $_POST["nic"];
        $email = $_POST["email"];
        $contactNo = $_POST["contactNo"];
        $confirmId = $_POST["confirmId"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];


		if($confirmId != "1234"){
			echo "<script>alert('Invalid Confirmation ID!');</script>";
		}

		else{
			// Check if passwords match
			if ($password !== $confirmPassword) {
				echo "<script>alert('Passwords do not match!');</script>";
			} else {
				// Check if email exists in database
				$check = "SELECT * FROM otherStaff WHERE OSEmail='$email'";
				$result = mysqli_query($connection, $check);
	
				if (mysqli_num_rows($result) > 0) {
					echo "<script>alert('Email already exists!');</script>";
				} else {
                    //generate OSId for new user (OSId = 01 like that)
                    $sql = "SELECT MAX(OSId) AS maxId FROM otherStaff";
                    $result = mysqli_query($connection, $sql);
                    $row = mysqli_fetch_assoc($result);

                    if ($row['maxId'] == null) {
                        $maxId = 0;
                    } else {
                        $maxId = $row['maxId'];
                    }
                    $newId = $maxId + 1;

					// Insert new user into database
					$insert = "INSERT INTO otherStaff (OSId, FirstName, OSEmail, NIC, OSContactNo, OSPassword) VALUES ('$newId', '$firstName', '$email', '$nic', '$contactNo', '$confirmPassword')";

                    // Execute the query
                    $result = mysqli_query($connection, $insert);

                    if ($result) {
                        echo "<script>alert('User registered successfully!');</script>";
                        // Redirect to login page
                        header("Location: AdminLoginRegister.php");
                    } else {
                        echo "<script>alert('Error registering user!');</script>";
                    }
				}
			}
		}
	}

        

    if (isset($_POST['login'])) {
        // Login functionality
        $username = $_POST["loginEmail"];
        $password = $_POST["loginPassword"];

        $sql = "SELECT * FROM otherStaff WHERE OSEmail='$username' AND OSPassword='$password'";
        $result = mysqli_query($connection, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['userNames'] = $username;

            //cookie set for 30 minutes
            setcookie('adminEmail', $username, time() + 1800, '/');

            header("Location: AdminDashBoard.php");
            exit();
        } else {
            echo "<script>alert('Invalid username or password!');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Login | Admin</title>
    <style>
        *{
	margin: 0;
	padding: 0;
	box-sizing: border-box;
	font-family: 'Montserrat', sans-serif;
}

body{
    background : url('Images/banner.png');
    backdrop-filter: blur(2px) brightness(75%);
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	height: 100vh;
}

.container{
	background-color: white;
	border-radius: 30px;
	box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
	position: relative;
	overflow: hidden;
	width: 768px;
	max-width: 100%;
	min-height: 480px;
}

.container p{
	font-size: 14px;
	line-height: 20px;
	letter-spacing: 0.3px;
	margin: 20px 0;
}

.container button{
	background-color: #043082;
	color: white;
	font-size: 12px;
	padding: 10px 45px;
	border: 1px solid transparent;
	border-radius: 8px;
	font-weight: 600;
	letter-spacing: 0.5px;
	text-transform: uppercase;
	margin-top: 10px;
	cursor: pointer;
}

.container button.hidden{
	background-color: transparent;
	border-color: white;
}

.container form{
	background-color: white;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	padding: 0 40px;
	height: 100%;
}

.container input{
	background-color: #eee;
	border: none;
	margin: 8px 0;
	padding: 10px 15px;
	font-size: 13px;
	border-radius: 8px;
	width: 100%;
	outline: none;
}

.form-container{
	position: absolute;
	top: 0;
	height: 100%;
	transition: all 0.6s ease-in-out;
}

.sign-in{
	left: 0;
	width: 50%;
	z-index: 2;
}

.sign-up{
	width: 50%;
}

.container.active .sign-in{
	transform: translateX(100%);
}


.container.active .sign-up{
	transform: translateX(100%);
	opacity: 1;
	z-index: 5;
	animation: move 0.6s;
}

@keyframes move{
	0%, 49.99%{
		opacity: 0;
		z-index: 1;
	}
	50%, 100%{
		opacity: 0;
		z-index: 5;
	}
}

.toggle-container{
	position: absolute;
	top: 0;
	left: 50%;
	width: 50%;
	height: 100%;
	overflow: hidden;
}

.container.active .toggle-container{
	transform: translateX(-100%);
	border-radius: 0 150px 100px 0;
}

.toggle{
	background-color: #043082;
	height: 100%;
	background: linear-gradient(to right, #043082, #043082);
	color: white;
	position: relative;
	left: -100%;
	height: 100%;
	width: 200%;
	transform: translateX(0);
	transition: all 0.6s ease-in-out;
}

.container.active .toggle{
	transform: translateX(50%);
}

.toggle-panel{
	position: absolute;
	width: 50%;
	height: 100%;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	padding: 0 30px;
	text-align: center;
	top: 0;
	transform: translateX(0);
	transition: all 0.6s ease-in-out;
}

.toggle-left{
	transform: translateX(-200%);
}

.container.active .toggle-left{
	transform: translateX(0);
}

.toggle-right{
	right: 0;
	transform: translateX(0);
}

.container.active .toggle-right{
	transform: translateX(200%);
}
    </style>
</head>
<body>
	<div class="container" id="container">
		<div class="form-container sign-up">
			<form id="signupForm" method="post" action="#">
				<h1>Create Account</h1>
				<input type="text" name="firstName" placeholder="First Name" required>
				<input type="text" name="nic" placeholder="NIC" required>
				<input type="email" name="email" placeholder="E-Mail" required>
				<input type="text" name="contactNo" placeholder="Contatct No" required>
				<input type="password" name="confirmId" placeholder="Confirmation ID" required>
				<input type="password" name="password" placeholder="Password" required>
				<input type="password" name="confirmPassword" placeholder="Confirm Password" required>
				<button type="submit" name="signup">Sign Up</button>
				
			</form>
		</div>
		<div class="form-container sign-in">
			<form id="signinForm" method="post" action="#">
				<h1>Sign In</h1>
				<input type="email" name="loginEmail" placeholder="E-Mail" required>
				<input type="password" name="loginPassword" placeholder="Password" required>
				<a href="#">Forget Password?</a>
				<button type="submit" name="login">Sign In</button>
				
			</form>
		</div>
		<div class="toggle-container">	
				<div class="toggle">	
					<div class="toggle-panel toggle-left">	
						<h1>Welcome Back!</h1>
						<p>Enter your personal details to use all of site features</p>
						<button class="hidden" id="login">Sign In</button>
					</div>
					<div class="toggle-panel toggle-right">
						<h1>Hello, Friend</h1>
						<p>Register with your personal details to use all of site features</p>	
						<button class="hidden" id="register">Sign Up</button>
					</div>
				</div>
		</div>
	</div>
		<script type="text/javascript">
			const container = document.getElementById('container');
			const registerButton = document.getElementById('register');
			const loginButton = document.getElementById('login');

			registerButton.addEventListener('click',()=>{
				container.classList.add("active");
			});

			loginButton.addEventListener('click',()=>{
				container.classList.remove("active");
			});
		</script>
</body>
</html>