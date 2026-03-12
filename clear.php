<?php
if (session_status() === PHP_SESSION_NONE) session_start();
session_unset();
session_destroy();
setcookie('tallerpro_token','',time()-3600,'/');
setcookie('PHPSESSID','',time()-3600,'/');
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8">
<style>
*{margin:0;padding:0;}
body{font-family:sans-serif;background:#0d0f14;color:#e2e8f0;display:flex;align-items:center;justify-content:center;height:100vh;text-align:center;}
.btn{background:#f59e0b;color:#000;padding:12px 28px;border-radius:8px;font-weight:bold;text-decoration:none;font-size:16px;display:inline-block;margin-top:20px;}
</style>
</head>
<body>
<script>
try{localStorage.clear();}catch(e){}
try{sessionStorage.clear();}catch(e){}
</script>
<div>
  <div style="font-size:48px;margin-bottom:16px;">✅</div>
  <div style="font-size:22px;color:#f59e0b;font-weight:bold;">Sesión limpiada</div>
  <br>
  <a class="btn" href="index.php">→ Ir al Login</a>
</div>
</body>
</html>
