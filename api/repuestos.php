<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'db.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$id     = $_GET['id']     ?? null;
$action = $_GET['action'] ?? null;

if ($method === 'GET') {
    if ($action === 'compras') {
        $sql = "SELECT c.*, r.descripcion as repuesto_descripcion
                FROM compras_repuestos c
                LEFT JOIN repuestos r ON r.id = c.repuesto_id
                ORDER BY c.fecha DESC, c.id DESC";
        $result = $db->query($sql);
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
    } else {
        $result = $db->query("SELECT * FROM repuestos ORDER BY descripcion ASC");
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        echo json_encode($rows);
    }

} elseif ($method === 'POST' && $action === 'compra') {
    $d      = json_decode(file_get_contents('php://input'), true);
    $repId  = $d['repuesto_id']   ?? '';
    $cant   = (int)($d['cantidad']   ?? 0);
    $precio = (float)($d['precio_unit'] ?? 0);
    $fecha  = $d['fecha']         ?? date('Y-m-d');
    $prov   = $d['proveedor']     ?? '';
    $obs    = $d['observaciones'] ?? '';
    if (!$repId || $cant < 1) { echo json_encode(['error'=>'Repuesto y cantidad requeridos']); exit; }
    $stmt = $db->prepare("INSERT INTO compras_repuestos (repuesto_id,fecha,proveedor,cantidad,precio_unit,observaciones) VALUES (?,?,?,?,?,?)");
    if (!$stmt) { echo json_encode(['error'=>$db->error]); exit; }
    $stmt->bind_param('sssids', $repId, $fecha, $prov, $cant, $precio, $obs);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }
    $upd = $db->prepare("UPDATE repuestos SET stock = stock + ? WHERE id = ?");
    $upd->bind_param('is', $cant, $repId);
    $upd->execute();
    echo json_encode(['ok'=>true]);

} elseif ($method === 'POST') {
    $d           = json_decode(file_get_contents('php://input'), true);
    $rid         = $d['id']           ?? '';
    $desc        = $d['descripcion']  ?? '';
    $condicion   = $d['condicion']    ?? 'nuevo';
    $categoria   = $d['categoria']    ?? '';
    $marca       = $d['marca']        ?? '';
    $referencia  = $d['referencia']   ?? '';
    $stock       = (int)($d['stock']       ?? 0);
    $minimo      = (int)($d['stock_minimo'] ?? 5);
    $precio      = (float)($d['precio']    ?? 0);
    $unidad      = $d['unidad']       ?? '';
    $compatible  = $d['compatible_con'] ?? '';
    $bodega      = $d['bodega'] ?? '';
    $stmt = $db->prepare("INSERT INTO repuestos (id,descripcion,condicion,categoria,marca,referencia,stock,stock_minimo,precio,unidad,compatible_con,bodega) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
    if (!$stmt) { echo json_encode(['error'=>$db->error]); exit; }
    $stmt->bind_param('ssssssiiidss', $rid, $desc, $condicion, $categoria, $marca, $referencia, $stock, $minimo, $precio, $unidad, $compatible, $bodega);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }
    echo json_encode(['ok'=>true]);

} elseif ($method === 'PUT' && $id) {
    $d          = json_decode(file_get_contents('php://input'), true);
    $desc       = $d['descripcion']   ?? '';
    $condicion  = $d['condicion']     ?? 'nuevo';
    $categoria  = $d['categoria']     ?? '';
    $marca      = $d['marca']         ?? '';
    $referencia = $d['referencia']    ?? '';
    $minimo     = (int)($d['stock_minimo'] ?? 5);
    $precio     = (float)($d['precio']    ?? 0);
    $unidad     = $d['unidad']        ?? '';
    $compatible = $d['compatible_con'] ?? '';
    $bodega     = $d['bodega'] ?? '';
    $stmt = $db->prepare("UPDATE repuestos SET descripcion=?,condicion=?,categoria=?,marca=?,referencia=?,stock_minimo=?,precio=?,unidad=?,compatible_con=?,bodega=? WHERE id=?");
    if (!$stmt) { echo json_encode(['error'=>$db->error]); exit; }
    $stmt->bind_param('ssssssidsss', $desc, $condicion, $categoria, $marca, $referencia, $minimo, $precio, $unidad, $compatible, $bodega, $id);
    if (!$stmt->execute()) { echo json_encode(['error'=>$stmt->error]); exit; }
    echo json_encode(['ok'=>true]);

} elseif ($method === 'DELETE' && $id) {
    $stmt = $db->prepare("DELETE FROM repuestos WHERE id=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    echo json_encode(['ok'=>true]);
}
$db->close();
