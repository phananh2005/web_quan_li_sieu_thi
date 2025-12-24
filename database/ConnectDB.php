<?php
$servername = "localhost";
$username   = "root";
$password   = "123456";
$database   = "sieuthi";
$port = 3307;

$conn = mysqli_connect($servername, $username, $password, $database, $port);
if (!$conn) {
    die("Kết nối không thành công: " . mysqli_connect_error());
}
?>