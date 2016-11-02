<?php 
//sensor data is collected from the database and shown on map
$db = new PDO('mysql:host=localhost;dbname=testdb', 'root', ''); 
$sql = "UPDATE mytable SET number = (@n := COALESCE(number, @n)) ORDER BY date;"; 
$sql = "SELECT sensor1,sensor2,sensor3,sensor4,sensor5 FROM tblmarker order by id desc limit 1"; 

$rs = $db->query($sql); 
if (!$rs) { 
    echo "An SQL error occured.\n"; 
    exit; 
} 

$rows = array(); 
while($r = $rs->fetch(PDO::FETCH_ASSOC)) { 
    $rows[] = $r; 
    $sensor1[] = $r['sensor1'];
    $sensor2[] = $r['sensor2'];
	$sensor3[] = $r['sensor3'];
	$sensor4[] = $r['sensor4'];
	$sensor5[] = $r['sensor5'];
	
    
} 
//$fp = fopen('markers_td.json', 'w');
  //  fwrite($fp, json_encode($rows));
   //  fclose($fp);
echo json_encode($rows); 

//$output = json_encode(array('kitten' => $rows);
//echo 

$db = NULL; 
?> 