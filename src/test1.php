<?php 
session_start();
require_once "db_connect.php";

db();
global $link;
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
  $query = "SELECT distinct dates from report where Employee_name in (SELECT Employee_name from report where dates like '%$year' and dates like '___$m%' and Employee_name = '$name')";
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
$date = '01/12/2017';
$var = 10.35;
?>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']}); 
google.charts.setOnLoadCallback(drawChart); 
function drawChart() { 
    var data = google.visualization.arrayToDataTable([ 
        ['Day', 'Intime',{type: 'string', role: 'tooltip', 'p': {'html': true}} , 'Outtime' , {type: 'string', role: 'tooltip', 'p': {'html': true}}],
		<?php while($row = mysqli_fetch_array($result4)) { 
		$in = $row['Incoming']/10000;
			$out = $row['Outgoing']/10000;
			$in1 = sprintf('%02d:%02d', (int) $in, fmod($in, 1) * 60);
			$out1 = sprintf('%02d:%02d', (int) $out, fmod($out, 1) * 60);
            echo "['".$row["dating"]."', ".$in." ,'<div><p>In-time  : ".$in1."</p></div>' , ".$out." , '<div><p>Out time :  ".$out1." </p></div>'],"; 
		}
		?>
    ]); 
         
    var options = { 
        title: 'In and Out Report', 
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
  </head>
  <body>
    <div id="chart_division" style="width: 2000px; height: 600px"></div>
  </body>
</html>