<?php
require_once 'db.php';
$db = getDB();

// Crear tabla si no existe, con tipos por defecto
$db->query("CREATE TABLE IF NOT EXISTS tipos_maquinaria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  orden INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Insertar tipos por defecto si la tabla está vacía
$check = $db->query("SELECT COUNT(*) as total FROM tipos_maquinaria");
$row = $check->fetch_assoc();
if ($row['total'] == 0) {
  $defaults = ['Compresor','Bomba','Motor Eléctrico','Generador','Torno','Fresadora','Grúa / Montacargas','Prensa','Soldadora','Otro'];
  $stmt = $db->prepare("INSERT IGNORE INTO tipos_maquinaria (nombre, orden) VALUES (?, ?)");
  foreach ($defaults as $i => $tipo) {
    $stmt->bind_param('si', $tipo, $i);
    $stmt->execute();
  }
}

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

header('Content-Type: application/json');

if ($method === 'GET') {
  $result = $db->query("SELECT id, nombre FROM tipos_maquinaria ORDER BY orden ASC, nombre ASC");
  $rows = [];
  while ($row = $result->fetch_assoc()) $rows[] = $row;
  echo json_encode($rows);

} elseif ($method === 'POST') {
  $d = json_decode(file_get_contents('php://input'), true);
  $nombre = trim($d['nombre'] ?? '');
  if (!$nombre) { echo json_encode(['ok'=>false,'error'=>'Nombre requerido']); exit; }
  $res = $db->query("SELECT MAX(orden) as max_o FROM tipos_maquinaria");
  $r = $res->fetch_assoc();
  $orden = ($r['max_o'] ?? 0) + 1;
  $stmt = $db->prepare("INSERT INTO tipos_maquinaria (nombre, orden) VALUES (?, ?)");
  $stmt->bind_param('si', $nombre, $orden);
  if ($stmt->execute()) {
    echo json_encode(['ok'=>true,'id'=>$db->insert_id,'nombre'=>$nombre]);
  } else {
    echo json_encode(['ok'=>false,'error'=>'El tipo ya existe']);
  }

} elseif ($method === 'PUT' && $id) {
  $d = json_decode(file_get_contents('php://input'), true);
  $nombre = trim($d['nombre'] ?? '');
  if (!$nombre) { echo json_encode(['ok'=>false,'error'=>'Nombre requerido']); exit; }
  $stmt = $db->prepare("UPDATE tipos_maquinaria SET nombre=? WHERE id=?");
  $stmt->bind_param('si', $nombre, $id);
  $stmt->execute();
  echo json_encode(['ok'=>true]);

} elseif ($method === 'DELETE' && $id) {
  $stmt = $db->prepare("DELETE FROM tipos_maquinaria WHERE id=?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  echo json_encode(['ok'=>true]);
}

$db->close();
