<?php
header('Content-Type: application/json');
error_reporting(0);
require_once 'db.php';
$db = getDB();

// Auth inline
$token = '';
if (function_exists('getallheaders')) { $h=getallheaders(); $token=$h['X-Auth-Token']??$h['x-auth-token']??''; }
if (!$token && isset($_SERVER['HTTP_X_AUTH_TOKEN'])) $token=$_SERVER['HTTP_X_AUTH_TOKEN'];
if ($token) {
    $sa=$db->prepare("SELECT u.id,u.rol FROM sesiones s JOIN usuarios u ON u.id=s.usuario_id WHERE s.token=? AND s.expira>NOW() AND u.activo=1 LIMIT 1");
    $sa->bind_param('s',$token); $sa->execute();
    $authUser=$sa->get_result()->fetch_assoc();
} else { $authUser=null; }
if (!$authUser) { http_response_code(401); echo json_encode(['error'=>'Sin autenticacion']); exit; }

$method = $_SERVER['REQUEST_METHOD'];
$id     = $_GET['id'] ?? null;

// ── GET ──────────────────────────────────────────────────────
if ($method === 'GET') {
    $sql = "SELECT c.*, cl.empresa as cliente_nombre, m.nombre as maquina_nombre
            FROM cotizaciones c
            LEFT JOIN clientes cl ON cl.id=c.cliente_id
            LEFT JOIN maquinaria m ON m.id=c.maquina_id
            ORDER BY c.creado_en DESC";
    $res = $db->query($sql);
    $rows = [];
    while ($row=$res->fetch_assoc()) $rows[]=$row;
    echo json_encode($rows);

// ── POST ─────────────────────────────────────────────────────
} elseif ($method === 'POST') {
    $d = json_decode(file_get_contents('php://input'), true);
    $res = $db->query("SELECT MAX(CAST(SUBSTRING(id,5) AS UNSIGNED)) AS max_n FROM cotizaciones");
    $row = $res->fetch_assoc();
    $n   = ($row['max_n'] ?? 0) + 1;
    $newId = 'COT-' . str_pad($n, 4, '0', STR_PAD_LEFT);
    $total_visita = (float)($d['total_visita'] ?? 0);
    $total_mano   = (float)($d['total_mano']   ?? 0);
    $total_rep    = (float)($d['total_rep']     ?? 0);
    $total        = (float)($d['total']         ?? 0);
    $cliente_id   = $d['cliente_id']   ?? '';
    $maquina_id   = $d['maquina_id']   ?? '';
    $fecha        = $d['fecha']        ?? date('Y-m-d');
    $valida       = $d['valida_hasta'] ?? '';
    $desc         = $d['descripcion']  ?? '';
    $obs          = $d['observaciones']?? '';
    $items        = $d['items']        ?? '{}';
    $stmt = $db->prepare("INSERT INTO cotizaciones (id,cliente_id,maquina_id,fecha,valida_hasta,descripcion,observaciones,items,total_visita,total_mano,total_rep,total,estado) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,'Pendiente')");
    if (!$stmt) { echo json_encode(['error'=>$db->error]); exit; }
    $stmt->bind_param('ssssssssdddd', $newId,$cliente_id,$maquina_id,$fecha,$valida,$desc,$obs,$items,$total_visita,$total_mano,$total_rep,$total);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }
    echo json_encode(['ok'=>true, 'id'=>$newId]);

// ── PUT ──────────────────────────────────────────────────────
} elseif ($method === 'PUT' && $id) {
    $d = json_decode(file_get_contents('php://input'), true);
    // Solo cambio de estado
    if (isset($d['estado']) && count($d)===1) {
        $estado = $d['estado'];
        $stmt = $db->prepare("UPDATE cotizaciones SET estado=? WHERE id=?");
        $stmt->bind_param('ss', $estado, $id);
        $stmt->execute();
        echo json_encode(['ok'=>true]); exit;
    }
    $total_visita = (float)($d['total_visita'] ?? 0);
    $total_mano   = (float)($d['total_mano']   ?? 0);
    $total_rep    = (float)($d['total_rep']     ?? 0);
    $total        = (float)($d['total']         ?? 0);
    $cliente_id   = $d['cliente_id']   ?? '';
    $maquina_id   = $d['maquina_id']   ?? '';
    $fecha        = $d['fecha']        ?? '';
    $valida       = $d['valida_hasta'] ?? '';
    $desc         = $d['descripcion']  ?? '';
    $obs          = $d['observaciones']?? '';
    $items        = $d['items']        ?? '{}';
    $stmt = $db->prepare("UPDATE cotizaciones SET cliente_id=?,maquina_id=?,fecha=?,valida_hasta=?,descripcion=?,observaciones=?,items=?,total_visita=?,total_mano=?,total_rep=?,total=? WHERE id=?");
    if (!$stmt) { echo json_encode(['error'=>$db->error]); exit; }
    $stmt->bind_param('sssssssdddds', $cliente_id,$maquina_id,$fecha,$valida,$desc,$obs,$items,$total_visita,$total_mano,$total_rep,$total,$id);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }
    echo json_encode(['ok'=>true]);

// ── DELETE ────────────────────────────────────────────────────
} elseif ($method === 'DELETE' && $id) {
    $stmt = $db->prepare("DELETE FROM cotizaciones WHERE id=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    echo json_encode(['ok'=>true]);
}
$db->close();
