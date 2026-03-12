<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8">
<meta http-equiv="refresh" content="1;url=http://localhost/tallerpro/">
<style>body{font-family:sans-serif;background:#0d0f14;color:#e2e8f0;display:flex;align-items:center;justify-content:center;min-height:100vh;text-align:center;}
</style>
</head>
<body>
<div>
  <div style="font-size:40px;margin-bottom:16px;">✅</div>
  <div style="font-size:18px;color:#f59e0b;font-weight:bold;">Sesión cerrada</div>
  <div style="margin-top:10px;color:#94a3b8;">Redirigiendo al login...</div>
  <br>
  <a href="http://localhost/tallerpro/" style="color:#f59e0b;">→ Ir al login ahora</a>
</div>
</body>
</html>
