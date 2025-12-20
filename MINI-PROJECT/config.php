<?php

$host = "localhost";
$user ="root";
$password="";
$database="user_db";

$conn = new mysqli($host,$user,$password,$database,4308);

if($conn->connect_error){
    die("Connection failed: ". $conn->connect_error);
}

?>