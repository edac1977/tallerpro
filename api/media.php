<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

error_reporting(0);
require_once 'db.php';
$db = getDB();

// Verificar token
$token = '';
if (function_exists('getallheaders')) {
    $h = getallheaders();
    $token = $h['X-Auth-Token'] ?? $h['x-auth-token'] ?? '';
}
if (!$token && isset($_SERVER['HTTP_X_AUTH_TOKEN'])) $token = $_SERVER['HTTP_X_AUTH_TOKEN'];
if ($token) {
    $sa = $db->prepare("SELECT u.id,u.rol FROM sesiones s JOIN usuarios u ON u.id=s.usuario_id WHERE s.token=? AND s.expira>NOW() AND u.activo=1 LIMIT 1");
    $sa->bind_param('s', $token);
    $sa->execute();
    $authUser = $sa->get_result()->fetch_assoc();
} else { $authUser = null; }
if (!$authUser) { http_response_code(401); echo json_encode(['error'=>'Sin autenticacion']); exit; }

$method = $_SERVER['REQUEST_METHOD'];
$tipo   = $_GET['tipo']      ?? '';   // 'orden' o 'mantenimiento'
$refId  = $_GET['ref_id']    ?? '';   // OT-0001 o MNT-0001
$action = $_GET['action']    ?? '';
$mediaId= $_GET['id']        ?? '';

// Directorio base de uploads
$baseDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
$subDir  = $baseDir . $tipo . 's' . DIRECTORY_SEPARATOR . $refId . DIRECTORY_SEPARATOR;

// ── GET: listar archivos de una orden/mantenimiento ──────────
if ($method === 'GET' && $refId) {
    $stmt = $db->prepare("SELECT id,tipo_ref,ref_id,nombre_archivo,ruta,tipo_archivo,tamanio,descripcion,creado_en FROM media_archivos WHERE tipo_ref=? AND ref_id=? ORDER BY creado_en ASC");
    $stmt->bind_param('ss', $tipo, $refId);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    // Normalizar rutas (corregir backslashes de Windows)
    foreach ($rows as &$row) {
        $row['ruta'] = str_replace('\\', '/', $row['ruta']);
    }
    echo json_encode($rows);
    exit;
}

// ── POST: subir archivo ──────────────────────────────────────
if ($method === 'POST') {
    if (empty($_FILES['archivo'])) { echo json_encode(['error'=>'No se recibió archivo']); exit; }

    $file     = $_FILES['archivo'];
    $desc     = $_POST['descripcion'] ?? '';
    $tipoRef  = $_POST['tipo_ref']    ?? $tipo;
    $refIdPost= $_POST['ref_id']      ?? $refId;

    // Validar tipo de archivo
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp','mp4','mov','avi','mkv','webm'];
    if (!in_array($ext, $allowed)) { echo json_encode(['error'=>'Tipo de archivo no permitido']); exit; }

    // Máximo 100MB
    if ($file['size'] > 100 * 1024 * 1024) { echo json_encode(['error'=>'Archivo demasiado grande (máx 100MB)']); exit; }

    // Crear directorio si no existe
    $dir = $baseDir . $tipoRef . 's' . DIRECTORY_SEPARATOR . $refIdPost . DIRECTORY_SEPARATOR;
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    // Nombre único
    $nombreUnico = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
    $rutaFisica  = $dir . $nombreUnico;
    $rutaWeb     = 'uploads/' . $tipoRef . 's/' . $refIdPost . '/' . $nombreUnico;
    $tipoArch    = in_array($ext, ['mp4','mov','avi','mkv','webm']) ? 'video' : 'foto';
    $tamanio     = $file['size'];

    if (!move_uploaded_file($file['tmp_name'], $rutaFisica)) {
        echo json_encode(['error'=>'Error al guardar archivo']); exit;
    }

    $stmt = $db->prepare("INSERT INTO media_archivos (tipo_ref,ref_id,nombre_archivo,ruta,tipo_archivo,tamanio,descripcion) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param('sssssds', $tipoRef, $refIdPost, $file['name'], $rutaWeb, $tipoArch, $tamanio, $desc);
    $stmt->execute();
    $newId = $db->insert_id;

    echo json_encode(['ok'=>true, 'id'=>$newId, 'ruta'=>$rutaWeb, 'tipo_archivo'=>$tipoArch, 'nombre'=>$file['name']]);
    exit;
}

// ── DELETE: eliminar archivo ─────────────────────────────────
if ($method === 'DELETE' && $mediaId) {
    $stmt = $db->prepare("SELECT ruta FROM media_archivos WHERE id=?");
    $stmt->bind_param('i', $mediaId);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        $rutaFisica = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $row['ruta']);
        if (file_exists($rutaFisica)) unlink($rutaFisica);
        $db->query("DELETE FROM media_archivos WHERE id=" . (int)$mediaId);
    }
    echo json_encode(['ok'=>true]);
    exit;
}

echo json_encode(['error'=>'Acción no reconocida']);
$db->close();
