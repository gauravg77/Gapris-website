<?php 
$host="localhost";
$dbname='authorization';
$username='root';
$password='';
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
