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

if ($method === 'GET') {

    // Historial de compras
    if ($action === 'compras') {
        $sql = "SELECT c.*, r.descripcion as repuesto_descripcion
                FROM compras_repuestos c
                LEFT JOIN repuestos r ON r.id = c.repuesto_id
                ORDER BY c.fecha DESC, c.id DESC";
        $result = $db->query($sql);
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);

    // Stock por bodega de un repuesto
    } elseif ($action === 'stock_bodegas' && $id) {
        $stmt = $db->prepare("SELECT rb.*, b.nombre as bodega_nombre, b.ubicacion
            FROM repuesto_bodega rb
            JOIN bodegas b ON b.id = rb.bodega_id
            WHERE rb.repuesto_id = ?
            ORDER BY b.nombre ASC");
        $stmt->bind_param('s', $id); $stmt->execute();
        $rows = [];
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);

    // Lista de repuestos con stock total y por bodega
    } else {
        $result = $db->query("SELECT r.*,
            CASE WHEN (SELECT COUNT(*) FROM repuesto_bodega rb WHERE rb.repuesto_id=r.id) > 0 THEN (SELECT SUM(rb.stock) FROM repuesto_bodega rb WHERE rb.repuesto_id=r.id) ELSE r.stock END as stock_total
            FROM repuestos r ORDER BY r.descripcion ASC");
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            // Agregar bodegas con stock
            $bs = $db->prepare("SELECT rb.stock, rb.stock_minimo, b.id as bodega_id, b.nombre as bodega_nombre
                FROM repuesto_bodega rb JOIN bodegas b ON b.id=rb.bodega_id
                WHERE rb.repuesto_id=?");
            $bs->bind_param('s', $row['id']); $bs->execute();
            $bRes = $bs->get_result();
            $row['bodegas'] = [];
            while ($b = $bRes->fetch_assoc()) $row['bodegas'][] = $b;
            $rows[] = $row;
        }
        echo json_encode($rows);
    }

// Registrar compra/entrada a bodega
} elseif ($method === 'POST' && $action === 'compra') {
    $d       = json_decode(file_get_contents('php://input'), true);
    $repId   = $d['repuesto_id']   ?? '';
    $bodId   = $d['bodega_id']     ?? '';
    $cant    = (int)($d['cantidad']    ?? 0);
    $precio  = (float)($d['precio_unit'] ?? 0);
    $fecha   = $d['fecha']         ?? date('Y-m-d');
    $prov    = $d['proveedor']     ?? '';
    $obs     = $d['observaciones'] ?? '';
    if (!$repId || $cant < 1) { echo json_encode(['error'=>'Repuesto y cantidad requeridos']); exit; }

    // Registrar compra
    $stmt = $db->prepare("INSERT INTO compras_repuestos (repuesto_id,fecha,proveedor,cantidad,precio_unit,observaciones) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param('sssids', $repId, $fecha, $prov, $cant, $precio, $obs);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }

    // Actualizar stock general
    $upd = $db->prepare("UPDATE repuestos SET stock = stock + ? WHERE id = ?");
    $upd->bind_param('is', $cant, $repId);
    $upd->execute();

    // Si se especificó bodega, actualizar stock en bodega
    if ($bodId) {
        $check = $db->prepare("SELECT id FROM repuesto_bodega WHERE repuesto_id=? AND bodega_id=?");
        $check->bind_param('ss', $repId, $bodId); $check->execute();
        if ($check->get_result()->fetch_assoc()) {
            $updB = $db->prepare("UPDATE repuesto_bodega SET stock = stock + ? WHERE repuesto_id=? AND bodega_id=?");
            $updB->bind_param('iss', $cant, $repId, $bodId);
            $updB->execute();
        } else {
            $minimo = (int)($d['stock_minimo'] ?? 5);
            $insB = $db->prepare("INSERT INTO repuesto_bodega (repuesto_id,bodega_id,stock,stock_minimo) VALUES (?,?,?,?)");
            $insB->bind_param('ssii', $repId, $bodId, $cant, $minimo);
            $insB->execute();
        }
    }
    echo json_encode(['ok'=>true]);

// Crear repuesto
} elseif ($method === 'POST') {
    $d          = json_decode(file_get_contents('php://input'), true);
    $rid        = $d['id']            ?? '';
    $desc       = $d['descripcion']   ?? '';
    $condicion  = $d['condicion']     ?? 'nuevo';
    $categoria  = $d['categoria']     ?? '';
    $marca      = $d['marca']         ?? '';
    $referencia = $d['referencia']    ?? '';
    $stock      = (int)($d['stock']        ?? 0);
    $minimo     = (int)($d['stock_minimo'] ?? 5);
    $precio     = (float)($d['precio']     ?? 0);
    $unidad     = $d['unidad']        ?? '';
    $compatible = $d['compatible_con'] ?? '';
    $stmt = $db->prepare("INSERT INTO repuestos (id,descripcion,condicion,categoria,marca,referencia,stock,stock_minimo,precio,unidad,compatible_con) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
    if (!$stmt) { echo json_encode(['error'=>$db->error]); exit; }
    $stmt->bind_param('ssssssiidss', $rid, $desc, $condicion, $categoria, $marca, $referencia, $stock, $minimo, $precio, $unidad, $compatible);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }

    // Si se indicó bodega inicial
    $bodId = $d['bodega_id'] ?? '';
    if ($bodId && $stock > 0) {
        $insB = $db->prepare("INSERT INTO repuesto_bodega (repuesto_id,bodega_id,stock,stock_minimo) VALUES (?,?,?,?)");
        $insB->bind_param('ssii', $rid, $bodId, $stock, $minimo);
        $insB->execute();
    }
    echo json_encode(['ok'=>true]);

// Editar repuesto
} elseif ($method === 'PUT' && $id) {
    $d          = json_decode(file_get_contents('php://input'), true);
    $desc       = $d['descripcion']   ?? '';
    $condicion  = $d['condicion']     ?? 'nuevo';
    $categoria  = $d['categoria']     ?? '';
    $marca      = $d['marca']         ?? '';
    $referencia = $d['referencia']    ?? '';
    $minimo     = (int)($d['stock_minimo'] ?? 5);
    $precio     = (float)($d['precio']     ?? 0);
    $unidad     = $d['unidad']        ?? '';
    $compatible = $d['compatible_con'] ?? '';
    $stmt = $db->prepare("UPDATE repuestos SET descripcion=?,condicion=?,categoria=?,marca=?,referencia=?,stock_minimo=?,precio=?,unidad=?,compatible_con=? WHERE id=?");
    if (!$stmt) { echo json_encode(['error'=>$db->error]); exit; }
    $stmt->bind_param('sssssidsss', $desc, $condicion, $categoria, $marca, $referencia, $minimo, $precio, $unidad, $compatible, $id);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }

    // Si se indicó bodega, actualizar o crear entrada en repuesto_bodega
    $bodId = $d['bodega_id'] ?? '';
    if ($bodId) {
        $check = $db->prepare("SELECT id, stock FROM repuesto_bodega WHERE repuesto_id=? AND bodega_id=?");
        $check->bind_param('ss', $id, $bodId); $check->execute();
        $existing = $check->get_result()->fetch_assoc();
        if (!$existing) {
            $stockActual = (int)($d['stock'] ?? 0);
            $insB = $db->prepare("INSERT INTO repuesto_bodega (repuesto_id,bodega_id,stock,stock_minimo) VALUES (?,?,?,?)");
            $insB->bind_param('ssii', $id, $bodId, $stockActual, $minimo);
            $insB->execute();
        }
    }
    echo json_encode(['ok'=>true]);

// Eliminar repuesto
} elseif ($method === 'DELETE' && $id) {
    $db->query("DELETE FROM repuesto_bodega WHERE repuesto_id='" . $db->real_escape_string($id) . "'");
    $stmt = $db->prepare("DELETE FROM repuestos WHERE id=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    echo json_encode(['ok'=>true]);
}
$db->close();
