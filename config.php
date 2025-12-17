<?php
// config.php

header('Content-Type: application/json');

// Database Credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'abhicares');

// Gemini API Key (recommended via env or secrets.php)
define('GEMINI_API_KEY', 'AIzaSyDbgmYl9303QbMD_npGcbtgLkOG2UM4gj4');

// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $db->set_charset("utf8mb4");
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "reply"  => "Database connection failed."
    ]);
    exit;
}
?>