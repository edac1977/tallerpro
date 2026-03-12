<?php
/**
 * TallerPRO — Script de configuración inicial
 * Ejecutar UNA SOLA VEZ en: http://localhost/tallerpro/setup_root.php
 * BORRAR este archivo después de usarlo.
 */
require_once 'api/db.php';
$db = getDB();

// Crear tabla usuarios si no existe
$db->query("CREATE TABLE IF NOT EXISTS usuarios (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  username       VARCHAR(50) NOT NULL UNIQUE,
  nombre         VARCHAR(100) NOT NULL,
  email          VARCHAR(100),
  password_hash  VARCHAR(255) NOT NULL,
  rol            ENUM('root','admin','tecnico','operario') DEFAULT 'operario',
  activo         TINYINT(1) DEFAULT 1,
  permisos       JSON,
  ultimo_acceso  DATETIME,
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Insertar usuario root si no existe
$check = $db->query("SELECT id FROM usuarios WHERE username='edinson'")->fetch_assoc();
if (!$check) {
    $hash = password_hash('taller2026', PASSWORD_DEFAULT);
    $perms = json_encode(["dashboard","clientes","maquinaria","repuestos","tecnicos","ordenes","mantenimientos","reportes","facturas","pagos","gastos","usuarios"]);
    $stmt = $db->prepare("INSERT INTO usuarios (username,nombre,email,password_hash,rol,activo,permisos) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param('sssssds',
        ...['edinson','EDINSON ACUÑA AYALA','edac77@gmail.com',$hash,'root',1,$perms]
    );
    $stmt->execute();
    $msg = "✅ Usuario root creado correctamente.";
} else {
    $msg = "ℹ️ El usuario 'edinson' ya existe. No se realizaron cambios.";
}
$db->close();
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>TallerPRO Setup</title>
<style>body{font-family:sans-serif;background:#0d0f14;color:#e2e8f0;display:flex;align-items:center;justify-content:center;min-height:100vh;}
.box{background:#13161e;border:1px solid #252a38;border-radius:12px;padding:40px;max-width:480px;text-align:center;}
h2{color:#f59e0b;font-size:24px;margin-bottom:20px;}
.msg{font-size:16px;margin:16px 0;line-height:1.6;}
.creds{background:#1a1e29;border-radius:8px;padding:16px;margin:20px 0;font-family:monospace;font-size:15px;text-align:left;}
.warn{color:#ef4444;font-size:13px;margin-top:16px;}
a{color:#f59e0b;}
</style></head>
<body><div class="box">
<h2>⚙ TallerPRO Setup</h2>
<div class="msg"><?= htmlspecialchars($msg) ?></div>
<div class="creds">
  <div>👤 Usuario: <strong>edinson</strong></div>
  <div>🔑 Contraseña: <strong>taller2026</strong></div>
</div>
<div class="warn">⚠️ <strong>IMPORTANTE:</strong> Cambia la contraseña después de tu primer ingreso.<br>
Luego <strong>borra este archivo</strong> del servidor: <code>setup_root.php</code></div>
<br><a href="index.html">→ Ir al sistema</a>
</div></body></html>
