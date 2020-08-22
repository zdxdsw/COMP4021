<?php

// Open the text file using 'append' mode,
// and add the Query String string at the end of the file
header('Access-Control-Allow-Origin: null');
header('Content-Type: text/plain; charset=utf-8');
$myfile = fopen("chatroom.txt", "a") or die("Unable to open file!");
fwrite($myfile, $_SERVER['QUERY_STRING'] . "\n");
fclose($myfile);

?>

