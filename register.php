<?php  
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<h1>Register here!</h1>
	<?php  
	if (isset($_SESSION['message']) && isset($_SESSION['status'])) {

		if ($_SESSION['status'] == "200") {
			echo "<h1 style='color: green;'>{$_SESSION['message']}</h1>";
		}

		else {
			echo "<h1 style='color: red;'>{$_SESSION['message']}</h1>";	
		}

	}
	unset($_SESSION['message']);
	unset($_SESSION['status']);
	?>
<form action="core/handleForms.php" method="POST">
    <p>
        <label for="username">Username</label>
        <input type="text" name="username" class="short-input">
    </p>
    <p>
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" class="short-input">
    </p>
    <p>
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" class="short-input">
    </p>
    <p>
        <label for="password">Password</label>
        <input type="password" name="password" class="short-input">
    </p>
    <p>
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" class="short-input">
        <input type="submit" name="insertNewUserBtn" style="margin-top: 25px;">
    </p>
</form>
</body>
</html>