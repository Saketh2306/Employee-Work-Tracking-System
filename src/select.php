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
if(isset($_POST['submit']) == true)
{
  $name = $_POST['select-choice'];
  $year = (string)$_POST['select-year'];
  $month = $_POST['select-month'];
  if($month == 'January')
  {
    $m = '01';
  }
  if($month == 'February')
  {
    $m = '02';
  }
  if($month == 'March')
  {
    $m = '03';
  }
  if($month == 'April')
  {
    $m = '04';
  }
  if($month == 'May')
  {
    $m = '05';
  }
  if($month == 'June')
  {
    $m = '06';
  }
  if($month == 'July')
  {
    $m = '07';
  }
  if($month == 'August')
  {
    $m = '08';
  }
  if($month == 'September')
  {
    $m = '09';
  }
  if($month == 'October')
  {
    $m = '10';
  }
  if($month == 'November')
  {
    $m = '11';
  }
  if($month == 'December')
  {
    $m = '12';
  }
  $query = "SELECT distinct dates from report where Employee_name = '$name' and dates like '%$year' and dates like '___$m%'";
  //$query = "SELECT distinct dates from report where Employee_name in (SELECT Employee_name from report where dates like '%$year' and dates like '___$m%' and Employee_name = '$name')";
  $query2 = "SELECT * from reports where Employee_name = '$name' and dates like '%$year' and dates like '___$m%' and Intime = 0";
  $query6 = "SELECT * from reports where Employee_name = '$name' and dates like '%$year' and dates like '___$m%' and Outtime = 0";
  $result5 = mysqli_query($link , $query2);
  $result6 = mysqli_query($link , $query6);
  $num = mysqli_num_rows($result5);
  $num = mysqli_num_rows($result6);
  $result = mysqli_query($link , $query);
  $sum2 = 0;
  $rec = 0;
  while($row = mysqli_fetch_array($result))
  {
    $date3 = $row['dates'];
    $query1 = "SELECT * from report where dates = '$date3' and Employee_name = '$name'";
    $result1 = mysqli_query($link , $query1);
    $diff1 = 0;
    $sum1 = 0;
    $k = 0;
    while($row1 = mysqli_fetch_array($result1))
    {
        $intime1 = $row1['Intime'] / 10000;
        $outtime1 = $row1['Outtime'] / 10000;
        $diff1 = $outtime1 - $intime1;
        if($outtime1 == 0 || $intime1 == 0)
        {
          $diff1 = 0;
          $k++;
        }
        $sum1 = $sum1 + $diff1;
    }
    $sum2 = $sum2 + $sum1;
	$rec = $rec + $k;
    $query2 = "INSERT INTO graph (dates,hours) VALUES ('$date3' , '$sum1')";
    mysqli_query($link , $query2);
  }
  $query3 = "SELECT dates , hours FROM graph";
  $result3 = mysqli_query($link, $query3); 
  $query = "DELETE FROM graph";
  mysqli_query($link , $query);
  $query = "SELECT distinct dates from report where Employee_name = '$name' and dates like '%$year' and dates like '___$m%'";
	//$query = "SELECT distinct dates from report where Employee_name in (SELECT Employee_name from report where dates like '%$year' and dates like '___$m%' and Employee_name = '$name')";
  $result = mysqli_query($link , $query);
  while($row = mysqli_fetch_array($result))
  {
	  $date1 = $row['dates'];
	  $query = "SELECT dates,Intime from report where Sr_no IN(SELECT MIN(Sr_no) FROM report where dates = '$date1' and Employee_name = '$name')";
	  $query1 = "SELECT dates ,Outtime from report where Sr_no IN(SELECT MAX(Sr_no) FROM report where dates = '$date1' and Employee_name = '$name')";
	  $result1 = mysqli_query($link , $query);
	  $result2 = mysqli_query($link , $query1);
	  while($row = mysqli_fetch_array($result1))
	  {
		  $date12 = $row['dates'];
		  $intimes = 0;
		  $intimes = $row['Intime'];
		  //$outtimes = $row1['Outtime'];
		  while($row1 = mysqli_fetch_array($result2))
		  {
			  $outtimes = 0;
			  if($row1['dates'] == $date12)
			  {
				  $outtimes = $row1['Outtime'];
				  break;
			  }
		  }
		  $query2 = "INSERT INTO graphs (dating , Incoming , Outgoing) VALUES ('$date12' , '$intimes' , '$outtimes')";
		  mysqli_query($link , $query2);
	  }
  }
  $query4 = "SELECT * from graphs";
  $result4 = mysqli_query($link , $query4);
 $query = "DELETE FROM graphs";
  mysqli_query($link , $query);
}
if(isset($_POST['submit']) == false)
{
	header('location: error.php');
}

?>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Hours'],
          <?php while($row = mysqli_fetch_array($result3))
          {
            echo "['".$row["dates"]."', ".$row["hours"]."],"; 
          }
          ?>
        ]);

        var options = {
          title: '<?php echo $name ?> Report',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
	    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']}); 
google.charts.setOnLoadCallback(drawChart); 
function drawChart() { 
    var data = google.visualization.arrayToDataTable([ 
        ['Day', 'Intime',{type: 'string', role: 'tooltip', 'p': {'html': true}} , 'Outtime' , {type: 'string', role: 'tooltip', 'p': {'html': true}}],
		<?php while($row = mysqli_fetch_array($result4)) { 
		$in = $row['Incoming']/10000;
			$out = $row['Outgoing']/10000;
			if((int)$in < 12)
			{
				$clock = "AM";
			}
			if((int)$in >= 12)
			{
				$clock = "PM";
			}
			if((int)$out < 12)
			{
				$clocks = "AM";
			}
			if((int)$out >= 12)
			{
				$clocks = "PM";
			}
			$in1 = sprintf('%02d:%02d', (int) $in, fmod($in, 1) * 60);
			$out1 = sprintf('%02d:%02d', (int) $out, fmod($out, 1) * 60);
            echo "['".$row["dating"]."', ".$in." ,'<div><p>In-time  : ".$in1." ".$clock." </p></div>' , ".$out." , '<div><p>Out time :  ".$out1." ".$clocks."</p></div>'],"; 
		}
		?>
    ]); 
         
    var options = { 
        title: '<?php echo $name ?> In and Out Report', 
        curveType: 'function', 
        legend: { position: 'bottom' }, 
        animation:{ duration: 1000, easing: 'out', startup: true },
        tooltip: {isHtml: true}, 
        hAxis: {format:'E, d-MMM'}, 
        vAxis: {
            title: 'Time of day',
            viewWindowMode:'maximized',
            //ticks is used to show custom text instead of actual integrar value
            //for example int value of 8 would be shown as 8:00 AM
            ticks: [
				{v: 0, f: '00:00 AM'},
				{v: 1, f: '1:00 AM'},
				{v: 2, f: '2:00 AM'},
				{v: 3, f: '3:00 AM'},
				{v: 4, f: '4:00 AM'},
				{v: 5, f: '5:00 AM'},
				{v: 6, f: '6:00 AM'},
				{v: 7, f: '7:00 AM'},	
                {v: 8, f: '8:00 AM'},
                {v: 9, f: '9:00 AM'},
                {v: 10, f: '10:00 AM'},
                {v: 11, f: '11:00 AM'},
				{v: 12, f: '12:00 PM'},
				{v: 13, f: '13:00 PM'},
				{v: 14, f: '14:00 PM'},
				{v: 15, f: '15:00 PM'},
				{v: 16, f: '16:00 PM'},
				{v: 17, f: '17:00 PM'},
				{v: 18, f: '18:00 PM'},
				{v: 19, f: '19:00 PM'},
				{v: 20, f: '20:00 PM'},
				{v: 21, f: '21:00 PM'},
				{v: 22, f: '22:00 PM'},
				{v: 23, f: '23:00 PM'},
				{v: 24, f: '24:00 PM'},
             ]
        } 
    }; 
         
    var chart = new google.visualization.LineChart(document.getElementById('chart_division')); 
    chart.draw(data, options); 
}
    </script>
<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

li {
    float: left;
}

li a {
    display: block;
    color: white;
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

</style>
<style>
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 50%;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}
</style>
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="ICON" href="favicon.ico" type="image/ico" />
  </head>
  <body>
	<ul>
  <li style="float:right"><a href="home.php">Logout</a></li>
  <li style="float:right"><a href="report.php">Home</a></li>
</ul>
	<br><img src="speechsoft.png" alt="Smiley face" width="300" height="100">
	<br>
	<br>
	<h3 align = "left">Year : <?php echo $year ?></h3>
    <br>
	<h3 align = "left">Month : <?php echo $month ?></h3>
    <br>
	<h3><font face = "Helvetica Neue">Working Hours  : <?php echo $sum2 ?> Hours </font></h3>
	<br>
	<h3 align = "center"> Graphical Statistics Of Daily Working Hours : </h3> 
    <div id="curve_chart" style="width: 2000px; height: 600px"></div>
	<br>
	<div id="chart_division" style="width: 2000px; height: 600px"></div>
	<br>
		<h3 align = "center"><?php echo $month ?> <?php echo $year ?> Exceptional Records</h3>
	<table id="customers" align = "center" > 
  <tr>
    <th>Date</th>
    <th>In-time</th>
    <th>Out-Time</th>
  </tr>
  <?php
  while($row = mysqli_fetch_array($result5))
  { 
	$in = $row['Intime'];
	$out = $row['Outtime'];
	?>
  <tr>
    <td><?php echo $row['dates']; ?></td>
    <td><?php echo $in; ?></td>
    <td><?php echo $out; ?></td>
  </tr>
  <?php } ?>
    <?php 
	while($row = mysqli_fetch_array($result6))
	{
	$in = $row['Intime'];
	$out =$row['Outtime'];
	?>
  <tr>
    <td><?php echo $row['dates']; ?></td>
    <td><?php echo $in; ?></td>
    <td><?php echo $out; ?></td>
  </tr>
  <?php } ?>
</table>
<br>
  </body>
</html>