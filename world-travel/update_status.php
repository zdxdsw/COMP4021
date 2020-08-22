<?php
    $data=$_POST["status"];
    /*$image = explode(";", $image)[1];
    $image = explode(",", $image)[1];
    $image = str_replace(" ", "+", $image);

    $image = base64_decode($image);
    file_put_contents("postercard.jpeg", $image);
    */
    $myfile = fopen("status.txt", "w") or die("Unable to open file!"); // "a" means append, "w" means overwrite
    fwrite($myfile, $data);
    fclose($myfile);
    echo $data;
?>