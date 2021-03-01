<?php
session_start();
if($_SESSION['login'] == 0)
{
  $_SESSION['message2'] = "Please Login to access the content";
  header('location: home.php');
}
?>
<!DOCTYPE html>
<html>
<head>
<style>
body  {
    background-image: url("error.jpg");
}
div {
    font-size: 30px;
	align: center;
}
</style>
<link rel="ICON" href="favicon.ico" type="image/ico" />
</head>
<body>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<div align = "center" ><a href = "report.php">Click Here To Redirect To Home</a></div>

</body>
</html>