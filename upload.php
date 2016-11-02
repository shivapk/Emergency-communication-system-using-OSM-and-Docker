<?php
// reads lat long for js file and uploads images and passes to functions.php to generate thumnails.
//makesure you changed php.ini configuration to max allowed size image.
$mylat = $_POST['extra']; //this is the latitude of clicked point
$mylong = $_POST['extra1']; //this is the longitude of clicked point

         // next work is to update to data base...do it by tuesday ...pk can do


require 'config.php';
require 'functions.php';


 if(isset($_FILES['image']) || isset($_FILES['msg'])){
	 
	  $msg = $_POST['msg'];
	$errors= array();
	 if (!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
		 
		 if(empty($msg)){
			 $errors[] = 'Sorry, both the fields cannot be empty. Try again.';
		 }
		
		 $file_name = "Your Text Message";
		 $source = "";
		 $target = "";
		 $targetforthumb = "";
		 $file_ext = "Text message";
		 $file_type = "Text message";
		 $file_size = 10;
		  
	  } //elseif
	  else{
    $file_name = $_FILES['image']['name'];
	 $source = $_FILES['image']['tmp_name']; 
     $target = $path_to_image_directory . $file_name;
	 $targetforthumb = $path_to_thumbs_directory . $file_name;	
            
       
      
      $file_size =$_FILES['image']['size'];
      $file_type=$_FILES['image']['type'];
      $file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));
	  $expensions= array("jpeg","jpg","png","txt","gif","mp4","MP4");
	 
   
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="Sorry, only JPG, PNG ,GIF & TXT files are allowed.";
      }

      if($file_size > 3245800){
         $errors[]='File size must be less than 3 MB';
      }
	  if(file_exists($target)){
		  $errors[]='This file already exists';
	  }
	  } //else
      if(empty($errors)==true){
		  move_uploaded_file($source, $target);
		  if($file_ext== 'Text message'){
			  // only message no image
		  //copy($target, $targetforthumb);
		 
		  mysql_connect("localhost", "root", "")or die("cannot connect to server"); 
          mysql_select_db('testdb')or die("cannot select Database");
		  $sql = "INSERT INTO upload_img (img_name,url,img_type,thumbnail,lat,lng,video,caption) VALUES ('message.png','uploads/fullsized/message.png','textmessage','uploads/thumbs/message.png','$mylat','$mylong','','$msg')";
	      $result = mysql_query($sql);
	  }  
	     else{
        createThumbnail($file_name); 
        mysql_connect("localhost", "root", "")or die("cannot connect to server"); 
         mysql_select_db('testdb')or die("cannot select Database");
		 $sql = "INSERT INTO upload_img (img_name,url,img_type,thumbnail,lat,lng,video,caption) VALUES ('$file_name','$target','$file_type','$targetforthumb','$mylat','$mylong','','$msg')";
	     $result = mysql_query($sql);

		 }
	     echo $file_name;
		 
		 
		 //Image and text data is collected from the database and shown on map
		$sql = "SELECT lat,lng,thumbnail,url,video,caption FROM upload_img order by img_id "; 

$rs = mysql_query($sql);
if (!$rs) { 
    echo "An SQL error occured.\n"; 
    exit; 
} 
$return_arr = array();
//$rows = array(); 
while($r = mysql_fetch_array($rs) ){

    //$rows[] = $r; 
	$row_array['lat'] = $r['lat'];
    $row_array['lng'] = $r['lng'];
    $row_array['thumbnail'] = $r['thumbnail'];
	$row_array['url'] = $r['url'];
    $row_array['video'] = $r['video'];
	 $row_array['caption'] = $r['caption'];

    array_push($return_arr,$row_array);

       
}  //while


$fp = fopen('rows.json', 'w');
  // fwrite($fp, json_encode($rows));
  fwrite($fp, json_encode(array('rows' => $return_arr)));
 
  fclose($fp);
   echo "  Successfully Uploaded";
		
		 
      } //if errors==true
	  
	  
	  
	  
	  
	  else{
         print_r($errors);
      }
	  
   } 
?>
<html>
<body>
<a href="index_makelaternew.html">
<button>Go back to map</button>
</a>
</html>
