<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Default XAMPP password is empty
$database = 'todo';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
    }