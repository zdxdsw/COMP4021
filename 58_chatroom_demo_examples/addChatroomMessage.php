<?php
header('Access-Control-Allow-Origin: https://course.cse.ust.hk');
header('Access-Control-Allow-Origin: localhost/addChatroomMessage.php');
header('Content-Type: text/plain; charset=utf-8');
// Open the text file using 'append' mode,
// and add the Query String string at the end of the file
$myfile = fopen("chatroom.txt", "a") or die("Unable to open file!");
fwrite($myfile, $_SERVER['QUERY_STRING'] . "\n");
fclose($myfile);

?>

