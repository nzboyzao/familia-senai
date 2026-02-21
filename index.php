<?php
require_once __DIR__ . '/includes/config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>FamilyHub · Sistema Familiar Completo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        /* ===== PALETA FAMILYHUB ===== */
        /* --verde-escuro: #1a3a2a  --verde-medio: #2d5a3d  --oliva: #8a9a3a  --laranja: #c8741a  --dourado: #e8a830  --creme: #f5f0e8  --creme-escuro: #ede5d0 */
        *{margin:0;padding:0;box-sizing:border-box;font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif}
        body{background-color:#1a3a2a;min-height:100vh;display:flex;position:relative;overflow:hidden}
        #particles-canvas{position:fixed;top:0;left:0;width:100%;height:100%;z-index:0;pointer-events:none}
        #auth-container{width:100%;display:flex;justify-content:center;align-items:center;min-height:100vh;position:relative;z-index:10}
        #auth-app{width:100%;max-width:480px;margin:20px;position:relative;z-index:10;background:rgba(245,240,232,.07);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-radius:32px;box-shadow:0 20px 40px rgba(0,0,0,.5);border:1px solid rgba(200,116,26,.25);overflow:hidden}
        #splash-screen{display:flex;flex-direction:column;justify-content:center;align-items:center;min-height:600px;padding:40px 20px;background:transparent;transition:opacity .8s ease}
        .logo-container{text-align:center;animation:fadeIn 1.5s ease}
        @keyframes fadeIn{0%{opacity:0;transform:scale(.95)}100%{opacity:1;transform:scale(1)}}
        .logo-image{width:140px;height:140px;background:linear-gradient(145deg,#2d5a3d,#1a3a2a);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;border:3px solid #c8741a;box-shadow:0 10px 30px rgba(200,116,26,.35)}
        .logo-image svg{width:80px;height:80px;fill:#c8741a}
        .logo-text{font-size:2.8rem;font-weight:700;letter-spacing:-.5px;color:#f5f0e8;margin-bottom:8px;text-shadow:0 2px 10px rgba(200,116,26,.4)}
        .splash-sub{color:#e8a830;font-size:1rem;font-weight:500;letter-spacing:2px;text-transform:uppercase}
        .screen{display:none;padding:32px 28px 40px;opacity:0;transform:translateY(15px);transition:opacity .6s ease,transform .6s ease}
        .screen.active{display:block;opacity:1;transform:translateY(0)}
        .screen-title{color:#f5f0e8;font-size:2rem;font-weight:700;text-align:center;margin-bottom:6px;letter-spacing:-.5px}
        .screen-sub{text-align:center;color:#e8a830;margin-bottom:32px;font-size:.95rem;border-bottom:1px solid rgba(200,116,26,.25);padding-bottom:16px}
        .input-group{margin-bottom:24px;position:relative}
        .input-group label{display:block;color:#ede5d0;margin-bottom:8px;font-weight:500;font-size:.9rem;letter-spacing:.3px}
        .input-group input,.input-group select{width:100%;background:rgba(26,58,42,.7);border:1.5px solid rgba(200,116,26,.35);border-radius:16px;padding:14px 18px;color:#f5f0e8;font-size:1rem;outline:none;transition:all .2s}
        .input-group select option{background:#1a3a2a;color:#f5f0e8}
        .input-group input:focus,.input-group select:focus{border-color:#c8741a;background:rgba(45,90,61,.7);box-shadow:0 0 0 4px rgba(200,116,26,.15)}
        .input-group input::placeholder{color:#8a9a6a;font-weight:400}
        .register-options{display:flex;gap:15px;margin-bottom:25px}
        .register-option{flex:1;background:rgba(26,58,42,.7);border:2px solid rgba(200,116,26,.3);border-radius:20px;padding:20px 15px;text-align:center;cursor:pointer;transition:all .2s}
        .register-option:hover{border-color:#c8741a;background:rgba(45,90,61,.8);transform:translateY(-2px)}
        .register-option i{font-size:2rem;color:#c8741a;margin-bottom:10px;display:block}
        .register-option h3{color:#f5f0e8;font-size:1.1rem;margin-bottom:5px}
        .register-option p{color:#8a9a6a;font-size:.8rem}
        .info-box{background:rgba(200,116,26,.1);border:1px solid rgba(200,116,26,.3);border-radius:16px;padding:16px;margin-bottom:24px;color:#ede5d0;font-size:.9rem;display:flex;align-items:center;gap:12px}
        .info-box i{color:#e8a830;font-size:1.3rem}
        .info-box strong{color:#e8a830}
        .photo-upload-container{display:flex;flex-direction:column;align-items:center;margin-bottom:10px}
        .photo-preview-circle{width:120px;height:120px;border-radius:50%;background:rgba(26,58,42,.8);border:3px dashed #c8741a;display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;overflow:hidden;position:relative;transition:all .2s;margin-bottom:12px}
        .photo-preview-circle:hover{border-color:#e8a830;background:rgba(45,90,61,.9);transform:scale(1.02)}
        .photo-preview-circle.has-image{border:3px solid #c8741a}
        .photo-preview-circle .preview-img{width:100%;height:100%;object-fit:cover;display:none}
        .photo-preview-circle .placeholder-icon{color:#c8741a;font-size:2.2rem;line-height:1;margin-bottom:4px;opacity:.8}
        .photo-preview-circle .placeholder-text{color:#8a9a6a;font-size:.75rem;font-weight:500}
        .photo-preview-circle.has-image .placeholder-icon,.photo-preview-circle.has-image .placeholder-text{display:none}
        .photo-preview-circle.has-image .preview-img{display:block}
        #foto-familia,#foto-membro,#foto-membro-edit{display:none}
        .file-name-small{color:#8a9a6a;font-size:.8rem;margin-top:4px;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
        .btn-primary{width:100%;background:linear-gradient(135deg,#c8741a,#e8a830);color:#fff;border:none;border-radius:16px;padding:16px 20px;font-size:1.1rem;font-weight:700;cursor:pointer;transition:all .2s;margin:20px 0 16px;box-shadow:0 8px 20px rgba(200,116,26,.4)}
        .btn-primary:hover{background:linear-gradient(135deg,#e8a830,#c8741a);transform:translateY(-2px);box-shadow:0 12px 28px rgba(200,116,26,.5)}
        .toggle-link{text-align:center;color:#8a9a6a;font-size:.9rem}
        .toggle-link a{color:#e8a830;text-decoration:none;font-weight:600;margin-left:5px;cursor:pointer}
        .toggle-link a:hover{text-decoration:underline;color:#c8741a}
        #dashboard-container{display:none;width:100%;height:100vh;position:relative;z-index:10;backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px)}
        .dashboard-container{display:flex;width:100%;height:100vh;position:relative;z-index:10}
        .sidebar{width:280px;background:rgba(15,35,22,.92);backdrop-filter:blur(12px);border-right:1px solid rgba(200,116,26,.2);padding:30px 20px;display:flex;flex-direction:column;height:100vh;position:relative;z-index:20;transition:all .3s;overflow-y:auto}
        .logo-sidebar{display:flex;align-items:center;gap:12px;margin-bottom:40px;padding-left:10px}
        .logo-sidebar svg{width:40px;height:40px;fill:#c8741a}
        .logo-sidebar span{font-size:1.5rem;font-weight:700;color:#f5f0e8;letter-spacing:-.5px}
        .menu-items{flex:1;display:flex;flex-direction:column;gap:8px;margin-bottom:20px}
        .menu-item{display:flex;align-items:center;gap:14px;padding:14px 16px;border-radius:14px;color:#8a9a6a;transition:all .2s;cursor:pointer;font-weight:500}
        .menu-item:hover{background:rgba(200,116,26,.12);color:#f5f0e8}
        .menu-item.active{background:rgba(200,116,26,.18);color:#e8a830;border-left:3px solid #c8741a}
        .menu-item i{font-size:1.3rem;width:24px;text-align:center}
        .menu-badge{background:#c8741a;color:#fff;font-size:.7rem;padding:2px 6px;border-radius:20px;margin-left:auto}
        .user-info-sidebar{display:flex;align-items:center;gap:12px;padding:16px 10px;border-top:1px solid rgba(200,116,26,.2);margin-top:auto}
        .user-avatar{width:48px;height:48px;border-radius:50%;background:linear-gradient(145deg,#2d5a3d,#1a3a2a);border:2px solid #c8741a;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0}
        .user-avatar img{width:100%;height:100%;object-fit:cover}
        .user-avatar span{color:#e8a830;font-size:1.5rem}
        .user-details{flex:1;overflow:hidden}
        .user-name{color:#f5f0e8;font-weight:600;font-size:.95rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .user-family{color:#8a9a6a;font-size:.8rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .main-content{flex:1;padding:30px 35px;overflow-y:auto;height:100vh;position:relative;z-index:15}
        .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:30px}
        .page-title h1{color:#f5f0e8;font-size:2rem;font-weight:700;letter-spacing:-.5px}
        .page-title p{color:#8a9a6a;font-size:.95rem;margin-top:5px}
        .header-actions{display:flex;gap:15px}
        .btn-icon{background:rgba(15,35,22,.6);border:1.5px solid rgba(200,116,26,.3);border-radius:14px;padding:12px 18px;color:#f5f0e8;cursor:pointer;transition:all .2s;font-size:.9rem;display:flex;align-items:center;gap:8px}
        .btn-icon:hover{border-color:#c8741a;background:rgba(200,116,26,.12)}
        .btn-icon.danger{border-color:#ff6b6b;color:#ff6b6b}
        .btn-icon.danger:hover{background:rgba(255,107,107,.1)}
        .cards-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:30px}
        .status-card{background:rgba(15,35,22,.6);backdrop-filter:blur(5px);border:1px solid rgba(200,116,26,.15);border-radius:24px;padding:25px;transition:all .2s}
        .status-card:hover{transform:translateY(-3px);border-color:rgba(200,116,26,.4)}
        .card-header{display:flex;align-items:center;gap:12px;margin-bottom:15px}
        .card-icon{width:45px;height:45px;background:rgba(200,116,26,.15);border-radius:14px;display:flex;align-items:center;justify-content:center;color:#e8a830;font-size:1.3rem}
        .card-title{color:#8a9a6a;font-size:.9rem;font-weight:500}
        .card-value{font-size:2.2rem;font-weight:700;color:#f5f0e8;margin-bottom:8px}
        .card-sub{font-size:.85rem;color:#8a9a6a}
        .trend-up{color:#51cf66}
        .tasks-section{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:25px}
        .tasks-container{background:rgba(15,35,22,.6);backdrop-filter:blur(5px);border:1px solid rgba(200,116,26,.15);border-radius:24px;padding:25px;margin-bottom:25px}
        .section-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
        .section-header h2{color:#f5f0e8;font-size:1.3rem;font-weight:600}
        .btn-add{background:linear-gradient(135deg,#c8741a,#e8a830);color:#fff;border:none;border-radius:12px;padding:10px 18px;cursor:pointer;transition:all .2s;font-weight:600;font-size:.9rem;display:flex;align-items:center;gap:8px}
        .btn-add:hover{background:linear-gradient(135deg,#e8a830,#c8741a);transform:translateY(-1px);box-shadow:0 5px 15px rgba(200,116,26,.3)}
        .task-list{display:flex;flex-direction:column;gap:12px;max-height:400px;overflow-y:auto}
        .task-item{background:rgba(26,58,42,.5);border:1px solid rgba(200,116,26,.1);border-radius:16px;padding:15px;display:flex;align-items:center;justify-content:space-between;transition:all .2s}
        .task-item:hover{border-color:#c8741a;background:rgba(45,90,61,.5)}
        .task-info{display:flex;align-items:center;gap:15px;flex:1}
        .task-check{width:24px;height:24px;border:2px solid #c8741a;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#c8741a;font-size:1.1rem;transition:all .2s;flex-shrink:0}
        .task-check.completed{background:#c8741a;color:#fff}
        .task-details{flex:1}
        .task-title{color:#f5f0e8;font-weight:600;margin-bottom:4px;display:flex;align-items:center;gap:8px;flex-wrap:wrap}
        .task-points{font-size:.7rem;background:rgba(232,168,48,.2);padding:2px 6px;border-radius:12px;color:#e8a830}
        .task-meta{display:flex;align-items:center;gap:12px;color:#8a9a6a;font-size:.8rem;flex-wrap:wrap}
        .task-meta span{display:flex;align-items:center;gap:4px}
        .task-actions{display:flex;gap:8px}
        .task-btn{background:transparent;border:none;color:#8a9a6a;font-size:1.1rem;cursor:pointer;padding:5px;border-radius:8px;transition:all .2s}
        .task-btn:hover{color:#e8a830;background:rgba(232,168,48,.1)}
        .task-btn.delete:hover{color:#ff6b6b}
        .task-filters{display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap}
        .filter-btn{background:rgba(26,58,42,.5);border:1px solid rgba(200,116,26,.2);border-radius:20px;padding:8px 18px;color:#8a9a6a;cursor:pointer;transition:all .2s;font-size:.9rem}
        .filter-btn:hover,.filter-btn.active{background:rgba(200,116,26,.15);border-color:#c8741a;color:#e8a830}
        .task-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:15px;margin-top:20px}
        .stat-item{background:rgba(26,58,42,.4);border-radius:16px;padding:15px;text-align:center}
        .stat-value{font-size:1.8rem;font-weight:700;color:#e8a830}
        .stat-label{color:#8a9a6a;font-size:.8rem}
        .priority-alta{color:#ff6b6b;font-size:.7rem;padding:2px 8px;background:rgba(255,107,107,.15);border-radius:12px}
        .priority-media{color:#ffc107;font-size:.7rem;padding:2px 8px;background:rgba(255,193,7,.15);border-radius:12px}
        .priority-baixa{color:#51cf66;font-size:.7rem;padding:2px 8px;background:rgba(81,207,102,.15);border-radius:12px}
        .calendar-container{background:rgba(15,35,22,.6);backdrop-filter:blur(5px);border:1px solid rgba(200,116,26,.15);border-radius:24px;padding:25px}
        .calendar-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
        .calendar-month{color:#f5f0e8;font-weight:700;font-size:1.2rem}
        .calendar-nav{display:flex;gap:10px}
        .calendar-nav button{background:rgba(26,58,42,.8);border:1px solid rgba(200,116,26,.2);border-radius:10px;padding:8px 12px;color:#f5f0e8;cursor:pointer;transition:all .2s}
        .calendar-nav button:hover{border-color:#c8741a;background:rgba(200,116,26,.12)}
        .calendar-weekdays{display:grid;grid-template-columns:repeat(7,1fr);gap:5px;margin-bottom:10px;color:#8a9a6a;font-size:.85rem;text-align:center;font-weight:600}
        .calendar-days{display:grid;grid-template-columns:repeat(7,1fr);gap:5px}
        .calendar-day{aspect-ratio:1;display:flex;flex-direction:column;align-items:center;justify-content:center;border-radius:16px;color:#f5f0e8;font-size:.9rem;cursor:pointer;transition:all .2s;background:rgba(26,58,42,.4);position:relative;padding:5px}
        .calendar-day:hover{background:rgba(200,116,26,.2);transform:scale(1.05)}
        .calendar-day.has-task{background:rgba(200,116,26,.25);border:1px solid #c8741a;font-weight:600}
        .calendar-day.has-task::after{content:'';position:absolute;top:5px;right:5px;width:6px;height:6px;background:#e8a830;border-radius:50%}
        .calendar-day.today{border:2px solid #c8741a;background:rgba(200,116,26,.15)}
        .calendar-day.selected{background:rgba(200,116,26,.35);border:2px solid #c8741a;transform:scale(1.05)}
        .calendar-day .day-number{font-size:1rem;font-weight:600}
        .calendar-day .task-count{font-size:.65rem;color:#e8a830}
        .calendar-day.empty{background:transparent;cursor:default}
        .calendar-day.empty:hover{transform:none}
        .day-details-container{background:rgba(15,35,22,.6);backdrop-filter:blur(5px);border:1px solid rgba(200,116,26,.15);border-radius:24px;padding:25px;margin-top:20px}
        .day-details-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
        .day-details-header h3{color:#f5f0e8;font-size:1.2rem}
        .day-details-date{color:#e8a830;font-weight:600}
        .day-tasks-list{display:flex;flex-direction:column;gap:12px;max-height:300px;overflow-y:auto}
        .day-task-item{background:rgba(26,58,42,.6);border:1px solid rgba(200,116,26,.1);border-radius:16px;padding:15px;display:flex;align-items:center;gap:15px}
        .day-task-check{width:24px;height:24px;border:2px solid #c8741a;border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#c8741a}
        .day-task-check.completed{background:#c8741a;color:#fff}
        .day-task-info{flex:1}
        .day-task-title{color:#f5f0e8;font-weight:600;margin-bottom:4px}
        .day-task-meta{display:flex;gap:12px;color:#8a9a6a;font-size:.75rem}
        .empty-day{text-align:center;padding:40px;color:#8a9a6a}
        .empty-day i{font-size:3rem;margin-bottom:15px;opacity:.5;display:block}
        .recent-activities{background:rgba(15,35,22,.6);backdrop-filter:blur(5px);border:1px solid rgba(200,116,26,.15);border-radius:24px;padding:25px;margin-top:25px}
        .activity-list{display:flex;flex-direction:column;gap:15px}
        .activity-item{display:flex;align-items:center;gap:15px;padding:10px 0;border-bottom:1px solid rgba(200,116,26,.1)}
        .activity-icon{width:40px;height:40px;background:rgba(200,116,26,.15);border-radius:12px;display:flex;align-items:center;justify-content:center;color:#e8a830;flex-shrink:0}
        .activity-details{flex:1}
        .activity-text{color:#f5f0e8;margin-bottom:4px}
        .activity-time{color:#6a7a5a;font-size:.75rem}
        .members-container{background:rgba(15,35,22,.6);backdrop-filter:blur(5px);border:1px solid rgba(200,116,26,.15);border-radius:24px;padding:25px}
        .members-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px}
        .members-header h2{color:#f5f0e8;font-size:1.3rem;font-weight:600}
        .members-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px}
        .member-card{background:rgba(26,58,42,.5);border:1px solid rgba(200,116,26,.1);border-radius:20px;padding:20px;text-align:center;transition:all .2s}
        .member-card:hover{border-color:#c8741a;transform:translateY(-3px)}
        .member-avatar{width:70px;height:70px;border-radius:50%;background:linear-gradient(145deg,#2d5a3d,#1a3a2a);border:2px solid #c8741a;display:flex;align-items:center;justify-content:center;overflow:hidden;margin:0 auto 15px;font-size:1.8rem}
        .member-avatar img{width:100%;height:100%;object-fit:cover}
        .member-name{color:#f5f0e8;font-size:1.1rem;font-weight:600;margin-bottom:5px}
        .member-role{color:#8a9a6a;font-size:.8rem;margin-bottom:10px}
        .member-points{color:#e8a830;font-weight:600;font-size:.9rem;margin-bottom:15px}
        .member-actions{display:flex;gap:8px;justify-content:center}
        .member-btn{background:rgba(200,116,26,.1);border:1px solid rgba(200,116,26,.3);border-radius:10px;padding:8px 14px;color:#e8a830;cursor:pointer;transition:all .2s;font-size:.85rem}
        .member-btn:hover{background:rgba(200,116,26,.25)}
        .member-btn.danger{border-color:#ff6b6b;color:#ff6b6b;background:rgba(255,107,107,.1)}
        .member-btn.danger:hover{background:rgba(255,107,107,.2)}
        .chat-container{background:rgba(15,35,22,.6);backdrop-filter:blur(5px);border:1px solid rgba(200,116,26,.15);border-radius:24px;padding:25px;display:flex;flex-direction:column;height:calc(100vh - 160px)}
        .chat-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-shrink:0}
        .chat-header h2{color:#f5f0e8;font-size:1.3rem;font-weight:600}
        .chat-messages{flex:1;overflow-y:auto;display:flex;flex-direction:column;gap:15px;padding-right:5px}
        .message{display:flex;gap:10px}
        .message.mine{flex-direction:row-reverse}
        .msg-avatar{width:36px;height:36px;border-radius:50%;background:linear-gradient(145deg,#2d5a3d,#1a3a2a);border:2px solid #c8741a;display:flex;align-items:center;justify-content:center;font-size:1rem;overflow:hidden;flex-shrink:0}
        .msg-avatar img{width:100%;height:100%;object-fit:cover}
        .msg-bubble{max-width:70%;background:rgba(26,58,42,.8);border:1px solid rgba(200,116,26,.15);border-radius:18px;padding:12px 16px}
        .mine .msg-bubble{background:rgba(200,116,26,.2);border-color:rgba(200,116,26,.35)}
        .msg-author{font-size:.75rem;color:#e8a830;font-weight:600;margin-bottom:5px}
        .mine .msg-author{text-align:right}
        .msg-text{color:#f5f0e8;font-size:.95rem;line-height:1.5}
        .msg-time{font-size:.7rem;color:#6a7a5a;margin-top:6px}
        .mine .msg-time{text-align:right}
        .chat-input-area{display:flex;gap:12px;margin-top:20px;flex-shrink:0}
        .chat-input{flex:1;background:rgba(26,58,42,.6);border:1.5px solid rgba(200,116,26,.3);border-radius:16px;padding:14px 18px;color:#f5f0e8;font-size:.95rem;resize:none;min-height:50px;max-height:120px;outline:none;transition:all .2s}
        .chat-input:focus{border-color:#c8741a;background:rgba(45,90,61,.6)}
        .chat-input::placeholder{color:#6a7a5a}
        .chat-send-btn{background:linear-gradient(135deg,#c8741a,#e8a830);color:#fff;border:none;border-radius:16px;padding:14px 22px;cursor:pointer;transition:all .2s;font-weight:700;font-size:.95rem;white-space:nowrap}
        .chat-send-btn:hover{background:linear-gradient(135deg,#e8a830,#c8741a);box-shadow:0 5px 15px rgba(200,116,26,.3)}
        .gallery-container{background:rgba(15,35,22,.6);backdrop-filter:blur(5px);border:1px solid rgba(200,116,26,.15);border-radius:24px;padding:25px;margin-bottom:30px}
        .gallery-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px}
        .gallery-header h2{color:#f5f0e8;font-size:1.3rem;font-weight:600}
        .gallery-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:15px}
        .gallery-item{background:rgba(26,58,42,.5);border:1px solid rgba(200,116,26,.1);border-radius:16px;overflow:hidden;cursor:pointer;transition:all .2s;position:relative}
        .gallery-item:hover{border-color:#c8741a;transform:scale(1.02)}
        .gallery-item img{width:100%;height:150px;object-fit:cover;display:block}
        .gallery-item .gallery-overlay{position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,.8));padding:10px;display:flex;justify-content:space-between;align-items:flex-end}
        .gallery-item-title{color:#f5f0e8;font-size:.8rem;font-weight:600}
        .gallery-item-actions{display:flex;gap:5px}
        .gallery-action-btn{background:rgba(255,255,255,.2);border:none;border-radius:8px;padding:5px 8px;color:#fff;cursor:pointer;font-size:.8rem;transition:all .2s}
        .gallery-action-btn:hover{background:rgba(255,255,255,.35)}
        .financas-container{background:rgba(15,35,22,.6);backdrop-filter:blur(5px);border:1px solid rgba(200,116,26,.15);border-radius:24px;padding:25px;margin-bottom:30px}
        .financas-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px}
        .financas-header h2{color:#f5f0e8;font-size:1.3rem;font-weight:600}
        .financas-resumo{display:grid;grid-template-columns:repeat(3,1fr);gap:15px;margin-bottom:30px}
        .resumo-card{background:rgba(26,58,42,.5);border:1px solid rgba(200,116,26,.1);border-radius:16px;padding:20px;text-align:center}
        .resumo-label{color:#8a9a6a;font-size:.8rem;margin-bottom:8px}
        .resumo-valor{font-size:1.4rem;font-weight:700;color:#f5f0e8}
        .resumo-valor.positivo{color:#51cf66}
        .resumo-valor.negativo{color:#ff6b6b}
        .transacoes-list{display:flex;flex-direction:column;gap:12px}
        .transacao-item{background:rgba(26,58,42,.5);border:1px solid rgba(200,116,26,.1);border-radius:16px;padding:15px;display:flex;align-items:center;gap:15px}
        .transacao-icon{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center}
        .transacao-icon.receita{background:rgba(81,207,102,.15);color:#51cf66}
        .transacao-icon.despesa{background:rgba(255,107,107,.15);color:#ff6b6b}
        .transacao-info{flex:1}
        .transacao-descricao{color:#f5f0e8;font-weight:500;margin-bottom:3px}
        .transacao-data{color:#8a9a6a;font-size:.7rem}
        .transacao-valor{font-weight:700}
        .transacao-valor.despesa{color:#ff6b6b}
        .transacao-valor.receita{color:#51cf66}
        .lembretes-container{background:rgba(15,35,22,.6);backdrop-filter:blur(5px);border:1px solid rgba(200,116,26,.15);border-radius:24px;padding:25px;margin-bottom:30px}
        .lembretes-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px}
        .lembretes-header h2{color:#f5f0e8;font-size:1.3rem;font-weight:600}
        .lembretes-list{display:flex;flex-direction:column;gap:12px}
        .lembrete-item{background:rgba(26,58,42,.5);border:1px solid rgba(200,116,26,.1);border-radius:16px;padding:15px;display:flex;align-items:center;gap:12px;border-left-width:4px}
        .lembrete-info{flex:1}
        .lembrete-titulo{color:#f5f0e8;font-weight:600;margin-bottom:4px}
        .lembrete-meta{display:flex;align-items:center;gap:12px;color:#8a9a6a;font-size:.75rem}
        .documentos-container{background:rgba(15,35,22,.6);backdrop-filter:blur(5px);border:1px solid rgba(200,116,26,.15);border-radius:24px;padding:25px;margin-bottom:30px}
        .documentos-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px}
        .documentos-header h2{color:#f5f0e8;font-size:1.3rem;font-weight:600}
        .documentos-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:15px}
        .documento-item{background:rgba(26,58,42,.5);border:1px solid rgba(200,116,26,.1);border-radius:16px;padding:15px;display:flex;align-items:center;gap:12px;transition:all .2s;cursor:pointer}
        .documento-item:hover{border-color:#c8741a;background:rgba(45,90,61,.5)}
        .documento-icon{width:45px;height:45px;background:rgba(200,116,26,.12);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#e8a830;flex-shrink:0}
        .documento-info{flex:1;overflow:hidden}
        .documento-nome{color:#f5f0e8;font-weight:600;font-size:.9rem;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .documento-meta{color:#8a9a6a;font-size:.7rem}
        .ranking-container{background:rgba(15,35,22,.6);backdrop-filter:blur(5px);border:1px solid rgba(200,116,26,.15);border-radius:24px;padding:25px;margin-bottom:30px}
        .ranking-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px}
        .ranking-header h2{color:#f5f0e8;font-size:1.3rem;font-weight:600}
        .ranking-list{display:flex;flex-direction:column;gap:12px}
        .ranking-item{background:rgba(26,58,42,.5);border:1px solid rgba(200,116,26,.1);border-radius:16px;padding:15px 20px;display:flex;align-items:center;gap:15px}
        .ranking-pos{font-size:1.5rem;font-weight:700;color:#8a9a6a;width:40px;text-align:center}
        .ranking-pos.gold{color:#ffd700}
        .ranking-pos.silver{color:#c0c0c0}
        .ranking-pos.bronze{color:#cd7f32}
        .ranking-avatar{width:50px;height:50px;border-radius:50%;background:linear-gradient(145deg,#2d5a3d,#1a3a2a);border:2px solid #c8741a;display:flex;align-items:center;justify-content:center;overflow:hidden;font-size:1.3rem}
        .ranking-avatar img{width:100%;height:100%;object-fit:cover}
        .ranking-info{flex:1}
        .ranking-nome{color:#f5f0e8;font-weight:600;margin-bottom:3px}
        .ranking-nivel{color:#8a9a6a;font-size:.8rem}
        .ranking-pts{text-align:right}
        .ranking-pts-val{color:#e8a830;font-size:1.2rem;font-weight:700}
        .ranking-pts-label{color:#8a9a6a;font-size:.7rem}
        .points-badge{background:rgba(200,116,26,.15);border:1px solid rgba(200,116,26,.3);border-radius:20px;padding:8px 14px;color:#e8a830;font-size:.85rem;display:flex;align-items:center;gap:8px}
        .config-container{background:rgba(15,35,22,.6);backdrop-filter:blur(5px);border:1px solid rgba(200,116,26,.15);border-radius:24px;padding:30px}
        .config-header{margin-bottom:30px}
        .config-header h2{color:#f5f0e8;font-size:1.8rem;margin-bottom:5px}
        .config-header p{color:#8a9a6a}
        .config-menu{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:30px;border-bottom:1px solid rgba(200,116,26,.2);padding-bottom:20px}
        .config-menu-item{background:rgba(26,58,42,.5);border:1px solid rgba(200,116,26,.2);border-radius:30px;padding:10px 20px;color:#8a9a6a;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:8px;font-size:.95rem}
        .config-menu-item:hover{background:rgba(200,116,26,.12);border-color:#c8741a;color:#f5f0e8}
        .config-menu-item.active{background:linear-gradient(135deg,#c8741a,#e8a830);border-color:#c8741a;color:#fff}
        .config-content{min-height:500px}
        .config-pane{display:none}
        .config-pane.active{display:block}
        .config-pane h3{color:#f5f0e8;margin-bottom:20px;font-size:1.3rem}
        .config-card{background:rgba(26,58,42,.4);border:1px solid rgba(200,116,26,.1);border-radius:20px;padding:20px;margin-bottom:20px}
        .config-card-header{display:flex;align-items:center;gap:12px;margin-bottom:15px}
        .config-card-header i{color:#e8a830;font-size:1.3rem}
        .config-card-header h4{color:#f5f0e8;font-size:1.1rem}
        .config-card-body{padding-left:35px}
        .config-field{margin-bottom:15px}
        .config-field label{display:block;color:#8a9a6a;margin-bottom:5px;font-size:.9rem}
        .config-field-row{display:flex;gap:10px}
        .config-field input,.config-select{flex:1;background:rgba(15,35,22,.8);border:1.5px solid rgba(200,116,26,.3);border-radius:12px;padding:12px 15px;color:#f5f0e8;font-size:.95rem;outline:none;transition:all .2s}
        .config-field input:focus,.config-select:focus{border-color:#c8741a}
        .config-field-value{background:rgba(15,35,22,.6);border-radius:12px;padding:12px 15px;color:#f5f0e8}
        .config-btn{background:rgba(26,58,42,.8);border:1.5px solid rgba(200,116,26,.3);border-radius:12px;padding:10px 18px;color:#f5f0e8;cursor:pointer;transition:all .2s;display:inline-flex;align-items:center;gap:8px;font-size:.9rem;margin-top:5px;margin-right:5px}
        .config-btn:hover{border-color:#c8741a;background:rgba(200,116,26,.12)}
        .config-btn-primary{background:linear-gradient(135deg,#c8741a,#e8a830);border-color:#c8741a;color:#fff}
        .config-btn-primary:hover{background:linear-gradient(135deg,#e8a830,#c8741a)}
        .config-btn-danger{border-color:#ff6b6b;color:#ff6b6b}
        .config-btn-danger:hover{background:rgba(255,107,107,.1)}
        .config-btn-warning{border-color:#ffc107;color:#ffc107}
        .family-photo-edit{display:flex;align-items:center;gap:30px;flex-wrap:wrap}
        .current-family-photo{width:100px;height:100px;border-radius:50%;background:linear-gradient(145deg,#2d5a3d,#1a3a2a);border:3px solid #c8741a;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0}
        .current-family-photo img{width:100%;height:100%;object-fit:cover}
        .current-family-photo .photo-placeholder{font-size:3rem}
        .photo-edit-actions{display:flex;gap:10px;flex-wrap:wrap}
        .auditoria-filtros{display:flex;gap:15px;margin-bottom:20px;align-items:center;flex-wrap:wrap}
        .auditoria-filtros select{background:rgba(15,35,22,.8);border:1.5px solid rgba(200,116,26,.3);border-radius:12px;padding:10px 15px;color:#f5f0e8;cursor:pointer;outline:none}
        .modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.75);z-index:1000;justify-content:center;align-items:center;padding:20px}
        .modal-content{background:#0f2218;border:1px solid rgba(200,116,26,.35);border-radius:24px;padding:30px;width:100%;max-width:500px;max-height:90vh;overflow-y:auto;position:relative}
        .modal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px}
        .modal-header h3{color:#f5f0e8;font-size:1.3rem;font-weight:600}
        .modal-close{background:none;border:none;color:#8a9a6a;font-size:1.5rem;cursor:pointer;padding:5px;border-radius:8px;transition:all .2s;line-height:1}
        .modal-close:hover{color:#f5f0e8;background:rgba(255,255,255,.1)}
        .modal-input-group{margin-bottom:20px}
        .modal-input-group label{display:block;color:#ede5d0;margin-bottom:8px;font-weight:500;font-size:.9rem}
        .modal-input-group input,.modal-input-group select,.modal-input-group textarea{width:100%;background:rgba(26,58,42,.6);border:1.5px solid rgba(200,116,26,.3);border-radius:12px;padding:12px 16px;color:#f5f0e8;font-size:.95rem;outline:none;transition:all .2s;resize:vertical}
        .modal-input-group input:focus,.modal-input-group select:focus,.modal-input-group textarea:focus{border-color:#c8741a;background:rgba(45,90,61,.6)}
        .modal-input-group input::placeholder,.modal-input-group textarea::placeholder{color:#6a7a5a}
        .modal-input-group select option{background:#1a3a2a;color:#f5f0e8}
        .modal-btn-row{display:flex;gap:12px;margin-top:25px}
        .modal-btn{flex:1;padding:14px;border-radius:14px;cursor:pointer;font-size:1rem;font-weight:600;border:none;transition:all .2s}
        .modal-btn-primary{background:linear-gradient(135deg,#c8741a,#e8a830);color:#fff}
        .modal-btn-primary:hover{background:linear-gradient(135deg,#e8a830,#c8741a);box-shadow:0 5px 15px rgba(200,116,26,.3)}
        .modal-btn-secondary{background:rgba(26,58,42,.8);border:1.5px solid rgba(200,116,26,.3);color:#f5f0e8}
        .modal-btn-secondary:hover{border-color:#c8741a}
        .empty-state{text-align:center;padding:60px 20px;color:#8a9a6a}
        .empty-state i{font-size:3rem;margin-bottom:15px;opacity:.5;display:block}
        .empty-state p{font-size:1rem}
        #customAlert{display:none;position:fixed;top:20px;right:20px;z-index:9999;background:rgba(15,35,22,.97);border:1px solid rgba(200,116,26,.35);border-radius:16px;padding:15px 20px;flex-direction:row;align-items:center;gap:12px;max-width:350px;box-shadow:0 10px 30px rgba(0,0,0,.5)}
        #customAlert.error{border-color:#ff6b6b}
        #customAlert.success{border-color:#51cf66}
        #alertMessage{color:#f5f0e8;font-size:.95rem}
        .nivel-badge{background:rgba(232,168,48,.15);color:#e8a830;border-radius:10px;padding:3px 8px;font-size:.75rem;font-weight:600}
        @media(max-width:1200px){.cards-grid{grid-template-columns:repeat(2,1fr)}.tasks-section{grid-template-columns:1fr}.financas-resumo{grid-template-columns:1fr}.task-stats{grid-template-columns:1fr}}
        @media(max-width:768px){.sidebar{width:80px;padding:20px 10px}.logo-sidebar span,.menu-item span,.user-details{display:none}.menu-item{justify-content:center;padding:14px}.user-info-sidebar{justify-content:center}.main-content{padding:20px 15px}.cards-grid{grid-template-columns:1fr}.gallery-grid{grid-template-columns:repeat(2,1fr)}.documentos-grid{grid-template-columns:repeat(2,1fr)}.family-photo-edit{flex-direction:column;align-items:flex-start}.config-field-row{flex-direction:column}}
    </style>
</head>
<body>

<canvas id="particles-canvas"></canvas>

<!-- TELAS DE AUTENTICAÇÃO -->
<div id="auth-container">
    <div id="auth-app">
        <!-- SPLASH SCREEN -->
        <div id="splash-screen">
            <div class="logo-container">
                <div class="logo-image">
                    <svg viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#4f9fff" stroke-width="2.5"/>
                        <path d="M30 45 L45 65 L70 35" stroke="#4f9fff" stroke-width="4" fill="none" stroke-linecap="round"/>
                        <circle cx="50" cy="50" r="8" fill="#4f9fff"/>
                    </svg>
                </div>
                <div class="logo-text">FamilyHub</div>
                <div class="splash-sub">conexão que une</div>
            </div>
        </div>

        <!-- TELA DE LOGIN -->
        <div id="login-screen" class="screen">
            <h1 class="screen-title">Bem-vindo de volta</h1>
            <p class="screen-sub">Faça login na sua conta</p>
            <div class="input-group">
                <label>Usuário</label>
                <input type="text" id="login-usuario" placeholder="Digite seu usuário" maxlength="30">
            </div>
            <div class="input-group">
                <label>Senha</label>
                <input type="password" id="login-senha" placeholder="Digite sua senha" maxlength="50">
            </div>
            <button class="btn-primary" onclick="fazerLogin()">Entrar</button>
            <div class="toggle-link">Não tem cadastro? <a onclick="mostrarTelaAuth('register')">Criar conta</a></div>
        </div>

        <!-- TELA DE CADASTRO (ESCOLHA) -->
        <div id="register-choice-screen" class="screen">
            <h1 class="screen-title">Criar conta</h1>
            <p class="screen-sub">Escolha uma opção</p>
            <div class="register-options">
                <div class="register-option" onclick="escolherCadastro('familia')">
                    <i class="fas fa-home"></i>
                    <h3>Criar Família</h3>
                    <p>Crie uma nova família e seja o administrador</p>
                </div>
                <div class="register-option" onclick="escolherCadastro('membro')">
                    <i class="fas fa-user-plus"></i>
                    <h3>Entrar em Família</h3>
                    <p>Já tem uma família? Cadastre-se como membro</p>
                </div>
            </div>
            <div class="toggle-link">Já tem cadastro? <a onclick="mostrarTelaAuth('login')">Fazer login</a></div>
        </div>

        <!-- TELA DE CADASTRO DE FAMÍLIA -->
        <div id="register-family-screen" class="screen">
            <h1 class="screen-title">Criar Família</h1>
            <p class="screen-sub">Cadastre sua família</p>
            <div class="input-group">
                <label>Foto da família (opcional)</label>
                <div class="photo-upload-container">
                    <div class="photo-preview-circle" id="familyPhotoPreview" onclick="document.getElementById('foto-familia').click()">
                        <div class="placeholder-icon">📸</div>
                        <div class="placeholder-text">Adicionar foto</div>
                        <img class="preview-img" id="familyPreviewImg" src="#" alt="preview">
                    </div>
                    <input type="file" id="foto-familia" accept="image/*" onchange="handlePhotoUpload(event,'familyPhotoPreview','familyPreviewImg','familyFileName')">
                    <span class="file-name-small" id="familyFileName">Nenhum arquivo</span>
                </div>
            </div>
            <div class="input-group"><label>Nome da família *</label><input type="text" id="nome-familia" placeholder="Ex: Família Silva" maxlength="100"></div>
            <div class="input-group"><label>Senha da família *</label><input type="password" id="familia-senha" placeholder="Senha para novos membros entrarem" maxlength="50"></div>
            <div class="input-group"><label>Repetir senha da família *</label><input type="password" id="familia-rep-senha" placeholder="Repita a senha" maxlength="50"></div>
            <p style="color:#4f9fff;font-weight:600;margin-bottom:15px;font-size:.95rem">👤 Dados do Administrador</p>
            <div class="input-group"><label>Seu nome *</label><input type="text" id="admin-nome" placeholder="Seu nome completo" maxlength="100"></div>
            <div class="input-group"><label>Usuário *</label><input type="text" id="admin-usuario" placeholder="Nome de usuário (min. 3 caracteres)" maxlength="30"></div>
            <div class="input-group"><label>Senha *</label><input type="password" id="admin-senha" placeholder="Mínimo 6 caracteres" maxlength="50"></div>
            <div class="input-group"><label>Repetir senha *</label><input type="password" id="admin-rep-senha" placeholder="Repita a senha" maxlength="50"></div>
            <button class="btn-primary" onclick="cadastrarFamilia()">Criar Família</button>
            <div class="toggle-link"><a onclick="voltarEscolhaCadastro()">← Voltar</a></div>
        </div>

        <!-- TELA DE CADASTRO DE MEMBRO -->
        <div id="register-member-screen" class="screen">
            <h1 class="screen-title">Entrar em Família</h1>
            <p class="screen-sub">Cadastre-se como membro</p>
            <div class="info-box"><i class="fas fa-info-circle"></i><span>Solicite ao administrador o <strong>nome da família</strong> e a <strong>senha de acesso</strong>.</span></div>
            <div class="input-group"><label>Foto (opcional)</label>
                <div class="photo-upload-container">
                    <div class="photo-preview-circle" id="memberPhotoPreview" onclick="document.getElementById('foto-membro').click()">
                        <div class="placeholder-icon">📸</div>
                        <div class="placeholder-text">Foto</div>
                        <img class="preview-img" id="memberPreviewImg" src="#" alt="preview">
                    </div>
                    <input type="file" id="foto-membro" accept="image/*" onchange="handlePhotoUpload(event,'memberPhotoPreview','memberPreviewImg','memberFileName')">
                    <span class="file-name-small" id="memberFileName">Nenhum arquivo</span>
                </div>
            </div>
            <div class="input-group"><label>Família *</label>
                <select id="familia-select"><option value="">Carregando...</option></select>
            </div>
            <div class="input-group"><label>Senha da família *</label><input type="password" id="membro-familia-senha" placeholder="Senha fornecida pelo admin" maxlength="50"></div>
            <div class="input-group"><label>Seu nome *</label><input type="text" id="membro-nome" placeholder="Seu nome completo" maxlength="100"></div>
            <div class="input-group"><label>Usuário *</label><input type="text" id="membro-usuario" placeholder="Nome de usuário único" maxlength="30"></div>
            <div class="input-group"><label>Senha *</label><input type="password" id="membro-senha" placeholder="Mínimo 6 caracteres" maxlength="50"></div>
            <button class="btn-primary" onclick="cadastrarMembro()">Criar Conta</button>
            <div class="toggle-link"><a onclick="voltarEscolhaCadastro()">← Voltar</a></div>
        </div>
    </div>
</div>

<!-- DASHBOARD -->
<div id="dashboard-container">
    <div class="dashboard-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="logo-sidebar">
                <svg viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="#4f9fff" stroke-width="2.5"/><path d="M30 45 L45 65 L70 35" stroke="#4f9fff" stroke-width="4" fill="none" stroke-linecap="round"/><circle cx="50" cy="50" r="8" fill="#4f9fff"/></svg>
                <span>FamilyHub</span>
            </div>
            <div class="menu-items">
                <div class="menu-item active" onclick="mostrarTelaDashboard('home')"><i class="fas fa-home"></i><span>Dashboard</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('membros')"><i class="fas fa-users"></i><span>Membros</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('tarefas')"><i class="fas fa-tasks"></i><span>Tarefas</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('chat')"><i class="fas fa-comments"></i><span>Chat</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('galeria')"><i class="fas fa-images"></i><span>Galeria</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('financas')"><i class="fas fa-dollar-sign"></i><span>Finanças</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('lembretes')"><i class="fas fa-bell"></i><span>Lembretes</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('documentos')"><i class="fas fa-file-alt"></i><span>Documentos</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('ranking')"><i class="fas fa-trophy"></i><span>Ranking</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('config')"><i class="fas fa-cog"></i><span>Configurações</span></div>
            </div>
            <div class="user-info-sidebar">
                <div class="user-avatar" id="sidebarAvatar"><span>👤</span></div>
                <div class="user-details">
                    <div class="user-name" id="sidebarNome">Usuário</div>
                    <div class="user-family" id="sidebarFamilia">Família</div>
                </div>
            </div>
        </div>

        <!-- CONTEÚDO PRINCIPAL -->
        <div class="main-content">
            <div class="header">
                <div class="page-title">
                    <h1 id="pageTitle">Dashboard</h1>
                    <p id="pageSubtitle">Bem-vindo ao FamilyHub</p>
                </div>
                <div class="header-actions">
                    <button class="btn-icon" onclick="atualizarDados()"><i class="fas fa-sync-alt"></i> <span>Atualizar</span></button>
                    <button class="btn-icon danger" onclick="fazerLogout()"><i class="fas fa-sign-out-alt"></i> <span>Sair</span></button>
                </div>
            </div>

            <!-- HOME -->
            <div id="homeSection">
                <div class="cards-grid">
                    <div class="status-card"><div class="card-header"><div class="card-icon"><i class="fas fa-tasks"></i></div><div class="card-title">Tarefas hoje</div></div><div class="card-value" id="totalTarefasHoje">0</div><div class="card-sub"><span class="trend-up" id="tarefasConcluidasHoje">0 concluídas</span></div></div>
                    <div class="status-card"><div class="card-header"><div class="card-icon"><i class="fas fa-clock"></i></div><div class="card-title">Pendentes</div></div><div class="card-value" id="tarefasPendentes">0</div><div class="card-sub">aguardando ação</div></div>
                    <div class="status-card"><div class="card-header"><div class="card-icon"><i class="fas fa-users"></i></div><div class="card-title">Membros</div></div><div class="card-value" id="totalMembros">1</div><div class="card-sub">ativos na família</div></div>
                    <div class="status-card"><div class="card-header"><div class="card-icon"><i class="fas fa-calendar-check"></i></div><div class="card-title">Próximos eventos</div></div><div class="card-value" id="proximosEventos">0</div><div class="card-sub">próximos 7 dias</div></div>
                </div>
                <div class="tasks-section">
                    <div class="tasks-container">
                        <div class="section-header"><h2>Tarefas de hoje</h2><button class="btn-add" onclick="mostrarTelaDashboard('tarefas')"><i class="fas fa-plus"></i> Ver todas</button></div>
                        <div class="task-list" id="taskListHome"></div>
                    </div>
                    <div class="calendar-container">
                        <div class="calendar-header"><h3 class="calendar-month" id="currentMonth">...</h3><div class="calendar-nav"><button onclick="navegarMes(-1)"><i class="fas fa-chevron-left"></i></button><button onclick="navegarMes(1)"><i class="fas fa-chevron-right"></i></button></div></div>
                        <div class="calendar-weekdays"><div>Dom</div><div>Seg</div><div>Ter</div><div>Qua</div><div>Qui</div><div>Sex</div><div>Sáb</div></div>
                        <div class="calendar-days" id="calendarDays"></div>
                    </div>
                </div>
                <div class="day-details-container" id="dayDetails" style="display:none">
                    <div class="day-details-header"><h3>Tarefas do dia</h3><span class="day-details-date" id="selectedDate"></span></div>
                    <div class="day-tasks-list" id="dayTasksList"></div>
                </div>
                <div class="recent-activities">
                    <div class="section-header"><h2>Atividades recentes</h2></div>
                    <div class="activity-list" id="activityList"></div>
                </div>
            </div>

            <!-- MEMBROS -->
            <div id="membersSection" style="display:none">
                <div class="members-container">
                    <div class="members-header"><h2>Todos os Membros</h2></div>
                    <div class="members-grid" id="membersGrid"></div>
                </div>
            </div>

            <!-- TAREFAS -->
            <div id="tarefasSection" style="display:none">
                <div class="tasks-container">
                    <div class="section-header"><h2>📋 Todas as Tarefas</h2><button class="btn-add" onclick="abrirModalTarefa()"><i class="fas fa-plus"></i> Nova Tarefa</button></div>
                    <div class="task-filters">
                        <button class="filter-btn active" onclick="filtrarTarefas('todas')">Todas</button>
                        <button class="filter-btn" onclick="filtrarTarefas('hoje')">Hoje</button>
                        <button class="filter-btn" onclick="filtrarTarefas('semana')">Esta semana</button>
                        <button class="filter-btn" onclick="filtrarTarefas('pendentes')">Pendentes</button>
                        <button class="filter-btn" onclick="filtrarTarefas('concluidas')">Concluídas</button>
                        <button class="filter-btn" onclick="filtrarTarefas('atrasadas')">Atrasadas</button>
                    </div>
                    <div class="task-stats">
                        <div class="stat-item"><div class="stat-value" id="totalTarefasStat">0</div><div class="stat-label">Total</div></div>
                        <div class="stat-item"><div class="stat-value" id="concluidasStat">0</div><div class="stat-label">Concluídas</div></div>
                        <div class="stat-item"><div class="stat-value" id="pendentesStat">0</div><div class="stat-label">Pendentes</div></div>
                    </div>
                    <div class="task-list" id="allTasksList" style="max-height:500px;margin-top:20px"></div>
                </div>
            </div>

            <!-- CHAT -->
            <div id="chatSection" style="display:none">
                <div class="chat-container">
                    <div class="chat-header"><h2>Chat da Família</h2><span class="points-badge"><i class="fas fa-star"></i> +1 ponto por mensagem</span></div>
                    <div class="chat-messages" id="chatMessages"></div>
                    <div class="chat-input-area">
                        <textarea class="chat-input" id="chatInput" placeholder="Digite sua mensagem..." rows="1"></textarea>
                        <button class="chat-send-btn" onclick="enviarMensagem()"><i class="fas fa-paper-plane"></i> Enviar</button>
                    </div>
                </div>
            </div>

            <!-- GALERIA -->
            <div id="galeriaSection" style="display:none">
                <div class="gallery-container">
                    <div class="gallery-header"><h2>Galeria da Família</h2><button class="btn-add" onclick="abrirModalUploadFoto()"><i class="fas fa-upload"></i> Upload</button></div>
                    <div class="gallery-grid" id="galleryGrid"></div>
                </div>
            </div>

            <!-- FINANÇAS -->
            <div id="financasSection" style="display:none">
                <div class="financas-container">
                    <div class="financas-header"><h2>Finanças da Família</h2><button class="btn-add" onclick="abrirModalTransacao()"><i class="fas fa-plus"></i> Nova Transação</button></div>
                    <div class="financas-resumo">
                        <div class="resumo-card"><div class="resumo-label">Saldo Total</div><div class="resumo-valor" id="saldoTotal">R$ 0,00</div></div>
                        <div class="resumo-card"><div class="resumo-label">Receitas</div><div class="resumo-valor positivo" id="totalReceitas">R$ 0,00</div></div>
                        <div class="resumo-card"><div class="resumo-label">Despesas</div><div class="resumo-valor negativo" id="totalDespesas">R$ 0,00</div></div>
                    </div>
                    <h3 style="color:#fff;margin:20px 0 15px">Últimas Transações</h3>
                    <div class="transacoes-list" id="transacoesList"></div>
                </div>
            </div>

            <!-- LEMBRETES -->
            <div id="lembretesSection" style="display:none">
                <div class="lembretes-container">
                    <div class="lembretes-header"><h2>Lembretes</h2><button class="btn-add" onclick="abrirModalLembrete()"><i class="fas fa-plus"></i> Novo Lembrete</button></div>
                    <div class="lembretes-list" id="lembretesList"></div>
                </div>
            </div>

            <!-- DOCUMENTOS -->
            <div id="documentosSection" style="display:none">
                <div class="documentos-container">
                    <div class="documentos-header"><h2>Documentos</h2><button class="btn-add" onclick="abrirModalDocumento()"><i class="fas fa-upload"></i> Adicionar</button></div>
                    <div class="documentos-grid" id="documentosGrid"></div>
                </div>
            </div>

            <!-- RANKING -->
            <div id="rankingSection" style="display:none">
                <div class="ranking-container">
                    <div class="ranking-header"><h2>Ranking de Pontos</h2><span class="points-badge"><i class="fas fa-star"></i> Ganhe pontos!</span></div>
                    <div class="ranking-list" id="rankingList"></div>
                </div>
            </div>

            <!-- CONFIGURAÇÕES -->
            <div id="configSection" style="display:none">
                <div class="config-container">
                    <div class="config-header"><h2>⚙️ Configurações da Família</h2><p>Gerencie todas as configurações da sua família</p></div>
                    <div class="config-menu">
                        <button class="config-menu-item active" onclick="mostrarSubConfig('geral')"><i class="fas fa-home"></i> Geral</button>
                        <button class="config-menu-item" onclick="mostrarSubConfig('membros')"><i class="fas fa-users"></i> Membros</button>
                        <button class="config-menu-item" onclick="mostrarSubConfig('perfil')"><i class="fas fa-user"></i> Meu Perfil</button>
                        <button class="config-menu-item" onclick="mostrarSubConfig('auditoria')"><i class="fas fa-history"></i> Auditoria</button>
                    </div>
                    <div class="config-content">
                        <!-- GERAL -->
                        <div id="configGeral" class="config-pane active">
                            <h3>Informações da Família</h3>
                            <div class="config-card">
                                <div class="config-card-header"><i class="fas fa-camera"></i><h4>Foto da Família</h4></div>
                                <div class="config-card-body">
                                    <div class="family-photo-edit">
                                        <div class="current-family-photo" id="currentFamilyPhoto">
                                            <img src="" alt="Foto da família" id="familyPhotoImg" style="display:none">
                                            <span class="photo-placeholder" id="familyPhotoPlaceholder">👪</span>
                                        </div>
                                        <div class="photo-edit-actions">
                                            <button class="config-btn" onclick="document.getElementById('fotoFamiliaEdit').click()"><i class="fas fa-edit"></i> Alterar foto</button>
                                            <input type="file" id="fotoFamiliaEdit" accept="image/*" style="display:none" onchange="alterarFotoFamilia(event)">
                                            <button class="config-btn config-btn-danger" onclick="removerFotoFamilia()"><i class="fas fa-trash"></i> Remover</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="config-card">
                                <div class="config-card-header"><i class="fas fa-home"></i><h4>Nome da Família</h4></div>
                                <div class="config-card-body">
                                    <div class="config-field">
                                        <label>Nome atual</label>
                                        <input type="text" class="config-field" id="configNomeFamilia" placeholder="Nome da família" style="width:100%;background:rgba(20,30,45,.8);border:1.5px solid rgba(79,159,255,.3);border-radius:12px;padding:12px 15px;color:#fff;font-size:.95rem;outline:none">
                                    </div>
                                    <button class="config-btn config-btn-primary" onclick="salvarConfigFamilia()"><i class="fas fa-save"></i> Salvar alterações</button>
                                </div>
                            </div>
                        </div>
                        <!-- MEMBROS CONFIG -->
                        <div id="configMembros" class="config-pane">
                            <h3>Gerenciar Membros</h3>
                            <div id="configMembrosLista"></div>
                        </div>
                        <!-- PERFIL CONFIG -->
                        <div id="configPerfil" class="config-pane">
                            <h3>Meu Perfil</h3>
                            <div class="config-card">
                                <div class="config-card-header"><i class="fas fa-user"></i><h4>Foto do Perfil</h4></div>
                                <div class="config-card-body">
                                    <div class="family-photo-edit">
                                        <div class="current-family-photo" id="perfilFotoContainer" style="cursor:pointer" onclick="document.getElementById('foto-membro-edit').click()">
                                            <img src="" id="perfilFotoImg" style="display:none;width:100%;height:100%;object-fit:cover">
                                            <span id="perfilFotoPlaceholder" style="font-size:2rem">👤</span>
                                        </div>
                                        <input type="file" id="foto-membro-edit" accept="image/*" onchange="previewPerfilFoto(event)" style="display:none">
                                        <div><button class="config-btn config-btn-primary" onclick="salvarPerfil()"><i class="fas fa-save"></i> Salvar Foto</button></div>
                                    </div>
                                </div>
                            </div>
                            <div class="config-card">
                                <div class="config-card-header"><i class="fas fa-edit"></i><h4>Dados Pessoais</h4></div>
                                <div class="config-card-body">
                                    <div class="config-field"><label>Nome</label><input type="text" id="perfilNome" style="width:100%;background:rgba(20,30,45,.8);border:1.5px solid rgba(79,159,255,.3);border-radius:12px;padding:12px 15px;color:#fff;font-size:.95rem;outline:none"></div>
                                    <div class="config-field"><label>Senha atual (para trocar senha)</label><input type="password" id="perfilSenhaAtual" placeholder="Senha atual" style="width:100%;background:rgba(20,30,45,.8);border:1.5px solid rgba(79,159,255,.3);border-radius:12px;padding:12px 15px;color:#fff;font-size:.95rem;outline:none"></div>
                                    <div class="config-field"><label>Nova senha (opcional)</label><input type="password" id="perfilNovaSenha" placeholder="Nova senha (mín. 6 caracteres)" style="width:100%;background:rgba(20,30,45,.8);border:1.5px solid rgba(79,159,255,.3);border-radius:12px;padding:12px 15px;color:#fff;font-size:.95rem;outline:none"></div>
                                    <button class="config-btn config-btn-primary" onclick="salvarPerfil()"><i class="fas fa-save"></i> Salvar Dados</button>
                                </div>
                            </div>
                        </div>
                        <!-- AUDITORIA -->
                        <div id="configAuditoria" class="config-pane">
                            <h3>Log de Atividades</h3>
                            <div class="auditoria-filtros">
                                <select id="filtroAuditoria" onchange="carregarAuditoria()" style="background:rgba(20,30,45,.8);border:1.5px solid rgba(79,159,255,.3);border-radius:12px;padding:10px 15px;color:#fff;outline:none">
                                    <option value="">Todos os tipos</option>
                                    <option value="add">Criações</option>
                                    <option value="edit">Edições</option>
                                    <option value="delete">Exclusões</option>
                                    <option value="check">Conclusões</option>
                                    <option value="levelup">Level ups</option>
                                </select>
                            </div>
                            <div class="activity-list" id="auditoriaLista"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAIS -->

<!-- MODAL TAREFA -->
<div class="modal" id="modalTarefa">
    <div class="modal-content">
        <div class="modal-header"><h3 id="modalTarefaTitulo">Nova Tarefa</h3><button class="modal-close" onclick="fecharModal('modalTarefa')">×</button></div>
        <input type="hidden" id="tarefaEditId">
        <div class="modal-input-group"><label>Título *</label><input type="text" id="tarefaTitulo" placeholder="Nome da tarefa" maxlength="200"></div>
        <div class="modal-input-group"><label>Descrição</label><textarea id="tarefaDescricao" placeholder="Detalhes da tarefa" rows="3"></textarea></div>
        <div class="modal-input-group"><label>Responsável</label><input type="text" id="tarefaResponsavel" placeholder="Quem vai fazer?" maxlength="100"></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
            <div class="modal-input-group"><label>Data</label><input type="date" id="tarefaData"></div>
            <div class="modal-input-group"><label>Hora</label><input type="time" id="tarefaHora"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
            <div class="modal-input-group"><label>Prioridade</label><select id="tarefaPrioridade"><option value="baixa">🟢 Baixa</option><option value="media" selected>🟡 Média</option><option value="alta">🔴 Alta</option></select></div>
            <div class="modal-input-group"><label>Pontos</label><input type="number" id="tarefaPontos" value="10" min="1" max="100"></div>
        </div>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalTarefa')">Cancelar</button>
            <button class="modal-btn modal-btn-primary" onclick="salvarTarefa()">Salvar</button>
        </div>
    </div>
</div>

<!-- MODAL EDITAR MEMBRO -->
<div class="modal" id="modalEditarMembro">
    <div class="modal-content">
        <div class="modal-header"><h3>Editar Membro</h3><button class="modal-close" onclick="fecharModal('modalEditarMembro')">×</button></div>
        <input type="hidden" id="editMembroId">
        <div class="modal-input-group"><label>Nome</label><input type="text" id="editMembroNome" placeholder="Nome completo" maxlength="100"></div>
        <div class="modal-input-group"><label>Senha atual (para trocar senha)</label><input type="password" id="editMembroSenhaAtual" placeholder="Senha atual"></div>
        <div class="modal-input-group"><label>Nova senha (opcional)</label><input type="password" id="editMembroNovaSenha" placeholder="Nova senha (mín. 6 caracteres)"></div>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalEditarMembro')">Cancelar</button>
            <button class="modal-btn modal-btn-primary" onclick="salvarEdicaoMembro()">Salvar</button>
        </div>
    </div>
</div>

<!-- MODAL UPLOAD FOTO -->
<div class="modal" id="modalUploadFoto">
    <div class="modal-content">
        <div class="modal-header"><h3>Upload de Foto</h3><button class="modal-close" onclick="fecharModal('modalUploadFoto')">×</button></div>
        <div class="modal-input-group"><label>Título (opcional)</label><input type="text" id="fotoTitulo" placeholder="Ex: Férias 2025" maxlength="200"></div>
        <div class="modal-input-group"><label>Foto *</label>
            <div class="photo-upload-container">
                <div class="photo-preview-circle" id="galleryPhotoPreview" onclick="document.getElementById('fotoGaleria').click()" style="width:150px;height:150px;border-radius:16px">
                    <div class="placeholder-icon">📷</div>
                    <div class="placeholder-text">Escolher foto</div>
                    <img class="preview-img" id="galleryPreviewImg" src="#" alt="preview">
                </div>
                <input type="file" id="fotoGaleria" accept="image/*" style="display:none" onchange="handlePhotoUpload(event,'galleryPhotoPreview','galleryPreviewImg','galleryFileName')">
                <span class="file-name-small" id="galleryFileName">Nenhum arquivo</span>
            </div>
        </div>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalUploadFoto')">Cancelar</button>
            <button class="modal-btn modal-btn-primary" onclick="uploadFoto()">Upload</button>
        </div>
    </div>
</div>

<!-- MODAL TRANSAÇÃO -->
<div class="modal" id="modalTransacao">
    <div class="modal-content">
        <div class="modal-header"><h3>Nova Transação</h3><button class="modal-close" onclick="fecharModal('modalTransacao')">×</button></div>
        <div class="modal-input-group"><label>Descrição *</label><input type="text" id="transacaoDescricao" placeholder="Ex: Supermercado" maxlength="200"></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
            <div class="modal-input-group"><label>Valor (R$) *</label><input type="number" id="transacaoValor" placeholder="0,00" min="0.01" step="0.01"></div>
            <div class="modal-input-group"><label>Tipo</label><select id="transacaoTipo"><option value="receita">✅ Receita</option><option value="despesa" selected>❌ Despesa</option></select></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
            <div class="modal-input-group"><label>Categoria</label><input type="text" id="transacaoCategoria" placeholder="Ex: Alimentação" maxlength="100"></div>
            <div class="modal-input-group"><label>Data</label><input type="date" id="transacaoData"></div>
        </div>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalTransacao')">Cancelar</button>
            <button class="modal-btn modal-btn-primary" onclick="salvarTransacao()">Salvar</button>
        </div>
    </div>
</div>

<!-- MODAL LEMBRETE -->
<div class="modal" id="modalLembrete">
    <div class="modal-content">
        <div class="modal-header"><h3>Novo Lembrete</h3><button class="modal-close" onclick="fecharModal('modalLembrete')">×</button></div>
        <div class="modal-input-group"><label>Título *</label><input type="text" id="lembreteTitulo" placeholder="Ex: Consulta médica" maxlength="200"></div>
        <div class="modal-input-group"><label>Descrição</label><textarea id="lembreteDescricao" placeholder="Detalhes..." rows="3"></textarea></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px">
            <div class="modal-input-group"><label>Data</label><input type="date" id="lembreteData"></div>
            <div class="modal-input-group"><label>Hora</label><input type="time" id="lembreteHora"></div>
        </div>
        <div class="modal-input-group"><label>Cor</label><select id="lembreteCor"><option value="#4f9fff">🔵 Azul</option><option value="#51cf66">🟢 Verde</option><option value="#ff6b6b">🔴 Vermelho</option><option value="#ffd700">🟡 Amarelo</option><option value="#a78bfa">🟣 Roxo</option></select></div>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalLembrete')">Cancelar</button>
            <button class="modal-btn modal-btn-primary" onclick="salvarLembrete()">Salvar</button>
        </div>
    </div>
</div>

<!-- MODAL DOCUMENTO -->
<div class="modal" id="modalDocumento">
    <div class="modal-content">
        <div class="modal-header"><h3>Adicionar Documento</h3><button class="modal-close" onclick="fecharModal('modalDocumento')">×</button></div>
        <div class="modal-input-group"><label>Nome do documento *</label><input type="text" id="docNome" placeholder="Ex: Certidão de nascimento" maxlength="200"></div>
        <div class="modal-input-group"><label>Arquivo *</label><input type="file" id="docArquivo" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt" onchange="previewDocumento(event)" style="background:rgba(20,30,45,.6);border:1.5px solid rgba(79,159,255,.3);border-radius:12px;padding:12px 16px;color:#fff;width:100%"></div>
        <p id="docPreviewInfo" style="color:#8ba3c7;font-size:.85rem;margin-top:8px"></p>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalDocumento')">Cancelar</button>
            <button class="modal-btn modal-btn-primary" onclick="uploadDocumento()">Salvar</button>
        </div>
    </div>
</div>

<!-- MODAL CONFIRMAÇÃO -->
<div class="modal" id="modalConfirmar">
    <div class="modal-content" style="max-width:400px">
        <div class="modal-header"><h3 id="confirmarTitulo">Confirmar</h3><button class="modal-close" onclick="fecharModal('modalConfirmar')">×</button></div>
        <p id="confirmarMensagem" style="color:#a0b8d9;margin-bottom:25px;line-height:1.6"></p>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalConfirmar')">Cancelar</button>
            <button class="modal-btn" id="confirmarSimBtn" style="background:#ff6b6b;color:#fff">Confirmar</button>
        </div>
    </div>
</div>

<!-- ALERTA CUSTOMIZADO -->
<div id="customAlert">
    <span id="alertIcon">ℹ️</span>
    <span id="alertMessage">Mensagem</span>
</div>

<script>
// ==================== THREE.JS PARTÍCULAS ====================
(function() {
    const canvas = document.getElementById('particles-canvas');
    const renderer = new THREE.WebGLRenderer({canvas, alpha: true});
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.z = 3;
    const particlesGeometry = new THREE.BufferGeometry();
    const count = 2000;
    const positions = new Float32Array(count * 3);
    for (let i = 0; i < count * 3; i++) positions[i] = (Math.random() - 0.5) * 10;
    particlesGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    const particlesMaterial = new THREE.PointsMaterial({size: 0.015, color: 0xc8741a, transparent: true, opacity: 0.5});
    const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
    scene.add(particlesMesh);
    let targetRotationX = 0, targetRotationY = 0;
    document.addEventListener('mousemove', (e) => {
        targetRotationY = (e.clientX / window.innerWidth) * 2 - 1;
        targetRotationX = -((e.clientY / window.innerHeight) * 2 - 1);
    });
    let time = 0;
    function animate() {
        requestAnimationFrame(animate);
        time += 0.001;
        const pos = particlesGeometry.attributes.position.array;
        for (let i = 0; i < pos.length; i += 3) pos[i+1] += Math.sin(time + pos[i]) * 0.001;
        particlesGeometry.attributes.position.needsUpdate = true;
        particlesMesh.rotation.y += (targetRotationY * 0.3 - particlesMesh.rotation.y) * 0.05;
        particlesMesh.rotation.x += (targetRotationX * 0.2 - particlesMesh.rotation.x) * 0.05;
        renderer.render(scene, camera);
    }
    animate();
    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });
})();

// ==================== ESTADO GLOBAL ====================
let usuarioAtual = null;
let dadosTarefas = [];
let calendarYear, calendarMonth;
let filtroTarefaAtual = 'todas';
let docBase64 = null, docNomeArq = '', docTipoArq = '', docTamanhoArq = 0;
let perfilFotoBase64 = null;

// ==================== UTILITÁRIOS ====================
function api(action, data = {}) {
    const fd = new FormData();
    fd.append('action', action);
    for (const k in data) fd.append(k, data[k]);
    return fetch('api.php', {method: 'POST', body: fd}).then(r => r.json());
}

function apiGet(action, params = {}) {
    const qs = new URLSearchParams({action, ...params}).toString();
    return fetch('api.php?' + qs).then(r => r.json());
}

function mostrarAlerta(tipo, msg) {
    const box = document.getElementById('customAlert');
    document.getElementById('alertIcon').textContent = tipo === 'erro' ? '❌' : tipo === 'sucesso' ? '✅' : 'ℹ️';
    document.getElementById('alertMessage').textContent = msg;
    box.className = tipo === 'erro' ? 'error' : tipo === 'sucesso' ? 'success' : '';
    box.style.display = 'flex';
    setTimeout(() => box.style.display = 'none', 3500);
}

function fecharModal(id) {
    document.getElementById(id).style.display = 'none';
}

function abrirModalConfirmar(titulo, msg, cb) {
    document.getElementById('confirmarTitulo').textContent = titulo;
    document.getElementById('confirmarMensagem').textContent = msg;
    document.getElementById('confirmarSimBtn').onclick = () => { cb(); fecharModal('modalConfirmar'); };
    document.getElementById('modalConfirmar').style.display = 'flex';
}

window.onclick = e => {
    ['modalTarefa','modalEditarMembro','modalUploadFoto','modalTransacao','modalLembrete','modalDocumento','modalConfirmar'].forEach(id => {
        const m = document.getElementById(id);
        if (e.target === m) m.style.display = 'none';
    });
};

function handlePhotoUpload(event, previewCircleId, previewImgId, fileNameId) {
    const file = event.target.files[0];
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) { mostrarAlerta('erro', 'Imagem deve ter no máximo 5MB'); return; }
    const circle = document.getElementById(previewCircleId);
    const img = document.getElementById(previewImgId);
    const fname = document.getElementById(fileNameId);
    if (fname) fname.textContent = file.name.length > 30 ? file.name.substr(0,27)+'...' : file.name;
    const reader = new FileReader();
    reader.onload = e => { img.src = e.target.result; circle.classList.add('has-image'); };
    reader.readAsDataURL(file);
}

function formatMoeda(v) {
    return 'R$ ' + parseFloat(v).toLocaleString('pt-BR', {minimumFractionDigits:2, maximumFractionDigits:2});
}

function formatData(d) {
    if (!d) return '';
    const dt = new Date(d + 'T00:00:00');
    return dt.toLocaleDateString('pt-BR');
}

function formatDateTime(d) {
    if (!d) return '';
    const dt = new Date(d);
    return dt.toLocaleDateString('pt-BR') + ' às ' + dt.toLocaleTimeString('pt-BR', {hour:'2-digit', minute:'2-digit'});
}

function avatarHtml(foto, nome, size = 36) {
    if (foto && foto !== 'null') {
        return `<img src="${foto}" style="width:100%;height:100%;object-fit:cover;border-radius:50%">`;
    }
    return `<span style="font-size:${size*0.4}px">${(nome||'?')[0].toUpperCase()}</span>`;
}

// ==================== AUTENTICAÇÃO ====================
function mostrarTelaAuth(tela) {
    ['login-screen','register-choice-screen','register-family-screen','register-member-screen'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.remove('active');
    });
    if (tela === 'login') {
        setTimeout(() => document.getElementById('login-screen').classList.add('active'), 50);
    } else if (tela === 'register') {
        carregarFamiliasSelect();
        setTimeout(() => document.getElementById('register-choice-screen').classList.add('active'), 50);
    }
}

function voltarEscolhaCadastro() { mostrarTelaAuth('register'); }

function escolherCadastro(tipo) {
    document.getElementById('register-choice-screen').classList.remove('active');
    if (tipo === 'familia') {
        setTimeout(() => document.getElementById('register-family-screen').classList.add('active'), 50);
    } else {
        carregarFamiliasSelect();
        setTimeout(() => document.getElementById('register-member-screen').classList.add('active'), 50);
    }
}

function carregarFamiliasSelect() {
    apiGet('listar_familias').then(fams => {
        const sel = document.getElementById('familia-select');
        if (!sel) return;
        sel.innerHTML = '<option value="">Selecione uma família</option>';
        (Array.isArray(fams) ? fams : []).forEach(f => {
            sel.innerHTML += `<option value="${f.id}">${f.nome}</option>`;
        });
    });
}

function cadastrarFamilia() {
    const nomeFamilia = document.getElementById('nome-familia').value.trim();
    const familiaSenha = document.getElementById('familia-senha').value;
    const familiaRepSenha = document.getElementById('familia-rep-senha').value;
    const adminNome = document.getElementById('admin-nome').value.trim();
    const adminUsuario = document.getElementById('admin-usuario').value.trim();
    const adminSenha = document.getElementById('admin-senha').value;
    const adminRepSenha = document.getElementById('admin-rep-senha').value;

    if (!nomeFamilia || !familiaSenha || !adminNome || !adminUsuario || !adminSenha) {
        mostrarAlerta('erro', 'Preencha todos os campos obrigatórios'); return;
    }
    if (familiaSenha !== familiaRepSenha) { mostrarAlerta('erro', 'Senhas da família não coincidem'); return; }
    if (adminSenha !== adminRepSenha) { mostrarAlerta('erro', 'Senhas do admin não coincidem'); return; }
    if (adminUsuario.length < 3) { mostrarAlerta('erro', 'Usuário deve ter no mínimo 3 caracteres'); return; }
    if (adminSenha.length < 6) { mostrarAlerta('erro', 'Senha deve ter no mínimo 6 caracteres'); return; }

    const fotoImg = document.getElementById('familyPreviewImg');
    const foto = fotoImg.src && fotoImg.src !== window.location.href && fotoImg.closest('.has-image') ? fotoImg.src : '';

    api('cadastrar_familia', {
        nome_familia: nomeFamilia, familia_senha: familiaSenha,
        admin_nome: adminNome, admin_usuario: adminUsuario, admin_senha: adminSenha,
        foto_familia: foto
    }).then(r => {
        if (r.sucesso) {
            mostrarAlerta('sucesso', r.mensagem || 'Família criada!');
            setTimeout(() => mostrarTelaAuth('login'), 1200);
        } else {
            mostrarAlerta('erro', r.erro || 'Erro ao cadastrar');
        }
    });
}

function cadastrarMembro() {
    const familiaId = document.getElementById('familia-select').value;
    const senhFam = document.getElementById('membro-familia-senha').value;
    const nome = document.getElementById('membro-nome').value.trim();
    const usuario = document.getElementById('membro-usuario').value.trim();
    const senha = document.getElementById('membro-senha').value;

    if (!familiaId || !senhFam || !nome || !usuario || !senha) {
        mostrarAlerta('erro', 'Preencha todos os campos obrigatórios'); return;
    }

    const fotoImg = document.getElementById('memberPreviewImg');
    const foto = fotoImg.src && fotoImg.src !== window.location.href && fotoImg.closest('.has-image') ? fotoImg.src : '';

    api('cadastrar_membro', {
        familia_id: familiaId, familia_senha: senhFam,
        nome, usuario, senha, foto_membro: foto
    }).then(r => {
        if (r.sucesso) {
            mostrarAlerta('sucesso', r.mensagem || 'Conta criada!');
            setTimeout(() => mostrarTelaAuth('login'), 1200);
        } else {
            mostrarAlerta('erro', r.erro || 'Erro ao cadastrar');
        }
    });
}

function fazerLogin() {
    const usuario = document.getElementById('login-usuario').value.trim();
    const senha = document.getElementById('login-senha').value;
    if (!usuario || !senha) { mostrarAlerta('erro', 'Preencha todos os campos'); return; }
    api('login', {usuario, senha}).then(r => {
        if (r.sucesso) {
            usuarioAtual = r.usuario;
            mostrarDashboard();
        } else {
            mostrarAlerta('erro', r.erro || 'Credenciais inválidas');
        }
    });
}

function fazerLogout() {
    abrirModalConfirmar('Sair', 'Deseja realmente sair?', () => {
        api('logout').then(() => location.reload());
    });
}

document.getElementById('login-senha').addEventListener('keypress', e => { if (e.key === 'Enter') fazerLogin(); });

// ==================== DASHBOARD ====================
function mostrarDashboard() {
    document.getElementById('auth-container').style.display = 'none';
    document.getElementById('dashboard-container').style.display = 'block';
    atualizarSidebar();
    mostrarTelaDashboard('home');
    atualizarDados();
}

function atualizarSidebar() {
    if (!usuarioAtual) return;
    const av = document.getElementById('sidebarAvatar');
    av.innerHTML = usuarioAtual.foto && usuarioAtual.foto !== 'null'
        ? `<img src="${usuarioAtual.foto}" style="width:100%;height:100%;object-fit:cover;border-radius:50%">`
        : `<span>${usuarioAtual.nome[0].toUpperCase()}</span>`;
    document.getElementById('sidebarNome').textContent = usuarioAtual.nome;
    document.getElementById('sidebarFamilia').textContent = usuarioAtual.familia_nome || '';
}

function mostrarTelaDashboard(tela) {
    const secoes = ['homeSection','membersSection','tarefasSection','chatSection','galeriaSection','financasSection','lembretesSection','documentosSection','rankingSection','configSection'];
    secoes.forEach(s => document.getElementById(s).style.display = 'none');

    const titulos = {
        home: ['Dashboard', 'Visão geral da família'],
        membros: ['Membros', 'Todos os integrantes da família'],
        tarefas: ['Tarefas', 'Gerencie as tarefas da família'],
        chat: ['Chat', 'Converse com a família'],
        galeria: ['Galeria', 'Fotos da família'],
        financas: ['Finanças', 'Controle financeiro familiar'],
        lembretes: ['Lembretes', 'Seus lembretes e alertas'],
        documentos: ['Documentos', 'Arquivos e documentos'],
        ranking: ['Ranking', 'Quem está no topo?'],
        config: ['Configurações', 'Ajustes da família']
    };

    document.querySelectorAll('.menu-item').forEach(m => m.classList.remove('active'));

    const map = {home:'homeSection',membros:'membersSection',tarefas:'tarefasSection',chat:'chatSection',galeria:'galeriaSection',financas:'financasSection',lembretes:'lembretesSection',documentos:'documentosSection',ranking:'rankingSection',config:'configSection'};
    if (map[tela]) document.getElementById(map[tela]).style.display = 'block';

    const t = titulos[tela] || ['FamilyHub', ''];
    document.getElementById('pageTitle').textContent = t[0];
    document.getElementById('pageSubtitle').textContent = t[1];

    // Marcar menu ativo
    const menuItems = document.querySelectorAll('.menu-item');
    const menuMap = ['home','membros','tarefas','chat','galeria','financas','lembretes','documentos','ranking','config'];
    menuItems.forEach((item, i) => { if (menuMap[i] === tela) item.classList.add('active'); });

    // Carregar dados da tela
    if (tela === 'home') { carregarHome(); renderizarCalendario(); }
    else if (tela === 'membros') carregarMembros();
    else if (tela === 'tarefas') carregarTarefas();
    else if (tela === 'chat') { carregarMensagens(); }
    else if (tela === 'galeria') carregarGaleria();
    else if (tela === 'financas') carregarFinancas();
    else if (tela === 'lembretes') carregarLembretes();
    else if (tela === 'documentos') carregarDocumentos();
    else if (tela === 'ranking') carregarRanking();
    else if (tela === 'config') iniciarConfig();
}

function atualizarDados() {
    // Verificar sessão
    apiGet('verificar_sessao').then(r => {
        if (r.logado) {
            usuarioAtual = r.usuario;
            atualizarSidebar();
        } else if (document.getElementById('dashboard-container').style.display !== 'none') {
            location.reload();
        }
    });
}

// ==================== HOME ====================
function carregarHome() {
    const hoje = new Date().toISOString().split('T')[0];
    Promise.all([
        apiGet('listar_tarefas'),
        apiGet('listar_membros'),
        apiGet('listar_atividades')
    ]).then(([tarefas, membros, atividades]) => {
        dadosTarefas = Array.isArray(tarefas) ? tarefas : [];
        const tarefasHoje = dadosTarefas.filter(t => t.data === hoje);
        const concluiHoje = tarefasHoje.filter(t => parseInt(t.concluida));
        const pendentes = dadosTarefas.filter(t => !parseInt(t.concluida) && (!t.data || t.data >= hoje));

        document.getElementById('totalTarefasHoje').textContent = tarefasHoje.length;
        document.getElementById('tarefasConcluidasHoje').textContent = concluiHoje.length + ' concluídas';
        document.getElementById('tarefasPendentes').textContent = pendentes.length;
        document.getElementById('totalMembros').textContent = Array.isArray(membros) ? membros.length : 0;

        // Próximos 7 dias
        const em7dias = new Date(); em7dias.setDate(em7dias.getDate() + 7);
        const eventos7 = dadosTarefas.filter(t => t.data && t.data > hoje && t.data <= em7dias.toISOString().split('T')[0]);
        document.getElementById('proximosEventos').textContent = eventos7.length;

        // Lista home
        renderTarefasList('taskListHome', tarefasHoje.slice(0, 5));

        // Atividades
        const actList = document.getElementById('activityList');
        const ativList = Array.isArray(atividades) ? atividades : [];
        actList.innerHTML = ativList.slice(0, 10).map(a => `
            <div class="activity-item">
                <div class="activity-icon"><i class="fas ${getIconeAtividade(a.tipo)}"></i></div>
                <div class="activity-details">
                    <div class="activity-text">${a.descricao}</div>
                    <div class="activity-time">${formatDateTime(a.criado_em)}</div>
                </div>
            </div>
        `).join('') || '<div style="color:#8ba3c7;text-align:center;padding:20px">Nenhuma atividade</div>';

        renderizarCalendario();
    });
}

function getIconeAtividade(tipo) {
    const m = {add:'fa-plus-circle',edit:'fa-edit',delete:'fa-trash',check:'fa-check-circle',pontos:'fa-star',levelup:'fa-level-up-alt'};
    return m[tipo] || 'fa-history';
}

// ==================== CALENDÁRIO ====================
function renderizarCalendario() {
    const now = new Date();
    if (!calendarYear) calendarYear = now.getFullYear();
    if (calendarMonth === undefined) calendarMonth = now.getMonth();

    const meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    document.getElementById('currentMonth').textContent = `${meses[calendarMonth]} ${calendarYear}`;

    const primeirodia = new Date(calendarYear, calendarMonth, 1);
    const ultimodia = new Date(calendarYear, calendarMonth + 1, 0);
    const hoje = now.toISOString().split('T')[0];

    const grid = document.getElementById('calendarDays');
    grid.innerHTML = '';

    // Dias vazios antes
    for (let i = 0; i < primeirodia.getDay(); i++) {
        const div = document.createElement('div');
        div.className = 'calendar-day empty';
        grid.appendChild(div);
    }

    for (let d = 1; d <= ultimodia.getDate(); d++) {
        const dataStr = `${calendarYear}-${String(calendarMonth+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const tarefasDia = dadosTarefas.filter(t => t.data === dataStr);
        const div = document.createElement('div');
        let cls = 'calendar-day';
        if (dataStr === hoje) cls += ' today';
        if (tarefasDia.length > 0) cls += ' has-task';
        div.className = cls;
        div.innerHTML = `<div class="day-number">${d}</div>${tarefasDia.length > 0 ? `<div class="task-count">${tarefasDia.length}t</div>` : ''}`;
        div.onclick = () => selecionarDia(dataStr, div);
        grid.appendChild(div);
    }
}

function navegarMes(dir) {
    calendarMonth += dir;
    if (calendarMonth > 11) { calendarMonth = 0; calendarYear++; }
    if (calendarMonth < 0) { calendarMonth = 11; calendarYear--; }
    renderizarCalendario();
}

function selecionarDia(dataStr, el) {
    document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
    el.classList.add('selected');

    const detalhes = document.getElementById('dayDetails');
    detalhes.style.display = 'block';
    document.getElementById('selectedDate').textContent = formatData(dataStr);

    const tarefasDia = dadosTarefas.filter(t => t.data === dataStr);
    const lista = document.getElementById('dayTasksList');
    lista.innerHTML = tarefasDia.length > 0 ? tarefasDia.map(t => `
        <div class="day-task-item">
            <div class="day-task-check ${parseInt(t.concluida) ? 'completed' : ''}" onclick="toggleTarefa(${t.id}, ${parseInt(t.concluida) ? 0 : 1})">
                ${parseInt(t.concluida) ? '✓' : ''}
            </div>
            <div class="day-task-info">
                <div class="day-task-title">${t.titulo}</div>
                <div class="day-task-meta">${t.hora ? `<span>🕐 ${t.hora.slice(0,5)}</span>` : ''}${t.responsavel ? `<span>👤 ${t.responsavel}</span>` : ''}</div>
            </div>
        </div>
    `).join('') : '<div class="empty-day"><i class="fas fa-calendar"></i><p>Nenhuma tarefa neste dia</p></div>';
}

// ==================== MEMBROS ====================
function carregarMembros() {
    apiGet('listar_membros').then(membros => {
        const grid = document.getElementById('membersGrid');
        const lista = Array.isArray(membros) ? membros : [];
        grid.innerHTML = lista.map(m => `
            <div class="member-card">
                <div class="member-avatar">${avatarHtml(m.foto, m.nome, 70)}</div>
                <div class="member-name">${m.nome}</div>
                <div class="member-role">${m.cargo === 'admin' ? '👑 Administrador' : '👤 Membro'}</div>
                <div class="member-points">⭐ ${m.pontos} pts · Nível ${m.nivel}</div>
                <div class="member-actions">
                    ${(usuarioAtual.id == m.id || usuarioAtual.cargo === 'admin') ? `<button class="member-btn" onclick="abrirModalEditarMembro(${m.id},'${m.nome.replace(/'/g,"\\'")}')"><i class="fas fa-edit"></i></button>` : ''}
                    ${(usuarioAtual.cargo === 'admin' && usuarioAtual.id != m.id) ? `<button class="member-btn danger" onclick="removerMembro(${m.id},'${m.nome.replace(/'/g,"\\'")}')"><i class="fas fa-trash"></i></button>` : ''}
                </div>
            </div>
        `).join('') || '<div class="empty-state"><i class="fas fa-users"></i><p>Nenhum membro</p></div>';
    });
}

function abrirModalEditarMembro(id, nome) {
    document.getElementById('editMembroId').value = id;
    document.getElementById('editMembroNome').value = nome;
    document.getElementById('editMembroSenhaAtual').value = '';
    document.getElementById('editMembroNovaSenha').value = '';
    document.getElementById('modalEditarMembro').style.display = 'flex';
}

function salvarEdicaoMembro() {
    const id = document.getElementById('editMembroId').value;
    const nome = document.getElementById('editMembroNome').value.trim();
    const senhaAtual = document.getElementById('editMembroSenhaAtual').value;
    const novaSenha = document.getElementById('editMembroNovaSenha').value;
    if (!nome) { mostrarAlerta('erro', 'Nome obrigatório'); return; }
    api('atualizar_membro', {id, nome, senha_atual: senhaAtual, nova_senha: novaSenha}).then(r => {
        if (r.sucesso) {
            mostrarAlerta('sucesso', 'Membro atualizado!');
            fecharModal('modalEditarMembro');
            carregarMembros();
            if (id == usuarioAtual.id) { usuarioAtual.nome = nome; atualizarSidebar(); }
        } else mostrarAlerta('erro', r.erro || 'Erro ao atualizar');
    });
}

function removerMembro(id, nome) {
    abrirModalConfirmar('Remover Membro', `Deseja remover "${nome}" da família?`, () => {
        api('remover_membro', {id}).then(r => {
            if (r.sucesso) { mostrarAlerta('sucesso', 'Membro removido'); carregarMembros(); }
            else mostrarAlerta('erro', r.erro || 'Erro ao remover');
        });
    });
}

// ==================== TAREFAS ====================
function carregarTarefas() {
    apiGet('listar_tarefas').then(tarefas => {
        dadosTarefas = Array.isArray(tarefas) ? tarefas : [];
        filtrarTarefas(filtroTarefaAtual || 'todas');
    });
}

function filtrarTarefas(filtro) {
    filtroTarefaAtual = filtro;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    event?.target?.classList?.add('active');

    const hoje = new Date().toISOString().split('T')[0];
    const em7dias = new Date(); em7dias.setDate(em7dias.getDate() + 7);
    const str7 = em7dias.toISOString().split('T')[0];

    let lista = [...dadosTarefas];
    if (filtro === 'hoje') lista = lista.filter(t => t.data === hoje);
    else if (filtro === 'semana') lista = lista.filter(t => t.data >= hoje && t.data <= str7);
    else if (filtro === 'pendentes') lista = lista.filter(t => !parseInt(t.concluida));
    else if (filtro === 'concluidas') lista = lista.filter(t => parseInt(t.concluida));
    else if (filtro === 'atrasadas') lista = lista.filter(t => !parseInt(t.concluida) && t.data && t.data < hoje);

    document.getElementById('totalTarefasStat').textContent = dadosTarefas.length;
    document.getElementById('concluidasStat').textContent = dadosTarefas.filter(t => parseInt(t.concluida)).length;
    document.getElementById('pendentesStat').textContent = dadosTarefas.filter(t => !parseInt(t.concluida)).length;

    renderTarefasList('allTasksList', lista);
}

function renderTarefasList(containerId, lista) {
    const el = document.getElementById(containerId);
    if (!lista.length) { el.innerHTML = '<div class="empty-state"><i class="fas fa-tasks"></i><p>Nenhuma tarefa</p></div>'; return; }
    el.innerHTML = lista.map(t => {
        const pIcon = t.prioridade === 'alta' ? 'priority-alta' : t.prioridade === 'media' ? 'priority-media' : 'priority-baixa';
        return `
        <div class="task-item">
            <div class="task-info">
                <div class="task-check ${parseInt(t.concluida) ? 'completed' : ''}" onclick="toggleTarefa(${t.id}, ${parseInt(t.concluida) ? 0 : 1})">
                    ${parseInt(t.concluida) ? '✓' : ''}
                </div>
                <div class="task-details">
                    <div class="task-title" style="${parseInt(t.concluida) ? 'text-decoration:line-through;opacity:.6' : ''}">${t.titulo} <span class="task-points">+${t.pontos}pts</span></div>
                    <div class="task-meta">
                        ${t.data ? `<span>📅 ${formatData(t.data)}</span>` : ''}
                        ${t.hora ? `<span>🕐 ${t.hora.slice(0,5)}</span>` : ''}
                        ${t.responsavel ? `<span>👤 ${t.responsavel}</span>` : ''}
                        <span class="${pIcon}">${t.prioridade}</span>
                    </div>
                </div>
            </div>
            <div class="task-actions">
                <button class="task-btn" onclick="abrirModalEditarTarefa(${t.id})"><i class="fas fa-edit"></i></button>
                <button class="task-btn delete" onclick="deletarTarefa(${t.id})"><i class="fas fa-trash"></i></button>
            </div>
        </div>`;
    }).join('');
}

function toggleTarefa(id, concluida) {
    api('concluir_tarefa', {id, concluida}).then(r => {
        if (r.sucesso) {
            const t = dadosTarefas.find(x => x.id == id);
            if (t) t.concluida = concluida;
            if (document.getElementById('tarefasSection').style.display !== 'none') filtrarTarefas(filtroTarefaAtual);
            else if (document.getElementById('homeSection').style.display !== 'none') carregarHome();
            if (concluida) mostrarAlerta('sucesso', 'Tarefa concluída! Pontos ganhos 🎉');
            atualizarDados();
        } else mostrarAlerta('erro', r.erro);
    });
}

function abrirModalTarefa() {
    document.getElementById('modalTarefaTitulo').textContent = 'Nova Tarefa';
    document.getElementById('tarefaEditId').value = '';
    document.getElementById('tarefaTitulo').value = '';
    document.getElementById('tarefaDescricao').value = '';
    document.getElementById('tarefaResponsavel').value = '';
    document.getElementById('tarefaData').value = '';
    document.getElementById('tarefaHora').value = '';
    document.getElementById('tarefaPrioridade').value = 'media';
    document.getElementById('tarefaPontos').value = 10;
    document.getElementById('modalTarefa').style.display = 'flex';
}

function abrirModalEditarTarefa(id) {
    const t = dadosTarefas.find(x => x.id == id);
    if (!t) return;
    document.getElementById('modalTarefaTitulo').textContent = 'Editar Tarefa';
    document.getElementById('tarefaEditId').value = t.id;
    document.getElementById('tarefaTitulo').value = t.titulo;
    document.getElementById('tarefaDescricao').value = t.descricao || '';
    document.getElementById('tarefaResponsavel').value = t.responsavel || '';
    document.getElementById('tarefaData').value = t.data || '';
    document.getElementById('tarefaHora').value = t.hora ? t.hora.slice(0,5) : '';
    document.getElementById('tarefaPrioridade').value = t.prioridade || 'media';
    document.getElementById('tarefaPontos').value = t.pontos || 10;
    document.getElementById('modalTarefa').style.display = 'flex';
}

function salvarTarefa() {
    const id = document.getElementById('tarefaEditId').value;
    const dados = {
        titulo: document.getElementById('tarefaTitulo').value.trim(),
        descricao: document.getElementById('tarefaDescricao').value.trim(),
        responsavel: document.getElementById('tarefaResponsavel').value.trim(),
        data: document.getElementById('tarefaData').value,
        hora: document.getElementById('tarefaHora').value,
        prioridade: document.getElementById('tarefaPrioridade').value,
        pontos: document.getElementById('tarefaPontos').value
    };
    if (!dados.titulo) { mostrarAlerta('erro', 'Título obrigatório'); return; }
    const action = id ? 'editar_tarefa' : 'criar_tarefa';
    if (id) dados.id = id;
    api(action, dados).then(r => {
        if (r.sucesso) {
            mostrarAlerta('sucesso', id ? 'Tarefa atualizada!' : 'Tarefa criada!');
            fecharModal('modalTarefa');
            carregarTarefas();
        } else mostrarAlerta('erro', r.erro || 'Erro ao salvar');
    });
}

function deletarTarefa(id) {
    abrirModalConfirmar('Excluir Tarefa', 'Deseja excluir esta tarefa?', () => {
        api('deletar_tarefa', {id}).then(r => {
            if (r.sucesso) { mostrarAlerta('sucesso', 'Tarefa excluída'); carregarTarefas(); }
            else mostrarAlerta('erro', r.erro);
        });
    });
}

// ==================== CHAT ====================
let chatInterval = null;

function carregarMensagens() {
    apiGet('listar_mensagens').then(msgs => {
        const el = document.getElementById('chatMessages');
        const lista = Array.isArray(msgs) ? msgs : [];
        el.innerHTML = lista.map(m => {
            const mine = m.membro_id == usuarioAtual.id;
            return `<div class="message ${mine ? 'mine' : ''}">
                <div class="msg-avatar">${avatarHtml(m.autor_foto, m.autor_nome)}</div>
                <div class="msg-bubble">
                    ${!mine ? `<div class="msg-author">${m.autor_nome}</div>` : ''}
                    <div class="msg-text">${m.texto.replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>')}</div>
                    <div class="msg-time">${formatDateTime(m.criado_em)}</div>
                </div>
            </div>`;
        }).join('');
        el.scrollTop = el.scrollHeight;
    });
    clearInterval(chatInterval);
    chatInterval = setInterval(carregarMensagens, 5000);
}

function enviarMensagem() {
    const input = document.getElementById('chatInput');
    const texto = input.value.trim();
    if (!texto) return;
    api('enviar_mensagem', {texto}).then(r => {
        if (r.sucesso) { input.value = ''; carregarMensagens(); }
        else mostrarAlerta('erro', r.erro);
    });
}

document.getElementById('chatInput').addEventListener('keypress', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); enviarMensagem(); }
});

// ==================== GALERIA ====================
function carregarGaleria() {
    apiGet('listar_fotos').then(fotos => {
        const grid = document.getElementById('galleryGrid');
        const lista = Array.isArray(fotos) ? fotos : [];
        grid.innerHTML = lista.map(f => `
            <div class="gallery-item">
                <img src="${f.dados}" alt="${f.titulo || 'Foto'}" loading="lazy">
                <div class="gallery-overlay">
                    <span class="gallery-item-title">${f.titulo || formatData(f.criado_em.split(' ')[0])}</span>
                    <div class="gallery-item-actions">
                        <button class="gallery-action-btn" onclick="favoritarFoto(${f.id},${parseInt(f.favorita) ? 0 : 1})" title="${parseInt(f.favorita) ? 'Desfavoritar' : 'Favoritar'}">
                            ${parseInt(f.favorita) ? '⭐' : '☆'}
                        </button>
                        <button class="gallery-action-btn" onclick="deletarFoto(${f.id})" title="Excluir" style="color:#ff6b6b">🗑</button>
                    </div>
                </div>
            </div>
        `).join('') || '<div class="empty-state" style="grid-column:1/-1"><i class="fas fa-images"></i><p>Nenhuma foto na galeria</p></div>';
    });
}

function abrirModalUploadFoto() {
    document.getElementById('fotoTitulo').value = '';
    document.getElementById('galleryPhotoPreview').classList.remove('has-image');
    document.getElementById('galleryPreviewImg').src = '#';
    document.getElementById('galleryFileName').textContent = 'Nenhum arquivo';
    document.getElementById('fotoGaleria').value = '';
    document.getElementById('modalUploadFoto').style.display = 'flex';
}

function uploadFoto() {
    const titulo = document.getElementById('fotoTitulo').value.trim();
    const img = document.getElementById('galleryPreviewImg');
    if (!img.src || img.src === window.location.href || !document.getElementById('galleryPhotoPreview').classList.contains('has-image')) {
        mostrarAlerta('erro', 'Selecione uma foto'); return;
    }
    api('upload_foto', {titulo, dados: img.src}).then(r => {
        if (r.sucesso) { mostrarAlerta('sucesso', 'Foto enviada!'); fecharModal('modalUploadFoto'); carregarGaleria(); }
        else mostrarAlerta('erro', r.erro);
    });
}

function favoritarFoto(id, fav) {
    api('favoritar_foto', {id, favorita: fav}).then(r => { if (r.sucesso) carregarGaleria(); });
}

function deletarFoto(id) {
    abrirModalConfirmar('Excluir Foto', 'Deseja excluir esta foto permanentemente?', () => {
        api('deletar_foto', {id}).then(r => { if (r.sucesso) { mostrarAlerta('sucesso', 'Foto excluída'); carregarGaleria(); } });
    });
}

// ==================== FINANÇAS ====================
function carregarFinancas() {
    apiGet('listar_transacoes').then(trans => {
        const lista = Array.isArray(trans) ? trans : [];
        let receitas = 0, despesas = 0;
        lista.forEach(t => {
            if (t.tipo === 'receita') receitas += parseFloat(t.valor);
            else despesas += parseFloat(t.valor);
        });
        document.getElementById('totalReceitas').textContent = formatMoeda(receitas);
        document.getElementById('totalDespesas').textContent = formatMoeda(despesas);
        document.getElementById('saldoTotal').textContent = formatMoeda(receitas - despesas);
        document.getElementById('transacoesList').innerHTML = lista.slice(0,20).map(t => `
            <div class="transacao-item">
                <div class="transacao-icon ${t.tipo}"><i class="fas ${t.tipo === 'receita' ? 'fa-arrow-up' : 'fa-arrow-down'}"></i></div>
                <div class="transacao-info">
                    <div class="transacao-descricao">${t.descricao}</div>
                    <div class="transacao-data">${t.categoria ? t.categoria + ' · ' : ''}${formatData(t.data)} · ${t.autor_nome}</div>
                </div>
                <div class="transacao-valor ${t.tipo}">${t.tipo === 'receita' ? '+' : '-'}${formatMoeda(t.valor)}</div>
                <button class="task-btn delete" onclick="deletarTransacao(${t.id})"><i class="fas fa-trash"></i></button>
            </div>
        `).join('') || '<div class="empty-state"><i class="fas fa-dollar-sign"></i><p>Nenhuma transação</p></div>';
    });
}

function abrirModalTransacao() {
    document.getElementById('transacaoDescricao').value = '';
    document.getElementById('transacaoValor').value = '';
    document.getElementById('transacaoTipo').value = 'despesa';
    document.getElementById('transacaoCategoria').value = '';
    document.getElementById('transacaoData').value = new Date().toISOString().split('T')[0];
    document.getElementById('modalTransacao').style.display = 'flex';
}

function salvarTransacao() {
    const dados = {
        descricao: document.getElementById('transacaoDescricao').value.trim(),
        valor: document.getElementById('transacaoValor').value,
        tipo: document.getElementById('transacaoTipo').value,
        categoria: document.getElementById('transacaoCategoria').value.trim(),
        data: document.getElementById('transacaoData').value
    };
    if (!dados.descricao || !dados.valor) { mostrarAlerta('erro', 'Preencha os campos obrigatórios'); return; }
    api('criar_transacao', dados).then(r => {
        if (r.sucesso) { mostrarAlerta('sucesso', 'Transação salva!'); fecharModal('modalTransacao'); carregarFinancas(); }
        else mostrarAlerta('erro', r.erro);
    });
}

function deletarTransacao(id) {
    abrirModalConfirmar('Excluir Transação', 'Deseja excluir esta transação?', () => {
        api('deletar_transacao', {id}).then(r => { if (r.sucesso) { mostrarAlerta('sucesso', 'Transação excluída'); carregarFinancas(); } });
    });
}

// ==================== LEMBRETES ====================
function carregarLembretes() {
    apiGet('listar_lembretes').then(lembs => {
        const lista = Array.isArray(lembs) ? lembs : [];
        document.getElementById('lembretesList').innerHTML = lista.map(l => `
            <div class="lembrete-item" style="border-left-color:${l.cor}">
                <div class="lembrete-info">
                    <div class="lembrete-titulo">${l.titulo}</div>
                    <div class="lembrete-meta">
                        ${l.data ? `<span>📅 ${formatData(l.data)}</span>` : ''}
                        ${l.hora ? `<span>🕐 ${l.hora.slice(0,5)}</span>` : ''}
                        <span>por ${l.autor_nome}</span>
                    </div>
                    ${l.descricao ? `<div style="color:#a0b8d9;font-size:.85rem;margin-top:5px">${l.descricao}</div>` : ''}
                </div>
                <button class="task-btn delete" onclick="deletarLembrete(${l.id})"><i class="fas fa-trash"></i></button>
            </div>
        `).join('') || '<div class="empty-state"><i class="fas fa-bell"></i><p>Nenhum lembrete</p></div>';
    });
}

function abrirModalLembrete() {
    document.getElementById('lembreteTitulo').value = '';
    document.getElementById('lembreteDescricao').value = '';
    document.getElementById('lembreteData').value = '';
    document.getElementById('lembreteHora').value = '';
    document.getElementById('lembreteCor').value = '#4f9fff';
    document.getElementById('modalLembrete').style.display = 'flex';
}

function salvarLembrete() {
    const dados = {
        titulo: document.getElementById('lembreteTitulo').value.trim(),
        descricao: document.getElementById('lembreteDescricao').value.trim(),
        data: document.getElementById('lembreteData').value,
        hora: document.getElementById('lembreteHora').value,
        cor: document.getElementById('lembreteCor').value
    };
    if (!dados.titulo) { mostrarAlerta('erro', 'Título obrigatório'); return; }
    api('criar_lembrete', dados).then(r => {
        if (r.sucesso) { mostrarAlerta('sucesso', 'Lembrete criado!'); fecharModal('modalLembrete'); carregarLembretes(); }
        else mostrarAlerta('erro', r.erro);
    });
}

function deletarLembrete(id) {
    abrirModalConfirmar('Excluir Lembrete', 'Deseja excluir este lembrete?', () => {
        api('deletar_lembrete', {id}).then(r => { if (r.sucesso) { mostrarAlerta('sucesso', 'Lembrete excluído'); carregarLembretes(); } });
    });
}

// ==================== DOCUMENTOS ====================
function carregarDocumentos() {
    apiGet('listar_documentos').then(docs => {
        const lista = Array.isArray(docs) ? docs : [];
        document.getElementById('documentosGrid').innerHTML = lista.map(d => {
            const icone = d.tipo && d.tipo.includes('pdf') ? 'fa-file-pdf' : d.tipo && d.tipo.startsWith('image') ? 'fa-file-image' : 'fa-file-alt';
            const tam = d.tamanho ? (d.tamanho > 1024*1024 ? (d.tamanho/(1024*1024)).toFixed(1)+'MB' : Math.round(d.tamanho/1024)+'KB') : '';
            return `<div class="documento-item">
                <div class="documento-icon"><i class="fas ${icone}"></i></div>
                <div class="documento-info">
                    <div class="documento-nome">${d.nome}</div>
                    <div class="documento-meta">${tam} · ${d.autor_nome}</div>
                </div>
                <div style="display:flex;flex-direction:column;gap:5px">
                    <button class="task-btn" onclick="baixarDocumento(${d.id},'${d.nome.replace(/'/g,"\\'")}','${d.tipo||''}')" title="Baixar"><i class="fas fa-download"></i></button>
                    <button class="task-btn delete" onclick="deletarDocumento(${d.id})" title="Excluir"><i class="fas fa-trash"></i></button>
                </div>
            </div>`;
        }).join('') || '<div class="empty-state" style="grid-column:1/-1"><i class="fas fa-file-alt"></i><p>Nenhum documento</p></div>';
    });
}

function previewDocumento(event) {
    const file = event.target.files[0];
    if (!file) return;
    if (file.size > 10 * 1024 * 1024) { mostrarAlerta('erro', 'Arquivo deve ter no máximo 10MB'); event.target.value = ''; return; }
    docTipoArq = file.type;
    docTamanhoArq = file.size;
    docNomeArq = file.name;
    document.getElementById('docPreviewInfo').textContent = `${file.name} (${Math.round(file.size/1024)} KB)`;
    if (!document.getElementById('docNome').value) document.getElementById('docNome').value = file.name.replace(/\.[^/.]+$/, '');
    const reader = new FileReader();
    reader.onload = e => { docBase64 = e.target.result; };
    reader.readAsDataURL(file);
}

function abrirModalDocumento() {
    document.getElementById('docNome').value = '';
    document.getElementById('docArquivo').value = '';
    document.getElementById('docPreviewInfo').textContent = '';
    docBase64 = null;
    document.getElementById('modalDocumento').style.display = 'flex';
}

function uploadDocumento() {
    const nome = document.getElementById('docNome').value.trim();
    if (!nome || !docBase64) { mostrarAlerta('erro', nome ? 'Selecione um arquivo' : 'Nome obrigatório'); return; }
    api('upload_documento', {nome, tipo: docTipoArq, dados: docBase64, tamanho: docTamanhoArq}).then(r => {
        if (r.sucesso) { mostrarAlerta('sucesso', 'Documento salvo!'); fecharModal('modalDocumento'); carregarDocumentos(); docBase64 = null; }
        else mostrarAlerta('erro', r.erro);
    });
}

function baixarDocumento(id, nome, tipo) {
    apiGet('baixar_documento', {id}).then(r => {
        if (r.dados) {
            const a = document.createElement('a');
            a.href = r.dados;
            a.download = nome;
            a.click();
        } else mostrarAlerta('erro', 'Erro ao baixar');
    });
}

function deletarDocumento(id) {
    abrirModalConfirmar('Excluir Documento', 'Deseja excluir este documento?', () => {
        api('deletar_documento', {id}).then(r => { if (r.sucesso) { mostrarAlerta('sucesso', 'Documento excluído'); carregarDocumentos(); } });
    });
}

// ==================== RANKING ====================
function carregarRanking() {
    apiGet('listar_ranking').then(membros => {
        const lista = Array.isArray(membros) ? membros : [];
        document.getElementById('rankingList').innerHTML = lista.map((m, i) => {
            const posClass = i === 0 ? 'gold' : i === 1 ? 'silver' : i === 2 ? 'bronze' : '';
            const medal = i === 0 ? '🥇' : i === 1 ? '🥈' : i === 2 ? '🥉' : `#${i+1}`;
            return `<div class="ranking-item" ${m.id == usuarioAtual.id ? 'style="border-color:#4f9fff;background:rgba(79,159,255,.05)"' : ''}>
                <div class="ranking-pos ${posClass}">${medal}</div>
                <div class="ranking-avatar">${avatarHtml(m.foto, m.nome, 50)}</div>
                <div class="ranking-info">
                    <div class="ranking-nome">${m.nome} ${m.id == usuarioAtual.id ? '(você)' : ''}</div>
                    <div class="ranking-nivel">Nível ${m.nivel} · ${m.cargo === 'admin' ? '👑' : '👤'}</div>
                </div>
                <div class="ranking-pts">
                    <div class="ranking-pts-val">⭐ ${m.pontos}</div>
                    <div class="ranking-pts-label">pontos</div>
                </div>
            </div>`;
        }).join('') || '<div class="empty-state"><i class="fas fa-trophy"></i><p>Nenhum membro</p></div>';
    });
}

// ==================== CONFIGURAÇÕES ====================
function iniciarConfig() {
    mostrarSubConfig('geral');
    // Preencher config geral
    document.getElementById('configNomeFamilia').value = usuarioAtual.familia_nome || '';

    // Foto família
    if (usuarioAtual.familia_foto && usuarioAtual.familia_foto !== 'null') {
        document.getElementById('familyPhotoImg').src = usuarioAtual.familia_foto;
        document.getElementById('familyPhotoImg').style.display = 'block';
        document.getElementById('familyPhotoPlaceholder').style.display = 'none';
    } else {
        document.getElementById('familyPhotoImg').style.display = 'none';
        document.getElementById('familyPhotoPlaceholder').style.display = 'block';
    }

    // Config perfil
    document.getElementById('perfilNome').value = usuarioAtual.nome;
    if (usuarioAtual.foto && usuarioAtual.foto !== 'null') {
        document.getElementById('perfilFotoImg').src = usuarioAtual.foto;
        document.getElementById('perfilFotoImg').style.display = 'block';
        document.getElementById('perfilFotoPlaceholder').style.display = 'none';
    }
}

function mostrarSubConfig(pane) {
    document.querySelectorAll('.config-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.config-menu-item').forEach(b => b.classList.remove('active'));
    document.getElementById('config' + pane.charAt(0).toUpperCase() + pane.slice(1)).classList.add('active');
    event?.target?.classList?.add('active');
    if (pane === 'membros') carregarConfigMembros();
    if (pane === 'auditoria') carregarAuditoria();
}

function carregarConfigMembros() {
    apiGet('listar_membros').then(membros => {
        const el = document.getElementById('configMembrosLista');
        const lista = Array.isArray(membros) ? membros : [];
        el.innerHTML = lista.map(m => `
            <div class="config-card">
                <div class="config-card-header">
                    <div style="width:40px;height:40px;border-radius:50%;background:rgba(79,159,255,.15);border:2px solid #4f9fff;display:flex;align-items:center;justify-content:center;overflow:hidden">
                        ${avatarHtml(m.foto, m.nome, 40)}
                    </div>
                    <h4>${m.nome} ${m.cargo === 'admin' ? '👑' : ''}</h4>
                </div>
                <div class="config-card-body">
                    <p style="color:#8ba3c7;font-size:.9rem">👤 @${m.usuario} · ⭐ ${m.pontos} pts · Nível ${m.nivel}</p>
                    <div style="margin-top:10px;display:flex;gap:8px">
                        ${(usuarioAtual.id == m.id || usuarioAtual.cargo === 'admin') ? `<button class="config-btn" onclick="abrirModalEditarMembro(${m.id},'${m.nome.replace(/'/g,"\\'")}')"><i class="fas fa-edit"></i> Editar</button>` : ''}
                        ${(usuarioAtual.cargo === 'admin' && usuarioAtual.id != m.id) ? `<button class="config-btn config-btn-danger" onclick="removerMembro(${m.id},'${m.nome.replace(/'/g,"\\'")}')"><i class="fas fa-trash"></i> Remover</button>` : ''}
                    </div>
                </div>
            </div>
        `).join('');
    });
}

function salvarConfigFamilia() {
    if (usuarioAtual.cargo !== 'admin') { mostrarAlerta('erro', 'Apenas admins podem editar a família'); return; }
    const nome = document.getElementById('configNomeFamilia').value.trim();
    if (!nome) { mostrarAlerta('erro', 'Nome obrigatório'); return; }
    api('atualizar_familia', {nome}).then(r => {
        if (r.sucesso) {
            usuarioAtual.familia_nome = nome;
            atualizarSidebar();
            mostrarAlerta('sucesso', 'Configurações salvas!');
        } else mostrarAlerta('erro', r.erro);
    });
}

function alterarFotoFamilia(event) {
    const file = event.target.files[0];
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) { mostrarAlerta('erro', 'Imagem deve ter no máximo 5MB'); return; }
    const reader = new FileReader();
    reader.onload = e => {
        const foto = e.target.result;
        api('atualizar_familia', {nome: usuarioAtual.familia_nome || '', foto}).then(r => {
            if (r.sucesso) {
                usuarioAtual.familia_foto = foto;
                document.getElementById('familyPhotoImg').src = foto;
                document.getElementById('familyPhotoImg').style.display = 'block';
                document.getElementById('familyPhotoPlaceholder').style.display = 'none';
                mostrarAlerta('sucesso', 'Foto da família atualizada!');
            } else mostrarAlerta('erro', r.erro);
        });
    };
    reader.readAsDataURL(file);
}

function removerFotoFamilia() {
    if (usuarioAtual.cargo !== 'admin') { mostrarAlerta('erro', 'Apenas admins podem editar a família'); return; }
    api('atualizar_familia', {nome: usuarioAtual.familia_nome || '', foto: ''}).then(r => {
        if (r.sucesso) {
            usuarioAtual.familia_foto = null;
            document.getElementById('familyPhotoImg').style.display = 'none';
            document.getElementById('familyPhotoPlaceholder').style.display = 'block';
            mostrarAlerta('sucesso', 'Foto removida');
        }
    });
}

function previewPerfilFoto(event) {
    const file = event.target.files[0];
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) { mostrarAlerta('erro', 'Imagem deve ter no máximo 5MB'); return; }
    const reader = new FileReader();
    reader.onload = e => {
        perfilFotoBase64 = e.target.result;
        document.getElementById('perfilFotoImg').src = perfilFotoBase64;
        document.getElementById('perfilFotoImg').style.display = 'block';
        document.getElementById('perfilFotoPlaceholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
}

function salvarPerfil() {
    const nome = document.getElementById('perfilNome').value.trim();
    const senhaAtual = document.getElementById('perfilSenhaAtual').value;
    const novaSenha = document.getElementById('perfilNovaSenha').value;
    if (!nome) { mostrarAlerta('erro', 'Nome obrigatório'); return; }
    const dados = {id: usuarioAtual.id, nome, senha_atual: senhaAtual, nova_senha: novaSenha};
    if (perfilFotoBase64) dados.foto = perfilFotoBase64;
    api('atualizar_membro', dados).then(r => {
        if (r.sucesso) {
            usuarioAtual.nome = nome;
            if (perfilFotoBase64) usuarioAtual.foto = perfilFotoBase64;
            atualizarSidebar();
            mostrarAlerta('sucesso', 'Perfil atualizado!');
            perfilFotoBase64 = null;
            document.getElementById('perfilSenhaAtual').value = '';
            document.getElementById('perfilNovaSenha').value = '';
        } else mostrarAlerta('erro', r.erro || 'Erro ao salvar');
    });
}

function carregarAuditoria() {
    const filtro = document.getElementById('filtroAuditoria')?.value || '';
    apiGet('listar_atividades', {filtro}).then(ativ => {
        const lista = Array.isArray(ativ) ? ativ : [];
        document.getElementById('auditoriaLista').innerHTML = lista.map(a => `
            <div class="activity-item">
                <div class="activity-icon"><i class="fas ${getIconeAtividade(a.tipo)}"></i></div>
                <div class="activity-details">
                    <div class="activity-text">${a.descricao}</div>
                    <div class="activity-time">${formatDateTime(a.criado_em)}${a.membro_nome ? ' · ' + a.membro_nome : ''}</div>
                </div>
            </div>
        `).join('') || '<div style="color:#8ba3c7;text-align:center;padding:30px">Nenhuma atividade</div>';
    });
}

// ==================== INICIALIZAÇÃO ====================
(function init() {
    apiGet('verificar_sessao').then(r => {
        if (r.logado) {
            usuarioAtual = r.usuario;
            document.getElementById('splash-screen').style.display = 'none';
            mostrarDashboard();
        } else {
            // Splash e depois login
            setTimeout(() => {
                const splash = document.getElementById('splash-screen');
                splash.style.opacity = '0';
                setTimeout(() => {
                    splash.style.display = 'none';
                    document.getElementById('login-screen').classList.add('active');
                }, 800);
            }, 2500);
        }
    });
})();
</script>
</body>
</html>