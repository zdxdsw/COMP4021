<?php
/*
    function turnY($filename) {
        echo "flipping";
        $back = imagecreatefrompng($filename);
 
        $width = imagesx($back);
        $height = imagesy($back);
 
        $new = imagecreatetruecolor($width, $height);
        //copy a pixels, right to left, left to right
        for($x=0 ;$x<$width; $x++){
            imagecopy($new, $back, $width-$x-1, 0, $x, 0, 1, $height);
        }
        
        //save the new image
        file_put_contents("postcard.png", $new);
        //imagejpeg($new,$filename);
        imagedestroy($back);
        imagedestroy($new);
    
    }
    */

    $image=$_POST["image"];
    $image = explode(";", $image)[1];
    $image = explode(",", $image)[1];
    $image = str_replace(" ", "+", $image);

    
    file_put_contents("postcard.jpeg", $image);
    //imagejpeg($new,$filename);
    
    echo "Done";
?>