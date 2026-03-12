<?php
header('Content-Type: application/json');
require_once 'db.php';
require_once 'auth.php';

// Solo admin/root pueden gestionar usuarios
$user = verificarToken();
if (!$user || !in_array($user['rol'], ['root','admin'])) {
    http_response_code(403);
    echo json_encode(['error'=>'Sin permisos']); exit;
}

$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ── GET uno ──────────────────────────────────────────────────
if ($method === 'GET' && $id > 0) {
    $stmt = $db->prepare("SELECT id,username,nombre,email,rol,activo,permisos,ultimo_acceso FROM usuarios WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_assoc());

// ── GET lista ────────────────────────────────────────────────
} elseif ($method === 'GET') {
    $result = $db->query("SELECT id,username,nombre,email,rol,activo,permisos,ultimo_acceso FROM usuarios ORDER BY rol ASC, nombre ASC");
    $rows = [];
    while ($r = $result->fetch_assoc()) $rows[] = $r;
    echo json_encode($rows);

// ── POST nuevo usuario ────────────────────────────────────────
} elseif ($method === 'POST') {
    $d        = json_decode(file_get_contents('php://input'), true);
    $username = trim($d['username'] ?? '');
    $nombre   = trim($d['nombre']   ?? '');
    $password = $d['password'] ?? '';
    $rol      = $d['rol']      ?? 'operario';
    $email    = $d['email']    ?? '';
    $activo   = (int)($d['activo'] ?? 1);
    $permisos = $d['permisos'] ?? '[]';
    if (!$username || !$nombre || !$password) {
        echo json_encode(['error'=>'Campos requeridos']); exit;
    }
    // Verificar que no exista el username
    $chk = $db->prepare("SELECT id FROM usuarios WHERE username=?");
    $chk->bind_param('s', $username);
    $chk->execute();
    if ($chk->get_result()->fetch_assoc()) {
        echo json_encode(['error'=>'El nombre de usuario ya existe']); exit;
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO usuarios (username,nombre,email,password_hash,rol,activo,permisos) VALUES (?,?,?,?,?,?,?)");
    if (!$stmt) { echo json_encode(['error'=>$db->error]); exit; }
    $stmt->bind_param('sssssds', $username, $nombre, $email, $hash, $rol, $activo, $permisos);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }
    echo json_encode(['ok'=>true,'id'=>$db->insert_id]);

// ── PUT editar usuario ────────────────────────────────────────
} elseif ($method === 'PUT' && $id > 0) {
    // No editar al root
    $chkRoot = $db->prepare("SELECT rol FROM usuarios WHERE id=?");
    $chkRoot->bind_param('i', $id);
    $chkRoot->execute();
    $uRow = $chkRoot->get_result()->fetch_assoc();
    if ($uRow && $uRow['rol'] === 'root') {
        echo json_encode(['error'=>'No se puede editar el usuario root']); exit;
    }
    $d       = json_decode(file_get_contents('php://input'), true);
    $nombre  = trim($d['nombre']  ?? '');
    $email   = $d['email']    ?? '';
    $rol     = $d['rol']      ?? 'operario';
    $activo  = (int)($d['activo'] ?? 1);
    $permisos= $d['permisos'] ?? '[]';
    // Actualizar sin contraseña por defecto
    if (!empty($d['password']) && strlen($d['password']) >= 6) {
        $hash = password_hash($d['password'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE usuarios SET nombre=?,email=?,rol=?,activo=?,permisos=?,password_hash=? WHERE id=?");
        $stmt->bind_param('sssdssi', $nombre, $email, $rol, $activo, $permisos, $hash, $id);
    } else {
        $stmt = $db->prepare("UPDATE usuarios SET nombre=?,email=?,rol=?,activo=?,permisos=? WHERE id=?");
        $stmt->bind_param('sssdsi', $nombre, $email, $rol, $activo, $permisos, $id);
    }
    if (!$stmt) { echo json_encode(['error'=>$db->error]); exit; }
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }
    echo json_encode(['ok'=>true]);

// ── DELETE ────────────────────────────────────────────────────
} elseif ($method === 'DELETE' && $id > 0) {
    // Proteger root y no permitir eliminarse a sí mismo
    $chkRoot = $db->prepare("SELECT rol FROM usuarios WHERE id=?");
    $chkRoot->bind_param('i', $id);
    $chkRoot->execute();
    $uRow = $chkRoot->get_result()->fetch_assoc();
    if ($uRow && $uRow['rol'] === 'root') {
        echo json_encode(['error'=>'No se puede eliminar el usuario root']); exit;
    }
    if ($id === (int)($user['id'] ?? 0)) {
        echo json_encode(['error'=>'No puedes eliminarte a ti mismo']); exit;
    }
    $stmt = $db->prepare("DELETE FROM usuarios WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    echo json_encode(['ok'=>true]);
}
$db->close();
