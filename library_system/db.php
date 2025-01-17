<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'library_system';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
