<?php
header('Content-Type: application/json');
require_once 'db.php';
$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$id     = $_GET['id'] ?? null;

// ── GET ──────────────────────────────────────────────────────
if ($method === 'GET') {
    $sql = "SELECT o.*, c.empresa as cliente_nombre, m.nombre as maquina_nombre, m.marca as maquina_marca,
            CONCAT(t.nombre,' ',IFNULL(t.apellido,'')) as tecnico_nombre
            FROM ordenes o
            LEFT JOIN clientes c ON c.id=o.cliente_id
            LEFT JOIN maquinaria m ON m.id=o.maquina_id
            LEFT JOIN tecnicos t ON t.id=o.tecnico_id
            ORDER BY o.creado_en DESC";
    $result = $db->query($sql);
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rRes = $db->query("SELECT * FROM orden_repuestos WHERE orden_id='" . $db->real_escape_string($row['id']) . "'");
        $repuestos = [];
        while ($r = $rRes->fetch_assoc()) $repuestos[] = $r;
        $row['repuestos'] = $repuestos;
        $rows[] = $row;
    }
    echo json_encode($rows);

// ── POST ─────────────────────────────────────────────────────
} elseif ($method === 'POST') {
    $d = json_decode(file_get_contents('php://input'), true);
    $res = $db->query("SELECT MAX(CAST(SUBSTRING(id,4) AS UNSIGNED)) AS max_n FROM ordenes");
    $row = $res->fetch_assoc();
    $n = ($row['max_n'] ?? 0) + 1;
    $newId    = 'OT-' . str_pad($n, 4, '0', STR_PAD_LEFT);
    $horas    = (float)($d['horas']           ?? 0);
    $manoObra = (float)($d['mano_obra']       ?? 0);
    $totalRep = (float)($d['total_repuestos'] ?? 0);
    $total    = $manoObra + $totalRep;
    $stmt = $db->prepare("INSERT INTO ordenes (id,fecha,tipo,cliente_id,maquina_id,tecnico_id,prioridad,falla,diagnostico,trabajos,horas,mano_obra,total_repuestos,total,estado,fecha_entrega,observaciones) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('ssssssssssddddsss', $newId, $d['fecha'], $d['tipo'], $d['cliente_id'], $d['maquina_id'], $d['tecnico_id'], $d['prioridad'], $d['falla'], $d['diagnostico'], $d['trabajos'], $horas, $manoObra, $totalRep, $total, $d['estado'], $d['fecha_entrega'], $d['observaciones']);
    $stmt->execute();
    // Repuestos
    if (!empty($d['repuestos'])) {
        $rStmt = $db->prepare("INSERT INTO orden_repuestos (orden_id,repuesto_id,descripcion,cantidad,precio_unit) VALUES (?,?,?,?,?)");
        foreach ($d['repuestos'] as $r) {
            $qty    = (int)($r['cantidad']   ?? 1);
            $precio = (float)($r['precio_unit'] ?? 0);
            $rStmt->bind_param('sssid', $newId, $r['repuesto_id'], $r['descripcion'], $qty, $precio);
            $rStmt->execute();
            if (!empty($r['repuesto_id'])) {
                $db->query("UPDATE repuestos SET stock = GREATEST(0, stock - $qty) WHERE id='" . $db->real_escape_string($r['repuesto_id']) . "'");
            }
        }
    }
    echo json_encode(['id' => $newId, 'ok' => true]);

// ── PUT (edición completa con repuestos) ──────────────────────
} elseif ($method === 'PUT' && $id) {
    $d        = json_decode(file_get_contents('php://input'), true);
    $horas    = (float)($d['horas']           ?? 0);
    $manoObra = (float)($d['mano_obra']       ?? 0);
    $totalRep = (float)($d['total_repuestos'] ?? 0);
    $total    = $manoObra + $totalRep;

    // 1. Restaurar stock de repuestos anteriores antes de reemplazarlos
    $oldReps = $db->query("SELECT repuesto_id, cantidad FROM orden_repuestos WHERE orden_id='" . $db->real_escape_string($id) . "'");
    while ($or = $oldReps->fetch_assoc()) {
        $qty = (int)$or['cantidad'];
        $db->query("UPDATE repuestos SET stock = stock + $qty WHERE id='" . $db->real_escape_string($or['repuesto_id']) . "'");
    }

    // 2. Actualizar campos de la orden
    $stmt = $db->prepare("UPDATE ordenes SET fecha=?,tipo=?,cliente_id=?,maquina_id=?,tecnico_id=?,prioridad=?,falla=?,diagnostico=?,trabajos=?,horas=?,mano_obra=?,total_repuestos=?,total=?,estado=?,fecha_entrega=?,observaciones=? WHERE id=?");
    if(!$stmt){ echo json_encode(['error'=>$db->error]); exit; }
    $stmt->bind_param('sssssssssddddssss',
        $d['fecha'], $d['tipo'], $d['cliente_id'], $d['maquina_id'],
        $d['tecnico_id'], $d['prioridad'], $d['falla'], $d['diagnostico'],
        $d['trabajos'], $horas, $manoObra, $totalRep, $total,
        $d['estado'], $d['fecha_entrega'], $d['observaciones'], $id
    );
    if(!$stmt->execute()){ echo json_encode(['error'=>$stmt->error]); exit; }

    // 3. Borrar repuestos anteriores e insertar los nuevos
    $db->query("DELETE FROM orden_repuestos WHERE orden_id='" . $db->real_escape_string($id) . "'");
    if (!empty($d['repuestos'])) {
        $rStmt = $db->prepare("INSERT INTO orden_repuestos (orden_id,repuesto_id,descripcion,cantidad,precio_unit) VALUES (?,?,?,?,?)");
        foreach ($d['repuestos'] as $r) {
            $qty    = (int)($r['cantidad']   ?? 1);
            $precio = (float)($r['precio_unit'] ?? 0);
            $rStmt->bind_param('sssid', $id, $r['repuesto_id'], $r['descripcion'], $qty, $precio);
            $rStmt->execute();
            // Descontar stock actualizado
            if (!empty($r['repuesto_id'])) {
                $db->query("UPDATE repuestos SET stock = GREATEST(0, stock - $qty) WHERE id='" . $db->real_escape_string($r['repuesto_id']) . "'");
            }
        }
    }
    echo json_encode(['ok' => true]);

// ── DELETE ────────────────────────────────────────────────────
} elseif ($method === 'DELETE' && $id) {
    // Restaurar stock antes de eliminar
    $oldReps = $db->query("SELECT repuesto_id, cantidad FROM orden_repuestos WHERE orden_id='" . $db->real_escape_string($id) . "'");
    while ($or = $oldReps->fetch_assoc()) {
        $qty = (int)$or['cantidad'];
        $db->query("UPDATE repuestos SET stock = stock + $qty WHERE id='" . $db->real_escape_string($or['repuesto_id']) . "'");
    }
    $db->query("DELETE FROM orden_repuestos WHERE orden_id='" . $db->real_escape_string($id) . "'");
    $stmt = $db->prepare("DELETE FROM ordenes WHERE id=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    echo json_encode(['ok' => true]);
}
$db->close();
