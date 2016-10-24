<!DOCTYPE HTML> 
<html>
<head>
<meta charset="utf-8">
<title>Sign Up and Send SMS</title>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<?php

include('testQwasi.php');

$nameErr = $emailErr = $phoneErr = $res = "";
$firstName = $lastName = $email = $phone = "";
$error = false;

if($_SERVER["REQUEST_METHOD"] == "POST") {
	// because both of the methods need phone number, so put it at the first place
	// judge if the phone number is empty
	if (empty($_POST["phone"])) {
		$phoneErr = "Phone number must be filled";
		$error = true;
	} else {
		$replacement = array("(",")","-",".");
		$phone = process_input(str_replace($replacement, "", $_POST["phone"]));
	}

	if(isset($_POST['createUser'])) {
		// echo "createUser";
		// judge firstName and last name is legal
		// todo: shorten this part of code later
		if (empty($_POST["firstName"])) {
			$nameErr = "First Name must be filled";
			$error = true;
		} else {
			$firstName = process_input($_POST["firstName"]);
			if (!preg_match("/^[a-zA-Z ]*$/",$firstName)) {
				$nameErr = "Only letter and space allowed"; 
				$error = true;
			}
		}

		if (empty($_POST["lastName"])) {
			$nameErr = "Last Name must be filled";
			$error = true;
		} else {
			$lastName = process_input($_POST["lastName"]);
			if (!preg_match("/^[a-zA-Z ]*$/",$lastName)) {
				$nameErr = "Only letter and space allowed"; 
				$error = true;
			}
		}

		if (empty($_POST["email"])) {
			$emailErr = "Email address must be filled";
			$error = true;
		} else {
			$email = process_input($_POST["email"]);
			if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
				$emailErr = "illegal email form"; 
				$error = true;
			}
		}
		if (!$error) {
			$data = array(
				'firstName' => $firstName,
				'lastName' => $lastName,
				'mobile' => $phone,
				'email' => $email
				);
			$resArray = sendQuery('createUser', $data);
			if ($resArray["ErrorCode"] != -999) {
				$res = "Sign Up: ".$resArray["ErrorString"];
			}
		}		
	}elseif(isset($_POST['sendSMS'])) {
		// echo "send SMS";
		if (!error) {
			$data = array('mobile'=>$phone, 'message'=>"hello world");
			$resArray = sendQuery('sendSMS', $data);
			if ($resArray["ErrorCode"] != -999) {
				$res = "Send Message: ".$resArray["ErrorString"];
			}
		}
	}
	$error = false;
}

function process_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>

<h2>QWASI create user and send sms instance</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
First Name: <input type="text" name="firstName" value="<?php echo $firstName;?>">
<span class="error">* <?php echo $nameErr;?></span>
<br><br>
Last Name: <input type="text" name="lastName" value="<?php echo $lastName;?>">
<span class="error">* <?php echo $nameErr;?></span>
<br><br>
Phone: <input type="text" name="phone" value="<?php echo $phone;?>">
<span class="error">* <?php echo $phoneErr;?></span>
<br><br>
Email: <input type="text" name="email" value="<?php echo $email;?>">
<span class="error">* <?php echo $emailErr;?></span>
<br><br>
<?php echo $res;?>
<br><br>
<input type="submit" name="createUser" value="Sign Up">
<input type="submit" name="sendSMS" value="Send SMS">

</form>

</body>
</html>