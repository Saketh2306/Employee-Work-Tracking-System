<?php
session_start();
$_SESSION['message1'] = "";
$_SESSION['login'] = 0;
require_once "db_connect.php";
db();
global $link;
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['login']))
	{
		$useremail = $_POST['email'];
		$password = $_POST['password'];
		$result = mysqli_query($link,"SELECT * FROM admin WHERE username = '$useremail' and password = '$password'");
		$row = mysqli_fetch_array($result);
		if($row['username'] == $useremail && $row['password'] == $password)
		{
			$_SESSION['message1'] = "Login Successfull!";
			$_SESSION['next'] = "report.php";
			$_SESSION['login'] = 1;
		}
		else
		{
			$_SESSION['message1'] = "Invalid username and password";
			$_SESSION['next'] = "home.php";
		}
		if($_SESSION['next'] == "report.php")
		{
			header('location: report.php');
		}
	}
}
?>
<link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Nunito:400,700'>
<link rel='stylesheet prefetch' href='https://fonts.googleapis.com/icon?family=Material+Icons'>
<link rel="stylesheet" href="home.css" type="text/css">
<link rel="ICON" href="favicon.ico" type="image/ico" />
<script>
window.location.hash="no-back-button";
window.location.hash="Again-No-back-button";//again because google chrome don't insert first hash into history
window.onhashchange=function(){window.location.hash="no-back-button";}
</script> 
<div id="frm-cvr">
<div class="cl-wh" id="f-mlb"><img src="speechsoft.png" alt="Smiley face" width="300" height="100"></div>
<div class="alert alert-error"><?= $_SESSION['message1']?></div>
<?php 
if(isset($_SESSION['message2']))
{ ?>
<div class="alert alert-error"><?= $_SESSION['message2']?></div>

<?php $_SESSION['message2'] = "" ;} ?>
<form  action = "home.php" method = "POST" autocomplete="off" enctype="multipart/form-data">
	<label class="cl-wh f-lb">Username</label>
				<div class="f-i-bx b3 mrg3b">
					<div class="tb">
						<div class="td icn"><i class="material-icons">email</i></div>
						<div class="td prt"><input type="text" name = "email" required ></div>
					</div>
				</div>
	<label class="cl-wh f-lb">Password</label>
				<div class="f-i-bx b3">
					<div class="tb">
						<div class="td icn"><i class="material-icons">lock</i></div>
						<div class="td prt"><input type="password" name = "password" required></div>
					</div>
				</div>
	<div id="s-btn" class="mrg25t"><input type="submit" value="Login" class="b3" name = 'login' formaction="home.php"></div>

	<!--<div id="tc-bx">If not registered <a href="form.php">SignUp!</a>.</div>
	<div id="tc-bx">To redirect to home page <a href ="home.php">Click Here</a></div>-->
</form>
</div>