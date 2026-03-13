<?php
header('Content-Type: application/json');
require_once 'db.php';
$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$id     = $_GET['id']     ?? null;
$action = $_GET['action'] ?? null;

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

// ── GET lista ────────────────────────────────────────────────
if ($method === 'GET' && !$id && !$action) {
    $soloActivas = $_GET['activas'] ?? null;
    $where = $soloActivas ? 'WHERE activa=1' : '';
    $result = $db->query("SELECT * FROM bodegas $where ORDER BY nombre ASC");
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    echo json_encode($rows);

// ── GET uno ──────────────────────────────────────────────────
} elseif ($method === 'GET' && $id) {
    $stmt = $db->prepare("SELECT * FROM bodegas WHERE id=?");
    $stmt->bind_param('s', $id); $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_assoc());

// ── POST: nueva bodega ────────────────────────────────────────
} elseif ($method === 'POST' && !$action) {
    $d = json_decode(file_get_contents('php://input'), true);
    $res = $db->query("SELECT MAX(CAST(SUBSTRING(id,5) AS UNSIGNED)) AS n FROM bodegas");
    $n = ($res->fetch_assoc()['n'] ?? 0) + 1;
    $newId = 'BOD-' . str_pad($n, 3, '0', STR_PAD_LEFT);
    $nombre    = $d['nombre']      ?? '';
    $ubicacion = $d['ubicacion']   ?? '';
    $desc      = $d['descripcion'] ?? '';
    $activa    = isset($d['activa']) ? (int)$d['activa'] : 1;
    $stmt = $db->prepare("INSERT INTO bodegas (id,nombre,ubicacion,descripcion,activa) VALUES (?,?,?,?,?)");
    $stmt->bind_param('ssssi', $newId, $nombre, $ubicacion, $desc, $activa);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }
    echo json_encode(['id'=>$newId, 'ok'=>true]);

// ── PUT: editar bodega ────────────────────────────────────────
} elseif ($method === 'PUT' && $id) {
    $d = json_decode(file_get_contents('php://input'), true);
    $nombre    = $d['nombre']      ?? '';
    $ubicacion = $d['ubicacion']   ?? '';
    $desc      = $d['descripcion'] ?? '';
    $activa    = isset($d['activa']) ? (int)$d['activa'] : 1;
    $stmt = $db->prepare("UPDATE bodegas SET nombre=?,ubicacion=?,descripcion=?,activa=? WHERE id=?");
    $stmt->bind_param('sssss', $nombre, $ubicacion, $desc, $activa, $id);
    // activa is int, fix type
    $stmt = $db->prepare("UPDATE bodegas SET nombre=?,ubicacion=?,descripcion=?,activa=? WHERE id=?");
    $stmt->bind_param('sssss', $nombre, $ubicacion, $desc, $activa, $id);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }
    echo json_encode(['ok'=>true]);

// ── DELETE ────────────────────────────────────────────────────
} elseif ($method === 'DELETE' && $id) {
    // Verificar si tiene stock
    $check = $db->prepare("SELECT SUM(stock) as total FROM repuesto_bodega WHERE bodega_id=?");
    $check->bind_param('s', $id); $check->execute();
    $row = $check->get_result()->fetch_assoc();
    if ($row['total'] > 0) {
        echo json_encode(['error'=>'No se puede eliminar, tiene repuestos con stock']); exit;
    }
    $stmt = $db->prepare("DELETE FROM bodegas WHERE id=?");
    $stmt->bind_param('s', $id); $stmt->execute();
    echo json_encode(['ok'=>true]);

// ── GET stock de bodega ───────────────────────────────────────
} elseif ($method === 'GET' && $action === 'stock' && $id) {
    $result = $db->query("SELECT rb.*, r.descripcion, r.referencia, r.marca, r.precio
        FROM repuesto_bodega rb
        JOIN repuestos r ON r.id = rb.repuesto_id
        WHERE rb.bodega_id = '" . $db->real_escape_string($id) . "'
        ORDER BY r.descripcion ASC");
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    echo json_encode($rows);
}
$db->close();
