<?php
require_once 'db.php';
$db = getDB();
$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

if ($method === 'GET') {
    $sql = "SELECT m.*, c.empresa as cliente_nombre FROM maquinaria m LEFT JOIN clientes c ON c.id = m.cliente_id ORDER BY m.nombre ASC";
    $result = $db->query($sql);
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        // Cargar fotos
        $fRes = $db->query("SELECT foto_base64, orden FROM maquinaria_fotos WHERE maquina_id='" . $db->real_escape_string($row['id']) . "' ORDER BY orden ASC");
        $fotos = [];
        while ($f = $fRes->fetch_assoc()) $fotos[] = $f['foto_base64'];
        $row['fotos'] = $fotos;
        $rows[] = $row;
    }
    echo json_encode($rows);

} elseif ($method === 'POST') {
    $d = json_decode(file_get_contents('php://input'), true);
    $res = $db->query("SELECT MAX(CAST(SUBSTRING(id,5) AS UNSIGNED)) AS max_n FROM maquinaria");
    $row = $res->fetch_assoc();
    $n = ($row['max_n'] ?? 0) + 1;
    $newId = 'MAQ-' . str_pad($n, 3, '0', STR_PAD_LEFT);
    $stmt = $db->prepare("INSERT INTO maquinaria (id,nombre,marca,modelo,serie,anio,tipo,cliente_id,potencia,voltaje,horas_uso,ubicacion,estado,notas) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $anio = $d['anio'] ? (int)$d['anio'] : null;
    $horas = $d['horas_uso'] ? (int)$d['horas_uso'] : null;
    $stmt->bind_param('sssssissssiiss', $newId, $d['nombre'], $d['marca'], $d['modelo'], $d['serie'], $anio, $d['tipo'], $d['cliente_id'], $d['potencia'], $d['voltaje'], $horas, $d['ubicacion'], $d['estado'], $d['notas']);
    $stmt->execute();
    // Fotos
    if (!empty($d['fotos'])) {
        $fStmt = $db->prepare("INSERT INTO maquinaria_fotos (maquina_id, foto_base64, orden) VALUES (?,?,?)");
        foreach ($d['fotos'] as $i => $foto) {
            $fStmt->bind_param('ssi', $newId, $foto, $i);
            $fStmt->execute();
        }
    }
    echo json_encode(['id' => $newId, 'ok' => true]);

} elseif ($method === 'PUT' && $id) {
    $d = json_decode(file_get_contents('php://input'), true);
    $anio = $d['anio'] ? (int)$d['anio'] : null;
    $horas = $d['horas_uso'] ? (int)$d['horas_uso'] : null;
    $stmt = $db->prepare("UPDATE maquinaria SET nombre=?,marca=?,modelo=?,serie=?,anio=?,tipo=?,cliente_id=?,potencia=?,voltaje=?,horas_uso=?,ubicacion=?,estado=?,notas=? WHERE id=?");
    $stmt->bind_param('ssssiissssisss', $d['nombre'], $d['marca'], $d['modelo'], $d['serie'], $anio, $d['tipo'], $d['cliente_id'], $d['potencia'], $d['voltaje'], $horas, $d['ubicacion'], $d['estado'], $d['notas'], $id);
    $stmt->execute();
    // Actualizar fotos
    $db->query("DELETE FROM maquinaria_fotos WHERE maquina_id='" . $db->real_escape_string($id) . "'");
    if (!empty($d['fotos'])) {
        $fStmt = $db->prepare("INSERT INTO maquinaria_fotos (maquina_id, foto_base64, orden) VALUES (?,?,?)");
        foreach ($d['fotos'] as $i => $foto) {
            $fStmt->bind_param('ssi', $id, $foto, $i);
            $fStmt->execute();
        }
    }
    echo json_encode(['ok' => true]);

} elseif ($method === 'DELETE' && $id) {
    $db->query("DELETE FROM maquinaria_fotos WHERE maquina_id='" . $db->real_escape_string($id) . "'");
    $stmt = $db->prepare("DELETE FROM maquinaria WHERE id=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    echo json_encode(['ok' => true]);
}
$db->close();
