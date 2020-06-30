<?php
 /*
$servername = "localhost";
$username = "root44";
$password = "root44";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
 */

$con = mysqli_connect("localhost","root44","root44","notification");

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
  
?>