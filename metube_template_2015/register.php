<?php
ob_start();
session_start();

include_once "function.php";

if(isset($_POST['submit'])) {
	if($_POST['username'] == "" || $_POST['password1'] == "" || $_POST['password2'] == "") {
			$register_error = "One or more fields are missing.";
	}
	else if( $_POST['passowrd1'] != $_POST['passowrd2']) {
		$register_error = "Passwords don't match. Try again?";
	}
	else {
		$check = user_exist_check($_POST['username'], $_POST['passowrd1']);	
		if($check == 1){
			//echo "Rigister succeeds";
			$_SESSION['username']=$_POST['username'];
			header('Location: browse.php');
		}
		else if($check == 2){
			$register_error = "Username already exists. Please use a different username.";
		}
	}
}

?>
<form action="register.php" method="post">
	Username: <input type="text" name="username"> <br>
	Create Password: <input  type="password" name="passowrd1"> <br>
	Repeat password: <input type="password" name="passowrd2"> <br>
	<input name="submit" type="submit" value="Submit"><input name="reset" type="reset" value="Reset">
</form>

<?php
  if(isset($register_error))
   {  echo $register_error; }
?>

</body>
</html>
