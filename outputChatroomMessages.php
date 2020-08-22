<?php 

// The following line of code reads the file containing all 
// the chatroom messages and 'prints' it out to the browser
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/plain; charset=utf-8');
readfile('chatroom.txt');
?> 
