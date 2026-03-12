<?php
require_once 'db.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

if ($method === 'GET') {
    $sql = "SELECT * FROM clientes ORDER BY empresa ASC";
    $result = $db->query($sql);
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    echo json_encode($rows);

} elseif ($method === 'POST') {
    $d = json_decode(file_get_contents('php://input'), true);
    // Auto-generate ID
    $res = $db->query("SELECT MAX(CAST(SUBSTRING(id,5) AS UNSIGNED)) AS max_n FROM clientes");
    $row = $res->fetch_assoc();
    $n = ($row['max_n'] ?? 0) + 1;
    $newId = 'CLI-' . str_pad($n, 3, '0', STR_PAD_LEFT);
    $stmt = $db->prepare("INSERT INTO clientes (id,empresa,rut,contacto,telefono,email,ciudad,rubro,direccion,notas) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('ssssssssss', $newId, $d['empresa'], $d['rut'], $d['contacto'], $d['telefono'], $d['email'], $d['ciudad'], $d['rubro'], $d['direccion'], $d['notas']);
    $stmt->execute();
    echo json_encode(['id' => $newId, 'ok' => true]);

} elseif ($method === 'PUT' && $id) {
    $d = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare("UPDATE clientes SET empresa=?,rut=?,contacto=?,telefono=?,email=?,ciudad=?,rubro=?,direccion=?,notas=? WHERE id=?");
    $stmt->bind_param('ssssssssss', $d['empresa'], $d['rut'], $d['contacto'], $d['telefono'], $d['email'], $d['ciudad'], $d['rubro'], $d['direccion'], $d['notas'], $id);
    $stmt->execute();
    echo json_encode(['ok' => true]);

} elseif ($method === 'DELETE' && $id) {
    $stmt = $db->prepare("DELETE FROM clientes WHERE id=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    echo json_encode(['ok' => true]);
}
$db->close();
