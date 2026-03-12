<?php
header('Content-Type: application/json');
require_once 'db.php';
$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ── GET uno ──────────────────────────────────────────────────
if ($method === 'GET' && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM gastos_administrativos WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    echo json_encode($row ?: null);

// ── GET lista ────────────────────────────────────────────────
} elseif ($method === 'GET') {
    $periodo  = $_GET['periodo']  ?? 'mes';
    $categoria= $_GET['categoria']?? '';

    $cond = '1=1';
    if ($periodo === 'mes')  $cond .= " AND MONTH(fecha)=MONTH(CURDATE()) AND YEAR(fecha)=YEAR(CURDATE())";
    if ($periodo === '3m')   $cond .= " AND fecha >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
    if ($periodo === 'anio') $cond .= " AND YEAR(fecha)=YEAR(CURDATE())";

    if ($categoria !== '') {
        $stmt = $db->prepare("SELECT * FROM gastos_administrativos WHERE $cond AND categoria=? ORDER BY fecha DESC, id DESC");
        $stmt->bind_param('s', $categoria);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $db->query("SELECT * FROM gastos_administrativos WHERE $cond ORDER BY fecha DESC, id DESC");
    }
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    echo json_encode($rows);

// ── POST ─────────────────────────────────────────────────────
} elseif ($method === 'POST') {
    $d          = json_decode(file_get_contents('php://input'), true);
    $fecha      = $d['fecha']         ?? date('Y-m-d');
    $categoria  = $d['categoria']     ?? '';
    $concepto   = $d['concepto']      ?? '';
    $proveedor  = $d['proveedor']     ?? '';
    $metodo     = $d['metodo_pago']   ?? 'Efectivo';
    $valor      = (float)($d['valor'] ?? 0);
    $soporte    = $d['soporte']       ?? '';
    $obs        = $d['observaciones'] ?? '';

    $stmt = $db->prepare(
        "INSERT INTO gastos_administrativos (fecha,categoria,concepto,proveedor,metodo_pago,valor,soporte,observaciones)
         VALUES (?,?,?,?,?,?,?,?)"
    );
    if (!$stmt) { echo json_encode(['error' => 'Prepare: '.$db->error]); exit; }
    $stmt->bind_param('sssssdss', $fecha, $categoria, $concepto, $proveedor, $metodo, $valor, $soporte, $obs);
    if (!$stmt->execute()) { echo json_encode(['error' => 'Execute: '.$stmt->error]); exit; }
    echo json_encode(['ok' => true, 'id' => $db->insert_id]);

// ── PUT ──────────────────────────────────────────────────────
} elseif ($method === 'PUT' && $id > 0) {
    $d          = json_decode(file_get_contents('php://input'), true);
    $fecha      = $d['fecha']         ?? date('Y-m-d');
    $categoria  = $d['categoria']     ?? '';
    $concepto   = $d['concepto']      ?? '';
    $proveedor  = $d['proveedor']     ?? '';
    $metodo     = $d['metodo_pago']   ?? 'Efectivo';
    $valor      = (float)($d['valor'] ?? 0);
    $soporte    = $d['soporte']       ?? '';
    $obs        = $d['observaciones'] ?? '';

    $stmt = $db->prepare(
        "UPDATE gastos_administrativos
         SET fecha=?,categoria=?,concepto=?,proveedor=?,metodo_pago=?,valor=?,soporte=?,observaciones=?
         WHERE id=?"
    );
    if (!$stmt) { echo json_encode(['error' => 'Prepare: '.$db->error]); exit; }
    $stmt->bind_param('sssssdssi', $fecha, $categoria, $concepto, $proveedor, $metodo, $valor, $soporte, $obs, $id);
    if (!$stmt->execute()) { echo json_encode(['error' => 'Execute: '.$stmt->error]); exit; }
    echo json_encode(['ok' => true]);

// ── DELETE ───────────────────────────────────────────────────
} elseif ($method === 'DELETE' && $id > 0) {
    $stmt = $db->prepare("DELETE FROM gastos_administrativos WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    echo json_encode(['ok' => true]);

} else {
    echo json_encode(['error' => 'Método no permitido']);
}
$db->close();
