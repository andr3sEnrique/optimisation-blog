<?php
$servername = "localhost";
$username = "root";
$password = "Bou@ke2024";
$dbname = "publications_bonnet";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
