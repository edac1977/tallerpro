<?php
// ============================================================
//  TallerPRO — Configuración de Base de Datos
//  Compatible con XAMPP local y Railway en la nube
// ============================================================

// Railway inyecta estas variables automáticamente
// En local usa los valores de XAMPP
define('DB_HOST', getenv('MYSQLHOST')     ?: 'localhost');
define('DB_USER', getenv('MYSQLUSER')     ?: 'root');
define('DB_PASS', getenv('MYSQLPASSWORD') ?: '');
define('DB_NAME', getenv('MYSQLDATABASE') ?: 'tallerpro');
define('DB_PORT', getenv('MYSQLPORT')     ?: 3306);

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, (int)DB_PORT);
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(['error' => 'Error de conexion: ' . $conn->connect_error]));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }
