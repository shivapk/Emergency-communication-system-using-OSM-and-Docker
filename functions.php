<?php 
// creates thumbnails of the images.
error_reporting(0);
function createThumbnail($file_name) {
     
    require 'config.php';
    if(preg_match('/[.](jpg)$/', $file_name)) {
        $im = imagecreatefromjpeg($path_to_image_directory.$file_name);
    } 
    else if(preg_match('/[.](JPG)$/', $file_name)) {
        $im = imagecreatefromjpeg($path_to_image_directory.$file_name);
    } else if (preg_match('/[.](GIF)$/', $file_name)) {
        $im = imagecreatefromgif($path_to_image_directory . $file_name);
    } else if (preg_match('/[.](png)$/', $file_name)) {
        $im = imagecreatefrompng($path_to_image_directory . $file_name);
    }
     
    $ox = imagesx($im);
    $oy = imagesy($im);
     
    $nx = $final_width_of_image;
    $ny = floor($oy * ($final_width_of_image / $ox));
     
    $nm = imagecreatetruecolor($nx, $ny);
     
    imagecopyresized($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);
     
    if(!file_exists($path_to_thumbs_directory)) {
      if(!mkdir($path_to_thumbs_directory)) {
           die("There was a problem. Please try again!");
      } 
       }
    imagejpeg($nm, $path_to_thumbs_directory . $file_name);
}
?>