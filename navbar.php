<div class="navbar" style="text-align: center; margin-bottom: 50px;">
    <h1>Welcome to UCOSnaps, <span style="color: violet;"><?php echo $_SESSION['username']; ?></span></h1>
	<div class="navbar">
    <a href="index.php">Home</a>
    <a href="profile.php?username=<?php echo $_SESSION['username']; ?>">Your Profile</a>
    <a href="allusers.php">All Users</a>
    <a href="core/handleForms.php?logoutUserBtn=1">Logout</a>
</div>