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
  while($row = mysqli_fetch_array($result4))
  {
	  echo $row['dating'];
	  echo "-";
	  echo $row['Incoming'];
	  echo "-";
	  echo $row['Outgoing'];
	  echo "----";
  }
 $query = "DELETE FROM graphs";
  mysqli_query($link , $query);
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
          ['Date', 'Intime', 'Outtime'],
         <?php while($row = mysqli_fetch_array($result4))
          {
			$in = (float)$row['Incoming']/10000;
			$out = (float)$row['Outgoing']/10000;
			$in1 = sprintf('%02d:%02d', (int) $in, fmod($in, 1) * 60);
			$out1 = sprintf('%02d:%02d', (int) $out, fmod($out, 1) * 60);
            echo "['".$row["dating"]."', ".$in." , ".$out."],"; 
          }
          ?>
        ]);

        var options = {
          title: 'Company Performance',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="curve_chart" style="width: 900px; height: 500px"></div>
  </body>
</html>