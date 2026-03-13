<?php
require_once 'db.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

// Crear tabla si no existe
$db->query("CREATE TABLE IF NOT EXISTS tipos_maquinaria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

if ($method === 'GET') {
    $result = $db->query("SELECT * FROM tipos_maquinaria ORDER BY nombre ASC");
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode($rows);

} elseif ($method === 'POST') {
    $d = json_decode(file_get_contents('php://input'), true);
    $nombre = trim($d['nombre'] ?? '');
    if ($nombre === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Nombre requerido']);
        exit;
    }
    $stmt = $db->prepare("INSERT INTO tipos_maquinaria (nombre) VALUES (?)");
    $stmt->bind_param('s', $nombre);
    if ($stmt->execute()) {
        echo json_encode(['ok' => true, 'id' => $db->insert_id, 'nombre' => $nombre]);
    } else {
        http_response_code(409);
        echo json_encode(['ok' => false, 'error' => 'Ya existe ese tipo']);
    }

} elseif ($method === 'PUT' && $id) {
    $d = json_decode(file_get_contents('php://input'), true);
    $nombre = trim($d['nombre'] ?? '');
    if ($nombre === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Nombre requerido']);
        exit;
    }
    $stmt = $db->prepare("UPDATE tipos_maquinaria SET nombre=? WHERE id=?");
    $stmt->bind_param('si', $nombre, $id);
    $stmt->execute();
    echo json_encode(['ok' => true]);

} elseif ($method === 'DELETE' && $id) {
    $stmt = $db->prepare("DELETE FROM tipos_maquinaria WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    echo json_encode(['ok' => true]);
}

$db->close();
