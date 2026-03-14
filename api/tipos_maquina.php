<?php
header('Content-Type: application/json');
require_once 'db.php';
$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$id     = $_GET['id'] ?? null;

// Auth
$token = '';
if (function_exists('getallheaders')) { $h=getallheaders(); $token=$h['X-Auth-Token']??$h['x-auth-token']??''; }
if (!$token && isset($_SERVER['HTTP_X_AUTH_TOKEN'])) $token=$_SERVER['HTTP_X_AUTH_TOKEN'];
if ($token) {
    $sa=$db->prepare("SELECT u.id,u.rol FROM sesiones s JOIN usuarios u ON u.id=s.usuario_id WHERE s.token=? AND s.expira>NOW() AND u.activo=1 LIMIT 1");
    $sa->bind_param('s',$token); $sa->execute();
    $authUser=$sa->get_result()->fetch_assoc();
} else { $authUser=null; }
if (!$authUser) { http_response_code(401); echo json_encode(['error'=>'Sin autenticacion']); exit; }

// GET lista
if ($method === 'GET' && !$id) {
    $soloActivos = $_GET['activos'] ?? null;
    $where = $soloActivos ? 'WHERE activo=1' : '';
    $result = $db->query("SELECT * FROM tipos_maquina $where ORDER BY nombre ASC");
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    echo json_encode($rows);

// GET uno
} elseif ($method === 'GET' && $id) {
    $stmt = $db->prepare("SELECT * FROM tipos_maquina WHERE id=?");
    $stmt->bind_param('s', $id); $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_assoc());

// POST nuevo
} elseif ($method === 'POST') {
    $d = json_decode(file_get_contents('php://input'), true);
    $res = $db->query("SELECT MAX(CAST(SUBSTRING(id,5) AS UNSIGNED)) AS n FROM tipos_maquina");
    $n = ($res->fetch_assoc()['n'] ?? 0) + 1;
    $newId = 'TM-' . str_pad($n, 3, '0', STR_PAD_LEFT);
    $nombre = $d['nombre'] ?? '';
    $desc   = $d['descripcion'] ?? '';
    $activo = isset($d['activo']) ? (int)$d['activo'] : 1;
    if (!$nombre) { echo json_encode(['error'=>'Nombre requerido']); exit; }
    $stmt = $db->prepare("INSERT INTO tipos_maquina (id,nombre,descripcion,activo) VALUES (?,?,?,?)");
    $stmt->bind_param('sssi', $newId, $nombre, $desc, $activo);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }
    echo json_encode(['id'=>$newId, 'ok'=>true]);

// PUT editar
} elseif ($method === 'PUT' && $id) {
    $d = json_decode(file_get_contents('php://input'), true);
    $nombre = $d['nombre'] ?? '';
    $desc   = $d['descripcion'] ?? '';
    $activo = isset($d['activo']) ? (int)$d['activo'] : 1;
    $stmt = $db->prepare("UPDATE tipos_maquina SET nombre=?,descripcion=?,activo=? WHERE id=?");
    $stmt->bind_param('ssis', $nombre, $desc, $activo, $id);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }
    echo json_encode(['ok'=>true]);

// DELETE
} elseif ($method === 'DELETE' && $id) {
    // Verificar si hay maquinas usando este tipo
    $check = $db->prepare("SELECT COUNT(*) as n FROM maquinaria WHERE tipo=?");
    $check->bind_param('s', $id); $check->execute();
    $row = $check->get_result()->fetch_assoc();
    if ($row['n'] > 0) {
        echo json_encode(['error'=>'No se puede eliminar, hay máquinas con este tipo']); exit;
    }
    $stmt = $db->prepare("DELETE FROM tipos_maquina WHERE id=?");
    $stmt->bind_param('s', $id); $stmt->execute();
    echo json_encode(['ok'=>true]);
}
$db->close();
