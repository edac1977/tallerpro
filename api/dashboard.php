<?php
require_once 'db.php';
$db = getDB();

$clientes   = $db->query("SELECT COUNT(*) as n FROM clientes")->fetch_assoc()['n'];
$maquinas   = $db->query("SELECT COUNT(*) as n FROM maquinaria")->fetch_assoc()['n'];
$ordenes    = $db->query("SELECT COUNT(*) as n FROM ordenes WHERE estado NOT IN ('Entregado')")->fetch_assoc()['n'];
$stockBajo  = $db->query("SELECT COUNT(*) as n FROM repuestos WHERE stock <= stock_minimo")->fetch_assoc()['n'];
$mantVenc   = $db->query("SELECT COUNT(*) as n FROM mantenimientos WHERE estado='Programado' AND fecha < CURDATE()")->fetch_assoc()['n'];
$mantHoy    = $db->query("SELECT COUNT(*) as n FROM mantenimientos WHERE estado='Programado' AND fecha = CURDATE()")->fetch_assoc()['n'];

// Facturación — basado en cuentas de cobro (valores con descuentos ya aplicados)
$facPend   = $db->query("SELECT COUNT(*) as n FROM facturas WHERE estado IN ('Emitida','Parcialmente Pagada')")->fetch_assoc()['n'];
$facVenc   = $db->query("SELECT COUNT(*) as n FROM facturas WHERE estado='Vencida'")->fetch_assoc()['n'];
// Por cobrar: saldo pendiente de facturas activas (total real - lo ya pagado)
$totalPend = $db->query("
    SELECT COALESCE(ROUND(SUM(
        f.total - COALESCE((SELECT SUM(monto) FROM pagos WHERE factura_id=f.id),0)
    ),0),0) as v
    FROM facturas f
    WHERE f.estado IN ('Emitida','Parcialmente Pagada','Vencida')
      AND f.estado != 'Anulada'
")->fetch_assoc()['v'];
// Cobrado este mes: pagos recibidos en el mes, solo de facturas no anuladas
$totalMes  = $db->query("
    SELECT COALESCE(SUM(p.monto),0) as v
    FROM pagos p
    INNER JOIN facturas f ON f.id = p.factura_id
    WHERE MONTH(p.fecha)=MONTH(CURDATE()) AND YEAR(p.fecha)=YEAR(CURDATE())
      AND f.estado != 'Anulada'
")->fetch_assoc()['v'];

$ultimasOrdenes = $db->query("SELECT o.id, o.estado, c.empresa as cliente, CONCAT(t.nombre,' ',IFNULL(t.apellido,'')) as tecnico
    FROM ordenes o LEFT JOIN clientes c ON c.id=o.cliente_id LEFT JOIN tecnicos t ON t.id=o.tecnico_id
    ORDER BY o.creado_en DESC LIMIT 5");
$ords = [];
while ($r = $ultimasOrdenes->fetch_assoc()) $ords[] = $r;

$stockCrit = $db->query("SELECT descripcion, stock, stock_minimo FROM repuestos WHERE stock <= stock_minimo ORDER BY stock ASC LIMIT 8");
$stock = [];
while ($r = $stockCrit->fetch_assoc()) $stock[] = $r;

// Compras de repuestos
$comprasRow = $db->query("SELECT COALESCE(SUM(cantidad * precio_unit), 0) as total, COUNT(*) as num FROM compras_repuestos");
$compras    = $comprasRow ? $comprasRow->fetch_assoc() : ['total'=>0,'num'=>0];

// Gastos administrativos este mes
$gastosRow  = $db->query("SELECT COALESCE(SUM(valor),0) as total, COUNT(*) as num FROM gastos_administrativos WHERE MONTH(fecha)=MONTH(CURDATE()) AND YEAR(fecha)=YEAR(CURDATE())");
$gastos     = $gastosRow ? $gastosRow->fetch_assoc() : ['total'=>0,'num'=>0];

echo json_encode([
    'clientes'        => (int)$clientes,
    'maquinas'        => (int)$maquinas,
    'ordenes'         => (int)$ordenes,
    'stock_bajo'      => (int)$stockBajo,
    'mant_vencidos'   => (int)$mantVenc,
    'mant_hoy'        => (int)$mantHoy,
    'fac_pendientes'  => (int)$facPend,
    'fac_vencidas'    => (int)$facVenc,
    'total_pendiente' => (float)$totalPend,
    'cobrado_mes'     => (float)$totalMes,
    'total_compras'   => (float)$compras['total'],
    'num_compras'     => (int)$compras['num'],
    'gastos_mes'      => (float)$gastos['total'],
    'num_gastos_mes'  => (int)$gastos['num'],
    'ultimas_ordenes' => $ords,
    'stock_critico'   => $stock,
]);
$db->close();
