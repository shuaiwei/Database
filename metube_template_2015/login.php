<?php
ob_start();
session_start();
?>

<link rel="stylesheet" type="text/css" href="css/default.css" />

<?php

include_once "function.php";

if(isset($_POST['submit'])) {
		if($_POST['username'] == "" || $_POST['password'] == "") {
			$login_error = "One or more fields are missing.";
		}
		else {
			$check = user_pass_check($_POST['username'],$_POST['password']); // Call functions from function.php
			if($check == 1) {
				//$login_error = "User ".$_POST['username']." not found.";
				$login_error = "The username or password you entered is incorrect.";
			}
			elseif($check==2) {
				//$login_error = "Incorrect password.";
				$login_error = "The username or password you entered is incorrect.";

			}
			else if($check==0){
				$_SESSION['username']=$_POST['username']; //Set the $_SESSION['username']
				header('Location: browse.php');
			}		
		}
}


 
?>
	<form method="POST" action="<?php echo "login.php"; ?>">

	<table width="100%">
		<tr>
			<td  width="20%">Username:</td>
			<td width="80%"><input class="text"  type="text" name="username"><br /></td>
		</tr>
		<tr>
			<td  width="20%">Password:</td>
			<td width="80%"><input class="text"  type="password" name="password"><br /></td>
		</tr>
		<tr>
        
			<td><input name="submit" type="submit" value="Login"><input name="reset" type="reset" value="Reset"><br /></td>
		</tr>
	</table>
	</form>

<?php
  if(isset($login_error))
   {  echo "<div id='1'>".$login_error."</div>";}
?>
