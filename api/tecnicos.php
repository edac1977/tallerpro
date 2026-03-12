<?php
require_once 'db.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

if ($method === 'GET') {
    $result = $db->query("SELECT * FROM tecnicos ORDER BY nombre ASC");
    $rows = [];
    while ($row = $result->fetch_assoc()) $rows[] = $row;
    echo json_encode($rows);

} elseif ($method === 'POST') {
    $d = json_decode(file_get_contents('php://input'), true);
    $res = $db->query("SELECT MAX(CAST(SUBSTRING(id,5) AS UNSIGNED)) AS max_n FROM tecnicos");
    $row = $res->fetch_assoc();
    $n = ($row['max_n'] ?? 0) + 1;
    $newId = 'TEC-' . str_pad($n, 3, '0', STR_PAD_LEFT);
    $stmt = $db->prepare("INSERT INTO tecnicos (id,nombre,apellido,rut,especialidad,telefono,email,estado,nivel,notas) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('ssssssssss', $newId, $d['nombre'], $d['apellido'], $d['rut'], $d['especialidad'], $d['telefono'], $d['email'], $d['estado'], $d['nivel'], $d['notas']);
    $stmt->execute();
    echo json_encode(['id' => $newId, 'ok' => true]);

} elseif ($method === 'PUT' && $id) {
    $d = json_decode(file_get_contents('php://input'), true);
    $stmt = $db->prepare("UPDATE tecnicos SET nombre=?,apellido=?,rut=?,especialidad=?,telefono=?,email=?,estado=?,nivel=?,notas=? WHERE id=?");
    $stmt->bind_param('ssssssssss', $d['nombre'], $d['apellido'], $d['rut'], $d['especialidad'], $d['telefono'], $d['email'], $d['estado'], $d['nivel'], $d['notas'], $id);
    $stmt->execute();
    echo json_encode(['ok' => true]);

} elseif ($method === 'DELETE' && $id) {
    $stmt = $db->prepare("DELETE FROM tecnicos WHERE id=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    echo json_encode(['ok' => true]);
}
$db->close();
