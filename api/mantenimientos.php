<?php
require_once 'db.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if ($method === 'GET') {
    if ($action === 'historial') {
        $sql = "SELECT h.*, m.nombre as maquina_nombre, CONCAT(t.nombre,' ',IFNULL(t.apellido,'')) as tecnico_nombre
                FROM historial_mantenimientos h
                LEFT JOIN maquinaria m ON m.id=h.maquina_id
                LEFT JOIN tecnicos t ON t.id=h.tecnico_id
                ORDER BY h.fecha_ejecucion DESC";
        $result = $db->query($sql);
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
    } else {
        $sql = "SELECT mn.*, m.nombre as maquina_nombre, CONCAT(t.nombre,' ',IFNULL(t.apellido,'')) as tecnico_nombre,
                DATEDIFF(mn.fecha, CURDATE()) as dias_restantes
                FROM mantenimientos mn
                LEFT JOIN maquinaria m ON m.id=mn.maquina_id
                LEFT JOIN tecnicos t ON t.id=mn.tecnico_id
                ORDER BY mn.fecha ASC";
        $result = $db->query($sql);
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
    }

} elseif ($method === 'POST' && $action === 'ejecutar' && $id) {
    $d = json_decode(file_get_contents('php://input'), true);
    $m = $db->query("SELECT * FROM mantenimientos WHERE id='" . $db->real_escape_string($id) . "'")->fetch_assoc();
    if (!$m) { echo json_encode(['error' => 'No encontrado']); exit; }
    // Registrar historial
    $hStmt = $db->prepare("INSERT INTO historial_mantenimientos (mantenimiento_id,maquina_id,tipo,fecha_ejecucion,tecnico_id,horas_reales,resultado,notas) VALUES (?,?,?,?,?,?,?,?)");
    $horas = (float)($d['horas_reales'] ?? 0);
    $hStmt->bind_param('sssssdss', $id, $m['maquina_id'], $m['tipo'], $d['fecha_ejecucion'], $d['tecnico_id'], $horas, $d['resultado'], $d['notas']);
    $hStmt->execute();
    // Reprogramar o completar
    if (!empty($d['proxima_fecha']) && $m['periodo_dias'] > 0) {
        $upStmt = $db->prepare("UPDATE mantenimientos SET fecha=?, estado='Programado' WHERE id=?");
        $upStmt->bind_param('ss', $d['proxima_fecha'], $id);
        $upStmt->execute();
    } else {
        $db->query("UPDATE mantenimientos SET estado='Completado' WHERE id='" . $db->real_escape_string($id) . "'");
    }
    echo json_encode(['ok' => true]);

} elseif ($method === 'POST') {
    $d = json_decode(file_get_contents('php://input'), true);
    $res = $db->query("SELECT MAX(CAST(SUBSTRING(id,5) AS UNSIGNED)) AS max_n FROM mantenimientos");
    $row = $res->fetch_assoc();
    $n = ($row['max_n'] ?? 0) + 1;
    $newId = 'MNT-' . str_pad($n, 4, '0', STR_PAD_LEFT);
    $periodo = (int)($d['periodo_dias'] ?? 0);
    $duracion = (float)($d['duracion_hrs'] ?? 2);
    $stmt = $db->prepare("INSERT INTO mantenimientos (id,maquina_id,tipo,fecha,periodo_dias,tecnico_id,duracion_hrs,prioridad,descripcion,materiales,observaciones,estado) VALUES (?,?,?,?,?,?,?,?,?,?,?,'Programado')");
    if (!$stmt) { echo json_encode(['error' => 'Prepare error: ' . $db->error]); exit; }
    $stmt->bind_param('ssssissdsss', $newId, $d['maquina_id'], $d['tipo'], $d['fecha'], $periodo, $d['tecnico_id'], $duracion, $d['prioridad'], $d['descripcion'], $d['materiales'], $d['observaciones']);
    if (!$stmt->execute()) { echo json_encode(['error' => 'Execute error: ' . $stmt->error]); exit; }
    echo json_encode(['id' => $newId, 'ok' => true]);

} elseif ($method === 'DELETE' && $id) {
    $stmt = $db->prepare("DELETE FROM mantenimientos WHERE id=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    echo json_encode(['ok' => true]);
}
$db->close();
