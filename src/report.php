<?php
session_start();
require_once "db_connect.php";
db();
global $link;
if($_SESSION['login'] == 0)
{
	$_SESSION['message2'] = "Please Login to access the content";
	header('location: home.php');
}
$query = "SELECT DISTINCT Employee_name from report";
$result = mysqli_query($link , $query);
?>
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Employee Reports</title>
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

  <link rel='stylesheet prefetch' href='https://necolas.github.io/normalize.css/3.0.1/normalize.css'>

      <link rel="stylesheet" href="report.css">
<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #ddd;
}

li {
    float: left;
}

li a {
    display: block;
    color: #666;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover:not(.active) {
    background-color: #111;
}

.active {
    background-color: #4CAF50;
}

input[type=submit] {
    background-color: #ADD8E6;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    float: center;
}
.btn {
  background-color: #f4511e;
  border: none;
  color: white;
  padding: 16px 32px;
  text-align: center;
  font-size: 16px;
  margin: 4px 2px;
  opacity: 0.6;
  transition: 0.3s;
}

.btn:hover {opacity: 1}

</style>
<link rel="ICON" href="favicon.ico" type="image/ico" />
   <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
<ul>
  <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="speechsoft.png" alt="Smiley face" width="120" height="40"></li>
  <li style="float:right"><a href="home.php">Logout</a></li>
  <li style="float:right"><a href="report.php">Home</a></li>
</ul>
  <form method="POST" action = "select.php">
  <br><br><br><br>
  <h2>&nbsp;&nbsp;&nbsp;Select an employee</h2>
  <br>
  <br>
  <fieldset>
    <label for="select-choice">Employee Name</label>
    <select name="select-choice" id="select-choice">
      <?php  while($row = mysqli_fetch_array($result)) { ?>
      <option value = "<?php echo $row['Employee_name'] ?>" ><?php echo $row['Employee_name']?> </option>
      $i++;
      <?php } ?>
    </select>
  </fieldset>
  <br>
  <fieldset>
    <label for="select-year">Year</label>
    <select name="select-year" id="select-year">
    <?php
    $i = 0; 
    while($i <= 100) {
    	$date = 2017 + $i; 
    	?>
      <option value = "<?php echo $date ?>" ><?php echo $date?> </option>
      <?php 
      $i = $i + 1;
       } 
      ?>
    }
    </select>
  </fieldset>
  <br>
<fieldset>
  	<label for="select-month">Month</label>
  	<select name="select-month" id = "select-month">
  		<option value = "January">January</option>
  		<option value = "February">February</option>
  		<option value = "March">March</option>
  		<option value = "April">April</option>
  		<option value = "May">May</option>
  		<option value = "June">June</option>
  		<option value = "July">July</option>
  		<option value = "August">August</option>
  		<option value = "September">September</option>
  		<option value = "October">October</option>
  		<option value = "November">November</option>
  		<option value = "December">December</option>
  	</select>
  </fieldset>
  <br>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "submit" class = "btn" name = "submit" value = "Submit"><br><br>
 <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><footer>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Speech Soft Solutions &copy; 2016</footer>
</form>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  

    <script  src="js/index.js"></script>




</body>
</html>