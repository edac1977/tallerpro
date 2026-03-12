<?php
header('Content-Type: application/json');
require_once 'db.php';
$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$id     = $_GET['id']     ?? null;
$action = $_GET['action'] ?? null;

// ── GET uno ──────────────────────────────────────────────────
if ($method === 'GET' && $id && !$action) {
    $row = $db->query("SELECT f.*, c.empresa as cliente_nombre,
        COALESCE((SELECT SUM(monto) FROM pagos WHERE factura_id=f.id),0) as pagado,
        COALESCE((SELECT SUM(monto) FROM pagos WHERE factura_id=f.id),0) as total_pagado,
        (f.total - COALESCE((SELECT SUM(monto) FROM pagos WHERE factura_id=f.id),0)) as saldo_pendiente
        FROM facturas f
        LEFT JOIN clientes c ON c.id=f.cliente_id
        WHERE f.id='" . $db->real_escape_string($id) . "'")->fetch_assoc();
    if ($row) {
        $items = $db->query("SELECT * FROM factura_items WHERE factura_id='" . $db->real_escape_string($id) . "' ORDER BY orden ASC");
        $row['items'] = [];
        while ($i = $items->fetch_assoc()) $row['items'][] = $i;
        // Historial de pagos
        $pagosRes = $db->query("SELECT * FROM pagos WHERE factura_id='" . $db->real_escape_string($id) . "' ORDER BY fecha DESC");
        $row['pagos'] = [];
        while ($p = $pagosRes->fetch_assoc()) $row['pagos'][] = $p;
    }
    echo json_encode($row);

// ── GET lista ────────────────────────────────────────────────
} elseif ($method === 'GET' && !$action) {
    $result = $db->query("SELECT f.*, c.empresa as cliente_nombre,
        COALESCE((SELECT SUM(monto) FROM pagos WHERE factura_id=f.id),0) as pagado,
        COALESCE((SELECT SUM(monto) FROM pagos WHERE factura_id=f.id),0) as total_pagado,
        (f.total - COALESCE((SELECT SUM(monto) FROM pagos WHERE factura_id=f.id),0)) as saldo_pendiente
        FROM facturas f LEFT JOIN clientes c ON c.id=f.cliente_id
        ORDER BY f.fecha DESC, f.id DESC");
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    echo json_encode($rows);

// ── POST: nueva factura ───────────────────────────────────────
} elseif ($method === 'POST' && !$action) {
    $d = json_decode(file_get_contents('php://input'), true);
    $res    = $db->query("SELECT MAX(CAST(SUBSTRING(id,5) AS UNSIGNED)) AS n FROM facturas");
    $n      = ($res->fetch_assoc()['n'] ?? 0) + 1;
    $newId  = 'FAC-' . str_pad($n, 4, '0', STR_PAD_LEFT);
    $numero = $d['numero'] ?? ('CC-' . date('Y') . '-' . str_pad($n, 4, '0', STR_PAD_LEFT));
    // Calcular total desde los ítems reales (con descuentos aplicados)
    $total = 0;
    foreach (($d['items'] ?? []) as $item) {
        $total += floatval($item['cantidad'] ?? 1) * floatval($item['precio_unit'] ?? 0);
    }
    $iva = 0; // Cuenta de cobro colombiana sin IVA
    $fv  = $d['fecha_vencimiento'] ?: null;
    $est = $d['estado'] ?? 'Emitida';
    $stmt = $db->prepare("INSERT INTO facturas (id,numero,fecha,fecha_vencimiento,cliente_id,concepto,subtotal,porcentaje_iva,valor_iva,total,estado,notas) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
    if (!$stmt) { echo json_encode(['error'=>$db->error]); exit; }
    $stmt->bind_param('sssssssdddss', $newId, $numero, $d['fecha'], $fv, $d['cliente_id'], $d['concepto'], $total, $iva, $iva, $total, $est, $d['notas']);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }
    // Ítems
    if (!empty($d['items'])) {
        $iStmt = $db->prepare("INSERT INTO factura_items (factura_id,tipo,descripcion,cantidad,precio_unit,subtotal,orden) VALUES (?,?,?,?,?,?,?)");
        foreach ($d['items'] as $i => $item) {
            $qty   = floatval($item['cantidad']   ?? 1);
            $price = floatval($item['precio_unit'] ?? 0);
            $sub   = $qty * $price;
            $iStmt->bind_param('sssdddi', $newId, $item['tipo'], $item['descripcion'], $qty, $price, $sub, $i);
            $iStmt->execute();
        }
    }
    echo json_encode(['id' => $newId, 'numero' => $numero, 'ok' => true]);

// ── POST: registrar pago ──────────────────────────────────────
} elseif ($method === 'POST' && $action === 'pago' && $id) {
    $d     = json_decode(file_get_contents('php://input'), true);
    $monto = floatval($d['monto'] ?? 0);
    if ($monto <= 0) { echo json_encode(['error'=>'Monto inválido']); exit; }
    $stmt = $db->prepare("INSERT INTO pagos (factura_id,fecha,monto,metodo,referencia,notas) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param('ssdsss', $id, $d['fecha'], $monto, $d['metodo'], $d['referencia'], $d['notas']);
    $stmt->execute();
    // Recalcular estado según total real de la factura
    $fRes   = $db->query("SELECT total FROM facturas WHERE id='" . $db->real_escape_string($id) . "'")->fetch_assoc();
    $pRes   = $db->query("SELECT COALESCE(SUM(monto),0) AS pagado FROM pagos WHERE factura_id='" . $db->real_escape_string($id) . "'")->fetch_assoc();
    $total  = floatval($fRes['total']  ?? 0);
    $pagado = floatval($pRes['pagado'] ?? 0);
    if ($pagado >= $total)  $nuevoEst = 'Pagada';
    elseif ($pagado > 0)    $nuevoEst = 'Parcialmente Pagada';
    else                    $nuevoEst = 'Emitida';
    $db->query("UPDATE facturas SET estado='$nuevoEst' WHERE id='" . $db->real_escape_string($id) . "'");
    echo json_encode(['ok' => true, 'estado' => $nuevoEst, 'saldo' => round($total - $pagado, 2)]);

// ── PUT: editar factura ───────────────────────────────────────
} elseif ($method === 'PUT' && $id) {
    $d = json_decode(file_get_contents('php://input'), true);
    // Recalcular total desde ítems reales (con todos los descuentos aplicados)
    $total = 0;
    foreach (($d['items'] ?? []) as $item) {
        $total += floatval($item['cantidad'] ?? 1) * floatval($item['precio_unit'] ?? 0);
    }
    $iva = 0;
    $fv  = $d['fecha_vencimiento'] ?: null;
    $stmt = $db->prepare("UPDATE facturas SET numero=?,fecha=?,fecha_vencimiento=?,cliente_id=?,concepto=?,subtotal=?,porcentaje_iva=?,valor_iva=?,total=?,estado=?,notas=? WHERE id=?");
    if (!$stmt) { echo json_encode(['error'=>$db->error]); exit; }
    $stmt->bind_param('ssssssdddsss', $d['numero'], $d['fecha'], $fv, $d['cliente_id'], $d['concepto'], $total, $iva, $iva, $total, $d['estado'], $d['notas'], $id);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }
    // Reemplazar ítems
    $db->query("DELETE FROM factura_items WHERE factura_id='" . $db->real_escape_string($id) . "'");
    if (!empty($d['items'])) {
        $iStmt = $db->prepare("INSERT INTO factura_items (factura_id,tipo,descripcion,cantidad,precio_unit,subtotal,orden) VALUES (?,?,?,?,?,?,?)");
        foreach ($d['items'] as $i => $item) {
            $qty   = floatval($item['cantidad']   ?? 1);
            $price = floatval($item['precio_unit'] ?? 0);
            $sub   = $qty * $price;
            $iStmt->bind_param('sssdddi', $id, $item['tipo'], $item['descripcion'], $qty, $price, $sub, $i);
            $iStmt->execute();
        }
    }
    echo json_encode(['ok' => true]);

// ── DELETE ────────────────────────────────────────────────────
} elseif ($method === 'DELETE' && $id) {
    $db->query("DELETE FROM factura_items WHERE factura_id='" . $db->real_escape_string($id) . "'");
    $db->query("DELETE FROM pagos WHERE factura_id='"         . $db->real_escape_string($id) . "'");
    $stmt = $db->prepare("DELETE FROM facturas WHERE id=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    echo json_encode(['ok' => true]);
}
$db->close();
