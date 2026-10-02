<?php
// Edit these if your XAMPP MySQL differs.
$pdo = new PDO('mysql:host=localhost;dbname=barangay_service_hub;charset=utf8mb4', 'root', '', [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
