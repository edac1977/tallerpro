<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TallerPRO — Gestión de Taller Industrial</title>
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=IBM+Plex+Sans:wght@300;400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{--bg:#0d0f14;--bg2:#13161e;--bg3:#1a1e29;--border:#252a38;--accent:#f59e0b;--accent2:#3b82f6;--danger:#ef4444;--success:#10b981;--text:#e2e8f0;--text2:#94a3b8;--text3:#64748b;}
  *{margin:0;padding:0;box-sizing:border-box;}
  body{font-family:'IBM Plex Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;}
  .sidebar{width:240px;min-height:100vh;background:var(--bg2);border-right:1px solid var(--border);display:flex;flex-direction:column;position:fixed;left:0;top:0;z-index:100;}
  .sidebar-logo{padding:24px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:12px;}
  .logo-icon{width:38px;height:38px;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:20px;clip-path:polygon(50% 0%,100% 25%,100% 75%,50% 100%,0% 75%,0% 25%);}
  .logo-text{font-family:'Rajdhani',sans-serif;font-weight:700;font-size:20px;letter-spacing:1px;}
  .logo-sub{font-size:10px;color:var(--text3);letter-spacing:2px;text-transform:uppercase;}
  .nav{padding:16px 0;flex:1;}
  .nav-section{padding:8px 20px 4px;font-size:10px;color:var(--text3);letter-spacing:2px;text-transform:uppercase;font-weight:600;display:flex;align-items:center;justify-content:space-between;cursor:pointer;user-select:none;transition:color .15s;}
  .nav-section:hover{color:var(--text2);}
  .nav-section .nav-section-arrow{font-size:10px;transition:transform .2s;margin-right:4px;}
  .nav-section.collapsed .nav-section-arrow{transform:rotate(-90deg);}
  .nav-group{overflow:hidden;transition:max-height .25s ease;}
  .nav-group.collapsed{max-height:0!important;}
  .nav-item{display:flex;align-items:center;gap:10px;padding:10px 20px;cursor:pointer;color:var(--text2);font-size:13.5px;font-weight:500;transition:all .15s;border-left:3px solid transparent;margin:1px 0;}
  .nav-item:hover{color:var(--text);background:var(--bg3);}
  .nav-item.active{color:var(--accent);background:rgba(245,158,11,.08);border-left-color:var(--accent);}
  .nav-item .icon{font-size:16px;width:18px;text-align:center;}
  .nav-badge{margin-left:auto;background:var(--accent);color:#000;font-size:10px;font-weight:700;padding:2px 6px;border-radius:10px;font-family:'IBM Plex Mono',monospace;}
  .nav-badge.red{background:var(--danger);color:#fff;}
  .main{margin-left:240px;flex:1;min-height:100vh;}
  .topbar{height:60px;background:var(--bg2);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 28px;position:sticky;top:0;z-index:50;}
  .topbar-title{font-family:'Rajdhani',sans-serif;font-size:22px;font-weight:700;letter-spacing:1px;}
  .topbar-right{display:flex;align-items:center;gap:10px;}
  .db-status{display:flex;align-items:center;gap:6px;font-size:12px;padding:5px 10px;border-radius:6px;border:1px solid var(--border);background:var(--bg3);}
  .db-dot{width:8px;height:8px;border-radius:50%;background:var(--success);animation:pulse 2s infinite;}
  @keyframes pulse{0%,100%{opacity:1;}50%{opacity:.4;}}
  .btn{padding:8px 16px;border-radius:6px;border:none;cursor:pointer;font-family:'IBM Plex Sans',sans-serif;font-size:13px;font-weight:600;transition:all .15s;display:inline-flex;align-items:center;gap:6px;}
  .btn-primary{background:var(--accent);color:#000;}.btn-primary:hover{background:#fbbf24;transform:translateY(-1px);}
  .btn-secondary{background:var(--bg3);color:var(--text);border:1px solid var(--border);}.btn-secondary:hover{border-color:var(--accent);color:var(--accent);}
  .btn-danger{background:rgba(239,68,68,.15);color:var(--danger);border:1px solid rgba(239,68,68,.3);}.btn-danger:hover{background:rgba(239,68,68,.25);}
  .btn-sm{padding:5px 10px;font-size:12px;}
  .btn-blue{background:rgba(59,130,246,.15);color:var(--accent2);border:1px solid rgba(59,130,246,.3);}
  .btn-green{background:rgba(16,185,129,.15);color:var(--success);border:1px solid rgba(16,185,129,.3);}
  .content{padding:28px;}
  .stats-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:16px;margin-bottom:28px;}
  .stat-card{background:var(--bg2);border:1px solid var(--border);border-radius:10px;padding:20px;position:relative;overflow:hidden;}
  .stat-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;}
  .stat-card.amber::before{background:var(--accent)}.stat-card.blue::before{background:var(--accent2)}.stat-card.green::before{background:var(--success)}.stat-card.red::before{background:var(--danger)}
  .stat-label{font-size:11px;color:var(--text3);text-transform:uppercase;letter-spacing:1.5px;font-weight:600;margin-bottom:8px;}
  .stat-value{font-family:'Rajdhani',sans-serif;font-size:36px;font-weight:700;line-height:1;}
  .stat-card.amber .stat-value{color:var(--accent)}.stat-card.blue .stat-value{color:var(--accent2)}.stat-card.green .stat-value{color:var(--success)}.stat-card.red .stat-value{color:var(--danger)}
  .stat-sub{font-size:12px;color:var(--text3);margin-top:6px;}
  .stat-icon{position:absolute;right:16px;top:16px;font-size:28px;opacity:.15;}
  .section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
  .section-title{font-family:'Rajdhani',sans-serif;font-size:18px;font-weight:700;letter-spacing:.5px;}
  .table-wrap{background:var(--bg2);border:1px solid var(--border);border-radius:10px;overflow:hidden;}
  .search-bar{display:flex;align-items:center;gap:10px;padding:14px 16px;border-bottom:1px solid var(--border);}
  .search-input{flex:1;background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:8px 12px;color:var(--text);font-family:'IBM Plex Sans',sans-serif;font-size:13px;outline:none;transition:border-color .15s;}
  .search-input:focus{border-color:var(--accent);}
  .search-input::placeholder{color:var(--text3);}
  table{width:100%;border-collapse:collapse;}
  th{background:var(--bg3);padding:10px 16px;text-align:left;font-size:11px;color:var(--text3);text-transform:uppercase;letter-spacing:1px;font-weight:600;border-bottom:1px solid var(--border);}
  td{padding:12px 16px;font-size:13px;border-bottom:1px solid var(--border);vertical-align:middle;}
  tr:last-child td{border-bottom:none;}
  tr:hover td{background:rgba(255,255,255,.02);}
  .tag{display:inline-block;padding:3px 8px;border-radius:4px;font-size:11px;font-weight:600;font-family:'IBM Plex Mono',monospace;}
  .tag-green{background:rgba(16,185,129,.15);color:var(--success);border:1px solid rgba(16,185,129,.25);}
  .tag-amber{background:rgba(245,158,11,.15);color:var(--accent);border:1px solid rgba(245,158,11,.25);}
  .tag-red{background:rgba(239,68,68,.15);color:var(--danger);border:1px solid rgba(239,68,68,.25);}
  .tag-blue{background:rgba(59,130,246,.15);color:var(--accent2);border:1px solid rgba(59,130,246,.25);}
  .tag-gray{background:rgba(148,163,184,.1);color:var(--text2);border:1px solid var(--border);}
  .actions{display:flex;gap:6px;}
  .id-code{font-family:'IBM Plex Mono',monospace;font-size:12px;color:var(--text3);}
  .page-hide{display:none;}
  .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:200;align-items:center;justify-content:center;padding:20px;}
  .modal-overlay.open{display:flex;}
  .modal{background:var(--bg2);border:1px solid var(--border);border-radius:12px;width:100%;max-width:620px;max-height:90vh;overflow-y:auto;animation:modalIn .2s ease;}
  @keyframes modalIn{from{opacity:0;transform:scale(.96) translateY(10px)}to{opacity:1;transform:scale(1) translateY(0)}}
  .modal-header{padding:20px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
  .modal-title{font-family:'Rajdhani',sans-serif;font-size:20px;font-weight:700;}
  .modal-close{background:none;border:none;color:var(--text3);cursor:pointer;font-size:22px;line-height:1;padding:4px;}
  .modal-close:hover{color:var(--text);}
  .modal-body{padding:24px;}
  .modal-footer{padding:16px 24px;border-top:1px solid var(--border);display:flex;gap:10px;justify-content:flex-end;}
  .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
  .form-group{display:flex;flex-direction:column;gap:6px;}
  .form-group.span2{grid-column:span 2;}
  label{font-size:12px;color:var(--text2);font-weight:500;text-transform:uppercase;letter-spacing:.8px;}
  input,select,textarea{background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:9px 12px;color:var(--text);font-family:'IBM Plex Sans',sans-serif;font-size:13px;outline:none;transition:border-color .15s;}
  input:focus,select:focus,textarea:focus{border-color:var(--accent);}
  input::placeholder,textarea::placeholder{color:var(--text3);}
  select option{background:var(--bg3);}
  textarea{resize:vertical;min-height:80px;}
  .form-divider{grid-column:span 2;height:1px;background:var(--border);margin:4px 0;}
  .grid2{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;}
  .empty-state{text-align:center;padding:60px 20px;color:var(--text3);}
  .empty-state .empty-icon{font-size:48px;margin-bottom:12px;opacity:.3;}
  .empty-state p{font-size:14px;}
  .avatar{width:32px;height:32px;border-radius:50%;background:var(--accent);display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:#000;}
  .progress-bar{height:5px;background:var(--bg3);border-radius:3px;overflow:hidden;margin-top:4px;}
  .progress-fill{height:100%;border-radius:3px;background:var(--accent);transition:width .4s;}
  .alert-banner{border-radius:8px;padding:14px 18px;margin-bottom:16px;display:flex;align-items:center;gap:12px;font-size:13px;font-weight:500;}
  .alert-banner.danger{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:var(--danger);}
  .alert-banner.warning{background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);color:var(--accent);}
  .mant-card{background:var(--bg2);border:1px solid var(--border);border-radius:10px;padding:16px 20px;margin-bottom:10px;display:flex;align-items:center;gap:16px;transition:border-color .15s;}
  .mant-card:hover{border-color:var(--accent);}
  .mant-card-body{flex:1;}
  .mant-card-title{font-weight:600;font-size:14px;margin-bottom:3px;}
  .mant-card-sub{font-size:12px;color:var(--text3);}
  .mant-card-right{text-align:right;flex-shrink:0;}
  .dias-badge{font-family:'IBM Plex Mono',monospace;font-size:20px;font-weight:700;line-height:1;}
  .tabs{display:flex;gap:4px;margin-bottom:20px;border-bottom:1px solid var(--border);}
  .tab{padding:10px 18px;cursor:pointer;font-size:13px;font-weight:500;color:var(--text3);border-bottom:2px solid transparent;margin-bottom:-1px;transition:all .15s;}
  .tab:hover{color:var(--text);}.tab.active{color:var(--accent);border-bottom-color:var(--accent);}
  .cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:4px;}
  .cal-header{display:grid;grid-template-columns:repeat(7,1fr);gap:4px;margin-bottom:4px;}
  .cal-dow{text-align:center;font-size:11px;color:var(--text3);font-weight:700;text-transform:uppercase;letter-spacing:1px;padding:6px 0;}
  .cal-day{min-height:72px;background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:6px;cursor:pointer;transition:border-color .15s;}
  .cal-day:hover{border-color:var(--accent);}
  .cal-day.today{border-color:var(--accent);background:rgba(245,158,11,.06);}
  .cal-day.other-month{opacity:.35;}
  .cal-day-num{font-size:12px;font-weight:600;color:var(--text2);margin-bottom:4px;}
  .cal-day.today .cal-day-num{color:var(--accent);}
  .cal-event{font-size:10px;padding:2px 5px;border-radius:3px;margin-bottom:2px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;font-weight:600;}
  .cal-event.vencido{background:rgba(239,68,68,.2);color:var(--danger);}
  .cal-event.hoy{background:rgba(245,158,11,.2);color:var(--accent);}
  .cal-event.proximo{background:rgba(59,130,246,.2);color:var(--accent2);}
  .cal-event.programado{background:rgba(16,185,129,.15);color:var(--success);}
  .cal-nav{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;}
  .cal-nav-title{font-family:'Rajdhani',sans-serif;font-size:20px;font-weight:700;letter-spacing:1px;}
  .timeline{position:relative;padding-left:24px;}
  .timeline::before{content:'';position:absolute;left:8px;top:0;bottom:0;width:2px;background:var(--border);}
  .tl-item{position:relative;margin-bottom:20px;}
  .tl-dot{position:absolute;left:-20px;top:4px;width:12px;height:12px;border-radius:50%;border:2px solid var(--accent);background:var(--bg);}
  .tl-content{background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px 16px;font-size:13px;}
  .tl-date{font-size:11px;color:var(--text3);margin-bottom:4px;font-family:'IBM Plex Mono',monospace;}
  /* Report */
  .report-preview{background:#fff;color:#111;border-radius:8px;padding:40px;font-family:'IBM Plex Sans',sans-serif;max-width:700px;margin:0 auto;}
  .report-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:32px;border-bottom:3px solid #f59e0b;padding-bottom:20px;}
  .report-company-name{font-family:'Rajdhani',sans-serif;font-size:26px;font-weight:700;color:#111;}
  .report-badge{background:#f59e0b;color:#000;padding:6px 14px;border-radius:4px;font-weight:700;font-size:12px;text-align:center;}
  .report-section{margin-bottom:24px;}
  .report-section-title{font-size:11px;text-transform:uppercase;letter-spacing:1.5px;color:#f59e0b;font-weight:700;margin-bottom:10px;border-bottom:1px solid #e5e7eb;padding-bottom:6px;}
  .report-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;}
  .report-field{font-size:13px;}.report-field span{color:#666;font-size:12px;display:block;margin-bottom:1px;}
  .report-table{width:100%;border-collapse:collapse;font-size:13px;}
  .report-table th{background:#f3f4f6;padding:8px 12px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:#555;}
  .report-table td{padding:8px 12px;border-bottom:1px solid #e5e7eb;}
  .sign-area{margin-top:40px;display:grid;grid-template-columns:1fr 1fr;gap:40px;}
  .sign-line{border-top:1px solid #aaa;margin-top:40px;padding-top:6px;font-size:12px;color:#555;text-align:center;}
  .report-footer{margin-top:40px;border-top:1px solid #e5e7eb;padding-top:16px;display:flex;justify-content:space-between;font-size:11px;color:#999;}
  .spinner{display:inline-block;width:16px;height:16px;border:2px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin .7s linear infinite;}
  @keyframes spin{to{transform:rotate(360deg)}}
  /* FACTURACIÓN */
  .fac-total-box{background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:14px 18px;display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;}
  .fac-kpi{text-align:center;padding:0 16px;}
  .fac-kpi-val{font-family:'Rajdhani',sans-serif;font-size:28px;font-weight:700;}
  .fac-kpi-label{font-size:11px;color:var(--text3);text-transform:uppercase;letter-spacing:1px;margin-top:2px;}
  .fac-kpi-sep{width:1px;background:var(--border);align-self:stretch;}
  .item-row{display:grid;grid-template-columns:1fr 2fr 80px 100px 80px auto;gap:8px;align-items:center;margin-bottom:8px;}
  .item-row input,  .item-row select{background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:7px 10px;color:var(--text);font-size:12px;outline:none;}
  .item-row input:focus, .item-row select:focus{border-color:var(--accent);}
  .pago-row{display:flex;align-items:center;gap:10px;padding:10px 14px;background:var(--bg3);border:1px solid var(--border);border-radius:8px;margin-bottom:8px;font-size:13px;}
  .pago-row .pago-monto{font-family:'Rajdhani',sans-serif;font-size:18px;font-weight:700;color:var(--success);margin-left:auto;}
  .stat-card{background:var(--bg2);border:1px solid var(--border);border-radius:10px;padding:20px 24px;}
  .stat-val{font-family:'Rajdhani',sans-serif;font-size:36px;font-weight:700;color:var(--accent);}
  .stat-label{font-size:12px;color:var(--text3);text-transform:uppercase;letter-spacing:1px;margin-top:4px;}
  /* Factura imprimible */
  .fac-print{background:#fff;color:#111;padding:44px;font-family:'IBM Plex Sans',sans-serif;max-width:720px;margin:0 auto;border-radius:8px;}
  .fac-print-head{display:flex;justify-content:space-between;border-bottom:3px solid #f59e0b;padding-bottom:20px;margin-bottom:28px;}
  .fac-print-co{font-family:'Rajdhani',sans-serif;font-size:28px;font-weight:700;}
  .fac-print-num{background:#f59e0b;color:#000;padding:8px 16px;border-radius:4px;font-weight:700;font-size:14px;text-align:right;}
  .fac-print-sec{margin-bottom:22px;}
  .fac-print-sec-title{font-size:10px;text-transform:uppercase;letter-spacing:2px;color:#f59e0b;font-weight:700;border-bottom:1px solid #e5e7eb;padding-bottom:5px;margin-bottom:10px;}
  .fac-print-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:13px;}
  .fac-print-field span{color:#666;font-size:11px;display:block;}
  .fac-print-table{width:100%;border-collapse:collapse;font-size:13px;}
  .fac-print-table th{background:#f3f4f6;padding:9px 12px;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.8px;color:#555;}
  .fac-print-table td{padding:9px 12px;border-bottom:1px solid #e5e7eb;}
  .fac-print-totals{margin-top:12px;display:flex;flex-direction:column;align-items:flex-end;gap:4px;font-size:13px;}
  .fac-print-total-final{font-size:18px;font-weight:700;color:#111;border-top:2px solid #111;padding-top:8px;margin-top:4px;}
  .fac-print-pagos{margin-top:20px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:6px;padding:14px;}
  .fac-print-footer{margin-top:40px;border-top:1px solid #e5e7eb;padding-top:14px;display:flex;justify-content:space-between;font-size:11px;color:#999;}
  /* ── LOGIN ── */
  .login-screen{display:none;position:fixed;inset:0;background:var(--bg);z-index:9999;align-items:center;justify-content:center;flex-direction:column;}
  .login-screen.visible{display:flex;}
  .login-box{background:var(--bg2);border:1px solid var(--border);border-radius:16px;padding:48px 44px;width:100%;max-width:420px;box-shadow:0 24px 60px rgba(0,0,0,.6);}
  .login-logo{display:flex;align-items:center;gap:14px;margin-bottom:36px;justify-content:center;}
  .login-logo-icon{width:52px;height:52px;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:26px;clip-path:polygon(50% 0%,100% 25%,100% 75%,50% 100%,0% 75%,0% 25%);}
  .login-title{font-family:'Rajdhani',sans-serif;font-size:28px;font-weight:700;letter-spacing:1px;}
  .login-sub{font-size:11px;color:var(--text3);letter-spacing:2px;text-transform:uppercase;}
  .login-field{margin-bottom:18px;}
  .login-field label{display:block;font-size:12px;color:var(--text2);font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-bottom:6px;}
  .login-field input{width:100%;background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px 16px;color:var(--text);font-size:14px;outline:none;transition:border-color .2s;}
  .login-field input:focus{border-color:var(--accent);}
  .login-btn{width:100%;background:var(--accent);color:#000;border:none;border-radius:8px;padding:14px;font-family:'Rajdhani',sans-serif;font-size:18px;font-weight:700;letter-spacing:1px;cursor:pointer;transition:opacity .2s;margin-top:8px;}
  .login-btn:hover{opacity:.88;}
  .login-error{background:rgba(239,68,68,.1);border:1px solid var(--danger);color:var(--danger);border-radius:8px;padding:10px 14px;font-size:13px;margin-bottom:16px;display:none;}
  .login-error.visible{display:block;}
  /* ── COTIZACIONES ── */
  .cot-estado{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;letter-spacing:.5px;}
  .cot-estado.pendiente{background:rgba(255,160,0,.15);color:#ffa000;border:1px solid rgba(255,160,0,.3);}
  .cot-estado.aprobada{background:rgba(0,200,120,.15);color:#00c878;border:1px solid rgba(0,200,120,.3);}
  .cot-estado.rechazada{background:rgba(255,80,80,.15);color:#ff5050;border:1px solid rgba(255,80,80,.3);}
  .cot-item-row{display:grid;grid-template-columns:1fr 80px 100px 100px 30px;gap:8px;align-items:center;margin-bottom:6px;}
  .cot-item-row input{background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:6px 10px;color:var(--text);font-size:13px;width:100%;}
  .cot-total-box{background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:14px 18px;margin-top:12px;}
  .cot-total-row{display:flex;justify-content:space-between;font-size:13px;padding:3px 0;color:var(--text2);}
  .cot-total-row.final{font-size:16px;font-weight:700;color:var(--text);border-top:1px solid var(--border);margin-top:6px;padding-top:8px;}

  /* Print cotización */
  @media print{
    .no-print-cot{display:none!important;}
    #cot-print-area{display:block!important;}
  }
  #cot-print-area{display:none;}
  .media-btn{background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:5px 10px;color:var(--text2);cursor:pointer;font-size:12px;display:inline-flex;align-items:center;gap:5px;}
  .media-btn:hover{border-color:var(--accent);color:var(--accent);}
  .media-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:8px;margin-top:8px;}
  .media-thumb{position:relative;border-radius:6px;overflow:hidden;aspect-ratio:1;background:var(--bg2);border:1px solid var(--border);cursor:pointer;}
  .media-thumb img{width:100%;height:100%;object-fit:cover;}
  .media-thumb video{width:100%;height:100%;object-fit:cover;}
  .media-thumb:hover .media-del{opacity:1;}
  .media-del{position:absolute;top:3px;right:3px;background:rgba(0,0,0,.7);border:none;color:#fff;border-radius:4px;width:20px;height:20px;cursor:pointer;font-size:11px;opacity:0;transition:opacity .15s;display:flex;align-items:center;justify-content:center;}
  .media-video-icon{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:24px;pointer-events:none;}
  .media-lightbox{position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:9999;display:flex;align-items:center;justify-content:center;flex-direction:column;}
  .media-lightbox img{max-width:90vw;max-height:85vh;border-radius:8px;}
  .media-lightbox video{max-width:90vw;max-height:85vh;border-radius:8px;}
  .media-lightbox-close{position:absolute;top:20px;right:24px;color:#fff;font-size:28px;cursor:pointer;background:none;border:none;}
  .media-lightbox-desc{color:#ccc;font-size:13px;margin-top:10px;}
  .media-upload-area{border:2px dashed var(--border);border-radius:8px;padding:16px;text-align:center;cursor:pointer;margin-top:8px;color:var(--text3);font-size:13px;transition:border-color .15s;}
  .media-upload-area:hover{border-color:var(--accent);color:var(--accent);}
  @media print{ .media-btn,.media-upload-area,.media-del{display:none!important;} }
  .hdv-selector{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;}
  .hdv-select{background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:10px 14px;color:var(--text);font-size:13px;outline:none;width:100%;}
  .hdv-select:focus{border-color:var(--accent);}
  .hdv-header{background:var(--bg2);border:1px solid var(--border);border-radius:12px;padding:24px;margin-bottom:20px;display:grid;grid-template-columns:120px 1fr auto;gap:20px;align-items:center;}
  .hdv-foto{width:110px;height:110px;border-radius:10px;object-fit:cover;border:2px solid var(--border);}
  .hdv-foto-placeholder{width:110px;height:110px;border-radius:10px;background:var(--bg3);border:2px dashed var(--border);display:flex;align-items:center;justify-content:center;font-size:36px;}
  .hdv-info-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:12px;}
  .hdv-info-item{background:var(--bg3);border-radius:6px;padding:8px 12px;}
  .hdv-info-label{font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:.8px;}
  .hdv-info-val{font-size:13px;font-weight:600;color:var(--text);margin-top:2px;}
  .hdv-stats{display:flex;flex-direction:column;gap:8px;min-width:140px;}
  .hdv-stat-box{background:var(--bg3);border-radius:8px;padding:10px 16px;text-align:center;}
  .hdv-stat-num{font-size:22px;font-weight:700;color:var(--accent);}
  .hdv-stat-lbl{font-size:11px;color:var(--text3);}
  .hdv-timeline{position:relative;padding-left:24px;}
  .hdv-timeline::before{content:'';position:absolute;left:8px;top:0;bottom:0;width:2px;background:var(--border);}
  .hdv-tl-item{position:relative;margin-bottom:16px;}
  .hdv-tl-dot{position:absolute;left:-20px;top:6px;width:12px;height:12px;border-radius:50%;border:2px solid var(--bg2);}
  .hdv-tl-card{background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:14px 16px;}
  .hdv-tl-card:hover{border-color:var(--accent);transition:border-color .15s;}
  .hdv-tl-fecha{font-size:11px;color:var(--text3);margin-bottom:4px;}
  .hdv-tl-titulo{font-size:13px;font-weight:600;color:var(--text);margin-bottom:6px;}
  .hdv-tl-detalle{font-size:12px;color:var(--text2);}
  .hdv-section-title{font-size:14px;font-weight:700;color:var(--accent);margin:20px 0 12px;display:flex;align-items:center;gap:8px;font-family:'Rajdhani',sans-serif;letter-spacing:.5px;}
  .hdv-empty{text-align:center;padding:40px;color:var(--text3);font-size:13px;}
  .hdv-rep-tag{display:inline-block;background:var(--bg2);border:1px solid var(--border);border-radius:4px;padding:2px 8px;font-size:11px;margin:2px;color:var(--text2);}
  @media print{
    .sidebar,.topbar,.section-header,.hdv-selector,
    .hdv-section-title.no-print, .hdv-mant-section{ display:none!important; }
    body{background:#fff!important;color:#000!important;}
    #page-hoja-vida{display:block!important;padding:0!important;}
    .hdv-header{border:1px solid #ccc;background:#f9f9f9!important;color:#000!important;page-break-inside:avoid;}
    .hdv-info-item{background:#eee!important;border:1px solid #ccc!important;}
    .hdv-info-label{color:#555!important;}
    .hdv-info-val{color:#000!important;}
    .hdv-stat-box{background:#eee!important;border:1px solid #ccc!important;}
    .hdv-stat-num{color:#000!important;}
    .hdv-stat-lbl{color:#555!important;}
    .hdv-tl-card{background:#f5f5f5!important;border:1px solid #ccc!important;page-break-inside:avoid;}
    .hdv-tl-fecha,.hdv-tl-titulo,.hdv-tl-detalle{color:#000!important;}
    .hdv-timeline::before{background:#ccc!important;}
    .hdv-tl-dot{border-color:#fff!important;}
    .print-header{display:block!important;}
    .hdv-section-title{color:#333!important;border-bottom:1px solid #ccc;padding-bottom:4px;}
    .hdv-notas-section{background:#f5f5f5!important;border:1px solid #ccc!important;color:#000!important;}
  }
  .print-header{display:none;}
  .perm-modulo{background:var(--bg3);border:1px solid var(--border);border-radius:8px;margin-bottom:8px;overflow:hidden;}
  .perm-modulo-header{display:flex;align-items:center;justify-content:space-between;padding:10px 14px;cursor:pointer;user-select:none;}
  .perm-modulo-title{font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px;}
  .perm-acciones{display:flex;gap:6px;padding:0 14px 12px;flex-wrap:wrap;}
  .perm-accion{display:flex;align-items:center;gap:6px;background:var(--bg2);border:1px solid var(--border);border-radius:6px;padding:6px 12px;font-size:12px;cursor:pointer;transition:all .15s;white-space:nowrap;}
  .perm-accion:hover{border-color:var(--accent);}
  .perm-accion input[type=checkbox]{accent-color:var(--accent);width:14px;height:14px;cursor:pointer;}
  .perm-accion.consultar{border-left:3px solid var(--accent2);}
  .perm-accion.crear{border-left:3px solid var(--success);}
  .perm-accion.editar{border-left:3px solid var(--accent);}
  .perm-accion.eliminar{border-left:3px solid var(--danger);}
  .perm-modulo.tiene-permisos .perm-modulo-header{background:rgba(245,158,11,.06);}
  .perm-quick{display:flex;gap:6px;margin-bottom:12px;flex-wrap:wrap;}
  .perm-quick button{font-size:11px;padding:4px 10px;}
  /* ── TOPBAR USER ── */
  .user-chip{display:flex;align-items:center;gap:8px;background:var(--bg3);border:1px solid var(--border);border-radius:20px;padding:5px 14px 5px 8px;cursor:pointer;font-size:13px;transition:border-color .2s;}
  .user-chip:hover{border-color:var(--accent);}
  .user-avatar{width:28px;height:28px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:#000;}
  @media print{.sidebar,.topbar,.actions,.btn,.modal-overlay,.no-print,.hdv-mant-section{display:none!important;}.main{margin-left:0;} .fac-print{box-shadow:none!important;border-radius:0!important;padding:20px 30px!important;}}
</style>
</head>
<body>

<!-- ===== PANTALLA DE LOGIN ===== -->
<div class="login-screen" id="login-screen">
  <div class="login-box">
    <div class="login-logo">
      <div class="login-logo-icon">⚙</div>
      <div><div class="login-title">TallerPRO</div><div class="login-sub">Industrial MMS</div></div>
    </div>
    <div class="login-error" id="login-error">Usuario o contraseña incorrectos</div>
    <div class="login-field"><label>Usuario</label><input id="login-user" type="text" placeholder="Ingresa tu usuario" autocomplete="username" onkeydown="if(event.key==='Enter')document.getElementById('login-pass').focus()"></div>
    <div class="login-field"><label>Contraseña</label><input id="login-pass" type="password" placeholder="••••••••" autocomplete="current-password" onkeydown="if(event.key==='Enter')doLogin()"></div>
    <button class="login-btn" onclick="doLogin()">INGRESAR</button>
    <div style="text-align:center;margin-top:20px;font-size:11px;color:var(--text3);">TallerPRO © 2026 · Neiva, Huila</div>
  </div>
</div>

<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">⚙</div>
    <div><div class="logo-text">TallerPRO</div><div class="logo-sub">Industrial MMS</div></div>
  </div>
  <nav class="nav">
    <div class="nav-section" onclick="toggleNavGroup('group-principal')">Principal <span class="nav-section-arrow">▾</span></div>
    <div class="nav-group" id="group-principal">
      <div class="nav-item active" onclick="showPage('dashboard')"><span class="icon">📊</span> Dashboard</div>
    </div>
    <div class="nav-section" onclick="toggleNavGroup('group-gestion')">Gestión <span class="nav-section-arrow">▾</span></div>
    <div class="nav-group" id="group-gestion">
      <div class="nav-item" onclick="showPage('clientes')"><span class="icon">👥</span> Clientes</div>
      <div class="nav-item" onclick="showPage('maquinaria')"><span class="icon">⚙️</span> Maquinaria</div>
      <div class="nav-item" onclick="showPage('repuestos')"><span class="icon">🔩</span> Repuestos</div>
      <div class="nav-item" onclick="showPage('bodegas')"><span class="icon">🏭</span> Bodegas</div>
      <div class="nav-item" onclick="showPage('tecnicos')"><span class="icon">👷</span> Técnicos</div>
    </div>
    <div class="nav-section" onclick="toggleNavGroup('group-servicio')">Servicio <span class="nav-section-arrow">▾</span></div>
    <div class="nav-group" id="group-servicio">
      <div class="nav-item" onclick="showPage('ordenes')"><span class="icon">📋</span> Órdenes de Servicio <span class="nav-badge" id="badge-ordenes">0</span></div>
      <div class="nav-item" onclick="showPage('mantenimientos')"><span class="icon">🗓️</span> Mantenimientos <span class="nav-badge red" id="badge-mant" style="display:none">!</span></div>
      <div class="nav-item" onclick="showPage('hoja-vida')"><span class="icon">📖</span> Hoja de Vida</div>
      <div class="nav-item" onclick="showPage('reportes')"><span class="icon">📄</span> Reportes</div>
    </div>
    <div class="nav-section" onclick="toggleNavGroup('group-finanzas')">Finanzas <span class="nav-section-arrow">▾</span></div>
    <div class="nav-group" id="group-finanzas">
      <div class="nav-item" onclick="showPage('facturas')"><span class="icon">🧾</span> Cuenta de Cobro <span class="nav-badge red" id="badge-fac" style="display:none">!</span></div>
      <div class="nav-item" onclick="showPage('cotizaciones')"><span class="icon">📝</span> Cotizaciones <span class="nav-badge" id="badge-cot" style="display:none">0</span></div>
      <div class="nav-item" onclick="showPage('pagos')"><span class="icon">💰</span> Pagos</div>
      <div class="nav-item" onclick="showPage('gastos')"><span class="icon">💸</span> Gastos Administrativos</div>
    </div>
    <div class="nav-section" id="nav-section-admin" style="display:none;" onclick="toggleNavGroup('group-admin')">Administración <span class="nav-section-arrow">▾</span></div>
    <div class="nav-group" id="group-admin">
      <div class="nav-item" id="nav-usuarios" style="display:none;" onclick="showPage('usuarios')"><span class="icon">👤</span> Usuarios y Permisos</div>
    </div>
  </nav>
</aside>

<main class="main">
  <div class="topbar">
    <div class="topbar-title" id="page-title">Dashboard</div>
    <div class="topbar-right">
      <div class="db-status"><div class="db-dot" id="db-dot"></div><span id="db-status-text" style="font-size:12px;color:var(--text2);">MySQL</span></div>
      <span style="font-size:12px;color:var(--text3);font-family:'IBM Plex Mono',monospace;" id="current-date"></span>
      <div class="user-chip" onclick="cerrarSesion()" title="Cerrar sesión">
        <div class="user-avatar" id="topbar-avatar">?</div>
        <span id="topbar-username" style="font-weight:600;">—</span>
        <span style="color:var(--text3);font-size:11px;">· Salir</span>
      </div>
    </div>
  </div>
  <div class="content">

    <!-- DASHBOARD -->
    <div id="page-dashboard">
      <div class="stats-grid">
        <div class="stat-card amber"><div class="stat-icon">👥</div><div class="stat-label">Clientes</div><div class="stat-value" id="d-clientes">—</div><div class="stat-sub">Registrados</div></div>
        <div class="stat-card blue"><div class="stat-icon">⚙️</div><div class="stat-label">Maquinaria</div><div class="stat-value" id="d-maquinas">—</div><div class="stat-sub">Equipos</div></div>
        <div class="stat-card green"><div class="stat-icon">✅</div><div class="stat-label">Órdenes Activas</div><div class="stat-value" id="d-ordenes">—</div><div class="stat-sub">En proceso</div></div>
        <div class="stat-card red"><div class="stat-icon">🔩</div><div class="stat-label">Stock Bajo</div><div class="stat-value" id="d-stock">—</div><div class="stat-sub">Críticos</div></div>
        <div class="stat-card red"><div class="stat-icon">🗓️</div><div class="stat-label">Mant. Vencidos</div><div class="stat-value" id="d-mant">—</div><div class="stat-sub">Sin atender</div></div>
        <div class="stat-card green"><div class="stat-icon">💰</div><div class="stat-label">Cobrado este mes</div><div class="stat-value" id="d-cobrado" style="font-size:24px;">—</div><div class="stat-sub">En pagos recibidos</div></div>
        <div class="stat-card amber"><div class="stat-icon">🧾</div><div class="stat-label">Por Cobrar</div><div class="stat-value" id="d-pendiente" style="font-size:24px;">—</div><div class="stat-sub">Cuentas pendientes</div></div>
        <div class="stat-card red"><div class="stat-icon">📦</div><div class="stat-label">Total en Compras</div><div class="stat-value" id="d-compras" style="font-size:22px;">—</div><div class="stat-sub" id="d-compras-sub">Órdenes de compra</div></div>
        <div class="stat-card red"><div class="stat-icon">💸</div><div class="stat-label">Gastos Admin. (mes)</div><div class="stat-value" id="d-gastos" style="font-size:22px;">—</div><div class="stat-sub" id="d-gastos-sub">Este mes</div></div>
      </div>
      <div class="grid2">
        <div>
          <div class="section-header"><span class="section-title">Últimas Órdenes</span></div>
          <div class="table-wrap"><table><thead><tr><th>ID</th><th>Cliente</th><th>Estado</th><th>Técnico</th></tr></thead><tbody id="dash-ordenes"></tbody></table></div>
        </div>
        <div>
          <div class="section-header"><span class="section-title">Stock Crítico</span></div>
          <div class="table-wrap"><table><thead><tr><th>Repuesto</th><th>Stock</th><th>Estado</th></tr></thead><tbody id="dash-stock"></tbody></table></div>
        </div>
      </div>
    </div>

    <!-- CLIENTES -->
    <div id="page-clientes" class="page-hide">
      <div class="section-header"><span class="section-title">Clientes</span><button class="btn btn-primary" id="btn-nuevo-cliente" onclick="openModal('modal-cliente')">＋ Nuevo Cliente</button></div>
      <div class="table-wrap">
        <div class="search-bar"><input class="search-input" placeholder="🔍 Buscar cliente..." oninput="filterTable(this,'tb-clientes')"></div>
        <table><thead><tr><th>ID</th><th>Empresa</th><th>RUT/NIT</th><th>Contacto</th><th>Teléfono</th><th>Ciudad</th><th>Acciones</th></tr></thead><tbody id="tb-clientes"></tbody></table>
      </div>
    </div>

    <!-- MAQUINARIA -->
    <div id="page-maquinaria" class="page-hide">
      <div class="section-header"><span class="section-title">Maquinaria</span><button class="btn btn-primary" id="btn-nueva-maquina" onclick="openModal('modal-maquina')">＋ Nueva Máquina</button></div>
      <div class="table-wrap">
        <div class="search-bar"><input class="search-input" placeholder="🔍 Buscar maquinaria..." oninput="filterTable(this,'tb-maquinaria')"></div>
        <table><thead><tr><th>ID</th><th>Nombre / Modelo</th><th>Marca</th><th>Serie</th><th>Cliente</th><th>Tipo</th><th>Estado</th><th>Acciones</th></tr></thead><tbody id="tb-maquinaria"></tbody></table>
      </div>
    </div>

    <!-- REPUESTOS -->
    <div id="page-repuestos" class="page-hide">
      <div class="section-header">
        <span class="section-title">Inventario de Repuestos</span>
        <div style="display:flex;gap:10px;">
          <button class="btn btn-green" onclick="abrirCompraGeneral()">📦 Registrar Compra</button>
          <button class="btn btn-primary" id="btn-nuevo-repuesto" onclick="abrirNuevoRepuesto()">＋ Nuevo Repuesto</button>
        </div>
      </div>
      <!-- Tabs -->
      <div class="tabs" style="margin-bottom:16px;">
        <div class="tab active" id="tab-inv" onclick="switchRepTab('inventario')">🔩 Inventario</div>
        <div class="tab" id="tab-compras" onclick="switchRepTab('compras')">📦 Historial de Compras</div>
      </div>
      <!-- Inventario -->
      <div id="rep-vista-inventario">
        <div class="table-wrap">
          <div class="search-bar"><input class="search-input" placeholder="🔍 Buscar repuesto..." oninput="filterTable(this,'tb-repuestos')"></div>
          <table><thead><tr><th>Código</th><th>Descripción</th><th>Categoría</th><th>Condición</th><th>Stock Total</th><th>Stock por Bodega</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr></thead><tbody id="tb-repuestos"></tbody></table>
        </div>
      </div>
      <!-- Historial de compras -->
      <div id="rep-vista-compras" style="display:none;">
        <div class="table-wrap">
          <div class="search-bar"><input class="search-input" placeholder="🔍 Buscar compra..." oninput="filterTable(this,'tb-compras')"></div>
          <table><thead><tr><th>Fecha</th><th>Repuesto</th><th>Proveedor</th><th>Cantidad</th><th>Precio Unit.</th><th>Total</th><th>Observaciones</th></tr></thead><tbody id="tb-compras"></tbody></table>
        </div>
      </div>
    </div>

    <!-- BODEGAS -->
    <div id="page-bodegas" class="page-hide">
      <div class="section-header">
        <span class="section-title">🏭 Bodegas</span>
        <button class="btn btn-primary" onclick="openModal('modal-bodega');bodegaModalNuevo()">＋ Nueva Bodega</button>
      </div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>ID</th><th>Nombre</th><th>Ubicación</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr></thead>
          <tbody id="tb-bodegas"></tbody>
        </table>
      </div>
    </div>

    <!-- TÉCNICOS -->
    <div id="page-tecnicos" class="page-hide">
      <div class="section-header"><span class="section-title">Técnicos</span><button class="btn btn-primary" id="btn-nuevo-tecnico" onclick="openModal('modal-tecnico')">＋ Nuevo Técnico</button></div>
      <div class="table-wrap">
        <div class="search-bar"><input class="search-input" placeholder="🔍 Buscar técnico..." oninput="filterTable(this,'tb-tecnicos')"></div>
        <table><thead><tr><th>ID</th><th>Nombre</th><th>Especialidad</th><th>Teléfono</th><th>Estado</th><th>Acciones</th></tr></thead><tbody id="tb-tecnicos"></tbody></table>
      </div>
    </div>

    <!-- ÓRDENES -->
    <div id="page-ordenes" class="page-hide">
      <div class="section-header"><span class="section-title">Órdenes de Servicio</span><button class="btn btn-primary" id="btn-nueva-orden" onclick="abrirNuevaOrden()">＋ Nueva Orden</button></div>
      <div class="table-wrap">
        <div class="search-bar">
          <input class="search-input" placeholder="🔍 Buscar orden..." oninput="filterTable(this,'tb-ordenes')">
          <select style="background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:8px 12px;color:var(--text);font-size:13px;outline:none;" onchange="loadOrdenes(this.value)">
            <option value="">Todos</option><option>Pendiente</option><option>En Proceso</option><option>Completado</option><option>Entregado</option>
          </select>
        </div>
        <table><thead><tr><th>N° Orden</th><th>Fecha</th><th>Cliente</th><th>Máquina</th><th>Técnico</th><th>Tipo</th><th>Estado</th><th>Acciones</th></tr></thead><tbody id="tb-ordenes"></tbody></table>
      </div>
    </div>

    <!-- MANTENIMIENTOS -->
    <div id="page-mantenimientos" class="page-hide">
      <div id="mant-alertas"></div>
      <div style="display:flex;gap:16px;align-items:flex-start;">
        <div style="flex:1;min-width:0;">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div class="tabs" style="margin-bottom:0;border-bottom:none;">
              <div class="tab active" id="tab-lista" onclick="switchMantTab('lista')">📋 Lista</div>
              <div class="tab" id="tab-calendario" onclick="switchMantTab('calendario')">🗓️ Calendario</div>
              <div class="tab" id="tab-historial" onclick="switchMantTab('historial')">📂 Historial</div>
            </div>
            <button class="btn btn-primary" onclick="openModal('modal-mantenimiento')">＋ Programar Mantenimiento</button>
          </div>
          <div id="mant-vista-lista"><div id="mant-lista-content"></div></div>
          <div id="mant-vista-calendario" style="display:none;">
            <div class="cal-nav">
              <button class="btn btn-secondary btn-sm" onclick="calNav(-1)">◀ Anterior</button>
              <span class="cal-nav-title" id="cal-month-title"></span>
              <button class="btn btn-secondary btn-sm" onclick="calNav(1)">Siguiente ▶</button>
            </div>
            <div class="cal-header"><div class="cal-dow">Dom</div><div class="cal-dow">Lun</div><div class="cal-dow">Mar</div><div class="cal-dow">Mié</div><div class="cal-dow">Jue</div><div class="cal-dow">Vie</div><div class="cal-dow">Sáb</div></div>
            <div class="cal-grid" id="cal-grid"></div>
          </div>
          <div id="mant-vista-historial" style="display:none;">
            <div class="table-wrap"><table><thead><tr><th>Fecha</th><th>Máquina</th><th>Tipo</th><th>Técnico</th><th>Resultado</th></tr></thead><tbody id="tb-historial"></tbody></table></div>
          </div>
        </div>
        <div style="width:260px;flex-shrink:0;">
          <div class="section-title" style="margin-bottom:12px;">Próximos 30 días</div>
          <div id="mant-sidebar"></div>
        </div>
      </div>
    </div>

    <!-- REPORTES — Panel de Estadísticas e Historial -->
    <div id="page-reportes" class="page-hide">
      <div class="section-header">
        <span class="section-title">Estadísticas e Historial</span>
        <div style="display:flex;gap:10px;align-items:center;">
          <select id="rep-filtro-periodo" style="background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:8px 12px;color:var(--text);font-size:13px;outline:none;" onchange="loadReportes()">
            <option value="30">Últimos 30 días</option>
            <option value="90">Últimos 90 días</option>
            <option value="180">Últimos 6 meses</option>
            <option value="365">Último año</option>
            <option value="0">Todo el historial</option>
          </select>
          <button class="btn btn-primary" onclick="window.print()">🖨️ Imprimir</button>
        </div>
      </div>

      <!-- KPIs -->
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;" id="rep-kpis">
        <div class="stat-card"><div class="stat-val" id="rep-total-ordenes">0</div><div class="stat-label">Órdenes Realizadas</div></div>
        <div class="stat-card"><div class="stat-val" id="rep-entregadas">0</div><div class="stat-label">Equipos Entregados</div></div>
        <div class="stat-card"><div class="stat-val" id="rep-clientes-atendidos">0</div><div class="stat-label">Clientes Atendidos</div></div>
        <div class="stat-card"><div class="stat-val" id="rep-mant-ejecutados">0</div><div class="stat-label">Mantenimientos Hechos</div></div>
      </div>

      <!-- Dos columnas: órdenes por estado + trabajos por técnico -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
        <!-- Órdenes por tipo -->
        <div style="background:var(--bg2);border:1px solid var(--border);border-radius:10px;padding:20px;">
          <div style="font-family:'Rajdhani',sans-serif;font-size:16px;font-weight:700;margin-bottom:16px;color:var(--accent);">📊 Órdenes por Tipo</div>
          <div id="rep-por-tipo"></div>
        </div>
        <!-- Trabajos por técnico -->
        <div style="background:var(--bg2);border:1px solid var(--border);border-radius:10px;padding:20px;">
          <div style="font-family:'Rajdhani',sans-serif;font-size:16px;font-weight:700;margin-bottom:16px;color:var(--accent);">👷 Trabajos por Técnico</div>
          <div id="rep-por-tecnico"></div>
        </div>
      </div>

      <!-- Historial completo de órdenes -->
      <div style="background:var(--bg2);border:1px solid var(--border);border-radius:10px;padding:20px;margin-bottom:24px;">
        <div style="font-family:'Rajdhani',sans-serif;font-size:16px;font-weight:700;margin-bottom:16px;color:var(--accent);">📋 Historial de Órdenes de Servicio</div>
        <div class="table-wrap" style="margin:0;">
          <input class="search-input" style="margin-bottom:12px;width:100%;" placeholder="🔍 Buscar por cliente, máquina, técnico..." oninput="filterTable(this,'tb-rep-ordenes')">
          <table>
            <thead><tr><th>N° Orden</th><th>Fecha</th><th>Cliente</th><th>Máquina</th><th>Tipo</th><th>Técnico</th><th>Estado</th><th>Reporte</th></tr></thead>
            <tbody id="tb-rep-ordenes"></tbody>
          </table>
        </div>
      </div>

      <!-- Vista previa del reporte imprimible (oculta hasta seleccionar) -->
      <div id="report-container" style="display:none;">
        <div class="section-header">
          <span class="section-title">Vista Previa — Reporte de Entrega al Cliente</span>
          <div style="display:flex;gap:10px;">
            <button class="btn btn-secondary" onclick="document.getElementById('report-container').style.display='none';document.getElementById('report-content').innerHTML=''">✕ Cerrar</button>
            <button class="btn btn-primary" onclick="window.print()">🖨️ Imprimir / PDF</button>
          </div>
        </div>
        <div id="report-content"></div>
      </div>
    </div>

    <!-- HOJA DE VIDA DE MÁQUINA -->
    <div id="page-hoja-vida" class="page-hide">
      <div class="section-header">
        <span class="section-title">📖 Hoja de Vida de Máquina</span>
        <div style="display:flex;gap:10px;">
          <button class="btn btn-secondary" onclick="window.print()">🖨️ Imprimir / PDF</button>
        </div>
      </div>

      <!-- Selector cliente + máquina -->
      <div class="hdv-selector">
        <div>
          <label style="font-size:12px;color:var(--text3);display:block;margin-bottom:6px;">CLIENTE</label>
          <select class="hdv-select" id="hdv-cliente" onchange="hdvLoadMaquinas()">
            <option value="">— Seleccionar cliente —</option>
          </select>
        </div>
        <div>
          <label style="font-size:12px;color:var(--text3);display:block;margin-bottom:6px;">MÁQUINA</label>
          <select class="hdv-select" id="hdv-maquina" onchange="hdvCargar()">
            <option value="">— Seleccionar máquina —</option>
          </select>
        </div>
      </div>

      <!-- Contenido hoja de vida -->
      <div id="hdv-contenido">
        <div class="hdv-empty">👆 Selecciona un cliente y una máquina para ver su hoja de vida</div>
      </div>
    </div>

    <!-- REPORTES -->
    <div id="page-facturas" class="page-hide">
      <div class="fac-total-box">
        <div class="fac-kpi"><div class="fac-kpi-val" id="fkpi-total">$0</div><div class="fac-kpi-label">Total Cobrado</div></div>
        <div class="fac-kpi-sep"></div>
        <div class="fac-kpi"><div class="fac-kpi-val" style="color:var(--success)" id="fkpi-pagado">$0</div><div class="fac-kpi-label">Total Pagado</div></div>
        <div class="fac-kpi-sep"></div>
        <div class="fac-kpi"><div class="fac-kpi-val" style="color:var(--danger)" id="fkpi-pendiente">$0</div><div class="fac-kpi-label">Por Cobrar</div></div>
        <div class="fac-kpi-sep"></div>
        <div class="fac-kpi"><div class="fac-kpi-val" style="color:var(--accent)" id="fkpi-count">0</div><div class="fac-kpi-label">Cuentas Activas</div></div>
      </div>
      <div class="section-header">
        <span class="section-title">Cuentas de Cobro</span>
        <button class="btn btn-primary" id="btn-nueva-factura" onclick="abrirNuevaFactura()">＋ Nueva Cuenta de Cobro</button>
      </div>
      <div class="table-wrap">
        <div class="search-bar">
          <input class="search-input" placeholder="🔍 Buscar cuenta de cobro, cliente..." oninput="filterTable(this,'tb-facturas')">
          <select style="background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:8px 12px;color:var(--text);font-size:13px;outline:none;" onchange="loadFacturas(this.value)">
            <option value="">Todos los estados</option>
            <option>Borrador</option><option>Emitida</option><option>Parcialmente Pagada</option><option>Pagada</option><option>Vencida</option><option>Anulada</option>
          </select>
        </div>
        <table>
          <thead><tr><th>N° Cuenta de Cobro</th><th>Fecha</th><th>Vencimiento</th><th>Cliente</th><th>Total</th><th>Pagado</th><th>Saldo</th><th>Estado</th><th>Acciones</th></tr></thead>
          <tbody id="tb-facturas"></tbody>
        </table>
      </div>
    </div>

    <!-- PAGOS -->
    <div id="page-pagos" class="page-hide">
      <div class="section-header"><span class="section-title">Registro de Pagos</span></div>
      <div class="table-wrap">
        <div class="search-bar"><input class="search-input" placeholder="🔍 Buscar por factura, cliente, referencia..." oninput="filterTable(this,'tb-pagos')"></div>
        <table>
          <thead><tr><th>Fecha</th><th>N° Cuenta de Cobro</th><th>Cliente</th><th>Método</th><th>Referencia</th><th>Monto</th><th>Acción</th></tr></thead>
          <tbody id="tb-pagos"></tbody>
        </table>
      </div>
    </div>

    <!-- DETALLE / REPORTE FACTURA -->
    <div id="page-factura-detalle" class="page-hide">
      <div class="section-header">
        <span class="section-title" id="fdet-titulo">Detalle de Cuenta de Cobro</span>
        <div style="display:flex;gap:10px;">
          <button class="btn btn-secondary" onclick="showPage('facturas')">← Volver</button>
          <button class="btn btn-green" id="fdet-btn-pago" onclick="abrirPago()">＋ Registrar Pago</button>
          <button class="btn btn-primary" onclick="window.print()">🖨️ Imprimir / PDF</button>
        </div>
      </div>
      <div id="fdet-content"></div>
    </div>

    <!-- GASTOS ADMINISTRATIVOS -->
    <!-- COTIZACIONES -->
    <div id="page-cotizaciones" class="page-hide">
      <div class="section-header">
        <span class="section-title">Cotizaciones</span>
        <button class="btn btn-primary" id="btn-nueva-cot" onclick="abrirCotModal()">＋ Nueva Cotización</button>
      </div>
      <!-- KPIs -->
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
        <div class="stat-card"><div class="stat-val" id="cot-kpi-total">0</div><div class="stat-label">Total Cotizaciones</div></div>
        <div class="stat-card"><div class="stat-val" style="color:var(--accent)" id="cot-kpi-pendientes">0</div><div class="stat-label">Pendientes</div></div>
        <div class="stat-card"><div class="stat-val" style="color:var(--success)" id="cot-kpi-aprobadas">0</div><div class="stat-label">Aprobadas</div></div>
        <div class="stat-card"><div class="stat-val" style="color:var(--danger)" id="cot-kpi-rechazadas">0</div><div class="stat-label">Rechazadas</div></div>
      </div>
      <div class="table-wrap">
        <input class="search-input" placeholder="🔍 Buscar cotización..." oninput="filterTable(this,'tb-cot')">
        <table>
          <thead><tr><th>N° Cotización</th><th>Fecha</th><th>Cliente</th><th>Máquina</th><th>Descripción</th><th>Total</th><th>Estado</th><th>Acciones</th></tr></thead>
          <tbody id="tb-cot"></tbody>
        </table>
      </div>
    </div>

    <!-- GASTOS -->
    <div id="page-gastos" class="page-hide">
      <div class="section-header">
        <span class="section-title">Gastos Administrativos</span>
        <button class="btn btn-primary" id="btn-nuevo-gasto" onclick="abrirNuevoGasto()">＋ Registrar Gasto</button>
      </div>

      <!-- KPIs gastos -->
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">
        <div class="stat-card red"><div class="stat-icon">💸</div><div class="stat-label">Total Este Mes</div><div class="stat-value" id="gkpi-mes" style="font-size:22px;">$0</div><div class="stat-sub">Gastos del mes actual</div></div>
        <div class="stat-card amber"><div class="stat-icon">📅</div><div class="stat-label">Este Año</div><div class="stat-value" id="gkpi-anio" style="font-size:22px;">$0</div><div class="stat-sub">Acumulado anual</div></div>
        <div class="stat-card blue"><div class="stat-icon">📂</div><div class="stat-label">Mayor Categoría</div><div class="stat-value" id="gkpi-cat" style="font-size:16px;line-height:1.2;">—</div><div class="stat-sub" id="gkpi-cat-val">este mes</div></div>
        <div class="stat-card"><div class="stat-icon">🔢</div><div class="stat-label">Registros</div><div class="stat-value" id="gkpi-count">0</div><div class="stat-sub">Total gastos</div></div>
      </div>

      <!-- Filtros -->
      <div class="table-wrap">
        <div class="search-bar">
          <input class="search-input" placeholder="🔍 Buscar por concepto, categoría, proveedor..." oninput="filterTable(this,'tb-gastos')">
          <select id="gasto-filtro-cat" style="background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:8px 12px;color:var(--text);font-size:13px;outline:none;" onchange="loadGastos()">
            <option value="">Todas las categorías</option>
            <option>Viáticos</option>
            <option>Servicios Públicos</option>
            <option>Arriendo / Alquiler local</option>
            <option>Alquiler de Equipos</option>
            <option>Transporte</option>
            <option>Comunicaciones</option>
            <option>Papelería / Insumos</option>
            <option>Salarios / Honorarios</option>
            <option>Mantenimiento Locativo</option>
            <option>Impuestos / Tasas</option>
            <option>Seguros</option>
            <option>Otros</option>
          </select>
          <select id="gasto-filtro-periodo" style="background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:8px 12px;color:var(--text);font-size:13px;outline:none;" onchange="loadGastos()">
            <option value="mes">Este mes</option>
            <option value="3m">Últimos 3 meses</option>
            <option value="anio">Este año</option>
            <option value="todo">Todo</option>
          </select>
        </div>
        <table>
          <thead><tr><th>Fecha</th><th>Categoría</th><th>Concepto</th><th>Proveedor / Beneficiario</th><th>Método de Pago</th><th>Valor</th><th>Soporte</th><th>Acciones</th></tr></thead>
          <tbody id="tb-gastos"></tbody>
        </table>
      </div>
    </div>

    <!-- USUARIOS Y PERMISOS -->
    <div id="page-usuarios" class="page-hide">
      <div class="section-header">
        <span class="section-title">Usuarios y Permisos</span>
        <button class="btn btn-primary" onclick="abrirNuevoUsuario()">＋ Nuevo Usuario</button>
      </div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Usuario</th><th>Nombre</th><th>Rol</th><th>Estado</th><th>Último acceso</th><th>Permisos</th><th>Acciones</th></tr></thead>
          <tbody id="tb-usuarios"></tbody>
        </table>
      </div>
    </div>

  </div>
</main>

<!-- Usuario -->
<div class="modal-overlay" id="modal-usuario">
  <div class="modal" style="max-width:680px;">
    <div class="modal-header"><span class="modal-title">👤 <span id="usr-modal-title">Nuevo Usuario</span></span><button class="modal-close" onclick="closeModal('modal-usuario')">✕</button></div>
    <div class="modal-body">
      <input type="hidden" id="usr-id">
      <div class="form-grid">
        <div class="form-group"><label>Usuario (login) *</label><input id="usr-username" placeholder="Ej: jperez" autocomplete="off"></div>
        <div class="form-group"><label>Nombre completo *</label><input id="usr-nombre" placeholder="Ej: Juan Pérez"></div>
        <div class="form-group"><label>Contraseña *</label><input id="usr-password" type="password" placeholder="Mínimo 6 caracteres" autocomplete="new-password"></div>
        <div class="form-group"><label>Rol</label>
          <select id="usr-rol" onchange="onRolChange()">
            <option value="operario">Operario</option>
            <option value="tecnico">Técnico</option>
            <option value="admin">Administrador</option>
            <option value="root" disabled>Root (sistema)</option>
          </select>
        </div>
        <div class="form-group"><label>Estado</label>
          <select id="usr-activo"><option value="1">Activo</option><option value="0">Inactivo</option></select>
        </div>
        <div class="form-group"><label>Email</label><input id="usr-email" type="email" placeholder="correo@ejemplo.com"></div>
      </div>

      <!-- Permisos granulares -->
      <div style="margin-top:20px;" id="usr-permisos-wrap">
        <div style="font-size:12px;color:var(--text2);font-weight:600;text-transform:uppercase;letter-spacing:.8px;margin-bottom:8px;">
          Permisos por módulo
          <span style="font-weight:400;color:var(--text3);margin-left:8px;text-transform:none;">(Admin tiene acceso total automáticamente)</span>
        </div>
        <div style="font-size:11px;color:var(--text3);margin-bottom:10px;">
          🔵 <strong>Consultar</strong> = solo lectura y uso en formularios de otras secciones &nbsp;|&nbsp;
          🟢 <strong>Crear</strong> &nbsp;|&nbsp; 🟡 <strong>Editar</strong> &nbsp;|&nbsp; 🔴 <strong>Eliminar</strong>
        </div>
        <div class="perm-quick">
          <button type="button" class="btn btn-secondary btn-sm" onclick="toggleAllGranular('all')">✅ Todo acceso</button>
          <button type="button" class="btn btn-secondary btn-sm" onclick="toggleAllGranular('consultar')">🔵 Solo consultar todo</button>
          <button type="button" class="btn btn-secondary btn-sm" onclick="toggleAllGranular('none')">☐ Quitar todo</button>
        </div>
        <div id="perm-modulos-container"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-usuario')">Cancelar</button>
      <button class="btn btn-primary" onclick="guardarUsuario()">💾 Guardar Usuario</button>
    </div>
  </div>
</div>

<!-- Importar Orden a Factura -->
<div class="modal-overlay" id="modal-importar-orden" style="z-index:300;">
  <div class="modal" style="max-width:680px;">
    <div class="modal-header">
      <span class="modal-title">📋 Importar desde Orden de Servicio</span>
      <button class="modal-close" onclick="closeModal('modal-importar-orden')">✕</button>
    </div>
    <div class="modal-body">
      <p style="color:var(--text2);font-size:13px;margin-bottom:14px;">Selecciona una orden para importar automáticamente sus repuestos y mano de obra a la cuenta de cobro.</p>
      <div class="search-bar" style="margin-bottom:12px;">
        <input class="search-input" placeholder="🔍 Buscar por ID, cliente, máquina..." oninput="filtrarOrdenesImport(this.value)">
      </div>
      <div id="lista-ordenes-import" style="max-height:380px;overflow-y:auto;display:flex;flex-direction:column;gap:8px;"></div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-importar-orden')">Cancelar</button>
    </div>
  </div>
</div>

<!-- Gasto Administrativo -->
<div class="modal-overlay" id="modal-gasto">
  <div class="modal" style="max-width:620px;">
    <div class="modal-header"><span class="modal-title">💸 <span id="gasto-modal-title">Registrar Gasto</span></span><button class="modal-close" onclick="closeModal('modal-gasto')">✕</button></div>
    <div class="modal-body">
      <input type="hidden" id="g-id">
      <div class="form-grid">
        <div class="form-group"><label>Fecha *</label><input id="g-fecha" type="date"></div>
        <div class="form-group"><label>Categoría *</label>
          <select id="g-categoria">
            <option value="">Seleccionar...</option>
            <option>Viáticos</option>
            <option>Servicios Públicos</option>
            <option>Arriendo / Alquiler local</option>
            <option>Alquiler de Equipos</option>
            <option>Transporte</option>
            <option>Comunicaciones</option>
            <option>Papelería / Insumos</option>
            <option>Salarios / Honorarios</option>
            <option>Mantenimiento Locativo</option>
            <option>Impuestos / Tasas</option>
            <option>Seguros</option>
            <option>Otros</option>
          </select>
        </div>
        <div class="form-group span2"><label>Concepto / Descripción *</label><input id="g-concepto" placeholder="Ej: Pago arriendo mes de marzo, viáticos visita cliente..."></div>
        <div class="form-group"><label>Proveedor / Beneficiario</label><input id="g-proveedor" placeholder="Ej: Inmobiliaria XYZ, Juan Pérez..."></div>
        <div class="form-group"><label>Método de Pago</label>
          <select id="g-metodo">
            <option>Efectivo</option>
            <option>Transferencia</option>
            <option>Cheque</option>
            <option>Tarjeta Débito</option>
            <option>Tarjeta Crédito</option>
            <option>Nequi / Daviplata</option>
            <option>Otro</option>
          </select>
        </div>
        <div class="form-group"><label>Valor *</label>
          <input id="g-valor" type="number" min="0" value="0" oninput="this.closest('.modal-body').querySelector('#g-valor-letras').textContent=numeroALetras(parseFloat(this.value||0))+' pesos'">
        </div>
        <div class="form-group"><label>N° Soporte / Factura</label><input id="g-soporte" placeholder="Ej: Factura #1234, Recibo #56..."></div>
        <div class="form-group span2" id="g-valor-letras-wrap" style="font-size:12px;color:var(--text3);font-style:italic;padding:4px 0;" ><span id="g-valor-letras">cero pesos</span></div>
        <div class="form-group span2"><label>Observaciones</label><textarea id="g-observaciones" placeholder="Notas adicionales..."></textarea></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-gasto')">Cancelar</button>
      <button class="btn btn-primary" onclick="guardarGasto()">💾 Guardar Gasto</button>
    </div>
  </div>
</div>


<div class="modal-overlay" id="modal-cliente">
  <div class="modal">
    <div class="modal-header"><span class="modal-title">👥 Registrar Cliente</span><button class="modal-close" onclick="closeModal('modal-cliente')">✕</button></div>
    <div class="modal-body">
      <input type="hidden" id="c-id">
      <div class="form-grid">
        <div class="form-group span2"><label>Empresa / Nombre *</label><input id="c-empresa" placeholder="Ej: Industrias ABC S.A."></div>
        <div class="form-group"><label>RUT / NIT</label><input id="c-rut" placeholder="Ej: 76.123.456-7"></div>
        <div class="form-group"><label>Contacto</label><input id="c-contacto" placeholder="Nombre responsable"></div>
        <div class="form-group"><label>Teléfono</label><input id="c-telefono"></div>
        <div class="form-group"><label>Email</label><input id="c-email" type="email"></div>
        <div class="form-group"><label>Ciudad</label><input id="c-ciudad"></div>
        <div class="form-group"><label>Actividad</label><input id="c-rubro" placeholder="Ej: Minería..."></div>
        <div class="form-group span2"><label>Dirección</label><input id="c-direccion"></div>
        <div class="form-group span2"><label>Notas</label><textarea id="c-notas"></textarea></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-cliente')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveCliente()">Guardar</button>
    </div>
  </div>
</div>

<!-- Maquinaria -->
<div class="modal-overlay" id="modal-maquina">
  <div class="modal">
    <div class="modal-header"><span class="modal-title">⚙️ Registrar Maquinaria</span><button class="modal-close" onclick="closeModal('modal-maquina')">✕</button></div>
    <div class="modal-body">
      <input type="hidden" id="m-id">
      <div class="form-grid">
        <div class="form-group span2"><label>Nombre *</label><input id="m-nombre" placeholder="Ej: Compresor Industrial"></div>
        <div class="form-group"><label>Marca</label><input id="m-marca"></div>
        <div class="form-group"><label>Modelo</label><input id="m-modelo"></div>
        <div class="form-group"><label>N° Serie</label><input id="m-serie"></div>
        <div class="form-group"><label>Año</label><input id="m-anio" type="number"></div>
        <div class="form-group"><label>Tipo</label>
          <select id="m-tipo"><option value="">Seleccionar...</option><option>Compresor</option><option>Bomba</option><option>Motor Eléctrico</option><option>Generador</option><option>Torno</option><option>Fresadora</option><option>Grúa / Montacargas</option><option>Prensa</option><option>Soldadora</option><option>Otro</option></select>
        </div>
        <div class="form-group"><label>Cliente</label><select id="m-cliente_id"><option value="">Sin asignar</option></select></div>
        <div class="form-group"><label>Potencia</label><input id="m-potencia" placeholder="Ej: 15 HP"></div>
        <div class="form-group"><label>Voltaje</label><input id="m-voltaje" placeholder="Ej: 380V"></div>
        <div class="form-group"><label>Horas de Uso</label><input id="m-horas_uso" type="number"></div>
        <div class="form-group"><label>Ubicación</label><input id="m-ubicacion"></div>
        <div class="form-group"><label>Estado</label>
          <select id="m-estado"><option>Operativo</option><option>En Reparación</option><option>Fuera de Servicio</option><option>En Revisión</option></select>
        </div>
        <div class="form-group span2"><label>Notas Técnicas</label><textarea id="m-notas"></textarea></div>
        <div class="form-group span2">
          <label>Fotos</label>
          <div id="foto-dropzone" onclick="document.getElementById('m-fotos-input').click()" ondragover="event.preventDefault();this.style.borderColor='var(--accent)'" ondragleave="this.style.borderColor='var(--border)'" ondrop="handleFotoDrop(event)" style="border:2px dashed var(--border);border-radius:8px;padding:20px;text-align:center;cursor:pointer;color:var(--text3);font-size:13px;transition:border-color .15s;">
            📷 Haz clic o arrastra fotos aquí (máx. 5)
          </div>
          <input type="file" id="m-fotos-input" accept="image/*" multiple style="display:none;" onchange="handleFotoSelect(event)">
          <div id="foto-preview-grid" style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px;margin-top:10px;"></div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-maquina')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveMaquina()">Guardar</button>
    </div>
  </div>
</div>

<!-- Repuesto -->
<div class="modal-overlay" id="modal-repuesto">
  <div class="modal">
    <div class="modal-header"><span class="modal-title">🔩 <span id="rep-modal-title">Nuevo Repuesto</span></span><button class="modal-close" onclick="closeModal('modal-repuesto')">✕</button></div>
    <div class="modal-body">
      <input type="hidden" id="r-editing-id">
      <div class="form-grid">
        <div class="form-group"><label>Código *</label><input id="r-id" placeholder="Ej: REP-001"></div>
        <div class="form-group"><label>Condición</label>
          <select id="r-condicion">
            <option value="nuevo">🟢 Nuevo</option>
            <option value="usado">🟡 Usado</option>
          </select>
        </div>
        <div class="form-group"><label>Categoría</label>
          <select id="r-categoria"><option>Filtros</option><option>Rodamientos</option><option>Sellos / Juntas</option><option>Correas / Bandas</option><option>Eléctrico</option><option>Hidráulico</option><option>Neumático</option><option>Lubricantes</option><option>General</option></select>
        </div>
        <div class="form-group span2"><label>Descripción *</label><input id="r-descripcion"></div>
        <div class="form-group"><label>Marca</label><input id="r-marca"></div>
        <div class="form-group"><label>Referencia</label><input id="r-referencia"></div>
        <!-- Stock: solo editable al crear, bloqueado al editar -->
        <div class="form-group" id="r-stock-group">
          <label id="r-stock-label">Stock Inicial</label>
          <input id="r-stock" type="number" value="0" min="0">
          <div id="r-stock-hint" style="font-size:11px;color:var(--text3);margin-top:4px;display:none;">Para modificar el stock usa <strong>📦 Registrar Compra</strong></div>
        </div>
        <div class="form-group"><label>Stock Mínimo (alerta)</label><input id="r-stock_minimo" type="number" value="5"></div>
        <div class="form-group"><label>Precio de venta ($)</label><input id="r-precio" type="number" value="0"></div>
        <div class="form-group"><label>Unidad</label><select id="r-unidad"><option>Unidad</option><option>Par</option><option>Juego</option><option>Litro</option><option>Kg</option><option>Metro</option></select></div>
        <div class="form-group span2"><label>Compatible con</label><input id="r-compatible_con"></div>
        <div class="form-group"><label>Bodega inicial</label>
          <select id="r-bodega-id">
            <option value="">— Sin bodega —</option>
          </select>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-repuesto')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveRepuesto()">Guardar</button>
    </div>
  </div>
</div>

<!-- Registrar Compra -->
<div class="modal-overlay" id="modal-compra">
  <div class="modal" style="max-width:560px;">
    <div class="modal-header"><span class="modal-title">📦 Registrar Compra</span><button class="modal-close" onclick="closeModal('modal-compra')">✕</button></div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="form-group span2"><label>Repuesto *</label>
          <select id="compra-repuesto_id"><option value="">Seleccionar repuesto...</option></select>
        </div>
        <div class="form-group"><label>Fecha *</label><input id="compra-fecha" type="date"></div>
        <div class="form-group"><label>Proveedor</label><input id="compra-proveedor" placeholder="Ej: Ferretería Central"></div>
        <div class="form-group span2"><label>Bodega destino</label>
          <select id="compra-bodega_id">
            <option value="">— Sin bodega específica —</option>
          </select>
        </div>
        <div class="form-group"><label>Cantidad *</label><input id="compra-cantidad" type="number" min="1" value="1" oninput="calcTotalCompra()"></div>
        <div class="form-group"><label>Precio Unitario ($)</label><input id="compra-precio_unit" type="number" min="0" value="0" oninput="calcTotalCompra()"></div>
        <div class="form-group span2">
          <label>Total de la Compra</label>
          <div style="background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:10px 14px;font-size:18px;font-weight:700;color:var(--accent);" id="compra-total-display">$0</div>
        </div>
        <div class="form-group span2"><label>Observaciones</label><textarea id="compra-observaciones" placeholder="Ej: Factura #1234, garantía 6 meses..."></textarea></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-compra')">Cancelar</button>
      <button class="btn btn-green" onclick="guardarCompra()">📦 Guardar Compra y Actualizar Stock</button>
    </div>
  </div>
</div>

<!-- Bodega -->
<div class="modal-overlay" id="modal-bodega">
  <div class="modal">
    <div class="modal-header"><span class="modal-title">🏭 <span id="bodega-modal-title">Nueva Bodega</span></span><button class="modal-close" onclick="closeModal('modal-bodega')">✕</button></div>
    <div class="modal-body">
      <div class="form-grid">
        <input type="hidden" id="bod-id">
        <div class="form-group span2"><label>Nombre *</label><input id="bod-nombre" placeholder="Ej: Bodega Principal"></div>
        <div class="form-group span2"><label>Ubicación</label><input id="bod-ubicacion" placeholder="Ej: Planta 1, Estante A"></div>
        <div class="form-group span2"><label>Descripción</label><input id="bod-descripcion" placeholder="Descripción opcional"></div>
        <div class="form-group"><label>Estado</label>
          <select id="bod-activa">
            <option value="1">Activa</option>
            <option value="0">Inactiva</option>
          </select>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-bodega')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveBodega()">💾 Guardar</button>
    </div>
  </div>
</div>

<!-- Técnico -->
<div class="modal-overlay" id="modal-tecnico">
  <div class="modal">
    <div class="modal-header"><span class="modal-title">👷 Registrar Técnico</span><button class="modal-close" onclick="closeModal('modal-tecnico')">✕</button></div>
    <div class="modal-body">
      <input type="hidden" id="t-id">
      <div class="form-grid">
        <div class="form-group"><label>Nombre *</label><input id="t-nombre"></div>
        <div class="form-group"><label>Apellido</label><input id="t-apellido"></div>
        <div class="form-group"><label>Cédula de Ciudadanía</label><input id="t-rut" placeholder="Ej: 1075322059"></div>
        <div class="form-group"><label>Especialidad</label>
          <select id="t-especialidad"><option>Mecánico Industrial</option><option>Electricista Industrial</option><option>Hidráulica / Neumática</option><option>Electromecánico</option><option>Soldador</option><option>Multidisciplinario</option></select>
        </div>
        <div class="form-group"><label>Teléfono</label><input id="t-telefono"></div>
        <div class="form-group"><label>Email</label><input id="t-email" type="email"></div>
        <div class="form-group"><label>Estado</label><select id="t-estado"><option>Disponible</option><option>En Servicio</option><option>Vacaciones</option><option>Inactivo</option></select></div>
        <div class="form-group"><label>Nivel</label><select id="t-nivel"><option>Junior</option><option>Semi-Senior</option><option>Senior</option><option>Maestro</option></select></div>
        <div class="form-group span2"><label>Notas</label><textarea id="t-notas"></textarea></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-tecnico')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveTecnico()">Guardar</button>
    </div>
  </div>
</div>

<!-- Orden -->
<div class="modal-overlay" id="modal-orden">
  <div class="modal" style="max-width:720px;">
    <div class="modal-header"><span class="modal-title">📋 <span id="orden-modal-title">Nueva Orden de Servicio</span></span><button class="modal-close" onclick="closeModal('modal-orden')">✕</button></div>
    <div class="modal-body">
      <input type="hidden" id="o-editing-id">
      <div class="form-grid">
        <div class="form-group"><label>Fecha *</label><input id="o-fecha" type="date"></div>
        <div class="form-group"><label>Tipo *</label>
          <select id="o-tipo"><option>Mantenimiento Preventivo</option><option>Mantenimiento Correctivo</option><option>Reparación</option><option>Diagnóstico</option><option>Instalación</option><option>Calibración</option></select>
        </div>
        <div class="form-group"><label>Cliente *</label><select id="o-cliente_id" onchange="loadMaquinasByCliente()"><option value="">Seleccionar...</option></select></div>
        <div class="form-group"><label>Máquina *</label><select id="o-maquina_id"><option value="">Seleccionar...</option></select></div>
        <div class="form-group"><label>Técnico</label><select id="o-tecnico_id"><option value="">Sin asignar</option></select></div>
        <div class="form-group"><label>Prioridad</label><select id="o-prioridad"><option>Normal</option><option>Alta</option><option>Urgente</option><option>Baja</option></select></div>
        <div class="form-group span2"><label>Falla Reportada</label><textarea id="o-falla"></textarea></div>
        <div class="form-group span2"><label>Diagnóstico</label><textarea id="o-diagnostico"></textarea></div>
        <div class="form-group span2"><label>Trabajos Realizados</label><textarea id="o-trabajos"></textarea></div>
        <div class="form-divider"></div>
        <!-- Repuestos -->
        <div class="form-group span2">
          <label>Repuestos Utilizados</label>
          <div style="background:var(--bg2);border:1px solid var(--border);border-radius:8px;padding:12px;margin-top:8px;">
            <div style="display:grid;grid-template-columns:2fr 1fr 110px auto;gap:8px;margin-bottom:6px;padding:0 2px;">
              <span style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:1px;">Repuesto</span>
              <span style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:1px;">Cantidad</span>
              <span style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:1px;">Subtotal</span>
              <span></span>
            </div>
            <div id="lista-rep-orden"></div>
            <div style="margin-top:8px;display:flex;align-items:center;justify-content:space-between;">
              <button type="button" class="btn btn-secondary btn-sm" onclick="addRepOrden()">＋ Agregar Repuesto</button>
              <div style="font-size:13px;color:var(--text2);">Total repuestos: <strong id="o-total-rep-display" style="color:var(--accent);">$0</strong></div>
            </div>
          </div>
        </div>
        <!-- Mano de obra -->
        <div class="form-group"><label>Horas Trabajo</label><input id="o-horas" type="number" value="0" min="0" step="0.5" oninput="recalcOrdenTotal()"></div>
        <div class="form-group"><label>Mano de Obra ($)</label><input id="o-mano_obra" type="number" value="0" min="0" oninput="recalcOrdenTotal()"></div>
        <!-- Total visual -->
        <div class="form-group span2">
          <div style="background:var(--bg2);border:1px solid var(--accent);border-radius:8px;padding:14px 18px;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:13px;color:var(--text2);font-weight:600;">TOTAL ORDEN</span>
            <span id="o-total-display" style="font-size:22px;font-weight:800;color:var(--accent);">$0</span>
          </div>
        </div>
        <div class="form-group"><label>Estado</label><select id="o-estado"><option>Pendiente</option><option>En Proceso</option><option>Completado</option><option>Entregado</option></select></div>
        <div class="form-group"><label>Fecha Entrega</label><input id="o-fecha_entrega" type="date"></div>
        <div class="form-group span2"><label>Observaciones</label><textarea id="o-observaciones"></textarea></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-orden')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveOrden()">💾 Guardar Orden</button>
    </div>
  </div>
</div>

<!-- Mantenimiento -->
<div class="modal-overlay" id="modal-mantenimiento">
  <div class="modal" style="max-width:660px;">
    <div class="modal-header"><span class="modal-title">🗓️ Programar Mantenimiento</span><button class="modal-close" onclick="closeModal('modal-mantenimiento')">✕</button></div>
    <div class="modal-body">
      <div class="form-grid">
        <div class="form-group"><label>Máquina *</label><select id="mnt-maquina_id"><option value="">Seleccionar...</option></select></div>
        <div class="form-group"><label>Tipo *</label>
          <select id="mnt-tipo"><option>Preventivo General</option><option>Cambio de Aceite / Lubricación</option><option>Cambio de Filtros</option><option>Revisión Eléctrica</option><option>Revisión Hidráulica</option><option>Calibración</option><option>Inspección Visual</option><option>Limpieza General</option><option>Cambio de Correas</option><option>Cambio de Rodamientos</option><option>Revisión Completa</option></select>
        </div>
        <div class="form-group"><label>Fecha *</label><input id="mnt-fecha" type="date"></div>
        <div class="form-group"><label>Periodicidad</label>
          <select id="mnt-periodo_dias" onchange="document.getElementById('mnt-custom-wrap').style.display=this.value==='custom'?'block':'none'">
            <option value="0">Sin repetición</option><option value="7">Semanal</option><option value="14">Quincenal</option><option value="30">Mensual</option><option value="60">Bimestral</option><option value="90">Trimestral</option><option value="180">Semestral</option><option value="365">Anual</option><option value="custom">Personalizado...</option>
          </select>
        </div>
        <div class="form-group" id="mnt-custom-wrap" style="display:none;"><label>Cada cuántos días</label><input id="mnt-custom-dias" type="number" min="1"></div>
        <div class="form-group"><label>Técnico</label><select id="mnt-tecnico_id"><option value="">Sin asignar</option></select></div>
        <div class="form-group"><label>Duración estimada (hrs)</label><input id="mnt-duracion_hrs" type="number" value="2"></div>
        <div class="form-group"><label>Prioridad</label><select id="mnt-prioridad"><option>Normal</option><option>Alta</option><option>Crítica</option><option>Baja</option></select></div>
        <div class="form-group span2"><label>Descripción / Checklist</label><textarea id="mnt-descripcion" style="min-height:90px;"></textarea></div>
        <div class="form-group span2"><label>Materiales Necesarios</label><textarea id="mnt-materiales"></textarea></div>
        <div class="form-group span2"><label>Observaciones</label><input id="mnt-observaciones"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-mantenimiento')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveMantenimiento()">Programar</button>
    </div>
  </div>
</div>

<!-- Ejecutar Mantenimiento -->
<div class="modal-overlay" id="modal-ejecutar">
  <div class="modal" style="max-width:520px;">
    <div class="modal-header"><span class="modal-title">✅ Registrar Ejecución</span><button class="modal-close" onclick="closeModal('modal-ejecutar')">✕</button></div>
    <div class="modal-body">
      <div id="exec-info" style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:14px;margin-bottom:16px;font-size:13px;color:var(--text2);"></div>
      <div class="form-grid">
        <div class="form-group"><label>Fecha Ejecución *</label><input id="exec-fecha" type="date"></div>
        <div class="form-group"><label>Técnico</label><select id="exec-tecnico_id"><option value="">Seleccionar...</option></select></div>
        <div class="form-group"><label>Horas Reales</label><input id="exec-horas_reales" type="number" value="0"></div>
        <div class="form-group"><label>Resultado</label>
          <select id="exec-resultado"><option>Completado sin novedad</option><option>Completado con observaciones</option><option>Parcialmente completado</option><option>Requiere revisión adicional</option></select>
        </div>
        <div class="form-group span2"><label>Notas</label><textarea id="exec-notas"></textarea></div>
        <div class="form-group span2"><label>Reprogramar próxima fecha</label><input id="exec-proxima_fecha" type="date"></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-ejecutar')">Cancelar</button>
      <button class="btn btn-primary" onclick="ejecutarMant()">Registrar</button>
    </div>
  </div>
</div>

<!-- Ver Fotos -->
<div class="modal-overlay" id="modal-fotos">
  <div class="modal" style="max-width:750px;">
    <div class="modal-header"><span class="modal-title" id="fotos-titulo">📷 Fotos</span><button class="modal-close" onclick="closeModal('modal-fotos')">✕</button></div>
    <div class="modal-body"><div id="fotos-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;"></div></div>
  </div>
</div>

<!-- Modal Cotización -->
<div class="modal-overlay" id="modal-cot">
  <div class="modal" style="max-width:720px;width:95%;">
    <div class="modal-header">
      <span class="modal-title">📝 <span id="cot-modal-title">Nueva Cotización</span></span>
      <button class="modal-close" onclick="closeModal('modal-cot')">✕</button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="cot-id">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
        <div>
          <label class="form-label">Cliente *</label>
          <select class="form-input" id="cot-cliente" onchange="cotLoadMaquinas()">
            <option value="">— Seleccionar —</option>
          </select>
        </div>
        <div>
          <label class="form-label">Máquina</label>
          <select class="form-input" id="cot-maquina">
            <option value="">— Seleccionar —</option>
          </select>
        </div>
        <div>
          <label class="form-label">Fecha *</label>
          <input type="date" class="form-input" id="cot-fecha">
        </div>
        <div>
          <label class="form-label">Válida hasta</label>
          <input type="date" class="form-input" id="cot-valida">
        </div>
        <div style="grid-column:1/-1;">
          <label class="form-label">Descripción del trabajo</label>
          <textarea class="form-input" id="cot-descripcion" rows="2" placeholder="Descripción general de la cotización..."></textarea>
        </div>
      </div>

      <!-- Visita técnica -->
      <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
          <span style="font-size:13px;font-weight:600;">🔍 Visita Técnica</span>
          <label style="display:flex;align-items:center;gap:6px;font-size:12px;cursor:pointer;">
            <input type="checkbox" id="cot-visita-check" onchange="cotToggleVisita()"> Incluir visita técnica
          </label>
        </div>
        <div id="cot-visita-row" style="display:none;display:grid;grid-template-columns:1fr 120px;gap:8px;">
          <input class="form-input" id="cot-visita-desc" placeholder="Descripción visita técnica" style="font-size:13px;">
          <input type="number" class="form-input" id="cot-visita-valor" placeholder="Valor $" min="0" oninput="cotCalcular()" style="font-size:13px;">
        </div>
      </div>

      <!-- Mano de obra -->
      <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
          <span style="font-size:13px;font-weight:600;">👷 Mano de Obra</span>
          <button class="btn btn-secondary btn-sm" onclick="cotAddManoObra()">＋ Agregar</button>
        </div>
        <div style="display:grid;grid-template-columns:1fr 120px 30px;gap:6px;margin-bottom:4px;">
          <span style="font-size:11px;color:var(--text3);">DESCRIPCIÓN</span>
          <span style="font-size:11px;color:var(--text3);">VALOR</span>
          <span></span>
        </div>
        <div id="cot-mano-lista"></div>
      </div>

      <!-- Repuestos/materiales -->
      <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:12px;margin-bottom:12px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
          <span style="font-size:13px;font-weight:600;">🔧 Repuestos / Materiales</span>
          <button class="btn btn-secondary btn-sm" onclick="cotAddRepuesto()">＋ Agregar</button>
        </div>
        <div style="display:grid;grid-template-columns:1fr 80px 100px 30px;gap:6px;margin-bottom:4px;">
          <span style="font-size:11px;color:var(--text3);">DESCRIPCIÓN</span>
          <span style="font-size:11px;color:var(--text3);">CANT.</span>
          <span style="font-size:11px;color:var(--text3);">PRECIO UNIT.</span>
          <span></span>
        </div>
        <div id="cot-rep-lista"></div>
      </div>

      <!-- Totales -->
      <div class="cot-total-box">
        <div class="cot-total-row"><span>Visita técnica</span><span id="cot-t-visita">$0</span></div>
        <div class="cot-total-row"><span>Mano de obra</span><span id="cot-t-mano">$0</span></div>
        <div class="cot-total-row"><span>Repuestos/materiales</span><span id="cot-t-rep">$0</span></div>
        <div class="cot-total-row final"><span>TOTAL</span><span id="cot-t-total">$0</span></div>
      </div>

      <div style="margin-top:12px;">
        <label class="form-label">Observaciones / Condiciones</label>
        <textarea class="form-input" id="cot-observaciones" rows="2" placeholder="Condiciones de pago, tiempo de entrega, garantía..."></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-cot')">Cancelar</button>
      <button class="btn btn-primary" onclick="cotGuardar()">💾 Guardar Cotización</button>
    </div>
  </div>
</div>

<!-- Nueva / Editar Cuenta de Cobro -->
<div class="modal-overlay" id="modal-factura">
  <div class="modal" style="max-width:820px;">
    <div class="modal-header"><span class="modal-title">🧾 <span id="fac-modal-title">Nueva Cuenta de Cobro</span></span><button class="modal-close" onclick="closeModal('modal-factura')">✕</button></div>
    <div class="modal-body">
      <input type="hidden" id="fac-id">

      <!-- Sección: Datos del Prestador (Taller) -->
      <div style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:16px;margin-bottom:18px;">
        <div style="font-size:11px;color:var(--accent);font-weight:700;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:12px;">🏭 Datos del Prestador</div>

        <!-- Datos fijos — solo lectura -->
        <div style="background:var(--bg);border:1px solid var(--border);border-radius:6px;padding:12px 14px;margin-bottom:14px;display:grid;grid-template-columns:1fr 1fr;gap:6px 20px;font-size:12px;">
          <div><span style="color:var(--text3);">Nombre:</span> <strong style="color:var(--text);">EDINSON ACUÑA AYALA</strong></div>
          <div><span style="color:var(--text3);">CC:</span> <strong style="color:var(--text);">96.354.114</strong></div>
          <div><span style="color:var(--text3);">Teléfono:</span> <strong style="color:var(--text);">3137217967</strong></div>
          <div><span style="color:var(--text3);">Email:</span> <strong style="color:var(--text);">edac77@gmail.com</strong></div>
          <input type="hidden" id="fac-taller-nombre" value="EDINSON ACUÑA AYALA">
          <input type="hidden" id="fac-taller-rut"    value="96.354.114">
          <input type="hidden" id="fac-taller-telefono" value="3137217967">
          <input type="hidden" id="fac-titular-cuenta"  value="edac77@gmail.com">
        </div>

        <!-- Datos opcionales — el usuario los puede completar -->
        <div class="form-grid">
          <div class="form-group"><label>Ciudad / Dirección</label><input id="fac-taller-ciudad" placeholder="Ej: Neiva, Cll 20a #38-36 Guaduales"></div>
          <div class="form-group"><label>Banco</label><input id="fac-banco" placeholder="Ej: Bancolombia"></div>
          <div class="form-group"><label>Tipo de Cuenta</label>
            <select id="fac-tipo-cuenta"><option value="">Seleccionar...</option><option>Cuenta de Ahorros</option><option>Cuenta Corriente</option><option>Nequi</option><option>Daviplata</option></select>
          </div>
          <div class="form-group"><label>Número de Cuenta</label><input id="fac-num-cuenta" placeholder="Ej: 123-456789-00"></div>
        </div>
      </div>

      <!-- Sección: Datos de la Cuenta -->
      <div class="form-grid">
        <div class="form-group"><label>N° Cuenta de Cobro *</label><input id="fac-numero" placeholder="Ej: CC-2024-0001"></div>
        <div class="form-group"><label>Cliente *</label><select id="fac-cliente_id"><option value="">Seleccionar...</option></select></div>
        <div class="form-group"><label>Fecha *</label><input id="fac-fecha" type="date"></div>
        <div class="form-group"><label>Fecha Vencimiento</label><input id="fac-fecha_vencimiento" type="date"></div>
        <div class="form-group"><label>Estado</label>
          <select id="fac-estado"><option>Borrador</option><option>Emitida</option><option>Anulada</option></select>
        </div>
        <div class="form-group"><label>Concepto General</label><input id="fac-concepto" placeholder="Ej: Servicios de mantenimiento industrial..."></div>
      </div>

      <!-- Ítems -->
      <div style="margin-top:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
          <label style="font-size:13px;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.8px;">Descripción de Servicios</label>
          <div style="display:flex;gap:8px;">
            <button type="button" class="btn btn-green btn-sm" onclick="abrirImportarOrden()" title="Importar todos los ítems de una Orden de Servicio">📋 Importar desde Orden</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addItem('Servicio')">＋ Servicio</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addItem('Mano de Obra')">＋ M. de Obra</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addItem('Repuesto')">＋ Repuesto</button>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:100px 1fr 80px 110px 90px 32px;gap:6px;margin-bottom:6px;padding:0 4px;">
          <span style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:1px;">Tipo</span>
          <span style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:1px;">Descripción</span>
          <span style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:1px;">Cant.</span>
          <span style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:1px;">Precio Unit.</span>
          <span style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:1px;">Subtotal</span>
          <span></span>
        </div>
        <div id="fac-items-list"></div>
        <div style="margin-top:16px;display:flex;flex-direction:column;align-items:flex-end;gap:6px;border-top:1px solid var(--border);padding-top:12px;">
          <div style="font-size:22px;font-weight:700;color:var(--accent);">TOTAL: <strong id="fac-total-display">$0</strong></div>
        </div>
      </div>
      <div class="form-group" style="margin-top:16px;"><label>Observaciones / Condiciones de pago</label><textarea id="fac-notas" placeholder="Ej: Pago a 30 días, transferencia bancaria..."></textarea></div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-factura')">Cancelar</button>
      <button class="btn btn-primary" onclick="saveFactura()">💾 Guardar Cuenta de Cobro</button>
    </div>
  </div>
</div>

<!-- Registrar Pago -->
<div class="modal-overlay" id="modal-pago">
  <div class="modal" style="max-width:500px;">
    <div class="modal-header"><span class="modal-title">💰 Registrar Pago</span><button class="modal-close" onclick="closeModal('modal-pago')">✕</button></div>
    <div class="modal-body">
      <div id="pago-fac-info" style="background:var(--bg3);border:1px solid var(--border);border-radius:8px;padding:14px;margin-bottom:16px;font-size:13px;"></div>
      <div class="form-grid">
        <div class="form-group"><label>Fecha del Pago *</label><input id="pago-fecha" type="date"></div>
        <div class="form-group"><label>Método *</label>
          <select id="pago-metodo">
            <option>Efectivo</option><option>Transferencia Bancaria</option><option>Cheque</option><option>Tarjeta Débito</option><option>Tarjeta Crédito</option><option>Otro</option>
          </select>
        </div>
        <div class="form-group"><label>Monto *</label><input id="pago-monto" type="number" min="0" step="0.01" placeholder="0.00"></div>
        <div class="form-group"><label>N° Referencia / Comprobante</label><input id="pago-referencia" placeholder="Ej: Transf. 12345"></div>
        <div class="form-group span2"><label>Notas</label><textarea id="pago-notas" style="min-height:60px;"></textarea></div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('modal-pago')">Cancelar</button>
      <button class="btn btn-primary" onclick="guardarPago()">💰 Registrar Pago</button>
    </div>
  </div>
</div>

<script>
// ── CONFIG ────────────────────────────────────────────────────
const API = 'api'; // Ruta relativa a la carpeta api/

// ── STATE ─────────────────────────────────────────────────────
let cache = { clientes:[], maquinaria:[], repuestos:[], tecnicos:[], ordenes:[], mantenimientos:[] };
let fotosTemp = [];
let repOrdenTemp = [];
let currentMantId = null;
let calYear, calMonth;
const today = new Date(); calYear = today.getFullYear(); calMonth = today.getMonth();

// ── API CALLS ─────────────────────────────────────────────────
async function apiFetch(endpoint, method='GET', body=null) {
  const headers = {'Content-Type':'application/json'};
  const token = currentUser?.token || getStoredUser()?.token || '';
  if(token) headers['X-Auth-Token'] = token;
  const opts = { method, headers };
  if (body) opts.body = JSON.stringify(body);
  try {
    const res = await fetch(`${API}/${endpoint}`, opts);
    return await res.json();
  } catch(e) {
    showToast('❌ Error de conexión con el servidor'); 
    document.getElementById('db-dot').style.background='var(--danger)';
    document.getElementById('db-status-text').textContent='Sin conexión';
    return null;
  }
}

function toggleNavGroup(id){
  const group = document.getElementById(id);
  const section = group.previousElementSibling;
  if(!group) return;
  const isCollapsed = group.classList.contains('collapsed');
  if(isCollapsed){
    group.style.maxHeight = group.scrollHeight + 'px';
    group.classList.remove('collapsed');
    section.classList.remove('collapsed');
  } else {
    group.style.maxHeight = group.scrollHeight + 'px';
    requestAnimationFrame(()=>{ group.style.maxHeight='0'; });
    group.classList.add('collapsed');
    section.classList.add('collapsed');
  }
}
function initNavGroups(){
  document.querySelectorAll('.nav-group').forEach(g=>{
    g.style.maxHeight = g.scrollHeight + 'px';
  });
}

// ── NAVIGATION ────────────────────────────────────────────────
function showPage(page) {
  document.querySelectorAll('[id^="page-"]').forEach(el=>el.classList.add('page-hide'));
  document.querySelectorAll('.nav-item').forEach(el=>el.classList.remove('active'));
  document.getElementById('page-'+page).classList.remove('page-hide');
  document.querySelectorAll('.nav-item').forEach(el=>{
    if(el.getAttribute('onclick')?.includes("'"+page+"'")) el.classList.add('active');
  });
  const titles={dashboard:'Dashboard',clientes:'Clientes',maquinaria:'Maquinaria',repuestos:'Inventario de Repuestos',tecnicos:'Técnicos',ordenes:'Órdenes de Servicio',mantenimientos:'Programación de Mantenimientos',reportes:'Estadísticas e Historial',facturas:'Cuentas de Cobro',pagos:'Registro de Pagos','factura-detalle':'Detalle de Cuenta de Cobro',gastos:'Gastos Administrativos',usuarios:'Usuarios y Permisos','hoja-vida':'Hoja de Vida de Máquina',cotizaciones:'Cotizaciones'};
  document.getElementById('page-title').textContent = titles[page]||page;
  if(page==='dashboard') loadDashboard();
  if(page==='clientes') loadClientes();
  if(page==='maquinaria') loadMaquinaria();
  if(page==='repuestos') loadRepuestos();
  if(page==='bodegas') loadBodegas();
  if(page==='tecnicos') loadTecnicos();
  if(page==='ordenes') loadOrdenes();
  if(page==='mantenimientos') loadMantenimientos();
  if(page==='cotizaciones') loadCotizaciones();
  if(page==='hoja-vida') hdvInit();
  if(page==='reportes') loadReportes();
  if(page==='facturas') loadFacturas();
  if(page==='pagos') loadPagos();
  if(page==='gastos') loadGastos();
  if(page==='usuarios') loadUsuarios();
}

function openModal(id) {
  if(id==='modal-maquina') populateSelect('m-cliente_id', cache.clientes, 'id','empresa','Sin asignar');
  // modal-orden: populated by abrirNuevaOrden() or editarOrden()
  if(id==='modal-mantenimiento') { populateSelect('mnt-maquina_id',cache.maquinaria,'id','nombre','Seleccionar...'); populateSelect('mnt-tecnico_id',cache.tecnicos,'id',t=>`${t.nombre} ${t.apellido||''}`,'Sin asignar'); document.getElementById('mnt-fecha').value=new Date().toISOString().split('T')[0]; }
  if(id==='modal-ejecutar') { populateSelect('exec-tecnico_id',cache.tecnicos,'id',t=>`${t.nombre} ${t.apellido||''}`,'Seleccionar...'); document.getElementById('exec-fecha').value=new Date().toISOString().split('T')[0]; }
  document.getElementById(id).classList.add('open');
}
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
window.onclick = e => { if(e.target.classList.contains('modal-overlay')) e.target.classList.remove('open'); };

function populateSelect(selId, data, valKey, labelFn, placeholder='') {
  const sel = document.getElementById(selId); if(!sel) return;
  const prev = sel.value;
  const fn = typeof labelFn === 'function' ? labelFn : item => item[labelFn];
  sel.innerHTML = `<option value="">${placeholder}</option>` + data.map(d=>`<option value="${d[valKey]}">${fn(d)}</option>`).join('');
  sel.value = prev;
}

function val(id) { const el=document.getElementById(id); return el?el.value:''; }
function setVal(id,v) { const el=document.getElementById(id); if(el) el.value=v||''; }
function filterTable(inp, tbId) {
  const q=inp.value.toLowerCase();
  document.getElementById(tbId).querySelectorAll('tr').forEach(r=>r.style.display=r.textContent.toLowerCase().includes(q)?'':'none');
}

// ── DASHBOARD ─────────────────────────────────────────────────
async function loadDashboard() {
  const d = await apiFetch('dashboard.php');
  if(!d) return;
  document.getElementById('d-clientes').textContent=d.clientes;
  document.getElementById('d-maquinas').textContent=d.maquinas;
  document.getElementById('d-ordenes').textContent=d.ordenes;
  document.getElementById('d-stock').textContent=d.stock_bajo;
  document.getElementById('d-mant').textContent=d.mant_vencidos;
  // Billing KPIs
  const fmt = v => '$'+Number(v||0).toLocaleString('es-CL');
  if(document.getElementById('d-cobrado'))  document.getElementById('d-cobrado').textContent  = fmt(d.cobrado_mes);
  if(document.getElementById('d-pendiente')) document.getElementById('d-pendiente').textContent = fmt(d.total_pendiente);
  if(document.getElementById('d-compras')) {
    document.getElementById('d-compras').textContent     = fmt(d.total_compras||0);
    document.getElementById('d-compras-sub').textContent = `${d.num_compras||0} compra${(d.num_compras||0)!==1?'s':''} registrada${(d.num_compras||0)!==1?'s':''}`;
  }
  if(document.getElementById('d-gastos')) {
    document.getElementById('d-gastos').textContent     = fmt(d.gastos_mes||0);
    document.getElementById('d-gastos-sub').textContent = `${d.num_gastos_mes||0} gasto${(d.num_gastos_mes||0)!==1?'s':''} este mes`;
  }
  const bf=document.getElementById('badge-fac');
  if(bf){if((d.fac_vencidas||0)>0){bf.style.display='';bf.textContent=d.fac_vencidas;}else bf.style.display='none';}
  document.getElementById('badge-ordenes').textContent=d.ordenes;
  const bm=document.getElementById('badge-mant');
  const u=d.mant_vencidos+d.mant_hoy;
  if(u>0){bm.style.display='';bm.textContent=u;}else bm.style.display='none';
  const stMap={'Pendiente':'amber','En Proceso':'blue','Completado':'green','Entregado':'gray'};
  const tbO=document.getElementById('dash-ordenes');
  tbO.innerHTML=d.ultimas_ordenes.length?d.ultimas_ordenes.map(o=>`<tr><td class="id-code" style="color:var(--accent)">${o.id}</td><td>${o.cliente||'-'}</td><td><span class="tag tag-${stMap[o.estado]||'gray'}">${o.estado}</span></td><td>${o.tecnico||'-'}</td></tr>`).join(''):'<tr><td colspan="4" style="text-align:center;color:var(--text3);padding:20px;">Sin órdenes</td></tr>';
  const tbS=document.getElementById('dash-stock');
  tbS.innerHTML=d.stock_critico.length?d.stock_critico.map(r=>`<tr><td>${r.descripcion}</td><td>${r.stock}</td><td><span class="tag tag-${r.stock<=0?'red':'amber'}">${r.stock<=0?'Sin Stock':'Bajo'}</span></td></tr>`).join(''):'<tr><td colspan="3" style="text-align:center;color:var(--text3);padding:20px;">Stock normal ✓</td></tr>';
}

// ── CLIENTES ──────────────────────────────────────────────────
async function loadClientes() {
  const data = await apiFetch('clientes.php'); if(!data) return;
  cache.clientes = data;
  const tb = document.getElementById('tb-clientes');
  tb.innerHTML = data.length ? data.map(c=>`<tr>
    <td class="id-code">${c.id}</td><td><strong>${c.empresa}</strong></td>
    <td class="id-code">${c.rut||'-'}</td><td>${c.contacto||'-'}</td><td>${c.telefono||'-'}</td><td>${c.ciudad||'-'}</td>
    <td class="actions">
      ${tienePermiso('clientes','editar')?`<button class="btn btn-blue btn-sm" onclick="editCliente('${c.id}')">✏️</button>`:''}
      ${tienePermiso('clientes','eliminar')?`<button class="btn btn-danger btn-sm" onclick="deleteCliente('${c.id}')">🗑</button>`:''}
    </td></tr>`).join('') : '<tr><td colspan="7"><div class="empty-state"><div class="empty-icon">👥</div><p>No hay clientes</p></div></td></tr>';
}
async function saveCliente() {
  const empresa=val('c-empresa'); if(!empresa) return alert('Empresa es obligatorio');
  const id=val('c-id');
  const body={empresa,rut:val('c-rut'),contacto:val('c-contacto'),telefono:val('c-telefono'),email:val('c-email'),ciudad:val('c-ciudad'),rubro:val('c-rubro'),direccion:val('c-direccion'),notas:val('c-notas')};
  if(id) await apiFetch(`clientes.php?id=${id}`,'PUT',body);
  else await apiFetch('clientes.php','POST',body);
  closeModal('modal-cliente'); resetForm(['c-id','c-empresa','c-rut','c-contacto','c-telefono','c-email','c-ciudad','c-rubro','c-direccion','c-notas']); loadClientes(); showToast('✅ Cliente guardado');
}
function editCliente(id) {
  const c=cache.clientes.find(x=>x.id===id); if(!c) return;
  ['id','empresa','rut','contacto','telefono','email','ciudad','rubro','direccion','notas'].forEach(f=>setVal('c-'+f,c[f]));
  openModal('modal-cliente');
}
async function deleteCliente(id) {
  if(!confirm('¿Eliminar este cliente?')) return;
  await apiFetch(`clientes.php?id=${id}`,'DELETE'); loadClientes(); showToast('🗑 Cliente eliminado');
}

// ── MAQUINARIA ────────────────────────────────────────────────
async function loadMaquinaria() {
  const data=await apiFetch('maquinaria.php'); if(!data) return;
  cache.maquinaria=data;
  const estadoMap={'Operativo':'green','En Reparación':'amber','Fuera de Servicio':'red','En Revisión':'blue'};
  const tb=document.getElementById('tb-maquinaria');
  tb.innerHTML=data.length?data.map(m=>{
    const fotos=m.fotos||[];
    const thumb=fotos.length?`<img src="${fotos[0]}" style="width:34px;height:34px;object-fit:cover;border-radius:4px;border:1px solid var(--border);cursor:pointer;" onclick="verFotos('${m.id}')">`:`<div style="width:34px;height:34px;border-radius:4px;border:1px dashed var(--border);display:flex;align-items:center;justify-content:center;font-size:14px;color:var(--text3);">📷</div>`;
    return `<tr>
      <td class="id-code">${m.id}</td>
      <td style="display:flex;align-items:center;gap:10px;">${thumb}<div><strong>${m.nombre}</strong><br><span style="color:var(--text3);font-size:12px;">${m.modelo||''}</span></div></td>
      <td>${m.marca||'-'}</td><td class="id-code">${m.serie||'-'}</td>
      <td>${m.cliente_nombre||'-'}</td><td>${m.tipo||'-'}</td>
      <td><span class="tag tag-${estadoMap[m.estado]||'gray'}">${m.estado}</span></td>
      <td class="actions">
        ${fotos.length?`<button class="btn btn-secondary btn-sm" onclick="verFotos('${m.id}')">📷${fotos.length}</button>`:''}
        ${tienePermiso('maquinaria','editar')?`<button class="btn btn-blue btn-sm" onclick="editMaquina('${m.id}')">✏️</button>`:''}
        ${tienePermiso('maquinaria','eliminar')?`<button class="btn btn-danger btn-sm" onclick="deleteMaquina('${m.id}')">🗑</button>`:''}
      </td></tr>`;
  }).join(''):'<tr><td colspan="8"><div class="empty-state"><div class="empty-icon">⚙️</div><p>No hay maquinaria</p></div></td></tr>';
}
function handleFotoSelect(e){processFotos(Array.from(e.target.files));}
function handleFotoDrop(e){e.preventDefault();document.getElementById('foto-dropzone').style.borderColor='var(--border)';processFotos(Array.from(e.dataTransfer.files).filter(f=>f.type.startsWith('image/')));}
function processFotos(files){
  const rem=5-fotosTemp.length; if(rem<=0)return showToast('⚠️ Máximo 5 fotos');
  files.slice(0,rem).forEach(f=>{const r=new FileReader();r.onload=e=>{fotosTemp.push(e.target.result);renderFotoPreview();};r.readAsDataURL(f);});
}
function renderFotoPreview(){
  document.getElementById('foto-preview-grid').innerHTML=fotosTemp.map((s,i)=>`<div style="position:relative;aspect-ratio:1;border-radius:6px;overflow:hidden;border:1px solid var(--border);"><img src="${s}" style="width:100%;height:100%;object-fit:cover;"><button onclick="fotosTemp.splice(${i},1);renderFotoPreview()" style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,.7);border:none;color:white;border-radius:50%;width:22px;height:22px;cursor:pointer;">✕</button></div>`).join('');
  document.getElementById('foto-dropzone').style.display=fotosTemp.length>=5?'none':'block';
}
function verFotos(id){
  const m=cache.maquinaria.find(x=>x.id===id); if(!m) return;
  const fotos=m.fotos||[]; if(!fotos.length) return showToast('Sin fotos');
  document.getElementById('fotos-titulo').textContent=`📷 ${m.nombre} — ${fotos.length} foto(s)`;
  document.getElementById('fotos-grid').innerHTML=fotos.map(s=>`<div style="border-radius:8px;overflow:hidden;border:1px solid var(--border);aspect-ratio:4/3;"><img src="${s}" style="width:100%;height:100%;object-fit:cover;cursor:zoom-in;" onclick="window.open('${s}','_blank')"></div>`).join('');
  openModal('modal-fotos');
}
async function saveMaquina(){
  const nombre=val('m-nombre'); if(!nombre) return alert('Nombre es obligatorio');
  const id=val('m-id');
  const body={nombre,marca:val('m-marca'),modelo:val('m-modelo'),serie:val('m-serie'),anio:val('m-anio'),tipo:val('m-tipo'),cliente_id:val('m-cliente_id'),potencia:val('m-potencia'),voltaje:val('m-voltaje'),horas_uso:val('m-horas_uso'),ubicacion:val('m-ubicacion'),estado:val('m-estado'),notas:val('m-notas'),fotos:fotosTemp};
  if(id) await apiFetch(`maquinaria.php?id=${id}`,'PUT',body);
  else await apiFetch('maquinaria.php','POST',body);
  fotosTemp=[]; closeModal('modal-maquina'); loadMaquinaria(); showToast('✅ Máquina guardada');
}
function editMaquina(id){
  const m=cache.maquinaria.find(x=>x.id===id); if(!m) return;
  populateSelect('m-cliente_id',cache.clientes,'id','empresa','Sin asignar');
  ['id','nombre','marca','modelo','serie','anio','tipo','potencia','voltaje','ubicacion','estado','notas'].forEach(f=>setVal('m-'+f,m[f]));
  setVal('m-cliente_id',m.cliente_id); setVal('m-horas_uso',m.horas_uso);
  fotosTemp=[...(m.fotos||[])]; renderFotoPreview();
  openModal('modal-maquina');
}
async function deleteMaquina(id){if(!confirm('¿Eliminar?'))return;await apiFetch(`maquinaria.php?id=${id}`,'DELETE');loadMaquinaria();showToast('🗑 Máquina eliminada');}

// ── REPUESTOS ─────────────────────────────────────────────────
function switchRepTab(tab){
  document.getElementById('rep-vista-inventario').style.display = tab==='inventario'?'':'none';
  document.getElementById('rep-vista-compras').style.display    = tab==='compras'?'':'none';
  document.getElementById('tab-inv').classList.toggle('active',    tab==='inventario');
  document.getElementById('tab-compras').classList.toggle('active', tab==='compras');
  if(tab==='compras') loadCompras();
}
function abrirNuevoRepuesto(){
  setVal('r-editing-id','');
  document.getElementById('rep-modal-title').textContent='Nuevo Repuesto';
  document.getElementById('r-id').readOnly=false;
  document.getElementById('r-id').style.opacity='1';
  document.getElementById('r-stock').readOnly=false;
  document.getElementById('r-stock').style.opacity='1';
  document.getElementById('r-stock-label').textContent='Stock Inicial';
  document.getElementById('r-stock-hint').style.display='none';
  setVal('r-id',''); setVal('r-descripcion',''); setVal('r-marca',''); setVal('r-referencia','');
  setVal('r-stock','0'); setVal('r-stock_minimo','5'); setVal('r-precio','0');
  setVal('r-categoria','Filtros'); setVal('r-unidad','Unidad'); setVal('r-compatible_con',''); setVal('r-bodega','');
  openModal('modal-repuesto');
}
async function loadRepuestos(){
  const data=await apiFetch('repuestos.php'); if(!data) return;
  cache.repuestos=data;
  const tb=document.getElementById('tb-repuestos');
  tb.innerHTML=data.length?data.map(r=>{
    const stockReal = r.stock_total !== undefined ? r.stock_total : r.stock;
    const pct=Math.min(100,(stockReal/Math.max(r.stock_minimo*3,1))*100);
    const est=r.stock<=0?['red','Sin Stock']:r.stock<=r.stock_minimo?['amber','Bajo']:['green','OK'];
    return `<tr><td class="id-code">${r.id}</td>
    <td><strong>${r.descripcion}</strong><br><span style="font-size:11px;color:var(--text3);">${r.marca||''} ${r.referencia||''}</span></td>
    <td>${r.categoria}</td>
    <td><span class="tag ${r.condicion==='usado'?'tag-amber':'tag-green'}" style="font-size:11px;">${r.condicion==='usado'?'Usado':'Nuevo'}</span></td>
    <td><strong style="color:${stockReal<=r.stock_minimo?'var(--danger)':'var(--text)'}">${stockReal}</strong> ${r.unidad}
      <div class="progress-bar"><div class="progress-fill" style="width:${pct}%;background:${r.stock<=r.stock_minimo?'var(--danger)':'var(--success)'}"></div></div></td>
    <td>${(r.bodegas&&r.bodegas.length)?r.bodegas.map(b=>`<div style="font-size:11px;margin-bottom:2px;"><span style="color:var(--text3);">${b.bodega_nombre}:</span> <strong>${b.stock}</strong></div>`).join(''):'<span style="color:var(--text3);font-size:11px;">Sin bodega</span>'}</td>
    <td style="font-weight:600;">${fmt$(r.precio)}</td>
    <td><span class="tag tag-${est[0]}">${est[1]}</span></td>
    <td class="actions">
      <button class="btn btn-green btn-sm" title="Registrar compra" onclick="abrirCompraRapida('${r.id}')">📦</button>
      ${tienePermiso('repuestos','editar')?`<button class="btn btn-blue btn-sm" onclick="editRepuesto('${r.id}')">✏️</button>`:''}
      ${tienePermiso('repuestos','eliminar')?`<button class="btn btn-danger btn-sm" onclick="deleteRepuesto('${r.id}')">🗑</button>`:''}
    </td></tr>`;
  }).join(''):'<tr><td colspan="8"><div class="empty-state"><div class="empty-icon">🔩</div><p>No hay repuestos</p></div></td></tr>';
}
async function saveRepuesto(){
  const cod=val('r-id'),desc=val('r-descripcion'); if(!cod||!desc) return alert('Código y descripción requeridos');
  const editId=val('r-editing-id');
  const body={id:cod,descripcion:desc,categoria:val('r-categoria'),marca:val('r-marca'),referencia:val('r-referencia'),
    stock_minimo:val('r-stock_minimo'),precio:val('r-precio'),unidad:val('r-unidad'),compatible_con:val('r-compatible_con'),condicion:val('r-condicion'),bodega:val('r-bodega')};
  // stock solo se envía al crear
  if(!editId) body.stock=val('r-stock');
  if(editId) await apiFetch(`repuestos.php?id=${editId}`,'PUT',body);
  else await apiFetch('repuestos.php','POST',body);
  closeModal('modal-repuesto'); loadRepuestos(); showToast('✅ Repuesto guardado');
}
function editRepuesto(id){
  const r=cache.repuestos.find(x=>x.id===id); if(!r) return;
  document.getElementById('rep-modal-title').textContent='Editar Repuesto';
  setVal('r-editing-id',id);
  // Código: solo lectura al editar
  document.getElementById('r-id').value=r.id;
  document.getElementById('r-id').readOnly=true;
  document.getElementById('r-id').style.opacity='0.5';
  // Stock: solo lectura al editar
  document.getElementById('r-stock').value=r.stock;
  document.getElementById('r-stock').readOnly=true;
  document.getElementById('r-stock').style.opacity='0.5';
  document.getElementById('r-stock-label').textContent='Stock Actual (solo lectura)';
  document.getElementById('r-stock-hint').style.display='block';
  setVal('r-descripcion',r.descripcion); setVal('r-categoria',r.categoria);
  setVal('r-marca',r.marca); setVal('r-referencia',r.referencia);
  setVal('r-stock_minimo',r.stock_minimo); setVal('r-precio',r.precio);
  setVal('r-unidad',r.unidad); setVal('r-compatible_con',r.compatible_con); setVal('r-bodega',r.bodega||'');
  setVal('r-condicion', r.condicion||'nuevo');
  openModal('modal-repuesto');
}
async function deleteRepuesto(id){if(!confirm('¿Eliminar?'))return;await apiFetch(`repuestos.php?id=${id}`,'DELETE');loadRepuestos();showToast('🗑 Repuesto eliminado');}

// ── COMPRAS ───────────────────────────────────────────────────
function abrirCompraGeneral(){
  populateSelect('compra-repuesto_id', cache.repuestos, 'id', 'descripcion', 'Seleccionar repuesto...');
  document.getElementById('compra-fecha').value = new Date().toISOString().split('T')[0];
  setVal('compra-repuesto_id',''); setVal('compra-proveedor','');
  setVal('compra-cantidad','1'); setVal('compra-precio_unit','0');
  setVal('compra-observaciones','');
  calcTotalCompra();
  openModal('modal-compra');
}
function abrirCompraRapida(repuestoId){
  populateSelect('compra-repuesto_id', cache.repuestos, 'id', 'descripcion', 'Seleccionar repuesto...');
  setVal('compra-repuesto_id', repuestoId);
  document.getElementById('compra-fecha').value = new Date().toISOString().split('T')[0];
  setVal('compra-proveedor',''); setVal('compra-cantidad','1');
  // Pre-cargar precio del repuesto
  const r = cache.repuestos.find(x=>x.id===repuestoId);
  setVal('compra-precio_unit', r?.precio||'0');
  setVal('compra-observaciones','');
  calcTotalCompra();
  openModal('modal-compra');
}
function calcTotalCompra(){
  const cant  = parseFloat(val('compra-cantidad')||0);
  const precio= parseFloat(val('compra-precio_unit')||0);
  document.getElementById('compra-total-display').textContent = fmt$(cant*precio);
}
async function guardarCompra(){
  const repId = val('compra-repuesto_id');
  const cant  = parseFloat(val('compra-cantidad'));
  if(!repId)    return alert('Selecciona un repuesto');
  if(!cant||cant<1) return alert('La cantidad debe ser mayor a 0');
  const body = {
    repuesto_id: repId,
    bodega_id:   val('compra-bodega_id'),
    fecha:       val('compra-fecha'),
    proveedor:   val('compra-proveedor'),
    cantidad:    cant,
    precio_unit: parseFloat(val('compra-precio_unit')||0),
    observaciones: val('compra-observaciones')
  };
  const res = await apiFetch('repuestos.php?action=compra','POST',body);
  if(res?.ok){
    closeModal('modal-compra');
    loadRepuestos();
    showToast(`✅ Compra registrada — Stock actualizado (+${cant})`);
  }
}
async function loadCompras(){
  const data = await apiFetch('repuestos.php?action=compras'); if(!data) return;
  const tb = document.getElementById('tb-compras');
  tb.innerHTML = data.length ? data.map(c=>`<tr>
    <td>${c.fecha||'-'}</td>
    <td><strong>${c.repuesto_descripcion||c.repuesto_id}</strong><br><span class="id-code" style="font-size:11px;">${c.repuesto_id}</span></td>
    <td>${c.proveedor||'-'}</td>
    <td style="font-weight:700;color:var(--success);">+${c.cantidad}</td>
    <td>${fmt$(c.precio_unit)}</td>
    <td style="font-weight:700;color:var(--accent);">${fmt$(c.cantidad*c.precio_unit)}</td>
    <td style="font-size:12px;color:var(--text3);">${c.observaciones||'-'}</td>
  </tr>`).join('')
  : '<tr><td colspan="7"><div class="empty-state"><div class="empty-icon">📦</div><p>Sin compras registradas</p></div></td></tr>';
}

// ── BODEGAS ───────────────────────────────────────────────────
async function loadBodegas() {
  const data = await apiFetch('bodegas.php'); if (!data) return;
  cache.bodegas = data;
  const tb = document.getElementById('tb-bodegas');
  if (!tb) return;
  if (!data.length) { tb.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--text3);padding:40px;">No hay bodegas registradas</td></tr>'; return; }
  tb.innerHTML = data.map(b => `
    <tr>
      <td class="id-code">${b.id}</td>
      <td><strong>${b.nombre}</strong></td>
      <td>${b.ubicacion||'—'}</td>
      <td>${b.descripcion||'—'}</td>
      <td><span class="badge ${b.activa=='1'?'badge-green':'badge-gray'}">${b.activa=='1'?'Activa':'Inactiva'}</span></td>
      <td>
        <button class="btn btn-xs" onclick="editarBodega('${b.id}')">✏️</button>
        <button class="btn btn-xs btn-danger" onclick="deleteBodega('${b.id}')">🗑</button>
      </td>
    </tr>`).join('');
  // Actualizar selectores de bodega en formularios
  poblarSelectBodegas();
}

function poblarSelectBodegas() {
  const bodegas = (cache.bodegas||[]).filter(b => b.activa == '1');
  const opts = '<option value="">— Sin bodega —</option>' + bodegas.map(b => `<option value="${b.id}">${b.nombre}${b.ubicacion?' — '+b.ubicacion:''}</option>`).join('');
  const selects = ['r-bodega-id', 'compra-bodega_id'];
  selects.forEach(id => { const el = document.getElementById(id); if (el) el.innerHTML = opts; });
}

function bodegaModalNuevo() {
  document.getElementById('bodega-modal-title').textContent = 'Nueva Bodega';
  ['bod-id','bod-nombre','bod-ubicacion','bod-descripcion'].forEach(id => setVal(id,''));
  setVal('bod-activa','1');
}

async function editarBodega(id) {
  const b = (cache.bodegas||[]).find(x => x.id === id); if (!b) return;
  document.getElementById('bodega-modal-title').textContent = 'Editar Bodega';
  setVal('bod-id', b.id); setVal('bod-nombre', b.nombre);
  setVal('bod-ubicacion', b.ubicacion||''); setVal('bod-descripcion', b.descripcion||'');
  setVal('bod-activa', b.activa);
  openModal('modal-bodega');
}

async function saveBodega() {
  const nombre = val('bod-nombre').trim();
  if (!nombre) { showToast('❌ El nombre es obligatorio','error'); return; }
  const body = { nombre, ubicacion: val('bod-ubicacion'), descripcion: val('bod-descripcion'), activa: val('bod-activa') };
  const id = val('bod-id');
  const res = await apiFetch(id ? `bodegas.php?id=${id}` : 'bodegas.php', id ? 'PUT' : 'POST', body);
  if (!res) return;
  closeModal('modal-bodega'); loadBodegas(); showToast('✅ Bodega guardada');
}

async function deleteBodega(id) {
  if (!confirm('¿Eliminar esta bodega?')) return;
  const res = await apiFetch(`bodegas.php?id=${id}`, 'DELETE');
  if (res?.error) { showToast('❌ ' + res.error, 'error'); return; }
  loadBodegas(); showToast('🗑 Bodega eliminada');
}

// ── TÉCNICOS ──────────────────────────────────────────────────
async function loadTecnicos(){
  const data=await apiFetch('tecnicos.php'); if(!data) return;
  cache.tecnicos=data;
  const estMap={'Disponible':'green','En Servicio':'blue','Vacaciones':'amber','Inactivo':'gray'};
  const tb=document.getElementById('tb-tecnicos');
  tb.innerHTML=data.length?data.map(t=>{
    const ini=((t.nombre[0]||'')+(t.apellido?t.apellido[0]:'')).toUpperCase();
    return `<tr><td class="id-code">${t.id}</td>
    <td style="display:flex;align-items:center;gap:10px;"><div class="avatar">${ini}</div><div><strong>${t.nombre} ${t.apellido||''}</strong><br><span style="font-size:11px;color:var(--text3);">${t.nivel}</span></div></td>
    <td>${t.especialidad}</td><td>${t.telefono||'-'}</td>
    <td><span class="tag tag-${estMap[t.estado]||'gray'}">${t.estado}</span></td>
    <td class="actions">${tienePermiso('tecnicos','editar')?`<button class="btn btn-blue btn-sm" onclick="editTecnico('${t.id}')">✏️</button>`:''}${tienePermiso('tecnicos','eliminar')?`<button class="btn btn-danger btn-sm" onclick="deleteTecnico('${t.id}')">🗑</button>`:''}</td></tr>`;
  }).join(''):'<tr><td colspan="6"><div class="empty-state"><div class="empty-icon">👷</div><p>No hay técnicos</p></div></td></tr>';
}
async function saveTecnico(){
  const nombre=val('t-nombre'); if(!nombre) return alert('Nombre requerido');
  const id=val('t-id');
  const body={nombre,apellido:val('t-apellido'),rut:val('t-rut'),especialidad:val('t-especialidad'),telefono:val('t-telefono'),email:val('t-email'),estado:val('t-estado'),nivel:val('t-nivel'),notas:val('t-notas')};
  if(id) await apiFetch(`tecnicos.php?id=${id}`,'PUT',body); else await apiFetch('tecnicos.php','POST',body);
  closeModal('modal-tecnico'); resetForm(['t-id','t-nombre','t-apellido','t-rut','t-telefono','t-email','t-notas']); loadTecnicos(); showToast('✅ Técnico guardado');
}
function editTecnico(id){
  const t=cache.tecnicos.find(x=>x.id===id); if(!t) return;
  ['id','nombre','apellido','rut','especialidad','telefono','email','estado','nivel','notas'].forEach(f=>setVal('t-'+f,t[f]));
  openModal('modal-tecnico');
}
async function deleteTecnico(id){if(!confirm('¿Eliminar?'))return;await apiFetch(`tecnicos.php?id=${id}`,'DELETE');loadTecnicos();showToast('🗑 Técnico eliminado');}

// ── ÓRDENES ───────────────────────────────────────────────────
async function loadOrdenes(filtroEstado=''){
  const data=await apiFetch('ordenes.php'); if(!data) return;
  cache.ordenes=data;
  const filtered=filtroEstado?data.filter(o=>o.estado===filtroEstado):data;
  const stMap={'Pendiente':'amber','En Proceso':'blue','Completado':'green','Entregado':'gray'};
  const tb=document.getElementById('tb-ordenes');
  tb.innerHTML=filtered.length?filtered.map(o=>`<tr>
    <td class="id-code" style="color:var(--accent);font-weight:700;">${o.id}</td>
    <td>${o.fecha||'-'}</td><td>${o.cliente_nombre||'-'}</td><td>${o.maquina_nombre||'-'}</td>
    <td>${o.tecnico_nombre?.trim()||'-'}</td><td>${o.tipo}</td>
    <td><span class="tag tag-${stMap[o.estado]||'gray'}">${o.estado}</span></td>
    <td class="actions">
      <button class="btn btn-green btn-sm" title="Avanzar estado" onclick="nextStatus('${o.id}')">↻</button>
      ${tienePermiso('ordenes','editar')?`<button class="btn btn-blue btn-sm" title="Editar orden" onclick="editarOrden('${o.id}')">✏️</button>`:''}
      <button class="btn btn-secondary btn-sm" title="Ver reporte" onclick="verReporte('${o.id}')">📄</button>
      ${tienePermiso('ordenes','eliminar')?`<button class="btn btn-danger btn-sm" title="Eliminar" onclick="deleteOrden('${o.id}')">🗑</button>`:''}
    </td></tr>`).join(''):'<tr><td colspan="8"><div class="empty-state"><div class="empty-icon">📋</div><p>No hay órdenes</p></div></td></tr>';
}
function loadMaquinasByCliente(){
  const cid=val('o-cliente_id');
  const maqsFiltradas=cid?cache.maquinaria.filter(m=>m.cliente_id===cid):cache.maquinaria;
  populateSelect('o-maquina_id',maqsFiltradas,'id','nombre','Seleccionar...');
}
let repOrdenItems=[];

function recalcOrdenTotal(){
  const totalRep = repOrdenItems.reduce((s,r)=>{
    const rep = cache.repuestos.find(x=>x.id===r.repuesto_id)||{};
    const precio = parseFloat(r.precio_unit ?? rep.precio ?? 0);
    const cant   = parseInt(r.cantidad)||1;
    return s + precio*cant;
  }, 0);
  const manoObra = parseFloat(val('o-mano_obra')||0);
  const elRep = document.getElementById('o-total-rep-display');
  const elTot = document.getElementById('o-total-display');
  if(elRep) elRep.textContent = fmt$(totalRep);
  if(elTot) elTot.textContent = fmt$(totalRep + manoObra);
}

function addRepOrden(repuesto_id='', cantidad=1, precio_unit=null){
  const id='ri_'+Date.now();
  repOrdenItems.push({id, repuesto_id, cantidad, precio_unit});
  const div=document.createElement('div');
  div.id=id;
  div.style.cssText='display:grid;grid-template-columns:2fr 1fr 110px auto;gap:8px;margin-bottom:8px;align-items:center;';
  const repOptions = cache.repuestos.map(r=>`<option value="${r.id}" ${r.id===repuesto_id?'selected':''}>${r.descripcion} (Stock: ${r.stock})</option>`).join('');
  const rep = cache.repuestos.find(x=>x.id===repuesto_id)||{};
  const precioInicial = precio_unit !== null ? precio_unit : (rep.precio||0);
  const subtotal = precioInicial * cantidad;
  div.innerHTML=`
    <select style="background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:8px;color:var(--text);font-size:12px;outline:none;" onchange="updateRepOrden('${id}','repuesto_id',this.value)">
      <option value="">Seleccionar repuesto...</option>${repOptions}
    </select>
    <input type="number" value="${cantidad}" min="1" style="background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:8px;color:var(--text);font-size:12px;outline:none;text-align:right;" oninput="updateRepOrden('${id}','cantidad',this.value)">
    <div id="sub_${id}" style="font-size:12px;font-weight:700;color:var(--accent);text-align:right;padding:8px;">${fmt$(subtotal)}</div>
    <button onclick="removeRepOrden('${id}')" style="background:var(--bg3);border:1px solid var(--border);border-radius:6px;padding:8px 10px;cursor:pointer;color:var(--danger);font-size:14px;">✕</button>`;
  document.getElementById('lista-rep-orden').appendChild(div);
  recalcOrdenTotal();
}

function updateRepOrden(id, field, value){
  const r = repOrdenItems.find(x=>x.id===id); if(!r) return;
  r[field] = value;
  if(field==='repuesto_id'){
    const rep = cache.repuestos.find(x=>x.id===value)||{};
    r.precio_unit = rep.precio||0;
  }
  const precio = parseFloat(r.precio_unit||0);
  const cant   = parseInt(r.cantidad)||1;
  const el = document.getElementById('sub_'+id);
  if(el) el.textContent = fmt$(precio*cant);
  recalcOrdenTotal();
}

function removeRepOrden(id){
  repOrdenItems = repOrdenItems.filter(x=>x.id!==id);
  const el=document.getElementById(id); if(el) el.remove();
  recalcOrdenTotal();
}

// Abrir modal para NUEVA orden
function abrirNuevaOrden(){
  setVal('o-editing-id','');
  document.getElementById('orden-modal-title').textContent='Nueva Orden de Servicio';
  document.getElementById('o-fecha').value = new Date().toISOString().split('T')[0];
  setVal('o-tipo','Mantenimiento Preventivo'); setVal('o-prioridad','Normal');
  setVal('o-estado','Pendiente'); setVal('o-falla',''); setVal('o-diagnostico','');
  setVal('o-trabajos',''); setVal('o-horas','0'); setVal('o-mano_obra','0');
  setVal('o-fecha_entrega',''); setVal('o-observaciones','');
  repOrdenItems=[]; document.getElementById('lista-rep-orden').innerHTML='';
  populateSelect('o-cliente_id', cache.clientes, 'id', 'empresa', 'Seleccionar...');
  populateSelect('o-tecnico_id', cache.tecnicos, 'id', t=>`${t.nombre} ${t.apellido||''}`, 'Sin asignar');
  populateSelect('o-maquina_id', cache.maquinaria, 'id', 'nombre', 'Seleccionar...');
  recalcOrdenTotal();
  openModal('modal-orden');
}

// Abrir modal para EDITAR orden existente
async function editarOrden(id){
  const o = cache.ordenes.find(x=>x.id===id); if(!o) return;
  document.getElementById('orden-modal-title').textContent = `Editar Orden ${id}`;
  setVal('o-editing-id', id);
  // Poblar selects primero
  populateSelect('o-cliente_id', cache.clientes, 'id', 'empresa', 'Seleccionar...');
  populateSelect('o-tecnico_id', cache.tecnicos, 'id', t=>`${t.nombre} ${t.apellido||''}`, 'Sin asignar');
  // Máquinas del cliente
  const maqsFiltradas = o.cliente_id ? cache.maquinaria.filter(m=>m.cliente_id===o.cliente_id) : cache.maquinaria;
  populateSelect('o-maquina_id', maqsFiltradas, 'id', 'nombre', 'Seleccionar...');
  // Llenar campos
  setVal('o-fecha',          o.fecha);
  setVal('o-tipo',           o.tipo);
  setVal('o-cliente_id',     o.cliente_id);
  setVal('o-maquina_id',     o.maquina_id);
  setVal('o-tecnico_id',     o.tecnico_id||'');
  setVal('o-prioridad',      o.prioridad);
  setVal('o-falla',          o.falla||'');
  setVal('o-diagnostico',    o.diagnostico||'');
  setVal('o-trabajos',       o.trabajos||'');
  setVal('o-horas',          o.horas||0);
  setVal('o-mano_obra',      o.mano_obra||0);
  setVal('o-estado',         o.estado);
  setVal('o-fecha_entrega',  o.fecha_entrega||'');
  setVal('o-observaciones',  o.observaciones||'');
  // Cargar repuestos existentes
  repOrdenItems=[]; document.getElementById('lista-rep-orden').innerHTML='';
  (o.repuestos||[]).forEach(r => addRepOrden(r.repuesto_id, parseInt(r.cantidad)||1, parseFloat(r.precio_unit)||0));
  recalcOrdenTotal();
  openModal('modal-orden');
}

async function saveOrden(){
  const editId = val('o-editing-id');
  const cid=val('o-cliente_id'), mid=val('o-maquina_id');
  if(!cid||!mid) return alert('Cliente y máquina son requeridos');
  const reps = repOrdenItems.filter(r=>r.repuesto_id).map(r=>{
    const rep = cache.repuestos.find(x=>x.id===r.repuesto_id)||{};
    return {repuesto_id:r.repuesto_id, descripcion:rep.descripcion||r.repuesto_id, cantidad:parseInt(r.cantidad)||1, precio_unit:parseFloat(r.precio_unit ?? rep.precio ?? 0)};
  });
  const totalRep   = reps.reduce((s,r)=>s+(r.precio_unit*r.cantidad),0);
  const manoObra   = parseFloat(val('o-mano_obra')||0);
  const body = {
    fecha:val('o-fecha'), tipo:val('o-tipo'), cliente_id:cid, maquina_id:mid,
    tecnico_id:val('o-tecnico_id'), prioridad:val('o-prioridad'), falla:val('o-falla'),
    diagnostico:val('o-diagnostico'), trabajos:val('o-trabajos'), horas:val('o-horas'),
    mano_obra:manoObra, total_repuestos:totalRep, estado:val('o-estado'),
    fecha_entrega:val('o-fecha_entrega'), observaciones:val('o-observaciones'), repuestos:reps
  };
  if(editId){
    await apiFetch(`ordenes.php?id=${editId}`, 'PUT', body);
    showToast(`✅ Orden ${editId} actualizada`);
  } else {
    await apiFetch('ordenes.php', 'POST', body);
    showToast('✅ Orden creada');
  }
  repOrdenItems=[]; document.getElementById('lista-rep-orden').innerHTML='';
  closeModal('modal-orden'); loadOrdenes();
}
async function nextStatus(id){
  const o=cache.ordenes.find(x=>x.id===id); if(!o) return;
  const states=['Pendiente','En Proceso','Completado','Entregado'];
  const next=states[(states.indexOf(o.estado)+1)%states.length];
  await apiFetch(`ordenes.php?id=${id}`,'PUT',{...o,estado:next}); loadOrdenes();
}
async function deleteOrden(id){if(!confirm('¿Eliminar?'))return;await apiFetch(`ordenes.php?id=${id}`,'DELETE');loadOrdenes();showToast('🗑 Orden eliminada');}

// ── MANTENIMIENTOS ────────────────────────────────────────────
async function loadMantenimientos(){
  const [data, hist] = await Promise.all([apiFetch('mantenimientos.php'), apiFetch('mantenimientos.php?action=historial')]);
  if(!data) return;
  cache.mantenimientos=data;
  const activos=data.filter(m=>m.estado!=='Completado');
  const vencidos=activos.filter(m=>m.dias_restantes<0);
  const hoyM=activos.filter(m=>m.dias_restantes==0);
  let alertHtml='';
  if(vencidos.length) alertHtml+=`<div class="alert-banner danger">⚠️ <strong>${vencidos.length} mantenimiento(s) vencido(s)</strong> — Atención inmediata requerida</div>`;
  if(hoyM.length) alertHtml+=`<div class="alert-banner warning">🔔 <strong>${hoyM.length} mantenimiento(s) programado(s) para HOY</strong></div>`;
  document.getElementById('mant-alertas').innerHTML=alertHtml;
  const colorMap={'-1':'var(--danger)','0':'var(--accent)','7':'var(--accent2)','365':'var(--success)'};
  const getColor=d=>d<0?'var(--danger)':d===0?'var(--accent)':d<=7?'var(--accent2)':'var(--success)';
  const priorColors={'Crítica':'var(--danger)','Alta':'var(--accent)','Normal':'var(--accent2)','Baja':'var(--text3)'};
  document.getElementById('mant-lista-content').innerHTML=activos.length?activos.sort((a,b)=>a.dias_restantes-b.dias_restantes).map(m=>`
    <div class="mant-card" style="border-left:4px solid ${getColor(parseInt(m.dias_restantes))};">
      <div style="font-size:28px;width:44px;text-align:center;flex-shrink:0;">🔧</div>
      <div class="mant-card-body">
        <div class="mant-card-title">${m.tipo} <span style="font-size:11px;font-weight:400;color:${priorColors[m.prioridad]||'var(--text3)'};">● ${m.prioridad}</span></div>
        <div class="mant-card-sub"><strong style="color:var(--text2);">${m.maquina_nombre||m.maquina_id}</strong> · ${m.id}${m.tecnico_nombre?.trim()?` · ${m.tecnico_nombre}`:''} ${m.periodo_dias>0?`· ⟳ cada ${m.periodo_dias}d`:''}</div>
      </div>
      <div class="mant-card-right">
        <div class="dias-badge" style="color:${getColor(parseInt(m.dias_restantes))};">${Math.abs(m.dias_restantes)}</div>
        <div style="font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:1px;margin-top:2px;">${m.dias_restantes<0?'días vencido':m.dias_restantes==0?'HOY':'días'}</div>
        <div style="margin-top:8px;display:flex;gap:4px;justify-content:flex-end;">
          <button class="btn btn-green btn-sm" onclick="abrirEjecutar('${m.id}')">✓</button>
          ${tienePermiso('mantenimientos','eliminar')?`<button class="btn btn-danger btn-sm" onclick="deleteMant('${m.id}')">🗑</button>`:''}
        </div>
        <div style="font-size:11px;color:var(--text3);margin-top:4px;">${m.fecha}</div>
      </div>
    </div>`).join(''):'<div class="empty-state"><div class="empty-icon">🗓️</div><p>No hay mantenimientos programados</p></div>';
  // Sidebar
  const proximos=activos.filter(m=>m.dias_restantes<=30).sort((a,b)=>a.dias_restantes-b.dias_restantes).slice(0,8);
  document.getElementById('mant-sidebar').innerHTML=proximos.length?'<div class="timeline">'+proximos.map(m=>`<div class="tl-item"><div class="tl-dot" style="border-color:${getColor(parseInt(m.dias_restantes))};"></div><div class="tl-content"><div class="tl-date">${m.fecha} · ${m.dias_restantes<0?'Vencido':m.dias_restantes==0?'HOY':m.dias_restantes+'d'}</div><div style="font-weight:600;font-size:12px;">${m.tipo}</div><div style="font-size:11px;color:var(--text3);">${m.maquina_nombre||''}</div></div></div>`).join('')+'</div>':'<p style="color:var(--text3);font-size:13px;">Sin mantenimientos en 30 días</p>';
  // Historial
  if(hist){
    const resColors={'Completado sin novedad':'green','Completado con observaciones':'amber','Parcialmente completado':'red','Requiere revisión adicional':'red'};
    const tb=document.getElementById('tb-historial');
    tb.innerHTML=hist.length?hist.map(h=>`<tr><td class="id-code">${h.fecha_ejecucion}</td><td>${h.maquina_nombre||'-'}</td><td>${h.tipo}</td><td>${h.tecnico_nombre?.trim()||'-'}</td><td><span class="tag tag-${resColors[h.resultado]||'gray'}">${h.resultado||'-'}</span></td></tr>`).join(''):'<tr><td colspan="5" style="text-align:center;color:var(--text3);padding:30px;">Sin historial</td></tr>';
  }
  renderCalendario();
  renderSidebarProximos();
}
function abrirEjecutar(id){
  const m=cache.mantenimientos.find(x=>x.id===id); if(!m) return;
  currentMantId=id;
  document.getElementById('exec-info').innerHTML=`<strong>${m.tipo}</strong> — ${m.maquina_nombre||m.maquina_id}<br><span style="color:var(--text3);">${m.id} · Programado: ${m.fecha}</span>`;
  document.getElementById('exec-tecnico_id').value=m.tecnico_id||'';
  if(m.periodo_dias>0){
    const nx=new Date(m.fecha+'T00:00:00'); nx.setDate(nx.getDate()+parseInt(m.periodo_dias));
    document.getElementById('exec-proxima_fecha').value=nx.toISOString().split('T')[0];
  } else document.getElementById('exec-proxima_fecha').value='';
  document.getElementById('exec-horas_reales').value=m.duracion_hrs||0;
  document.getElementById('exec-notas').value='';
  openModal('modal-ejecutar');
}
async function ejecutarMant(){
  if(!currentMantId) return;
  const body={fecha_ejecucion:val('exec-fecha'),tecnico_id:val('exec-tecnico_id'),horas_reales:val('exec-horas_reales'),resultado:val('exec-resultado'),notas:val('exec-notas'),proxima_fecha:val('exec-proxima_fecha')};
  await apiFetch(`mantenimientos.php?id=${currentMantId}&action=ejecutar`,'POST',body);
  closeModal('modal-ejecutar'); loadMantenimientos(); showToast('✅ Mantenimiento ejecutado');
}
async function saveMantenimiento(){
  const maqId=val('mnt-maquina_id'),fecha=val('mnt-fecha'); if(!maqId||!fecha) return alert('Máquina y fecha requeridos');
  let periodo=parseInt(val('mnt-periodo_dias'))||0;
  if(val('mnt-periodo_dias')==='custom') periodo=parseInt(val('mnt-custom-dias'))||0;
  const body={maquina_id:maqId,tipo:val('mnt-tipo'),fecha,periodo_dias:periodo,tecnico_id:val('mnt-tecnico_id'),duracion_hrs:val('mnt-duracion_hrs'),prioridad:val('mnt-prioridad'),descripcion:val('mnt-descripcion'),materiales:val('mnt-materiales'),observaciones:val('mnt-observaciones')};
  await apiFetch('mantenimientos.php','POST',body);
  closeModal('modal-mantenimiento'); loadMantenimientos(); showToast('✅ Mantenimiento programado');
}
async function deleteMant(id){if(!confirm('¿Eliminar?'))return;await apiFetch(`mantenimientos.php?id=${id}`,'DELETE');loadMantenimientos();showToast('🗑 Mantenimiento eliminado');}
function switchMantTab(tab){
  ['lista','calendario','historial'].forEach(t=>{
    document.getElementById('tab-'+t).classList.toggle('active',t===tab);
    document.getElementById('mant-vista-'+t).style.display=t===tab?'block':'none';
  });
  if(tab==='calendario') renderCalendario();
}

// ── CALENDARIO ────────────────────────────────────────────────
function renderCalendario(){
  const months=['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
  document.getElementById('cal-month-title').textContent=`${months[calMonth]} ${calYear}`;
  const firstDay=new Date(calYear,calMonth,1).getDay();
  const daysInMonth=new Date(calYear,calMonth+1,0).getDate();
  const daysInPrev=new Date(calYear,calMonth,0).getDate();
  const todayStr=new Date().toISOString().split('T')[0];
  const byDate={};
  cache.mantenimientos.filter(m=>m.estado!=='Completado').forEach(m=>{if(!byDate[m.fecha])byDate[m.fecha]=[];byDate[m.fecha].push(m);});
  let html=''; let day=1,nextDay=1;
  const total=Math.ceil((firstDay+daysInMonth)/7)*7;
  for(let i=0;i<total;i++){
    if(i<firstDay){const d=daysInPrev-firstDay+i+1;html+=`<div class="cal-day other-month"><div class="cal-day-num">${d}</div></div>`;}
    else if(day<=daysInMonth){
      const ds=`${calYear}-${String(calMonth+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
      const events=byDate[ds]||[];
      const evHtml=events.map(m=>{const d=parseInt(m.dias_restantes);const cls=d<0?'vencido':d===0?'hoy':d<=7?'proximo':'programado';return `<div class="cal-event ${cls}" onclick="abrirEjecutar('${m.id}')" title="${m.tipo}">${m.tipo.substring(0,14)}</div>`;}).join('');
      html+=`<div class="cal-day ${ds===todayStr?'today':''}"><div class="cal-day-num">${day}</div>${evHtml}</div>`;
      day++;
    } else {html+=`<div class="cal-day other-month"><div class="cal-day-num">${nextDay++}</div></div>`;}
  }
  document.getElementById('cal-grid').innerHTML=html;
}
function renderSidebarProximos(){}
function calNav(dir){calMonth+=dir;if(calMonth<0){calMonth=11;calYear--;}if(calMonth>11){calMonth=0;calYear++;}renderCalendario();}

// ── COTIZACIONES ──────────────────────────────────────────────
async function loadCotizaciones() {
  const data = await apiFetch('cotizaciones.php');
  if (!data) return;
  cache.cotizaciones = data;

  // KPIs
  document.getElementById('cot-kpi-total').textContent      = data.length;
  document.getElementById('cot-kpi-pendientes').textContent  = data.filter(c=>c.estado==='Pendiente').length;
  document.getElementById('cot-kpi-aprobadas').textContent   = data.filter(c=>c.estado==='Aprobada').length;
  document.getElementById('cot-kpi-rechazadas').textContent  = data.filter(c=>c.estado==='Rechazada').length;

  // Badge nav
  const badge = document.getElementById('badge-cot');
  const pend = data.filter(c=>c.estado==='Pendiente').length;
  badge.textContent = pend; badge.style.display = pend>0?'':'none';

  const tb = document.getElementById('tb-cot');
  if (!data.length) { tb.innerHTML='<tr><td colspan="8" style="text-align:center;color:var(--text3);padding:30px;">Sin cotizaciones registradas</td></tr>'; return; }
  tb.innerHTML = data.map(c=>`
    <tr>
      <td class="id-code" style="color:var(--accent);font-weight:700;">${c.id}</td>
      <td>${c.fecha||'—'}</td>
      <td>${c.cliente_nombre||'—'}</td>
      <td>${c.maquina_nombre||'—'}</td>
      <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${c.descripcion||'—'}</td>
      <td style="font-weight:600;">${fmt$(c.total||0)}</td>
      <td><span class="cot-estado ${(c.estado||'').toLowerCase()}">${c.estado}</span></td>
      <td>
        <div style="display:flex;gap:4px;flex-wrap:wrap;">
          ${c.estado==='Pendiente'?`
            <button class="btn btn-green btn-sm" onclick="cotCambiarEstado('${c.id}','Aprobada')" title="Aprobar">✅</button>
            <button class="btn btn-danger btn-sm" onclick="cotCambiarEstado('${c.id}','Rechazada')" title="Rechazar">❌</button>
          `:''}
          ${c.estado==='Aprobada'?`<button class="btn btn-primary btn-sm" onclick="cotConvertirOrden('${c.id}')" title="Convertir a OT">🔄 Convertir OT</button>`:''}
          <button class="btn btn-blue btn-sm" onclick="cotEditar('${c.id}')" title="Editar">✏️</button>
          <button class="btn btn-secondary btn-sm" onclick="cotImprimir('${c.id}')" title="Imprimir">🖨️</button>
          <button class="btn btn-danger btn-sm" onclick="cotEliminar('${c.id}')" title="Eliminar">🗑</button>
        </div>
      </td>
    </tr>`).join('');
}

function abrirCotModal(cot=null) {
  document.getElementById('cot-modal-title').textContent = cot ? 'Editar Cotización' : 'Nueva Cotización';
  document.getElementById('cot-id').value = cot?.id || '';
  document.getElementById('cot-fecha').value = cot?.fecha || new Date().toISOString().split('T')[0];
  document.getElementById('cot-valida').value = cot?.valida_hasta || '';
  document.getElementById('cot-descripcion').value = cot?.descripcion || '';
  document.getElementById('cot-observaciones').value = cot?.observaciones || '';

  // Poblar clientes
  const selCli = document.getElementById('cot-cliente');
  selCli.innerHTML = '<option value="">— Seleccionar —</option>';
  (cache.clientes||[]).forEach(c=>{ const o=document.createElement('option'); o.value=c.id; o.textContent=c.empresa; selCli.appendChild(o); });
  selCli.value = cot?.cliente_id || '';
  cotLoadMaquinas(cot?.maquina_id);

  // Visita
  const items = cot?.items ? JSON.parse(cot.items) : {visita:null, mano:[], repuestos:[]};
  const tieneVisita = !!items.visita;
  document.getElementById('cot-visita-check').checked = tieneVisita;
  document.getElementById('cot-visita-row').style.display = tieneVisita ? 'grid' : 'none';
  document.getElementById('cot-visita-desc').value = items.visita?.desc || 'Visita técnica diagnóstica';
  document.getElementById('cot-visita-valor').value = items.visita?.valor || '';

  // Mano de obra
  document.getElementById('cot-mano-lista').innerHTML = '';
  (items.mano||[]).forEach(m=>cotAddManoObra(m));
  if (!items.mano?.length) cotAddManoObra();

  // Repuestos
  document.getElementById('cot-rep-lista').innerHTML = '';
  (items.repuestos||[]).forEach(r=>cotAddRepuesto(r));
  if (!items.repuestos?.length) cotAddRepuesto();

  cotCalcular();
  openModal('modal-cot');
}

function cotLoadMaquinas(selId='') {
  const cid = document.getElementById('cot-cliente').value;
  const sel = document.getElementById('cot-maquina');
  sel.innerHTML = '<option value="">— Sin máquina —</option>';
  (cache.maquinaria||[]).filter(m=>String(m.cliente_id)===String(cid)).forEach(m=>{
    const o=document.createElement('option'); o.value=m.id; o.textContent=`${m.nombre} — ${m.marca||''}`;
    sel.appendChild(o);
  });
  if (selId) sel.value = selId;
}

function cotToggleVisita() {
  const check = document.getElementById('cot-visita-check').checked;
  document.getElementById('cot-visita-row').style.display = check ? 'grid' : 'none';
  if (check && !document.getElementById('cot-visita-desc').value)
    document.getElementById('cot-visita-desc').value = 'Visita técnica diagnóstica';
  cotCalcular();
}

function cotAddManoObra(data=null) {
  const div = document.createElement('div');
  div.className = 'cot-item-row';
  div.style.gridTemplateColumns = '1fr 120px 30px';
  div.innerHTML = `
    <input placeholder="Ej: Desmontaje y reparación" value="${data?.desc||''}" oninput="cotCalcular()">
    <input type="number" placeholder="Valor $" min="0" value="${data?.valor||data?.valor_hora||''}" oninput="cotCalcular()">
    <button onclick="this.parentElement.remove();cotCalcular();" style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:16px;">✕</button>`;
  document.getElementById('cot-mano-lista').appendChild(div);
}

function cotAddRepuesto(data=null) {
  const div = document.createElement('div');
  div.className = 'cot-item-row';
  div.style.gridTemplateColumns = '1fr 80px 100px 30px';
  div.innerHTML = `
    <input placeholder="Ej: Rodamiento 6205" value="${data?.desc||''}" oninput="cotCalcular()">
    <input type="number" placeholder="Cant" min="1" value="${data?.cant||1}" oninput="cotCalcular()">
    <input type="number" placeholder="Precio" min="0" value="${data?.precio||''}" oninput="cotCalcular()">
    <button onclick="this.parentElement.remove();cotCalcular();" style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:16px;">✕</button>`;
  document.getElementById('cot-rep-lista').appendChild(div);
}

function cotCalcular() {
  let visita=0, mano=0, rep=0;
  if (document.getElementById('cot-visita-check').checked)
    visita = parseFloat(document.getElementById('cot-visita-valor').value||0);
  document.querySelectorAll('#cot-mano-lista .cot-item-row').forEach(row=>{
    const ins = row.querySelectorAll('input');
    mano += parseFloat(ins[1]?.value||0);
  });
  document.querySelectorAll('#cot-rep-lista .cot-item-row').forEach(row=>{
    const ins = row.querySelectorAll('input');
    rep += (parseFloat(ins[1]?.value||1)) * (parseFloat(ins[2]?.value||0));
  });
  const total = visita+mano+rep;
  document.getElementById('cot-t-visita').textContent = fmt$(visita);
  document.getElementById('cot-t-mano').textContent   = fmt$(mano);
  document.getElementById('cot-t-rep').textContent    = fmt$(rep);
  document.getElementById('cot-t-total').textContent  = fmt$(total);
  return {visita, mano, rep, total};
}

function cotGetItems() {
  const visita = document.getElementById('cot-visita-check').checked ? {
    desc: document.getElementById('cot-visita-desc').value,
    valor: parseFloat(document.getElementById('cot-visita-valor').value||0)
  } : null;
  const mano = [...document.querySelectorAll('#cot-mano-lista .cot-item-row')].map(row=>{
    const ins=row.querySelectorAll('input');
    return {desc:ins[0].value, valor:parseFloat(ins[1].value||0)};
  }).filter(m=>m.desc);
  const repuestos = [...document.querySelectorAll('#cot-rep-lista .cot-item-row')].map(row=>{
    const ins=row.querySelectorAll('input');
    return {desc:ins[0].value, cant:parseFloat(ins[1].value||1), precio:parseFloat(ins[2].value||0)};
  }).filter(r=>r.desc);
  return {visita, mano, repuestos};
}

async function cotGuardar() {
  const id        = document.getElementById('cot-id').value;
  const clienteId = document.getElementById('cot-cliente').value;
  if (!clienteId) { alert('Selecciona un cliente'); return; }
  const totales = cotCalcular();
  const items   = cotGetItems();
  const payload = {
    cliente_id:    clienteId,
    maquina_id:    document.getElementById('cot-maquina').value,
    fecha:         document.getElementById('cot-fecha').value,
    valida_hasta:  document.getElementById('cot-valida').value,
    descripcion:   document.getElementById('cot-descripcion').value,
    observaciones: document.getElementById('cot-observaciones').value,
    items:         JSON.stringify(items),
    total_visita:  totales.visita,
    total_mano:    totales.mano,
    total_rep:     totales.rep,
    total:         totales.total,
  };
  const method = id ? 'PUT' : 'POST';
  const url    = id ? `api/cotizaciones.php?id=${id}` : 'api/cotizaciones.php';
  const res = await fetch(url, {method, headers:{'Content-Type':'application/json','X-Auth-Token':getStoredToken()}, body:JSON.stringify(payload)});
  const data = await res.json();
  if (data.error) { alert('Error: '+data.error); return; }
  closeModal('modal-cot');
  loadCotizaciones();
}

async function cotCambiarEstado(id, estado) {
  await fetch(`api/cotizaciones.php?id=${id}`, {method:'PUT', headers:{'Content-Type':'application/json','X-Auth-Token':getStoredToken()}, body:JSON.stringify({estado})});
  loadCotizaciones();
}

async function cotEliminar(id) {
  if (!confirm('¿Eliminar esta cotización?')) return;
  await fetch(`api/cotizaciones.php?id=${id}`, {method:'DELETE', headers:{'X-Auth-Token':getStoredToken()}});
  loadCotizaciones();
}

function cotEditar(id) {
  const cot = (cache.cotizaciones||[]).find(c=>c.id===id);
  if (cot) abrirCotModal(cot);
}

async function cotConvertirOrden(id) {
  const cot = (cache.cotizaciones||[]).find(c=>c.id===id);
  if (!cot) return;
  if (!confirm(`¿Convertir ${cot.id} en Orden de Servicio? Se creará una nueva OT con los datos de esta cotización.`)) return;
  const items = JSON.parse(cot.items||'{}');
  const manoTotal = (items.mano||[]).reduce((s,m)=>s+(m.horas*m.valor_hora),0);
  const repTotal  = (items.repuestos||[]).reduce((s,r)=>s+(r.cant*r.precio),0);
  const payload = {
    fecha:          cot.fecha,
    tipo:           'Cotización Aprobada',
    cliente_id:     cot.cliente_id,
    maquina_id:     cot.maquina_id||'',
    tecnico_id:     '',
    prioridad:      'Normal',
    falla:          cot.descripcion||'',
    diagnostico:    '',
    trabajos:       (items.mano||[]).map(m=>m.desc).join(', '),
    horas:          (items.mano||[]).reduce((s,m)=>s+m.horas,0),
    mano_obra:      manoTotal,
    total_repuestos:repTotal,
    estado:         'Pendiente',
    fecha_entrega:  '',
    observaciones:  cot.observaciones||'',
    repuestos:      (items.repuestos||[]).map(r=>({repuesto_id:'',descripcion:r.desc,cantidad:r.cant,precio_unit:r.precio})),
  };
  const res = await fetch('api/ordenes.php', {method:'POST', headers:{'Content-Type':'application/json','X-Auth-Token':getStoredToken()}, body:JSON.stringify(payload)});
  const data = await res.json();
  if (data.error) { alert('Error: '+data.error); return; }
  await cotCambiarEstado(id, 'Aprobada');
  alert(`✅ Orden ${data.id} creada exitosamente`);
  showPage('ordenes');
}

function cotImprimir(id) {
  const cot = (cache.cotizaciones||[]).find(c=>c.id===id);
  if (!cot) return;
  const items = JSON.parse(cot.items||'{}');
  const cliente = (cache.clientes||[]).find(c=>String(c.id)===String(cot.cliente_id));
  const maquina = (cache.maquinaria||[]).find(m=>String(m.id)===String(cot.maquina_id));
  const win = window.open('','_blank');
  win.document.write(`<!DOCTYPE html><html><head><meta charset="utf-8"><title>${cot.id}</title>
  <style>
    body{font-family:Arial,sans-serif;padding:30px;color:#222;max-width:750px;margin:0 auto;}
    h1{font-size:20px;margin:0;} .sub{color:#666;font-size:13px;}
    .header{display:flex;justify-content:space-between;border-bottom:2px solid #333;padding-bottom:12px;margin-bottom:20px;}
    .badge{display:inline-block;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;background:${cot.estado==='Aprobada'?'#d4edda':cot.estado==='Rechazada'?'#f8d7da':'#fff3cd'};color:${cot.estado==='Aprobada'?'#155724':cot.estado==='Rechazada'?'#721c24':'#856404'};}
    table{width:100%;border-collapse:collapse;margin:10px 0;}
    th{background:#f5f5f5;padding:8px;text-align:left;font-size:12px;border:1px solid #ddd;}
    td{padding:8px;font-size:13px;border:1px solid #ddd;}
    .total-box{background:#f9f9f9;border:1px solid #ddd;border-radius:6px;padding:14px;margin-top:16px;}
    .total-row{display:flex;justify-content:space-between;padding:4px 0;font-size:13px;}
    .total-final{font-size:16px;font-weight:700;border-top:2px solid #333;margin-top:6px;padding-top:8px;}
    .obs{background:#f9f9f9;padding:12px;border-radius:6px;font-size:13px;margin-top:12px;}
    .firma{display:flex;justify-content:space-between;margin-top:50px;}
    .firma-line{width:200px;border-top:1px solid #333;text-align:center;padding-top:6px;font-size:12px;}
    @media print{button{display:none!important;}}
  </style></head><body>
  <!-- Encabezado igual a cuenta de cobro -->
  <div style="display:flex;align-items:center;gap:0;border-bottom:3px solid #1a56a0;padding-bottom:20px;margin-bottom:28px;">
    <div style="flex-shrink:0;width:140px;">
      <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAADFCAYAAAARxr1AAAABCGlDQ1BJQ0MgUHJvZmlsZQAAeJxjYGA8wQAELAYMDLl5JUVB7k4KEZFRCuwPGBiBEAwSk4sLGHADoKpv1yBqL+viUYcLcKakFicD6Q9ArFIEtBxopAiQLZIOYWuA2EkQtg2IXV5SUAJkB4DYRSFBzkB2CpCtkY7ETkJiJxcUgdT3ANk2uTmlyQh3M/Ck5oUGA2kOIJZhKGYIYnBncAL5H6IkfxEDg8VXBgbmCQixpJkMDNtbGRgkbiHEVBYwMPC3MDBsO48QQ4RJQWJRIliIBYiZ0tIYGD4tZ2DgjWRgEL7AwMAVDQsIHG5TALvNnSEfCNMZchhSgSKeDHkMyQx6QJYRgwGDIYMZAKbWPz9HbOBQAAEAAElEQVR42oydd5xdVbn+v2vtcur0yUx6JSGETkKv0kWkKMWCXUS9NrDhtV+9P6+99+5VUVREikqTXkMLEAgkIW1SppdTd1vr98fa+8yek5N48/nMJ5PMmVP2Xutd7/u8z/O8wvM8LYQg+Ur+NH+vtW78WynV+P/kZ8njlVKNnyePkVICIKVEa934avX86b+T321+bsuyEEIQRRHN7z2KIqSUWJZFEASN50ueRyk14/HJaze/n+k/GqU0lmWjVIgmREoLtEQpjdYRIBufUWvd+D65DlrrGe975vMz4z0ppWa8F601lmXN+P/0dU++kv9rfu3ma5hcEyklSimklI3vtdaEYYjjONi2jVKKMAwbz59+nfRnSL+H9DWNoqjxc9u2sSyr8d6iKGq8R8dxiKIIrRWOY6O0Aq0RwiEIAoTQCCFnrIvkWib/Z35/+vpYltV4vSgKiaIQrUMQEktKhBTmnkYaISy0Fihl3oNSGts2v2snNy79AdMfvHlzpBdR80VLFnD6je7vT6uL2uo1mjdb+oYkC6J5cac3afr1mh/basOmF+30gteAQAoXS1rx5gQh7L0WSqvXTBZp+qY2B4X9Xafm69n8+82bvvlx6WvdvAHT36cDCYBlWYRh2FiAyUJPAlQYhjNeN1mUYRju87OkF3myZsxzSLQWCCyEFI1NI6WNZcnG8ybvuTkQJ+8j2fTTjzGbIYofH4YKy7KxLQtQ8ecWgCAMNUJMv0+7eYH9uxuzv8emL2o6Ku5vo/1f/qR/T0rZuFnJzUj/ab7o6Zu3r/fQvNCShdD88+ZNt68F2/wnfcP2t8D3FUD29bPmwJEs7vQ1aj5N9ncPhBCNkyMIgsb1TZ9OzSducr2bI3lz8Gt+7+lTLR3sklMtHaTMKa72ut7NazT983Sgnl6b2pwgloWUFulLEUXRXuvKbBBLgp5+0/uKqM3pU3oTpBdC801JX4jmRZhOO5rTtOYPn1z8dJRIpwrJ45ovzL6iajol2t+p1mrzNy+25PvkJE7eQ3qBJ+89/brNqVLz+0vfj+YN13zzk+uTRPx0ZpBeBOkF0CpFbhUwmjdROu1Krn9zipg+TZL31Xxv0/c7vbmT57Jte8aJkX5sOiCmr2Hy+um0UUoZp3HEm0KglEbK6Y2XvG5yn5LXtOPzZJ8L5t/t/n8X8fd3xO4riu0rajZH9eZ8vVVk+XfvKbkYYRjGkUXOWDT7qqdapSmtFrrVuFa6cXPNYjYRbebJptEalIr2ChbJ604vSk0UhTNSnGRRpANXOlVsFdBa3d9/d8qk72FzCtkqsDUHk/29VvNJnw4GzWl2eqMkj0lq0Jknq0htYN24fs1BrOWJSvzA5jpkX1ElvcPTEaPVh2iV4iQ3ybbtfRbq6effV0oUhuGMxdzqou+vrknf6CTqNv8sHSH/3aJpvg4marmgFUqFe6VardLD/2u6qbVGo0CAUBphCYRlxYWt2Kt4TV43KYb3lfq0CgitFnerlCu9QVsFpeaUd1+gSLLwkwK/OUOwbbtxiqWDVfLvBKBJP5e5NyFa0whWyd5qrgmbU187HZ3+L39a1RXNH3Z/eXPyWmmEI/k+OeKao3Wr1CN9fKej5b5y0lZ/0o9Ppx3N7ytBXZIb1yq9mrk5LMzTaEKlyLgufhBS93yCSo2JyQnCMCTwA/zAZ2J8gnJ5ijAMCIOQSEVknCzFYgHLtpG2xdy58+jq6iTjZhBC4LgOQkqcTMa8XwHE7yG9wJJTN31f0ouveYE0B7cEbUqjRs2fOYnoyXVKL8zmYj+N5LXaLOnA0SpbSZ+ErU6LdNBLULv0RnMcpyVY0KqGNSlWKj9tjritovr+juH9FZr7+p1Wp8b+njMd5Zpv0P4iXzrapaN98u/mfL9V0GiVvrUqSM3vKrSAUqXGJz71BZ5bv56a51Mul5kqTeGFPvV6vZEqRSpCAKEfgFJIIbEsibBtHMehvaODto4OOjvbcV0H2xZ87JqrOe34E1FRhJAClN4ndN0cJZuveZLrN9cGzShY8+9GUTQDwk2ea39oWasg1aoGbnUvkg3YjFqlN0j6Men6Jkmhk2whHRCaU7rkue1mhKNVWtUK9/6/nDD7Q8RaoVyt0qJWvZnmi9gK0djXQm4u5pph6uTfSZT9dwFg7/dowrnSCsdx2b5jJ7/4zR/AziAsiZQWlmOBnUMWctipm2dbFjqMTNqERmmFBhRQDkPGBsfZumcYy7GolsY48MCbecXxJxJGIRILobQBo/cD96ZToHRaub/rmN5IrUCS5lqpGRzYV8DVWiOkJD4D4ueZeTI1p337SpeTk25GehSnY8lzhWFIEAQEQTCj15NG3tJ9NKUUdvJNcoIkuyy9WJqhznTzKr1D97cB0sdecmS32hDppmJzYy/9QdIXaTotMwsQBFIKtAat1Yx0Kf0em+up5qIvvQjSj2leaM39oEhFRGGI47hs3LSZXKGItvMoBNISeKEHoQVCIDFwo4rMppDYCC0geX4RLzwri22BECEq8mkv9nL/fQ8zMLSb2b2z8AOFJS2EDkGbTSWYCflqrRt1SDprSH82yzIFrLmuSXokZ9zj6TWjGo04KUVjY6Qj+b7ADbMRQgTCBBWhQCfPJZsgWxBC77UBm4Nn85pNI1RphDE58Wzbbmys9KmTPn3sNOrRvNCbP9i/K1b3VcynkZRWi2tmtBeNLub+/iTPn3SGm7vfkCxmEaNDumVKtK9eT7qgbpXGpaOoihTSkqnrhml0KcUfr/8zNc/DtfMoZZHJt2O52jS/pE2+WKSzq5uOrh5UpHl508t4dQ9BiFJB/H4VAoWSEV5QIYoCLGGxe88o23cMMKevHy0UWkqkEkgRL8D9oFAJ9Jr+nNPQchjfIzXjGjSftmiFkK0BleZUbu/7JxDCjtdUlNx6tFYgTOshCb7Je0leJ10P8W8+Yxq+TVK/5HOng35zDZOs9UaKlRxHzXBhGnf+d7SM9KJLniNBF9K7vvn0SJAF8725SIZaIFr2DFoV5I30RugY1YjijULc8RaNUyaNj+8rvdtfPbQXrGsJtDARWwtzfx3LYfvOAZ57cQPSdal6Pvl8B+e96nJy+SIIiCJFEEZISyIEePU6w0Nj2HYNS0qUmgYyVBSg8AnLmkiEaCTVeomHHl3LcauPJkJjS4EQEqE1QmvSWyRNNQmCoLEZZlJNTBmTbEqtFZblmAirImTjnsQUGwmWlXTAdRyM4kWe6lk0r580CGLelmhA3DM3GI01IYRsRPx9tQma0/X05miGv5vXc7I+03BxnKbZe+2kVkV580mzL85Retfatj3j4rTaVNMpS9wHUJpQJejEzAubfv10zjv9+godqfhymyiVFJHTkVAaTlV8UdIXphXdJJ0PN3fom9NLwyGCWq1CIZ/j/oceZHBkhGz7bKqlgFyxnVyxjRc2vMhUaQqlDe0h0hoLhYpCJiYmTLREgNCgZZwsCaTM4rpFKuWSgcmFzZNPrcNHUfcDbMc1V0xpJAIlmMF5AvB9fwbM3giEWpnUDhuRQKBSE4YBUWjSEnNKpmBSodFCg4oa90pFGAg6jtSO47Tk7k2vA9049U06qA3ogHk981iTMqcjfPK50uu3FeLVnDKnYeJ0w7PVmrdtG7sZFWpFqdhXWtVqQSVQXvJGmou76eeeGQGiKDQLAhBSotGoKIwhUxGfKKol4tW4cGAiW7KhNPFzaoQWhoimAyzLdGg93yOXy6GVbonNN1+PNOI149SJb3MkNDF+hZQ2zz73HF6gsLUmRDNv/jzCMGBsbJxQa7PspcCWFmBjIc1nVyCkqSJSy4cIcNwM0rLwlY/lZHj+hZcYGRujs6OzEXGV0OgWp93+aC/TULGKTwCFiGFqhUKFGkdoJBJhCZQQCNPVjH8xdXqIffdVzL1SgEQIC6VDtI7ikkujFEipEUI1Nl3S6Pu/8tXS962539Qc2JO6LH1N0mWBbLX4E6rC/ghx+4Jzk2jdioWb7mMIkc7pNZZt0B3LtpCWQKMaR3WyoZqbmDOg1eTfQsY5lUah0FIR6ZBIR0jbwXEy5qQB8oXcjAWfjqitkLJmNmvjSAeUNtFUYZCZsdIkTzz5NLlcEaVMpJw/fwHVah2BxLIcpLAQWprTQqtG5J1RPcSfBcsiEnED0nURloWbzTOwaw+bNm4mY9kzm3T7CGSO48ygZzQCjjSbU1gCLc0mV0KAhEzWRaPI5/Jkc1ksKYm0JorTolQ2nKzpGWupOSVKp+8JWXAm0hY1roU5PWZutn21HFpRg5pTsDRHLQ1LB0HQWPcz1nEzHb1VJG11RDbn8M0Nm+bokT4W01/pRp0lzRcaU/hKK/We1F6NotYF4XTqVqvVqNfq2E4G23bZtGULP/zRj3njG9/CVe+6it27duM6LvW6RxAEe/VX9gc+zLgpOnmMufkZJ8PmLdt4afN2LMdFh5piroOOjm5GRkeJtEkDEWYzpAGKliscgVYKKUAKScbJIrBBSzzP58WXXsIybckY9Zp+bwmcmaRIzdSUGURHKRDSIggj6kFIPVBkc3kefvQxLnn9G3n7e/+Dv93yD8p1HzebA1tSCzz8UBMpRRj5SDFdBBv0bRo9CoKgqfmqCMOoUUD7vt9AHS1ppTazod8087jSHfPmzGd/lKkE2EneZxq5TTcypZTYzadEM2LTnPM378zm5s6/QxdmBMYGRm1OjEQHYPBx0SBRmsex31pGN23oKApoK7ZhW1kefmwt//u/v+P+Bx9m154hXNtFhQGj7/0AP/red5g7ezZBDCa06t7u7+RspZXJu3m2D+xiolyjUCxQ9yLmz5mL62QYGRlFWHJvMMCk2ua6JJ9Hp/ZMnH5qBLbtIkSce2vB2rWP8tYrXo8QSS0Qp6QxbdvUMyCZKRUQzQiT1mih4k0S0Fto55e//x2f/NRnCLXksWc38udbbufIIw7ltNNO5E2XX8L82bOJIoWOIqQAx7YIo+lFjkhVGSn4NIqSzxM21kKSWViWRFqC1JVoqY35v7CT90c5ShMtW4EwjRRr5pE3sweRXjCt3kwaKdhX57RVnyHZJI2ooEBHpsgTSGzL2SvtSePgUsrGgiKuUYQQqEiRcbPks+28vG0XH/zIJ7jkdW/i+hv/zlQ1oK2nH7IF3LZOHn1iHVe9532MT02RzWbNqSVkC/QqTXhLVm4KKjZLjThZAuCZZ9fj1SNs28GSNv19s/H8gJrvNeqqGZs8PlL09I7Y6ygRGrQSOHbGLPZIU8gVeOzRxxkaHSZjOaa5KIj1DZooSVfiDea6LrYTF9xSxi+b9CKmT7XO9k6uv+UWPvHZL1BXDtou4BQ7yfT08eRLm/n6937M+a95HV/7zvcpVesUc8VGPdYIMDJ57maBmLl+tm1hWXaKzBk1IF+t0z0psdeC/780rf9dm6AVb2svvl+SbzmOs1exkvB4kqOxuc2fHJsJvtzc+2jubM48ZdKtfQxaoyW25WJbLlLYqYtHAwffS0shoFatUKmWkQKymRy7dw3y2f/+MudceAk/+e11eHYWp6OXQOYZKwfUlE0lBG1neOK5l3jvhz7C6MQEtm1TrVYb/J3pWiN5u6pRGxnlmWqkDPVqhXq1ipSC0eo4Dz/2OLblEAQKrSVz5s6lXK2azSb25jMpBDqO+gZkkPF61Q3Y1uRwEimdhmjLsmyG9gzz7NPPoaKIIAjxA48w8ojCgCgK4vQkQsf1ETrZzkm9o6jXq3i+R7VepyOb46ZbbuHDH/kEoXJxs52EyiWIJL6vsewCbq6TbXsm+OLXvsvlV7yVX//xT9SDENt2CKOQulcxBXgTEzmNbtm2BC0IgyiGfOV0g1eJmK0cNJCsJE1Pp+qt9DDNrYTmUyNJOZubpOn+SSOlbkXB2Bd9eV+0k/0RFJt5O60oJ1rrGcjHXsdckzYiQayEFhBqHNuio72dUqXCr397HRde8jq++5NfMFkPKPbNJrBzTHqCsaqgf/4q5ixYSUAW4RRRMsett93H+6/5OF4YUGgrtEwVI63QmFw7UiFhFBKEPn7gE/p1bFtSLBTI2Fm2Dexiy9YduNkMQRjguFna2jqoVmstWQrp80ImGKtu1RfQcRoqsCzHIGdaU6l7PLP+BbJWlrZsO7lM3gAHUYQKFVoZ+FtHESjzb3NkxxtdK/wgolb36SgU+cs/b+Njn/wcSjhIu4AXSDK5DjKZdmyVxfIdtG+Tcdtob5/Ds+t38KGPfoY3vu0qbr/3PoQtyeVzRDHk3txgng7GMbNiBrXEMv0kzT4QsOlaVkqJ67ozNuC+5OHNUH4S3NIctGaQBkAEQaBbMT33Jdds7mI2F07N0GurfkK67d8MBjT3JfZGLkQjZ43CCDfjAnDDjTfzla9/k+0Dg9iZAoGTpR5qvCBCOnkWLljGyhUH09XZhWVL7rnrNvYMbEaIANeW6HqJM045lu9+88vM6umZUZPEJzxaRwSBj9YRjuPguhkkDqGKGBwa4fmXXuSxJ57grnvv45lnNoHt4kcWs2ct57xXv4Z1z7/IRKmEHXf4YynONMSqFBPjI0RhgNACKePAoQ2sijZBQQqN75WolEawojJS+PT0dnLxq89j1YHLOezQg1m5fFkMH0OkQ4LAN01JaTTZ0oobkVojLJtyuU5XRwcPPPoYr3vLVXjKRtpZAu1g2znaip3Yjk0UBPh1D9+roCIflEJohdABUVTFJuT8c8/gv7/wGWbP6iUMfZRiBqt3ev1oPK9OEPhIS2JJq1GHSGnvxclqhmBboVdpMuJeQa6J4ZymwrTS9FiWhQjDUKebXvtqmqUXfjNs1iyTbG7AJcdicrztT2PRyrhgBk1FK3RkivhsNsvAzp18+Wvf5vobbgLh0NXdRy3QTHoKYWVYsvAAlq88iHyhjdHhUYZGBnEzGQ484ADWrX2YTdueJwzq5IQiqk9x/NGH8rMf/4BZvb3UanUQ4MU3MJfLkrFcNBG7B/ewceMm7rrrbja8+DLrN7zI6OQkpWoNISWZbDtaWkTKZc3q01lx0GGsfWodSoAFCOnMRMmEifijI4PoKMQSEoRukB8bRYKWoBWSkMnxPRBVcayIUAXUKpNYQtFezHPYIQex8sBlHHTQgRx62KEsW7qUrmIHAih7FWq1OhnXQaMIAkW+0Mbjjz/F297xXoanfGS2g0hJnFyB9vZeXDtr0rS4BhNRgFct49eqqNAHFWFboCMfrzrO6qNW8Y63vp6LLjifTCZLqTRFxs0gpWWaknHealL0wJA4LQvbNty0BMFsxZZubtY2M9KTxa21xvM8XNedEcwdx2nUGMnJ1AzxWlbsOxCGoU7rHFqRvlo5jaR1HenIn6Y/Nx9b6Ryx1e5vRUpM54Z+6KFDTaFQpO57/Oa31/G9H/yErQODdHT1ESFR0kWIPPMWLubQw4+ip2c2O3YO8PK2rURRhOs6BHWPns4ODj7oIB56+F42vLAOoWpkHYVfm+S0U47npz/6Ae35grlgUlD26zz/0kaeeeY5nlj7BGsfW8vuXUNUKjXsXI5Ia4S0sWwbpMQPQoRw0SrDqae/imLnLJ5/aQOO7SK1REtrBgJnNkjI2PAgRFGjUy0aKZcFWAiROLtoaqVx6tUyUgTYcVFuInkNr16hXq/gujaz+maxcMECTjz+GI45ejWrjzqKWT3dKDTlapX2fJGNW7byxivezraBcUSmQC0UuE6Ozu5+bCdnmpdCN2oWA1KADkP8ao16ZQoVeEgRkrEiotDHq5U475Vn8t//9UkWLZxPpVICJbHd6fw/qWGFFNiWPYPw2MqAo5mFkUZX02s4Oak8z9tLvJd2bGmWKRgXFTEtLktOkP1tkFa1R3M3MnmzrTZIs5a4md/VXHO02iAAYRSScTO88MJGPv1fX+Su+x5F2FmknScIBdgOXb39HHHkMcybv5CxiQmGB0eoVivmdeNiWwJ+vc6snh5WrjyAf931Tza/9CyOrXCskFp1kjNOO4nvfeublCYnueXvf+fRtU/w3PoNDI2MUo8Cstk8mUwegURrSb3u4QUBkdJY0iGbzZHPt9HePos1x57CZLXO9l07sS0HqeOCvMUGGR8ZgpijpNGNmkRjmqBSxrJSAToMKE9NIHSIFDEtJPKBAMeRCKmQtiQMfcIgxPfrZBybpUsXcMzRR/Laiy5g5fLlbN6yg0996r954fktYGfxkGBl6Onpx8nkCQONJW2UjkwzVADaFP22trC1IPTrVKslvFoZSYDQIZZU1KtTLF+2gPe++2285Y2XI9AEwbQaMAGA0gs7gXOb9TvNqXtzEE7TnJIg3kxmTTrnyWunTyTLsvB9fwa61ThB0qlVWqm1P2OFfXlnpbHqhC+T9q3al3Bmr+54E04dKcUvf/kb/udr32V4qkKhs5tAQa0aYdk5Dj58NQcdshrPi9ixYzul8hSWtHEtu9HtNoL9CCEh8D3mzZnDAUsX8o+//5WdO7diyQgpfEKvwsrlyyhNldi1Zze2m8PNFLHcDJG0qFc9wjCmTGiL9rY2urp76ejsZNas2cya1YeTcXCcPJ4Xsf7FF6l4HkJYZoOkT2Up0FKgwpCxkUEIU7LYuIchLNnos08DywKhFUQhqgEa1ImigCD0QKpGmpZ8aR0R1ivUa2W62gosmj+H4ZEpJicDspkOvEBBNkt7Vw+umzekT20jtERaoKSOr6MGoZFKoENAGpStXivjVaeIghqWjBBCEQUV8hm4+FVn8okPX83cuXNnGEwk6y2J6OlmXTO7ttmSqJm237xx0uso+UpSriStSm+8tJRXSomIokind2q6pmhunO0r7Wo+YVq5W6Q3SKuCaG8kTDS64lFkiuKvfv3r/M+Xv0W2vYfQyuIridIWs2bN44jD19De3cf2XUOMjY4hUVi2AOGYhWRoqujkAgsQFuggZO7sPubNm8Wtt9zI2NgeLBkg8CH0yGRz4Dj4vsYPBZESSDIUCm20t3eycMFi5s1bQGdXN5lMlmrdY7JUolQpEYQ+dc9HBZpqtUooDJzdxA4yaZSUqChgZHgQwticLuY7CWE2UGQaIQ2+k4xbDTKm9As0ESEqXrxRUMP3PfzAN/0QbVAsS2lcy0JFHlFQx3ayKOUiyRJqKHb3kG3rIAgUQoHAGKtZljAnSIrjFsNQqDj5EihUUKNWK+HXy0gihAiwdZ3y6CBnnnQi1//xd2Qy7l7rLb1BWsks9mXekD450qlXGhRKNkLy2Ga9SvKYhEvY0MA34/HNmuFWNjitutmtJI/NxLj9OWY049GGYWqOcRU3uXbvGiSXK2DZWXwtybh5Vh50FAuXHcxkqcSz619ACgvHkkaApK2YtDdtcqCEJsLAj1JLhGOzY/cupCU455xXc9ttN1EqjeDYGYRbIIwUtUoE2HR09jF//mLm9M1l3vxFYNlU6z7jE1OMbN9DtVqlUirjqRCFwrYspBA4tm2UcyoCIhP/tUxRveONkKjqpOl/xHxFQ73Rhkaima5NFEZzYgthkC6USd20RlqSTNYiky2glML3fTy/TuR5qNDD1yBlBpl1CQKFLTNEyiLX1kYu10bkh0gksbjEMHeR8evPBBe0TElylUBKl7ZiD3XboVqdAGFhSZvuHs1UpUQUhohsZr/Mi1b9s2aNe3Oa/n/xSGhOqfYVoGdQTZppJmkGZCu6+r4p62ovBKo5pUrDts1qs70sNWOeUhTrIlasPJC6VyObKRAEcMCygzj6mKN58tlNTJWnzOaOMVlp26bh1mhSm5VmCYGwZnZpbSfHtu07UUpw1jmv5o7bb2FiYpRCro22Ypb53b0sWLCE2bMXUCx2MFkqs2NwmOHRcWp1H5ViMEvHIStsUz80uFoRUqoZjn0I2aDQCKFNWyJhwgqBFnFXXRCfCCJFz0loKNMGaCImMpqSRccMWatBJ8lmsmQzeVQ+IKp71OpVwjBARZFZ+NKmvb0bN1+Im3sWCNPE1XJ68wo9k4ettUZL0WAeSyFAuGgVkcu1IwXUyuNkXZfyZI0jDzucQrHQMKZLo6dpkmDaR6xZz5GgpPuSBDerQVsZUjRvvuT10tZSUspp04b07kqnV/vblfvSfTer0NIbrnnDNJ8w04iF0YYQE/Q0cPRxa2hrL+CpAClsBga2s2fPbiIVYNmWUW3GEHBCZJomE8Yplkp4SDEVA40WEjuTZWD3IJbjcPJp57B1y2ba29qZ3T8XJ5ejVg8Y2D3KxOQWqnWPUEVYtkGtXMdp9DSUNqlOwqlCm5NMaBuJUR8itMnZ48cZir8w6UwMoyqUoZQn2hZAxPQLgygJZNxLQYq4oJ9urAgxHSzQSeVioq6TdclmC4RRROj71AOPQqETxy2YYBSzohVMp3j7pHTEtPfksNNJemzFxBmBjBRSgWNJTjjhuJaNu73Uii0sf5q75f8Xs5BW3l37YvW2Wt/2vgzB9uWPtS+3kpn1hGywMlsV8q12fvqoM3rlmZvI1wEHrlzBsuVLeeHFbeRzGSqVMr7v0d3dxfYdAziWHRfh0zi7jjdGgwTZiIUxfYEQTWT+1xZsHRhgwfw5HHrEamqVOnuGRhmf2kbN880ylRLLsXGEQxLKo3RtlvQ5aPwYgZqO+rq5sZrKWQQUCgUiFZraIlYoAgQpCQJE8UYPzedtHJN2g1WslUoSotRrSKO7UIkgS+JmXJxcAWFnzFWIUbOoRQrdSKnStWf8PqdTLo2WRpooLKh7NRAaPwrItxWYv3jRzICqYr5YKpBGkWoEjmZ7nlYLf1+mE62caVqRE/dHqrWbjRCa7W/StpHN3W3zZjVKpeFbgVJBLLSfRgaa06nmv9Pu4o3XEipOleLiXsIJJx3PU+teoJjrwPdrDA8OsmT5IWxVipAo7h7Hm4JpKxyEmuY2iRTBI5GJxtFXCMGunYMMD47g130ipbAyDq5rY5ThAiKFxnS0TVye1oWkiXeWmKYyWnJaq661JmK6i65UhMIUqMViBiGyJpVJpQQy3kxKGTlxEASEUYQXI2Oe7xPGZs8ahSUkM4m6KtYpChSW2YByWiFovO0USmpIK/1kk2mHSNu3TJMPp5eijDUxgjDwUEGILSUq8pg1u4e+eX0EKjLCMAGBMgicZRlFpg4Tgzcr1TuzDECQqmtVIikWKSsghFEhxtc8jaSmi/lmlWhyjZulAFrr6SK9WSPQvAtbbSLz/Qw0P64d4iM21hKnO5atDKCbRyYYj2ETzXScHySw5oknnsAvfvG7uHMrGRrczYEHHU4hlyMIIxryPtFCtxj3Fkxqb8UEuOk0RAiJ49iEQYjnBYb5CqikVog3mJIyFjiRENrMahfmpHBtG8tx0CpCq4go8Kl7dZRWRFFIpVrBC/34pFSoSKGITIrEtMQWPW0+kck4OK5hBtuWjZvNkctkyWezDRv/iAjf96nXa4TxBpo+dTRKSENSlEahaDVunohPNj2DYc8MQSx7MbGnD+KkLtJxkDHFSlj3zH1yBX6lymmnvpp5c+bgez6u5aR+3Uh4kxpMxJu7FagzHaz3JsIm9P79GQU2B/t0Wmcaye6ME8neV2e7mX/fii6cMFq1VghpkKLkwwn2xqVbcfNbdtCVjmsJbTrT0yA+x6w5moNXHcRTz76E6+YZHdmD1iEd7e3s3j044wM2TpGmnNngu3FyIKYlopZloWIEKNGYm/EGMk5XYt6RSESwpii1hEAiCZXhPE1Olgl8D9+rE4UhYVAnCGrTN1VoIjmd9lmWhYxRKRUZfb4UYlpZqDXVUrwoEFjSRkgLy3axpE02lyeXzZHNZnAzWXLt7SAEfuATBH6DoRxFIVoaREkLUEIZiFg0aU/20cDVWhvKfXrLxIFF6OnNZa6RIgiqIHyEMlyt0045Mf6ZmPlacqYjTIN338JrrXEaqKilTex0EG49yqMZJGpudqd7K42k9f/Cs59ZI6i4AWjFPBqZ0kgkyM10kdz84dJQW/IGjZoshpmlRIrY7kanoM5I0ZUvctDKA3n40ado685T86pMToxTyOdn5K3sfc8T0rxJu+I0Qsi9iZdJamH0SEk6GRo0Kuk9mM4jtVoVv+4Rhj6l8jh1r4oOfcOXSnoVAqSIGoV5krkkxA2hjVkcxqrA/FDo6RxsxoeQgOmYB2GNAEGtMsa4BktIpONgZ3Lki+0Uih0Ui20UC0WiKKJSqeH5PrV6HYSFsuQMYZYBL+ReA4laEfpm9quU+Wgp4VUUBQRBDUuE+PUKixbOZfWRh4EKjZDKJEExsGAlpVmcHOlYly5iXpaM9S3Tc0cSQ4dkHaVZvWEYxhvXGPg1q1qb3VyS/3NdF8/zGrqZKIqmYd5WzoWtvp/Jg4kIQi+G5lI7WQiUFi2F+826gITImIbvEgsaIS0SgFEYJhIAR685iv/93R+NHi302bN7JweuOspERjUTh5wuJuPNqtP6jhhKFtO5s0yjT3FtIS2FCkOEDtGRwqvWqFbKVMpT1Os1Iq1ROkAIEzkdO067SG6yqQtMO0eiZ8iskiCQvCk10yNq+h9xKqMSES6W0DGRMalXLLTS+PWIWq3C8NAgmUyGXC5HR0cnhXyBjmIHNS9LtebhB0EMJQsilezHNPjSwrdMm6J8L9QnkQ5Ls+GVX0eFHrbU1Op1li5ZTGdHFzWviuvYpi+V2AtFukkgphv1kRB6Ri3VKAUQRNqctJZtG6EbIrXFYuO8JE3fj8Cq1TCoRpHeCm5tBeHOpJbEDMjYUU805X5m11r7dEBp9kZqpsHPOAFSBWPcCuPkU05idn8/Q5MVHCfDjh1bWXXIGvK5HJVa1aR/Srcwe6ABSaaza6EFSNVAt4RO4FmwbYso9AnrFUqTY5SmJgnqHlpHaEIsK5aIKoUgMpEvzqV13GcxPhLS0DVihwMlQrNBY0dF3bheipQ8phHVtWiCW5ORYQkap8xCkla8qFSElpIwCCj5VaYmRnAsm2J7O52dPXS0tRFpxeRUiXrdM+xiIdP6xb0qD62NnVBiLZQUIyKlq1copNSEfi12eTQcseOOOxZbWMZ3a/osj2tYOcMbS+vm19cxBV7OMD1v0G3iAJLexCJlfrGv6VppYWDzWpyeD9IE2baaJZGwG5uf3JYOwkkcSLQxW27ooWVDcZgukJpRsFb4976gZcdxCVTA/P5+Dj7kILbdeR/ZQpZarWo06B3tTFYq2FI0Fo5s1KAxgNCM/GkRN+piP60oQKGxpDFKKJWqTE6MUSlNEAY1LBlzxhrpUqzyQ8dpmDIRLQ4gkYpQftzoCiM83yMMAjI5h1w2G/+eMl1rzbRWXApUGBGGKja4VqgECtUapMB289i2TSaTMUCIBlRoeAMqBCmNc0rcdFRRRGlyjMmJcXL5Iu3tnbS1d1DI5yiVTOPQEK50A+FLp6BCxNdT760VbzxGxboZzzM9FBXR3lZgzZqjTOriTNeIMlncscoxTS9qNpVO+3s118wJ6bC5eS2lbPiANY+daKaypDld6aaknYZuWy3KNM9lZjoWb38VIyNxYd5cYDXDu/vqyuuZ8rk47RHTJ0i8w0MV4To2p516Erfe8S8yGZdKucT27dvom7uIXYPDZmNYNipWtCXmD3EFudcGaSBRQmEJUFFAaarE5MQolWo1fgIfxw3RKohPx+mejxSRidpEBEGdatUjDHy0hkwmQzabpX/2LA5etYr+/llkMy7tne0csHQJhVwOIcC2jQZfxj64URgRhhGe5+N5PlNTZaamphgdHWXjSxvZuWsXw2PjlKZKTIyMEPgBmWzeOLhkcjiWa+gp2nTKRZyuSAkWFqFXYWiwwsTEGG1tHbiZHEIYR5MkYpsc3kofZ9PNnf0ZJUQKFQbYUiI1uBmHYj4HRDFvjGl7I2HEvzomke6rJ7Evx5mktZB4gu3Lv6zZh7g5nUqv/xkG2Psa/ticWu19NIkGgrGXuUAjV562ElXaID6JW0krqDe9+xo1g8DUM0LEYwJChJTMmt2PtF0irbBdl4GBbSxYssIMa4xCU2tICWoa2tXNnlPxUS+lxEJQq9UoT4xSnpzAq9dwbIGFIhQKxzYplFZhiuBmo6KIyK/j1SsIFD09nSxesYSF8+dz4IEHctRRR9Hf30ffrF56urriYZRmqI5tWTElRJEkeI14QNxW13EPBBtHmozYC0Nq9RqDQ8Ps3LWTkZER1j7+OE+ve45Nm7czODSM5eTJFgq4mYwxhW4o/6JYPmxOF9+rMlKvYdsubjaPm8kjbTF9zbRqdO5bA8Az0RBLCupeHaE1liXwqjUOXH4AS5YtpezXjO+AtGf0oXRj7+mW06lEA8WMrWO1ajAipudJqpby2mYrq+a1nDCKWzkz7iW5TQXw2GFCt2zt712XTIvqk2J7egLqtDGZFNKkHAk7U+mWDilpJ3ItMO5+Aqq1KvlCjrVrn+YDH/kEA4MTCDuLihwcq8j5F13GnpEJhoYGkZbTgCWnezQx/RvdyKEtWxD4HuOjg0yMjaDCOraIuU/KLFQtIiDEsRQZx0YpTak0Sb1Woauzg9l9vRx77NEce8wxHHHYYSyYN5tiWwFH2HHZHaFiWreRn0YIGRltuAClI9Am/zf2OaYd3wAVMCYO0sqgVIhSIZa0yWRzCAtsbEAzUZlkYGA39z/wCA889AiPPr6OkdEJLMsyFkhSEilNPVREWDH5MA5GUsbSXgvHzVFobzdIlzIBSmDg4QaG0NSETFaBJaFcGsavTmJTRygPpaq8573v4OoPvY9KuUJ7sQ1bWggliPzEJE63NCBMOHlSTqsASUV4U7va8XWJ9uq8N8O5exXhtj1DM7KX30IQBLqZf6JU1Gj3JxrhfW2Q5m749HMkdOKYvizMCRJFEVGTUcO+CJCWZaFi+8sgCslncjz61NNc8aa3Mjg2Rb6rnzBy0WQ5YNlBHHP8yWx6eQtjY2NI22kUa2iDTtFAgOJcPQoplycYGx2iXitjCYWQCk0Uu4tHCC1wbYltaXyvjNARtpQsXDCXk046jvNfdS7z5s+nr6+PvJOhHtbjiGSiHFJgkXTCjRbd3AzV6Pj6vocUkHEzuK5DrVIjiAKkbSNFMjLCmLppHcUb3fRDgigijEJsxzaabsvCsiR1z2PL1q089PCj3Hv3gzzxxNNMTpWRVoZMvo0gEqQGQsRWRAbciDRIO0M2nyeTKxKECstyZzRXRZpeolMjsy1B3a/glUahXiIjQ8KowtTUCO9731V8/tOfbZziUmtUGLUsnhPSYMNcPXaObNQUloVSRgYhhCQI/JbjNlrNv2keW72vDRJFkdkgzZN2oiiYcTxJae/TFbtVpz2xtI9iBw3HdRDCbsy+btQGKWfCZifyRGSltKIeBBQyWe586CHedeX7mJisYRdyVOoKy+1m9ZpTWXXYEQwM7GDnwE5z8ZIUKwZWjf+BieeSCL9WZmj3LkqVCWxLGNO1KDCRHWUMoKUFKkKFPiqs09PVxmmnnsS555zNmtVHMm/2HEAxVS4RRiFu3BfKZDMN3YmMvaeSlE8pw9w1jcAoPiltLDTPrFvHY488yjFHH80Ra9aggHrda7CSlVIxlcJApJZ0CJWJv4l3sYpiq88YkGgrtKOBF154kTvvvIc//OkvbHx5O8LK4eQKDcTP/L75UhpCZTQobjZPLl/EdbIG6MCK9SExW0zMHDqKFAYuD6pUxvegggoWEUIGTIwP8853vJOv/L/PIiREQYjUKnY1EaSHmiZO7o0WgGMZmk0UnyDxSea6pnbz/WCG9qO5f9PM5E1vkGbi4owTxPf9pg2iCKMAGdvN64YKTzd56850kpg5oMY45ulGL0CihAVIgjBAmQHtcXMpZe3RlGqBpubVyeeLXHf99Xz0Pz+LCm3cXBtTXg0338XRR5/OgkWreGHTZkqlMdMsk0YHktBeLG16BrYlUKrO0J5dTI4MmoUvY+4SEegIoSNcR2JLSWliEtuRHHrIgZx91umcf97ZrFqxEoBaUENHYEk7bmpGplhUhjaCnHYGjFtfqCgW5Ngu1XIZ13Vob+vg+fXP8Nc/X8+D99+PY9u4tsOa447j/Asu5OBDD6VSq5nrG1PZbceOT0cLhUmBlNaEoYdt2bTl29BKYUmbLTu30NbWRnt7Oy4uO0cGufGWf3Lzrbfx2GNPESoo5IvYjksUaYIItLBAWEQx5iqlRT7fRi5XQNoZwiih6tgxF6+5ThVYIkRHFSqlccJ6FUuG2DZMjE/wmgvP5utf/m9yuQxESePQkBRn8K0SCbdjg5046JsNSaRMmjbDjG5vFWrzAND0+PFkcySBLDlN9rL9Sdy2pdSoeHiKGfBiNXDpZkFV84Ju1rFHOopvUtzxjBGLIAqJ4k6oieqiYWIm9Uz9RqR8sm6O3//pT3z82s8SkQHHoRYosvlujjnhLGbPWcyLGzZTrVQQtpGDJjCz1qbDa9IkRRiWGdi+jUppEkmIEInroEJqhVYRltCEXpVcxmbVyuW868q3cdppJ9Ld0UslqJjUx7JwpI1EYmnZcDC0LNHIg6f5RNNRw/d9wjDCzWTIuA47d+zglptu4pa/3Ugh53LMMUczf9589uzZw7qnn6JUqfCKM8/hvPMvYMmypVRqNYIgxM1mYzhY4YchSpmUI5vJ4HshN914I088/gjnveo8Hrz/PubMncNlV7yZUAsyboaMnaEe+Nx797386je/4/4HH8ELNLlCO0rbMeIqp90ihSRQGieTI1/swHGycd/GnCRJDZLktIaOEplBPJFPpTwGqoYUCsuyKU2McsF5Z/Ktb3yJvO2gIg8ptBmdkNRciVGgMAOVpIQIQaQhVBEqCsm6jjHAUIoEv28lDU+XEGlBYPNJk57G1fi9KAq1+Yc0fCptih0pLaRIbxARD1T5v26QAMfKmMZRiuvpE1ENag1Nhox1CxKJ3UA0BFEYks26/OO2O7ny/R9AWzmEsPFCsNwOzjznQjKFLp597oUUIyNFoFS60X6TaEaGdzEytBOtzSbQyse1tVEtKoWFQgpF5Fc5ZvURvOH1l3HOWWfS0dFOpVYGATXPI5vLIWyJ1AKppeHFaqPfsKRA6SgVzaY3iJEN29hWli1bNnLjDTfwwL33Up6a5ITjj2PlQcsNMzUITXqJYMfOnax94knCSHP6mWdx0WsuYs6cBfiBZ+BYYYwHcoU2XOly25138IPv/ZAnn1hLV1eRQw4+CNe1mJyc4Iyzz+MNb36LqV0sB6WhkMvj1z0eeOhRfvijn/LIY08QRBaFQpEoxjGklDHCZhPFPLZ8vp1cvg2N1SjyG2Z+DbRLxc6KGklIvTZFWK8iITZzGOP8c0/n21/7Co4FYegb3l2cVeggaiABliUNy1gaOFwisbDwlIcKFDkni+d7M9Zi2mWnOdtJO/KkWxCtrKhEGBoUS0rLOAaG9UZhbnaTsYG0LHuvmqOxI+N5HlEUEQYmtcrncvzhz3/ml7/5DQvmL+SoI4/kkMMOpX9uH/1z+snaGUJCfM8nDCIyloONje1YVMoVisU27rjtDt7zwY9QjQQykyPwFe1dszn9rAuxs0WefW4DQegbKnTcopWWwBYQBQGWJYiCOrt3bqU0OYYlDbdKECIJkUQ4QiJR1KpTLF+2mHe89QouvujVdHb2EgRlo3yznXgEhoglwBKUxsJq6MEjFcU2/TNPXM/zYhSpg5Hh3fztr3/hz3/+E5MTUyxcMI85s/vJ5/N0d3fS09ND1s2YNE2DFdvTbNu2nSeffgql4cyzzua1l1xKZ1c3fhiRy+V4at0z/PgnP+WmG2+io6ODE48/ls7OAp5f5cjDDmXrli2sW7+Bi17zWk457RX09PZhZ3L4no8E8vkCnhfwyCOP8a3v/oBHH32cbK5AvtBOzfdRsQuLFhIVSYR0yBXaKBQ7QBm3GWHZBvVqnAAaJaetTUWkqJUm0UEddIDrRIwP7+BNr7+Mr33lS0xMjhmZcMZBaPCrAdlsxiCZgLYkgQrZun0bO3YO8Owzz/PIAw9TL5f54Xe/y4L58+LALvdinCdOJWm14QzNU1zbJAYjab/pBoplNkCEH9TiIy2xTDEIimO7ezVuZgz9xLyI7/kU8wXuuvs+3vjmtxLq+Fis+7R3tNE9q4tjjj+a4044mtWrj2T50gPICZdAK6J6RBQjE888+wxve8dVjI17aCePF0bk23o495Wvxc23s+759ehAYEsIVR2hLTMk0wble7iWwK/X2DWwlSCYAhXEMGZoFBEixJIaXfPobC9w2SUXcdW73sbs2bOYKpVMpLJinlPMd0paZEoKUGALO7VBwgbpOO0ja9s2tVqNu/91F7/+xc+Ymhjl6DVrmNU/h+HhQUbHxwyCZbkUi230z+qjq7vbzGmM3RalLVGhYsNLL/H440/Q3tXFhRe9huNOPJmf/fzn/PwXv6Kjo4NjjlnN8ccdT7k0wfMvPM3qNWs487TT2LVjB7f+8w52Dw1x8qmnccCBq1i0dDndPb3GcEEZVm9bezu+5/PXG2/ixz/5JZs2b8XJZUFapmgX5iQpFNqx7CyRElhS4mYyaGETqMRoWqCERMeaD601UgsyWlGeHCHwqwhdx5Z16qUJPvyRD/KB9/8HtXoV2xFY0iJr5wAYGRvj2WefZe2TT/PSxpd54MFHKVVq1P2QfNYhrE3xmgvO4wff/hZhPFpumqtjTofEG6t5PEfzvJJW5iQijEKNnj6afN/DzcgYOkuMgy0s6dCM9KbZtxqo16s4tsPuwWEuvORydu0eJJNrR0iXQJkcHx1SqZZRoU9fXw/HHr2aY489hlNPOYW+nln0dHWyfWCAd7zzXTz/0lbsbJ66b5HNdXP+JW8gk2vjqaefIwjrWJaDUMYWXsSDjrRWOALKE0MM79mOCj20EITaMzWHUDiApSNK5UmOP+owPv6Razj1tJOAkFq1RhRp3Iw7A8pMIxBGGKhiaNTci3QuK8D4Y1kWjz30AL/51S/YtOklDj3kYI4+eg2OYxPUFZFSjI6OsGvXLuqeD5Yp5Ns6Opk/ZxbdXZ04tmMKeymxbJt6vcazzz7Hy1u2UPYCXtq8jZNPOZkTjj+eOXNn8/LLm3j44Xs54IDFXPqay8hmcmzfto3BoWHWPvYIQRhy+JHH0N07m56+2SxespT2jg5UFBAGIYV8G22FAlsHdvKTn/yEX/7692hh4eQKhJE2qFauDa0t6rWAtrYCPb29hEjGJssEkY719BKEQyPPVNKkoKpOfXIU5ZWR1HFkhF+f4DOf/iTvfvtbGS5PMTo6xhNrn+Te+x/gqafXsX1gN3UfLNvFdrJYlou0XVTokXc1lalRvvE//8UVl19KpVLBkoladNobKynQDZlWNrRySaBXyojMmgeciiAKzZi5GROgpvUZ04Pmp5swzbMHDRyrqft1spkc73z3+/nDX26krb2LUDkIK4MlBUHgY0twXAulQoLAw6tX0VHA/PnzmTt3Pl2dHQwNj/DiSxux3ByerxBOG+e+8jV09S3gqaefoe7VsV3joIE2eLqT2MZIQXlimN0DG5HCixuFsQ5WmrzdCjwyIuJd73ob73/vuynk8kyUxnBd03cQ8cAaHWP8Us4kXsrUCOxkQ8yg8kc+2WyejZs28f53v4vOtjxnn3U2CxYtYHJywgSewNR0bsalVqszsHMnI2NjeEGIEBLXcejsaGdWbxedne0NtnQ+n6der/Hc+udZ99xLzJ6/kAsvvJDS1ART5SkefOh+CnmXK974eubNmU+9WmPjpo34vk95apLH1j6BtDMcteZ4Mpkcbi5Pf38/8+fPo7d3FuAY5M2StGez/PPOu/n05/6LrTt2kW9rJ1voJFIGNXKdLP39c5CWhRcqAi0YnZgkUuC4OSIlUiOnpDkNRQShT600hgrKSFVH4lEs5jhq9WoGh4fZsWMnewaHUVqQyRdw3BxCZFFKo7QgDBVBpMg6Fq4MUEGVBbO7uO63v2DBvLmgggZsvb9R5ZFWjXolUiq29tIzPBmsz33uc5+jSS2YPLlBYmRLbXDjOEpNQMo4Ln+56R987/s/paOrn5ov8COXxQccTLGtGz/QBEpSqQb4oUZaLplcG9l8G5VqwK7BUV7etpuxibIxTo4kocpw4ilnM2/BUp595gV8r47lxHoSpJlOJkDrgIwtmZoYYWRoACnqaPwYhQGhAyQKr1pi4ZxZfOMrX+TNb7wUjcbz64ZomRpmo1tYoja+UnzXvc6YeCSZ4zps3bKF++69m8MPOYRatcbU5BRtxSIZ1yWI6Q1J3tzWXqS7q4tsJkO9XscLIuqez/DwEFNTk+QLeRTw4sZNPPHUU0QKMtkCNc9n2QGLCYI6991/D1pFvPr881m2eImR8mrF5MQEda+O6zr09/ezefPLDA8OMnt2P67tMD46yvjIKFGkKbS1kclksB2HWhCwYvkyTj/zDB5/4kkq1TqO4+LXfVzbom9WD5mMQxgGPL/hBaQlmT17tvECUxqE0XkYrp5GiphTLy0s2yH06wS+h9bg+SEbNm1laHiCmqeNm3yuHYVLhEMYCTQOws6QybVRbOs0ny8ytcrg4B6Ghoc4//xXxsRTK96Tap/UdtW0GRJ3fWnJRg/Gnl7kaWq52ifZsFn4ZDToBvV6cdNmPve5L6BwqPkaX9ksP+gIjjnhNHw/RAiNV6swPjbGtq0bGB3eRTU2PzabzjZaAaVxnAw1v8qaY05i6bKDeX7DS9SqZYzkXCC0HTtqRI00Z2R4J8ODA1jSUOOljKkbMUO1NDHKxeedxWc/9QmWLV3ERGmMbDaLtEQDsdGKGZMzmt0l/+3QlthsWmDR3t6FbWXIZnO0t7UxOjbCxMQYs2b10tMzq8E21Rg4vFgo0N3TQ19fH5u3DzA8MoolLUoVn6fWvYDneWil6OjsI5vNMDmwi0q9ThAGrF37COPjY7zh9a/jkFWr4gwgIAjqlCul2HhaMG/BPM4683TuuvMu1j76EIcccgRz585HR4qXnt/A4MgYKw9aSXtHO7Zt44UBu4cGzaJ2swR+gCCir7efjvY8pXKFDRs3I4VgaHA3QaSYPXcRE+OT1P0QIZzYwCEyShgh8bXEsgWFtk6qKkASogVk3Rg2VprACxCWRNoulutiuTksy8GyHSzLRakAV2Xxoyp+GJBr6+C2O+/i/gfu58xTTqNe8xqS4n2NBJxhipjoc8R0gS6lxPrMZz/7OdliQPtM2oe1l7mboQ0LypVqw87xC1/6Gvc9uBbbLeIF0NE9m5NPO4dt2wbYtmMXdT/EyeTpmTWHpctWsnTZSubOW0yxvYd8rkjgB1QqNTNMRksWLl3JsSeczkubtjEyPErGsRqsHUsK02wUGktEjAztYnR4BzLubWjM/HHLEjhSENbKXP3+q/h/X/wMvb1d1L06GTebWIBASrkmZEJ5jm18hEGU0rLg/QnB0IaaPzY+xq233MK8OXPI57LkCwUcx2Hnzp0MDGxHCMjn8+Z54wZgGEZkMi49PV309vYwMjzG+PgktUoNIaCvr9/A0sKiXC6hopCR4SE2bd7MK899JSeecCK2tGKHF0m1WmdocATXzcScpYg5c2Yzt38eGze+zOYtWyiVq1iOQ7aQpVqt4Hs+XV2dFNsLDA6P8JnP/zcvb9+JlBa18hS9PR309c0iDCJe3rLNzF0XAimgUiqhlKBv9lzCICQKQ+PYHtNuSLmiuLaFbQvCwEdImzAELR2klSFXaCNf7KRQ7CaTa8fJ5JCWg4hbDwiT6kZaoaPQdOajkPXPPcPZZ51JNutSqxsxX5pKP3PybWxikTS59d4aJVvoxGuVJrHIdCdYKb2f+XyCbDbH0+uf59Z/3I7l5AmUQNouhx+xGj+MqFSrSAHlcolqpcYusYdMJovr2LS1tXHAyj7yGRcdeExMjrF122by+TxHHHk8u4dHGR0dJpOxUMqQ6bTQDZ9Z29JMjAwxNTGMkUokxD/LoFXaR4URn/n0J3jbW15P4FXRWsUXTqeMa3RDy6LRsUNIfBEt2XJI5Exji4TBbOgxKhFNicYz4/vmpFy0aBFTU1Ps2DHA0OAwixcvpq2r09w8aWPZDpPjY7y8ZRvDQ4P4vllkAtmg0WsdYVkQ+FW2b5viiCMO56yzz8Sr17Fz2XgUtYibvbEuU5tu9cTkGHPnz+aUU0/gnvseYHR0hA0vbeLAg1Zw+BFH0j9nNt093UQR/Oxnv2b79l10tncyPjrCK049nivf+TZ+8ctf8+CDawkDo83XcaBpL+SZGh9EWjY9PXMgKuOHiUl07PGlDIk0VBbCLiIz8fDTrINjZ7Fd17jZaIHGUExUPIGK2OQhmb+XyRXxowAVVbHsLE88+Rzf/f4P+cLnPk0Qu7ykwZakfg7D0HR3LItQ6wYjuVnYZ0dNIqVpLrxqjEebwbVpQrEcx6Lu+3z+C19idKJMJt9DpaaYt2g+cxcs5oWNW/B9H8fNNHI+AVRqVSYrEcNjozi2JJ/J0VHspKu7n+NPOoBcLsPYyATbt2zFksoIsKQ0NqQiHmYpNROjgwzu2YnUPpatCANzQRxp4VcrZLKKr33jy1z0qlcyMjFCIZuJLTLVtIdUgzHb4DPGCz8+Le2ksRTTorXEdR204xAGQWOenoh1IiI1z13GjTMtJMKykFIQRhEdnV20tXcwNTXF7sFB6p5P/+zZ+EHI+g0vMjQ0hBCC9o4i4+OTMaVjenxdQva0HQc3lwE0GzduZOGC+QaRjB0TEw9drc0CE3HhOrBnFzt2DaCU4sCVKzjnlSt4eds2Fi89gAMOXE7gR3zjW9/nz3/+G509vZQnx1k0t59PX/tRFi6ajyU1G15Yz65do0hh+jUWgtCrkXVcxof3gJL09vQxOTVFEAZx8IltVrWFac9aZIr51AmcaM2Jey8CpEYqPYNSYgKksTl1c0VKkzWUH1Fs7+JPf7mJSy55LYcevIog8JCxZavE3mvCwF6Ty5rEejLtBpEuVtJG080YcnJEKaXI57LcdPOt3H3fg2Tz7QSRwM5kOfiwIxgZn6BcrSFtp8HgDYIgnp1htNsZ10ZoqFYq7B7cyfoXXuDpp9fzwgub2bh5IyrycSwLLSyE0Di2QKgIG8XUxAiDewbQUR0VeQRe3cy/swShX6K3M8dvf/EzLn7VuYxXxsgV8nFjKJkvGBKG2lC5LRcVGVeRBKK1HauxOZJTRyDIZDPccuutfOzjHydxuxHC1FAJsTNd3EvLQiRs2Zg5a4zgNO3tHfT2zkKFEYN7hrjvgQcZHBqmUCxSKBQIw4Aw9IhUOMOyRggLrSxs6bJg3mKqlTp/+fNf+etfb2LjS5tRkQSh4nEIEbYjcVybsfFRHnzoMW699U62bR/mkCOOY8Hi5UzV6hxyxOEcdMihCCm57k9/4pe/+h2ZXB4VhWQcwSc//gGWLJhHrVRm1YoD+OF3v01/bw+R75FxJI4tkCpCewEWMDUxQqk0Sm9vZ5wCGd8y46gS26XGHhqhNhbADWVnbHCHSoiEMZkyCTjKOJJF2sKyc2SyRZxMDsfNMVWq8dOf/ZIoCmO/YtXIEBKpblJONFNNkv+fMSe92daxefTZXuYNmElPQsLEVIlf//Z3WJk8WjooYbN0+Sq6Zs1l9+AwouE9plNuFUa8I7VGR6GZrW2DZSssqajVqwzs2kWlWkNYGSJtOta2BEKPrK2RocfI0G4zGpcIIaKGHY9Xm6S7w+U3v/oRJxy3mtGxQVwrtsKMbUYSSoRjOzENPWHETmswREMjGevGVYRjWwxs28G3v/4Nbr7hRu647TYc12mcslpPT3YVOqllomnPMD2d+6bpDcW2NqrVGpVKmc6u7sZYAKUU2WyG3t5euru7UwxowwGqegHjk2V8XzO7fyEjg+Pc8Jcb+e1vf88Ta5+iXg9x3TzVis9z65/nrrvv5fkXXyYSWQ454lh6++cRCE3v7FkcftQRFLNZHnzoUX7zm+vo7Z1lHCzLE7zrHW/htFNOojQ5iVJGelDIF+jq6sT3K5RLEzGEZ8XNVR+hfUaGdjExPkZPVzdCa6IwjOehqNSM+NgWW6Tto5LxDhqp1bS8WU+DSGZtWSBcMtk2NC6htim0d/H3f9zJv+57gLZc0cyVlKAsHbvT6xkOKEn9nR7smfzcbjX7PF2PpBGrRhcdQxjL2C433HwTDz72OF2986n5knyxg5WrDmXr9l149QCr4Q7erF+eXkwq/uAicS5HmwEwWhIkO57AMGZ1BEHAzu3bCfwSUsbsWa2wLIFfnaSjYPOzH36H1YcdxkRpxGgGtNFTp/lC0xGk2YhbxHluyj9UG9267bj84qc/ZfvmrXR0tPP1r3yNA1euYvnyA02xKg1On8x/TxqLzLCf2VtXo7RxsZeWRZBMRULT0dGBEJ1GrER6hqSRFOSLRU4+9TTWP/csg3uG6JvVQ7anm5GRYW655Z9093QT+AHlcplqvc6Kg1aRK7QzsHMPm7du5oyzzqKtvY0FCxfQUWxn/Usb+fL/fJN61SeTzTAxOsyZZ5zG6y+7lFrdM3wox7Cyf/SjHzExPsryZQspV2sMDk+Qy7fjR0FsxmAEYrsGtrN02Qp6ersZGZloNOpM+pcyLtXExhHTG6dZwWi0MLEpSELrFxbSzmO5BaK6ItIKPxL88Ic/4/RTTjKTd7VqyB2s+Hc1e3swNPOx5L7s49OwbrMgKvB90DBZLvP7664nky1Q9yL8QLF4yQFIy2VycgLLnukU32pS1QyOfGwziVZmGmuKJWoWWYRjaXbv3Mbk5DDoOiqq4kiNJcBSAQU74uc//h7HH7OaWm2SfK5ALpdDWlZjJLGMHTKc2GSuGc5O2MSJyMnzAup10/x7+OFH+N/f/i9z58wmm8mydcsWvvaVL1Mpl6hVKw2fsBm6aCn2q+FO6grf94miEGnFOn9k41SLUg2v9Pz2KDR0ltWrj+KI1UcQCRgaNaYM3bNmU6p4bNm+k2JHN6eedhbLDziIRQsX8cY3vJ7Xv+Fynlz3NIODw/R19jI1UeZb3/wOY6NjdLS1MTEyyKEHr+RT/3ktjmsb9Mw247q//4MfsXbtE7QV83zhc5/hf3/1M3q6CgT1Eu2FLLbQaBWgdYDUATu2bUYITXt7gUhPK/uSkditpN/NtUFLzUbieiJtsvkOSMY45Dt45LHH+fMNN5JzcgS+cbInxZNTkZqhYUom3s6wJ22eQru3EfXeNzfwQzKOyx//9GeeWvcsbrZAEEG+2MGSZSsYHB6L3QHlPvwqW9gBCYEWVmzzNF0tN7xD4obg4J4BSpMjODGuLrWBeh1LENSqfOEzn+TkE49nojyG5VqJ89UM/zUhp+nOQooZxmTTD5w5N8K2bTwv4Hvf/wEqjDj4oIPo6uigo62N+++5h3/+/RYKhRwI3TAry2QyZoHrfz/wXsUnVMpM0fCadCIHSC2aGdfNgAdRpOjs6uLwI45gxYEHUvUCBnbuxnazHHPcCRx51BraOrqYPWc+qw46hF07dvP+976PsdERzj7zTAKl+fHPfsGzz71Ae3snE2Oj9Pd289//9Rm6O9qIAp8wCijk89x++x3c+NebCYKQyy55DSceu5rDDz6Q73/nqxTzNvVqCSnMaW8RYVkKrQJ2DmzHzbnkstmGC07zmvh314lm8+yE6aHAclxy+QKh1oRItHD4640348XOOqSM5NJy7/2tedk8I6HVaLXmD5DJZJgqV7n++r9gOVmEdNBIDlx1CNJxmSqVDPmxYfUZ5/J62mc3jUPP+OQt/WCN2Kk8OcbI0B6kpWOHDAuJBZFmcnyUq695H2960xVMlktgucbsQZPy9o3HKGPF6Jye+fo0ORjqxNnPJpct8L+/+TV333Enxx5zNN093XiBR3tHO8V8nut+9zsmxiewbZtsNodju6xfv54gnuA6877rGX34lK1tk6GF2Fe/vhE4LCnJZfOmxxEzqntnzeK4447n6KOPYc+eQR577HFy+SIHH3Iwo2OjfOlLX+bKK9/N7P65fPY/P4XjuPzyN7/lppv/TjFfpFYpoUKfT33qEyxftgzPqxBFPvl8gTvuvIvvfPf71Osep7/idN50xRvRRJSmJjj9lJP5wuc+SehXIQyMhEBHWDrClpp6vczE2BhdXZ0NswQh/s1+aJFxNEzCG26QBtWKtDZaGcvGD0La2jt5/MmnuPu+eylkC/HmiCclo//9ZgRklDI4/ncOJ4ZnZGx3/nXvPazfsIl8voPQV3R1z2LJ0uUMjYzFQyZjbyxtLMKEjoevxEYKif2+kBaNgQHamKYpIYmEIEQjbQtQ+F6VHds3o5VvaoGYfSyFZmpynCvecAnvefc7KHkV3EwG13IhMuQ/FSp0qBGhQCiJjiDwlBnlhGjhWC9iLYyFigSOk2HPnt38+hc/Z/6cfg5ccQA1v2YGy0go5LNseGE93/jmN6h7HmvXPsZ7/uPdXH31NXED0ppmK8R5c8MKWycO8yK+aWLahjMet6a12muBhJGZwFWrlFn/7DNEYUQuk43lpz4TUxP0dHfz/v94H1/60v/Q1tbBV/7n63zsI9dy1113s3zlCr72ta9QyBf4x+3/4o9/vIGO9k6CaoXdA9t493veyZmvOI3xiRF836etrYMXXtjAN7/1A8bGJzn44JV84P3vRilzelm2Q7Vc4fLXXsyXPv8pnGQMhIqMJZ7ycTBTfEPfo7u7w9R5jRmKci+1apqW3hA8SQvLiuFaPW0fZJAvQYSNsNxGAKx4Htf98c9EgsaELpWoP1uMYWueSWK3mvTUyidLTDt6EUaKP1x3vSkchSSKQg5YarDz8dFJLOnGThX7iQza9AsS5EdrhW7ak8Y1PcASioGd21AqjH04tGHy6ojS1CgnnXQsn/vMp4jC0OjfzSSbhrdWghwl54X5bHsf09Nw9vSJorXGsS3+cN11bN2yhddefBGWZeN5XsolQ5LLF7n5ppvYvmMHj61dC2hOOP54du3abRwoLasxB4N9Rc3Y1d5QXtReBkUzBtxLQaRCNIrHn3icRx57lEMOPYwlS5aQy+VYteoQ5s2dx8ZNm/n2d77L7XfcxeT4BN1dXcydP4/Pfu5zLFu8hIeefJrvf+8HFPM5JJqBod28/vWXc8UbXsfE5LhpljkZPD/iq1//FqOjE/T19PHha66mWMjg+/Xp8QxSMDY+xmWXXcKeoTG+/q0fkC924Pke0nWQwkaokIHtW1i6bDnFQp5ypWb8CpieCdNsGjczYCcP0vE0rSSaxPWalthuhsCzCEWEsB3uf+ghXtq0mRUHLEJHRkKsItXy3u81cW1fx1k65Up+KQyN2u222+7gnnvux7Jc6rUArYzlfzaTwbGtRuHVXH/s5RieeOCK1i59MjanmZoYoVoej3lXylCzQw8V1Vm8cDZf+X+fpaOYw7VtMxcwgWcb0TgZxyBbmnTvvTlMYy2KAjKZDM+se5qf/OiHrFl9JLN6e5icmjSjrhOUT0gKxQKWlDz6yCMcf/xx/PBHP+Sqq95NPpfj4YcfYXhkhFwhj+U6qaqoxR5R0/ngNCw83QW2bZvx8XGef/4FRscmOeyIo/jAB6/mpJNP5dlnn6NSqXL88Sfg+z5f/do3eN/7P8hNN/8d23ZZesABLF95IB/44Ac559xz2LJzN1/+8jcplSpIKRjYsZUTTzyWT3/y2nieicB2XFw3y9e+/k2ef/4lpICrr/4Ahx92GCq2KLJS7GbXyRCFIe//j3dzztmnUa9VcB2B0CEq9HCEJqiWGNy1g862IrZl7wXr7c8/dzojnp7XDhpLR1gqROiIrJvBtRy8uk9newf1Wp0777iTjJVFMHM2YXqcWxoASU4vO424pHkozb2RxFJea02hUGgIgYRTRAjBY489xMnFTg5cvoT1619ESTP4xo6F8U2+JY3eQtq0OFHiTVtcamrVMkN7BlDKzN5WKjRQL4Yd+sUvfpZVK1ZQ9yo4toMUgjAew5C8ZyFkw7lQ671nJib8nCQnNs0rc028ep1vfvMbRGHAoQcfzJ49uykWOnAcx5wKkcHupWXR1lYkjELGRkcZGBjg0IMP4ePXXss//n4rTz31BC9seIGVB65g3uzZRErHE1Ulvj992obpSVI6zpe1uRe1Wo2dO3dSqVQ4YMUKDj9qDR2dXYyOTXDiCSfzxjdewfbt2/nWt77D3XffzeREic7uHpYuncvs2XPIZF0OWrmCCy68gHKlwle//g02vLiR+fPmMTS4h3mz+/jkf34MKY1nmW3bZHI5fn/d9dxy6z/JZgu85U2v45Xnnkm9XiXj5FCEhCoCNW2MEIYhdgY+99lPsWnzO9m2czfSMuIyFfrYlsvk+Cjt7d10d3UwODza0GKQku6K/RQoYkYxK0AFWPEc92ppgsCr4QgLIoVXrUOkiHQ4XQ+nZj4mvZBmrp1lWdMnSCui4t6TRY345LRTT+InP/4etquIojrSgiCscc+/bqY8PsiqFQdgJYtU6FiolHRPDbSnG186NRkyiRJmGIzlSEaGd1KrTqC1hxQm3XIdSbkywetefzHnnXkmFb+GjCNR4vKd7reYj6OM9HOvcdPJ/+uGobKIbTALhQIPPngfd991J6eddBICQRSZ7q6BgGPFuzCj0mzXpdjWxoYXXuAnP/wxX//619m+YztXvPnNvPcDH2LOvEU8/vg67rn3QYaHRgwfzBgCx+NK4k6vMjM2pCVxHJdIw9bt23l2/Xr8MATbJlPIm9HRGlauXEF3TxvX/f63fOxjH+Uf//wnCsHiZUtYumwxBx64gu7uLlzH5YyzzqK3t5e//f2fPPLYk/T3z2J4cBCCgGs/9hEWLFhgRiR4NQr5Ag89+jC//OVvsIXDma84iddd9lrK5QoIzDyU0BASiR3yERbSdvH9iCUL5/PZT32YYsE17GoiLB0AARrN7j27CKMaxaJLqD20FY+3Tlw1EXtvFB1PCNYpAENLI/kFqlNjeLUS0oqwMordQzt4y5tfz3uuegeeX42nfk2zdpVSpnnZxFJPahvrM5/5zOeau+jN6UejRxIPjClXyiw74AAOWLaMm2+9FY3hBEWR4uWXt7Jg/gL6+voYn5hA6QTT100DJXUKuEq9rjBGChKoVSbZNbANW0Rm3rY2Y80Cv8bSRQv46pe/RDbjYMVNNZnyYVWq2TZ/73x+2pZoepaEiJtYAsFUaYpP/ucnEEpx1hlnsHPngJnOFI8iGB4dw/MDw/SNr6HjuFSqFQr5AvPnL+B3v/8dL23cyFFHHMm5rzyXZcuWMTIywpNPr2P3nt0Ui20U2tqwbJtyucyePYN0dXVj2Tae7zOwazebNm+mUGzjlFNP47gTTqR3Vh9hFHL8icdz0EGH8ve/387/fPlr3HXn3ZSrNdo7OrBth/nzF9DbOwutBV7d421vezvHnXACDz/2GL/4+a8JAjP9anJ8jA9f80Fee/FFlCulBhK3ectWPv/5LzE1WWbJkoV84hPXkM3ZsUkbCMsQVXPZNlwnY8zrbBvLMpR136uzfMVyytUa9993P7lsvuHrLm0b3/ewLElbezue5xMlw3gS5PPftAXMyRHXEjqgPDWKVy9jWwrHjpgaH+TS117AN7785ZiLJnAs2wAh05wQsxEsabzK0k+PNilWuoOYPj0Sdzs9Y4qpJpfNEaqQSy48HwV8+KOfIFB1bDtHtV7iztv+wtlnX8CKpQt5fuM2hOPG7uqqMZhGINCqNXhp0LKAgR0vo8M60jLTAW3bwpYa5Sv+82Mfob+3mzDwyGYyjRQuCsNpT2A0YRTEF9wmCEIs6TQ+V9pvVmtt8mEhCMKAQq7Ir3/5C+675x6uuvLtRGGAjn2B/TAAy8J2HYQlDREzNb+7o72DHTt28KpXn88FF17AD3/wI97//g9y0skn8pqLL+IdV13Fhg0b+Mett3L3PfczZ3Y/Rx11RIyiCBzH5uWtWxkaGaXY1sa5572SJYuXEoQh3T09nHXOufhhwN9uuoXvfe+X3H/fA/iBjy0lh6w6iDPOPBOEplapsnnzy0xNlXjve97Laa84jZe3b+enP/4V1VINqTU7d+3k8ksv5q1vvoJ6vUYm4xIpje+HfOfb32dkZIze3m4+9KH30dvbRbVWpa2jHYFgfHySx154mo0bN7Fnz2527hygXC7TN6uXa6+9lnw+R71W4z3veDv33vUvnnthM4Vip3G7j2pIBOXJUXq6eykWc0yUq6ZlEMUtgSZ9+Izshpi8RUjg1/ErE0RBBceOsGREeWKIt15xCd/86ldARYSBOfGTlFgJYp6WBlsQCYUj7Ol5k/GELuuzn/3s51qNym2efy72UthpAh1w4IoV9Pf18tCD9xPU6sZEwS+zc9dOFixaTNesPkZHJ7ATUYoQKJkK640vU6wnFI3xsWGmxnZjS40gxEJjW5Ly1ASvPu9sPnr1B6iWS2QzGUNrEDo1hpiGdahli1hfnsyBaD62ZyJZkVI4jsP42CgfufpqFi5cwDFrVvPcM8+QzWZQWuEHIbW6x1S5gh+EWJados9ostksXr3Go48+yvmvPp9LL72c+fMW8Oijj/LH669n9549rF5zNKeceirdnV3sHNjJhg3PMzExzsT4JDt37qTm+4xPTLBg4UKWHbCcnt4+Dj/8KBYsXMw/brudz33+i/zlLzczuGeYfCFPGPkctPJg3vKWtyKkwHUzLF++gqGhQU4//QwuvuhiJkpTfPVr32DXgGEK79y5g2NWH8knP/lxclmn4arpOFl+9IOf8Ohjj+O6Dlde+XbOfMUriFTI5OQUjzz8KP/7m+v48Y9/zs23/IMHHnqIp556ip07d1Kve+zevYve3l4OOeQQPM+ju7ODtrY2/nnbHQjLMlJpzP2OIo0UNh3d3Xh+SBSZwUmiOXtptuNBIUSI0D7V8ihRUMW2NFIEVMvjvOn1r+WjV/8H2YzVmOqrYysoFTt5ahHTTWINkIgN1pMsJIoik2I1j4BuhrtmTL6Niyhiq6BKrcqxq9ewYvlybr/9dnzfJ5fNUq6W2TkwwAHLVtDV2cXw6AiumzUGX3ERLuIJQWbmdzJ7SYAO2LPjZWTkGUMGzDgzFQW05R2+9Y3/oX9WF1rEM0niDwexMZ0yHlS5XJHt2zfzvW9/m7/f+neKbW0sXLiYbCZLGAWxebRq6EKU0uRyOSxp842vf5Xb77yNt7/1zWx+cQO1ej22DlUI6TA2McHo2BiZfC7emCo199wAGeVymcnJKY44/HAWLlrAK195DkuWLuXhhx7i1ptvZmjPIKvXHM3Jp5xGX38/w6PjDA4Nc8RRR3HJpZdy4MqVPP74EyxcsJDzX30+//rXnfy/L3+ZP1z/Z8YnS+QLBRAay7HJ5wu87nWXMzk1xW9+9WsefOAhcpksrzr/VZx40kk4OZfv/uDHPLNuPe1tRbZu2cSCuf18+UtfpLenG6WNx2+h2Mmvf/Nb/vCH6xFacsFFr+byyy7joYcf5ve/+yM//vFP+cMfr2fL1q10drWTcR10FKJVZDrkUUgUBWzZ+jIHHbSSOXPmUKlWWL58ORtefIlnnt9AJpdH6JgJIW2qnke+0I5jZ/E8H1skYH7Cz4udZGKQHx0hLENlmRobJPTL2FIglI9fG+M/rnwr/+/zn8WWouHvljQHE3dGIa04qKaAqChpmiuD0EkLEUWRbh4asr+RbA1EK/GJiiv/vJvh4cef4F3vei+79gyTKXRR9TSW285Z516AW2hn48atREpi2VbDX1bGz5Gwe7FgdHgXu7ZvxJURUehhCY3jSgKvyjXvu4pPfPgDeGHVfADAq9ewLEkUgecHdHYUGR0Z5rrf/5abb7qROX2z6ejoZNOWrRyw4iAuvfRSjj7maFzXpVarxemVg+NkeOaZZ/jWN7/OnXfcxjlnn8mxR6/mvrvuoq2jAxVFWJaN5WbZMzTEy9u209ndPWNOeHp4S6VSYWRkhI9+9KMccdQR+EFAPpdHKcUzTz/N3266iaHBEY5avZqzzz6T9vZ2hoYGY2MGj/nzF9DZ1c26Z57lZ7/4GQ89/CC2myVbaEMrExETrtvChQt559vfzj13383jjz3B5GSJV1/wKr7xzW8QRhG33Horv/v99XT39LJz+1aG9uzi29/8OqeffrJxclEB2Vyef9x+J9/42jfJ5wrMnzuf4086jsfXPsYTax/H9zza2jro7unGsiS79gwwNjJCxnVYtHgxC+YvYO7cORx3wnH09/fT09NDV1dXoz3w5NPredPbr6IexGNRtUDaOXxh0d7Vx6z+RQyPTMS1rjWTXtKguRvGt+/XmBwbgqhMxlJIQspTw3zofVfy2U9cS+B7jUGojeGwySkfa3NimmyjdFChihWGKqbE24gwDHXz/ITmIr25y95glBp7KASaIPLJZHLcc+/9vPu972NsvI6dKVLxBYW2bl55/sVYdp5Nm7fjR1HDGaRxhKrY/FkFbH7xWaL6JBYRKvKxLE3g11g4r5+//OF/6Z/Vie26ydRy6vUaaE1bsYN6rcLfbvwLN9xwPYHvccqJJ7Fo/kKEFOwZHuSpp55haHiUA1eu4sKLLmbN0UfjuC4DAwP88Ac/4ro//AHfr9Pb1ck1H/oAm158ke3btuK67rTNkZ1hZGyclzZtpr2rE0ta8ayPvWfNDw4OsvKglXz4Ix8hExsyRFGI4xifsWefeY577rmXHQPbOeKIIzjpxBOZv2ABHR1dDOzcxe+v+wP/vO12hsdGyBVy5PNF40CIQEeG76WUYuXKlVx44YW8uGEDt9x0Cxk3w89/8TOOOWY19z+4lu9+7/sU29rYtXMXWza+wNUffD8feN97KFVKgCbjZnlx40Y+/p+fYnJ8ktAP6OzoJIh8pqYm6eropFgoMDY+zp7B3di2xeLFizj5pBM49NBDOOyww+jp6cHNuCaNiVTcGDX1Zmlqko6uHj5y7af57e//TKG9hyjC2EllsvjaZtGilSjtMFUuz5iPaPqABtWy4um542PDqKCKLTxsEeCVx/jQ+9/F1R94L5Yw5guJ+WHDlD0mexrOn0hNLMYoSCMTKFVkhoDatmMmTO3LNKsVFT6RbzamgsS0YVs6TExMcPJJJ/L7//01V7zl7QyPlihkuwm9OvfffSfHn3Q6c+fOZvuunXtNKRJC4GRcBnfsolaZImeruDFoctVqtcIbX3cZixfMp1qdQkjbjDDQEW2FLjy/wh133Mqvf/lzNr20kQUL5nPwylV0trVBFBCFEbN6Ojj/3DMZGR3j8afW8V+f/zTHHHsC/f1z+OnPfkboB6xefRTr1j3NxRddhNSaHdu2xeOhVUN1GB+eKGWYtJaY9hgVKVd3x3Ho6e5h27bt/OpXv+KNb3wjXV1dlEpT1L06WmkOPfwwVh60isfWPsaLL76I54fUPZ/rfvwTbv3HbQyPjtLe3smqVYfiOg5jY6NUqxVjTxT3dhzHAA9+4DNv/nwOOngV55x1Lscdu5oNm7bwq1//mkJbO9VKjZc3beTcc8/mne98K6XKJEEQkMlkGR0b47/+64uMDI9Sq1bJuhlsWxJhnEpqlSovbNhAX183b3rT6zj11FNYsnQJ/bFG3oyZDpiaqhlz8ripqeKJwpmMiyXgDZdfwq3/+AdVv4YSLlbsyRtGDlOTE/T2zqciakTxYCNDKtRxTQJKhYyPDxMGNTKWobJUypN8+mPX8LEPvp/J0ihC2rHDv2qAT0kbQyfDiRJDXJ1MkpJoKz1ESyfWo6He1/CQMAyN9aZlNSJoYo0Su+PEs/OSISiCIIpoyxW47e67+MCHrmW8FGG5HfgBzJqzkKOOOZGB3XuMiCVK9kkUj1UO2LzxObzKGLaIsHRILuugVJ2FC+bw1+t/T0dbIRYnSSIVYluStY8+ws9/+iN2bNvCoYccxMEHH8LWLdvYvn07tiWZN3cOSxYvpLOrE7QZIiOkZGhohKefeZaXt2xjaHiYj3/8Wrp6ennb297KGaedRiHjmoamkAYd0wpp2diZPEPDw7zw0kv0z5mDJeX0zJO4XySlpFqr4XketXqNKFK0tbVxySWXcPzxx+NmXEqlMuVyhWKxyLHHrmH3nj38+Mc/4/rr/8L4ZIme7h5m982hf+5sOrs7IVLU6zVGh4bZuXMnE6VyA+SY1TeL0047lXyhwLJly7nkta8lCEM+/19fZGD7TvKFAuufeZblSxfy4x9/j1zWaL2ltPD8kE99+nM88eTTqDCis7ODOXPmsGnjS1iuQ2lyglk9PVx88YWce97ZLFy0sAH3R2HUmDRrWUZ4FoWRMbuImdM6no+itaKQb+Mj136C3/7xr2QKnaggQGERiiy208EBBx5JtR7GbAUr9vg1zVJLQhhUGB4eQAqF1CEEJT5x9Xv4yPvfix9WZzScSXHsgPgexcHYdoy3se83pA3JZLMgdmiUwjIoVjMnKZ1ypWWIM3ojKRRIJGbgsWu7QtPT28fNt/yD0fEyCBulJYccfiR2Js/4xEQ8HCdR8ilsS+JVphgZ2oElQuNWgiaXsfFqZd777is57cQTqHu1xvQqaQm+//3v8J1vfpWDVy7nvHPPZvGiRTiWw6xZvSZFCCL27BlkaGiYWtXDzeYMTB2EFIsFDlx+AAvmzaVeq/Lii8+zeOkytm3dwSOPPMzY2Ci25ZDJZmLprelsW5ZLpVZleGSUQrHYoF8nESoIfIaHhxkaHqKrp4tLL72U17zmNURhyN/+9jfuvedebMumr6+fJUsPYM6cufz05z/jY9f+J7fdeTdeoHDcDAetOJili5YZTyxhLEhd16Wnq4v+/j5ymQydXR2oKGRsdIR1656mq7ObK6+8kvaOdn74o5/z9NPP0t3VxaZNmyH0+fxnP8mKFUsYHx/DsW2CIOKb3/oW69Y9RyaTpaezk66uLp59Zh1TpUk62gtcetlr+OR/fpzTXnEK+XyBSrnacH2xLMuQTmNOGk2j3aWQM8ZKO45LLpfnrzfdhBDGIV8LgZA2fqBwM0WKbe3GDkqrxsLVMYgjhML3Kqh4wrJfneT1r3k1h646iFD7DdJnokicaa5hNSaXiZi1YIY7xTY/jftoTkAhwU7P9WhGsZJ2+/748iLuKGskkTaCFEc67Nr9Mlu370DILEoLcrk8bW3tTEyV4tEEif446RYqJiZHUSpASqMQFBJKlSn6ers463QDMzqOg+/7uJkMYVDjoQfuY/7cOXS2F/HqNUQ2HwtfBLN6e+nr7WPXzl1s3baNLVu2s2vXbhYtXsScOXPIZDLsGtjBju3b6WjLs3nzyzi2xf/7ny/x4P33c8+997B540YGBncxb+5cZvf3xd1vAwcnbaZkSkUYhpTLZSqVMu3t7Zz3qldx+hmn09XdTb1W57LXvY6TTj6Zv//9H8zqn82qgw/j1n/8k1/+8lesW/8MtuNy8KFHsuyAA9ixbTvDI6Pkc0U6ujtwclbDpDAII7AkCxfMAylZvHgRa9euZWKyxBvfeAVL5s3hptvv4oH7HqKvt4+JsVHGhkd4/3+8i+OOP4aR0SF6enp45pnn+Na3v226+lIyZ3YftVqNhx+6n/7Zs3jDG97CZZe/ltlzZhMGAVNTU7iZLI4z01mzlSK11cQwrTV1r8aaNUdx1GGH8sjap8kVOxHaQmojux0Z2U2xo4OM6+J5daJQNwRniSzadbNEgR+jYBZPrnuaN15+WTxvPS4L9EzpZtxWmYaMEw5ZLAOWsfO4isJ4Fo6PlJapQZpnuKUVhPub7zbtRSuMXBTwfJ+sAw89/AiVapVcsUi95jNnbg+5fIHte3ZAbPWYdE2FkNRrZcZHh3FsIApN510Kglqdc868mOVLl+D5Nax47DGACiJ6OrtZMHc2pckSw3uGaGtrZ+nSpWSz+Uandt78OfTM6mFwaIjRkWG2bNnCnj17jB1nqUSxWCSXLxIpYypRKBY4+5XncvzJp/DoI4/wr3/dyZbNm9m1Z5j+WbOYP2+B8c6K7YGC0GdiYoJarUZXVxdaw+z+2bziFa9g9uzZTExOUqvWKBQLHLhyJasOPpR1657jqvf8Bw8//Ai249LbM4sVyw/kHVdeSU9vL3Wvzj133sPGDRtxszbSNsIwqWIPLcvGkoLJqUl27trJzoEB/vtLX+GCc8/i+Ze38dcbbqS7pwu/XmXrlo286vyzePvb30y1VsZ1HP50/Z/43e//SE9vD67r0NPVQ6U8xfrnn+Pss0/nqne/i0MOPhiEJvADpLTJ5DMGeUwt+qQ52koy0CzfThanY9u8/vJL2LR5CyU/kVxH2HaG0K8RBR65bIYwME4oYRQ1+he2LclksviVktGkW5LHHl/L2OQIxWK+MRKauFBPLH60VjN4Vo7jmH+nVJpCEM+9Fw24126145vRmGbZbKvfkcQsP2HgtPsefAAtbJQ2qr5Z/XMa7X2znWXMVDYGb6Ojw1QqExTz9rTMVmny2QwXnH+eUdwldi0Jh0Yp0/+IIpYsXEy9XmfP0BBPP72OefPm09/fhxaxtahjccDypRSLBXbv2cPuPXuwLIPE2JZDzJBHWJK6V8fzfLLZAq84/UyOP+4EnnjiCW666W889/zzbB/YSXtHB0IIxsbGmBgfp6Ojg3PPO5cLLriQ0tQkN9zwV679xLWsWrWKSy+7nKPWrAHg3rvv4c9/voH7H3gUDRSKbXh+ndVHHMEb3vh6tu/Yxt//fhNnnXEOa1Yfyc5dA0YYpUHG6LrrOtTrdbbvGGDnzp0MDg/z5re8lSuuuIKpesht/7iTaqVOJpNh65bNLFo0n098/CNEkU+pNMVPf/oT7rjjDo495ni2bduB6zpsfnkTURRyzYev5pJLLsFxbKZKU2SzuRhOF40ZMc19slZOhS3HmcUBNgh8zj7zdH7169/x9IYtOG4+Zj2EBApKkxPMmbeIcrna8GwTZjyIGennZBCWRRDUcR2XocERdu7cySGrVsU0GCueDCzwfc/w51JTptIkVZ0WYEFsTKcaCk97X8rBdKGefOgZDcMZ0UHEC0yRzWYZmRzjpY0vI2WGKJRks1lmz53PyOiImdBqG4vJRESvdUS5ZOZ3qCjCRiIFeLUyKw5cxuqjjjSngW1Nz3MIQmpenZpXw7Yk9VqVUGkWLFhIvV5nYmKCUrlEe5fB7YWGp556ipc3bwWgUCiaznpjbqER4pRKZYptbUhZIwoV9dDDdTOccsqpHHXUah5/fC0333IjAwMDRCqimMvx+je8ntNecSpLly4jjEL6gllc/eFreOmll/jnP/6JtB3uve9BfvTDH/Lypk1kMjlm9fezaNEiBgf3kM26XHjhBYyPTXDd769j9+7dqJritDPOYGJqkkKhCKGZZQia4eFBNm7cyODwEI6T4dLLLudTn/40tiX5w3V/4p577qOtvY2xkRFqtSrXXP1f9Pf28NTT6/jq177Khg0vcOppp7DppY1MTkxSrZY59rhjeec738GqVQfh+35jVkbDdCKO8qbelLFHmNivZHVv2rqOTwSPWb29nHzSCTz65DoK2RxepHFci6Bm1KHz5s7DkoIwiEBoYmu0eJG72G6GKKxiC4tyqcaOHXtYtXKlOQXiAjsx8mtGaBvExJQ/VlJXa6WIklF5ljXzBEkv+jSLN/1hZzYUdUMVp1HUPZ+2QhsPPvQwL764mUKxj5oX0N3RjeM4VKt1w/CNYsqaNpT1yKvj12u4toVEEUUBli1Qvsc5Z59pqBvxdFND6NSN+XL1er3huI4K8X0f27bp6+uj7nlUazV279rD7t27DU+oz+TZU1NTsQG2UQ+GkcLKZPjZL37Jo48/yYUXXMTixcuo1z3CICAITe/ixJNO5rAjDuPxx9dy419vZHxijEWLF7N40RK0honxSSxLMn/efA44YDlz587ja9/4Nnf+618U8wWWLFpEX99sOrt7kVIyuGcXPV3d9M/q549/vJ5q1SNb6CDQmt1DexgbH6ero5P2nh7Gx0bZuPFF9uzeRRAEXHrppVxy6aUsO2AF3Z2d3HvfA9xwww20tXcgLc3Q0C7e9ra38IpTTuSuf93Nt7/9XcbGxjnvla/mmWfX8fLLW2grFnjHO9/Bm9/8JhzHbszSmDZVMAuneR5M2OC8zZwC0FoHFrt3KtUYdlSpVnjVq17Jddf/lbqvsC2LeuBj2xnqXo1qtUSx4OJP+MaYTzZU0GihzTTgkkbbklKlyr/uuY/zzjmHanWcQsFu9IqaR4vPmJeenmhgWdOtiwTrtaTZIM2ioWaRSvO8t7SBWcOlzjKzRXShjeeff5EwUEhhEYURc2bPNhNKwyj2UzX8XhUFSBlSmRxHBx6WZWS3jm0jCSkWsrzq3HNMMS9mKtkBI9By7KbcVzZOPtd1aevsYM/u3VTKVXq6e/F932D/2SyFfD6W7pq5bXYmy+WvvoCHHnyYj3zko5x04im89jWXsHjJEur1Gn4QEHgBrpvjnHPO47jjj+Ouu+7k+9/7Pr/8xS+55JJLuPjii1m0eBFPPPUk3//Ef3L77XdQ90Lmzp3PimUHMGf2bBzbwQ/Cxpy9QiGLjnwsC8bGx1i8bDnHnnAcDzz0IACDewYZ2LKFXbu2E/g+Jxx3LFe86QrOOfscZKy/nhgb5Q/X/YGenm7aO9t5+pmnOOvsM3jff7yb3/3hj9z+z9uoViucceYr2LZ1C5s2vsSixQu49uMf5aijjiQIfEOvsGa6+UcpB8LEXILU0M5mkmujGp7BmxKNcaaJwKxWq7F08SIOXL6MtU+uo9DRjV8zcH8UKmrVEu2d3ViWIAzj1D3phWhNxnGxbQclQpxsgfXPbcDzPdrb2xv2r433G7+nximR6v2ZMdKiAQs3iLtJSpakTWlUIv2BE7XYPhuKOtYxIMhms4RK8eKLL5HJZBuP6e3ro1ypGed0y0yC0kzrH8qlCUTsbSW0xrYlXrXO4asOYMWK5XHJIo1DuYrMBKJsnqnSBFOTpRQrV6TIh/F90uD7AbVqjaAtbAi+jPrNNVOnlELHEWTpkqW84rQzWLfuWf72t1v4/Bf+izVr1nDhhReyeNFCyuUKQRhS9zxy2RwXX3Qxp558KnfceSe5XI7x8Um++Z0Pc/Mtt6KVoq9vNnPmzGPBooUU8gX8umegYimwpENbscieXbsIghoXXfxq+ufNo3/ufLQQbN+xnXKpzKYXX0T5Hq847WTe8uY3c/ZZZ9HWXmBifAIhJe1tnfz297+nXKnSO6uPnbsGOPGE4/nwNR/iu9/7Ltu2bWOqNMXq1Ufy/PpnefSxR3jlK8/hk5/8JD3d3dRqhsVr2VZM8tQtPYiTiBs1hgSp2DNX7JVpNKdbKu5oq3g0uLQknZ2dnHzCcTz+5FMmpZMWYTxCvFqZoqe3l1zGoRSE0+MolNHuSMvGsl0IFbaTYXR8gqmpKTo6Y/fM+BRIL37dFPxnOFXGqWMQBPi+H5vG2dOa9Ob6oll+mFZdJcrD6aNYEiHI5RyGx8Z59vkXsJ0MKlJkslnaOrqZKJniKXEaUWgTrSITTYh5+Ymiz/c9TjjuWHK5LHXPuHcHvo8lJUEUcNddd/HVr36FytSooanHXrozRjdoBTrEijeyJWwibWoqJxZYaUDL2MhMRUxNjhMEczn4kEM5as3RrH/+OW74y1+59tqPcdxxx3DxxRcze958qpUS5XKZjvZ2Dl61ijlz5/Djn/6c/7n0MsYnJ1m0aBEL5s1j7py5FAqFBvVeOnYMLJjxDLN6e3niibX86c9/4dLLL+eIww9j08tbuPuBB9mzZzdjIyMsWbSQ97zrSt5w+WV0dXcTBAH1ekAml8VyJHfecxd33/MA7e3dDA8PcdwxR/O2t72F73zzm4yOjjIxMYHWEevXr2f9c89y1ZXv5F1XvZP29naCQJmAgWo0s5QOU3PsabifN4//Jj0WPLFuSqlSk56DjMVyic9ZxrGIIgPCHHbIwWQzrhEtRbH01YJKdQrf83AdFyH8eNyFoYmoSIN0kE4WpQJskWFodJztOwY4uLAM4vF1ssn8sDlbaqz9Js5h8pksGadYrYqqvejFqZw0TJ0olmUQDktrJBZbt2xn18492HaRIFB09XaTyeYo7x6Pu5wq9qvTphCv1/C9ery4Q9M5VZDNOBxxxOFx9zPEkjbFQhuPP/4YX/nKV7jj9jtYtmIxK5YtIiJEiyjWKbdyQmBGzbQXuiKN3bQQgmwmi+u6VKseYRiy6qBVHPrZw3n+uWe5/vo/8PGPfYxjjj+GN7/pCg455GAGhwb51ve+xy1//zsbXtpEZ1cvRxy5hoXzF9DX2xubkyX9niidlRMpRbGYZ/ac2Tz6yKOMjo/T1tHNo2sfZ9eeQWb19vDxa67mije8gWXLluB5HtVqlUwmQxRFZLM5Xtq4kRtuuJnOzg6qtQrnnXcurzrvlXzpv79IqWSoJOvXryefzzE1OcWnP/0pXnvJxYRhQBCEjeZZrFHay/Uxkai2KsBnpOSpVLxZxr13sW7mytTrdQ5atYolSxbz4ssDMetWI2Mn/FKpTC7f1ZhBQ2M8tTHlNrJv83o1z+OZZ59j9ZFHUPcr053xptqomYDbinuYNo6wW8F1+4RzkwtgSZQgZksSN1sM/WPjlm2UagHt7QYh6ujsxg9VXNRJkxNKgRAKiaZWqcRpk8lfbQukUCxZtJCTTjwerSJsy+L5557jhz/8AbfccgtCCD7+8Y9zyOGruPnGP+G4Lm4mi1LeXpLJRGeSGDFIOT0waHqknMCOpwpt2PACK1ceTCFXoFytUK1UQEiWLFnCpz71KZ54/HGGR4ZwnSzf+/4P+cMfr2diahLLsjn88CM57NAjyeZyVCplhoZGcF2HtkKbSR+RCGFo8VqaA87JOCxbtoRarcyOHTvYfN+DSNvlwotezUc+dDXHrFlNrVKmVqsauDcZb6wiypUqf7z+r4QRZHMZLrrofI499liu+dAHcWyb5SuWccMNN8Sz2xVf+/pXOe3Uk6lUKjiOO2PuC4IZBEGdcuLXTRr+VoyL6elbwugsUjqiVu4kQpjGan9/P8uWLuX5l7ZiOVlQEUibKFLUvTptHbapK40OuYE5Cq2wbNesPaXJFzt4/sWNRFFgGn0zOuOiZUMzKRlUarPsZT2U7qRPzylsKsCkNF/xJvH9gND3GyiS0hork8GyLB596ilkxnTPtRbk822EQTRt+yllYyKUmUBab4hnrNjyplIus2bNUfT19ZvCH8UHP/RBfvazn+F5HieccAJnnnUmfX2ziSJ45JHHGRoeQ9q2UR3a075J0zdIT7sopm6s67oEYcCmzZup1evceuvf+eR/foINGzbQXmwjCkMmJiZQWrFkyRIuuvAiBncPc+kll/OVr3yTwcExwsBwjHo7eylm8+zeuZtnn1kPCI44/Chq1Tp+3RAmtdLxgtVYtsXIyDDPPPMMk5NTRJHita99LbfecjM//emPOfyowynXSliOFR/5xljCq3sIIfnzX/7K9h27sCyHCy+4gLPOOoOPf/QjVGsVzjj9dLZs2YJt28ya1cOXv/xlXvGK0/A8j0wmG6elYgadyEpSi5TLR5IuWQ3zDfa5mNIck6QZty90Swij43EchzWr1+DYNrYlY49mc288z0yJsmN5hLTizaqNRMJybKTlNKyo7n/wIcYnJ8lnCybTCQOq1SphGMbzK2Mb2FShbviGRlsfxjaujcJdKew0lWT6qEmMDEw90DD8U8bo2bZlPBZaooTFrsE9bNnyMvc/upY77nmATKGLKHIIZEjv7NkEgR/TjA1KlZDColDheQa9AYNuaDTFfJYTjllN3a/hBT5132PBwgV86zvfIggCbrnl73zwQ9dw7rnn8KrzL+Lxxx7h3kceoz2f4fBDD6Z/Vh+WEARBiFZmqqklNLbQ+GHUiJhhFDKwaw8jExPMX7SYy97wZrq6u/nd737HRz76YRYuWMCnPv1p1hy9mlqtzu9/fx3XX/8nnnvmOaRl093ZxbyFC5k7dx62bbNhw4tMTZbIF4rYdgY3X6TY3s2s/jns3rUDSwtEBNmsy+T4GDt27ODlzZvwA5+zzjyTK9/1Lk466SQcx2GiNEmgIjMrHY1jWQzvHiZXyJMvFFn7xFOse3o9nR3tXHjB+aw5ejVXXvVuXt68mXe9653c/8D9PPzQg6xceSBXX301hx12OFOTU2Qy2dgXIJ7FmMwfUDE2KGKkMp7RboCHcaSU9PT04HkenudNn8AxrUNYorEARUq22txdF0LE9Y4JWH7gc8yao+go5BmvhfEoAIUlHeq+h0Lh2JpKzXiLkZwksX+AbTsoP0LrkNGJKT7xmS9w1umncPDKA1m+4gAKuQ4UIX7gT7N7ZxjSMs3SluxVa9mtm4SyEQ2CSOEIM/mIxDmi5vP8Cy/y4COP8tjaJ3ni6WfZMzJCxQ/ItfVi2QWEtMg5ebKFAuPjZbMvrOkbI6TE92r4frUhlzXGEObUWrpkSczmNPqTa665BtAEYcRhhx3JM88+x+133MG9DzzA+a86j2tOP4uHH7yXf/3rTro62jl69RH0dHeZA1nGTS4dIW0IQ8XEZIWR0Qm6ent462su4Yijjoy7toJrPvxhzjj9TLyax+LFi/nRj37EH/7wJ7Zv327mDXZ0goAzzz6b008/Hc8zZt59s/q577776Ovrp1Kr8eKGDcztm0N7WxsTuRzVWgUdKjZtfImXN2/E8+qccMJxXHnllZx++unkcjkqlSq1WjWZVoYKQjraO7jxrzfy7Lpn+djHP87g8DB33H47bjbDG15/OQcsX8Y111zDuqfX8fa3vY1HHnqYe++5h4NWHsi1117LoYceSrVaxc24MwwPtDZQfD5baOENIKh7Zb74xS9yww03kMlkOOigg3jDG97A2WefjSXdlCtMLF1FUPPqsRtkqt+gmxCxlLNIuVKmv7+f9vZ2do3txM4WUNpQPhI2eSaTxarUGy4nZiyzMA6LtkPkB4SRTbUeceMtd3PDX/9OV0cbhx16KGed9QpOPvk4urs66O7oIpstAiFVr0LdD4zhR8MLTcwY0RZF0XSjMD2UE6TZTUKYo6ceMDU5xfoXXuSue+7hwYceZsfALkoVDz8EJ5tH2UUKHS6RktQ9c6S1teXwAmXc86QVG1CY1EtqQRh4BEEVS0Vxl1QTRSF9PV3Mmz8XSxrelcDM8qtUqmaOk5thzXHHccTRa1j31NPcdttt3PqP2zj37DO46n0f5OEHHuC2u+6lv6+Ho486EiFtvEARasHw6AhDoxOMTpQotnXyjksv49DDDqVUKlGtVQGYM2ceV7zxCh555BGuuurdPPrYoxQLbcybOx/fD4i04vTTz+DMs87itttvY3x0jNNfcTpHHHk4Tz71JPValQXz5/Hs+ufZvOkljjz8cDo7O9kxsJ2XXnqRwcFdLFk0nw984P1cdukl9HT3UqlV8Lw6rmvH6jeF7/nYtsPTTz/Dd7/7PT71yU+SyWX52803UaqUuPLKd7FgwUL+33//P+6//34uv/wySv+fsv+OsrI637jxz1NPn95naEPvHQE7BOwaTWJFY40xlsQae4u9J7FFY0uiUaNiLygIKoJI752Zgen99PPU94/9nDMD+n3f32/WmuVaCFPOefbe977v6/pc0ShfL1nCmFGjueXWmxk9ejSmaRII+HGRsJy+uDFV1mmor+e7777D7/ejaRqWmQGv+7R69Rqef+45Lrn0UgJ+P88//zzfLvuGBecvYOSIkd5wTSJjZDBMkyFDhjB5yhRCwcDPXnpzmE+l777i9/vJC/uoqqpiy54GtP7wRCTSmTR54XzvJLVzshPROJDQtACGYuLYYrqvBvxIfpveVIovl63iq2XLKcwPUlZaRFlxCb/4xVxmzppGSUkR5eVlaKqK5c3gZA7mYlmWJdq8PzlBXBHd23CgkU+/+Jwli79i29YddEcTpE0TRfehakFCRUVopkTatHBci0zaRNcCVJVXEIgUEArnCzSpJKG4Mv3p1JIERiaF4xhospMzYhlGhgEDRlJaKgR72XqwpKQEy2rDTqYIBIOkTRNJgsNmTGPypImsW72Wzxd9huMazD1mDuPGT+KHld/z6ZeLKSosIhpP0LNtB5YFs486iqEjRrB6/Tqee+EFigqLOP744zjssGmEQmE2bNjEtX+6lu+++Q5/MMjw4SOorKwmL5zHmnXrqB5Qw5y5c/hi0Rcs//57cGxqaqqZPn0GPp9GPB4nkp9PZWUFdfv2UlJYSEVVBaFgCF3Xufzyy/ndpRcxfPhQ4vE40USvcK8pSp9ZxxYD02g0xt/++jeKi0uYOn0ar732Gjt27OIPf7iCAQOqeeihB9m6dSuzZh1OYX4+L77wAsOGDuPPf/4zw4cPI5lMijaz44rHy7OhKopCd1c3V199NatXr0bXdTFcVUQHStd1VFUlHAoxe+ZMAoEA3337HY2Njbz2yqvE4/Gc1N22HYKhEJIsc+11f+K6P11LKp086P73c7xVSZLIZAyC/iAjR4zg0yXf4pekHBbWtEwhAM0v8u4Ebs6HlP3w+YMiXDSZwMikyVi2lyvpJ1IYANsgbWZoaY3ReKCLVWs2UVAYIhIJMmxILVde8XuOOPwwTCsD3v2wf1tY/anMhBxg7dY77mLhx5+TX1gsBmv+CKVFQXrjKVKmQywdxx+MUFhcSmFREeUVVRQUlODzh7CBeDxFbzQhhnEe80ssDg+rYqTBtUSHAlCRSJsmNVVV6LqPnt4efD4fuuajuLAEXfPR1NxCTzSK6s0vRDSyy6xZhzFh4jg2bFrP519+CbbLsUcdyeyjDmfZ0qV0xxIcPvtwjjp2DqWlpTiuw+ixY2ht7eC7b7+jpKSUbdt28dZbb7Js2TekUhmGDxtBeXkFhYWFhMNhGhubsUyT4SOGYjkWK39cRbggj86OTqLJBPFEgvb2DkrLylH9Oo5tUlCUz5btmwiGfRQXFvLb887jppuuwzSTxKNRbMfB7/OLtqRH1bBdF1nVSMZiPPf8c+zYuZ0777yTnTt38P33y7nzznvIy8/j4YcfoaGpCZ8/yNgx43j9zf9SVlnBww89wMABNRiWhaKqnhTEk5TKInE24POzbMN61qxewx133MHYseNobm5EVaClpRVVkfHpPvbu2otlmsQtg/nz5+BYJgUF+cQTCQry8/EHwxiezPzrr5fy9ZLFXPmHK35ygmTzFbP4V9H8cXERuY+VFaVoske2QUaVxM+dSqdwJRlVVrFty3OwOtm0bZAltEAAxSeaLXYmjWWkwbExMikkSUfzBYQiwOcSjjg4skN7V5qNm79i187dfL3oI/IK8kmlUwetBVVVRZu3/y9iGBl0XaeltZPNm3cSCpeTyEjk5eWRSCaIp1OEQ/kMHVBDeWUlvkCIUCQPny9AOmPR0xsn3Z7AtG2vNpeQZS0XJilJ9AthFJNxQMi3PZzLjOnTkCWFcDhMZ2cn11z1R04//XTOPfdcRg6P0NjSTHt7B6lMGgsLRRZ9c01VmTlzFuMnTmL9+nV8s+Rr/D6dYUOH8qszzmTMmDF0R3uIxWK4LlRXVzFh/CQkJF599VVWrFhBJpOhrKycYcOGU5BfiKKouW6dokhIMiKPRFXQdY221lZkx2X0yFHsq6vDwsWwDVas+I6ujg6G1Q6lp6eXFT+sYsyYcaxctZa3313Imb8+DceV8GXj4Nx+nURbtIFfe+3fbN26jdmzZ1NaWsIjjzzKjTfcyIDqah559FEaDzTiDwUZOWIUP6xcAa7DIw8/xNBhQzEzmZwtoK9CkPrxzRwi4Qj5Bfl8+OGHLFu2jGgsiu3aJBMJQTd0YdTIUUTy85BlmbXr19Pe3p7rQMqyjKyqOezRzp27OOLwwz3Frulhltx+k2vVG9xlfwoFRRZK6qKiYhSvTeXaYhHIkkQmY+HiBTEZWUm9mwOd51rUkoSq6/h0DYkQEg6mp6NzbFGuurYNto3suJiOS3FZFb3RGJ1dneQXFmBZFgG//yDliHpouq0kySiKSktrC93dMVzXh22BLAeZNHkihSUllBVXoCo+uqNRemMxmpq6yRitYjqKDLn+uiAO9o3z+wZSEjZmJiUsrV4moONaaLrM0GGDc22+gvwCIpEI5513Hi+//DI33XQT8+bPp7qikpb2NlpbWojH4+L4lSUMrzU4c8ZhTJs4mRUrVvDSi//ku29XcNz8eRwz9xhKS8qoqq6ms6ODe+/9Cy+//CqWZVFUVMiwYSOorKwkHA4L3ZYlWqqO6+LzaQT8OulEgsL8PC69+CIam5oYXDMAn8/Pp599imkbbNywjrxwkFNOPJ4F557Lu+9/yNvvfUggVEB5eTlvvfMBlZVVHHvMUWTS8b5YY8/CrKoKTz71FA0NDZSXVzBo0GCeeeY5zjvvPKZPm8Grr/2Lb775jmkzZrB193aamvazc+dWHn/8EUaPGkEsFsPvhdT0n1F4yjlkGTJmksmTJ/Hoo4/yt7/9jV07d5IxDFq7OtE1FV3VScXj9PT2MGzEMGRJYs2a9UIhnZ8vSjBNI+j3I8uidp88eTKXXnbZT1yo/aPtHFukQWVJ7KbpYpoWJSWlgu/rgGOa2K6CJGuYhoFlWjlzk+xhXvui2/ryQRzXu6V4nVIlEEILigFP0Haw0gapZBLDymAjZnG9sQS7du1h6NCR/dQDfeLGn5RYiqLk1JemlcFBQlFkgkGNadNn0N4Zo7m1h96eKGnTwJWzxhRVKDXlg/GNrmsdpOrM5pDbjo1hpFBkWejvHRGjputK7pLnOA6ma/LMs89wwQUX8Nwzz3LG6aczZ+5cbrn1VmbNnElFaRmNzU20trWTSibQVQ3LswH7dJ15837BjOnT+HrJEhYufI/CkmLmz5vPvff+hYUL36OtrYPS0nLKy8uoqakhEAhhW7anx+kjzzuuje7TCQQDbN60kc0bNzBl2nQqysvZvnM7n376ORvWrUeSFI45cjZ/+uPVzJg+g0gon1GjR7FnTx0HDjQSCgQIRUK8/Orr1NQMYFhtDclkDE3TvTmNxCOPPMJXi5cwc+YsUqk0O3fu4vDDj+C0U37Joq8W8+qr/+KMM85g5Y8/4A/4WbpsKbfccD1HH3UEsXgcTddymNj+GRhIQguX4wk4DieccCKzZx/utU0lDhwQ/pBgIMjDDz7Ewg/e48UXXxRTd0kViuZZs/jVr3/N+PHjGThwIPG4kN0Eg6Gc4zObHZ+tGGQvKyYXpIpAzOq6iivZDB06kKLCCL2JpBCQSgLb49hpbDPdj+7uHsLpFag18cvJHv9KzElM75kSm76EFvSjBUJkUkli8W5s0yBjWrS0tnqedfuQKHQXyTRNt79QMZ1O4w/4+W7lKk45/RwUfwEZ02bUyDHMPGIum7fsI50BRdFAtnFwPI1VNtydnJQkhxF1HQGKy4oIkcC12Ll9HXamS+Sgu6CpDpEgfPTeW9QOHIztOHS1d7J58xbGjZ9ARXkZK1as4Kqrr6a+vp7zzjmbCy+8kMlTpmLj0NbSQkdHB4lEAkkGTVHojfWSl5fHuHHj2LRhE7ffeTd79uwVlPZwmIEDh1BVWUUkT7Q6HcsR4T8erdHtR76QJIlYLMaWTRtxXJfxEyfR2NzE7rq9pBIJRg0fyYXnL+C3F56PP+DH8DJEwuEIG9Zv5sprriOZMhk1ZiyKolBWXMgN111FQX4IVVGxLJt3332XZ559hrlz59Hc0kIikWLOnGO58oo/sHvvHi655FJmzJiBpuqs27iJ3mgXp55yAldd+QcB3/Na40J06Bw0zXYcB0VVcn3+TFr8fLrPh6qotDS38vXXX7Fjxw4a6uvZuGkTkbw8Wttaqa0dSntbB7FoD9HeXnw+H2PGjOH44+Yzd+5cRo4eTTKVxnVdgsFgThsnScKbYdtOLjC1L7NRnDyqqpA2TE485dfs3NuAPxjBcCVcOYDt6owYMRZZ8tHV0yVKLNnFOagB0DfDwFNPOFlaQi6wyUXBQZU0jHSa3u52ZNLEO/bzwF038serr6In2kE4FM6pwQ9q8/Y3SElIRHt7sWwLv6qRzjjkFQhwmu3YyIov1w1xPYxjbk0g/SRATcomTHm7mojL6kuQdTxdlus6+HxBNF3HQUw5VVklnUqz7NtvKS4qYOrUqSxfvpxHHn2U5597hjff/C+nn34GN938Z2qHDKWivJzW1la6ezpxHIdxA8fhOC7/fOEl3nzzbZKpDK4jMXbsBMrLyikqKiKTEce4rHoDIie7y0nemyxORdu2iUQijB0/no2bNvHl4i/JZDIMHjKE0y+8kPPOPpuhtUOwHYt0Op3zGUSjUSZMGMPvL7+Uv9z/MN3dnQwfPoKG/ft5+pnnufOOP6PrPpYvX8oXi76krLyC7p5eNm7cxIknnsjFF11Me2cHt912GwUFeQwYUMPHn3xKLJ5i8qQJXH7ppdimgaL7+bngkezizrrpcFwkRfT/VVUh2tvLP55/gYUL36etvYV0KoWuaeRFIowdM5bOjk462trp6ugQM6ZJk+jq6qZ+z14euv8BXn3pZU4+9TROPu1URo0ejaqqnvXVs+L2U2UoihfUmu1ipTMi+MjnJ5KXJ+g4soyCip0rvYXCu08j5IkqcXLPUF9QgZfr6GYzRKR+QXZ9SWeu7XjDTYUDjU39bBwHy07Ugwc6ov8N0N7WimvbQv6hSPh0P6l02vN0uLiSLX5OL8dP+v+Oe8v6B3P1Ynadu66DrCoCwUJAWColBZ8u05luQ5KgqKiARCLBokWLGDJkCOefv4BzzzmbRV98zn333cdHH3/E7353OVdccQUVFVVUVFTR2NjA++99yGv/+hcbN24mEAwiqSoVFRWMHDlKUBm9qXDWMCNJICtiYOlYNoZl5GQyPp+Prq4u9uzbRzwZZ8CgARx/3HGcfeZZTBg/DiOdJpPJoOkaqtoHxvP5fCSSSc45+zfs3LmLDz76hPLSUioqKtm8ZRtv/+89ivLz2bRpE4qqUlVdw7Jly5g3bx7XX38duq7x5F+eZMP6DVx40YWsWrWKvXv3MGbMGG695WZ8Pj/pdPInOqP+pUJ2cWialssg8fn8JBIJbrnlNj779HMKCgoYNXIMXZ0dBP1+dF1l2ZIllBQXM2LwYMyqGooL89E1jYriEoYNHkRraxv1+/fzr9f+xX/ffJPDjzySyy67lBmHHdaP5t/nVxdOReUgTVb2yddUtY875jqefs3JNXKkbG6GFyEtORz84Hlqy5z4sk+rmls+WU8JuUg3mUQ85olnsw2EfptK/zB1wUkSXyoW6/WYUH21rGOL8iMb9i4AwMrP9rgPSriVwMn5NFwUWRZeYUegW/COQNsxPfmAjuOAJou5SBYep6oqgUCA+vp61q9fT0VFOaecegqnnXYab731Fi+++E/+859/c9lll1FZVc5TT/6VvfsOICkq5QMGISkK8XiMjGnT1NxCcWERqur5YSQJSZGwDJPeaJR4LIptmli2ie2hVhVZyKg1SeKYIw7n5ltvZdz48RipFEZaKJJ9Pk3khfRXv0qg6RqmaXDj9X9if8N+tmzZwsSJkykoLOLfr79J7cAaSkpLkGSFXbv3MKS2lttuvZXiwmJe+derLPl6CYfNPAzDMNi8eTPFRUXcecetVFWWk06lCAVCON6DmAUvZ81klmXR0tJCa2srqVSa8ePHkZeXRyaT4a03/8eSxV9TWztUWHtdm5LCYjLpFB3tnYQiIZLpFG2trQwbMpSgz4emqxiGCbZDdVUFFRUldPX20tLWxtKvF7Poyy857vj5PPjgQ5SWlGAaZj/pie3t1FKOISYUSA66rqGoIqrOcV1kWROvffbekvX9SBKSZItTxZNTun1trVxDQsouFAdPpCqeYfpBqmVZIZlKiUrG+17Z11CWZWGYEjtK/0grPdcjEOkefTxd18vHcGQOMij1DYM8EPRPaIzeFN3Bu0y5OeRPNmnKdUDTBa0jC4muq6/jlVde4ZwF51FUWOLt+BKFRSV0dnXzwYcfMXjQQBYsWMAFF1zAf994g0cfe5TOrk5CoQjTDpvJzMMPp3rgACRJYvHixXy7dCl+XacgPx8VJed1TieTdHa2YxkGkXCQ0uIqwuEgiiQTjcWI9vbS2xtFCoYYMmAglSWlJHp6kXKCun4ZKK6bE2Vm5e5GJoOm6Tz0wH1cevlV1NXto7SsDNu0GTtuLFu2bqWlrR3Xcfj73/5GZUUlS5ct47+vv8GI4SMoKChkzZo1SBLcc8+djBs7hlQygapq4oTTdC8QVQQaGYZBMBjk8cce5+mnn8bn0+no7GDOnDnce889jB49hjVrVxMM+ikpKSKZSmGbwnKgaBpVVVXYrkNvbw976+vYtn07RYUFDKipYeDAAYSCQTGFllxGDqtl6NDBHH3sUWzfsZcP3ltIJBjm5ltuRVEV8vMKCIfzSCYTuaCk7KKxHRtFk4hE8nAsB8XvYUO9MBnbspEURTyd/aOA3X5Pn3TwH0j9Wtr0rSOhx5LJVT+yLJFIJHJNJMu2cg5WSZJQc+H0royLhe1mDpoaZiMLfLov2ygArxOSTWx1ZQEEPkgqf5CxBqRs3eiIroKm6n26LEnyYMySp+h1vag0DVz45JNP2LOvjpNPOpnZs2cRDIZIZrrRfD7yVJWmlhY++uRjKsvKuPTSi/hh1Q98/MmnTJ02g/N/exGGa3PgwAFqqqr51a/OoKezk62bt1BVWUkw4Md1bcxMmraWJjRNYey40QyorkTXVGEvdQS71XEcenuj7Kvbz7dLl3Gg4QD33HsvVYNqcv72vqwVx3tX7JzhVNd1ZEWhoqqMe++5g3vvf4i9e3Zz7FFH0NbWxo6de3Bcl/vvvZua6mo2bd7ELbfczPDhwwkGQzQ3t1BfX88dd9zK/Pm/IJlICrCAplJfX8+yZctEbFwmQ09Pj8cBtln5/QrOOvNMFMklHA7y6r/+ze8uv4yjjzqGhro6EvEY+xvqPPWuS35+PrpPF1EQik55aQVlpWUkEwm6urrYtnsPW3fuYEDNACaNH0tJcTGmbVNWWckv5s0nHI4gA++/v5AdO3cRi0cpLi7hD1dcyfHHn+B1LZ3c/Ccrrdd1HcmRUGUFwxPGgoTtuLkiSTQYnL4Yi2x7XBJZH5LTh8YV/DUnZ6KTHQnHk1NJsrAfyRJYloHj2gJG4dgCHKKoAvvjet0n2VNhWqZ7yBVbPOzJZArNl8EyMl53x0PvZB8E6eBwduRD/AJuH/7RMjQymURul3U93L2seLJrb/BkOwa1Q2s566yz+X7lSp597u8sX/ENR8+Zy6gxY8kLRYh5MQBGOk00HufFf77Moi+/orpmAKf98pe0d7ax8OOP2Lt3L4dNn8G5553PhAkT2LR+gyC2SBIZw6Sjox1NU5k6dTJDBg2kuLgE2zJoaWommU7kaua8vAjDhw8lEAywbdsOXn75JW6+47aD+v7ZFqfjoS6z3TxRTkikUynGjh3BoEE1tLQ0MnhIDZ9/8SWdXd3k5ecTiuSzYeMm7r/vfmzHpaCwiJbmFnbu2skll1zEr07/NWkjkdu0du/ezZVXXsnWrVsJhUIkEsIwFAwFkSWZqooKRgwfRjIhTsGK0jJaW1p4+603kWUNXdfp6enx5CUa3d1dRCJ5yLJCqCAPx7ExzQz+YIhyf5Dikgy9PT00N7fQ2dnJ8JEjOO74+Rx19FGoPg3DMjj3gvNYv2ULBxr3U1RUSCzWyznnnMWVV17FLbfcKhaDd3r0B9A5rp0rsbK7vqqo+P0igDOZSoppu+P2SyN2cyW8fChNMZs9g4OSLZAcGykXddB/hpr1sbjImnAiqgerLfuWhixLB80v4rEEgYCFkU5iWUlAxZXAdGyv3pP+X8kWTr9yy5QMMmZcXPSlPjNWn3HHzflGZFnmsMNmMG7CBFauXMHKVStZt2kLo8eN45ennsrQIUNJpRKkTYPBgwfz3nsLMU2L6YfNJBgO8+nChfT29pBfkM8Pq35g9uFHEA6Fxc6rqsiyRDweJZ1KMG3aVGqqKiktKSYUCiGThy7r7Nq94yDptirLBP0+KivK+erLRYyZMJ7zFiwglUrmfBOuS1/mtkvuJDZNk0gkwnvvf8DiJYs5fv7x7N2zl5279qD7Q7jIPPfcC9iWwa49e5gzZy4tre1s37Gdk046gYsvvoi0kcjV85ru4+OPP2Lzps08/NBDHHnkkTQ3N6MqKnkFYfy6zp7du1m3dh3BQICG+gZmzZrJ4UccQUlJKbbj0tHeTmdnJwWFhbz/wQd8//0KHBsMw6K7K0pxcTGRSEg8kJaFGtAIh/MoKy2jubWVVWvWEQxHOO6E45Fx2bltG42NTUQCAVwX3nrzDQL+AFdf/Sde+MfzDBwwkD9ceSXxWLyPaOgInKxje3eBrL1Rgvz8PAqLCqmr30cymRRwD+fnEEN9JbuE7JW8bi4RV3Gz+X+2R/X82evzQQLL/0OLhaeZkfq4sznDvS1Aa46gKSoeBUNyDl4cWeCXmxPQ9/WrJclF7p/L6Pb1xuOxBOl0mlAgkOsspNMZ9jc0YFgOw0eMpSceY/3GLezbV8fRRx3J0UceSUVFBbF4nJ27dlNUWsrwESPZs2cPe/buRg8G0DUdRZGJRqMkPXdeOBzBNA16ujopKy2lurKSkpJifD5B9VMkl2Qq5bnOnNxmIcuiZZqfH6G1XeeTTz7mzLPOEpTznFy6f7kpBqSmaZEXyeO75d/z4ouvMHjgEEpKynjzjdcpKCzEkVQGDBrE7j17MNJJhg0fgaKqrF27luHDarnqqqs8TZPrDXTFa1ZSUoZpmvzrtdd4f+H7wsvt09F0USYE/X4GDxpMRVk5Q4bU8vXSr9m2YwfhcBhFFhDrTCaDaVnsq6/D5/dRUFhAU1MrmUSS3mgMf8BHXiRMYVGxSNpyLGRVo6KyikAwxDdff8MTjz7B3GOPYtvmzcSTKRLRGHkFBVhGBlOWOOP0k1m3dg3/+MfzzPnFXGqHDME0RXaI5bjiLuBRS1xJ+IcEMFASZa5piaF0vwql76GVcweBIinZsbTX0/WkKbKgA7quKLUUj7GWVSXj8pNEK7VvoJM9RsSaSaUy3gDQi4KTJVDBcl1U2RXfzFWQXQcXW3QMDsJGcJAhPme78hJnFclFdmUcV+QOypKMoupYloDCYdvYroysqHz+xSI6OjqYMms2UwbUYOMwau9eli//lo8//4x1a9dxzOFHMmnSBOKJJMFQHrpfJ2NniMfTBCRBCR80cBCDhwzizTf+i6rI9PZ2E9BlLMukoKBAHPEWKLLA9kfjMdo62rE9iX72zUJVcSUJWdMIR8Ls27uPzz/9lPnHzSdjGFi2hawquI6w+Dq2i2tbhEIRli37hv+8/iaGaTNt2mS+/fYbKqqqefbZv/PYU3/jhx9WUVZcTH7ET3VFBds3b6O0uJBbbvmzN7NJe9kiwqCUThucevJpbN60haXLlpBIpzDSGdra2jAsk0QiQcDnZ1htLXuH16GqKnvrGzAtS8QbGAaW5eR27XAknyG1Q4nG4liug677cWyHTMakNd1Ba3snoWCA0tJi8sJhJNkhPz+f4qJSvly0BE0Wv3dXTxTLdemNxtm0cRPJeDddnV0cPns2H3/2GStXLmf0qDHE4x0oiobrykIyJDtYjgOyiqtAJiOs2oorgeUlAEtyTlDbd991cyeOJLsHK8+k7EwLXMkRnSz6EKPBQBgJQfR0HRdd0/ravP25pJIk55BGiqoImEH2QuUB1kSEmneM5S5APy9nPtjjLnknguc36Weol7wTR7TqZNFCRFgmFUXhscceo7mlhQ1bttAbjRKMhJk1fQYTxo5hxYoVbNmwkY8+/YSvlizGFwjg2C6WYVFdWc1xx81nxYrvKSws4je/+RVtBxpp3FdHQTjMlo0bKSsrJBwKEgyIKLXGxgN0dnagKAqpVMo7NUVdmlWk2paZ8w1omobrONx3332sXPUDl19+OYMGDiFtpDDMTM7nHckrYNOmzbzwwj8pKCwhP6+A5uZmOjraeeaZpxlRW8sZp5zGN4uXMnL6DHqiXfTGYmzZvpUbb7qBw484nHisF13XvAcgO0Rz0H06jz76OMlUlG+//ZYvPv+c/739PwoK8qkoLyMejdPQ2EhzSwuappJIJJg/fz6//e0FPHjf/ezZu48J48d7al8xs9FUH36fn67OHjRVEfIVx0aSVDIZg+bmVnr8UQoL84hEwuj+AOl0igONjci4+AIhdE0nlcmwdfMmAppKS3MHiZ4kfr+f75Yv57zzLsiVnYrmx7Jt7znzwCCOg6KI7BNLNLG9a7s4MaTsTASvbOL/jw+3r62r63ru/pE16Yny2BWW2z4zu+v1nWHE8BFevLKYNrouuWPdsR0UmdwRJvUjWRwau+zmjsCD5pmoiiaIGo4XK2A7KIpKNBqlq7uHgdVV+Hw+Dhw4wIsvvsTll1/Ob889l9Vr17Bt506S8ShlZRUcfdQxNB9oIqZGSSWTWIkkuuanfl89lVUVTJsymSnjx+Hz6yRSCT77+CMS8Si9PQLNk58XJJVM4A/4QBKnV09vL4onuHM9TpTr9uW4G6ZBxsjk7kqWbWOaJu++8w7Lly/noosv4YQTTqC4uIhEIk4gEKSpuYWHH32c4cNHsWXrdmRFZd269dx55+1MmjSRzs4uXn7pJcaOHYuuiTnQjp27yC8o4r//fZPBgwZw8kknkE6nvEm0kxv8yUi8887b/Ps/r7Fu3TpwXObNm8fIkcPJZDK8t/ADBhYW4vf7GDxwIO+++y6qotDT1Y1hmhQXFjFo0GAcR6KhvpFoNEpzcxOqrnHSifOZM+dYZFlixcrvWbL4a0xLzCCisSiBoB/dZ2JaNqZI4ET2ZBOappJMp0mnTfbu3EtnRzcWEqFQiKbGJhKJKKqqY5oWKA62ZeeAZrIsYfXrbpmWgWmZyLKL3H8/zpbyh9AlfqKpkoQQVJbcvn8mCf/HgAHVufdWlpWDAl7VgwGRffKPoqLCnFxZkiRSKXG0Z4NQbFt4DSRvtI8jHTQL6V9Zuf0uUdlhpCwryLKCI8m4CJiBokokEyl6enpRJBXDSFNVVcWXX37J++9/wG8vuIALL7qQcePGsa++jo8/+4LPv1jE1KlTGVpbSywe44vPF9HZ3snSpcvIGBlqhw1BdV06ujpY+u037K/bT3VNNcedcALHHHsMiz75iNWrfsAwMpim4WnC1H4W0T7ekzjVbJKJZJ/903UxTYOzzz6HRCrJ0qVLufvuu3nllVf54x+vYd68uaRSGW677Q6KCktIpjK0tLQSTyS5+OJLOOXkkwGHvz39d9asX8e55y1gx5Yt7KvfRyyeoKamhmQywV13/4Wujm4WnH8eyaQwK6mKCLG5+667eP3114nGesnLi/DbC37L+QsW0N7WRCwWZ+H7HyJLClOmTGXtmtVEwmG+WbaMzz79FFmSCYXCbNmylfz8Inbu2kFPTzfzjv8FC847jzFjx9DZ2c6+un1Mnz6J6qpyVq1azeatW1F1GdPKIMsFnuMPTMtC0UQylOyB2JZ99wMhn59QOICuy6RSSWpra8nPKyIWi6FqQu7S2tIquluehEmWRZ66z+8XG5Jjo8qy54O3D1I1SZKCJMs/iW7rWyyOMEPh4jq2F8pj47g2VdUVObGi7MiomoptiWGrmh28uK6o0WTVBWwKC/IIBQMkLRtJ1mlv72DQwAwDK8tIZwwM0yKeTIIjsCuulO0qODn3lxgQyuJ+0pdZ6i06BVXzYZrxnNhRliVsB7q7usXR6wjh25AhtWzeupMXX3mNt995nwULzuGkk+Yx79ijmTl9Br2xGI4jMdjvY8jgWpYsXsLq1atZumwZa35chWGk6e3poryinHPPOpOZM2eSV5CP67qUlBQDLl1dXeRFImiqKpBEWZm+V/7Jnhe6J9pDJpPBcUX0QdowCASCDBkyhKrqaqZPn86qVavYsnkLxUUlxGJJHnvsCRpb2jh91pG89977NLY0c9JJx3HuuWfi1zU+/PAD3nrnPeYceyzxaC9btmzhmGOPpmH/ARr2H2DAwEFEe3t58NHHiKeSXHnl70km4wQDIe66+x5eeeU1Jk6cyLQZUzENg1U/ruLbb7/hissvY8KEiUydMoU1a9fw1ZeL2L5tO0OGDGHBggXsb2hgxcqVNDQ0sHP3bizbZtKkKVx33Z+YMG4cppFh4+b1NDY2YphCr1ZcWsy11/+Rt978H++99wGy41JeWo6qaYLWYlvIuk8MTr0hn6pAQVEeBYUFtHd0EI3FmDV7FrKsYlkmoXCEhvomkrEkqqIjHCAelEEPoOt+OjtbsQ0TRVNz/nfc/sV938C6f9S363WsXC8pOYtTzZZUrmyjB/S+SbosCXi1R4RXHU/iK1A/dm4kHwpHiITDGFEbZI1EPMnqH1dTUlJCXkEBhUWlFJWIwVIinSKZSJFOi13YcVw0TShiHcdrXXlUvZxkQJLRVJVkzoOi4LgWhmmya89u0dFKJMgLBSgpLcFytlPoCQtffuU1Pvr4Q6ZPm8INN97IgIGDqKurZ9fuvfh1jbN+82v2N9Szd/duRg0fgqqqDBhQw7HHHkthfoGwuUajFBUVMWTIEHSfj9bWVspKS/H5fGK+0J89jEvGC5DJZIRn23EdkS/iWU/3799PZXUVeXl5zJ8/n7POOouxY8fzwosv8tlnn3PRxZewfv16tm8XBqgbb7iBoD/Ayh9W8cijT5AXySMvP5/vv/2W6VOncv99f2H3nj1cd8NNNDc1MnzECFwcnn76WcDlsksvZdOWzSz64guGDBnCww8/THdPJ6lkguPmzWXxV1/x96efZsjQWk444SQuvPQiGvbV09zUTEFBPgUFBZSUFDNq9Ch27drN8uXLaWtrp6enm0wmQyIZZ+X33xNNRnEcF13TKS8rp7q6Gt3n48Lf/patW7ayY9tOerq7xZ3VI6urqlDvZjIZVE1lSO0QwuEwe/fuZeXKlUydOo05x84hlYphWSI5eG9dHclMBj0cEcM9KZviq6LImuf5Oeg6eyjfDgcP0ifoILlFlJ2vmLYpqh/Hyqm0Nc1HWXlFTo+nqAqWkZXqaP0DdITqUnLBdi1UVSKZiBL056H7A/REMyR7u9jT3YErSfgCQQqLiikoLKKgsJDKiiI0zYdlOnR3d5NIxLFsB8Px3BnywXMSqR/tMHtJFzhShT379uIghpC638/ESZP4fPEyLFcYsiRVIZ40WPjBR3z86WeceMIJXHD+Ao49+nDaOjq584472LxxPXPnHsuVV1yGTw94lI4MiURCQK3z81i16gc+/vADHNsmkUjQ3t5OUWEhqWRKXAwtCxcX07LIZDK5ksryQlri8TiyrNDT08PWrVsZO34ckUgERVEYNmwYb731Fh9//DEzph+GT/OzauVKhg8fxi0330RxYSFdXd3ce+99WDYMGzqMA/X1yJLDHXfegk9XGDliKE89+Tj3/uU+Nm/ezMDBg3Fsh+dfeAnDsBk9Yjg9PVGmT5uC7lP4x3PPiPK4MJ+JEyZw1lm/Zveevbz80j+pqqlhwbnncd5559DS2sKGDRvp7OrAdVxqa4cwbtxY9u6rY+F77/OHP/yeq6+6kuqqKgKBAD5dp7ikhNKyMjKpFK3NzaRSGWZMn8r+hnraO9rxB4JiduZVN6qmCuVAKk1Xbw+bt21l7959zJn3Cx544CHKysrJpA38/gCaqtLS3o4h3l1cV3Q0M6ZDoT+ALMkYmUw/mTy5qILsihGLykustQSIG9fBskxvsxNEG8l1USQH3aeiIZNOWKKDiYiHc2wnx/TCdfsGhVnDjGlb2IZFJOLnN785lU8+X0bTgV1YtoSihAmGwkiahmHF6WiJ0d5Sj+7X0QNBCgqKKSuroKiokNLiYtIZg+b2duKpVM4JlkurdoXnNycs8+Tzsqqwb98+EkYSVdcwLYvRY8YIVx/gSOK+k0xlCEYKkCT46NPP+H7FSiZNGMe4sWO55so/cNnFF1NQmEcyGfM6TjLhUIjioiL27NnDs88/x+bNmznx+PnUDhnMO2+/xYH9B3Bsm1Aw7En5+/hJfQMpxwuXidPb24uqqpx33rlMmTKVru4uQqEQEydO5L333uPDDz/B5/cxfNgwFn/5FUF/kL/cew+DBg7AzGS4/fY7iMWSFBQUYqQzbN26mYcfup8BNVUi+NNxGDt6JE889hg3/PkWtu/aTXVFJS4K7733PrMPm46ua4RCQRLxGJLrUF5WCrhs37qZoqJiBlVXM270WJLJJIu/WsSyZV8ze/Zspk+fimGabN60CctyqKmuYdz48QQCAf7x/PO8/fbbXHP11UTyIlRWVqIqCq0tLXS1d9Le3kZraysSDiXFRTS3dqD79JzwUAxHXXw+Hy4ua9atxe/3c8NNN3DxxReTF87HNKwc8d9xRUPCkYTRyZXAxsU0bQLBEJKHKZX6y8K9wWuWwWV5VgzXdbAsG8m1kFxTZIvIsmgq2QamaWDbFkkjQ9AnMXPmVEpLi8jYqVwSley5am1huc2O+cXAXvFM/bKs8ND993HVlU18vew7vlq8jAP7W2hqbqW5pQFN9+PzBdD8AWzJJJ3soam3m6aGfej+AHn5JeTnl5OXV0gylfIyRKTssFN0gFQNRdZwHEvMYRwJny9CY1MHTU1tVFaUYBhpBg8eRHFRASnDynGRAsEw4YCOqioYkQhdXd18+vmXLF68mJ07d3LZpZcwauRI4skodfvqSSXTxKI9vP7xx3yx6AvGjB7DQw89QJWXUptKp/lg4fu4ra1UlLkEA2Lnch0n553AFeEq7V09tLe3EwgGOeussxhcO4RFi75kxMiRTJs6nS8Wfcm7735AaVkpkiSxt34f9Y31PP74Y4wfMwafT+ORRx9lx/ZdDBw0mKbmJg401HPRRRdy0kknE49HhQDRdUmlU1RXVfD8M3/j9jvv5rvl31NSXolPi7B2/RYMy8WyHRKJhHBnOhahYAifpuBYFp2trfR2dVJWVs7kCeNoam5mxXffsHnDOo466mjmzZlDa1sHiVQayYXDZ87gm2Vfs3LlD9TX1XP66b/kQOMBurq7iScSdLa30NXZSSpleM5WDceVvIRb0X6VFTEnciSBqVVUQYUcVjuM/Lx8ErG0kI5IABbRaIKtW7ejqz5PReiAK6HIOj6fn1QySSqRQPbcrv03LduxsKw0ruNgOcJIpUgymix8R5aRwcykSKSSBP1+KitKqKqu4Jhjj2Lq9AlMnzoZ3aOziK5qHxAvB6/Odmj6fAPigUhnUlRWlHH+Oeew4JxzSCRS7NmzjyVLlrBm7To2bN5CW0crhu3iC4QJ6kEc1yGdidHVaROLZRg9phCfTyftBeC4WbWvKybyqqxhOCYuEoqroegy8WSMvXv2MGhAFWkjwfDhQxkxYjhr128S4GbHQddFp6m3txdJlqgZOBBdHUJD/V4WfvABXy3+ihkzZnDhRRcxf94v2LZ1M5dffimW43LTTX9m/PjxJBIJkt6s4/AjjkDXdJZ8vYTm1lZwXfLz8vD5fELE5zikUkmivTGi8QSDBw/mnHPOoa2tjRtvvIlf/fpXnH/+BXy/fAXPP/8PJk6axJ49e8jPz2fN2rXc/8ADHHXkEdi2zZIlS/jgk88YWjuCaDRKR1cn8449hksvvZS6ujrKy8s8G4DwTpimQX5emIfuv48HH3mUL5d8TVlpOYFgwNOA9YHE0+k0uqoho6EoMj6/jqKoxONCQaD7dKorK0gkEix89x02bljPGb/6DT6fj4aGA6QSUaZOncratetobWmlo6WJA3V7SaZTRKMx2jvbicXixGJJujq7SSRSaJqPVDrjzeMEOUVWZDRdIxQO4dN1uru7ufzyy6mrq+Oaq68lkzGQFYVwOI/W9jY6u7rQVB0HMWdTFQ1Tgkgkj0QiiZEx8Pt1XKycQFHAPi0cO4MM+FQZSbIxEwkyZgpdl6kqL2Hi+BmMGFrL7FmHMWxYLcWlhYR1PyaQspM516HP58M0DGzP/uFd0p2+fI2cejfb1VKwTAfDTpBKJVFVjbGjhzNxwhiSyTiNTU2sW7eB9Rs3sXbdBtatWYekBgiEi0hjIbkWjm3i0zWRXCQhOl7evUNWFGRZx3VT4h4iyWiKRCpp8MOPa5g751gS8TiVlfmMGzuGH1avIRQOYmcsUukk+HwMGDSIyuoqCouKRLLTwAHs2L6NluYmln67nJU/rOGYY45g+vTJ3HPvPUydOoNEMsnu3bvRdR1/MEBQ9yHh4g8GyBgG4XCEdCpNQ8MBZEVGVmQvdFSiqLCIX86bz7Bhw/j0s8/4/PPPufIqIcBbueIH7r//AcaOG0csFqOzs5N9+/Zx+223csxRRxKPx2hr6+Tl1/5FYWkplmPT3NLC8GG13HLLzbS0NnPN1Vdy5BFH8LvLL6e4tJhEPImiiOQnTVO49+47KS0p4s0336ZmwEAMU0AlRCClKzwrns9BliXPgy6aIqqioMoKuqoRLi2jpqqaxuYmvvv2GyZNmkpbawvNzY25e9qBxkbq6/bR0tRINBEnnU7R0tFJR3sXvdG4MEIoXkybJAlghwSSKqOoKq7tICPx55tuoKiomKee+huPPvIIU6fM4PDDDyeZzKD7Nfbs3UdjUzOKLw/LEbQTx5HQdZ38vHw6OqMelFr2hqR9l3jXMVEkB1UGK92L7CQ4bNxops+YzuQpUxkzZiSVFWUEA35kb9NPpWLE7DSOJIuyMJtV48ChBkL1/xIXZv0ZritC4nVfANd1yBhp0oaI6RpaO4zhw0Zw5m9+QyIZY9nSpbz0r7dYt3kv2BaGkcI0DAI+H7F4LCc99kaDnmswSMaI42KC62LbEqoa5PvvVpO+Oo0iK9iWxehRI8Wwx7EJh8MMGjSI8opyIpEwlmOTMoQfOpRfwPRZs2lva6Vu724621r5etlSVq9ezYgRwwGVk046iaFDh7F79y5aO9rZsm0rn3/6Kc3NjZxw/IkcecQRWKbF9q3baGtvR5LA7/dTWVEBskzD/v08+dRTWJbFI48+ytlnn8v6dRt48MGHKSosprCwiEVffUkykeCmm/7MGWecTiqZoKOjk5tuvpmWtnaqBwyg5UAz+aEQ991zN6FwgC8XL6KlpYUnnniCZd9+wx//+EdOOOEkTNPMsa0UyeX6P15FRXkpr/7rddLpVA7UnVXGCgMYqJrmURUDhIJB4e2RZQJ+P6FQGMsyvDvZbmprh1FfV0dnVwcdnR25+U5dQyPdvVG6enpoa2ujq7eHdDqDpvooLimlrq5RwBiyMyNZEdFl3v1SV1SGDxtGeXk5f7ji91x6yaUs+uILjjrqaCxLKLrXrF2LaTv4RBI6SAqm6VBcXICu67S3t4kJt1fmup62ynVtXNtEU1zsTILhg8t54qE7mThhAj4Pp5rJJIgn4qRTUTRVTOVVRUWRFDE0dNy+Nj7SIRSYfpN0EdYpIaF6Y0mprzfs0SyyHl+BdVGxLQPLI2b4NIUTTzwFlACX/eFaFD1C0kjQ3dPOwIFDhGPQMnO4eRnx6fP7IJ4VRToiFxuJrdu2s23LNiaMHYFtZhg5YihBn87QwYMYNmwEsqxgOhaJpBAeSrK3o9gmlutSVFhC/sQIsd5OmptbaG9tZ8UPP7L466VMmjiBCxacz+mnn8GA6gG89MI/sW2bu+66h8rycjJpsQFMnzEDWZYIhkMYpsniJYv5z7//Q1VlFWeddRaTJk1i2rRpbNm6jfseeABFVRg5ehQbNm2ko6OdG2+4nvPOPYtoby+yrPDYY0/QUN9AUVEJnW3tgMs9997LsKHD2btvL2PHjOOJJ57io48/5OOPP+T3v7+c88+/gGuvvZ6CgoKcnSCVznDBeQsIhUJcduklota2bSzb8u4CDs0dnWzdspXuni5UTWXkyJHMmD6d/EAERVWFq9N28Ot+2lva6O7qJh6P4dgOuqKjKRqZtElHdw8HGhvp6OjIOe/CwQgVldWYpuVVBLKnHfS6UJaLbYn7mmlZ2LZJ/d59OKbJ7NmzGTFqpNgMTRNJclm/fiN+f0BYJyTFk5/Y5EWKkGUNw/TmH5LoeYqTSsK2DWzbxKc5WE6KaVMnMmPGLHqjbViO4HJZtoXf7+vX9fLCaMWqFrMRx8UxLVEeIiF5bF6JfncQARzOGur7R5lJORy9eIqzUmTHs8J6l3zbwTSSVFWUE/SrxNJpcDTi8V4UCRRJxnLtnPpYBhxvUcpekyBb5vn9ATpa2ln+/QpmTJ9ET3cHh804jGlTJrJx02bKSsvIzy8UrsdsQHx/7LYkYdsWtu0QCucxeXI1Y8eM4YtFi1j942q279jLzbfewT9f/RfHz5/PXXfdQ3V1Jd2dnTQ1NiLLEkbGwOfTCQSDrF23lhdeeIH2jg6OO/54jjzyKIKBALW1tbS2tvLggw+RSCQZMWIErW2tbNq0iSv/cAXnL1hAd3cHkVAe//jny3y/ahUDBwwmEYth2wY333wTU6ZMpqW1jVgsQSAQYOiwYRQUFJJOpxk4cABvv/02mzZt4tlnn6e8vBzHFTMZNZPhmCOPpKSoCNu2cFwH07ZFh8YyWbV6PZZlM/uIo+nu7WXV6tXsrdvPySccx8jhw0mnU1imjayISGkRYyEeaKEUkOmNxamrq6elpVnc+zSNgsIiInl5pDMme/bWYXq5MNnbpSLLaLLmqQ4sUkaaH1evwU2b2LaIvRs2tJZMOoOua7S0tbF1xw4sy8FVbfBAD4qiUlJaRsa0ME0jd3fNcRm8DBBx/zJJp2IMGTIQy0oLRq+mHxThIPWTl2Sj2aSc9Mntd69x+qkkODhAJ4tgyRpRfjZlql+gI/3UkpKsYBkmI0cOZ8iggazesB3NX0QqkcCybQKBIEYsdsjcU+DrNc2HmUp5zCyJjGFho7By9VpSGUPQ+2SF3/z6DL759hu+//5bKitrGDBgIPn5+biI6WdW1pyVFiBJ1FTXkE6nqaur45KLLmZA1UC+XLwY03Zo7ujhpX//h4UffcRJxx3HRRdcwKRJk9nfuJ+uri6aW1v53//+x7Klyzjy6KO44aab8Pv8JBIpxowZRzwa45lnnyGRSFBbW0s0GmXlypX8/neXcflll5FKZ8iLFLLk62W88q/XKSwpJm0Z9MSi/PHqP3DccfNJRGM4jsWgQQNobW3l8ssvo/HAAa655hoqKytYvPhrvv76a5544gkefvhhLMvCp+tomkY6ncp5p3VdR1M1dN3Hjh3bsW2LBeeez4ABAzFsi2OPnc+rr/yTRV8uoSC/kOLCPFzbxcxkSBsGiqZiOTaGZWE5AsiRzhg0tbTiuBAMhikuLgYZ6vbvp72tE8sREg/6te/lbJaMl8ViGibbt+2ksqSEun37qKgsY9y4cSQScQqLSvjuhx9paW/HF8onmba9Mtol4A8SDofp6e0ik0mhyBIeXVi0Yr2nSPYi4gI+jcNnzhAPuKzkwlz75y0e6vXoD4jrD5DI+qJEpdMvjzCrPXJdfpJVmMOgZMNpcgZ575vKojPl01VGjRiGaxloqtDqpNMpNN0n1LqeYldCQlU1JEkRAxpHaGRc18VyXPzhCBu3bKO5vR1fwE8s0cuvzjidDxa+y4xpU9i5YzvfLF3K5k2b6e7swjGt3CJ3HAfDtCguEgTvtWvX8r///Y9PPv2Ew4+cxaRJEwgHghSF8gj4AsTjSf75yqv8+pxzuPOee0km01RVD+DJJ//Kxo2beeyxJ7n8st8TDIZxHJg0eTLpdIaXX3qZRDxJWVkZPT09bN++nd/97ndcfvnlgocVCrNtxy4eeOAhwqEImqrT1tbGUUcewdln/oZUMo7PpzG0dggb1q3jt+cvIOQP8Pe//ZUZM6ZTVFTESSedzMSJE1m0aBErV65E17Rc2I0kyQfFnAnYgEU8Hmf48OGUlBbx5VeLuPfee4lH41x19Z9A0vhh1WpsB9JGJlea2Y6D65VqpiVeSwHPUygsKKGycgCGYbN163Za2toxXdFUyT6A/Z8XWRLPSdZladsODQ37icfjHHnk4aiqKNGRYNWatRi2jeWKhF3Zq0YKC4oIBUP09HRjO1bOkpqNVnAd18urcTGNDNWVFdQOGojj2OgecrXP+twHRcwm22Z/tv7x0OJrOziOJU5l20TubxP9OZHXoVHQdi7Q0cvQzmZ3kdXcq0wYPwbbNlBwcGyLaEyoNnPYrGyUliPqI78/hCKpIkAEGUlWCQTDtHZ2s2njFjRFz9EOZ82ayQvPP8uTTzzEkMHVbN+2kdWrVrBz2zbivb2oioLf58e1bM+/odDV2UUwEGDTxo3s2b2LiRPGEfJpFOflUxwpIC8QpqyknO7eGC++/DIXXnQR9977F/507XV8tXgxRxwxG03TKCkqZsL48VimxT+efwHTdggEw3R2dLJ161YuueQSrrnmGlRZQZJEUOX99z9Aa1sHPl0nEYszuKaGyy+7VASARvIxTYvrrruOa/50Db+96Lc8+PCD5BcU4Pf78Qf8BIN+jjjiCGzLZuF774ud0XH7SbZtjHQGXAdFkTEtW3QgJZlkKkkqnaS8vIz/vvk6ZSWlHH30MezavYe2NsHYFRoqoRZwHSH3Ni1TyER0lfKKckKREI1NB9i0eRPJdFr8XfoYuf3zY2VFQlFFxyw7r0glksiywoiRIxk5alSOQNLTE2PZt9/jD0RwnKzfRkiP8vLyMS2LaG+v9+w5uVJIZP7YOI6NKoORSVM7ZDCFRcW5rBbpoBPj0FPDyQ3G+8VD/ayzUM7B4iTRVutfPv1ctlz/HeugPDtEMI3rOowZPYqA34eEhSq5xKJREY2mCDIKkoMrOViOheOArvpQFb/nLJRxHbAsibRh88H7H+FYgi8pKyqZVAZN07hgwbl88vH7vPjCM4waOYy6vXv4ceVKdu/YRmtTI5qqEI/GUVUfxxw9h66OLro6Omlr6aCqspL8gjwc18Gn6YQCQXRVoyASoaqiilQqw8qVP3DbbbfzzNPP0NXdSW3tEGqqa8ikMzz68MMYmQyWbdHa2sb+A/u5+eabufjii0mlUpiWwF8+/MQTrF+/kUh+Hj2xKIosceMN11FeUUokEmbFiu85+ZST2bJtC889/yynnnYqyVQK3e8nGAyQyWRoa2ulqrqKAQNq+HbZtzTU789pxUDspKZpil1bEQsjEs6nt6eHyqoaTMtm0KBBGEaajz58j1GjRpBIJunp7iEQCHh3P5GtIQGaLBMKBFBVBU1VSCTj7Ny1g311exk4aAAnHH8Co0eMAMdEVSRhf5WcnKRc9aAbwnWpeD7+fCJ5EQKhEOFIARnDwufz8eOPq9myfSe2qwhxriRjuxKyolNYVEo0GifaGxPQcNdBckWGhyKJSHBJdpBkB9exmDplYs7gJ7Ig5Zwr9qBMRUfCshwvLNY+JF3N7Yf/6bdA+pdO/T9/7kNImA0MIyNkAo7kfbr4fX5M02LChEmMHD6UTCqBqkqkEjEUycWvqV7qqyTagbImXhBNhHBaWT+AF7gZDkVYuWoN9fubiOTloSl+NC2AzxcimTAIBEKc+euzefvNN3nhH89y+OzD2Ld3N9988zUbN66ntaWJbVu2MHnyFK7+47UcdthsRowYRSqVJplKYjsWtisyu8tKSxgzehRjx45l8OBBKIpCIpHgiSef5MYbb0KSZDIZgyef/CumaRKPx2luamLnzh1cccUVnHfOuWQygowfjkR4/c03+c/rb1BYWoqqqNimxZVXXsHMw2agyBJ333Un55x7NlOnTuLRRx+hvLycVCpFOBzGMk12795DfV2dgFkrKgUFBezZu4f169f32Zx/kuvigONQVlpCW2srjmMzZ84cGhoayGQyNLc0k5+fhyIrdPf0oCqasKDaNo5lo6laX3ajz0csFmPPnj2YhsmsWbM49dRTGTxoIOefdy43Xn+deGizNbvnF5IkSdTt2ZQp1yWdSbNm7RoGDR5MaVkFLuAPBvj48889XKmE6bn8HNMhP1JAOFJAd08U27K9ZowozV1Xyt2PRTFiE9AVjj32GOFUVeSDYHSigaDgujKOLeM4CrKk4zhyv0zOrIFPAZTc9wBJXNKzZqfshY+fSTXtz3jNSuQdT22JR8JyAdMwCYdDTBo/hjVrNxFR8zBIkIrHCIeCxFNJJFfCdvvY3LYLvkCQeKzH+z5igeiaTnt3L98sX8mwobWkDJEEJEl4cASTWLSHVDLBnGOPYt68Oaxeu5aXX3mVJUuXcmD/fvbtraPxwAEmTZrMUUfPQZbgo48X0tHeRmFhCaFQmIKCPPIjEWRZwjQNggE/JSWldHR0UFlRwS033wpI3H//AzQ07EfXRflU19DAxZdcwsUXX0w8mcBxXSL+AF9/8y2v/ed1yqoq8ek+ejq6OOs3v+LMX/2KxsYGrrn6Kurq67n77ruYPHkSpplBVUVgTVNTE/X19cTj8dzd7KuvltDbGyMQCNDT0/MTpKiiKGiqJnwsrkNxcSHVVZX8/W9/5777H+CKK65gy5atHDZjCpomptzJZFpMsxHtT9Mwct0d8Xw7SKrMsGHDmDplCiUlpUiSzNAhgykpLWJ80Ri2bN7CJ599ier30mm9LpaqiE9dU9B1lVWrf2Ts6FHM/cV8EokMsqSyY89evl25gkAojO0gAISSgmvZlJdV4koK7e2d4t6Amxs9SEg4log0UHGx0kkGVJQxaEA1lp32No9sR1by4A1yzueUXTxCGyiTBScqCiiK3m/BKLiu3We57X/R63/sHJqP3RfC3i/L0GuhCViy+PszZx7GG29/gK7JJE2T7q5OqmoGCL1Lbigjjj7btQmH88gkoqTS8X6Z3RKm5fLewg845zdnoKhS3yXN62jIkoSui4GYaRocccThzJo1kw0bN/Gvf/+Hhe9+xHfffsuWTVuprKxGwqWhYS+VFWXU1AwgEAmJC6WUJfEJynoinqCmpporrriC6spqbrvtdrZs3YbrugwaWEN9w34uvPC3XH/d9XR1daJpGuFQmLqGeh569FEytkVBJExrSxsnHXccv7/8d+zds5vrrv8TpSXF3HjTjYRCodymZJkm+/c30NLSgoTIY9m/fz9ffrkYwzCoKK/wMjeUgxZIDlCtiChrRZZxLZsRw4exZuNmHrj/Pi686CJOPPEEkokY7777LrZlUVJaimmYuV3fzBgoHphD0zRc16GmuooTTzwBn89HYWEh1dXVBDQN17EwUinOPus3bNy0lfrGZnya5hmR3NxoQJYEZyoUCnHRxZcQDIWJJTOEAn7e/+BD6hr2448UY4noKyzTwecPUF5eSW80TiIeFwYp1+oDD7qOECU6NpquEe1JMGPGMVSU15BIduZcgQeNKbKMYMfo55y1clim/s++KM3c3N1FFRNaKRf1e2ji7cES9Sz6RsoF0PcPbBRMK/HnM2fOprKqgt6Eg6YqRGM9lDvV+HQ/hmPkOL225KLYIMsqoXCEjJHsc+85CqFwPit+WMXqNWs58ohZpDIpT53bd0mM5OflKshUKoUkSUybPI3JEydyzq/P5OVXXuOHlatZv+ZHSsvLKS+voqi4mKLiUgLhQK5dKsmgyyqGkSEQDnDppZcSCAW59vrr2blzB4qiMHr0aHbt2sW8efO44brrqd/fQDweZeigISRiCe69/yHqGhspLimmvaWN8WPGcvUfriCTTrLw/YWcfvoZzJ49i1QygW0Jokcmk+HAgQN0dHTg9/sxDIMvv1zE+vWbGDRoIKeduoBFX3yB41iUlZb0YTw92owsy0Lf54iHEgcCup8hAwaxdsMGnnjsMQYMHEhHZyd1dXWMHjGMmuoKZFW02W3LIpNOoniY2UymjzFcUVVBSXERFeXl2GYGyZaQXYXO7g5Un87YscPZV1fvgbNNT3Rq51C2oHD7rbcxc9ZsTNMmFArQ0dnJ2/97x9uxvQcUFcdRKS2rxOcP09TQiO1YSEpW3Sp59wYT2zaQsHAdQTCZMmlSrk0rK1mSpVgksiIJNYbtYFkIkJ+3+WZBFbKsCOa07MVtK0LrIctyX0ZhX0vO/lm2Vf922P8lT8l+LcuyGDiohlmzZvDx59+h6mHSyRRmKk1+OExHT6cwZnlEd0kW/8YfCCHJKo7V14KTFQXLdnnz7fc48ojZAqcvi3o124DIeeq938N1XVKZBPFYjDHjRvH4Ew9z4EATb/73LT7+5DOamw/Q2dFKIhGjorKC8opyZJ+GbVsYhkFZSSkXXnghLS0t3Hb/rWTSJj6fj9LSMvbs2cO0adO44/Y7aG5toampiZEjR4Cs8uwLz7Bu3QYKCwpJxOKEAn6uveZq8vIitLY0ccIJJxAMBohGo6IfIyv0RmM0NTWRTCbRdB/79zfy2eef4Tguxx9/PJMmTSQYDLNjx050XaO4pCR36rj9TxHXRdZ9+ENhzIzJ+k1b2L13H7KskMkYRLdtR1FkZh52GNOnTCYvEvEMSUruPRMRyWCaJpZlUVlZwdQpU4RE3LSwbJdUIkEqnqCro53O3m5RnngluuT241NlRamOTX5hvgeCk/H5Aixa/B51DY2E8otJm4jpOaKDWV5RheHYdHR25PJSHLtvSCgrMq7pImFhGQaFBREOnz0TxzHF/CPnqJJzMw3Xs9fmyO2eEDSrCcwBrekPGBGlmppdHD/3oPc/Sfq3gQ8FxPXPn8iFK2o+8vIKMAyLgC7egN5olKLSCu/fu7msEC/BEFlRUbUApmGiqoj8QhfyC4pZtGQp23bsYsSIWtJpQ4RYuYckOnqo/ezxKEj1LoaRZsiQAdx7751cdNFveeed//HWW++wZfMmurs76epsp7KqElWRqB06lHPOOYe1a9fy3HPPUVBQSDyWoLCwkPr6OqqqqrjzjjtIp9Ns2bKFCRMmUFRQxEv/+g8vvPIKxSVlOKaDmTa45bYbGD92FN8vX05lVRUjRoyko70VKT+fzu5uoX3q7PQkEQ5r16zhu+XfMWLESObNnUteJB+Q+Pyzz2nvaGfGjBkMHTqUdDpNIBDIIZVym5zPz4HmVvbs3kvGNBk2YgSKomJYJg3796OpCkcdcQRlJcV0d3aKPETPWprlM0uSRCgcRpZlamtr8ekq3Z0dZAyDZCpFb08vsZ4eOtvb6O7pJtob9UqaPsxTdtBsOzYlJSVE8vM885lKe0cPb7/7PloghONxhHFEuE5efiGl5VW0dXYTTyfRFEVYIdyDIwJsy0LVxO+s+/woms+Tj8i4soAzZMskx7axsXOLQJZFG9owrX54WHJrIFdmuQI8px5aUh0avnjoCdJ/QWQv91ksUBaTU1RUxPsffML7Cz9GkXXSaQsLBVsCC1c4A7OwryzSSBIM1kikgGRSSJAVxRUcVhQ6u7p49dV/89AD92TjeQWG6JCJv+30+ckFVEI4xVwXYvEYAwdVceONN3PW2Wfz3zfe4sMPP8jlll908UX89oILeOvNt1m48D2GDBlCd08PiqLS0dFBcXExf/3rXwkGg3y3fDkjR46krKSUH9eu5/kXX8QXDGFZDsloD+effy5n/PJUvlr0BRddfBFDhg7jD1dcwVFHHc6BA43sraunt7c3l8P46aef0N7RwdxfzGfM6DHg2sQTcb7/fgU//riG4qIibr/9DoLBAOm0yB/MChhN0wQJNm7aTG9nD3mRCOedey6TJk5i2LBhZDIZVqxcyTv/e4s3Xn+DeXOPFdmJ3V0CxCHLOeq6LEmEgkFRy0syqUSC7u4u4okUXT09xOMxot09tDc3EY6EycvLw7It9H6AwWwOSDKZ5KJLLmHosKF0dfZSWFTBm2+9zo9r1hHKKyRjOLiSLvhqkkrNgEHo/gCNzVvJzZTdg4FSruuCLGG5IuKvo7uXG/98O6++/DyhoE4ylUDXfLn7sevafdR2V7ClZVfEHsiaR61xRSxddqNxbLA9xpZ66II4dBEcekn/qfwkG7ElCf1/MMQnn33J9bfeRtKQcGU/pmVTPbCGgQMG0Njcge0IVqqqkJvco4iprO4PovkCGJk4AUX0xnRZIRSMsPDDTznn7DMZO2YohpXOsYV+moUnPOPkVJ+uN31WSGcyOHaKkuJCbrj+Gk499QTWrl1LOpNh7txf8OQTT7F48WKGjxghYuZSBvFkCteRuP3W2xk+bBibNm9m6NChDBowgJaWZu66+x5SmQyhcJDujm6OPGwGF5x7Lm1trSBDVXU1a35cy1Wbr+HSSy+htnaoQJDmhdi4YQNffPE5pRVl/OY3vyIvL590JomVMVm27Bs2rl8PwPP/eJZp0yfR2dmJrukgWcQTUZLJFEgyiUSaxv0tBIN+MqbJyy/9k3A4zMyZMznhhBOYO/dYJk4cz9+eeoo33nybc888i8GDBooSxhEuPIHPUbA9w1xTUxNt7R20trbT09NDd083iVhccLP8QYYMH0HDsu9wHa/1KgmVdrYbWFVVwTHHHoNpOYQCQTra2nn97XdQ9CCmBUgaiqxj2RAMRhhQM4Surm7i8SgCTumCrODaXpUh2biSgz/gxzQcLNtA80f4ZuVmrrr2Zp77+yP4dR+ykuUhiDmJ4ggbr+TafTKkbHnq9uWWKIorspYlL8rNsVHuvPPOu3/uftG/u3XoNL3/hziiXSzLJBgMsuy75Vz6+6tIpECSQ9jolJRWMWz4cLp6osSTaWRVqD8FfI4cT9V1s4I3SCXjyDlCnnjDeqO9xHt7OfHE43AcQdxTf6axkJUiCIq3dFB5KHtQ6Uw6TSqVoqioiKnTphHtjXLHHXfx/Yrvqayqpr29g2g8hmEYdHV1c8stN/PL005lf8N+JFli8ODB2LbL7XfeyXffrySSl0cqlaS6opKHH7iP4uJCPv/8c2RZ5vwFF5BIJNm7dy9btmxh1KjRFBcX88UXn7N06dfMnHkY8+fPxzQMJAlaW1v57NPPWbduLZWVlTz99N84/vjjSKVS6LouptyqSiwW4+VXXiE/P5+Ojk727NkNksuAmhoGDRpIQUEh69atY+nSr3Fsh1GjRjFt6jR2bN/Oj6tXM2bMGELBANu2byc/vwBJVsiYJt3d3Rw40IjP56OkpITm5mY6Ozvp7e3FtCzC4TCVVdX4AgGWLfuWRDwlXJfYDB5YRdDvI2UYLDj/AsaOm4BpOeSF83j1X6+z8JPPUXwBhCBDRZJUHAdqh46gorKGbdt3kEjEUBUpF52GB4zLQqpzVwJXNCX8gRAbNq6jpaWJU046Edt1MT1sj6yISkPK5Yi4uQXRH7Gb7c66juT5XMRCUu6+++67+z9Y/QeF/e8U2S9w6EcqlcLIGIQjEdas38gFF19Od28af7AYBz+hSDGjxo0jlkzS1RsFWe4zZdlOvxmXnbseaZqYcTi2ICzarovjCg3N5s0bGD92JKNHjfZMXGru53T7iS7lfoK57AvqepkkmUwGTVHJy8ujta2NF198iZdeepn2tg4GDa7FdSU6OjspLCyis6uLs88+i2uuvootWzbT2tpCTU0N4XCYv/79af79+hvk5eVj2RZBf4AH77mbceNG8eGHH/Lwww/zxReLqBkwkNNOO43W1lYaGwWYbevWrezdt4fTTz+DQYMGosiivNm0cROffvIpDQ0NXHDBBTz//LNMnDiBVCqFoogOixjQ2qRSKRa+t5DGxka2b99OWXkJCxacy9VXXcUJJx7PqaecyqBBg1i3bh3ffvstBQUFVFdXU15exjfffoOsKowaMZI1a9eRTKUoLi0lYwpcaWNjE+GwKKE6OztJpVKoiiK+Rs0AZEVh8eKv2b17D6qqixkKNoMGVoHrMnzkaE4+9TRcSSbgj7C3oYF7HnyYWNLAtIUywnEUJFknnJfPuPGT6O2NsXffHnE3dYQgUe7HasvlijiuN/zDs4qDqmssX/4dhpnhmGOOxcgYSLgiwsJ1vYXQF2iavXtnCYrZuDzXkTxskCeK7NNWCd/woZfvnzs9RDtXzn2DgsICNm3cysWXXE5be4xAqBTL1QgE8xgzYTwZ2ySaTCJlh4qy24+zmA337Mt6UFWFSF4ERVY9L7uGI6k4koas+njiqb/S3dODrmn834YvfjLwzPb4iwqLSSQSvPHG69x9972sWvUj1dU1DBs+nHQmQ0tbG+WVVXT19DJz9myuu/ZPNOyvZ9mypTiuQyDg5+NPPuPf/3mDopIywpE8bNPm95ddwtFHHc6eXTt5+pm/09rait8f4Jmnn2H9+vWcdtpplJWV0djYiGkaLFiwgKqqKgDaWlr58P0PeP+9haiywvPPP8ff//5X8vPz6O3tFZKOXJyaQmFBIR0d4vKcSCQYPXoU99xzFxdddBF+v4/u7i6am5uZOHEif7r2WgoLC3nrzTfZvWsX+YVFTJoyhY0bN9HbG0NWVOKJZM7nnd0IbdvODSbz8vKoqKykrLycrdu28dq//s3adetzPovsphoKhSgrr2DevPnIigAL+nSdl155jV179wlQoCs8HZKsYFsOg4cMQ9N9NDQ0gOOiqzIqLrKXP3NwJ0YWSGlJRVX9SIqO6cg4ik5haRVPPPUMd93zF/yBsMig6XcKZTfLvkNA8UBzXlyF7fTraOWS0myvbWoLwqHrHHQU/ZxE2LIFicIyxXG7ddsuLvjd5TS0dBEsKCfjyPh8QUaNHo1l27S0tns1rivysLzZSZaRlTXCCK2NjG05BHwB/IEgNgqurODg4CgOWriAtZv28I8XXiUYiGAamdzQUvJQqNlTxDIFEhQg6A8SCUZoaWnh1dde5drrbuDTz75E10UHpLW9nV179pAyDMoryojHo4wYVstf7rkL3adjWRbHHnsMQ4cOZdPWbdzzl3tBAt2nE+3u5pcnn8wF551HR3s7y777joryKlLJDPl5hdi2w9atWymtKGfosOGYhsXUqdMoLCzEMAx27drFhx99xNbtW5k7bw7vvPc2C849j2QygeuxmxybHKs4FArx3zff5Korr6G3p5vTfnkqjz/+KDNnzhTDRF1FUVU6OltZt/YHbDPF9BkzaO/oZNXqNTiOy9BhI0im0jQ0HkBRFCzbynl1FElG8YAVPp+P/IJ8agYOxLRt3v/wYz7++DO6u6Joii4o7KpCKp0hHI6ArHHksccyYPBgJBTCwTyWfrOcf73+Jv5QPq4r50pfy7IJ54sTqa29nc6uDlRVxrEcD94g5zLO+oHyvWhAGVnWUFQfsuoDFCTdT2FFDS+88m/uf+gR/IGgdw/9qVtQtMml3KftOJim5bWDLeF9F5d0kTfo5Mb59Js89kVR9Q+FFN0qi4L8QvbVNXDpH65hz4F2AsECDNPB5wsxZsx4HKC5pQXbzf5gh+RmSf2j4IUrUPJ60JLsEgjlEU2bWK6J4sVJm45MML+IZ194icOmTWbOnKNJpeO5XUCWFe+yJXndK5fWlna2b9/Gl19+xYoVy3Ecl9raoWQyJlu2bSMej+MPBPnVr3/D9l07qdu3D59P5/bbb6WqsoJ4IkFFZSWu49Le0cFf7r8f07IIBcMkEnEmjB/DNVf9ge6uTp588kk0XWfG9Bms/nEtO3fvFlxZbBSfjqJp9PT0IssyPT09LFmyhC1bNlFSUsIzzz7L8cfNx+f30Zvoxqf5cBxwLBtXkggEQuzatZMHH3yQzz9fRF4kn3vuvZdzzjkL22N7KYqMmXGI9fbQ09lJsreXWDxBYV4+RcUlbN22ncGDh9DY2ISsqOzatVtkgxQU5Aavsqri4GJaDkVFxeQXFrB69Vq+X7GCTMbC5w9iW9msP5lUOsXQIUOoHVzD2HHjmTbjMGRVw3Vkunpi3P/w46RMF59Pw3Flz8MBmh5g5KjRyLJIyLJsE1U8kvB/49D77HweJEJVdCzXEEA4yYcvWMzzL/6Hgkg+N/zpD8TiojHk9/lzYaaOl0aQTeQ1Lcd78lwc1xLxbFJ/R6HTh4yXZeWg+8ehXS1FkcmLFLJ+w0Z+d9WfqG/qQvMVYVoSoVAeE8ZPwlX9tLcLyJzULwckC13+SRvZe3Oyp4hlWej+AOFIPt09HWLKiQdtlmVMV+HeBx5l0pSpRMJ+bNsEZDLpDIZhsmfPXrZt28GunbtZu24d+/buJZFIUF1TxZDaIbS0tNLS2gyuzMzZs7n22uv493/eYNfO3Zhmmj/fdBPjxo0jGo2i635My8I0Te578EHq9jVQWFhAJpMhPxTi9jtupbS8mOuvvZ6PPvqI4+Yfz6ijRnPRJRfy/aqVyKrCmEnj6E1ESaQTqLpKW1sbXy9dTH19PccdN48///nPTJw4UWw+toMq65gZE0mWCIdCNDY18sGHH/Gvf73G1q1bGTZ0OI899jjHHHMUbe2tAofqWPR2d9PV3k5Xezuxzm7SqQTJWIJkPEFeXh6NBw7w1Zdf4g8E0DWNjvZ2bLufGkLz6IK2Q+3Q4ei+AO++s5C6hv3IioLPF/Bk8TLpTApVVTjxuHmMHDGSaLSb0375SzQtQCpjEQyHefxvj7J+yw78kUIMWwAZNEXHMAwG1tRQXT2Q/QcO0NPTg+qV4PwsiPqnCyUnWJQVfHoY09IwjBSg4PMHeODRpwkEVK68/FISyZ6DLueuN7jsiwn0nm1d66dHIwuOc3LJtFK/dNqDZML9bv8Bn5/Nm7dy7fV/Zk99C75QEVbGJhzJY/zEqbiSRv2BJrG6VRXHMnIlkOtKPynh+u8VgsoqhoCS6ZAfzsNMpzFSMVxZ9mTyNoFQHlt37OOpvz3DX+6+jVgsSSAQZt2G9Tz996dJebL4YDDIsGG1FBUVkM5kkBWdbdt3Egz6mDtvHiefdCpHHXUML/7zJT744CMUVeKcs8/iV6efQSaTxuf341ig636eeOqvLFn2jQisMUxc0+LWO+9k3OgxPPPc07zx39epqR7Ayh9+oKqmhnnHzWPGUTNJpFM4skTKTHOg+QCKqrB06VIUVeLee+/ld7+7FEWRve6UjmXaKBL4Aj5amhv55z/+wav/+jd1dfsJBgPkRQpoa2vnH//4B4aZYfToUaTSKZoaD9DR2kKsq4tkPEYmbRKPxWlta6OuuZ1EMpkjwk+ZMoXe7i7P4uzPlbyOYyHJEj5fgH376li/YT2JZBKfP4jliORZyzYwLYNx48dyysknUlVRyeo1Gzj1lFMpKirBtiCSV8jHny/ipdf+TTBSSNIwBetKEjF/oXABI0ePJZUx2bN77//HXVLKeTf6Hhs75y/Hi5xVVF2MHi0TW5ZQAyEeeOhRagfWMHfuHGzHOLgiyhZeWSXJQXxf8X2VO+687W7HkTyruZwTHh5678iVSK6LoqrcecedLF+xlmB+CWnDoiBSwLhJU5H0II3NbZim4+FybGGbcbMhPX0dif46fKlfxC85Yh5IsoyuaqS94aHreUlc2yXo01m7ZjXVNdVMmTyRZCJBVWU1vdFevl6ylGAohKJI9PQIIIH4b5RfzJnD1VddyVlnn8PYkaP5+ItFPP7UU9iWxVFHHs4dt94ibKyahmU76D4fr//3Tf7+zLOUlVcgI9HV0cnNf76RM04/jXQ6ieM4tLe109jYSEdHJ9u2bWX//gbhAFTFoPGrL75k07oN9Hb3MLR2MM89+yxnnnmWyEPxdkkJiYDfR0dnO2+88R+u/MPvee+996goq6CirBRZhtmzZ1FQUMCWrdtY+vUSZMlFAQ7s20MiGqW7sxPTMOno6mZPXR0NB5pIptPkFxTQ1dnFtKlTGTx4MN9/9z0Bvw/TMtF9GtU11Zi2RTptsHdvHdFoHNt2UFQNx8XDD6UpLy/lwgvP55STT0RyXH78YRVHH3MMEydORJYUNJ+fuoYDXHfDTcTiKSRZE1F9rosqKTiOwphxEykurWD7zh10d3eJrmPOT3ewPyPrWu3fksXNykQ8XJUjBoiyouDaNpaVRtME7WTLxnX88rRT8Pm0Q/Ii5dzX0jQRQS31849IsoQq2EJSLgZaKDk5qB1Gvx6y6yX7/ObMX7Ho6+UYtkDS+Px+AqEwdftbSSeFj1wcY1IOFXmQXuBndon+Z6srSbiKKi6Kfh+hcB6x3k4kWQHJFjIJXExX5r4HHmPEiKGMHjmceDTGueeeSTqd5u2338Hvr6S2dgijRo1i1KiRVNfUUFVRiaJomI7Lku++45FHHyUWjzNi6FBuv+0WfD7dk1+I+LIvly7j8SeeJByOoMgKnR0dnLfgXH5z1m+8KAKVw2YcxvgXxvP999/zxutv8Omnn7Hos8/4esliIgUFpDMGLS3NlBQXceGF53HHrbdTUzOAZELwxhzbIeAPkErHeO/dhTz99NMs/2Y5s2ZM5tyzziIYDPP111/j92mcevKJRGMJurqjvPu/t7j/L3/htwsWMKJ2kADpSTKtbW007G+ko7sHZIURw4djObB923YieXls37GDzq4uKivKSEeF+ct1bSRXwrUdb7Ku53bZTEbghU4+8XhOPH4+iUSMdat+YP+B/Rx59DHMmDHVEwuqpNMGt9x6Ow0NTWi+AJYphKBCF2ZRUzOIQYOH0NreRVNTk2BSkQWBSP/HMyL/zPPi1UE5dK34GjKCvilJLslkkukzplFWVkos3pvLaBdllXzQV3OdXDO5Lx/EcaV+ris594+z8dBi2mhlgwBAhkSyl1lHzGbM2BF8t3ozvmARza0tFDUeIBLMp7c38ZMbVi63WuoX+n7oXUQ6ZA1J2WEkhML5pNMJbDtBtp9g2KD5w7R19XL9n+/gxeefprSkAEl2uPKq33Phby9A92n4/X4P4iKTcWxSiRT+gEbd/v3ce/99dHR0kh+O8PCD91NTWUXGyOSSf/c1HODBBx9CURQikTCtLS1MnTaJ6669RuT0yTKyAqaVwefXOf6E45gz51i++245H3z4IcuXL6ezu4dwMMTZZ/2aCy84j6OPPgrHkr3McNnb2WDp0q956snHWLp0CbWDBvL4w/cydPAg6hsOgOqjsanJY3tJmIbB4Joq/njN1bzwwj94d+F7nHHaaYSCIfbv309LSwu2LXzxAwYNIhSJsPSbbynxxI5r16xB13UG1NTQuaGjr2LwnHayLKFpKpZjEYt1M2TwYC6+5GIGVVWwZdMGmpqaUGSZw2ZM5di5RwmEjiKjqjr33Hc/33y7An+oQIAdVC8vRNHxBQKMHTeRdMZkx66dAmouBlg/GTH8v39I/SqPviaQ63GuFElCsi3ywj7mz5tLOpPpN9y2f6IK6d+E6svlcVAVOesiy4q1DvaCiAVie/01cfPPWCYFeWF+feYvWfr9GnwhF0dyaGtpZsy4KnRfL6bl9s07cjMOt1+S7iFWyFyWnKfQldwcgsV2BYoyUlBAvCeDbZpeLraM4YDqj7B63VauvvZGXvnnc+SFA5gZi3A4Iujspp3rXFiygqr7iMUTPPL4Y7R2dGA7DvfedSeTxo4lZSbRVM8Dn07z4IMPsr+pmdKiIuLRKMOHDeGBB+8lENSFh0LxIrQlccE0jDQAc+f+gtmHz6S1tU1IqiWJ0tISfLpGtLcXzefHth0iefns39/AX//6V1577WVUWWLBuecwdEgtlmmSTgvnZnPTfhLxOF1dXaxatQpVUWjeX086Y1BVWcHGTRtYuWoN1dVVtLS0omkalWUV1AwcSCyeYNnSb+jo6qK6ulpEHbS3M3jwYMorKrDXOUiy7L3wNo5lEtB1XEmECZ1xxi/51S9Po7uzncWLPiWTziDJCiPGjuXoOXMJhiLoWhCfHub5F17itX//l0heERlLmJJMW0KVVVxLZvJhMwhHCvlxzToSiRSKTwHbEiGw/WsJiZ+VNv1fCyinbPYeflVWSPR0cdL82fxi7hwyhvDS9H++cwrhrBq5331b/D0bVeBR7INX4c+k9LiulxAqi1F/Mp3guHlzGDXiFXbWt6PoIbq7OojHeikrKWJ/UzsKsgj7lDjEHP//PdyTAFdyc0ee5Tr4fT6cYJh41Og7hD3sZaighG++W8WNN93KM39/EgUFy3KFszfL+pJAwUXSNP7++JNs2rQFx3G5/HeXceKJxxMzEvi8ybxP9/HU08+w7Lvl5BcUeKeKy5133EZRUQGJZAJd7dfxcO3cojdNk0ymCySJsrJSj0OsYFkmyaSB7vGFgwE/77z9Fg888ACNBw4wa+YMzjnnLObNm8vfnvo7/3vzTV7654ts3b6Ld959D7/fL4SOe/eB6+BYpgjb9OKgGxubUFSVSDhCdXU1pWXl7Ny1i7XrN3jyeon6+jqCgQAjhg9j0ICBJBNJdE31QBOyF24pY1kGVdU1nH/hBQweOIAV335LY0Od4NfaDoMG1nLsvOMpLCxFchX8/gjvLfyIBx95glAkH9MS8GrX406ZFowcOZbKqkHs2L2LlpYWNJ8OjttPUvLzC+GgReLdg93+AwLPg2LbNo4tTj/bNPFrKqeffiog4t2yHqb/exDevwngZiPY+lS5fdifQ7pMbk45j+O11RzLoqq0ml+ecjwPPPY8wVAB6ZTB7l3bGDdhGj5NE7WnJIshZD9pcX/KBD+TiZLNt84lU3kLzLFd/MGI1/PvRfFOHdEilikqq+bDjz6juLCAJx99CCOT8UI4LSRZxXQkQn4/L7/+Bl98tQTDNDlx3jz+dPUVxJO9aJofw3AI+wP8972FvPLGG4QLC1FkhfbOdv58/bXMmn4YXckuL4lKOgiVlHOxyaKcy3pTsmplWRW7tGkahPxh/v3KK9x26+04kstZv/4VZ597FhWVFazbuI7aobX85f77GTx0GBO6ulH/9w6xVBrHtskYGUqKixk0oIoB1QNJmwZbtm9nz87dpFNppkyegiLJLP/hB/bV1aEoOsUlpei6TDjop6K8nHAkQiIWQ3ZsT+YigyMAfrrmY+zY0Rxx+OFEO9t5Z8V3otsVCJJMJhk3YSInnXIaRSVlKKpOIBjhsy++4vo/34ak+cjYLo4rnKOKCplEktrhYxg9YSpNBw6wZ9cONF1Gciwkt88Om3v/JVC8sNi+csfNjrb7IHKujONKuJiiNLQMXMdCUyXR3Rs+nOPmH4ftCvehJDm5BdBfNpUNZJXlvsNBNAaUg3PSfyL46wtNF7Rxb7XKKriyjGlnOPvsM/nv/z6gvTeNoqp0dnXS1tpIWWkFzc3tYuhIX+jk/4VYQfqZUPiD2r8SKCqu7RLOL8J2wUhnvBmOKAEN2yZYWMa///s2eaEgt996K5bjYBkWkgL+UIRlP6zg9TffJJaIMbCmmptuuA7LyqCpikBTSjKrN27kySefxLUcJNeho62DU08+iXPPO5tYOorP5wPbyf0uWU/Fz4k9FUVB9wnkkWFY2LZN0B9k7Zq13H3X3ciSw4IF5zF3zlxef/111m9YT/2BAyRjSfIjEWbMmMGN113PCy++xF8efIj29nZGjhjJ3DlH4xgZuju72bBlM6FwGElWMG2XA43N7Nmzh46ebvIieQwfPoLCgkJ0VdyXLNOit7dXNEC8UzDrDjXNDKGgn/Kxo2ls3E9Pbw/BYBDHdkik0sycPYv5xx1PpKAISZEJhiOsWLGGm26+HdtRkDU/tisM0Yqi4dgu5WVDGTNmAtFoJ7t2bUbCRnYksd1mn7ND7p+u1D9W3O17Jhxy8kXJlbx0WxvbzORA1j5VxnLTnH/eWeSF80kkelBU2fO7ZHMMlYPu22KBKLnvlX3/1P6Ikyyn96d+EKkPfeVZZZFE337YoEFcftn53HnvE/giZViuS3NTA2NLygiH/CSSGTEszM5XsuyjQ+UsOXhlvyXh/TJ9nS0FWZVxbJtIXilRp4tMOpHLXnddF0eS8Yfy+Nsz/8RxVW6/42ZxfMsyDQf28/gTT9Hc1ExhQQGP3HcfZcUlpNIJkW7qKKRMk2ee+wfxRJK8SITuzi4mTxjHTTdc56UUSTiuhSSD5Ej9FvPPl6XZ2ZJAzAgBnKKqvPCPF+jt6eH8BWdzzDFH8v77C/nsi0UUFBYyZdI0fH4f++sb+GrxYurq6nn6mWf4x3PPsHXbNmqqazCMNKu+X8GqVT+SNg0s00ZV/cQSKTZs3ko6naG8vILhw4aiKTJmMobtAf5cT0yB50RUFKUvFtmxkVyFeDSK67iEgwEMyyQQCDF3/jFMnTqNSH4RquojnF/Asm9WcsWV1xKLZ1B8ITKWOM0lWSKTMYmEC5k+/QiCoTCbf/wBI50SPnOBUujH+DpYQd5/w+yTKAmsD4jTKTfYdlxsRwg4FdkiEYsxbswQfn3GyWQyceFRyT1PTk60KOQqcg5P2t/akZ2VKHfcccfdh/5h/wXSl2UuH3x7QviBk+k4AwYOYMnX39DaFUXVhMdbV3XKy6uIJVI4rigtJO/NQZKRFMHAyn19JaulknOZ8MIZJucestyg0eu2hYJ+bCuDZWZAtpEkgb2RJBk9EGLlDz9St3c3Rx11JKZtcf1NN7Ftyw4k4NEHH2LWtKmeSlbFsVwUReORxx7n8y++IBLOw8hkiAQDPP/sM1RWVWBZGeE1yFEq3H4Wz//jIin1GbkMQwwvDzQc4JEHH2LCuDGcfNLxdPd08e57Cykrq2DK1GnkFxTg032Ul5dTXlrGjh07sS0BgbjqD1cguTahYJAVK34gFo3iCwRFhmJvDEkRcPCaQQMZNXIkCi6ObZIVfDu203d3c1x8uk5HZwc+v05lRQVmJoWiKiieXiqVSVNYWMiJJ5/K+AkTycsvRPeH8QcjfPTJF1xx5R+JJ0TOuekoHvRCQKODgQjTpx9OpKCITVs20tLSjCoryGJkgfdU5Z6DQ92rfc9hv9lZ1g4rZW8tDjI2tmPiOiYyJnYmyn133cykiWPJpFMigTdrq8jOm/pP0bNESFn5SfWk9s83P3Rx9K/TxOLpM9C7nqjNMExqagZw2e8u5IZb/oKj+jEcl/r6vZRXVFNaXExre49waCFhOk5uNiIDsi1ax44pjl0x0ZTRVQVfwEc8lsBxPJmzbCHhsYMlE0XSKCwo9SBuds7o4rgSkgxaJI/X3/2AxpYOBg8awPr1W0gmMtxxy4384ugjiCZiQotj24RDIf71+pu8t/BdAoEgODJGxuSB++5haO0Q4skYqqyB7aJ4eRxeeLwXgOr2e/36Fr5tOznBpCIphPwF7N79He3tLcw5YhaaqmLEDTLpDOHKMDIKRtoESca0LCzLRtd19u7azfbqGk4+/iTKS8r4buk37N/fSEFhAYqmYbS1YjopZBSSyST1dUmMdJSK0nJCgQCSquJYdl/Cl2fVkzQVSxYYUcnzTaiKiDCwbIvpMw9j5sxZ5OcXEwrlEcnLR9b9/OfN/3Hn7feQzrj4/BEsR8TjSbKC5MgE/RFmHn40keJytu3awb66Ovya6oHLpdykWgxPyGnwvNoi9/9zd1dJpEBJjtdIEOcArmSRsQxcyUaSTRKxDs799Yn88tQTiSdi3sVcAsfJuU37P+NZvaGmaSBJGIbhbcxSX8LUzwEafm6BHNRR8ByzpmnS2dnO2b/6JZ989ClfLv2BQKQUw8hQV7eX0WMn093dQzItyg1VFrW+bVjgiMg3VVYI5UUI+oP4fCLt1rZNQQgMBWls7cDGEZdyV8o9hJZnEy0qLqG9rdkbYva1+yRFIb+ogsXfrCA/vJFAQGfBub/hot+eKwxZXm57MBBk5eq1PPvc82i6jk/TaGtt4cYbruO4+fOJJ2IoqnIQ5zX7EggW8MG73s+9jtnulm0b7N69h2g0hetFQeTn5TNm7BjWrd2Aqunoug9XUenu6WLfnr34AwEGDh5ELBFnSG0tLW1t7N1XRygo1Krt7e3U19eRn5/HLbfeyr69+3jppZfYuHErBwr2M3jQIIoKCwkGQ2ia5sksfr4s1DUfkqSQH8lnyvRpTJg8Ed0XRPeH8PmC2I7ME48+xdP/+Cc+LYisyqQtB2SxcTm2hYLOtKkzKCgoZOv2rezes0tYE9yD45qzcRqmYSJJCrqm58ZfruTkFBi5QaEr9yO820jYWGYGx04jS2CZBgMqirnxuqswrYwAfft8/P/8cYhx0AWB/TlU0v5zL9yhg5Ts4gkGQzgyhAM+br/xj6z98bckrRSy4qel+QAV5VUMqiln++46LNNCVSRUVcIfCpAfChMJBFFUkeDU09NDc0cnvdFuUskEwUCQEWPGU1FaRHNbR99A3lucriRh2Da6HqC0pJKeng4MM5PLdgAZw7HJLylHAxKxGLNmzkJVFEzXRdN0JFWltaOTv9x3H7YL4VAeLU0HOOWU47n00gtIJGKe74Sc9kfq1/nIemP6n76Ckq/+H6xjRcjJFYUf161jzi/m4iYE1LmpsYkNG9fj2JD0Tp2SoiKOPOIIagZUU1paQkd7F+vWb8DvD6CoCrFYjK1bt6CpGs888wxz58wjlU5w5pm/4YsvvuCN119n+7ZtRMIRBtQMoLyyEr/f79XsfRo8sbNCIm0wYdQYjjnmaAoKi/D5fSKPMhihszvKHXfcw6dfLMYfyceyRVPAkVRkRcWyHFRF4fBZR1JYXs76LZupr69D906ObNyA69pi0o2L4T3IIOPYaXF5lkXHT1QL3sLwfOUSfVZay8pgWUkkxEaZiHdzwe9/z4jakSRTPQQCwYM2tez7cZCBrv/G78ngc6wxQLntttvuzh4z2QtL1lRyqEjRcb2QT3JjBWRFRlKEsnPIwEHE4im+X7UORQ/g2Dbd3T1UVVSi+fyEgj7KSvIpLogQDOo4dobOjhbq6neza892Ghrr6OxsJ2OYWLZFPBklnU5TXVmD7EokM4ZoSWYvlVkrpuvi13z4fX4ypoFpmiiKJoIkvddXlkB2YPHixUyePInBgwaA5GJZDnfcdQ/rN2wkHMmjq6OLYbWDeeSR+/BpIo0oW68KoqPc9yZ5jQ1FkQUft58Ts7+uLbtrGoZBKBhmw8Z1rFi+nD11DTiOxazDZiDLMsOGj6CiooLqmhpKi0uZMG48v/rlL5kyeTJDageTMdIsXrwEy3bQVB/JRJLde/eQSqV57PFHOfnkU+np7cJxHAoLCzn88MM55ZRTqKkZwL69e9mybRtNzc0CseTz4/cHyMvPo2H/flzHYdiwYRx+9NHMO/44CoqL8YeC+H0BAqE8Vq/fxDXXXsc3y1fizyskabqYNrjoKGoQXBlN93HYjNlUDhjIlt172bVvD7omo2Rbp673KQkYh2llsG0DJOHBED4hE9s2c/4UWfYkS64tPOmujeOayDKYZgpcE7CwrRTTJozgsQfuQVMFx1lV5YMm59mPrOT90CGk4smjcu+tLB98B+k/NDl0deX6+a6L7DGHxKXYQnJcNFklGktw+WUXsW7tepZ+vxElUkBPsocduzczfsI00mmDWG8PnZ2ddHd1kUxEcVwTJBsHB82no+sqtm0hawqaq9DT2Ubd3l1UD64l7VjEYjExlfUMNa7k4qgaKcdG0/0UFpXS291JKpNCQkLDS7RSVEzHoKKimvLiEjRZJmXBA488yrcrVlFQXEYs1kNJcQEPPfwA5WVlJNNJMUDzlKMSYJuW1xGR+tWxfW3pvvsH/S6Youb2+wOASySSRzyZ5oT5x7F+7Wbub3icc889h8GDhjBp8hR8Ph3bcggGQqTTaQ4caOT75SvZsHEjjguhUAjTNGlorKe9o40HHnqQk085iZ7ertzGlslkSKWSBIMBfnf57zjh+OP579tv8Z9//5v6hnoaGxspLS5m2LBaFMVh0uRJnHf+BbmYOUVT8QcCmIbD8y+8zHMvvExHT5xQKB/TcJHQhdpVknDMDP5gPlOnzaakrIItO7aze9d2fIoMjpiD4XgpYpKLLNmiuWKlAQsFMC0LRdXEhd0ByXGxrDRm2kCSVXGqyDKapqLrMo5pINlWrsOVinZw3Z8epKCwgHQ6iaJoOcCgiKbuG1T3r4aywkXX9WiNqtD/ZaVWkmEY7v/bBDNH8XOcn8HJO7jYHhlCwTQtFFljxfcrOfPci3GCBZiST3ScQgFSqTS2LbpTmiJiwBxX7CBGJkUmESOo+8jPD5M2TRRfEF0OkkpBzcCh1NQOpr2jg3g86YHGxFGNDJLrgO2iyTKua9DT1YmRNnBdCxSQXYuiSICzzzqR2274I6Zpcc/9j/DO+x8QCIVBgkS0myceeZDj588llRb3DkFskXO9KtMwxd3JO2V/DoeUTZw99DU0DAO/30/dvn2ceMKJTJsyld9ecAF333UXzU1NTJw0keEjhlFYVIhP9xGLRtm1aw9NTc24QElJSQ4yvq+ujj379nDtdddxy623EI1F0TQtp3mzbScHvbAdRGCp309TUzPv/O9/vPvuu+zasYNjjjmGM351OsccOwdV9aNrOrKi4g8G2bZzF/fe/xBffrWUUF6hcHea4Hr2Z0WWyWRMCotLmThpGuG8QrZt30FDw240TfZKYkUM/bxLNR5w2kjHkTDQFEglouh+nUQqTcYwUBQfsuLH5w+hqX5sR2B4VEXHtEwcJ4OMjSJJ6LJDMtHJ6af8gueeeYxkMoqu6F6l4/zs+5Md4NqeKNPv9+fa8Pw/jb15uGVXXef9WcM+052qbs1VqaqkqjKRkJAwwwtqsF8RFJrBllcBlbFVjIriACqO3W2/Ij6CCiogCLbyiiiNMrQQeW2ZESiSyghJVWq6VXe+555h773W6j/WWnuvfe4tH/M8eQhVdzhnnzX8ft/fd6A2T1RKIYqicJONZZoFkkoUtwwQAaVEYHwqxvmYweaQ+V3zvPFNv8Lb3vleOrP7KEzUGWhUljEel5RmhDMjzLDP7PwOrr/2GI+98Tru+LZncsvNN/LJu+7iV//rW+j1dlGMNdg2V19/giNXX8OFi5dZWV2nFTxkS2d8/KOTCCxKGKR1jEdjFpcWQVquPrKH647v57Wv/kGuO34tv/Gbv80nPvlpejMzPrqsLPiNX34jL3j+d7OxsUy7q0PDLytkWzgo8hzZJBlUde12HmMp0FEURWUc8X0vfjFf/uKXePvb3sbBAwf4i/e/n5MnT7K2sY51llY7QylNt+vLnLm5OXq9HgBnz57lK//6FV75mlfz+297O+v9tWA/OonOBJaE8Bob5wSdVhutFGdOn2bx8iLHjl2DE5Ys62CdZMfsHGuDMX/1wQ/x9nf8MQ+fPc/s3LxfpNaB1Ug0SmUMxwWHjxzjpltuBQF333M3CxcX6LQ1zhXh+Wg/LQ/NY1kWUI6RlGSyoL9+iSc96XH8yq/+MgsLlzj5jbv5/Be+wql7v8n5CwtYK2m1u3S70z6vMC9Q2q+njpaY4QaPe+xx/uL972Z6puONwJWqTBrKsgxgYxNKjrEfPp2rvcXruDKxLsvSTXpf+aQi09gYkz1J3EixuXPOkuc5ZVnS7XZZWLjE9zz3xZw9v0LW7lIYQ17mDPIBQgsO7t/Nk5/0eG679Wae8qTHc9NNNzA/N4srDHleMDU1w5t+8zf53T94F53ePvKRQrUybrjxZvYdOMyFhcsMNof++vTGtOGML9EYtPMs0Y3NPu2O5oYbDvIjL3s+1x47wS++6c187eQper0pjHOMxjnFeMDLvv+FvPKHX8rVR65ibAa+l5Eq0Dg9Z6g+adLDRGx7eEwmdJWltzbduXMn//LP/5sXveCFHNi/n9f/1E+xcO4Cd99zN/3BJqPxCBPKOqU0razF1NQ0eZ5z7tw5HnroIb7jWXfwR3/8DjrdbnTZ9EO3BhITNojSQdkpMaWhzH0v18ra4CybAx+HJqXmY5/6J97y1rdxz6n7afdmKJy3eTU2UGqExBUl0mWcuO5xXHv9LQxHI+6//x6WlhZqxkV4/S5BnqzNKcox0pYoNyIfLnHLY47xh3/4Vq46chXOCdqtLpvjIQsXL/HFL/4r//zPn+Ohbz7MV792N5ubOZnOkEqQ6YyZXpfR5jJ/8s7f5dnf9Z2MQviqFF4XUm8Q2xAC1n2GC8Z5qjJfj9VSVTFZa91kTTZpIzppnZM6odSLw+P9/pdAt9vl4x/7X/zAD7ycPC/IWi32HtzHc5//HL7tWc/gxpsew9EDh4MGuMAUBbYsvdxUZb6WV5o7f+YNvP+DH6U9tZ+yyJBace31N3LwquOcv7DA6tpqIA26AP2BdAbhPEqSKU8+3LW7w0t/8Ht573v/gjOnzzEzNUtZjHHC0u9vUoxHrK8uc/jQPl75wz/Iy172EvbO72JUrFMUeXBgNyidocTkYVHPT6NjeHPoReVTG79vqjvNe97zbn76p36a/fv285133OFdCzc2GI2GFKakKD0ULvBD1YsLl7h8+RLf9V3P5nfe+hZm5+ZCbFks9cpQP7sqvw8psUr56TYKF7TklD7yrNVtU5Rw/wPf4k/e9Wd8+O/+gXFR0p2axTrf4xnjezgpfZXQaXW59XFP4+g1N7C8vMxXvvx5RuMhWaaq9xjIDbUToi0wxQDtLF2tWF9bYN+uLh/6qw9w7PgR+oONYFdg0TpDK41udxAiY2Pc5/77HuTTn/oM//L/f46Fi5d48KFvMtjc4Nd/9Y284WfuZHNz3bvySOVZEaH/K8vadjQV6DlnkVInB3/TfzoCLsI55ybH7OkGSc2t4wKIV9MkjFmGbDvvkuHtMT/8Nx+m3x9y9TVXc+jIVZy45gQ5lqEZYMrSm385n6etpajYajbMSPJizGt+/Gf58D98hmxqN6WxKNXmxLGbOHj4CBcvLdLf2ERnvrxy1iIj2bHifCpwOZuDJd/k6Ra2yLFmyOZgk1HwfMKByXOEKzl0aDcvfemLefGLn8fBvXu9vc5gQKfTQYna2d5/qK46OdPgyIoZnVDx4jMr8oJeb4r3v/8D/Nqv/TqrK2tcffVRut0exhiMKzBlyXAwYH1thXFesGvXHl71qldy550/iVR+DuQFbmGj2rJy2zc2ZL1IhRUKG2/60qKEYqbry7WT9z3ABz7wQf727/6epaU1ZuZ2ev6ajfkZIjB8M4ajnAP7D3LrrU9kamYnj54/z4MPnCIf+4i1GMsGwfQtsDiLcswoHyBdwXRbY0abtFuG9/zp23nm//V0hsNNlA6by3n6i3VQOoPT0g9ye1MoJKPhmHw05t77H+DMI4/wvc95Nu12C2tKLA5nqTaICJu71jb5yij6i9UjDBfslDKKwoTNE8CWtMTazkcqRhpMOpukddqkEEVKSVGOsLak15tCyTajvE9eFqAUpXUezYjTCuezLaKZdXUllx7qGxXw2jtfz9987FPM7NiDMRo7dhy5+jhHrj7B+saI1fW1OiAlJB8hHFaAcIosCxvQjBmPhjgzYjhcZnMw9GKeYJ+qpKalNWvrl9jcXOHG64/zkhe9kBe96AUcO3oUcIzzIXmRVz5iQnh/V1OaLR5i/pn47VHfJr4cGg1H7Ni5i4cefIj3vOe93HXXXZw5c8arApXD2pJW1uLQwYM84QlP4BWvehVPefKTveYhSJPjZ0Oa6hQOK6EUTikKCxZFq92mJSSDccF9p+7jrz74N/yvT3+GleV1tGghlKKwOdZJLBKEQsmMovD+yLfcejs3Xv9YxuOcL3zx8yyvLoNw/nCJmYUuasi9r5UpS/LxJpIS6cYMN9e56sAcf/D23+GpT7mdfDRCqaxKFsP5G9CH6oDUirwsMIXxlBkJ1hb0etNMd+boDzYCD01WA7IUSYzNuDci8THZSmlSD8Sa9dAUUEkpfZN+JaO1ehCmqsHYZDJoOnyJCbORYmGtCelJCuscOssQIQSnDD2DRiKivZCKzOdYryjvopcpNsdjfvL1P8df/PVHmd51EKxAOc1VVx3j0FUnKJzg0sJFyrJAZyqkI1THt0fMSoMOuvhi1Gd99RKFyT3x0HkDNCE9Xd2VJa2WZrC5yfrqClcdYPm9RgAAMSJJREFU3MNzn/MfeMXLf4DrrztGp9VlnHvUxTnTeB6+SaSKIo66/HQIG/UJOG9P1O60WFpa4sEHH+DUqVMMRiM6nQ475ma47XGP48iRo5jSsL6xwfT0dPU5NDD9cPrKqEkpHbLdptebwQELS8t87JN38aG//ltOnjxFf3PIzNwsmcpwZTicpLf7QWpPCzKSXfP7uenm2zh48Ajnz1/kwYdOsbJymSzrBCFaPJBCnIVwYbI9oijGKFvQlpbh+hJTsy3e/74/5alPup219WU67Y6nMBHr/zKazfpnpSSlMeTjkY8ot7Zyl5RSo5Q/uOvnEDzSkg0SXetN6YNz/G3vla1S+sRgrTXjfFT11RUSmW6QScPqmsQlq/JpOwg43SD1n0UGazSJU+G29hJeG0JWtFSB10Q9kbYxbstPsMeuRGSeh/PGN/8G73jX+9gxvxdJh9FQsGfPVdzwmMfihODc+fMYZ/wij/vDRbM674ahpJ/mGjNmdeky+ch73noH8LE/Da1/BSpQ/U05ZmNtmalei5uuu4bvfs6z+Y/Pfx5XHT6EUt6suchz8pF3JlFK+5D7IKSqfJxCqZVp7zoYCXLWWVSmA6Qsak4SjvF47Es/aivVuPnKsqh+Zpn7Z9rtdslaLTaHJZdWVvnGqVP8/cc+xmf++fMsLa9RGuh2pxCBxepDLxVYMKZAZx1KC9PTcxy56jjXXncDQmnuuedezjx6JjxDgotUquSxVchNUea4coRyJb1MsHLpAocP7+ZP//QdPOH2W9jsr5NlqpoRGVOGG9ef8N6ZP7AWnGE4HHiaTFn6mzNQW8Js3X+PmlQMNkElaxxlmI1olVUxbTGy2r8GHzLrARKJMMY0UKzJ3iPtRyY5WunUMVKG6w1kqzQqEqMvobwBnLVlkjVoEM7LJG1pa8amEz7vQTjKkCg03Zvid9/2B/z3//f3yTrzjMcKazKmZ3Zw8623IXSLixcXMNZUMt+GxqXiAHkdgUYy7Pfpr6/6KzyzQIELGRNUuRIS4RxlkZMP+wyHm+zbt5snP+WJ3PGsO3jWHd/B3t3zzLRaYcFDXozJ86F/v0lQvRCCltQTdG6FE46RySmt8X2MdX7yjEQLiXSRH6krPpqUgqylaLe87n44KtkcDPjq177BP3zsH/n8v36FU/ffj3PQ600jdQuEZxdXhEEUUnjnEmtKlOpw+PA13HjjLUxNz7G4dJn7H7yPxZXFEPwZTRJcHQHuRJh0FxTFmLIssPmArrKUw3WOHzvI297+Fh5zw/XYMq8Ow4i0xfi+GvWL3Ct/oOb5KOS9BKtDY5MkW3xjHyS1Mb02ZTJUbpuhac90JxFHiUANUljnZ2exyW9skLSPqI197bYJU7UKS15xDrCltxFecFVtEEAq5WWSCR/IlMmblw7jDMZZjC0prWHX9G7+5H3v47d/5w8ZlhlW9BgOcg5fdZxj193gXfqsraxhGtLNijbiQlQXKCkwZc762gqj0ToCXzZ5y33P+VJCVCCAwusdhsMBeT4GITmwfy+HD+3lmc94Krfd9FiuOniAw0cOMbdjFhVAg7EZk+cjn+xkddXMg/MafOmHLcYGp0vrqsWYicyrFKWm3emitaA0kI9z1tfXePChh/jK17/B57/wJc6cWeDhRx5lYzhA9zr0pqcReB2NtS6wnbMQVeBpTmVhkUJz6NARjh+/nt17D9LfGPHN0w9x7vyj/mTXGhEksk2Gf7TjMRT5MOjyHVqUFINVbr3pGv7s3e9g7945Bv0+vU43GbLaxsEMUBoTHNm11/mIWkobZxp+rdbrq9VqIxAUZeHPm1BGpZBtVW6FPiXS3qUk3ECa0oxDLk2QWxhjXGxIrLUNTlHcCClTNf7rCWZUKEr8nu1mAdE517OOS/+/SUNvrEVJGfj6qqYbKxGNkihMQVGW1Wk+3Z7l1T/xBv7Hhz/uh5FDuP76m5nfu49HH300THHdVvKlkBMETs8alcIihJdtDjY3GA7WcTYP8hTrbxRspUZLqTgeTswpRkOvasMx1ety/MTV7N27mwMH9nHjjdezf/8+9uzZze5d8+ya2UOWZbRbLVptwdD4/kcJQWkKfzAUBmf8aTnczFlfXmdxcZmFiwucfvQsp8+c4dEzZ3nkkdNcXLjE0HhaRqvVJcvaGCUpsd7zzH8CCKWxSKRsY0xJkY+Ympplz579HD1yjIMHD1OYkjOPnuHh06fZ2FhBKx2Qu+1tm6I6DwxlMaSMOZN2TE873v/nf8RTn/g41vvLtJUPNfInNo3Q2MaQTmTh54KjrBZ7rFZiH+K/VxDNRyKAEeOgG62BFOEgjqWYrQ5lT2PJsC5HKosUnhmitwvJmbwxYsOSfm2shasSaQLynSzDTMi/80xMUTFuRZBO2jI0mNJVJYQJXBudKRQCJ1Tlr7TW32Dx0hJS+AzDrNVl7959rAf3wGZ8Q2IWIQyVKAJw0lVae2csSraYndvNzMwcRTFk0N9gONhIuFaxpHA4FwFlnzHenWuHuY4v5R54+Cxf+Oo3sGWJ1r4R3LdvL71Ol/mZHczMzrJjbo5du3bRmm6htPR9TD5Ghli20WDIhfMXWb60zMbqBkVZsr62zubmkE5vCpB02l3aM/O0lKMoPVescCao9UTtKiUlTijKwkOv01OzXHPNDRw7doy5OW+l+ujZs5w7f57F5Us4rO+VnAjiMFlZxF7RbEPG09nHeA8Ha9xz9z089fG3IIXCOoEULlGqum1RUGddlQwlpKvK8XhQ+woGX4ZVEc8Speu5XF2C2W2Z1S5kr9dM7ToR19jSAz6T08VY56XahklCo03s7t0V/IzSa60sy8RRO6AlTlYP05PFKtNRf5Jb6139VF2dSTyWLZTm8vIip07dS68zxbC0TO+Ypt3psHrhQnV71N5axmP6TiVZE5PVn0TptmeLYpGyRafdpp1NMzO1k+Goz3i0wWg8RAJKZ8GDyYasChGi5aw/mYREZV127e6GS8tRFAWDsWS1v8Hpi0sgqDx/tfZ0h6IsPOysfV6IlJJMZ/iIe0Gn2yGb28HM9CxSKIw1GOHZxLYsyXRGWRa+FJGem2Wt9bONwpG1NLvmd3Hw0FEOX3WUbrfL2voK99xzNxcvnqPf30TKNjprB7MNVzmi+JZObb2BXW3cJnWGKn1UtHUwzA3fuOeUPzCNw2WhnTcGIaVnsYfBXYO+I1wtYBUuEaJRGWV4bZDcotOpY8ld1Wz7taQqBMtar+NJCbqlyet8RWMwhnqDpLyryeY7NVOog9bFllIqwm1xmJhO3uOg8Urs4TSOuiy9OlBrDcIG/NpWaE9XZpw+/SiXLy+yY9/VDPoFM9PTOOfIc5/3HZs5sUWgEzfOhP7FUUGz0WnDQrDSbJO1Mkyv51mygyHG5BQB8RLK2+eLUoLIkAHuLMt444aTUChMaVC6hWh1wUEreGa5EEga/amcM+BMZUcWLi3GpQ00DjyYEKLmEF4LPjYWY/zCU8binCTLuszMzLD/wFXs3X+AHTt2gZAsLS1y//2nWFy6HMKIJO1WGyEyTIivSykauAlHv/T5JR66UmdQjP1nqiSn7r2fxdUNptoZhSn8grU2aDy2y8V0CSNaBpmzq1gKVVlbDbhdoLS4YHonk7XqRXFCCnSWZJ/YwqNV4TazTmDL+nfH5DSdNtOTZdTWPMJmXvp2N8lkxFUa9hn7mckyLk29dRNRCfVmCnS3AL+ePHk3pTHBgypnfn6ezc1N388o1YxbEC4ZIgUn+WhZ6VSIgKstifwBJuot5lQ4bdrMzEzTm/bJuXkxZjweUpYFZpxjonWmDNN7YSv6S8zm9hCSRJk04s4hRKBum4i2KaKwAPAeU8EMIbqaO2FDD+UNC0wu8CV4RrvVY252B4cOHWbvnv3MzuxEaUW/v8HCpQXOXTzP6soiJh+hs4xWdDVv9JDuyiEEziXPWFQeVVHWKpSizHNa7R5fPXkPp+57gG9/yuNZ21gOPs0i+TmpQTV1aRX4KtH1c3JYXTvfBOGXFMkMRCTVjqDVann3k9DfuupAjGMAkaBdtupLdDoQTNGpK4XnTMLA6QaJt0768yaViJNlWLwuhXANi5fK/lSJSlBfln5eYhx87rNf8rEEhb899u3bx9lzCz4aOfQAXv3nqhyJ7U6+SudCPKlFje07UTnOx/dblBajJbI9RaczQ3saKEtsWVAUI0bjIWUxClBjWU2XZVxI0d5V1D6wDk/r8F/n4dPotyeqfil8nfHQaukcJsyapFJkWZs9ew4wO7OT3bv3sWt+D72Oj3NeX1/j4TNnWLx8gbWNFa+6VA4lJSpT1TDVWtuIlr6iHy6TbGZX0Z2j8YaQ2rO3pWU8hqWVlSqiWwRCoT/xY6pV3YNo7Qeojtq02lYcsxqNij2DF1XZLfxBf+jLStDWPNxF004oMYtLAZgtktuiKLaUVtvdHimbVylVoQWpr2+qxU4Jj+kmTG+ips2QC2++3ul5kSOd5uy5i9xzz/04nZGXlvnZOdqtjk9gctY7xDiHbmWe6u1SIdjEUNSasFIjJbzOSaFiaYfbMWw0Vzlq+KtbZgrd0rRdl2l2kJcjjxCNc4wpMUWOtWV9IOCh68n5jHeQrJtTKSSCDC01pfXlXqvVCkbhPebmd6K1ptfrMTs7R6czDU4wHGzS7w9ZuLTI5cuXWVtbZTQaAQVKQ9bK6oNIiMpN3W/OSPlxYJs3RHDubbACXGIgHVFIS4lSLQwjX07lJd84+XVe9N3fSeH83SiUn3Np2cyVjEPQSNFRMgBBpSdigsQ5D1m7EBWulMaY7ePLfT/hkhtDRBYlUtWkRv9tOrQJdZS1Tk0FJvUf2+Wnpw39dvHRaT+SukbEDTNJC58szbbaQta3SlkUzM7O8I1/+RJnz1+kt3M3g5Fj95495CF8sho08e/LLhTU1jyiPqz9CKIqFwLRL9wtwm2xUKYoDVhHpjO07iKlpdueDR+kdzcXeMp7aQ2FTXq00lQT953z84Fsp2i32rSzDu2sg1QZ7U6HXq9HlrVQSvgGPQy/RqMhF85/k42NdVaWV/xBF25nqSRaSRDtCtaujPxSSMpNvDmS/++2v0EqzWR1gHoNjZJhs9iCTrfHxz/+CV73oz9Mq5U1GAXOeSjWWkur1QoHsKkSnnACa9IZHNsmLqe/P5UfpOs19hf14e+ZCP4mqr3V0nWt465NG/BJJWHTmrS5OSbLqO0GhSkTOL7AeKukPyO9eZKtVmPkxttkfvquz1A6kKqF1o7ZuR0sLi15UzaVVcZj8WSOIqXt/F7r/47NnCcSKiWZ7vXobw6wRjQ+GEnN3HU2iP2V9kNQCPCv8OaLqMoFUEqJztpoAa3KIE1UnY8TgutueAzdqSlPfPS4OGVhKIuSvCxYXl1lMBiwsbHOaDhgOBxRloVHsdzQDxWV9h+6U/61imjj5RKIW9bIRKr8imyDSUPYKh/ApdyIxrMWQniIO/x8KSTWODLd4uKFiywuLrJv/x4f92A9ZyxlgEfemnA+6DOul+ahWtNa/GfiAm3Ic7iU9O8r1Xakup0m2TYgfYKaRwaBWezfh051Hltq8wn9bjrUEQmTNP6Z1jrAY6YhtEoXpD/xysbGmdTAVxu2qiklzpXoTLOyusrnv/BFdLtLUTqk8jrmzf6mt9JPbgRrrZfk/ls3SHUahYQ6Y2hpxfz8PHNzO3AO+oMBa2trjEajYLzmPNoUX5/wG2JyUJrm6Dm8bDTe8FGjLepjACfg6189SRH8hDGhLDOWosirEzLGQKusbl6lqvlJIoAQwhGm5qLp+dcIMrL15hB1IM12OR2OxCjPbU17qd+7qOYQ1jo6usXK6gInT57kBUeex6gYkildkUhJfRDwYidPGWnaBE32wbE3rVxIQv8oEkujdF01gYE6w9JanzfjQsUSZ3tFUWzvizU5KJy8Ga7Uo0x+T/qiKoSEEiED7Cv8VRq9rrZM4YX/OyH9RzM1NcO/fPbLfPPhM3Snd9LPR+yam0EDo2Eor0Rqh89EOio1S7T6ulDvI8MNpti9ew+65bj3vpNMTU2xb/9eDh3YgzGCIjds9NcZDocMyzF5WaAzb3vjhMLJwEsKDaUMiyU25ZV5sgoNuqsDg3CWtZVFijIPz1hUlHpVHfgCqSRO+Z9hQ+pWLIWcExXSRwOM8mM+goGztcaf1sKGiOSWR96kj8Lw69zbkCa7J/yexIi8KsFEMzkshoUhENr/+QMPPESm24zyzTp9VtTJUl7Z5ysFF4ay8ZYWoReJjb2/EU3t0FgBRaa6QSAAEIhqKo8ow3OXyQxHVpk1Dudv7BAMpdNG+UpT9bSnuJLqcFKNNdlfVI2nlGSZCvMO66fp0pPFJpssP3ALghbraHV6nD59ls3+kJneLrRUaJ2RtdrsmJtnZXUtuAd6630pm6b6NpX+pXAlgQLjLPsPHQJh+cbdX6W/sY4ATp95kOnpOXbN72XP7v3s2bsHnGAwHrO23mc46Fc+UxGpkXUcTeJuQuVUHyfe8cR0Yfe02y08iBMOFuu2BHR51qo3ctZKYpy/cZSsVk5AzGzD49iYwsdpB12EV//5xZxpS6Y7KKf9wgqbytK8SQTNUiVeg5Gd7Vxg+roiGHKU3oBNtbl0cQljy8CFEv4ijYijkChBkPZab8aX/G6/mKPTYijFlUvuNdkoxwOYH2ZiNdigtA5mdXE9qsqnFwSm9AbjCD8u0JO3RLqo03InvQm26zMmr7Et8QYVguSaDZXbGmta9znWn8pWItBY5/ifH/ko1likdSgHyysr3PfgAxy9+jjXnTjBxYUFlpdXfLkl66mvE2LbCbo1zivxbMmxq69GZvC1r3+FjdVFOpmv5Z0t6a+MWF9a4My37kW3Zti9ex8753czPzeL2DGFA0ZjH3gzHA1Dcw6FMVUjL0StcUy9mlRQM8ZNgq0pY5Fyn1SODVNsnEMG0p4SsZTyi944T7oscn8AFrYE6RAYhLNk0mtgytJP2qUTHmKNO9kpkObfjMqLtbs/ezxVvChGCFt4IzhKbCnodrp84XNfYnFpiZ07Z7wkQXihVTy4rHO+1Awq0zjziTdV1N00yy0TaClplIZnOUc+VtTKENjP0UNBiCxZ62yReWit6w1yJVXhZKk0eYtstzEmSzLPi4qm9RN6bVQDfp2Mn65oICHs/ulPeyp3feZzDPqrdGbnGBYFZxceZX1jjetP3MjBA4fYuXOeC5cW2BwMkJn3m3XxqnXNzZtpTZGXHDx4kCxTnDz5ZTZXL9PVIM2YjeV1elM92lkboSVSloxGi5x5+BKPPpLR7c0wNbuD3tQ0O3bsZHZuij27d+KcwOCNLPqbAzbW+/6kj/zLSeFZ5Ey5Oiii0nsFEdRWJ/wSiUArMFbUPlAYTFlSmryB6yvlB4/COkw+pt9fp9Nu0Z2aojAFthwxwJFpz/bdFgd0zViKSDr2t7WlLApMkSNd6Q8xJcjHA8Yba3z/C57rjSLi+w9D29iwW2OwDu97xoTjZ+BjucqFenIWk3DlGnLooFOJoy0nqywQKUXlNNMccNdBOmI8HrkoMnEh7XNyc6T0krQcS3uRySYoNupp4+qHOwEpcnE4FRt5WYmrtsLK/roZDofs3DHHR/7+E9z5M7/AwtI60zv2UtDChizDI4ePce3x6+hkXRYuXWZpYw1njadqCyqtQPV+SseB/XuZmc74169+kfWVy7SERZocWw55whNvZ2FhgXPnL9Jf72OMo93rMTs3j3WKonQMy8JX5Sqj051ix+wuet0pur0p5nbuJMs6PHr2PMN8FKgsDmvKCBqj4k3nHBvr69iy2Hpmi2aAhhSiNl8LQzI/W4klhsUGrYWW3s3E5AOK8Sa9dsa+ffM86fbHceb0ab5+8hRzO/czKCQjJ2l1ZsiynldYCleZXTcCLqtq1c+PrM29CYYtwI5RQCYMG2uL7No5xRte/zpe+YqXMhoNyFp16+spJ74Uakgo7MQIQdDIMRFpsKwDJXUFxUd6j9a1Q2hUFUYdirUOXM0cr+PQrS8/nZ+HiNFo6KI6zfPqW5VFzWRpFbv7+HeppU369ylkG5Gt+lYyNflQ6gmbTtuA9iZvMm9HaZiameVbZ87zS2/+Df7uf34CKzt0Z3cytg6ddWjrLtcdfwxXHznGKC+4cOECGxsbHqHIlHeCB4w1HNi3n/37dvOVL/9vLlx8hJluC1kWXD7/KL/1W7/M6173WpZWlnnk4TN89rNf4FP/eBePnDnNxYVF1tc9o7bV8+bOCE0ZTnKcQMoW3d40t932RJbX+yyuLKGVDoO1ymC4UgfGDeLKYmvGa6M38xthNOpTFJueqh+m054LZyiKnDwfepdBLPM757juxDXcfttjueM7nsnNNz+GI/v3cf78RV7xI6/m/gfPIjuzbOQWJzu0OtNeOhwsMGzIdZFJeey1JSXG5JRmjDMFSjpaUlCMBmxuLPKMpz2Bt/733+Km669jdbASoFMdVJ2hgwgXQlU9TPSKQookuiAcAMGAIfKttG41KxDhD5Esa1fIakWnAfK8wBqJUnoLdFyY3BNGZbhB0vsqnYtM8qnixDzS31MD55RinsK4UWlYnwbhVArKuLI0lWUnCVdmqwTYYT1RibwoEVqTZS0+9Y//xC/+0q9x6t5vMbN7L3pmGmczoMXOHbs5fvQE8/O7GAxHXFq4zGC4WTVmO3fu5Opjh/nG17/MhbPfQlJQjNYpRuv88i/8DHfe+TpW11fJWopup4dWmo1+n5XVNT77uc/x5S99lbtP3cvJk/cyyo3XWagWne4USI1SXQoruemm22j3pjlz/mxQJkZ5arStENUG6YcbZGsIsqzo+s5515PheAPMCIlvuEfDEVoKjMnZMTfLrp2zPOVJt3PrY2/ksTdfz7XXXsf+3XspKRkMNxgNx3RbPdZW1njVq1/H1+95CDU1x7CQiKxLu9WlrdpYV98ghD4nHoKl9TaiggIpLFoYNlcvs3/fTn78R1/BD770++hmMBqNabU6fkFaVzu9uO2l3rHsdGyVdkeDirLwGiEvu9AV8lezNbxeyZT+tWYtXTGCy7IEJydyC4O5nSnqCft4PHSVMGWibIqneZoXEm+IyeHh5JQ8LWPi92zlu0SxiqpRq2Cfs2VSj4czpfWIz9gM0VIzMz3LwtIib3nL2/jj97wPKzS79x6iP4Jx4YdOu3fv4cTx65mamqG/6ZNi2+02R44e5b777+GRb97PdEdixhssL57n19/8c7zhp3+a1fVFnNAo2WQPaK2YmppGkTEsRzxw34N86+EzfO4LX+b+Bx7i/ocepj8syUuNoc3xE49hz/5DPHL2jIcsE4v6LRtkYyOUWE1QQUQDNlF6s2ZbkI/XUWKMtDmZVjz5SY9nz+55jl9zhDu+/RnsO7CHA/N7/Iduh4yGI6wzXvYc6mxbWKa7UyxcXOZVr/0JvnbPQ8juTkalj3TutrtV8JFLWLSmLKlTPQxaGMaDVYpRn//0ou/hZ97wOq47eoz10TLCOrRq+1JZxkFgXOcJ8idlo2qYXHs1tSWgWsHELyXLNlWwPh05DoFjslQkNjZtdOvxZ1mOK383URRjF0lpV5pfTE7UJ6nxk6rD+LPiTRQboVge1EhZfWN5BqWrrrztSi2/ZkICFZ6kZ4MUd6o3w6c+fRfveOe7uOszn6PV2YFqdRjhJ9FStNizez9Hjx5lenqWVqvFww9/k/seuJd2JsgYsbFymZ++87W8+Zd+jvG4j1Rep5xqSMrSYExBJIlJpei1Oz74xhm0yPjQ3/09P3bnzyLbO8hNm/ldB3nMYx/H2fMXGI1H3onDBP5ZxN8DgrW54W+QquysNkg4OITB2TGmHGDKPi1lGa8t8Ys//3pef+d/ZmwNbamw5IzLsTe9i8OzqKCrIGf/N6YsyXSLc+cv85rXvp6773sI3ZtlmHvnQZ1l3jPKGErja3QRKPm9doYZDxj0l7npxmP85E/8GC/6j9/LuBhgyyKUlDEZjJqqH953WXhvZ6kig8JVrGARpABCSrRSleAulurO2Ho8I0gqnxgrl5hiWNcwPaxTbV1ineSLKa+pN2SZRuTF0An0FlQqLXHihohaj+20I+mtMwkR53mebMAaUtsqrqqHRf77dXM2k+QYOucJb1JK8jz30c3tFsY6/vqv/5Y/e8/7+drd91LojG53FovGWIGWGQcPHEZrzekz3wJpaSvL+vI5fvanfpQ3/fzrWV1dZnpmpopWiBh6tdnj2anDJhGSvMgp8jGtrMVgWPJ93/9yTl9Yw8hpLG0e//insbreZ2llOZjcTYBD1i/gwcYGxhQNf7AwQ8QisNIh3IjRcAVhh2TO0pUFH/nw/+DEiavZHGz4mjzzgqtYUvhZkq36ncg4cGES3+9v0Gp1WFzs89KXvZz7Hz7H1PwBNsdefoqI8KhEOEuvLSjGmwz7axzYt4sXv+h5vPZVL+eqA/tZ3VwF6+hmbVSAtsvgWtLkSwlMGQRmIW++nsS7ykY0+n/hXKDL0NCaxxvBu+/EG0JV85b6cJaV6aEXTNWycVlRVHz/Fte7nIR5Y0MzSXuPZmTNfqLmZqVlVIpsTXKrJmvJlCbgrz2vj5g0GyZkkaQ/21mvVlTKs1yN8YjID730/+FDH34/b/jZH2X/3DT95Ut0Mke3IzF2zCNnHuKbD9+PpUC6kgvnzvDDP/SD/Mobf568GNPpdJqhq65+nVprWlmGzjQKgZYSiUML4c0IhODAnr3c8tibGA769DotTDmmyEdMT/X8Jrfbm1q4IAjzSsXwb0Bc4v93uMp5UCDQQrBzbpZ9+3bj7IhOS9HSAo2PirPGYo3X/PvTNFgyBW6WdxssfaiOKTh6ZB9ve9tb2bd3FxurK0gpKG3pF3g4E1oKVhcvkjHi+174Xfz1B9/Nb/3yL7B31zT9zWUyqWkpHVjLFpNYrm5XodR/XmfTVIhpqldPTPFSi6nUbJ3grugsjbXp072KsLbCWpMePrYuZByGxj/Nu5dRtB4XQPyB6SaJLzY1hpvcJFfKGLnSrCT9Gr+JRKhaPIV8cnAzybIUoVnE+oUg8R5bSkr6mxt0Oxk//mOv4m8/9Oe87j+/nN0zGauXTjMaLKPFmEwVCDtkZekMr37lS/ilN/0cw/EArRTdbrf2Ik6oNVWt6+qhnApUEiXDQ8dhzJhnffszaCmw5QhncpaWLjHV7dJutaqclW0Nrxv9YvI1SZB2aS3G+azHTEhOHLuaTjfzzaUMgIcIojFq6yJPI8cbWJcGUybx1dIP8zY3+9x043W8913vZMdsRj5Yoi0LWmKMyzcYrJxHmjVe8p+ey1/+5bv5vd//ba45dpjV4QoWP8OQYW7j7FaNRip7iJB7zCSs0dCQPqvkFT0SJp+fNyr0Bg5eJqGqfMiyLMO/BdYaL0nGVixeG1jRkZCaMtllxMyjea+UaouryXbDu3/PP3FTbR1CpsOmxD5T1OZqjsnhpWgABJPExuQM9hp3A8PBgAOHdvOrb/55PvI3H+DX3/h6ju7fwfriecrBGvlghTt//Ef4vbf8F4QrA+dHJjehqCfWjah7mjh+wvuTQpKPR9x6y03M75hhONjAOcPK0qKXcGov7vl3svG3zunwjFecRUtJf2ON53z3d6Gk8lZBSK9GFB7OllKitPaUehOMsIORtUgEUc76UjrL2qytrnDzTdfygT97J7unMgZL59i49CizmeElL/y/+cs//yN+763/hRtuPMHa+iqD0RiEojRgXK1KTNdRxS0Ln3VEQ4UM0LHYxivAyYqvViGZYZCXUqPSeZmzrlKMimAxFaFgGZJ7m7MlUU3dGzau8RAejTYcqEo9JqWmCHriGNaSwr6xNpssr1J2b3rlTe52f3pEY2HPC1JKBmqFCRshSB6FqpNPXY1yTUZoVa8t2o3iws/32XdlWaIzxVRnlosLF/jIRz7Jn7z7PTz1aU/gd37nvzEYD5AW2jrzVkMVIc7hbP27nYvWpFsBifR1lEVBrzvFD/zQa/j0Z7+G7s5jbYenPv3bWFkfsLy8iO8dPYrlXO1Lu76yginz8MHp2q9YQuEMQjrGgzWEHaAZMduCT370/+PgwZ14y72symUvy7xy9jAmKvBk8r587V/Jj4MHmDWGvCjYvXM/H//kJ/lv//W3+Z7veS7Pf/7zOXbiKKUtGIxGVC6xztGSGkW0cxJJFJ2ojfeSQXNcMz5k0wXov0ZGTUlIN7ZI1URK07K8ej8TN7EItqVejuBZwy7kMQrpy/N0TccNnAr/nHPoIG7eclLF2r6Zuye21ZSnfcd2Zdd2gYl+E9jqllKVbsEmg0RZZaXXYpwozFdb9R1h9lbRNpRG4mO7xvmY/mCZ+fkpXvPql/HCFzwbqbxTokSQaRk2h+cgueB6EXXkLnKlbM1g3U5lGVNks6zFdddeyyf/6XN0p2FQjFhdWWZqao7lYOBQqxtr+WL64de8NeGDKgkeT65ECRj1N7n9xps5eHA/o9EGrXa25ZYbj8eVa2CjzI2/03nzPhcPslBFdNuK/uYKz3jGU3j60/+GmalpSlsyGgxwwpFJFZjEHqpNp/1Ka2wie5AJpyreHJN5M+A/a083ccF+1rMrdKYaqGZzKG221iZxjbqUgZAoghxb1vGkeV0ss6Q1dgtREHxudPQs9daZ/mHYij9jGwjUJP19skbcnu8lttWgxN/v94Ct1F5pdNb2OncxoSSsYxBkKG9KU3B56TzdXsZUr4ezBVk0Mxa1SKveBCQOG1tFddtR/qM52ZOf9AQyJcGVgGFleZFut42zjqLIPSvB+NLHBrvRSJ2IOv3YfQhnaCsBZY60JVpAmRccP3aUVqtbh6wqVafwMtkDpswHWee72CRfMfLVQp55Ph4x2NxgeWWR8WiIFKCFrNxWpK2nOc7XmAhH0r82xU7bKU/rSqSsnQ9l/Vlu6T0nBovRzkcIqv61Vgk2kdOtvKsJfWlyCQgh0NtwFMOJo2uOfQL12rL0PH5Xn/axmUqHgVUD5rgiJDzZY1RcTJnEiMkQ81aVWLJBwGvqVBI2cKBjeDNlKh9b61pMTbf8XMFJbwsagHRnbeU36ylCJrTh0qMdpmyYGUwCB1JKj+kLSVHmPOUpT+HY0SOcW1wj02021lfAOQ4dOMCw8LwsZz0EGyk87XYLo2ptRbzghbOYosDkY7TyRbESkic+/rZ4JNa2Saa+hdrtdnhmMRMjeFEJ19DLeLN9L7Kq3lMQFdW3dfgMjae2iLREqSwmrPf2CgK6aBOaHmzRcDDt4YSUvqSUshaFOYdQovbBItr4NKM5fENeTuRoNm92//W6YUM1aTSShnxGbqDOsu5E1oetHoaNYSbBdkZJL5YvYz6Fcx7mDKqu9KryruOhbRStyg83vfJSAwVrbdWgRxcL8HCvVH6ZGlN6x4wwaU8fflWS4Eu3qPardBVC4qxEBkdvEU5Prx/xudveEHnyRHFoHT/gcDskEHVKxRFCoITC4M2W9+6Z59rj1/DwmX8mm9KU4wEPf/MBdLtDYQtvt5O1yLSmlSmE0qyvWopAdSiLaFTgfW+dzXHCJ7EWowE7d8xx++2341zhU7bwGX51/5Lq+hMQRkhKV4ShrUxuLP+JRag5Lr6Y+R4PS+u8P3Fson2d1Qqv0VTuI7GHjZBp/P3VbVYRc6MkWTVYFJYS6USgsseBdbrRos7FNGj4UWXpD1S1xVmnLvFc0vDbJIrNobWXTG97g0wGVKYR0PEhKenDZqTYSlOPu1EqP+wzJduWV6ms04tbRIWipYOgWLfWGnXRMDSrbyjzbyJAxtgkrsxVHlkiTnbTelXYyjImmtmR1rfbeETF11ihbcAddzyTj37sE7TaM0hdcvniWQrrKFwZIGQPZ8aBHs56s4UoM40lj4wUbW96NhwPuPVxj+HaE8cZjQaV1DQtn+JiinnhZWnItK1uwLonkeG2pOG3vH0Z68IJ7/Mbtc4qU78rySXSYXO9MKliDGrodsKobmKtpJ7Qkx5uTek0FTM8rTSaziniinZUjWqqZteKLUbPESIjzSUUEiepPtDKBn+icZdKVF5X/uaQE3Wk22KeQLC4jLqG7WIXKuM3j7o3zAOsjSZqthGjlTJit3esrweVMbEhpqSmVJkoGzbWbOk9Gtwx4YVHhRnzzKc9mcP79zDOwVEwNjltJemozCNGwuFcUenTvZ7BbqW6hzgEZ3LaWtIfbXDbLTfQbrXp9zeQKqt6kErWK1TYfK6Rm1EzHXQFRPjBmt1GOt1UeaoQDuScp4gIZ4JkwdX2QZUoyW2J64tTbGNK787uaiqTD8MxldIxZVrEhe1tWnUFFaeDxckDui655Laf+2Q5Fg2x09eqy7KsYNPJ2GLC9VUmboWy2uGJnHUL8zIYsDkT5JEqicaqgQD/YYqGg3csn0gy/VKuF0nOhku00VsgX0HjtKivYRoOF03fJ1m57EnlBUDGNDea74vUlsOkIRAL773Ixxw7dpQXv+B5fPSjn2BjMKQcbTIqckwBTgpEpkMPFw4Ua/37CnW3NYZ2u01Ht2lLhRCGHe0pDl57Nf/hO7/dZ4YnepqtB50MZZqpeiRnRajffdnqT+Wtg95Y0jQPtroXMCbCtTpxxaQqkVMm9+StZIz1KVFCVWBQRC1TSlLtAF8Pa9PFPklXqg+sejq/XYMfHRcnRX2RHxgH0WJjo++0lgihUcqP3gnB70VZVr6mKRQo5ASXyHmor+ZsKYwd+wmz1qHUUxUmbxvhLZHVWyJkHcOQnmDe/FomPUftzi2FbJRqxhaY0pPf0tOlvgF12JiyYnemBsxxoqpUzc1J3cHj5k7Ta10yGfe2QVSqtDzPKQvD8tIq/c1NVlZXWVlZYWlpjSzTbA4GnDt/jvXNIeN8XNW1Smnm5uY4ePAA09MzzHQ77JiZZmqqR6vVYu++Pezdt7t2jkwERFpnVeRYg80gRZi71OWSL2V97p+jrDQ8acBlvQhdoGtYEBpbWoRQKJWF+r3AYSrtuArcPZ3IHqrBczLojZZOUsjKlM4/wyA1FrYqs+JhWTOrkyCixMLIWwjVqJ4NLjnx96VjgzohzVYNvNbaQ+Q1pBWmtJFq7YSPA1DKO5njsHFmIep6vQrcETLx1QqkNiTOBKq0NShVe+L6K9olyrTEAiehvaRcf49CJHMC15C21c4YqimuaTjTu5i5kdrTiNCYqcqxo55HxKgxqmm/b+RENYGO9BgX9OR1MI7/p91pc82xo8mkVm0zH7eBXRIdFePPUA3IGmxgBAc3y9RVeOI2a2L7Dqz0/XQ15DW144eoYdUm+2HyZ9aeVzJspCaK5yrWddO0w1TD1ohKRQdDFw4hRCyDBQaHcDKwtaMhtWgwNNKbrijL0BvLre48CZCS2uROeiCk/x2tSnWjdrORxuAqXDxmJcQ6bjuINrWvqYd22gvxbS2BjJJbhAuaY9cgIKacsHS2kk7OG3V0FcZoqtpfiq2OKs3/9uWGiaef9qWNp5i3fW6egCL3NbJSOpSPdV2fcqYipSHVuaQ4e6zZS1OQF+NmVncERp1FBccNF8JNYxpUHLx5R0TblN02MlBoEE6j3h/SiXbqNUbT+8pFDpzYMmFufq2sIOgoNqrNpSOK2GQYWGeT1+orkFiN+Mm1oZXpidM9UvRdZcc0aT7YAEiMqTQrk1oiGSqeyRFB2pPH35E+U6VU3CD14lYVUSvUoMbiDCgtt52Oq0A7sUXZ6BnS08ezJ1tAWclFa8zZVVywdHCU9g/b3QZxcU6iD1GROGmduvVD886FLdnypsshSaoqs6QAE6FHmxwayamfKANxUSW9dfAaL9x66CjDpDj8mbW+tJUCZ0PZSGh8Rd2rOSer2UFNf9n6POINKIXa1hCwXgBpFJloxGA0plNb3A1DqYbYNnEq/SzjxrQhkCaWemngDolNT/VMgrFWXY6nMzqRkBRNo+zfQjaM/WOINFDKl5B5njdCeeKtNNmH6wadQUTLFBpNrM++MDWDNtRzfsDtF5onfOmqgSMhglkTHA6dCGdgmhVYJ8DGaK74QusSx1UfetM2aJITRWMKup2p3SQYUVqD0hKdBXq28WGhUmgfOzwxRIoJU3HSXZPxonNGcxIbg0xjzxXLDOuoAY/wAUYQw0TNetggSIczqQGiuKJvckUAlboBP/uSRuNckbhequpzd04nWSb18zEVxyZF1Wp3Rv/eVejnIl8tRGqL+iDxC1dVP0OFHk4LiQ0bOd4CWO+Y6YKQSki9ddCsvNN9nud1ZRPjMaJJdbVeoweWF9q51CJIxGfhP88II8fno2MnHxsW/wtFdbJolQUSl9ky4EGIinefkhGVUtiJBy2Cc7gtrXcCdDROru3QoCYsWRsFpAOwdBGmm+dKVqoNL6qkrxOyBg9kyEuMskxjaoTGNXhrIoG6a+Rpks8ziek3GvrKsaNGZ3xfI6t62pN37bbWTJPvqZr0O9GYBcRyLfaI/nWrCi3yZ5LYot2RW+w6daDGpGhgjRoWJch4sycU99r/2CaeaLWrfQVNy/qWjkRWjy66bZnNIuSkRx+t6kBNeW7JuMH7GogKMKql5bIRwVCTFRtOIylnxTbq+foaNaFxrId1NuThVfCaqOncW4YxMiE0JXYRIjED2M6gLvXOihBxpGhs+3vEv80nT1WK1fOMnk8uDWaplY31ArcTv4sqFJJt+rEthwBNb6zJf2p9TFS+2cYpvr3Nf0IIDZJVXzbEibiuB7gxhEfYRPtuq3yPBmQ6sWGqm9gFr7GkzPVVRBOWn1SaXvnwco1nF8GYeEilpXY6G8l01lhL1bBQ1KBKw7k/kDPjposlWpQh1D2If83/B2GiJBTaVyMnAAAAAElFTkSuQmCC" style="width:140px;height:140px;object-fit:contain;" alt="Logo">
    </div>
    <div style="flex:1;text-align:center;padding-left:70px;padding-right:20px;">
      <div style="font-size:26px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#111;">EDINSON ACUÑA AYALA</div>
      <div style="font-size:13px;color:#333;margin-top:6px;">CC. / NIT 96.354.114</div>
      <div style="font-size:12px;color:#555;margin-top:4px;">NEIVA · 3137217967</div>
      <div style="font-size:12px;color:#555;margin-top:2px;">E – mail: edac77@gmail.com</div>
    </div>
    <div style="width:140px;flex-shrink:0;text-align:right;">
      <div style="font-size:18px;font-weight:700;color:#1a56a0;">${cot.id}</div>
      <div style="font-size:12px;color:#555;margin-top:4px;">Fecha: ${cot.fecha}</div>
      ${cot.valida_hasta?`<div style="font-size:12px;color:#555;">Válida hasta: ${cot.valida_hasta}</div>`:''}
    </div>
  </div>

  <table style="margin-bottom:16px;border:none;">
    <tr><td style="border:none;padding:4px 0;font-size:13px;"><strong>Cliente:</strong> ${cliente?.empresa||'—'}</td>
        <td style="border:none;padding:4px 0;font-size:13px;"><strong>Contacto:</strong> ${cliente?.contacto||'—'}</td></tr>
    <tr><td style="border:none;padding:4px 0;font-size:13px;"><strong>Teléfono:</strong> ${cliente?.telefono||'—'}</td>
        <td style="border:none;padding:4px 0;font-size:13px;"><strong>Máquina:</strong> ${maquina?`${maquina.nombre} — ${maquina.marca||''}`:'—'}</td></tr>
  </table>

  ${cot.descripcion?`<p style="font-size:13px;"><strong>Descripción:</strong> ${cot.descripcion}</p>`:''}

  ${items.visita?`
  <p style="font-weight:700;font-size:14px;margin:16px 0 6px;">🔍 Visita Técnica</p>
  <table><thead><tr><th>Descripción</th><th style="width:150px;text-align:right;">Valor</th></tr></thead>
  <tbody><tr><td>${items.visita.desc}</td><td style="text-align:right;">${fmt$(items.visita.valor)}</td></tr></tbody></table>`:''}

  ${items.mano?.length?`
  <p style="font-weight:700;font-size:14px;margin:16px 0 6px;">👷 Mano de Obra</p>
  <table><thead><tr><th>Descripción</th><th style="width:150px;text-align:right;">Valor</th></tr></thead>
  <tbody>${items.mano.map(m=>`<tr><td>${m.desc}</td><td style="text-align:right;">${fmt$(m.valor||m.valor_hora||0)}</td></tr>`).join('')}</tbody></table>`:''}

  ${items.repuestos?.length?`
  <p style="font-weight:700;font-size:14px;margin:16px 0 6px;">🔧 Repuestos / Materiales</p>
  <table><thead><tr><th>Descripción</th><th style="width:60px;text-align:right;">Cant.</th><th style="width:120px;text-align:right;">Precio unit.</th><th style="width:120px;text-align:right;">Subtotal</th></tr></thead>
  <tbody>${items.repuestos.map(r=>`<tr><td>${r.desc}</td><td style="text-align:right;">${r.cant}</td><td style="text-align:right;">${fmt$(r.precio)}</td><td style="text-align:right;">${fmt$(r.cant*r.precio)}</td></tr>`).join('')}</tbody></table>`:''}

  <div class="total-box">
    ${items.visita?`<div class="total-row"><span>Visita técnica</span><span>${fmt$(items.visita.valor)}</span></div>`:''}
    ${items.mano?.length?`<div class="total-row"><span>Mano de obra</span><span>${fmt$(items.mano.reduce((s,m)=>s+(m.valor||m.valor_hora||0),0))}</span></div>`:''}
    ${items.repuestos?.length?`<div class="total-row"><span>Repuestos/materiales</span><span>${fmt$(items.repuestos.reduce((s,r)=>s+(r.cant*r.precio),0))}</span></div>`:''}
    <div class="total-row total-final"><span>TOTAL</span><span>${fmt$(cot.total)}</span></div>
  </div>

  ${cot.observaciones?`<div class="obs"><strong>Observaciones:</strong> ${cot.observaciones}</div>`:''}

  <div class="firma">
    <div style="text-align:center;">
      <div style="width:200px;border-top:1px solid #333;padding-top:6px;font-size:12px;">Firma del cliente</div>
    </div>
    <div style="text-align:center;">
      <div style="width:200px;border-top:1px solid #333;padding-top:6px;font-size:12px;">Firma del técnico</div>
    </div>
  </div>

  <div style="text-align:center;margin-top:30px;">
    <button onclick="window.print()" style="padding:8px 20px;background:#333;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:14px;">🖨️ Imprimir / Guardar PDF</button>
  </div>
  </body></html>`);
  win.document.close();
}

// ── HOJA DE VIDA DE MÁQUINA ───────────────────────────────────
function hdvInit(){
  const sel = document.getElementById('hdv-cliente');
  sel.innerHTML = '<option value="">— Seleccionar cliente —</option>';
  (cache.clientes||[]).forEach(c=>{
    const o = document.createElement('option');
    o.value = c.id; o.textContent = c.empresa;
    sel.appendChild(o);
  });
  document.getElementById('hdv-maquina').innerHTML = '<option value="">— Seleccionar máquina —</option>';
  document.getElementById('hdv-contenido').innerHTML = '<div class="hdv-empty">👆 Selecciona un cliente y una máquina para ver su hoja de vida</div>';
}
function hdvLoadMaquinas(){
  const cid = document.getElementById('hdv-cliente').value;
  const sel = document.getElementById('hdv-maquina');
  sel.innerHTML = '<option value="">— Seleccionar máquina —</option>';
  document.getElementById('hdv-contenido').innerHTML = '<div class="hdv-empty">👆 Selecciona una máquina para ver su hoja de vida</div>';
  if(!cid) return;
  const maquinas = (cache.maquinaria||[]).filter(m=>String(m.cliente_id)===String(cid));
  maquinas.forEach(m=>{
    const o = document.createElement('option');
    o.value = m.id; o.textContent = `${m.nombre} — ${m.marca||''}`;
    sel.appendChild(o);
  });
  if(maquinas.length===1){ sel.value=maquinas[0].id; hdvCargar(); }
}
async function hdvCargar(){
  const mid = document.getElementById('hdv-maquina').value;
  if(!mid) return;
  const contenido = document.getElementById('hdv-contenido');
  contenido.innerHTML = '<div class="hdv-empty">⏳ Cargando historial...</div>';

  const maq = (cache.maquinaria||[]).find(m=>String(m.id)===String(mid));
  if(!maq){ contenido.innerHTML='<div class="hdv-empty">Máquina no encontrada</div>'; return; }
  const cliente = (cache.clientes||[]).find(c=>String(c.id)===String(maq.cliente_id));

  // Cargar órdenes y mantenimientos en paralelo (fotos ya están en cache.maquinaria)
  const [ordenes, mantenimientos] = await Promise.all([
    apiFetch('ordenes.php'),
    apiFetch('mantenimientos.php'),
  ]);

  const ordMaq = (ordenes||[]).filter(o=>String(o.maquina_id)===String(mid))
    .sort((a,b)=>new Date(b.fecha)-new Date(a.fecha));
  const mantMaq = (mantenimientos||[]).filter(m=>String(m.maquina_id)===String(mid))
    .sort((a,b)=>new Date(b.fecha)-new Date(a.fecha));

  const primeraFoto = maq.fotos?.[0] || null;
  const estadoColor = maq.estado==='Operativo'?'var(--success)':maq.estado==='En Reparación'?'var(--accent)':'var(--danger)';

  // Calcular stats
  const totalOrdenes = ordMaq.length;
  const totalMant = mantMaq.filter(m=>m.estado==='Completado').length;
  const ultimaOrden = ordMaq[0]?.fecha ? new Date(ordMaq[0].fecha).toLocaleDateString('es-CO') : 'Sin órdenes';

  contenido.innerHTML = `
    <!-- Encabezado solo para impresión -->
    <div class="print-header" style="text-align:center;margin-bottom:20px;padding-bottom:12px;border-bottom:2px solid #333;">
      <div style="font-size:20px;font-weight:700;">EDINSON ACUÑA AYALA</div>
      <div style="font-size:13px;color:#555;">Servicio Técnico Industrial · Tel: 3137217967 · Neiva, Huila</div>
      <div style="font-size:16px;font-weight:700;margin-top:8px;">HOJA DE VIDA DE MÁQUINA</div>
      <div style="font-size:12px;color:#555;">Generado: ${new Date().toLocaleDateString('es-CO',{year:'numeric',month:'long',day:'numeric'})}</div>
    </div>

    <!-- Header máquina -->
    <div class="hdv-header">
      <div>
        ${primeraFoto
          ? `<img src="${primeraFoto}" class="hdv-foto" alt="foto">`
          : `<div class="hdv-foto-placeholder">⚙️</div>`}
      </div>
      <div>
        <div style="font-size:22px;font-weight:700;font-family:'Rajdhani',sans-serif;">${maq.nombre}</div>
        <div style="font-size:13px;color:var(--text3);margin-bottom:10px;">
          ${cliente?.empresa||'Sin cliente'} &nbsp;·&nbsp;
          <span style="color:${estadoColor};font-weight:600;">${maq.estado}</span>
        </div>
        <div class="hdv-info-grid">
          <div class="hdv-info-item"><div class="hdv-info-label">Marca</div><div class="hdv-info-val">${maq.marca||'—'}</div></div>
          <div class="hdv-info-item"><div class="hdv-info-label">Modelo</div><div class="hdv-info-val">${maq.modelo||'—'}</div></div>
          <div class="hdv-info-item"><div class="hdv-info-label">Serie</div><div class="hdv-info-val">${maq.serie||'—'}</div></div>
          <div class="hdv-info-item"><div class="hdv-info-label">Año</div><div class="hdv-info-val">${maq.anio||'—'}</div></div>
          <div class="hdv-info-item"><div class="hdv-info-label">Tipo</div><div class="hdv-info-val">${maq.tipo||'—'}</div></div>
          <div class="hdv-info-item"><div class="hdv-info-label">Potencia</div><div class="hdv-info-val">${maq.potencia||'—'}</div></div>
          <div class="hdv-info-item"><div class="hdv-info-label">Voltaje</div><div class="hdv-info-val">${maq.voltaje||'—'}</div></div>
          <div class="hdv-info-item"><div class="hdv-info-label">Ubicación</div><div class="hdv-info-val">${maq.ubicacion||'—'}</div></div>
          <div class="hdv-info-item"><div class="hdv-info-label">Horas de uso</div><div class="hdv-info-val">${maq.horas_uso||'—'}</div></div>
        </div>
      </div>
      <div class="hdv-stats">
        <div class="hdv-stat-box">
          <div class="hdv-stat-num">${totalOrdenes}</div>
          <div class="hdv-stat-lbl">Órdenes</div>
        </div>
        <div class="hdv-stat-box">
          <div class="hdv-stat-num" style="color:var(--success);">${totalMant}</div>
          <div class="hdv-stat-lbl">Mantenimientos</div>
        </div>
        <div class="hdv-stat-box">
          <div class="hdv-stat-num" style="color:var(--text2);font-size:13px;">${ultimaOrden}</div>
          <div class="hdv-stat-lbl">Último servicio</div>
        </div>
      </div>
    </div>

    <!-- Timeline órdenes -->
    <div class="hdv-section-title">📋 Historial de Órdenes de Servicio</div>
    ${ordMaq.length ? `
    <div class="hdv-timeline">
      ${ordMaq.map(o=>{
        const stColor = o.estado==='Entregado'?'var(--success)':o.estado==='En Proceso'?'var(--accent)':'var(--text3)';
        return `<div class="hdv-tl-item">
          <div class="hdv-tl-dot" style="background:${stColor};"></div>
          <div class="hdv-tl-card">
            <div class="hdv-tl-fecha">${new Date(o.fecha+'T00:00:00').toLocaleDateString('es-CO',{year:'numeric',month:'long',day:'numeric'})} &nbsp;·&nbsp; <strong>${o.id}</strong> &nbsp;·&nbsp; <span style="color:${stColor};font-weight:600;">${o.estado}</span></div>
            <div class="hdv-tl-titulo">${o.tipo}</div>
            ${o.falla?`<div style="margin-top:6px;"><span style="font-size:10px;text-transform:uppercase;color:var(--text3);font-weight:600;">Falla reportada:</span><div class="hdv-tl-detalle">${o.falla}</div></div>`:''}
            ${o.diagnostico?`<div style="margin-top:4px;"><span style="font-size:10px;text-transform:uppercase;color:var(--text3);font-weight:600;">Diagnóstico:</span><div class="hdv-tl-detalle">${o.diagnostico}</div></div>`:''}
            ${o.trabajos?`<div style="margin-top:4px;"><span style="font-size:10px;text-transform:uppercase;color:var(--text3);font-weight:600;">Trabajos realizados:</span><div class="hdv-tl-detalle">${o.trabajos}</div></div>`:''}
            ${o.repuestos?.length?`<div style="margin-top:6px;"><span style="font-size:10px;text-transform:uppercase;color:var(--text3);font-weight:600;">Repuestos utilizados:</span><div style="margin-top:3px;">${o.repuestos.map(r=>`<span class="hdv-rep-tag">🔧 ${r.descripcion} x${r.cantidad}</span>`).join('')}</div></div>`:''}
            <div style="margin-top:6px;display:flex;gap:12px;flex-wrap:wrap;">
              ${o.tecnico_nombre?`<span style="font-size:11px;color:var(--text3);">👷 ${o.tecnico_nombre}</span>`:''}
            </div>
            <div style="margin-top:8px;">
              <button class="media-btn" onclick="mediaSubirClick('orden','${o.id}','media-ord-${o.id}')">📎 Agregar fotos/videos</button>
              <div id="media-ord-${o.id}" style="margin-top:6px;"></div>
            </div>
          </div>
        </div>`;
      }).join('')}
    </div>` : '<div class="hdv-empty" style="padding:20px;">Sin órdenes de servicio registradas</div>'}

    <!-- Timeline mantenimientos - solo pantalla, no imprime -->
    <div class="hdv-mant-section">
    <div class="hdv-section-title no-print">🗓️ Historial de Mantenimientos</div>
    ${mantMaq.length ? `
    <div class="hdv-timeline">
      ${mantMaq.map(m=>{
        const ejecutado = m.estado==='Completado';
        return `<div class="hdv-tl-item">
          <div class="hdv-tl-dot" style="background:${ejecutado?'var(--success)':'var(--accent)'};"></div>
          <div class="hdv-tl-card">
            <div class="hdv-tl-fecha">${new Date(m.fecha+'T00:00:00').toLocaleDateString('es-CO',{year:'numeric',month:'long',day:'numeric'})} &nbsp;·&nbsp; <span style="color:${ejecutado?'var(--success)':'var(--accent)'};">${ejecutado?'✅ Completado':'⏳ Programado'}</span></div>
            <div class="hdv-tl-titulo">${m.tipo}</div>
            <div class="hdv-tl-detalle">${m.descripcion||''}</div>
            ${m.tecnico_nombre?`<div style="font-size:11px;color:var(--text3);margin-top:4px;">👷 ${m.tecnico_nombre}</div>`:''}
          </div>
        </div>`;
      }).join('')}
    </div>` : '<div class="hdv-empty" style="padding:20px;">Sin mantenimientos registrados</div>'}
    </div>

    ${maq.notas?`
    <div class="hdv-section-title">📝 Notas Técnicas</div>
    <div class="hdv-notas-section" style="background:var(--bg2);border:1px solid var(--border);border-radius:8px;padding:16px;font-size:13px;color:var(--text2);">${maq.notas}</div>
    `:''}
  `;

  // Cargar galerías de media para cada orden
  ordMaq.forEach(o => mediaCargar('orden', o.id, `media-ord-${o.id}`));
}

// ── REPORTES ──────────────────────────────────────────────────
async function loadReportes(){
  const [ordenes, mantenimientos] = await Promise.all([
    apiFetch('ordenes.php'),
    apiFetch('mantenimientos.php?action=historial')
  ]);
  if(!ordenes) return;
  cache.ordenes = ordenes;

  // Filtrar por período
  const dias = parseInt(document.getElementById('rep-filtro-periodo')?.value||30);
  const desde = dias > 0 ? new Date(Date.now() - dias*24*60*60*1000) : new Date(0);
  const filtradas = ordenes.filter(o => new Date(o.fecha+'T00:00:00') >= desde);
  const mantFiltrados = (mantenimientos||[]).filter(m => new Date((m.fecha_ejecucion||'')+'T00:00:00') >= desde);

  // KPIs
  const entregadas  = filtradas.filter(o=>o.estado==='Entregado').length;
  const clientesSet = new Set(filtradas.map(o=>o.cliente_id||o.cliente_nombre));
  document.getElementById('rep-total-ordenes').textContent      = filtradas.length;
  document.getElementById('rep-entregadas').textContent         = entregadas;
  document.getElementById('rep-clientes-atendidos').textContent = clientesSet.size;
  document.getElementById('rep-mant-ejecutados').textContent    = mantFiltrados.length;

  // Órdenes por tipo — barras
  const porTipo = filtradas.reduce((acc,o)=>{ acc[o.tipo]=(acc[o.tipo]||0)+1; return acc; },{});
  const maxTipo = Math.max(...Object.values(porTipo),1);
  document.getElementById('rep-por-tipo').innerHTML = Object.entries(porTipo).length
    ? Object.entries(porTipo).sort((a,b)=>b[1]-a[1]).map(([tipo,n])=>`
        <div style="margin-bottom:12px;">
          <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
            <span style="color:var(--text)">${tipo}</span><strong style="color:var(--accent)">${n}</strong>
          </div>
          <div style="background:var(--bg3);border-radius:4px;height:8px;">
            <div style="background:var(--accent);height:100%;width:${Math.round(n/maxTipo*100)}%;border-radius:4px;"></div>
          </div>
        </div>`).join('')
    : '<div style="color:var(--text3);font-size:13px;text-align:center;padding:20px 0;">Sin datos en el período</div>';

  // Trabajos por técnico — barras
  const porTec = filtradas.reduce((acc,o)=>{ const t=o.tecnico_nombre?.trim()||'Sin asignar'; acc[t]=(acc[t]||0)+1; return acc; },{});
  const maxTec = Math.max(...Object.values(porTec),1);
  document.getElementById('rep-por-tecnico').innerHTML = Object.entries(porTec).length
    ? Object.entries(porTec).sort((a,b)=>b[1]-a[1]).map(([tec,n])=>`
        <div style="margin-bottom:12px;">
          <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
            <span style="color:var(--text)">👷 ${tec}</span><strong style="color:var(--accent2)">${n}</strong>
          </div>
          <div style="background:var(--bg3);border-radius:4px;height:8px;">
            <div style="background:var(--accent2);height:100%;width:${Math.round(n/maxTec*100)}%;border-radius:4px;"></div>
          </div>
        </div>`).join('')
    : '<div style="color:var(--text3);font-size:13px;text-align:center;padding:20px 0;">Sin datos en el período</div>';

  // Tabla historial completo
  const stMap={'Pendiente':'amber','En Proceso':'blue','Completado':'green','Entregado':'gray'};
  const tb = document.getElementById('tb-rep-ordenes');
  tb.innerHTML = filtradas.length
    ? filtradas.map(o=>`<tr>
        <td class="id-code" style="color:var(--accent);font-weight:700;">${o.id}</td>
        <td>${o.fecha||'-'}</td>
        <td><strong>${o.cliente_nombre||'-'}</strong></td>
        <td>${o.maquina_nombre||'-'}</td>
        <td><span style="font-size:11px;background:var(--bg3);padding:2px 8px;border-radius:4px;">${o.tipo}</span></td>
        <td>${o.tecnico_nombre?.trim()||'-'}</td>
        <td><span class="tag tag-${stMap[o.estado]||'gray'}">${o.estado}</span></td>
        <td><button class="btn btn-blue btn-sm" onclick="verReporte('${o.id}')">📄 Reporte</button></td>
      </tr>`).join('')
    : '<tr><td colspan="8"><div class="empty-state"><div class="empty-icon">📋</div><p>Sin órdenes en este período</p></div></td></tr>';
}
function verReporte(id){
  const o=cache.ordenes.find(x=>x.id===id); if(!o) return;
  const repRows=(o.repuestos||[]).map(r=>`<tr><td>${r.repuesto_id||'-'}</td><td>${r.descripcion||'-'}</td><td style="text-align:center;">${r.cantidad}</td></tr>`).join('');
  document.getElementById('report-content').innerHTML=`
  <div class="report-preview" id="printable-report">
    <!-- Encabezado igual a cuenta de cobro y cotización -->
    <div style="display:flex;align-items:center;gap:0;border-bottom:3px solid #1a56a0;padding-bottom:20px;margin-bottom:28px;">
      <div style="flex-shrink:0;width:140px;">
        <img src="data:image/png;base64,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" style="width:140px;height:140px;object-fit:contain;" alt="Logo">
      </div>
      <div style="flex:1;text-align:center;padding-left:70px;padding-right:20px;">
        <div style="font-size:20px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#111;white-space:nowrap;">EDINSON ACUÑA AYALA</div>
        <div style="font-size:13px;color:#333;margin-top:6px;">CC. / NIT 96.354.114</div>
        <div style="font-size:12px;color:#555;margin-top:4px;">NEIVA · 3137217967</div>
        <div style="font-size:12px;color:#555;margin-top:2px;">E – mail: edac77@gmail.com</div>
      </div>
      <div style="width:140px;flex-shrink:0;text-align:right;">
        <div style="font-size:14px;font-weight:700;color:#1a56a0;">ORDEN DE TRABAJO</div>
        <div style="font-size:18px;font-weight:700;color:#1a56a0;">${o.id}</div>
        <div style="font-size:12px;color:#555;margin-top:4px;">Fecha: ${o.fecha||'-'}</div>
        <div style="font-size:12px;color:#555;">Estado: <strong>${o.estado}</strong></div>
      </div>
    </div>
    <div class="report-section"><div class="report-section-title">Datos del Cliente</div>
      <div class="report-grid">
        <div class="report-field"><span>Empresa</span><strong>${o.cliente_nombre||'-'}</strong></div>
        <div class="report-field"><span>Contacto</span>${o.cliente_contacto||'-'}</div>
      </div>
    </div>
    <div class="report-section"><div class="report-section-title">Datos del Equipo</div>
      <div class="report-grid">
        <div class="report-field"><span>Equipo</span><strong>${o.maquina_nombre||'-'}</strong></div>
        <div class="report-field"><span>Marca</span>${o.maquina_marca||'-'}</div>
      </div>
    </div>
    <div class="report-section"><div class="report-section-title">Descripción del Servicio</div>
      <div class="report-grid">
        <div class="report-field"><span>Tipo</span><strong>${o.tipo}</strong></div>
        <div class="report-field"><span>Técnico</span>${o.tecnico_nombre?.trim()||'-'}</div>
        <div class="report-field"><span>Fecha Ingreso</span>${o.fecha||'-'}</div>
        <div class="report-field"><span>Fecha Entrega</span>${o.fecha_entrega||'-'}</div>
      </div>
      ${o.falla?`<div style="margin-top:12px;"><div style="font-size:11px;color:#666;font-weight:600;margin-bottom:4px;">FALLA REPORTADA</div><div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:4px;padding:10px;font-size:13px;">${o.falla}</div></div>`:''}
      ${o.diagnostico?`<div style="margin-top:8px;"><div style="font-size:11px;color:#666;font-weight:600;margin-bottom:4px;">DIAGNÓSTICO TÉCNICO</div><div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:4px;padding:10px;font-size:13px;">${o.diagnostico}</div></div>`:''}
      ${o.trabajos?`<div style="margin-top:8px;"><div style="font-size:11px;color:#666;font-weight:600;margin-bottom:4px;">TRABAJOS REALIZADOS</div><div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:4px;padding:10px;font-size:13px;">${o.trabajos}</div></div>`:''}
    </div>
    ${repRows?`<div class="report-section"><div class="report-section-title">Repuestos Utilizados</div><table class="report-table"><thead><tr><th>Código</th><th>Descripción</th><th>Cant.</th></tr></thead><tbody>${repRows}</tbody></table></div>`:''}
    ${o.observaciones?`<div class="report-section"><div class="report-section-title">Observaciones y Recomendaciones</div><div style="background:#fffbeb;border:1px solid #fde68a;border-radius:4px;padding:12px;font-size:13px;">${o.observaciones}</div></div>`:''}
    <div class="sign-area"><div><div class="sign-line">Firma del Técnico</div></div><div><div class="sign-line">Firma del Cliente</div></div></div>
    <div class="report-footer"><span>TallerPRO — Sistema de Gestión de Taller Industrial</span><span>${new Date().toLocaleDateString('es-CL')}</span></div>
  </div>`;
  document.getElementById('report-container').style.display='block';
  showPage('reportes');
  setTimeout(()=>document.getElementById('report-container').scrollIntoView({behavior:'smooth'}),100);
}

// ── FACTURACIÓN ───────────────────────────────────────────────
let facItems = [];
let currentFacId = null;
const fmt$ = v => '$' + Number(v||0).toLocaleString('es-CL', {minimumFractionDigits:0, maximumFractionDigits:0});

async function loadFacturas(filtroEstado='') {
  const data = await apiFetch('facturas.php'); if(!data) return;
  cache.facturas = data;
  const filtered = filtroEstado ? data.filter(f=>f.estado===filtroEstado) : data;
  // KPIs
  const total   = data.reduce((s,f)=>s+parseFloat(f.total||0),0);
  const pagado  = data.reduce((s,f)=>s+parseFloat(f.total_pagado||0),0);
  const pend    = data.reduce((s,f)=>s+parseFloat(f.saldo_pendiente||0),0);
  const activas = data.filter(f=>!['Pagada','Anulada'].includes(f.estado)).length;
  document.getElementById('fkpi-total').textContent    = fmt$(total);
  document.getElementById('fkpi-pagado').textContent   = fmt$(pagado);
  document.getElementById('fkpi-pendiente').textContent= fmt$(pend);
  document.getElementById('fkpi-count').textContent    = activas;
  const estMap={'Borrador':'gray','Emitida':'blue','Parcialmente Pagada':'amber','Pagada':'green','Vencida':'red','Anulada':'gray'};
  const tb=document.getElementById('tb-facturas');
  tb.innerHTML=filtered.length?filtered.map(f=>`<tr>
    <td class="id-code" style="color:var(--accent);font-weight:700;">${f.numero}</td>
    <td>${f.fecha||'-'}</td>
    <td style="color:${f.fecha_vencimiento&&new Date(f.fecha_vencimiento)<new Date()?'var(--danger)':'var(--text)'}">${f.fecha_vencimiento||'-'}</td>
    <td><strong>${f.cliente||'-'}</strong><br><span style="font-size:11px;color:var(--text3);">${f.cliente_rut||''}</span></td>
    <td style="font-weight:600;">${fmt$(f.total)}</td>
    <td style="color:var(--success);">${fmt$(f.total_pagado)}</td>
    <td style="color:${parseFloat(f.saldo_pendiente)>0?'var(--danger)':'var(--success)'};font-weight:600">${fmt$(f.saldo_pendiente)}</td>
    <td><span class="tag tag-${estMap[f.estado]||'gray'}">${f.estado}</span></td>
    <td class="actions">
      <button class="btn btn-blue btn-sm" onclick="verFacturaDetalle('${f.id}')">👁 Ver</button>
      ${tienePermiso('pagos','crear')?`<button class="btn btn-green btn-sm" onclick="currentFacId='${f.id}';abrirPago()">💰</button>`:''}
      ${tienePermiso('facturas','editar')?`<button class="btn btn-secondary btn-sm" onclick="editarFactura('${f.id}')">✏️</button>`:''}
      ${tienePermiso('facturas','eliminar')?`<button class="btn btn-danger btn-sm" onclick="deleteFactura('${f.id}')">🗑</button>`:''}
    </td></tr>`).join(''):'<tr><td colspan="9"><div class="empty-state"><div class="empty-icon">🧾</div><p>No hay facturas registradas</p></div></td></tr>';
}

// ── DATOS FIJOS DEL PRESTADOR ─────────────────────────────────
const TALLER_DEFAULTS = {
  nombre:         'EDINSON ACUÑA AYALA',
  rut:            '96.354.114',
  telefono:       '3137217967',
  titular_cuenta: 'edac77@gmail.com',  // email guardado en titular
  ciudad:         '',
  banco:          '',
  tipo_cuenta:    '',
  num_cuenta:     ''
};

// ── IMPORTAR ORDEN A FACTURA ──────────────────────────────────
let _ordenesImportCache = [];
async function abrirImportarOrden(){
  // Si el cache está vacío, cargar desde la API
  if(!cache.ordenes || cache.ordenes.length === 0){
    const data = await apiFetch('ordenes.php');
    if(data) cache.ordenes = data;
  }
  _ordenesImportCache = cache.ordenes || [];
  renderOrdenesImport(_ordenesImportCache);
  openModal('modal-importar-orden');
}
function filtrarOrdenesImport(q){
  const f = q.toLowerCase();
  renderOrdenesImport(_ordenesImportCache.filter(o=>
    (o.id||'').toLowerCase().includes(f) ||
    (o.cliente_nombre||'').toLowerCase().includes(f) ||
    (o.maquina_nombre||'').toLowerCase().includes(f) ||
    (o.tipo||'').toLowerCase().includes(f)
  ));
}
function renderOrdenesImport(lista){
  const stMap={'Pendiente':'amber','En Proceso':'blue','Completado':'green','Entregado':'gray'};
  const cont = document.getElementById('lista-ordenes-import');
  cont.innerHTML = lista.length ? lista.map(o=>{
    const repsCount = (o.repuestos||[]).length;
    const manoObra  = parseFloat(o.mano_obra||0);
    const total     = parseFloat(o.total||0);
    return `<div style="background:var(--bg2);border:1px solid var(--border);border-radius:8px;padding:14px 16px;cursor:pointer;transition:border-color .2s;"
      onmouseenter="this.style.borderColor='var(--accent)'" onmouseleave="this.style.borderColor='var(--border)'"
      onclick="importarOrden('${o.id}')">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
        <span style="font-family:'IBM Plex Mono',monospace;font-weight:700;color:var(--accent);font-size:14px;">${o.id}</span>
        <span class="tag tag-${stMap[o.estado]||'gray'}">${o.estado}</span>
      </div>
      <div style="font-size:13px;color:var(--text);margin-bottom:4px;"><strong>${o.cliente_nombre||'-'}</strong> · ${o.maquina_nombre||'-'}</div>
      <div style="font-size:12px;color:var(--text2);">${o.tipo} · ${o.fecha||''}</div>
      <div style="display:flex;gap:16px;margin-top:8px;font-size:12px;color:var(--text3);">
        <span>🔩 ${repsCount} repuesto${repsCount!==1?'s':''}</span>
        <span>🔧 Mano de obra: <strong style="color:var(--text2);">${fmt$(manoObra)}</strong></span>
        <span>💰 Total: <strong style="color:var(--accent);">${fmt$(total)}</strong></span>
      </div>
    </div>`;
  }).join('') : '<div style="text-align:center;padding:30px;color:var(--text3);">No se encontraron órdenes</div>';
}
function importarOrden(ordenId){
  const o = _ordenesImportCache.find(x=>x.id===ordenId); if(!o) return;
  // Limpiar
  facItems=[]; document.getElementById('fac-items-list').innerHTML='';

  // Función auxiliar: agrega un ítem con datos completos directamente
  function agregarItemFactura(tipo, descripcion, cantidad, precio_unit){
    // ID único con contador para evitar colisiones si Date.now() repite
    const id = 'fi_' + Date.now() + '_' + Math.random().toString(36).slice(2,6);
    facItems.push({id, tipo, descripcion, cantidad, precio_unit});
    const sub = cantidad * precio_unit;
    const div = document.createElement('div');
    div.id = id;
    div.style.cssText = 'display:grid;grid-template-columns:100px 1fr 80px 110px 90px 32px;gap:6px;margin-bottom:6px;align-items:center;';
    div.innerHTML = `
      <select style="background:var(--bg3);border:1px solid var(--border);border-radius:5px;padding:7px 6px;color:var(--text);font-size:11px;outline:none;" onchange="updateFacItem('${id}','tipo',this.value)">
        <option ${tipo==='Servicio'?'selected':''}>Servicio</option>
        <option ${tipo==='Repuesto'?'selected':''}>Repuesto</option>
        <option ${tipo==='Mano de Obra'?'selected':''}>Mano de Obra</option>
        <option ${tipo==='Otro'?'selected':''}>Otro</option>
      </select>
      <input value="${descripcion.replace(/"/g,'&quot;')}" placeholder="Descripción del ítem..." style="background:var(--bg3);border:1px solid var(--border);border-radius:5px;padding:7px 10px;color:var(--text);font-size:12px;outline:none;" oninput="updateFacItem('${id}','descripcion',this.value)">
      <input type="number" value="${cantidad}" min="0.01" step="0.01" style="background:var(--bg3);border:1px solid var(--border);border-radius:5px;padding:7px 8px;color:var(--text);font-size:12px;outline:none;text-align:right;" oninput="updateFacItem('${id}','cantidad',this.value)">
      <input type="number" value="${precio_unit}" min="0" step="0.01" style="background:var(--bg3);border:1px solid var(--border);border-radius:5px;padding:7px 8px;color:var(--text);font-size:12px;outline:none;text-align:right;" oninput="updateFacItem('${id}','precio_unit',this.value)">
      <div id="st_${id}" style="padding:7px 8px;font-size:12px;font-family:'IBM Plex Mono',monospace;color:var(--accent);text-align:right;font-weight:600;">${fmt$(sub)}</div>
      <button onclick="removeFacItem('${id}')" style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:16px;padding:4px;">✕</button>`;
    document.getElementById('fac-items-list').appendChild(div);
  }

  // Importar cada repuesto
  (o.repuestos||[]).forEach(r => {
    const desc    = r.descripcion || r.repuesto_id || '';
    const cant    = parseFloat(r.cantidad)   || 1;
    const precio  = parseFloat(r.precio_unit)|| 0;
    agregarItemFactura('Repuesto', desc, cant, precio);
  });

  // Importar mano de obra si existe
  if(parseFloat(o.mano_obra||0) > 0){
    agregarItemFactura('Mano de Obra', `Mano de obra – ${o.tipo}`, 1, parseFloat(o.mano_obra));
  }

  // Autocompletar cliente y concepto
  if(o.cliente_id) setVal('fac-cliente_id', o.cliente_id);
  if(!val('fac-concepto')) setVal('fac-concepto', `${o.tipo} – ${o.maquina_nombre||''} (${o.id})`);

  actualizarTotalesFactura();
  closeModal('modal-importar-orden');
  const nRep = (o.repuestos||[]).length;
  showToast(`✅ Importados ${nRep} repuesto${nRep!==1?'s':''} + mano de obra desde ${ordenId}`);
}

function abrirNuevaFactura() {
  document.getElementById('fac-id').value='';
  document.getElementById('fac-modal-title').textContent='Nueva Cuenta de Cobro';
  document.getElementById('fac-fecha').value=new Date().toISOString().split('T')[0];
  setVal('fac-numero',''); setVal('fac-concepto',''); setVal('fac-notas','');
  setVal('fac-estado','Emitida'); setVal('fac-fecha_vencimiento',''); setVal('fac-cliente_id','');
  // Datos fijos del prestador
  setVal('fac-taller-nombre', TALLER_DEFAULTS.nombre);
  setVal('fac-taller-rut',    TALLER_DEFAULTS.rut);
  setVal('fac-taller-telefono', TALLER_DEFAULTS.telefono);
  setVal('fac-titular-cuenta',  TALLER_DEFAULTS.titular_cuenta);
  // Datos opcionales (banco, ciudad) — el usuario los puede completar
  setVal('fac-taller-ciudad',''); setVal('fac-banco','');
  setVal('fac-tipo-cuenta',''); setVal('fac-num-cuenta','');
  facItems=[];
  document.getElementById('fac-items-list').innerHTML='';
  actualizarTotalesFactura();
  populateSelect('fac-cliente_id', cache.clientes, 'id', 'empresa', 'Seleccionar...');
  const n = (cache.facturas||[]).length+1;
  document.getElementById('fac-numero').value = 'CC-'+new Date().getFullYear()+'-'+String(n).padStart(4,'0');
  openModal('modal-factura');
}

function addItem(tipo='Servicio') {
  const id='fi_'+Date.now();
  facItems.push({id, tipo, descripcion:'', cantidad:1, precio_unit:0});
  const div=document.createElement('div');
  div.id=id;
  div.style.cssText='display:grid;grid-template-columns:100px 1fr 80px 110px 90px 32px;gap:6px;margin-bottom:6px;align-items:center;';
  div.innerHTML=`
    <select style="background:var(--bg3);border:1px solid var(--border);border-radius:5px;padding:7px 6px;color:var(--text);font-size:11px;outline:none;" onchange="updateFacItem('${id}','tipo',this.value)">
      <option ${tipo==='Servicio'?'selected':''}>Servicio</option>
      <option ${tipo==='Repuesto'?'selected':''}>Repuesto</option>
      <option ${tipo==='Mano de Obra'?'selected':''}>Mano de Obra</option>
      <option ${tipo==='Otro'?'selected':''}>Otro</option>
    </select>
    <input placeholder="Descripción del ítem..." style="background:var(--bg3);border:1px solid var(--border);border-radius:5px;padding:7px 10px;color:var(--text);font-size:12px;outline:none;" oninput="updateFacItem('${id}','descripcion',this.value)">
    <input type="number" value="1" min="0.01" step="0.01" style="background:var(--bg3);border:1px solid var(--border);border-radius:5px;padding:7px 8px;color:var(--text);font-size:12px;outline:none;text-align:right;" oninput="updateFacItem('${id}','cantidad',this.value)">
    <input type="number" value="0" min="0" step="0.01" style="background:var(--bg3);border:1px solid var(--border);border-radius:5px;padding:7px 8px;color:var(--text);font-size:12px;outline:none;text-align:right;" oninput="updateFacItem('${id}','precio_unit',this.value)">
    <div id="st_${id}" style="padding:7px 8px;font-size:12px;font-family:'IBM Plex Mono',monospace;color:var(--accent);text-align:right;font-weight:600;">$0</div>
    <button onclick="removeFacItem('${id}')" style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:16px;padding:4px;">✕</button>`;
  document.getElementById('fac-items-list').appendChild(div);
}

function updateFacItem(id, field, value) {
  const item = facItems.find(x=>x.id===id); if(!item) return;
  item[field] = field==='cantidad'||field==='precio_unit' ? parseFloat(value)||0 : value;
  const sub = item.cantidad * item.precio_unit;
  const el = document.getElementById('st_'+id); if(el) el.textContent=fmt$(sub);
  actualizarTotalesFactura();
}
function removeFacItem(id) {
  facItems = facItems.filter(x=>x.id!==id);
  const el=document.getElementById(id); if(el) el.remove();
  actualizarTotalesFactura();
}
function actualizarTotalesFactura() {
  const total = facItems.reduce((s,i)=>s+(i.cantidad*i.precio_unit),0);
  document.getElementById('fac-total-display').textContent = fmt$(total);
}
document.addEventListener('input', e=>{ if(['fac-taller-nombre','fac-taller-rut'].includes(e.target.id)) return; });

async function saveFactura() {
  const cliente = val('fac-cliente_id'), numero = val('fac-numero');
  if(!cliente||!numero) return alert('Cliente y número de cuenta de cobro son obligatorios');
  if(facItems.length===0) return alert('Agrega al menos un ítem a la cuenta de cobro');
  const id = val('fac-id');
  // Guardamos datos del taller en el campo concepto como JSON extendido
  const tallerData = {
    nombre: val('fac-taller-nombre'), rut: val('fac-taller-rut'),
    telefono: val('fac-taller-telefono'), ciudad: val('fac-taller-ciudad'),
    banco: val('fac-banco'), tipo_cuenta: val('fac-tipo-cuenta'),
    num_cuenta: val('fac-num-cuenta'), titular_cuenta: val('fac-titular-cuenta')
  };
  const body = {
    numero, fecha:val('fac-fecha'), fecha_vencimiento:val('fac-fecha_vencimiento'),
    cliente_id:cliente, concepto: val('fac-concepto'),
    porcentaje_iva: 0,  // Sin IVA - cuenta de cobro
    estado:val('fac-estado'),
    notas: JSON.stringify({obs: val('fac-notas'), taller: tallerData}),
    items: facItems.map(({tipo,descripcion,cantidad,precio_unit},i)=>({tipo,descripcion,cantidad,precio_unit,orden:i}))
  };
  if(id) await apiFetch(`facturas.php?id=${id}`,'PUT',body);
  else   await apiFetch('facturas.php','POST',body);
  closeModal('modal-factura'); loadFacturas(); showToast('✅ Cuenta de cobro guardada');
}

async function editarFactura(id) {
  const f = await apiFetch(`facturas.php?id=${id}`); if(!f) return;
  populateSelect('fac-cliente_id', cache.clientes, 'id', 'empresa', 'Seleccionar...');
  setVal('fac-id',id); setVal('fac-numero',f.numero); setVal('fac-fecha',f.fecha);
  setVal('fac-fecha_vencimiento',f.fecha_vencimiento||''); setVal('fac-cliente_id',f.cliente_id||'');
  setVal('fac-concepto',f.concepto||''); setVal('fac-estado',f.estado);
  // Parsear datos del taller desde notas
  let tallerData={obs:'',taller:{}};
  try{ tallerData=JSON.parse(f.notas||'{}'); }catch(e){ tallerData={obs:f.notas||'',taller:{}}; }
  // Siempre usar datos fijos del prestador
  setVal('fac-taller-nombre', TALLER_DEFAULTS.nombre);
  setVal('fac-taller-rut',    TALLER_DEFAULTS.rut);
  setVal('fac-taller-telefono', TALLER_DEFAULTS.telefono);
  setVal('fac-titular-cuenta',  TALLER_DEFAULTS.titular_cuenta);
  // Datos opcionales desde el registro guardado
  setVal('fac-taller-ciudad', t.ciudad||'');
  setVal('fac-banco',         t.banco||'');
  setVal('fac-tipo-cuenta',   t.tipo_cuenta||'');
  setVal('fac-num-cuenta',    t.num_cuenta||'');
  document.getElementById('fac-modal-title').textContent='Editar Cuenta de Cobro';
  facItems=[]; document.getElementById('fac-items-list').innerHTML='';
  (f.items||[]).forEach(item=>{
    addItem(item.tipo);
    const last=facItems[facItems.length-1];
    if(last){
      last.descripcion=item.descripcion; last.cantidad=parseFloat(item.cantidad); last.precio_unit=parseFloat(item.precio_unit);
      const row=document.getElementById(last.id);
      if(row){
        row.querySelectorAll('input')[0].value=item.cantidad;
        row.querySelectorAll('input')[1].value=item.precio_unit;
        row.querySelector('input[placeholder]').value=item.descripcion;
        const st=document.getElementById('st_'+last.id); if(st) st.textContent=fmt$(last.cantidad*last.precio_unit);
      }
    }
  });
  actualizarTotalesFactura();
  openModal('modal-factura');
}

async function deleteFactura(id) {
  if(!confirm('¿Eliminar esta cuenta de cobro y todos sus pagos?')) return;
  await apiFetch(`facturas.php?id=${id}`,'DELETE'); loadFacturas(); showToast('🗑 Cuenta de cobro eliminada');
}

async function verFacturaDetalle(id) {
  const f = await apiFetch(`facturas.php?id=${id}`); if(!f) return;
  currentFacId = id;
  document.getElementById('fdet-titulo').textContent = `Cuenta de Cobro ${f.numero}`;
  const estMap={'Borrador':'gray','Emitida':'blue','Parcialmente Pagada':'amber','Pagada':'green','Vencida':'red','Anulada':'gray'};
  // Parsear datos del taller
  let tallerData={obs:'',taller:{}};
  try{ tallerData=JSON.parse(f.notas||'{}'); }catch(e){ tallerData={obs:f.notas||'',taller:{}}; }
  const t=tallerData.taller||{};
  const obs=tallerData.obs||'';
  const saldo = parseFloat(f.saldo_pendiente||0);
  const totalNum = parseFloat(f.total||0);
  // Valor en letras
  const enLetras = numeroALetras(totalNum);
  const pagoRows=(f.pagos||[]).map(p=>`
    <div class="pago-row">
      <div>💳 <strong>${p.metodo}</strong>${p.referencia?` · Ref: ${p.referencia}`:''}</div>
      <div style="color:var(--text3);font-size:12px;margin-left:10px;">${p.fecha}</div>
      ${p.notas?`<div style="font-size:12px;color:var(--text3);margin-left:10px;">${p.notas}</div>`:''}
      <div class="pago-monto">${fmt$(p.monto)}</div>
      <button onclick="eliminarPago('${id}',${p.id})" class="btn btn-danger btn-sm" style="margin-left:8px;">🗑</button>
    </div>`).join('');
  const itemRows=(f.items||[]).map(i=>{
    const sub = parseFloat(i.subtotal||0);
    const desc = parseFloat(i.cantidad)!==1 ? `${i.cantidad} ${i.descripcion}` : i.descripcion;
    return `<tr>
      <td style="padding:6px 0;font-size:13px;width:75%;">${desc}</td>
      <td style="padding:6px 0;font-size:13px;text-align:right;width:25%;">$ ${Number(sub).toLocaleString('es-CO')}</td>
    </tr>`;
  }).join('');

  // Fecha en letras estilo colombiano
  const meses=['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
  const fechaObj = f.fecha ? new Date(f.fecha+'T00:00:00') : new Date();
  const fechaLetras = `${t.ciudad||'Neiva'}, ${fechaObj.getDate()} de ${meses[fechaObj.getMonth()]} del ${fechaObj.getFullYear()}`;

  document.getElementById('fdet-content').innerHTML=`
  <div style="display:grid;grid-template-columns:1fr 360px;gap:20px;">
    <div>
      <!-- ═══ CUENTA DE COBRO — Formato Colombia ═══ -->
      <div class="fac-print" style="max-width:680px;padding:50px 60px;font-family:'Times New Roman',serif;">

        <!-- Encabezado: logo izquierda + datos centrados -->
        <div style="display:flex;align-items:center;gap:0;border-bottom:3px solid #1a56a0;padding-bottom:20px;margin-bottom:28px;">
          <!-- Logo grande -->
          <div style="flex-shrink:0;width:140px;">
            <img src="data:image/png;base64,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" style="width:140px;height:140px;object-fit:contain;" alt="Logo">
          </div>
          <!-- Texto corrido más a la derecha -->
          <div style="flex:1;text-align:center;padding-left:70px;padding-right:20px;">
            <div style="font-size:20px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#111;white-space:nowrap;">${t.nombre||'NOMBRE DEL PRESTADOR'}</div>
            <div style="font-size:13px;color:#333;margin-top:6px;">CC. / NIT ${t.rut||'______________'}</div>
            <div style="font-size:12px;color:#555;margin-top:4px;">NEIVA · 3137217967</div>
            <div style="font-size:12px;color:#555;margin-top:2px;">E – mail: edac77@gmail.com</div>
          </div>
          <div style="width:140px;flex-shrink:0;"></div>
        </div>

        <!-- Fecha -->
        <div style="font-size:13px;color:#333;margin-bottom:30px;">${fechaLetras}</div>

        <!-- Número y título centrado -->
        <div style="text-align:center;margin-bottom:32px;">
          <div style="font-size:16px;font-weight:700;letter-spacing:2px;color:#111;">CUENTA DE COBRO No. ${f.numero}</div>
        </div>

        <!-- Bloque "DEBE A" centrado -->
        <div style="text-align:center;margin-bottom:32px;line-height:2;">
          <div style="font-size:14px;font-weight:700;text-transform:uppercase;">${f.cliente||'NOMBRE DEL CLIENTE'}</div>
          ${f.cliente_rut?`<div style="font-size:13px;">NIT/C.C. ${f.cliente_rut}</div>`:''}
          <div style="font-size:14px;margin-top:8px;margin-bottom:8px;">DEBE A</div>
          <div style="font-size:14px;font-weight:700;text-transform:uppercase;">${t.nombre||'PRESTADOR'}</div>
          <div style="font-size:13px;">CC. ${t.rut||'______________'}</div>
        </div>

        <!-- Monto en número y letras centrado -->
        <div style="text-align:center;margin-bottom:36px;">
          <div style="font-size:13px;color:#333;margin-bottom:4px;">LA SUMA DE</div>
          <div style="font-size:20px;font-weight:700;color:#111;">($${Number(totalNum).toLocaleString('es-CO')})</div>
          <div style="font-size:13px;font-style:italic;color:#333;margin-top:4px;">${enLetras.charAt(0).toUpperCase()+enLetras.slice(1)} pesos m/cte.</div>
        </div>

        <!-- Concepto + tabla de ítems -->
        <div style="margin-bottom:28px;">
          ${f.concepto?`<div style="font-size:13px;font-weight:600;margin-bottom:12px;">Concepto</div><div style="font-size:13px;margin-bottom:16px;">${f.concepto}</div>`:'<div style="font-size:13px;font-weight:600;margin-bottom:12px;">Concepto</div>'}
          <table style="width:100%;border-collapse:collapse;">
            <tbody>${itemRows}</tbody>
            <tr>
              <td style="padding:10px 0 4px;font-size:13px;font-weight:700;border-top:1px solid #ddd;text-align:right;">TOTAL</td>
              <td style="padding:10px 0 4px;font-size:14px;font-weight:700;border-top:1px solid #ddd;text-align:right;">$${Number(totalNum).toLocaleString('es-CO')}</td>
            </tr>
          </table>
        </div>

        <!-- Datos bancarios (si tiene) -->
        ${(t.banco||t.num_cuenta)?`
        <div style="background:#f0f7ff;border:1px solid #bfdbfe;border-radius:4px;padding:14px 16px;margin-bottom:28px;font-size:12px;">
          <div style="font-weight:700;color:#1d4ed8;margin-bottom:8px;font-size:11px;text-transform:uppercase;letter-spacing:1px;">Datos para Consignación / Transferencia</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:4px 20px;">
            ${t.banco?`<div><span style="color:#666;">Banco:</span> <strong>${t.banco}</strong></div>`:''}
            ${t.tipo_cuenta?`<div><span style="color:#666;">Tipo:</span> <strong>${t.tipo_cuenta}</strong></div>`:''}
            ${t.num_cuenta?`<div><span style="color:#666;">N° Cuenta:</span> <strong style="letter-spacing:1px;">${t.num_cuenta}</strong></div>`:''}
            ${t.titular_cuenta?`<div><span style="color:#666;">Titular:</span> <strong>${t.titular_cuenta}</strong></div>`:''}
          </div>
        </div>`:''}

        <!-- Observaciones -->
        ${obs?`<div style="margin-bottom:28px;font-size:12px;font-style:italic;color:#444;">${obs}</div>`:''}

        <!-- Firma -->
        <div style="margin-top:50px;">
          <div style="font-size:13px;margin-bottom:50px;">Atentamente,</div>
          <div style="border-top:1px solid #aaa;padding-top:8px;display:inline-block;min-width:220px;">
            <div style="font-size:13px;font-weight:700;text-transform:uppercase;">${t.nombre||'PRESTADOR DE SERVICIOS'}</div>
            <div style="font-size:12px;color:#444;">CC. ${t.rut||'______________'}</div>
            ${t.telefono?`<div style="font-size:12px;color:#444;">CEL. ${t.telefono}</div>`:''}
          </div>
        </div>

        <!-- Pie de página -->
        <div style="margin-top:40px;border-top:2px solid #1a56a0;padding-top:10px;text-align:center;font-size:11px;color:#666;">
          Dirección: Calle 20a # 38-36, Neiva-Huila · Tels. 3137217967 · E-mail: edac77@gmail.com
        </div>
      </div>
    </div>

    <!-- Panel lateral pagos (no se imprime) -->
    <div class="no-print">
      <div style="background:var(--bg2);border:1px solid var(--border);border-radius:10px;padding:20px;">
        <div style="font-family:'Rajdhani',sans-serif;font-size:18px;font-weight:700;margin-bottom:16px;">💰 Pagos</div>
        <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px;color:var(--text2);">
          <span>Total Cuenta</span><strong>${fmt$(f.total)}</strong>
        </div>
        <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13px;color:var(--success);">
          <span>Total Pagado</span><strong>${fmt$(f.total_pagado)}</strong>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:700;padding:12px 0;border-top:1px solid var(--border);color:${saldo>0?'var(--danger)':'var(--success)'};">
          <span>Saldo Pendiente</span><span>${fmt$(saldo)}</span>
        </div>
        ${saldo>0&&f.estado!=='Anulada'?`<button class="btn btn-primary" style="width:100%;margin-top:12px;" onclick="abrirPago()">＋ Registrar Pago</button>`:'<div style="text-align:center;color:var(--success);font-weight:600;padding:10px;">✓ Totalmente Pagada</div>'}
        <div style="margin-top:20px;font-size:12px;color:var(--text3);font-weight:600;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">Historial de Pagos</div>
        ${pagoRows||'<div style="color:var(--text3);font-size:13px;text-align:center;padding:20px 0;">Sin pagos registrados</div>'}
      </div>
    </div>
  </div>`;
  showPage('factura-detalle');
}

function abrirPago() {
  if(!currentFacId) return;
  const f = (cache.facturas||[]).find(x=>x.id===currentFacId);
  const saldo = f ? parseFloat(f.saldo_pendiente||0) : 0;
  document.getElementById('pago-fac-info').innerHTML=f?`<strong>${f.numero}</strong> · ${f.cliente||'-'}<br><span style="color:var(--text3);">Saldo pendiente: <strong style="color:var(--danger);">${fmt$(saldo)}</strong></span>`:'';
  document.getElementById('pago-fecha').value=new Date().toISOString().split('T')[0];
  document.getElementById('pago-monto').value=saldo>0?saldo.toFixed(0):'';
  setVal('pago-referencia',''); setVal('pago-notas','');
  openModal('modal-pago');
}

async function guardarPago() {
  const monto = parseFloat(val('pago-monto')); if(!monto||monto<=0) return alert('Ingresa un monto válido');
  const body = {fecha:val('pago-fecha'),monto,metodo:val('pago-metodo'),referencia:val('pago-referencia'),notas:val('pago-notas')};
  const res = await apiFetch(`facturas.php?id=${currentFacId}&action=pago`,'POST',body);
  closeModal('modal-pago');
  showToast(`✅ Pago registrado — Estado: ${res?.estado||''}`);
  loadFacturas();
  verFacturaDetalle(currentFacId);
}

async function eliminarPago(facId, pagoId) {
  if(!confirm('¿Eliminar este pago?')) return;
  await apiFetch(`facturas.php?id=${facId}&action=pago&pago_id=${pagoId}`,'DELETE');
  showToast('🗑 Pago eliminado'); loadFacturas(); verFacturaDetalle(facId);
}

// ── PAGOS (vista global) ──────────────────────────────────────
async function loadPagos() {
  const facs = await apiFetch('facturas.php'); if(!facs) return;
  cache.facturas = facs;
  // Recolectar todos los pagos de todas las facturas
  const allPagos = [];
  for(const f of facs) {
    if(f.total_pagado>0) {
      const det = await apiFetch(`facturas.php?id=${f.id}`);
      if(det?.pagos) det.pagos.forEach(p=>allPagos.push({...p, factura_numero:f.numero, cliente:f.cliente}));
    }
  }
  allPagos.sort((a,b)=>b.fecha.localeCompare(a.fecha));
  const metodoCols={'Efectivo':'green','Transferencia Bancaria':'blue','Cheque':'amber','Tarjeta Débito':'blue','Tarjeta Crédito':'blue','Otro':'gray'};
  const tb=document.getElementById('tb-pagos');
  tb.innerHTML=allPagos.length?allPagos.map(p=>`<tr>
    <td>${p.fecha}</td>
    <td class="id-code" style="color:var(--accent);cursor:pointer;" onclick="verFacturaDetalle('${p.factura_id}')">${p.factura_numero}</td>
    <td>${p.cliente||'-'}</td>
    <td><span class="tag tag-${metodoCols[p.metodo]||'gray'}">${p.metodo}</span></td>
    <td style="color:var(--text3);font-size:12px;">${p.referencia||'-'}</td>
    <td style="font-family:'IBM Plex Mono',monospace;font-weight:700;color:var(--success);">${fmt$(p.monto)}</td>
    <td><button class="btn btn-danger btn-sm" onclick="eliminarPago('${p.factura_id}',${p.id})">🗑</button></td>
  </tr>`).join(''):'<tr><td colspan="7"><div class="empty-state"><div class="empty-icon">💰</div><p>No hay pagos registrados</p></div></td></tr>';
}

// ── HELPERS ───────────────────────────────────────────────────
function resetForm(ids){ids.forEach(id=>{const el=document.getElementById(id);if(el)el.value='';});}
function showToast(msg){
  let t=document.getElementById('toast');
  if(!t){t=document.createElement('div');t.id='toast';t.style.cssText='position:fixed;bottom:28px;right:28px;background:#1a1e29;border:1px solid #f59e0b;color:#e2e8f0;padding:12px 20px;border-radius:8px;font-size:13px;font-weight:500;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,.4);transition:opacity .4s;';document.body.appendChild(t);}
  t.textContent=msg;t.style.opacity='1';clearTimeout(t._t);t._t=setTimeout(()=>t.style.opacity='0',3000);
}

// ── GASTOS ADMINISTRATIVOS ────────────────────────────────────
const CATEGORIAS_COLORES = {
  'Viáticos':'#f59e0b','Servicios Públicos':'#3b82f6','Arriendo / Alquiler local':'#8b5cf6',
  'Alquiler de Equipos':'#06b6d4','Transporte':'#10b981','Comunicaciones':'#f97316',
  'Papelería / Insumos':'#84cc16','Salarios / Honorarios':'#ef4444',
  'Mantenimiento Locativo':'#a78bfa','Impuestos / Tasas':'#dc2626',
  'Seguros':'#0ea5e9','Otros':'#94a3b8'
};

function abrirNuevoGasto() {
  setVal('g-id','');
  document.getElementById('gasto-modal-title').textContent = 'Registrar Gasto';
  document.getElementById('g-fecha').value = new Date().toISOString().split('T')[0];
  setVal('g-categoria',''); setVal('g-concepto',''); setVal('g-proveedor','');
  setVal('g-metodo','Efectivo'); setVal('g-valor','0');
  setVal('g-soporte',''); setVal('g-observaciones','');
  document.getElementById('g-valor-letras').textContent = 'cero pesos';
  openModal('modal-gasto');
}

async function loadGastos() {
  const cat     = document.getElementById('gasto-filtro-cat')?.value    || '';
  const periodo = document.getElementById('gasto-filtro-periodo')?.value || 'mes';
  const params  = new URLSearchParams({ periodo });
  if(cat) params.append('categoria', cat);
  const data = await apiFetch('gastos.php?' + params.toString());
  if(!data) return;

  // KPIs
  const ahora   = new Date();
  const esMes   = g => { const d=new Date(g.fecha+'T00:00:00'); return d.getMonth()===ahora.getMonth()&&d.getFullYear()===ahora.getFullYear(); };
  const esAnio  = g => new Date(g.fecha+'T00:00:00').getFullYear()===ahora.getFullYear();
  const totalMes  = data.filter(esMes).reduce((s,g)=>s+parseFloat(g.valor),0);
  const totalAnio = data.filter(esAnio).reduce((s,g)=>s+parseFloat(g.valor),0);
  // Categoría con más gasto este mes
  const porCat = data.filter(esMes).reduce((acc,g)=>{ acc[g.categoria]=(acc[g.categoria]||0)+parseFloat(g.valor); return acc; },{});
  const topCat = Object.entries(porCat).sort((a,b)=>b[1]-a[1])[0];
  document.getElementById('gkpi-mes').textContent   = fmt$(totalMes);
  document.getElementById('gkpi-anio').textContent  = fmt$(totalAnio);
  document.getElementById('gkpi-count').textContent = data.length;
  document.getElementById('gkpi-cat').textContent   = topCat ? topCat[0] : '—';
  document.getElementById('gkpi-cat-val').textContent = topCat ? fmt$(topCat[1])+' este mes' : 'sin datos';

  // Tabla
  const tb = document.getElementById('tb-gastos');
  tb.innerHTML = data.length ? data.map(g => {
    const color = CATEGORIAS_COLORES[g.categoria]||'#94a3b8';
    return `<tr>
      <td>${g.fecha}</td>
      <td><span style="background:${color}22;color:${color};padding:3px 10px;border-radius:4px;font-size:11px;font-weight:700;">${g.categoria}</span></td>
      <td><strong>${g.concepto}</strong></td>
      <td style="color:var(--text2);">${g.proveedor||'-'}</td>
      <td style="font-size:12px;">${g.metodo_pago}</td>
      <td style="font-weight:700;color:var(--danger);">${fmt$(g.valor)}</td>
      <td style="font-size:11px;color:var(--text3);">${g.soporte||'-'}</td>
      <td class="actions">
        ${tienePermiso('gastos','editar')?`<button class="btn btn-blue btn-sm" onclick="editarGasto(${g.id})">✏️</button>`:''}
        ${tienePermiso('gastos','eliminar')?`<button class="btn btn-danger btn-sm" onclick="eliminarGasto(${g.id})">🗑</button>`:''}
      </td>
    </tr>`;
  }).join('')
  : '<tr><td colspan="8"><div class="empty-state"><div class="empty-icon">💸</div><p>Sin gastos registrados en este período</p></div></td></tr>';
}

async function guardarGasto() {
  const categoria = val('g-categoria'), concepto = val('g-concepto'), valor = parseFloat(val('g-valor'));
  if(!categoria) return alert('Selecciona una categoría');
  if(!concepto)  return alert('El concepto es obligatorio');
  if(!valor || valor <= 0) return alert('El valor debe ser mayor a 0');
  const id = val('g-id');
  const body = {
    fecha:        val('g-fecha'),
    categoria,
    concepto,
    proveedor:    val('g-proveedor'),
    metodo_pago:  val('g-metodo'),
    valor,
    soporte:      val('g-soporte'),
    observaciones:val('g-observaciones')
  };
  if(id) await apiFetch(`gastos.php?id=${id}`, 'PUT', body);
  else   await apiFetch('gastos.php', 'POST', body);
  closeModal('modal-gasto');
  loadGastos();
  loadDashboard();
  showToast('✅ Gasto registrado');
}

async function editarGasto(id) {
  const data = await apiFetch(`gastos.php?id=${id}`); if(!data) return;
  document.getElementById('gasto-modal-title').textContent = 'Editar Gasto';
  setVal('g-id',         id);
  setVal('g-fecha',      data.fecha);
  setVal('g-categoria',  data.categoria);
  setVal('g-concepto',   data.concepto);
  setVal('g-proveedor',  data.proveedor||'');
  setVal('g-metodo',     data.metodo_pago);
  setVal('g-valor',      data.valor);
  setVal('g-soporte',    data.soporte||'');
  setVal('g-observaciones', data.observaciones||'');
  document.getElementById('g-valor-letras').textContent = numeroALetras(parseFloat(data.valor||0))+' pesos';
  openModal('modal-gasto');
}

async function eliminarGasto(id) {
  if(!confirm('¿Eliminar este gasto?')) return;
  await apiFetch(`gastos.php?id=${id}`, 'DELETE');
  loadGastos(); loadDashboard();
  showToast('🗑 Gasto eliminado');
}

// ── NÚMERO A LETRAS (Colombia) ────────────────────────────────
function numeroALetras(num) {
  const unidades=['','un','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once','doce','trece','catorce','quince','dieciséis','diecisiete','dieciocho','diecinueve'];
  const decenas=['','diez','veinte','treinta','cuarenta','cincuenta','sesenta','setenta','ochenta','noventa'];
  const centenas=['','ciento','doscientos','trescientos','cuatrocientos','quinientos','seiscientos','setecientos','ochocientos','novecientos'];
  num = Math.round(num);
  if(num===0) return 'cero';
  if(num>=1000000) return numeroALetras(Math.floor(num/1000000))+' millón'+((Math.floor(num/1000000)>1)?'es':'')+' '+numeroALetras(num%1000000);
  if(num>=1000){ const m=Math.floor(num/1000); return (m===1?'mil':numeroALetras(m)+' mil')+' '+numeroALetras(num%1000); }
  if(num>=100){ if(num===100) return 'cien'; return centenas[Math.floor(num/100)]+' '+numeroALetras(num%100); }
  if(num>=20){ const d=Math.floor(num/10); const u=num%10; return decenas[d]+(u>0?' y '+unidades[u]:''); }
  return unidades[num]||'';
}

// ── AUTENTICACIÓN ─────────────────────────────────────────────
let currentUser = null;
const TODAS_SECCIONES = ['dashboard','clientes','maquinaria','repuestos','tecnicos','ordenes','mantenimientos','reportes','facturas','pagos','gastos','usuarios'];

function getStoredUser(){ try{ const s=localStorage.getItem('tallerpro_session'); return s?JSON.parse(s):null; }catch(e){ return null; } }
function saveUser(u){ try{ localStorage.setItem('tallerpro_session',JSON.stringify(u)); }catch(e){} }
function clearUser(){ 
  try{ 
    localStorage.removeItem('tallerpro_session');
    localStorage.removeItem('tallerpro_user');
    localStorage.clear();
  }catch(e){} 
}

function mostrarLogin(){
  clearUser();
  document.getElementById('login-screen').classList.add('visible');
  document.querySelector('.sidebar').style.display='none';
  document.querySelector('.main').style.display='none';
  setTimeout(()=>document.getElementById('login-user').focus(),100);
}
function ocultarLogin(){
  document.getElementById('login-screen').classList.remove('visible');
  document.querySelector('.sidebar').style.display='';
  document.querySelector('.main').style.display='';
}
async function doLogin(){
  const username = document.getElementById('login-user').value.trim();
  const password = document.getElementById('login-pass').value;
  const errEl = document.getElementById('login-error');
  errEl.classList.remove('visible');
  if(!username||!password){ errEl.textContent='Ingresa usuario y contraseña'; errEl.classList.add('visible'); return; }
  const res = await fetch('api/auth.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({action:'login',username,password})});
  const data = await res.json().catch(()=>({}));
  if(data.ok){
    currentUser = data.user;
    saveUser(currentUser);
    ocultarLogin();
    aplicarPermisos();
    document.getElementById('topbar-username').textContent = currentUser.nombre || currentUser.username;
    document.getElementById('topbar-avatar').textContent   = (currentUser.nombre||currentUser.username).charAt(0).toUpperCase();
    preload();
  } else {
    errEl.textContent = data.error || 'Usuario o contraseña incorrectos';
    errEl.classList.add('visible');
    document.getElementById('login-pass').value='';
    document.getElementById('login-pass').focus();
  }
}
async function cerrarSesion(){
  if(!confirm('¿Cerrar sesión?')) return;
  const token = currentUser?.token || getStoredUser()?.token || '';
  if(token) await fetch('api/auth.php',{method:'POST',headers:{'Content-Type':'application/json','X-Auth-Token':token},body:JSON.stringify({action:'logout',token})}).catch(()=>{});
  currentUser=null;
  clearUser();
  document.getElementById('login-user').value='';
  document.getElementById('login-pass').value='';
  mostrarLogin();
}
// ── SISTEMA DE PERMISOS GRANULARES ───────────────────────────
const MODULOS = [
  {key:'dashboard',   label:'Dashboard',           icon:'📊', acciones:['consultar']},
  {key:'clientes',    label:'Clientes',             icon:'👥', acciones:['consultar','crear','editar','eliminar']},
  {key:'maquinaria',  label:'Maquinaria',           icon:'⚙️', acciones:['consultar','crear','editar','eliminar']},
  {key:'repuestos',   label:'Repuestos / Inventario',icon:'🔩', acciones:['consultar','crear','editar','eliminar']},
  {key:'tecnicos',    label:'Técnicos',             icon:'👷', acciones:['consultar','crear','editar','eliminar']},
  {key:'ordenes',     label:'Órdenes de Servicio',  icon:'📋', acciones:['consultar','crear','editar','eliminar']},
  {key:'mantenimientos',label:'Mantenimientos',     icon:'🗓️', acciones:['consultar','crear','editar','eliminar']},
  {key:'reportes',    label:'Reportes',             icon:'📄', acciones:['consultar']},
  {key:'facturas',    label:'Cuentas de Cobro',     icon:'🧾', acciones:['consultar','crear','editar','eliminar']},
  {key:'pagos',       label:'Pagos',                icon:'💰', acciones:['consultar','crear','eliminar']},
  {key:'gastos',      label:'Gastos Administrativos',icon:'💸', acciones:['consultar','crear','editar','eliminar']},
];
const ACCION_LABELS = {consultar:'🔵 Consultar', crear:'🟢 Crear', editar:'🟡 Editar', eliminar:'🔴 Eliminar'};

// Helpers de permisos
function tienePermiso(modulo, accion='consultar'){
  if(!currentUser) return false;
  if(currentUser.rol==='root'||currentUser.rol==='admin') return true;
  const perms = currentUser._permisos || {};
  if(!perms[modulo]) return false;
  return perms[modulo].includes(accion);
}
function puedeVer(modulo){
  // Puede ver el módulo en el menú solo si tiene más que consultar
  if(!currentUser) return false;
  if(currentUser.rol==='root'||currentUser.rol==='admin') return true;
  const perms = currentUser._permisos || {};
  if(!perms[modulo]) return false;
  return perms[modulo].some(a=>a!=='consultar') || (modulo==='dashboard'||modulo==='reportes');
}

// Renderizar módulos de permisos en el modal
function renderPermModulos(permisosActuales={}){
  const container = document.getElementById('perm-modulos-container');
  container.innerHTML = MODULOS.map(m=>{
    const tieneAlgo = permisosActuales[m.key] && permisosActuales[m.key].length>0;
    return `<div class="perm-modulo ${tieneAlgo?'tiene-permisos':''}" id="pm-${m.key}">
      <div class="perm-modulo-header" onclick="toggleModuloCollapse('${m.key}')">
        <div class="perm-modulo-title">${m.icon} ${m.label}</div>
        <div style="display:flex;align-items:center;gap:8px;">
          <span id="pm-badge-${m.key}" style="font-size:11px;color:var(--text3);">${tieneAlgo?(permisosActuales[m.key].join(', ')):'Sin acceso'}</span>
          <span style="color:var(--text3);font-size:12px;">▾</span>
        </div>
      </div>
      <div class="perm-acciones" id="pm-acciones-${m.key}">
        ${m.acciones.map(a=>`
          <label class="perm-accion ${a}" onclick="updatePermBadge('${m.key}')">
            <input type="checkbox" id="perm-${m.key}-${a}" value="${a}" 
              ${permisosActuales[m.key]&&permisosActuales[m.key].includes(a)?'checked':''}>
            ${ACCION_LABELS[a]}
          </label>`).join('')}
        <button type="button" onclick="setModuloPerms('${m.key}','all')" class="btn btn-secondary btn-sm" style="margin-left:auto;">Todo</button>
        <button type="button" onclick="setModuloPerms('${m.key}','none')" class="btn btn-secondary btn-sm">Ninguno</button>
      </div>
    </div>`;
  }).join('');
}
function toggleModuloCollapse(key){
  const acc = document.getElementById('pm-acciones-'+key);
  acc.style.display = acc.style.display==='none' ? '' : 'none';
}
function updatePermBadge(key){
  const mod = MODULOS.find(m=>m.key===key);
  const checked = mod.acciones.filter(a=>document.getElementById(`perm-${key}-${a}`)?.checked);
  document.getElementById('pm-badge-'+key).textContent = checked.length ? checked.join(', ') : 'Sin acceso';
  document.getElementById('pm-'+key).classList.toggle('tiene-permisos', checked.length>0);
}
function setModuloPerms(key, modo){
  const mod = MODULOS.find(m=>m.key===key);
  mod.acciones.forEach(a=>{
    const cb = document.getElementById(`perm-${key}-${a}`);
    if(cb) cb.checked = modo==='all';
  });
  updatePermBadge(key);
}
function toggleAllGranular(modo){
  MODULOS.forEach(m=>{
    m.acciones.forEach(a=>{
      const cb = document.getElementById(`perm-${m.key}-${a}`);
      if(!cb) return;
      if(modo==='all') cb.checked=true;
      else if(modo==='none') cb.checked=false;
      else if(modo==='consultar') cb.checked=(a==='consultar');
    });
    updatePermBadge(m.key);
  });
}
function getPermisos(){
  const result={};
  MODULOS.forEach(m=>{
    const checked=m.acciones.filter(a=>document.getElementById(`perm-${m.key}-${a}`)?.checked);
    if(checked.length) result[m.key]=checked;
  });
  return result;
}
function setPermisos(permObj){
  // permObj puede ser objeto granular {clientes:['consultar','crear']} 
  // o array legacy ['clientes','ordenes']
  let parsed = permObj;
  if(typeof permObj === 'string') try{ parsed=JSON.parse(permObj); }catch(e){ parsed={}; }
  // Compatibilidad con formato legacy (array de strings)
  if(Array.isArray(parsed)){
    const legacy={};
    parsed.forEach(m=>{ legacy[m]=['consultar','crear','editar','eliminar']; });
    parsed=legacy;
  }
  renderPermModulos(parsed);
}

// Aplicar permisos al nav y guardar en currentUser
function aplicarPermisos(){
  if(!currentUser) return;
  document.getElementById('topbar-username').textContent = currentUser.nombre || currentUser.username;
  document.getElementById('topbar-avatar').textContent   = (currentUser.nombre||currentUser.username).charAt(0).toUpperCase();
  const esRootAdmin = currentUser.rol==='root' || currentUser.rol==='admin';
  // Parsear permisos
  let permsObj = {};
  if(esRootAdmin){
    MODULOS.forEach(m=>{ permsObj[m.key]=['consultar','crear','editar','eliminar']; });
  } else {
    let raw = currentUser.permisos;
    if(typeof raw==='string') try{ raw=JSON.parse(raw); }catch(e){ raw={}; }
    if(Array.isArray(raw)){ raw.forEach(m=>{ permsObj[m]=['consultar','crear','editar','eliminar']; }); }
    else permsObj = raw||{};
  }
  currentUser._permisos = permsObj;
  // Mostrar/ocultar nav
  document.querySelectorAll('.nav-item').forEach(el=>{
    const onclick = el.getAttribute('onclick')||'';
    const match = onclick.match(/showPage\('(\w+)'\)/);
    if(!match) return;
    const page = match[1];
    if(page==='factura-detalle') return;
    if(page==='usuarios'){
      el.style.display = esRootAdmin ? '' : 'none';
      return;
    }
    if(page==='dashboard'){ el.style.display=''; return; }
    el.style.display = puedeVer(page) ? '' : 'none';
  });
  const navAdmin = document.getElementById('nav-section-admin');
  const navUsr   = document.getElementById('nav-usuarios');
  if(navAdmin) navAdmin.style.display = esRootAdmin ? '' : 'none';
  if(navUsr)   navUsr.style.display   = esRootAdmin ? '' : 'none';
  // Ocultar botones según permisos en cada módulo
  aplicarPermsBotones();
  initNavGroups();
  const primeraSeccion = MODULOS.find(m=>m.key!=='usuarios'&&(esRootAdmin||puedeVer(m.key)));
  showPage(primeraSeccion ? primeraSeccion.key : 'dashboard');
}

function aplicarPermsBotones(){
  // Ocultar botones de crear/editar/eliminar según permisos
  const acciones = [
    {selector:'#btn-nuevo-cliente',    modulo:'clientes',    accion:'crear'},
    {selector:'#btn-nueva-maquina',    modulo:'maquinaria',  accion:'crear'},
    {selector:'#btn-nuevo-repuesto',   modulo:'repuestos',   accion:'crear'},
    {selector:'#btn-nuevo-tecnico',    modulo:'tecnicos',    accion:'crear'},
    {selector:'#btn-nueva-orden',      modulo:'ordenes',     accion:'crear'},
    {selector:'#btn-nuevo-mant',       modulo:'mantenimientos',accion:'crear'},
    {selector:'#btn-nueva-factura',    modulo:'facturas',    accion:'crear'},
    {selector:'#btn-nuevo-gasto',      modulo:'gastos',      accion:'crear'},
  ];
  acciones.forEach(({selector,modulo,accion})=>{
    const el=document.querySelector(selector);
    if(el) el.style.display=tienePermiso(modulo,accion)?'':'none';
  });
}
// Bloquear navegación a secciones sin permiso
const _showPageOriginal = typeof showPage !== 'undefined' ? null : null;

// ── USUARIOS ──────────────────────────────────────────────────
async function loadUsuarios(){
  const data = await apiFetch('usuarios.php'); if(!data) return;
  const tb = document.getElementById('tb-usuarios');
  tb.innerHTML = data.length ? data.map(u=>{
    const rolColor = {root:'var(--danger)',admin:'var(--accent)',tecnico:'var(--accent2)',operario:'var(--success)'};
    const permsArr = u.rol==='root'||u.rol==='admin' ? ['Acceso total'] : (u.permisos ? JSON.parse(u.permisos) : []);
    const permsTxt = permsArr.length ? permsArr.slice(0,3).join(', ')+(permsArr.length>3?` +${permsArr.length-3}`:'') : 'Sin permisos';
    return `<tr>
      <td><span style="font-family:'IBM Plex Mono',monospace;font-weight:700;color:var(--accent);">${u.username}</span></td>
      <td><strong>${u.nombre||'-'}</strong>${u.email?`<br><span style="font-size:11px;color:var(--text3);">${u.email}</span>`:''}</td>
      <td><span style="background:${rolColor[u.rol]||'var(--text3)'}22;color:${rolColor[u.rol]||'var(--text3)'};padding:3px 10px;border-radius:4px;font-size:11px;font-weight:700;text-transform:uppercase;">${u.rol}</span></td>
      <td><span class="tag tag-${u.activo=='1'?'green':'red'}">${u.activo=='1'?'Activo':'Inactivo'}</span></td>
      <td style="font-size:12px;color:var(--text3);">${u.ultimo_acceso||'Nunca'}</td>
      <td style="font-size:11px;color:var(--text2);max-width:200px;">${permsTxt}</td>
      <td class="actions">
        ${u.rol!=='root'?`<button class="btn btn-blue btn-sm" onclick="editarUsuario(${u.id})">✏️</button>
        <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(${u.id},'${u.username}')">🗑</button>`:'<span style="font-size:11px;color:var(--text3);">Protegido</span>'}
      </td>
    </tr>`;
  }).join('') : '<tr><td colspan="7"><div class="empty-state"><div class="empty-icon">👤</div><p>No hay usuarios</p></div></td></tr>';
}
function abrirNuevoUsuario(){
  setVal('usr-id',''); setVal('usr-username',''); setVal('usr-nombre','');
  setVal('usr-password',''); setVal('usr-email','');
  setVal('usr-rol','operario'); setVal('usr-activo','1');
  document.getElementById('usr-modal-title').textContent='Nuevo Usuario';
  document.getElementById('usr-permisos-wrap').style.opacity='1';
  document.getElementById('usr-permisos-wrap').style.pointerEvents='';
  renderPermModulos({});
  openModal('modal-usuario');
}
function onRolChange(){
  const rol = val('usr-rol');
  const wrap = document.getElementById('usr-permisos-wrap');
  if(rol==='admin'||rol==='root'){
    wrap.style.opacity='0.4';
    wrap.style.pointerEvents='none';
    toggleAllGranular('all');
  } else {
    wrap.style.opacity='1';
    wrap.style.pointerEvents='';
  }
}
async function guardarUsuario(){
  const username = val('usr-username').trim();
  const nombre   = val('usr-nombre').trim();
  const password = val('usr-password');
  const id       = val('usr-id');
  if(!username||!nombre) return alert('Usuario y nombre son obligatorios');
  if(!id && !password)   return alert('La contraseña es obligatoria para nuevos usuarios');
  if(password && password.length < 6) return alert('La contraseña debe tener al menos 6 caracteres');
  const rol      = val('usr-rol');
  const permisos = (rol==='admin'||rol==='root') ? {} : getPermisos();
  const body = {username, nombre, email:val('usr-email'), rol, activo:val('usr-activo'), permisos:JSON.stringify(permisos)};
  if(password) body.password = password;
  if(id) await apiFetch(`usuarios.php?id=${id}`,'PUT',body);
  else   await apiFetch('usuarios.php','POST',body);
  closeModal('modal-usuario'); loadUsuarios(); showToast('✅ Usuario guardado');
}
async function editarUsuario(id){
  const data = await apiFetch(`usuarios.php?id=${id}`); if(!data) return;
  document.getElementById('usr-modal-title').textContent = 'Editar Usuario';
  setVal('usr-id',       id);
  setVal('usr-username', data.username);
  setVal('usr-nombre',   data.nombre||'');
  setVal('usr-email',    data.email||'');
  setVal('usr-rol',      data.rol);
  setVal('usr-activo',   data.activo);
  setVal('usr-password', '');
  document.getElementById('usr-password').placeholder = 'Dejar vacío para no cambiar';
  setPermisos(data.permisos||'{}');
  onRolChange();
  openModal('modal-usuario');
}
async function eliminarUsuario(id, username){
  if(!confirm(`¿Eliminar el usuario "${username}"? Esta acción no se puede deshacer.`)) return;
  await apiFetch(`usuarios.php?id=${id}`,'DELETE');
  loadUsuarios(); showToast('🗑 Usuario eliminado');
}

// ── INIT ──────────────────────────────────────────────────────
document.getElementById('current-date').textContent=new Date().toLocaleDateString('es-CL',{weekday:'long',year:'numeric',month:'long',day:'numeric'});
// Precargar datos en cache
async function preload(){
  const [c,m,r,t,b]= await Promise.all([apiFetch('clientes.php'),apiFetch('maquinaria.php'),apiFetch('repuestos.php'),apiFetch('tecnicos.php'),apiFetch('bodegas.php')]);
  if(c)cache.clientes=c; if(m)cache.maquinaria=m; if(r)cache.repuestos=r; if(t)cache.tecnicos=t; if(b)cache.bodegas=b;
  poblarSelectBodegas();
  loadDashboard();
}
// Agregar usuarios al mapa de títulos — se maneja dentro de showPage

// Verificar sesión activa al cargar
(async function initApp(){
  mostrarLogin();
  const saved = getStoredUser();
  if(!saved || !saved.token){
    document.getElementById('login-user').focus();
    return;
  }
  const res = await fetch('api/auth.php?action=check',{
    headers:{'Content-Type':'application/json','X-Auth-Token':saved.token}
  }).catch(()=>null);
  const data = res ? await res.json().catch(()=>({})) : {};
  if(data.ok && data.user){
    currentUser = {...data.user, token: saved.token};
    saveUser(currentUser);
    ocultarLogin();
    aplicarPermisos();
    document.getElementById('topbar-username').textContent = currentUser.nombre || currentUser.username;
    document.getElementById('topbar-avatar').textContent   = (currentUser.nombre||currentUser.username).charAt(0).toUpperCase();
    preload();
  } else {
    clearUser();
    document.getElementById('login-user').focus();
  }
})();

// ── MEDIA: FOTOS Y VIDEOS (globales) ──────────────────────────
async function mediaCargar(tipoRef, refId, contenedorId) {
  const cont = document.getElementById(contenedorId);
  if (!cont) return;
  cont.innerHTML = '<span style="font-size:11px;color:var(--text3);">Cargando...</span>';
  const data = await apiFetch(`media.php?tipo=${tipoRef}&ref_id=${refId}`);
  if (!data || !data.length) {
    cont.innerHTML = '<span style="font-size:11px;color:var(--text3);">Sin archivos</span>';
    return;
  }
  cont.innerHTML = `<div class="media-grid">${data.map(f=>`
    <div class="media-thumb" title="${f.descripcion||f.nombre_archivo}">
      ${f.tipo_archivo==='video'
        ? `<video src="${f.ruta}" preload="metadata" onclick="abrirLightbox('${f.ruta}','video','${(f.descripcion||f.nombre_archivo).replace(/'/g,'')}')" style="pointer-events:auto;cursor:pointer;"></video>
           <div class="media-video-icon">▶️</div>`
        : `<img src="${f.ruta}" loading="lazy" onclick="abrirLightbox('${f.ruta}','foto','${(f.descripcion||f.nombre_archivo).replace(/'/g,'')}')">`}
      ${tienePermiso('ordenes','eliminar')||tienePermiso('mantenimientos','eliminar')
        ? `<button class="media-del" onclick="mediaEliminar(${f.id},'${contenedorId}','${tipoRef}','${refId}')" title="Eliminar">✕</button>`:''}
    </div>`).join('')}</div>`;
}

async function mediaEliminar(id, contenedorId, tipoRef, refId) {
  if (!confirm('¿Eliminar este archivo?')) return;
  await fetch(`api/media.php?id=${id}`, {method:'DELETE', headers:{'X-Auth-Token': getStoredToken()}});
  mediaCargar(tipoRef, refId, contenedorId);
}

function abrirLightbox(src, tipo, desc) {
  const lb = document.getElementById('mediaLightbox');
  const cont = document.getElementById('mediaLightboxContent');
  cont.innerHTML = tipo==='video'
    ? `<video src="${src}" controls autoplay style="max-width:90vw;max-height:85vh;border-radius:8px;"></video>`
    : `<img src="${src}" style="max-width:90vw;max-height:85vh;border-radius:8px;">`;
  document.getElementById('mediaLightboxDesc').textContent = desc||'';
  lb.style.display = 'flex';
}

function cerrarLightbox(e) {
  if (e.target.id==='mediaLightbox') {
    document.getElementById('mediaLightbox').style.display='none';
    document.getElementById('mediaLightboxContent').innerHTML='';
  }
}

function mediaSubirClick(tipoRef, refId, contenedorId) {
  const input = document.createElement('input');
  input.type = 'file';
  input.accept = 'image/*,video/*';
  input.multiple = true;
  input.onchange = async () => {
    const files = Array.from(input.files);
    if (!files.length) return;
    const desc = prompt('Descripción (opcional):', '') || '';
    const cont = document.getElementById(contenedorId);
    const btn = cont.previousElementSibling;
    if (btn) btn.textContent = '⏳ Subiendo...';
    for (const file of files) {
      const fd = new FormData();
      fd.append('archivo', file);
      fd.append('tipo_ref', tipoRef);
      fd.append('ref_id', refId);
      fd.append('descripcion', desc);
      await fetch('api/media.php', {
        method: 'POST',
        headers: {'X-Auth-Token': getStoredToken()},
        body: fd
      });
    }
    if (btn) btn.textContent = '📎 Agregar fotos/videos';
    mediaCargar(tipoRef, refId, contenedorId);
  };
  input.click();
}

function getStoredToken() {
  try { return JSON.parse(localStorage.getItem('tallerpro_session')||'{}').token||''; } catch { return ''; }
}
</script>
  <!-- MEDIA LIGHTBOX -->
  <div class="media-lightbox" id="mediaLightbox" style="display:none;" onclick="cerrarLightbox(event)">
    <button class="media-lightbox-close" onclick="document.getElementById('mediaLightbox').style.display='none'">✕</button>
    <div id="mediaLightboxContent"></div>
    <div class="media-lightbox-desc" id="mediaLightboxDesc"></div>
  </div>
</body>
</html>
