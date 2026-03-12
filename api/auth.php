<?php
header('Content-Type: application/json');
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $_GET['action'] ?? ($input['action'] ?? '');

// Token viene en el header o en el body
function getToken() {
    $token = '';
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        $token = $headers['X-Auth-Token'] ?? $headers['x-auth-token'] ?? '';
    }
    if (!$token && isset($_SERVER['HTTP_X_AUTH_TOKEN'])) {
        $token = $_SERVER['HTTP_X_AUTH_TOKEN'];
    }
    return $token;
}

// Función reutilizable para otros archivos PHP
function verificarToken() {
    $token = getToken();
    if (!$token) return null;
    $db   = getDB();
    $stmt = $db->prepare("SELECT u.id,u.username,u.nombre,u.rol,u.permisos,u.email 
                          FROM sesiones s JOIN usuarios u ON u.id=s.usuario_id 
                          WHERE s.token=? AND s.expira > NOW() AND u.activo=1 LIMIT 1");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $db->close();
    return $user ?: null;
}

// ── CHECK token ───────────────────────────────────────────────
if ($action === 'check') {
    $token = getToken();
    if (!$token) { echo json_encode(['ok'=>false]); exit; }
    $db   = getDB();
    $stmt = $db->prepare("SELECT u.id,u.username,u.nombre,u.rol,u.permisos,u.email 
                          FROM sesiones s JOIN usuarios u ON u.id=s.usuario_id 
                          WHERE s.token=? AND s.expira > NOW() AND u.activo=1 LIMIT 1");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if ($user) {
        $upd = $db->prepare("UPDATE sesiones SET expira=DATE_ADD(NOW(),INTERVAL 8 HOUR) WHERE token=?");
        $upd->bind_param('s',$token); $upd->execute();
        echo json_encode(['ok'=>true,'user'=>$user]);
    } else {
        echo json_encode(['ok'=>false]);
    }
    $db->close(); exit;
}

// ── LOGIN ─────────────────────────────────────────────────────
if ($action === 'login') {
    $username = trim($input['username'] ?? '');
    $password = $input['password'] ?? '';
    if (!$username || !$password) { echo json_encode(['error'=>'Campos requeridos']); exit; }
    $db   = getDB();
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE username=? AND activo=1 LIMIT 1");
    $stmt->bind_param('s', $username); $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if (!$user || !password_verify($password, $user['password_hash'])) {
        echo json_encode(['error'=>'Usuario o contraseña incorrectos']); exit;
    }
    $token = bin2hex(random_bytes(32));
    $ip    = $_SERVER['REMOTE_ADDR'] ?? '';
    $ua    = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $ins   = $db->prepare("INSERT INTO sesiones (token,usuario_id,ip,user_agent,expira) VALUES (?,?,?,?,DATE_ADD(NOW(),INTERVAL 8 HOUR))");
    $ins->bind_param('siss', $token, $user['id'], $ip, $ua); $ins->execute();
    $db->query("UPDATE usuarios SET ultimo_acceso=NOW() WHERE id=".(int)$user['id']);
    $userData = ['id'=>$user['id'],'username'=>$user['username'],'nombre'=>$user['nombre'],
                 'rol'=>$user['rol'],'permisos'=>$user['permisos'],'email'=>$user['email'],'token'=>$token];
    echo json_encode(['ok'=>true,'user'=>$userData]);
    $db->close(); exit;
}

// ── LOGOUT ────────────────────────────────────────────────────
if ($action === 'logout') {
    $token = getToken() ?: ($input['token'] ?? '');
    if ($token) {
        $db   = getDB();
        $stmt = $db->prepare("DELETE FROM sesiones WHERE token=?");
        $stmt->bind_param('s',$token); $stmt->execute();
        $db->close();
    }
    echo json_encode(['ok'=>true]); exit;
}

// Solo ejecutar si es llamado directamente
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    echo json_encode(['error'=>'Acción no reconocida']);
}
