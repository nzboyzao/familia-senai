<?php
require_once __DIR__ . '/includes/config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>FamilyHub · Sistema Familiar Completo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>

        /* =================================================================
           1. DESIGN TOKENS (VARIÁVEIS CSS)
        ================================================================= */

        /* -- Tema Claro (padrão) -- */
        :root,
        :root[data-theme="light"] {
            /* Cor principal */
            --accent:           #2d9e6b;
            --accent-light:     #4dc48a;
            --accent-soft:      #eaf7f1;
            --accent-glow:      rgba(45, 158, 107, 0.18);

            /* Cores de feedback */
            --danger:           #e05566;
            --danger-soft:      #fdf0f2;
            --success:          #2d9e6b;
            --warning:          #f0b429;

            /* Fundos */
            --bg-page:          #f2f6f4;
            --bg-card:          #ffffff;
            --bg-input:         #f0f5f2;
            --bg-sidebar:       #ffffff;
            --bg-modal:         #ffffff;
            --bg-auth:          rgba(255, 255, 255, 0.97);

            /* Texto */
            --text-primary:     #172b22;
            --text-secondary:   #3d5c4a;
            --text-muted:       #8aaa98;
            --text-white:       #ffffff;

            /* Bordas */
            --border:           rgba(45, 158, 107, 0.18);
            --border-focus:     #2d9e6b;

            /* Sombras */
            --shadow-sm:        0 2px 10px rgba(45, 158, 107, 0.07);
            --shadow-md:        0 8px 28px rgba(45, 158, 107, 0.12);
            --shadow-lg:        0 24px 60px rgba(45, 158, 107, 0.16);
            --shadow-card:      0 4px 20px rgba(0, 0, 0, 0.06);

            /* Bordas arredondadas */
            --radius-sm:        10px;
            --radius-md:        16px;
            --radius-lg:        24px;
            --radius-xl:        32px;
        }

        /* -- Tema Escuro -- */
        :root[data-theme="dark"] {
            /* Cor principal */
            --accent:           #3ec07e;
            --accent-light:     #5dd898;
            --accent-soft:      #1a3327;
            --accent-glow:      rgba(62, 192, 126, 0.2);

            /* Cores de feedback */
            --danger:           #f27788;
            --danger-soft:      #2e1a1e;
            --success:          #3ec07e;
            --warning:          #f7c35c;

            /* Fundos */
            --bg-page:          #111d18;
            --bg-card:          #1c2e25;
            --bg-input:         #162019;
            --bg-sidebar:       #172619;
            --bg-modal:         #1c2e25;
            --bg-auth:          rgba(17, 29, 24, 0.98);

            /* Texto */
            --text-primary:     #e2f0e8;
            --text-secondary:   #a8ccb8;
            --text-muted:       #6b9278;
            --text-white:       #ffffff;

            /* Bordas */
            --border:           rgba(62, 192, 126, 0.2);
            --border-focus:     #3ec07e;

            /* Sombras */
            --shadow-sm:        0 2px 10px rgba(0, 0, 0, 0.35);
            --shadow-md:        0 8px 28px rgba(0, 0, 0, 0.45);
            --shadow-lg:        0 24px 60px rgba(0, 0, 0, 0.55);
            --shadow-card:      0 4px 20px rgba(0, 0, 0, 0.3);
        }


        /* =================================================================
           2. RESET & BASE
        ================================================================= */

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            background: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            overflow: hidden;
            transition: background 0.3s, color 0.3s;
        }


        /* =================================================================
           3. ANIMAÇÕES GLOBAIS
        ================================================================= */

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9) translateY(10px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }


        /* =================================================================
           4. ELEMENTOS GLOBAIS (FUNDO, TEMA, ALERTAS)
        ================================================================= */

        /* Fundo de partículas */
        #particles-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            opacity: 0.3;
        }

        /* Botão de alternar tema */
        .theme-toggle {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 999;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: var(--bg-card);
            border: 1.5px solid var(--accent);
            border-radius: 50px;
            cursor: pointer;
            color: var(--text-primary);
            font-family: inherit;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: var(--shadow-md);
            transition: all 0.25s;
        }
        .theme-toggle:hover {
            transform: scale(1.05);
            background: var(--accent-soft);
        }
        .theme-toggle i {
            color: var(--accent);
        }

        /* Alerta customizado */
        #customAlert {
            display: none;
            position: fixed;
            top: 22px;
            right: 22px;
            z-index: 9999;
            flex-direction: row;
            align-items: center;
            gap: 12px;
            max-width: 340px;
            padding: 14px 18px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-size: 0.88rem;
            font-weight: 500;
            box-shadow: var(--shadow-lg);
        }
        #customAlert.success {
            border-color: var(--accent);
            background: var(--accent-soft);
        }
        #customAlert.error {
            border-color: var(--danger);
            background: var(--danger-soft);
        }

        /* Estado vazio genérico */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
        }
        .empty-state i {
            display: block;
            margin-bottom: 12px;
            font-size: 2.5rem;
            opacity: 0.4;
        }
        .empty-state p {
            font-size: 0.9rem;
        }


        /* =================================================================
           5. FORMULÁRIOS (INPUTS, LABELS, SELECTS)
        ================================================================= */

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }
        .input-group label {
            display: block;
            margin-bottom: 7px;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.2px;
        }
        .input-group input,
        .input-group select,
        .input-group textarea {
            width: 100%;
            padding: 13px 16px;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.95rem;
            outline: none;
            transition: all 0.2s;
        }
        .input-group input:focus,
        .input-group select:focus,
        .input-group textarea:focus {
            border-color: var(--border-focus);
            background: var(--bg-card);
            box-shadow: 0 0 0 4px var(--accent-glow);
        }
        .input-group select option {
            background: var(--bg-card);
            color: var(--text-primary);
        }


        /* =================================================================
           6. BOTÕES GLOBAIS
        ================================================================= */

        /* Botão primário (auth) */
        .btn-primary {
            display: block;
            width: 100%;
            margin: 20px 0 14px;
            padding: 15px 20px;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            border: none;
            border-radius: var(--radius-md);
            color: #fff;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            cursor: pointer;
            box-shadow: 0 6px 20px var(--accent-glow);
            transition: all 0.25s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px var(--accent-glow);
            filter: brightness(1.05);
        }
        .btn-primary:active {
            transform: translateY(0);
        }

        /* Botão de ícone (header do dashboard) */
        .btn-icon {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 10px 16px;
            background: var(--bg-card);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s;
        }
        .btn-icon:hover {
            border-color: var(--accent);
            background: var(--accent-soft);
            color: var(--accent);
        }
        .btn-icon.danger:hover {
            border-color: var(--danger);
            background: var(--danger-soft);
            color: var(--danger);
        }

        /* Botão de adicionar (seções do dashboard) */
        .btn-add {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: var(--accent);
            border: none;
            border-radius: var(--radius-sm);
            color: #fff;
            font-family: inherit;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px var(--accent-glow);
            transition: all 0.2s;
        }
        .btn-add:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
        }

        /* Link de alternância (auth) */
        .toggle-link {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.88rem;
        }
        .toggle-link a {
            color: var(--accent);
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }
        .toggle-link a:hover {
            text-decoration: underline;
        }


        /* =================================================================
           7. UPLOAD DE FOTO
        ================================================================= */

        .photo-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 8px;
        }

        .photo-preview-circle {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 110px;
            height: 110px;
            margin-bottom: 10px;
            background: var(--bg-input);
            border: 2.5px dashed var(--accent);
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s;
        }
        .photo-preview-circle:hover {
            background: var(--accent-soft);
            border-color: var(--accent-light);
            transform: scale(1.03);
        }
        .photo-preview-circle.has-image {
            border-style: solid;
        }

        .photo-preview-circle .preview-img {
            display: none;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-preview-circle.has-image .preview-img {
            display: block;
        }

        .photo-preview-circle .placeholder-icon {
            font-size: 2rem;
            opacity: 0.7;
            margin-bottom: 4px;
        }
        .photo-preview-circle .placeholder-text {
            color: var(--text-muted);
            font-size: 0.72rem;
            font-weight: 500;
        }
        .photo-preview-circle.has-image .placeholder-icon,
        .photo-preview-circle.has-image .placeholder-text {
            display: none;
        }

        /* Inputs de arquivo ocultos */
        #foto-familia,
        #foto-membro,
        #foto-membro-edit {
            display: none;
        }

        .file-name-small {
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-top: 4px;
            color: var(--text-muted);
            font-size: 0.78rem;
        }

        /* Info box (dica de cadastro) */
        .info-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
            padding: 14px 16px;
            background: var(--accent-soft);
            border: 1px solid rgba(45, 158, 107, 0.28);
            border-radius: var(--radius-md);
            color: var(--text-secondary);
            font-size: 0.88rem;
        }
        .info-box i {
            color: var(--accent);
            flex-shrink: 0;
        }


        /* =================================================================
           8. AUTENTICAÇÃO (SPLASH + TELAS DE LOGIN/CADASTRO)
        ================================================================= */

        /* Container que centraliza o painel */
        #auth-container {
            position: fixed;
            inset: 0;
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        /* Painel de autenticação */
        #auth-app {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            max-height: 95vh;
            margin: 0 20px;
            background: var(--bg-auth);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: var(--shadow-lg);
            overflow-y: auto;
        }
        #auth-app .screen {
            padding: 36px 32px 44px;
        }

        /* Splash screen */
        #splash-screen {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 540px;
            padding: 40px 20px;
            transition: opacity 0.8s ease;
        }

        .logo-container {
            text-align: center;
            animation: fadeIn 1.4s ease;
        }

        .logo-image {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 128px;
            height: 128px;
            margin: 0 auto 22px;
            background: linear-gradient(145deg, var(--accent-soft), rgba(45, 158, 107, 0.12));
            border: 2.5px solid var(--accent);
            border-radius: 50%;
            box-shadow: 0 0 40px var(--accent-glow);
        }
        .logo-image svg {
            width: 72px;
            height: 72px;
        }

        .logo-text {
            margin-bottom: 6px;
            font-family: 'DM Serif Display', serif;
            font-size: 2.8rem;
            font-weight: 400;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }
        .splash-sub {
            color: var(--text-muted);
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* Telas de auth (login, cadastro, etc.) */
        .screen {
            display: none;
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 0.5s, transform 0.5s;
        }
        .screen.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .screen-title {
            margin-bottom: 6px;
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            font-weight: 400;
            color: var(--text-primary);
            text-align: center;
        }
        .screen-sub {
            margin-bottom: 30px;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--border);
            color: var(--text-muted);
            font-size: 0.9rem;
            text-align: center;
        }

        /* Opções de tipo de cadastro */
        .register-options {
            display: flex;
            gap: 14px;
            margin-bottom: 24px;
        }
        .register-option {
            flex: 1;
            padding: 22px 14px;
            background: var(--bg-input);
            border: 2px solid var(--border);
            border-radius: var(--radius-lg);
            text-align: center;
            cursor: pointer;
            transition: all 0.25s;
        }
        .register-option:hover {
            border-color: var(--accent);
            background: var(--accent-soft);
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }
        .register-option i {
            display: block;
            margin-bottom: 10px;
            font-size: 1.8rem;
            color: var(--accent);
        }
        .register-option h3 {
            margin-bottom: 4px;
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 700;
        }
        .register-option p {
            color: var(--text-muted);
            font-size: 0.78rem;
            line-height: 1.4;
        }


        /* =================================================================
           9. DASHBOARD — LAYOUT GERAL
        ================================================================= */

        #dashboard-container {
            display: none;
            width: 100%;
            height: 100vh;
            position: relative;
            z-index: 10;
        }

        .dashboard-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }


        /* =================================================================
           10. SIDEBAR
        ================================================================= */

        .sidebar {
            display: flex;
            flex-direction: column;
            width: 270px;
            height: 100vh;
            padding: 28px 18px;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            box-shadow: 2px 0 20px rgba(45, 158, 107, 0.06);
            overflow-y: auto;
            position: relative;
            z-index: 20;
            transition: all 0.3s;
        }

        .logo-sidebar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 38px;
            padding-left: 8px;
        }
        .logo-sidebar svg {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
        }
        .logo-sidebar span {
            font-family: 'DM Serif Display', serif;
            font-size: 1.5rem;
            color: var(--text-primary);
        }

        /* Itens do menu */
        .menu-items {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 20px;
        }
        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 14px;
            border: 1.5px solid transparent;
            border-radius: var(--radius-md);
            color: var(--text-secondary);
            font-size: 0.92rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        .menu-item:hover {
            background: var(--accent-soft);
            color: var(--accent);
        }
        .menu-item.active {
            background: var(--accent-soft);
            border-color: rgba(45, 158, 107, 0.25);
            color: var(--accent);
            font-weight: 700;
        }
        .menu-item i {
            width: 22px;
            font-size: 1.1rem;
            text-align: center;
            flex-shrink: 0;
        }

        /* Usuário no rodapé da sidebar */
        .user-info-sidebar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 10px;
            margin-top: auto;
            border-top: 1px solid var(--border);
        }
        .user-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            background: var(--accent-soft);
            border: 2px solid var(--accent);
            border-radius: 50%;
            overflow: hidden;
            font-weight: 700;
            color: var(--accent);
        }
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .user-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .user-family {
            margin-top: 2px;
            font-size: 0.78rem;
            color: var(--text-muted);
        }


        /* =================================================================
           11. CONTEÚDO PRINCIPAL (HEADER + LAYOUT)
        ================================================================= */

        .main-content {
            flex: 1;
            height: 100vh;
            padding: 28px 32px;
            background: var(--bg-page);
            overflow-y: auto;
            position: relative;
            z-index: 15;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .page-title h1 {
            font-size: 1.9rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }
        .page-title p {
            margin-top: 3px;
            font-size: 0.88rem;
            color: var(--text-muted);
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        /* Header e título padrão de cada seção */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }
        .section-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Card genérico de seção */
        .tasks-container,
        .calendar-container,
        .recent-activities,
        .members-container,
        .chat-container,
        .gallery-container,
        .financas-container,
        .lembretes-container,
        .documentos-container,
        .ranking-container,
        .config-container {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-card);
        }


        /* =================================================================
           12. CARDS DE STATUS (HOME)
        ================================================================= */

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .status-card {
            position: relative;
            overflow: hidden;
            padding: 22px 20px 18px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            transition: all 0.25s;
        }
        .status-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent-light));
            opacity: 0;
            transition: opacity 0.25s;
        }
        .status-card:hover {
            transform: translateY(-4px);
            border-color: var(--accent);
            box-shadow: var(--shadow-md);
        }
        .status-card:hover::before {
            opacity: 1;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }
        .card-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            background: var(--accent-soft);
            border: 1px solid rgba(45, 158, 107, 0.2);
            border-radius: var(--radius-sm);
            color: var(--accent);
            font-size: 1.05rem;
        }
        .card-title {
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .card-value {
            margin-bottom: 6px;
            font-size: 2.1rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, var(--text-primary) 40%, var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .card-sub {
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--text-muted);
        }
        .trend-up {
            font-weight: 600;
            color: var(--success);
        }


        /* =================================================================
           13. CALENDÁRIO
        ================================================================= */

        .tasks-section {
            display: grid;
            grid-template-columns: 1fr 1.4fr;
            gap: 18px;
            margin-bottom: 18px;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }
        .calendar-month {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--text-primary);
        }

        .calendar-nav {
            display: flex;
            gap: 6px;
        }
        .calendar-nav button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .calendar-nav button:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 3px;
            margin-bottom: 6px;
        }
        .calendar-weekdays div {
            padding: 5px 0;
            color: var(--accent);
            font-size: 0.65rem;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
        }
        .calendar-day {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            aspect-ratio: 1;
            background: var(--bg-input);
            border: 1.5px solid transparent;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.18s;
        }
        .calendar-day:hover {
            background: var(--accent-soft);
            border-color: var(--accent);
            transform: scale(1.06);
        }
        .calendar-day.empty {
            background: transparent;
            border: none;
            cursor: default;
        }
        .calendar-day.empty:hover {
            transform: none;
        }
        .calendar-day.today {
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            box-shadow: 0 4px 12px var(--accent-glow);
        }
        .calendar-day.today .day-number {
            color: #fff;
            font-weight: 800;
        }
        .calendar-day.today .task-count {
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
        }
        .calendar-day.has-task:not(.today) {
            border-color: var(--accent);
            background: var(--accent-soft);
        }
        .calendar-day.selected:not(.today) {
            background: var(--accent-soft);
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .day-number {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1;
        }
        .task-count {
            margin-top: 2px;
            padding: 1px 4px;
            background: var(--accent);
            border-radius: 5px;
            color: #fff;
            font-size: 0.55rem;
            font-weight: 800;
        }

        /* Detalhes do dia selecionado */
        .day-details-container {
            margin-bottom: 18px;
            padding: 22px;
            background: linear-gradient(135deg, var(--accent-soft), var(--bg-card) 60%);
            border: 1.5px solid rgba(45, 158, 107, 0.22);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            animation: fadeInUp 0.3s ease;
        }
        .day-details-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }
        .day-details-header h3 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .day-details-date {
            padding: 3px 12px;
            background: var(--accent-soft);
            border: 1px solid rgba(45, 158, 107, 0.25);
            border-radius: 20px;
            color: var(--accent);
            font-size: 0.82rem;
            font-weight: 700;
        }

        .day-task-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            margin-bottom: 8px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s;
        }
        .day-task-item:hover {
            border-color: var(--accent);
            transform: translateX(4px);
        }
        .day-task-check {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            flex-shrink: 0;
            border: 2px solid var(--accent);
            border-radius: 50%;
            color: #fff;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .day-task-check.completed {
            background: var(--accent);
        }
        .day-task-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        .day-task-meta {
            display: flex;
            gap: 8px;
            margin-top: 3px;
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        .empty-day {
            padding: 22px;
            text-align: center;
            color: var(--text-muted);
        }
        .empty-day i {
            display: block;
            margin-bottom: 6px;
            font-size: 1.8rem;
            opacity: 0.4;
        }


        /* =================================================================
           14. TAREFAS
        ================================================================= */

        .task-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .task-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            transition: all 0.2s;
        }
        .task-item:hover {
            border-color: var(--accent);
            box-shadow: var(--shadow-md);
        }

        .task-info {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .task-check {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            flex-shrink: 0;
            border: 2px solid var(--accent);
            border-radius: 50%;
            color: #fff;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .task-check.completed {
            background: var(--accent);
        }
        .task-details {
            flex: 1;
        }
        .task-title {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        .task-points {
            padding: 2px 8px;
            background: var(--accent-soft);
            border-radius: 20px;
            color: var(--accent);
            font-size: 0.72rem;
            font-weight: 800;
        }
        .task-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            margin-top: 4px;
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        /* Badges de prioridade */
        .priority-alta   { color: var(--danger);  font-weight: 700; }
        .priority-media  { color: var(--warning); font-weight: 700; }
        .priority-baixa  { color: var(--success); font-weight: 700; }

        .task-actions {
            display: flex;
            gap: 6px;
        }
        .task-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .task-btn:hover {
            background: var(--accent-soft);
            border-color: var(--accent);
            color: var(--accent);
        }
        .task-btn.delete:hover {
            background: var(--danger-soft);
            border-color: var(--danger);
            color: var(--danger);
        }

        /* Filtros de tarefas */
        .task-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 18px;
        }
        .filter-btn {
            padding: 7px 16px;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: 20px;
            color: var(--text-secondary);
            font-family: inherit;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .filter-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
        }
        .filter-btn.active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        /* Estatísticas de tarefas */
        .task-stats {
            display: flex;
            gap: 16px;
            padding: 16px;
            background: var(--bg-input);
            border-radius: var(--radius-md);
            margin-bottom: 8px;
        }
        .stat-item {
            flex: 1;
            text-align: center;
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--accent);
        }
        .stat-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }


        /* =================================================================
           15. ATIVIDADES RECENTES
        ================================================================= */

        .activity-list {
            display: flex;
            flex-direction: column;
        }
        .activity-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 0;
            border-bottom: 1px solid var(--border);
        }
        .activity-item:last-child {
            border-bottom: none;
        }
        .activity-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            background: var(--accent-soft);
            border: 1px solid rgba(45, 158, 107, 0.2);
            border-radius: 50%;
            color: var(--accent);
            font-size: 0.9rem;
        }
        .activity-text {
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text-primary);
        }
        .activity-time {
            margin-top: 2px;
            font-size: 0.75rem;
            color: var(--text-muted);
        }


        /* =================================================================
           16. MEMBROS
        ================================================================= */

        .members-header {
            margin-bottom: 20px;
        }
        .members-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .members-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 18px;
        }
        .member-card {
            padding: 24px 20px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            text-align: center;
            transition: all 0.25s;
        }
        .member-card:hover {
            border-color: var(--accent);
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        .member-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            margin: 0 auto 14px;
            background: var(--accent-soft);
            border: 2.5px solid var(--accent);
            border-radius: 50%;
            overflow: hidden;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--accent);
        }
        .member-name {
            margin-bottom: 4px;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .member-role {
            margin-bottom: 6px;
            font-size: 0.78rem;
            color: var(--text-muted);
        }
        .member-points {
            margin-bottom: 14px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--accent);
        }
        .member-actions {
            display: flex;
            justify-content: center;
            gap: 8px;
        }
        .member-btn {
            padding: 7px 12px;
            background: var(--bg-card);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 0.82rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .member-btn:hover {
            border-color: var(--accent);
            background: var(--accent-soft);
            color: var(--accent);
        }
        .member-btn.danger:hover {
            border-color: var(--danger);
            background: var(--danger-soft);
            color: var(--danger);
        }


        /* =================================================================
           17. CHAT
        ================================================================= */

        .chat-container {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 140px);
            overflow: hidden;
        }
        .chat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
        }
        .chat-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .points-badge {
            padding: 4px 12px;
            background: var(--accent-soft);
            border-radius: 20px;
            color: var(--accent);
            font-size: 0.78rem;
            font-weight: 700;
        }

        .chat-messages {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding: 20px 24px;
            overflow-y: auto;
        }

        .message {
            display: flex;
            align-items: flex-end;
            gap: 10px;
        }
        .message.mine {
            flex-direction: row-reverse;
        }
        .msg-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            flex-shrink: 0;
            background: var(--accent-soft);
            border: 2px solid var(--accent);
            border-radius: 50%;
            overflow: hidden;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--accent);
        }
        .msg-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .msg-bubble {
            max-width: 70%;
        }
        .msg-author {
            margin-bottom: 4px;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--accent);
        }
        .msg-text {
            padding: 10px 14px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 14px 14px 14px 4px;
            color: var(--text-primary);
            font-size: 0.9rem;
            line-height: 1.5;
        }
        .message.mine .msg-text {
            background: var(--accent);
            border-color: transparent;
            border-radius: 14px 14px 4px 14px;
            color: #fff;
        }
        .msg-time {
            padding: 0 4px;
            margin-top: 4px;
            font-size: 0.68rem;
            color: var(--text-muted);
        }
        .message.mine .msg-time {
            text-align: right;
        }

        .chat-input-area {
            display: flex;
            gap: 10px;
            padding: 16px 20px;
            background: var(--bg-card);
            border-top: 1px solid var(--border);
        }
        .chat-input {
            flex: 1;
            max-height: 120px;
            padding: 12px 16px;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.9rem;
            resize: none;
            outline: none;
            transition: border 0.2s;
        }
        .chat-input:focus {
            border-color: var(--border-focus);
        }
        .chat-send-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 12px 18px;
            background: var(--accent);
            border: none;
            border-radius: var(--radius-md);
            color: #fff;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px var(--accent-glow);
            transition: all 0.2s;
        }
        .chat-send-btn:hover {
            filter: brightness(1.08);
        }


        /* =================================================================
           18. GALERIA
        ================================================================= */

        .gallery-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .gallery-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 14px;
        }
        .gallery-item {
            position: relative;
            aspect-ratio: 1;
            overflow: hidden;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.25s;
        }
        .gallery-item:hover {
            border-color: var(--accent);
            transform: scale(1.02);
            box-shadow: var(--shadow-md);
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .gallery-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 12px;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.2s;
        }
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        .gallery-item-title {
            color: #fff;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .gallery-item-actions {
            display: flex;
            gap: 8px;
            margin-top: 6px;
        }
        .gallery-action-btn {
            padding: 5px 8px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .gallery-action-btn:hover {
            background: rgba(255, 255, 255, 0.35);
        }


        /* =================================================================
           19. FINANÇAS
        ================================================================= */

        .financas-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .financas-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .financas-resumo {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }
        .resumo-card {
            padding: 18px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            text-align: center;
        }
        .resumo-label {
            margin-bottom: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .resumo-valor {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
        }
        .resumo-valor.positivo { color: var(--success); }
        .resumo-valor.negativo { color: var(--danger); }

        .transacoes-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .transacao-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            transition: all 0.2s;
        }
        .transacao-item:hover {
            border-color: var(--accent);
        }
        .transacao-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border-radius: 50%;
            font-size: 0.9rem;
        }
        .transacao-icon.receita { background: #e8f8f0; color: var(--success); }
        .transacao-icon.despesa { background: var(--danger-soft); color: var(--danger); }

        .transacao-info {
            flex: 1;
        }
        .transacao-descricao {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        .transacao-data {
            margin-top: 2px;
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        .transacao-valor {
            font-size: 1rem;
            font-weight: 800;
            white-space: nowrap;
        }
        .transacao-valor.receita { color: var(--success); }
        .transacao-valor.despesa { color: var(--danger); }


        /* =================================================================
           20. LEMBRETES
        ================================================================= */

        .lembretes-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .lembretes-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .lembretes-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .lembrete-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 18px;
            background: var(--bg-input);
            border-top: 1px solid var(--border);
            border-right: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            border-left: 4px solid;     /* cor dinâmica via inline style */
            border-radius: var(--radius-md);
            transition: all 0.2s;
        }
        .lembrete-item:hover {
            transform: translateX(4px);
            box-shadow: var(--shadow-sm);
        }
        .lembrete-info {
            flex: 1;
        }
        .lembrete-titulo {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .lembrete-meta {
            display: flex;
            gap: 10px;
            margin-top: 4px;
            font-size: 0.78rem;
            color: var(--text-muted);
        }


        /* =================================================================
           21. DOCUMENTOS
        ================================================================= */

        .documentos-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .documentos-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .documentos-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .documento-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            transition: all 0.2s;
        }
        .documento-item:hover {
            border-color: var(--accent);
        }
        .documento-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            background: var(--accent-soft);
            border-radius: var(--radius-sm);
            color: var(--accent);
            font-size: 1.2rem;
        }
        .documento-info {
            flex: 1;
        }
        .documento-nome {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .documento-meta {
            margin-top: 2px;
            font-size: 0.75rem;
            color: var(--text-muted);
        }


        /* =================================================================
           22. RANKING
        ================================================================= */

        .ranking-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .ranking-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .ranking-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .ranking-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 18px;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            transition: all 0.2s;
        }
        .ranking-item:hover {
            border-color: var(--accent);
            transform: translateX(4px);
        }
        .ranking-pos {
            min-width: 36px;
            font-size: 1.4rem;
            text-align: center;
        }
        .ranking-pos.gold   { color: #f7c948; }
        .ranking-pos.silver { color: #b0b8c1; }
        .ranking-pos.bronze { color: #cd7f32; }

        .ranking-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            background: var(--accent-soft);
            border: 2px solid var(--accent);
            border-radius: 50%;
            overflow: hidden;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent);
        }
        .ranking-info {
            flex: 1;
        }
        .ranking-nome {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .ranking-nivel {
            margin-top: 2px;
            font-size: 0.78rem;
            color: var(--text-muted);
        }
        .ranking-pts {
            text-align: right;
        }
        .ranking-pts-val {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--accent);
        }
        .ranking-pts-label {
            font-size: 0.72rem;
            color: var(--text-muted);
        }


        /* =================================================================
           23. CONFIGURAÇÕES
        ================================================================= */

        .config-header {
            margin-bottom: 22px;
        }
        .config-header h2 {
            margin-bottom: 4px;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .config-header p {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .config-menu {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }
        .config-menu-item {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: 20px;
            color: var(--text-secondary);
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .config-menu-item:hover {
            border-color: var(--accent);
            color: var(--accent);
        }
        .config-menu-item.active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .config-pane {
            display: none;
        }
        .config-pane.active {
            display: block;
            animation: fadeInUp 0.25s ease;
        }

        .config-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .config-card {
            padding: 16px 18px;
            margin-bottom: 12px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
        }
        .config-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }
        .config-card-header h4 {
            font-weight: 700;
            color: var(--text-primary);
        }

        .config-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: var(--bg-card);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            color: var(--text-secondary);
            font-family: inherit;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .config-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
        }
        .config-btn-danger:hover {
            background: var(--danger-soft) !important;
            border-color: var(--danger) !important;
            color: var(--danger) !important;
        }

        .config-save-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-top: 16px;
            padding: 11px 22px;
            background: var(--accent);
            border: none;
            border-radius: var(--radius-sm);
            color: #fff;
            font-family: inherit;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px var(--accent-glow);
            transition: all 0.2s;
        }
        .config-save-btn:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
        }

        /* Foto de família/perfil na config */
        .family-photo-edit {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }
        .family-photo-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            flex-shrink: 0;
            background: var(--bg-input);
            border: 2.5px solid var(--accent);
            border-radius: 50%;
            overflow: hidden;
            font-size: 2rem;
        }
        .family-photo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .family-photo-actions {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }


        /* =================================================================
           24. MODAIS
        ================================================================= */

        .modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: rgba(17, 29, 24, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            padding: 28px;
            background: var(--bg-modal);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            overflow-y: auto;
            box-shadow: var(--shadow-lg);
            animation: fadeInUp 0.25s ease;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }
        .modal-header h3 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .modal-close {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            color: var(--text-muted);
            font-size: 1.1rem;
            line-height: 1;
            cursor: pointer;
            transition: all 0.2s;
        }
        .modal-close:hover {
            background: var(--danger-soft);
            border-color: var(--danger);
            color: var(--danger);
        }

        .modal-input-group {
            margin-bottom: 16px;
        }
        .modal-input-group label {
            display: block;
            margin-bottom: 6px;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.83rem;
        }
        .modal-input-group input,
        .modal-input-group select,
        .modal-input-group textarea {
            width: 100%;
            padding: 11px 14px;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.2s;
        }
        .modal-input-group input:focus,
        .modal-input-group select:focus,
        .modal-input-group textarea:focus {
            border-color: var(--border-focus);
            background: var(--bg-card);
            box-shadow: 0 0 0 4px var(--accent-glow);
        }
        .modal-input-group select option {
            background: var(--bg-card);
        }

        .modal-btn-row {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 22px;
        }
        .modal-btn {
            padding: 11px 22px;
            border: 1.5px solid transparent;
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        .modal-btn-primary {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 4px 12px var(--accent-glow);
        }
        .modal-btn-primary:hover {
            filter: brightness(1.08);
        }
        .modal-btn-secondary {
            background: var(--bg-input);
            border-color: var(--border);
            color: var(--text-secondary);
        }
        .modal-btn-secondary:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Seletor de membros (modal tarefa) */
        .membros-selector {
            margin-bottom: 18px;
            padding: 18px;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
        }
        .membros-selector label {
            display: block;
            margin-bottom: 14px;
            color: var(--text-secondary);
            font-size: 0.88rem;
            font-weight: 600;
        }
        .membros-grid-selector {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 12px;
            max-height: 240px;
            padding: 3px;
            overflow-y: auto;
        }
        .membro-selector-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 7px;
            padding: 10px 7px;
            background: var(--bg-card);
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s;
        }
        .membro-selector-item:hover {
            border-color: var(--accent);
            transform: translateY(-2px);
        }
        .membro-selector-item.selected {
            background: var(--accent-soft);
            border-color: var(--accent);
            box-shadow: 0 4px 12px var(--accent-glow);
        }
        .membro-selector-item.selected::after {
            content: '✓';
            position: absolute;
            top: 4px;
            right: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            background: var(--accent);
            border-radius: 50%;
            color: white;
            font-size: 10px;
        }
        .membro-selector-foto {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            background: var(--accent-soft);
            border: 2px solid var(--accent);
            border-radius: 50%;
            overflow: hidden;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--accent);
        }
        .membro-selector-foto img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .membro-selector-nome {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-primary);
            text-align: center;
            word-break: break-word;
        }


        /* =================================================================
           25. RESPONSIVIDADE
        ================================================================= */

        @media (max-width: 1200px) {
            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 900px) {
            .tasks-section {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 72px;
                padding: 18px 10px;
            }
            .logo-sidebar span,
            .menu-item span,
            .user-details {
                display: none;
            }
            .menu-item {
                justify-content: center;
                padding: 13px;
            }
            .main-content {
                padding: 18px 14px;
            }
            .cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .financas-resumo {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .cards-grid {
                grid-template-columns: 1fr;
            }
        }

    </style>
</head>
<body data-theme="light">
<canvas id="particles-canvas"></canvas>

<!-- BOTÃO TEMA -->
<div class="theme-toggle" onclick="toggleTheme()">
    <i class="fas fa-sun" id="themeIcon"></i>
    <span id="themeText">Claro</span>
</div>

<!-- ==================== AUTENTICAÇÃO ==================== -->
<div id="auth-container">
    <div id="auth-app">
        <!-- SPLASH -->
        <div id="splash-screen">
            <div class="logo-container">
                <div class="logo-image">
                    <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="50" r="38" stroke="#2d9e6b" stroke-width="2.5"/>
                        <path d="M30 45 L45 62 L70 34" stroke="#2d9e6b" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="50" cy="50" r="7" fill="#2d9e6b" opacity=".3"/>
                    </svg>
                </div>
                <div class="logo-text">FamilyHub</div>
                <div class="splash-sub">conexão que une</div>
            </div>
        </div>

        <!-- LOGIN -->
        <div id="login-screen" class="screen">
            <h1 class="screen-title">Bem-vindo de volta</h1>
            <p class="screen-sub">Faça login na sua conta</p>
            <div class="input-group">
                <label><i class="fas fa-user" style="margin-right:6px;color:var(--accent)"></i>Usuário</label>
                <input type="text" id="login-usuario" placeholder="Digite seu usuário" maxlength="30">
            </div>
            <div class="input-group">
                <label><i class="fas fa-lock" style="margin-right:6px;color:var(--accent)"></i>Senha</label>
                <input type="password" id="login-senha" placeholder="Digite sua senha" maxlength="50">
            </div>
            <button class="btn-primary" onclick="fazerLogin()"><i class="fas fa-sign-in-alt"></i> Entrar</button>
            <div class="toggle-link">Não tem cadastro? <a onclick="mostrarTelaAuth('register')">Criar conta</a></div>
        </div>

        <!-- ESCOLHA CADASTRO -->
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

        <!-- CADASTRO FAMÍLIA -->
        <div id="register-family-screen" class="screen">
            <h1 class="screen-title">Criar Família</h1>
            <p class="screen-sub">Configure sua família</p>
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
            <p style="color:var(--accent);font-weight:700;margin-bottom:14px;font-size:.88rem;display:flex;align-items:center;gap:6px"><i class="fas fa-user-shield"></i> Dados do Administrador</p>
            <div class="input-group"><label>Seu nome *</label><input type="text" id="admin-nome" placeholder="Seu nome completo" maxlength="100"></div>
            <div class="input-group"><label>Usuário *</label><input type="text" id="admin-usuario" placeholder="Nome de usuário (min. 3 caracteres)" maxlength="30"></div>
            <div class="input-group"><label>Senha *</label><input type="password" id="admin-senha" placeholder="Mínimo 6 caracteres" maxlength="50"></div>
            <div class="input-group"><label>Repetir senha *</label><input type="password" id="admin-rep-senha" placeholder="Repita a senha" maxlength="50"></div>
            <button class="btn-primary" onclick="cadastrarFamilia()"><i class="fas fa-plus"></i> Criar Família</button>
            <div class="toggle-link"><a onclick="voltarEscolhaCadastro()">← Voltar</a></div>
        </div>

        <!-- CADASTRO MEMBRO -->
        <div id="register-member-screen" class="screen">
            <h1 class="screen-title">Entrar em Família</h1>
            <p class="screen-sub">Cadastre-se como membro</p>
            <div class="info-box"><i class="fas fa-info-circle"></i><span>Solicite ao administrador o <strong>nome da família</strong> e a <strong>senha de acesso</strong>.</span></div>
            <div class="input-group">
                <label>Foto (opcional)</label>
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
            <div class="input-group"><label>Família *</label><select id="familia-select"><option value="">Carregando...</option></select></div>
            <div class="input-group"><label>Senha da família *</label><input type="password" id="membro-familia-senha" placeholder="Senha fornecida pelo admin" maxlength="50"></div>
            <div class="input-group"><label>Seu nome *</label><input type="text" id="membro-nome" placeholder="Seu nome completo" maxlength="100"></div>
            <div class="input-group"><label>Usuário *</label><input type="text" id="membro-usuario" placeholder="Nome de usuário único" maxlength="30"></div>
            <div class="input-group"><label>Senha *</label><input type="password" id="membro-senha" placeholder="Mínimo 6 caracteres" maxlength="50"></div>
            <button class="btn-primary" onclick="cadastrarMembro()"><i class="fas fa-user-plus"></i> Criar Conta</button>
            <div class="toggle-link"><a onclick="voltarEscolhaCadastro()">← Voltar</a></div>
        </div>
    </div>
</div>

<!-- ==================== DASHBOARD ==================== -->
<div id="dashboard-container">
    <div class="dashboard-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="logo-sidebar">
                <svg viewBox="0 0 100 100" fill="none">
                    <circle cx="50" cy="50" r="38" stroke="#2d9e6b" stroke-width="2.5"/>
                    <path d="M30 45 L45 62 L70 34" stroke="#2d9e6b" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>FamilyHub</span>
            </div>
            <div class="menu-items">
                <div class="menu-item active" onclick="mostrarTelaDashboard('home')"><i class="fas fa-home"></i><span>Dashboard</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('membros')"><i class="fas fa-users"></i><span>Membros</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('tarefas')"><i class="fas fa-tasks"></i><span>Tarefas</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('chat')"><i class="fas fa-comments"></i><span>Chat</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('galeria')"><i class="fas fa-images"></i><span>Galeria</span></div>
                <div class="menu-item" onclick="mostrarTelaDashboard('financas')"><i class="fas fa-wallet"></i><span>Finanças</span></div>
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
                    <div class="status-card">
                        <div class="card-header"><div class="card-icon"><i class="fas fa-tasks"></i></div><div class="card-title">Tarefas hoje</div></div>
                        <div class="card-value" id="totalTarefasHoje">0</div>
                        <div class="card-sub"><span class="trend-up" id="tarefasConcluidasHoje">0 concluídas</span></div>
                    </div>
                    <div class="status-card">
                        <div class="card-header"><div class="card-icon"><i class="fas fa-clock"></i></div><div class="card-title">Pendentes</div></div>
                        <div class="card-value" id="tarefasPendentes">0</div>
                        <div class="card-sub">aguardando ação</div>
                    </div>
                    <div class="status-card">
                        <div class="card-header"><div class="card-icon"><i class="fas fa-users"></i></div><div class="card-title">Membros</div></div>
                        <div class="card-value" id="totalMembros">1</div>
                        <div class="card-sub">ativos na família</div>
                    </div>
                    <div class="status-card">
                        <div class="card-header"><div class="card-icon"><i class="fas fa-calendar-check"></i></div><div class="card-title">Próximos eventos</div></div>
                        <div class="card-value" id="proximosEventos">0</div>
                        <div class="card-sub">próximos 7 dias</div>
                    </div>
                </div>
                <div class="tasks-section">
                    <div class="tasks-container">
                        <div class="section-header">
                            <h2>Tarefas de hoje</h2>
                            <button class="btn-add" onclick="mostrarTelaDashboard('tarefas')"><i class="fas fa-arrow-right"></i> Ver todas</button>
                        </div>
                        <div class="task-list" id="taskListHome"></div>
                    </div>
                    <div class="calendar-container">
                        <div class="calendar-header">
                            <h3 class="calendar-month" id="currentMonth">...</h3>
                            <div class="calendar-nav">
                                <button onclick="navegarMes(-1)"><i class="fas fa-chevron-left"></i></button>
                                <button onclick="navegarMes(1)"><i class="fas fa-chevron-right"></i></button>
                            </div>
                        </div>
                        <div class="calendar-weekdays"><div>Dom</div><div>Seg</div><div>Ter</div><div>Qua</div><div>Qui</div><div>Sex</div><div>Sáb</div></div>
                        <div class="calendar-days" id="calendarDays"></div>
                    </div>
                </div>
                <div class="day-details-container" id="dayDetails" style="display:none">
                    <div class="day-details-header">
                        <h3>Tarefas do dia</h3>
                        <span class="day-details-date" id="selectedDate"></span>
                    </div>
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
                    <div class="section-header">
                        <h2>📋 Todas as Tarefas</h2>
                        <button class="btn-add" onclick="abrirModalTarefa()"><i class="fas fa-plus"></i> Nova Tarefa</button>
                    </div>
                    <div class="task-filters">
                        <button class="filter-btn active" onclick="filtrarTarefas('todas',event)">Todas</button>
                        <button class="filter-btn" onclick="filtrarTarefas('hoje',event)">Hoje</button>
                        <button class="filter-btn" onclick="filtrarTarefas('semana',event)">Esta semana</button>
                        <button class="filter-btn" onclick="filtrarTarefas('pendentes',event)">Pendentes</button>
                        <button class="filter-btn" onclick="filtrarTarefas('concluidas',event)">Concluídas</button>
                        <button class="filter-btn" onclick="filtrarTarefas('atrasadas',event)">Atrasadas</button>
                    </div>
                    <div class="task-stats">
                        <div class="stat-item"><div class="stat-value" id="totalTarefasStat">0</div><div class="stat-label">Total</div></div>
                        <div class="stat-item"><div class="stat-value" id="concluidasStat">0</div><div class="stat-label">Concluídas</div></div>
                        <div class="stat-item"><div class="stat-value" id="pendentesStat">0</div><div class="stat-label">Pendentes</div></div>
                    </div>
                    <div class="task-list" id="allTasksList" style="max-height:500px;overflow-y:auto;margin-top:18px"></div>
                </div>
            </div>

            <!-- CHAT -->
            <div id="chatSection" style="display:none">
                <div class="chat-container">
                    <div class="chat-header">
                        <h2>💬 Chat da Família</h2>
                        <span class="points-badge"><i class="fas fa-star"></i> +1 ponto por mensagem</span>
                    </div>
                    <div class="chat-messages" id="chatMessages"></div>
                    <div class="chat-input-area">
                        <textarea class="chat-input" id="chatInput" placeholder="Digite sua mensagem... (Enter para enviar)" rows="1"></textarea>
                        <button class="chat-send-btn" onclick="enviarMensagem()"><i class="fas fa-paper-plane"></i> Enviar</button>
                    </div>
                </div>
            </div>

            <!-- GALERIA -->
            <div id="galeriaSection" style="display:none">
                <div class="gallery-container">
                    <div class="gallery-header">
                        <h2>📷 Galeria da Família</h2>
                        <button class="btn-add" onclick="abrirModalUploadFoto()"><i class="fas fa-upload"></i> Upload</button>
                    </div>
                    <div class="gallery-grid" id="galleryGrid"></div>
                </div>
            </div>

            <!-- FINANÇAS -->
            <div id="financasSection" style="display:none">
                <div class="financas-container">
                    <div class="financas-header">
                        <h2>💰 Finanças da Família</h2>
                        <button class="btn-add" onclick="abrirModalTransacao()"><i class="fas fa-plus"></i> Nova Transação</button>
                    </div>
                    <div class="financas-resumo">
                        <div class="resumo-card"><div class="resumo-label">Saldo Total</div><div class="resumo-valor" id="saldoTotal">R$ 0,00</div></div>
                        <div class="resumo-card"><div class="resumo-label">Receitas</div><div class="resumo-valor positivo" id="totalReceitas">R$ 0,00</div></div>
                        <div class="resumo-card"><div class="resumo-label">Despesas</div><div class="resumo-valor negativo" id="totalDespesas">R$ 0,00</div></div>
                    </div>
                    <h3 style="color:var(--text-primary);margin:0 0 14px;font-size:.95rem;font-weight:700">Últimas Transações</h3>
                    <div class="transacoes-list" id="transacoesList"></div>
                </div>
            </div>

            <!-- LEMBRETES -->
            <div id="lembretesSection" style="display:none">
                <div class="lembretes-container">
                    <div class="lembretes-header">
                        <h2>🔔 Lembretes</h2>
                        <button class="btn-add" onclick="abrirModalLembrete()"><i class="fas fa-plus"></i> Novo Lembrete</button>
                    </div>
                    <div class="lembretes-list" id="lembretesList"></div>
                </div>
            </div>

            <!-- DOCUMENTOS -->
            <div id="documentosSection" style="display:none">
                <div class="documentos-container">
                    <div class="documentos-header">
                        <h2>📁 Documentos</h2>
                        <button class="btn-add" onclick="abrirModalDocumento()"><i class="fas fa-upload"></i> Adicionar</button>
                    </div>
                    <div class="documentos-grid" id="documentosGrid"></div>
                </div>
            </div>

            <!-- RANKING -->
            <div id="rankingSection" style="display:none">
                <div class="ranking-container">
                    <div class="ranking-header">
                        <h2>🏆 Ranking de Pontos</h2>
                        <span class="points-badge"><i class="fas fa-star"></i> Ganhe pontos!</span>
                    </div>
                    <div class="ranking-list" id="rankingList"></div>
                </div>
            </div>

            <!-- CONFIGURAÇÕES -->
            <div id="configSection" style="display:none">
                <div class="config-container">
                    <div class="config-header">
                        <h2>⚙️ Configurações</h2>
                        <p>Gerencie todas as configurações da sua família</p>
                    </div>
                    <div class="config-menu">
                        <button class="config-menu-item active" onclick="mostrarSubConfig('geral',this)"><i class="fas fa-home"></i> Geral</button>
                        <button class="config-menu-item" onclick="mostrarSubConfig('membros',this)"><i class="fas fa-users"></i> Membros</button>
                        <button class="config-menu-item" onclick="mostrarSubConfig('perfil',this)"><i class="fas fa-user"></i> Meu Perfil</button>
                        <button class="config-menu-item" onclick="mostrarSubConfig('auditoria',this)"><i class="fas fa-history"></i> Auditoria</button>
                    </div>
                    <div class="config-content">
                        <!-- Geral -->
                        <div class="config-pane active" id="configGeral">
                            <div class="config-section-title"><i class="fas fa-home" style="color:var(--accent)"></i> Dados da Família</div>
                            <div class="family-photo-edit">
                                <div class="family-photo-circle">
                                    <img id="familyPhotoImg" src="" alt="família" style="display:none">
                                    <span id="familyPhotoPlaceholder">🏠</span>
                                </div>
                                <div class="family-photo-actions">
                                    <button class="config-btn" onclick="document.getElementById('familyPhotoUpload').click()"><i class="fas fa-camera"></i> Alterar foto</button>
                                    <button class="config-btn config-btn-danger" onclick="removerFotoFamilia()"><i class="fas fa-trash"></i> Remover</button>
                                    <input type="file" id="familyPhotoUpload" accept="image/*" style="display:none" onchange="alterarFotoFamilia(event)">
                                </div>
                            </div>
                            <div class="modal-input-group">
                                <label>Nome da Família</label>
                                <input type="text" id="configNomeFamilia" placeholder="Nome da sua família" maxlength="100">
                            </div>
                            <button class="config-save-btn" onclick="salvarConfigFamilia()"><i class="fas fa-save"></i> Salvar Alterações</button>
                        </div>

                        <!-- Membros -->
                        <div class="config-pane" id="configMembros">
                            <div class="config-section-title"><i class="fas fa-users" style="color:var(--accent)"></i> Gerenciar Membros</div>
                            <div id="configMembrosLista"></div>
                        </div>

                        <!-- Perfil -->
                        <div class="config-pane" id="configPerfil">
                            <div class="config-section-title"><i class="fas fa-user" style="color:var(--accent)"></i> Meu Perfil</div>
                            <div class="family-photo-edit">
                                <div class="family-photo-circle">
                                    <img id="perfilFotoImg" src="" alt="perfil" style="display:none">
                                    <span id="perfilFotoPlaceholder">👤</span>
                                </div>
                                <div class="family-photo-actions">
                                    <button class="config-btn" onclick="document.getElementById('perfilFotoUpload').click()"><i class="fas fa-camera"></i> Alterar foto</button>
                                    <input type="file" id="perfilFotoUpload" accept="image/*" style="display:none" onchange="previewPerfilFoto(event)">
                                </div>
                            </div>
                            <div class="modal-input-group">
                                <label>Nome</label>
                                <input type="text" id="perfilNome" placeholder="Seu nome completo" maxlength="100">
                            </div>
                            <div class="modal-input-group">
                                <label>Senha atual (deixe vazio para não alterar)</label>
                                <input type="password" id="perfilSenhaAtual" placeholder="Senha atual" maxlength="50">
                            </div>
                            <div class="modal-input-group">
                                <label>Nova senha</label>
                                <input type="password" id="perfilNovaSenha" placeholder="Nova senha (mín. 6 caracteres)" maxlength="50">
                            </div>
                            <button class="config-save-btn" onclick="salvarPerfil()"><i class="fas fa-save"></i> Salvar Perfil</button>
                        </div>

                        <!-- Auditoria -->
                        <div class="config-pane" id="configAuditoria">
                            <div class="config-section-title" style="justify-content:space-between">
                                <span><i class="fas fa-history" style="color:var(--accent)"></i> Log de Auditoria</span>
                                <select id="filtroAuditoria" onchange="carregarAuditoria()" style="padding:6px 12px;background:var(--bg-input);border:1.5px solid var(--border);border-radius:8px;color:var(--text-primary);font-family:inherit;font-size:.82rem;outline:none">
                                    <option value="">Todos</option>
                                    <option value="add">Adições</option>
                                    <option value="edit">Edições</option>
                                    <option value="delete">Exclusões</option>
                                    <option value="check">Conclusões</option>
                                    <option value="levelup">Nível Up</option>
                                </select>
                            </div>
                            <div id="auditoriaLista" style="margin-top:14px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MODAIS ==================== -->

<!-- MODAL TAREFA -->
<div class="modal" id="modalTarefa">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTarefaTitulo">Nova Tarefa</h3>
            <button class="modal-close" onclick="fecharModal('modalTarefa')">×</button>
        </div>
        <input type="hidden" id="tarefaEditId">
        <div class="membros-selector">
            <label><i class="fas fa-users" style="margin-right:6px;color:var(--accent)"></i>Selecione os responsáveis:</label>
            <div class="membros-grid-selector" id="membrosSelectorTarefa"></div>
        </div>
        <div class="modal-input-group"><label>Título *</label><input type="text" id="tarefaTitulo" placeholder="Nome da tarefa" maxlength="200"></div>
        <div class="modal-input-group"><label>Descrição</label><textarea id="tarefaDescricao" placeholder="Detalhes da tarefa" rows="3"></textarea></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div class="modal-input-group"><label>Data</label><input type="date" id="tarefaData"></div>
            <div class="modal-input-group"><label>Hora</label><input type="time" id="tarefaHora"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div class="modal-input-group"><label>Prioridade</label>
                <select id="tarefaPrioridade">
                    <option value="baixa">🟢 Baixa</option>
                    <option value="media" selected>🟡 Média</option>
                    <option value="alta">🔴 Alta</option>
                </select>
            </div>
            <div class="modal-input-group"><label>Pontos</label><input type="number" id="tarefaPontos" value="10" min="1" max="100"></div>
        </div>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalTarefa')">Cancelar</button>
            <button class="modal-btn modal-btn-primary" onclick="salvarTarefa()"><i class="fas fa-save"></i> Salvar</button>
        </div>
    </div>
</div>

<!-- MODAL EDITAR MEMBRO -->
<div class="modal" id="modalEditarMembro">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Membro</h3>
            <button class="modal-close" onclick="fecharModal('modalEditarMembro')">×</button>
        </div>
        <input type="hidden" id="editMembroId">
        <div class="modal-input-group"><label>Nome *</label><input type="text" id="editMembroNome" placeholder="Nome completo" maxlength="100"></div>
        <div class="modal-input-group"><label>Senha atual (para trocar senha)</label><input type="password" id="editMembroSenhaAtual" placeholder="Senha atual" maxlength="50"></div>
        <div class="modal-input-group"><label>Nova senha</label><input type="password" id="editMembroNovaSenha" placeholder="Nova senha (mín. 6 caracteres)" maxlength="50"></div>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalEditarMembro')">Cancelar</button>
            <button class="modal-btn modal-btn-primary" onclick="salvarEdicaoMembro()"><i class="fas fa-save"></i> Salvar</button>
        </div>
    </div>
</div>

<!-- MODAL UPLOAD FOTO GALERIA -->
<div class="modal" id="modalUploadFoto">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Upload de Foto</h3>
            <button class="modal-close" onclick="fecharModal('modalUploadFoto')">×</button>
        </div>
        <div class="modal-input-group">
            <label>Foto *</label>
            <div class="photo-upload-container">
                <div class="photo-preview-circle" id="galleryPhotoPreview" onclick="document.getElementById('fotoGaleria').click()" style="width:140px;height:140px">
                    <div class="placeholder-icon">📸</div>
                    <div class="placeholder-text">Clique para selecionar</div>
                    <img class="preview-img" id="galleryPreviewImg" src="#" alt="preview">
                </div>
                <input type="file" id="fotoGaleria" accept="image/*" onchange="handlePhotoUpload(event,'galleryPhotoPreview','galleryPreviewImg','galleryFileName')">
                <span class="file-name-small" id="galleryFileName">Nenhum arquivo</span>
            </div>
        </div>
        <div class="modal-input-group"><label>Título (opcional)</label><input type="text" id="fotoTitulo" placeholder="Legenda da foto" maxlength="100"></div>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalUploadFoto')">Cancelar</button>
            <button class="modal-btn modal-btn-primary" onclick="uploadFoto()"><i class="fas fa-upload"></i> Enviar</button>
        </div>
    </div>
</div>

<!-- MODAL TRANSAÇÃO -->
<div class="modal" id="modalTransacao">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Nova Transação</h3>
            <button class="modal-close" onclick="fecharModal('modalTransacao')">×</button>
        </div>
        <div class="modal-input-group"><label>Descrição *</label><input type="text" id="transacaoDescricao" placeholder="Ex: Conta de luz" maxlength="200"></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div class="modal-input-group"><label>Valor (R$) *</label><input type="number" id="transacaoValor" placeholder="0,00" step="0.01" min="0"></div>
            <div class="modal-input-group"><label>Tipo</label>
                <select id="transacaoTipo">
                    <option value="despesa">💸 Despesa</option>
                    <option value="receita">💰 Receita</option>
                </select>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div class="modal-input-group"><label>Categoria</label><input type="text" id="transacaoCategoria" placeholder="Ex: Alimentação" maxlength="50"></div>
            <div class="modal-input-group"><label>Data</label><input type="date" id="transacaoData"></div>
        </div>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalTransacao')">Cancelar</button>
            <button class="modal-btn modal-btn-primary" onclick="salvarTransacao()"><i class="fas fa-save"></i> Salvar</button>
        </div>
    </div>
</div>

<!-- MODAL LEMBRETE -->
<div class="modal" id="modalLembrete">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Novo Lembrete</h3>
            <button class="modal-close" onclick="fecharModal('modalLembrete')">×</button>
        </div>
        <div class="modal-input-group"><label>Título *</label><input type="text" id="lembreteTitulo" placeholder="Título do lembrete" maxlength="200"></div>
        <div class="modal-input-group"><label>Descrição</label><textarea id="lembreteDescricao" placeholder="Detalhes..." rows="3"></textarea></div>
        <div style="display:grid;grid-template-columns:1fr 1fr 80px;gap:12px">
            <div class="modal-input-group"><label>Data</label><input type="date" id="lembreteData"></div>
            <div class="modal-input-group"><label>Hora</label><input type="time" id="lembreteHora"></div>
            <div class="modal-input-group"><label>Cor</label><input type="color" id="lembreteCor" value="#2d9e6b" style="height:44px;padding:4px;cursor:pointer"></div>
        </div>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalLembrete')">Cancelar</button>
            <button class="modal-btn modal-btn-primary" onclick="salvarLembrete()"><i class="fas fa-save"></i> Salvar</button>
        </div>
    </div>
</div>

<!-- MODAL DOCUMENTO -->
<div class="modal" id="modalDocumento">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Adicionar Documento</h3>
            <button class="modal-close" onclick="fecharModal('modalDocumento')">×</button>
        </div>
        <div class="modal-input-group"><label>Nome do documento *</label><input type="text" id="docNome" placeholder="Nome do arquivo" maxlength="200"></div>
        <div class="modal-input-group">
            <label>Arquivo * (máx. 10MB)</label>
            <input type="file" id="docArquivo" onchange="previewDocumento(event)" style="padding:10px;cursor:pointer">
            <p id="docPreviewInfo" style="margin-top:6px;color:var(--text-muted);font-size:.78rem"></p>
        </div>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalDocumento')">Cancelar</button>
            <button class="modal-btn modal-btn-primary" onclick="uploadDocumento()"><i class="fas fa-upload"></i> Salvar</button>
        </div>
    </div>
</div>

<!-- MODAL CONFIRMAR -->
<div class="modal" id="modalConfirmar">
    <div class="modal-content" style="max-width:400px">
        <div class="modal-header">
            <h3 id="confirmarTitulo">Confirmar</h3>
            <button class="modal-close" onclick="fecharModal('modalConfirmar')">×</button>
        </div>
        <p id="confirmarMensagem" style="color:var(--text-secondary);margin-bottom:22px;line-height:1.6"></p>
        <div class="modal-btn-row">
            <button class="modal-btn modal-btn-secondary" onclick="fecharModal('modalConfirmar')">Cancelar</button>
            <button class="modal-btn modal-btn-primary" id="confirmarSimBtn" style="background:var(--danger);box-shadow:none"><i class="fas fa-check"></i> Confirmar</button>
        </div>
    </div>
</div>

<!-- ALERTA CUSTOMIZADO -->
<div id="customAlert">
    <span id="alertIcon">ℹ️</span>
    <span id="alertMessage">Mensagem</span>
</div>

<script>
// ==================== TEMA ====================
function toggleTheme() {
    const root = document.documentElement;
    const newTheme = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
    root.setAttribute('data-theme', newTheme);
    document.getElementById('themeIcon').className = newTheme === 'dark' ? 'fas fa-moon' : 'fas fa-sun';
    document.getElementById('themeText').textContent = newTheme === 'dark' ? 'Escuro' : 'Claro';
    localStorage.setItem('theme', newTheme);
}
(function() {
    const t = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', t);
    document.getElementById('themeIcon').className = t === 'dark' ? 'fas fa-moon' : 'fas fa-sun';
    document.getElementById('themeText').textContent = t === 'dark' ? 'Escuro' : 'Claro';
})();

// ==================== VARIÁVEIS GLOBAIS ====================
let usuarioAtual = null;
let dadosTarefas = [];
let calendarYear, calendarMonth;
let filtroTarefaAtual = 'todas';
let membrosSelecionadosTarefa = new Set();
let perfilFotoBase64 = null;
let docBase64 = null, docTipoArq = '', docTamanhoArq = 0, docNomeArq = '';

// ==================== API ====================
function api(action, data = {}) {
    const fd = new FormData();
    fd.append('action', action);
    for (const k in data) fd.append(k, data[k]);
    return fetch('api.php', {method: 'POST', body: fd}).then(r => r.json());
}
function apiGet(action, params = {}) {
    return fetch('api.php?' + new URLSearchParams({action, ...params})).then(r => r.json());
}

// ==================== UTILITÁRIOS ====================
function mostrarAlerta(tipo, msg) {
    const box = document.getElementById('customAlert');
    document.getElementById('alertIcon').textContent = tipo==='erro' ? '❌' : tipo==='sucesso' ? '✅' : 'ℹ️';
    document.getElementById('alertMessage').textContent = msg;
    box.className = tipo==='erro' ? 'error' : tipo==='sucesso' ? 'success' : '';
    box.style.display = 'flex';
    setTimeout(() => box.style.display = 'none', 3500);
}
function fecharModal(id) {
    document.getElementById(id).style.display = 'none';
    if (id === 'modalTarefa') membrosSelecionadosTarefa.clear();
}
function abrirModalConfirmar(titulo, msg, cb) {
    document.getElementById('confirmarTitulo').textContent = titulo;
    document.getElementById('confirmarMensagem').textContent = msg;
    document.getElementById('confirmarSimBtn').onclick = () => { cb(); fecharModal('modalConfirmar'); };
    document.getElementById('modalConfirmar').style.display = 'flex';
}
function formatData(d) {
    if (!d) return '';
    return new Date(d + 'T00:00:00').toLocaleDateString('pt-BR');
}
function formatDateTime(d) {
    if (!d) return '';
    const dt = new Date(d);
    return dt.toLocaleDateString('pt-BR') + ' às ' + dt.toLocaleTimeString('pt-BR', {hour:'2-digit', minute:'2-digit'});
}
function formatMoeda(v) {
    return 'R$ ' + parseFloat(v||0).toLocaleString('pt-BR', {minimumFractionDigits:2, maximumFractionDigits:2});
}
function avatarHtml(foto, nome, size=36) {
    if (foto && foto !== 'null')
        return `<img src="${foto}" style="width:100%;height:100%;object-fit:cover;border-radius:50%">`;
    return `<span style="font-size:${size*0.38}px;font-weight:700">${(nome||'?')[0].toUpperCase()}</span>`;
}
function getIconeAtividade(tipo) {
    const m = {add:'fa-plus-circle',edit:'fa-edit',delete:'fa-trash',check:'fa-check-circle',pontos:'fa-star',levelup:'fa-level-up-alt'};
    return m[tipo] || 'fa-history';
}
function handlePhotoUpload(event, previewCircleId, previewImgId, fileNameId) {
    const file = event.target.files[0];
    if (!file) return;
    if (file.size > 5*1024*1024) { mostrarAlerta('erro','Imagem deve ter no máximo 5MB'); return; }
    const fname = document.getElementById(fileNameId);
    if (fname) fname.textContent = file.name.length > 30 ? file.name.substr(0,27)+'...' : file.name;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById(previewImgId).src = e.target.result;
        document.getElementById(previewCircleId).classList.add('has-image');
    };
    reader.readAsDataURL(file);
}

// ==================== AUTENTICAÇÃO ====================
function mostrarTelaAuth(tela) {
    ['login-screen','register-choice-screen','register-family-screen','register-member-screen'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.remove('active');
    });
    if (tela === 'login') setTimeout(() => document.getElementById('login-screen').classList.add('active'), 50);
    else if (tela === 'register') { carregarFamiliasSelect(); setTimeout(() => document.getElementById('register-choice-screen').classList.add('active'), 50); }
}
function voltarEscolhaCadastro() { mostrarTelaAuth('register'); }
function escolherCadastro(tipo) {
    document.getElementById('register-choice-screen').classList.remove('active');
    if (tipo === 'familia') setTimeout(() => document.getElementById('register-family-screen').classList.add('active'), 50);
    else { carregarFamiliasSelect(); setTimeout(() => document.getElementById('register-member-screen').classList.add('active'), 50); }
}
function carregarFamiliasSelect() {
    apiGet('listar_familias').then(fams => {
        const sel = document.getElementById('familia-select');
        if (!sel) return;
        sel.innerHTML = '<option value="">Selecione uma família</option>';
        (Array.isArray(fams) ? fams : []).forEach(f => sel.innerHTML += `<option value="${f.id}">${f.nome}</option>`);
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
    if (!nomeFamilia||!familiaSenha||!adminNome||!adminUsuario||!adminSenha) { mostrarAlerta('erro','Preencha todos os campos obrigatórios'); return; }
    if (familiaSenha !== familiaRepSenha) { mostrarAlerta('erro','Senhas da família não coincidem'); return; }
    if (adminSenha !== adminRepSenha) { mostrarAlerta('erro','Senhas do admin não coincidem'); return; }
    if (adminUsuario.length < 3) { mostrarAlerta('erro','Usuário deve ter no mínimo 3 caracteres'); return; }
    if (adminSenha.length < 6) { mostrarAlerta('erro','Senha deve ter no mínimo 6 caracteres'); return; }
    const fotoImg = document.getElementById('familyPreviewImg');
    const foto = fotoImg.src && fotoImg.src !== window.location.href && document.getElementById('familyPhotoPreview').classList.contains('has-image') ? fotoImg.src : '';
    api('cadastrar_familia', {nome_familia:nomeFamilia, familia_senha:familiaSenha, admin_nome:adminNome, admin_usuario:adminUsuario, admin_senha:adminSenha, foto_familia:foto}).then(r => {
        if (r.sucesso) { mostrarAlerta('sucesso', r.mensagem||'Família criada!'); setTimeout(() => mostrarTelaAuth('login'), 1200); }
        else mostrarAlerta('erro', r.erro||'Erro ao cadastrar');
    });
}
function cadastrarMembro() {
    const familiaId = document.getElementById('familia-select').value;
    const senhFam = document.getElementById('membro-familia-senha').value;
    const nome = document.getElementById('membro-nome').value.trim();
    const usuario = document.getElementById('membro-usuario').value.trim();
    const senha = document.getElementById('membro-senha').value;
    if (!familiaId||!senhFam||!nome||!usuario||!senha) { mostrarAlerta('erro','Preencha todos os campos obrigatórios'); return; }
    const fotoImg = document.getElementById('memberPreviewImg');
    const foto = fotoImg.src && fotoImg.src !== window.location.href && document.getElementById('memberPhotoPreview').classList.contains('has-image') ? fotoImg.src : '';
    api('cadastrar_membro', {familia_id:familiaId, familia_senha:senhFam, nome, usuario, senha, foto_membro:foto}).then(r => {
        if (r.sucesso) { mostrarAlerta('sucesso', r.mensagem||'Conta criada!'); setTimeout(() => mostrarTelaAuth('login'), 1200); }
        else mostrarAlerta('erro', r.erro||'Erro ao cadastrar');
    });
}
function fazerLogin() {
    const usuario = document.getElementById('login-usuario').value.trim();
    const senha = document.getElementById('login-senha').value;
    if (!usuario||!senha) { mostrarAlerta('erro','Preencha todos os campos'); return; }
    api('login', {usuario, senha}).then(r => {
        if (r.sucesso) { usuarioAtual = r.usuario; mostrarDashboard(); }
        else mostrarAlerta('erro', r.erro||'Credenciais inválidas');
    });
}
function fazerLogout() {
    abrirModalConfirmar('Sair', 'Deseja realmente sair da conta?', () => api('logout').then(() => location.reload()));
}

// ==================== DASHBOARD ====================
function mostrarDashboard() {
    document.getElementById('auth-container').style.display = 'none';
    document.getElementById('dashboard-container').style.display = 'block';
    document.body.style.overflow = 'hidden';
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
    secoes.forEach(s => { const el = document.getElementById(s); if(el) el.style.display='none'; });
    const map = {home:'homeSection',membros:'membersSection',tarefas:'tarefasSection',chat:'chatSection',galeria:'galeriaSection',financas:'financasSection',lembretes:'lembretesSection',documentos:'documentosSection',ranking:'rankingSection',config:'configSection'};
    const titulos = {home:['Dashboard','Visão geral da família'],membros:['Membros','Todos os integrantes'],tarefas:['Tarefas','Gerencie as tarefas'],chat:['Chat','Converse com a família'],galeria:['Galeria','Fotos da família'],financas:['Finanças','Controle financeiro'],lembretes:['Lembretes','Seus alertas'],documentos:['Documentos','Arquivos e documentos'],ranking:['Ranking','Quem está no topo?'],config:['Configurações','Ajustes da família']};
    if (map[tela]) document.getElementById(map[tela]).style.display = 'block';
    const t = titulos[tela] || ['FamilyHub',''];
    document.getElementById('pageTitle').textContent = t[0];
    document.getElementById('pageSubtitle').textContent = t[1];
    document.querySelectorAll('.menu-item').forEach(m => m.classList.remove('active'));
    const menuMap = ['home','membros','tarefas','chat','galeria','financas','lembretes','documentos','ranking','config'];
    document.querySelectorAll('.menu-item').forEach((item, i) => { if (menuMap[i] === tela) item.classList.add('active'); });
    // Carregar dados
    if (tela === 'home') { carregarHome(); renderizarCalendario(); }
    else if (tela === 'membros') carregarMembros();
    else if (tela === 'tarefas') carregarTarefas();
    else if (tela === 'chat') carregarMensagens();
    else if (tela === 'galeria') carregarGaleria();
    else if (tela === 'financas') carregarFinancas();
    else if (tela === 'lembretes') carregarLembretes();
    else if (tela === 'documentos') carregarDocumentos();
    else if (tela === 'ranking') carregarRanking();
    else if (tela === 'config') iniciarConfig();
}
function atualizarDados() {
    apiGet('verificar_sessao').then(r => {
        if (r.logado) { usuarioAtual = r.usuario; atualizarSidebar(); }
        else if (document.getElementById('dashboard-container').style.display !== 'none') location.reload();
    });
}

// ==================== HOME ====================
function carregarHome() {
    const hoje = new Date().toISOString().split('T')[0];
    Promise.all([apiGet('listar_tarefas'), apiGet('listar_membros'), apiGet('listar_atividades')]).then(([tarefas, membros, atividades]) => {
        dadosTarefas = Array.isArray(tarefas) ? tarefas : [];
        const tarefasHoje = dadosTarefas.filter(t => t.data === hoje);
        const concluiHoje = tarefasHoje.filter(t => parseInt(t.concluida));
        const pendentes = dadosTarefas.filter(t => !parseInt(t.concluida) && (!t.data || t.data >= hoje));
        document.getElementById('totalTarefasHoje').textContent = tarefasHoje.length;
        document.getElementById('tarefasConcluidasHoje').textContent = concluiHoje.length + ' concluídas';
        document.getElementById('tarefasPendentes').textContent = pendentes.length;
        document.getElementById('totalMembros').textContent = Array.isArray(membros) ? membros.length : 0;
        const em7dias = new Date(); em7dias.setDate(em7dias.getDate()+7);
        document.getElementById('proximosEventos').textContent = dadosTarefas.filter(t => t.data && t.data > hoje && t.data <= em7dias.toISOString().split('T')[0]).length;
        renderTarefasList('taskListHome', tarefasHoje.slice(0,5));
        const actList = document.getElementById('activityList');
        const ativList = Array.isArray(atividades) ? atividades : [];
        actList.innerHTML = ativList.slice(0,10).map(a => `
            <div class="activity-item">
                <div class="activity-icon"><i class="fas ${getIconeAtividade(a.tipo)}"></i></div>
                <div class="activity-details">
                    <div class="activity-text">${a.descricao}</div>
                    <div class="activity-time">${formatDateTime(a.criado_em)}</div>
                </div>
            </div>
        `).join('') || '<div style="color:var(--text-muted);text-align:center;padding:20px">Nenhuma atividade</div>';
        renderizarCalendario();
    });
}

// ==================== TAREFAS ====================
function renderTarefasList(containerId, lista) {
    const el = document.getElementById(containerId);
    if (!lista.length) { el.innerHTML = '<div class="empty-state"><i class="fas fa-tasks"></i><p>Nenhuma tarefa</p></div>'; return; }
    el.innerHTML = lista.map(t => {
        const pIcon = t.prioridade === 'alta' ? 'priority-alta' : t.prioridade === 'media' ? 'priority-media' : 'priority-baixa';
        let responsaveisHtml = '';
        if (t.responsaveis) {
            try {
                const resp = JSON.parse(t.responsaveis);
                if (Array.isArray(resp) && resp.length > 0) {
                    responsaveisHtml = '<div style="display:flex;gap:6px;margin-top:7px;flex-wrap:wrap">';
                    resp.forEach(r => {
                        responsaveisHtml += `<div style="display:flex;align-items:center;gap:4px;background:var(--accent-soft);border-radius:20px;padding:3px 8px">
                            <div style="width:20px;height:20px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
                                ${r.foto && r.foto !== 'null' ? `<img src="${r.foto}" style="width:100%;height:100%;object-fit:cover">` : `<span style="color:white;font-size:10px;font-weight:700">${r.nome[0]}</span>`}
                            </div>
                            <span style="color:var(--text-primary);font-size:.72rem;font-weight:600">${r.nome}</span>
                        </div>`;
                    });
                    responsaveisHtml += '</div>';
                }
            } catch(e) {
                if (t.responsavel) responsaveisHtml = `<div style="margin-top:4px;color:var(--text-muted);font-size:.75rem">👤 ${t.responsavel}</div>`;
            }
        } else if (t.responsavel) {
            responsaveisHtml = `<div style="margin-top:4px;color:var(--text-muted);font-size:.75rem">👤 ${t.responsavel}</div>`;
        }
        return `<div class="task-item">
            <div class="task-info">
                <div class="task-check ${parseInt(t.concluida)?'completed':''}" onclick="toggleTarefa(${t.id},${parseInt(t.concluida)?0:1})">${parseInt(t.concluida)?'✓':''}</div>
                <div class="task-details">
                    <div class="task-title" style="${parseInt(t.concluida)?'text-decoration:line-through;opacity:.5':''}">
                        ${t.titulo} <span class="task-points">+${t.pontos}pts</span>
                    </div>
                    <div class="task-meta">
                        ${t.data ? `<span>📅 ${formatData(t.data)}</span>` : ''}
                        ${t.hora ? `<span>🕐 ${t.hora.slice(0,5)}</span>` : ''}
                        <span class="${pIcon}">${t.prioridade}</span>
                    </div>
                    ${responsaveisHtml}
                </div>
            </div>
            <div class="task-actions">
                <button class="task-btn" onclick="abrirModalEditarTarefa(${t.id})"><i class="fas fa-edit"></i></button>
                <button class="task-btn delete" onclick="deletarTarefa(${t.id})"><i class="fas fa-trash"></i></button>
            </div>
        </div>`;
    }).join('');
}
function carregarMembrosSelector() {
    apiGet('listar_membros').then(membros => {
        const container = document.getElementById('membrosSelectorTarefa');
        const lista = Array.isArray(membros) ? membros : [];
        container.innerHTML = lista.map(m => `
            <div class="membro-selector-item" onclick="toggleMembroSelecionado(${m.id},this)" data-id="${m.id}">
                <div class="membro-selector-foto">${m.foto && m.foto !== 'null' ? `<img src="${m.foto}" alt="${m.nome}">` : m.nome[0].toUpperCase()}</div>
                <div class="membro-selector-nome">${m.nome}</div>
            </div>
        `).join('');
        // Re-marcar selecionados se houver
        membrosSelecionadosTarefa.forEach(id => {
            const el = container.querySelector(`[data-id="${id}"]`);
            if (el) el.classList.add('selected');
        });
    });
}
function toggleMembroSelecionado(id, el) {
    if (membrosSelecionadosTarefa.has(id)) { membrosSelecionadosTarefa.delete(id); el.classList.remove('selected'); }
    else { membrosSelecionadosTarefa.add(id); el.classList.add('selected'); }
}
function abrirModalTarefa() {
    document.getElementById('modalTarefaTitulo').textContent = 'Nova Tarefa';
    document.getElementById('tarefaEditId').value = '';
    document.getElementById('tarefaTitulo').value = '';
    document.getElementById('tarefaDescricao').value = '';
    document.getElementById('tarefaData').value = '';
    document.getElementById('tarefaHora').value = '';
    document.getElementById('tarefaPrioridade').value = 'media';
    document.getElementById('tarefaPontos').value = 10;
    membrosSelecionadosTarefa.clear();
    carregarMembrosSelector();
    document.getElementById('modalTarefa').style.display = 'flex';
}
function abrirModalEditarTarefa(id) {
    const t = dadosTarefas.find(x => x.id == id);
    if (!t) return;
    document.getElementById('modalTarefaTitulo').textContent = 'Editar Tarefa';
    document.getElementById('tarefaEditId').value = t.id;
    document.getElementById('tarefaTitulo').value = t.titulo;
    document.getElementById('tarefaDescricao').value = t.descricao || '';
    document.getElementById('tarefaData').value = t.data || '';
    document.getElementById('tarefaHora').value = t.hora ? t.hora.slice(0,5) : '';
    document.getElementById('tarefaPrioridade').value = t.prioridade || 'media';
    document.getElementById('tarefaPontos').value = t.pontos || 10;
    membrosSelecionadosTarefa.clear();
    if (t.responsavel_ids) {
        t.responsavel_ids.split(',').map(x => parseInt(x)).filter(Boolean).forEach(id => membrosSelecionadosTarefa.add(id));
    }
    carregarMembrosSelector();
    document.getElementById('modalTarefa').style.display = 'flex';
}
function salvarTarefa() {
    const id = document.getElementById('tarefaEditId').value;
    const responsaveisIds = Array.from(membrosSelecionadosTarefa);
    if (responsaveisIds.length === 0) { mostrarAlerta('erro','Selecione pelo menos um responsável'); return; }
    const dados = {
        titulo: document.getElementById('tarefaTitulo').value.trim(),
        descricao: document.getElementById('tarefaDescricao').value.trim(),
        responsavel_ids: JSON.stringify(responsaveisIds),
        data: document.getElementById('tarefaData').value,
        hora: document.getElementById('tarefaHora').value,
        prioridade: document.getElementById('tarefaPrioridade').value,
        pontos: document.getElementById('tarefaPontos').value
    };
    if (!dados.titulo) { mostrarAlerta('erro','Título obrigatório'); return; }
    if (id) dados.id = id;
    api(id ? 'editar_tarefa' : 'criar_tarefa', dados).then(r => {
        if (r.sucesso) { mostrarAlerta('sucesso', id ? 'Tarefa atualizada!' : 'Tarefa criada!'); fecharModal('modalTarefa'); carregarTarefas(); }
        else mostrarAlerta('erro', r.erro||'Erro ao salvar');
    });
}
function carregarTarefas() {
    apiGet('listar_tarefas').then(tarefas => {
        dadosTarefas = Array.isArray(tarefas) ? tarefas : [];
        filtrarTarefas(filtroTarefaAtual || 'todas');
    });
}
function filtrarTarefas(filtro, event) {
    filtroTarefaAtual = filtro;
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    if (event && event.target) event.target.classList.add('active');
    const hoje = new Date().toISOString().split('T')[0];
    const em7dias = new Date(); em7dias.setDate(em7dias.getDate()+7); const str7 = em7dias.toISOString().split('T')[0];
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
function toggleTarefa(id, concluida) {
    api('concluir_tarefa', {id, concluida}).then(r => {
        if (r.sucesso) {
            const t = dadosTarefas.find(x => x.id == id);
            if (t) t.concluida = concluida;
            if (document.getElementById('tarefasSection').style.display !== 'none') filtrarTarefas(filtroTarefaAtual);
            else carregarHome();
            if (concluida) mostrarAlerta('sucesso','Tarefa concluída! Pontos ganhos 🎉');
            atualizarDados();
        } else mostrarAlerta('erro', r.erro);
    });
}
function deletarTarefa(id) {
    abrirModalConfirmar('Excluir Tarefa','Deseja excluir esta tarefa?', () => {
        api('deletar_tarefa', {id}).then(r => {
            if (r.sucesso) { mostrarAlerta('sucesso','Tarefa excluída'); carregarTarefas(); }
            else mostrarAlerta('erro', r.erro);
        });
    });
}

// ==================== CALENDÁRIO ====================
function renderizarCalendario() {
    const now = new Date();
    if (!calendarYear) calendarYear = now.getFullYear();
    if (calendarMonth === undefined) calendarMonth = now.getMonth();
    const meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    document.getElementById('currentMonth').textContent = `${meses[calendarMonth]} ${calendarYear}`;
    const primeirodia = new Date(calendarYear, calendarMonth, 1);
    const ultimodia = new Date(calendarYear, calendarMonth+1, 0);
    const hoje = now.toISOString().split('T')[0];
    const grid = document.getElementById('calendarDays');
    grid.innerHTML = '';
    for (let i = 0; i < primeirodia.getDay(); i++) { const d = document.createElement('div'); d.className = 'calendar-day empty'; grid.appendChild(d); }
    for (let d = 1; d <= ultimodia.getDate(); d++) {
        const dataStr = `${calendarYear}-${String(calendarMonth+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const tarefasDia = dadosTarefas.filter(t => t.data === dataStr);
        const div = document.createElement('div');
        let cls = 'calendar-day';
        if (dataStr === hoje) cls += ' today';
        if (tarefasDia.length > 0) cls += ' has-task';
        div.className = cls;
        div.innerHTML = `<div class="day-number">${d}</div>${tarefasDia.length ? `<div class="task-count">${tarefasDia.length}t</div>` : ''}`;
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
    document.getElementById('dayTasksList').innerHTML = tarefasDia.length
        ? tarefasDia.map(t => `<div class="day-task-item">
            <div class="day-task-check ${parseInt(t.concluida)?'completed':''}" onclick="toggleTarefa(${t.id},${parseInt(t.concluida)?0:1})">${parseInt(t.concluida)?'✓':''}</div>
            <div class="day-task-info">
                <div class="day-task-title">${t.titulo}</div>
                <div class="day-task-meta">${t.hora?`<span>🕐 ${t.hora.slice(0,5)}</span>`:''}${t.responsavel?`<span>👤 ${t.responsavel}</span>`:''}</div>
            </div>
        </div>`).join('')
        : '<div class="empty-day"><i class="fas fa-calendar"></i><p>Nenhuma tarefa neste dia</p></div>';
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
                <div class="member-role">${m.cargo==='admin'?'👑 Administrador':'👤 Membro'}</div>
                <div class="member-points">⭐ ${m.pontos} pts · Nível ${m.nivel}</div>
                <div class="member-actions">
                    ${(usuarioAtual.id==m.id||usuarioAtual.cargo==='admin') ? `<button class="member-btn" onclick="abrirModalEditarMembro(${m.id},'${m.nome.replace(/'/g,"\\'")}')"><i class="fas fa-edit"></i></button>` : ''}
                    ${(usuarioAtual.cargo==='admin'&&usuarioAtual.id!=m.id) ? `<button class="member-btn danger" onclick="removerMembro(${m.id},'${m.nome.replace(/'/g,"\\'")}')"><i class="fas fa-trash"></i></button>` : ''}
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
    if (!nome) { mostrarAlerta('erro','Nome obrigatório'); return; }
    api('atualizar_membro', {id, nome, senha_atual:senhaAtual, nova_senha:novaSenha}).then(r => {
        if (r.sucesso) {
            mostrarAlerta('sucesso','Membro atualizado!');
            fecharModal('modalEditarMembro');
            carregarMembros();
            if (id == usuarioAtual.id) { usuarioAtual.nome = nome; atualizarSidebar(); }
        } else mostrarAlerta('erro', r.erro||'Erro ao atualizar');
    });
}
function removerMembro(id, nome) {
    abrirModalConfirmar('Remover Membro', `Deseja remover "${nome}" da família?`, () => {
        api('remover_membro', {id}).then(r => {
            if (r.sucesso) { mostrarAlerta('sucesso','Membro removido'); carregarMembros(); }
            else mostrarAlerta('erro', r.erro||'Erro ao remover');
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
            return `<div class="message ${mine?'mine':''}">
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

// ==================== GALERIA ====================
function carregarGaleria() {
    apiGet('listar_fotos').then(fotos => {
        const grid = document.getElementById('galleryGrid');
        const lista = Array.isArray(fotos) ? fotos : [];
        grid.innerHTML = lista.map(f => `
            <div class="gallery-item">
                <img src="${f.dados}" alt="${f.titulo||'Foto'}" loading="lazy">
                <div class="gallery-overlay">
                    <span class="gallery-item-title">${f.titulo||formatData(f.criado_em.split(' ')[0])}</span>
                    <div class="gallery-item-actions">
                        <button class="gallery-action-btn" onclick="favoritarFoto(${f.id},${parseInt(f.favorita)?0:1})" title="${parseInt(f.favorita)?'Desfavoritar':'Favoritar'}">${parseInt(f.favorita)?'⭐':'☆'}</button>
                        <button class="gallery-action-btn" onclick="deletarFoto(${f.id})" title="Excluir" style="color:#ffaaaa">🗑</button>
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
    if (!document.getElementById('galleryPhotoPreview').classList.contains('has-image')) { mostrarAlerta('erro','Selecione uma foto'); return; }
    api('upload_foto', {titulo, dados:img.src}).then(r => {
        if (r.sucesso) { mostrarAlerta('sucesso','Foto enviada!'); fecharModal('modalUploadFoto'); carregarGaleria(); }
        else mostrarAlerta('erro', r.erro);
    });
}
function favoritarFoto(id, fav) {
    api('favoritar_foto', {id, favorita:fav}).then(r => { if (r.sucesso) carregarGaleria(); });
}
function deletarFoto(id) {
    abrirModalConfirmar('Excluir Foto','Deseja excluir esta foto permanentemente?', () => {
        api('deletar_foto', {id}).then(r => { if (r.sucesso) { mostrarAlerta('sucesso','Foto excluída'); carregarGaleria(); } });
    });
}

// ==================== FINANÇAS ====================
function carregarFinancas() {
    apiGet('listar_transacoes').then(trans => {
        const lista = Array.isArray(trans) ? trans : [];
        let receitas = 0, despesas = 0;
        lista.forEach(t => { if (t.tipo === 'receita') receitas += parseFloat(t.valor); else despesas += parseFloat(t.valor); });
        document.getElementById('totalReceitas').textContent = formatMoeda(receitas);
        document.getElementById('totalDespesas').textContent = formatMoeda(despesas);
        document.getElementById('saldoTotal').textContent = formatMoeda(receitas - despesas);
        document.getElementById('saldoTotal').style.color = receitas >= despesas ? 'var(--success)' : 'var(--danger)';
        document.getElementById('transacoesList').innerHTML = lista.slice(0,20).map(t => `
            <div class="transacao-item">
                <div class="transacao-icon ${t.tipo}"><i class="fas ${t.tipo==='receita'?'fa-arrow-up':'fa-arrow-down'}"></i></div>
                <div class="transacao-info">
                    <div class="transacao-descricao">${t.descricao}</div>
                    <div class="transacao-data">${t.categoria?t.categoria+' · ':''}${formatData(t.data)} · ${t.autor_nome}</div>
                </div>
                <div class="transacao-valor ${t.tipo}">${t.tipo==='receita'?'+':'-'}${formatMoeda(t.valor)}</div>
                <button class="task-btn delete" onclick="deletarTransacao(${t.id})"><i class="fas fa-trash"></i></button>
            </div>
        `).join('') || '<div class="empty-state"><i class="fas fa-wallet"></i><p>Nenhuma transação</p></div>';
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
    if (!dados.descricao||!dados.valor) { mostrarAlerta('erro','Preencha os campos obrigatórios'); return; }
    api('criar_transacao', dados).then(r => {
        if (r.sucesso) { mostrarAlerta('sucesso','Transação salva!'); fecharModal('modalTransacao'); carregarFinancas(); }
        else mostrarAlerta('erro', r.erro);
    });
}
function deletarTransacao(id) {
    abrirModalConfirmar('Excluir Transação','Deseja excluir esta transação?', () => {
        api('deletar_transacao', {id}).then(r => {
            if (r.sucesso) { mostrarAlerta('sucesso','Transação excluída'); carregarFinancas(); }
        });
    });
}

// ==================== LEMBRETES ====================
function carregarLembretes() {
    apiGet('listar_lembretes').then(lembs => {
        const lista = Array.isArray(lembs) ? lembs : [];
        document.getElementById('lembretesList').innerHTML = lista.map(l => `
            <div class="lembrete-item" style="border-left-color:${l.cor||'var(--accent)'}">
                <div class="lembrete-info">
                    <div class="lembrete-titulo">${l.titulo}</div>
                    <div class="lembrete-meta">
                        ${l.data?`<span>📅 ${formatData(l.data)}</span>`:''}
                        ${l.hora?`<span>🕐 ${l.hora.slice(0,5)}</span>`:''}
                        <span>por ${l.autor_nome}</span>
                    </div>
                    ${l.descricao?`<div style="color:var(--text-secondary);font-size:.82rem;margin-top:5px">${l.descricao}</div>`:''}
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
    document.getElementById('lembreteCor').value = '#2d9e6b';
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
    if (!dados.titulo) { mostrarAlerta('erro','Título obrigatório'); return; }
    api('criar_lembrete', dados).then(r => {
        if (r.sucesso) { mostrarAlerta('sucesso','Lembrete criado!'); fecharModal('modalLembrete'); carregarLembretes(); }
        else mostrarAlerta('erro', r.erro);
    });
}
function deletarLembrete(id) {
    abrirModalConfirmar('Excluir Lembrete','Deseja excluir este lembrete?', () => {
        api('deletar_lembrete', {id}).then(r => { if (r.sucesso) { mostrarAlerta('sucesso','Lembrete excluído'); carregarLembretes(); } });
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
                <div style="display:flex;gap:6px">
                    <button class="task-btn" onclick="baixarDocumento(${d.id},'${d.nome.replace(/'/g,"\\'")}','${d.tipo||''}')" title="Baixar"><i class="fas fa-download"></i></button>
                    <button class="task-btn delete" onclick="deletarDocumento(${d.id})" title="Excluir"><i class="fas fa-trash"></i></button>
                </div>
            </div>`;
        }).join('') || '<div class="empty-state"><i class="fas fa-file-alt"></i><p>Nenhum documento</p></div>';
    });
}
function abrirModalDocumento() {
    document.getElementById('docNome').value = '';
    document.getElementById('docArquivo').value = '';
    document.getElementById('docPreviewInfo').textContent = '';
    docBase64 = null;
    document.getElementById('modalDocumento').style.display = 'flex';
}
function previewDocumento(event) {
    const file = event.target.files[0];
    if (!file) return;
    if (file.size > 10*1024*1024) { mostrarAlerta('erro','Arquivo deve ter no máximo 10MB'); event.target.value=''; return; }
    docTipoArq = file.type; docTamanhoArq = file.size; docNomeArq = file.name;
    document.getElementById('docPreviewInfo').textContent = `${file.name} (${Math.round(file.size/1024)} KB)`;
    if (!document.getElementById('docNome').value) document.getElementById('docNome').value = file.name.replace(/\.[^/.]+$/, '');
    const reader = new FileReader();
    reader.onload = e => { docBase64 = e.target.result; };
    reader.readAsDataURL(file);
}
function uploadDocumento() {
    const nome = document.getElementById('docNome').value.trim();
    if (!nome||!docBase64) { mostrarAlerta('erro', nome ? 'Selecione um arquivo' : 'Nome obrigatório'); return; }
    api('upload_documento', {nome, tipo:docTipoArq, dados:docBase64, tamanho:docTamanhoArq}).then(r => {
        if (r.sucesso) { mostrarAlerta('sucesso','Documento salvo!'); fecharModal('modalDocumento'); carregarDocumentos(); docBase64=null; }
        else mostrarAlerta('erro', r.erro);
    });
}
function baixarDocumento(id, nome, tipo) {
    apiGet('baixar_documento', {id}).then(r => {
        if (r.dados) { const a=document.createElement('a'); a.href=r.dados; a.download=nome; a.click(); }
        else mostrarAlerta('erro','Erro ao baixar');
    });
}
function deletarDocumento(id) {
    abrirModalConfirmar('Excluir Documento','Deseja excluir este documento?', () => {
        api('deletar_documento', {id}).then(r => { if (r.sucesso) { mostrarAlerta('sucesso','Documento excluído'); carregarDocumentos(); } });
    });
}

// ==================== RANKING ====================
function carregarRanking() {
    apiGet('listar_ranking').then(membros => {
        const lista = Array.isArray(membros) ? membros : [];
        document.getElementById('rankingList').innerHTML = lista.map((m, i) => {
            const posClass = i===0?'gold':i===1?'silver':i===2?'bronze':'';
            const medal = i===0?'🥇':i===1?'🥈':i===2?'🥉':`#${i+1}`;
            return `<div class="ranking-item" ${m.id==usuarioAtual.id?'style="border-color:var(--accent);background:var(--accent-soft)"':''}>
                <div class="ranking-pos ${posClass}">${medal}</div>
                <div class="ranking-avatar">${avatarHtml(m.foto, m.nome, 46)}</div>
                <div class="ranking-info">
                    <div class="ranking-nome">${m.nome} ${m.id==usuarioAtual.id?'<span style="color:var(--accent);font-size:.75rem">(você)</span>':''}</div>
                    <div class="ranking-nivel">Nível ${m.nivel} · ${m.cargo==='admin'?'👑':'👤'}</div>
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
    mostrarSubConfig('geral', document.querySelector('.config-menu-item'));
    document.getElementById('configNomeFamilia').value = usuarioAtual.familia_nome || '';
    if (usuarioAtual.familia_foto && usuarioAtual.familia_foto !== 'null') {
        document.getElementById('familyPhotoImg').src = usuarioAtual.familia_foto;
        document.getElementById('familyPhotoImg').style.display = 'block';
        document.getElementById('familyPhotoPlaceholder').style.display = 'none';
    } else {
        document.getElementById('familyPhotoImg').style.display = 'none';
        document.getElementById('familyPhotoPlaceholder').style.display = 'block';
    }
    document.getElementById('perfilNome').value = usuarioAtual.nome;
    if (usuarioAtual.foto && usuarioAtual.foto !== 'null') {
        document.getElementById('perfilFotoImg').src = usuarioAtual.foto;
        document.getElementById('perfilFotoImg').style.display = 'block';
        document.getElementById('perfilFotoPlaceholder').style.display = 'none';
    } else {
        document.getElementById('perfilFotoImg').style.display = 'none';
        document.getElementById('perfilFotoPlaceholder').style.display = 'block';
    }
}
function mostrarSubConfig(pane, btn) {
    document.querySelectorAll('.config-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.config-menu-item').forEach(b => b.classList.remove('active'));
    const id = 'config' + pane.charAt(0).toUpperCase() + pane.slice(1);
    const el = document.getElementById(id);
    if (el) el.classList.add('active');
    if (btn) btn.classList.add('active');
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
                    <div style="width:40px;height:40px;border-radius:50%;background:var(--accent-soft);border:2px solid var(--accent);display:flex;align-items:center;justify-content:center;overflow:hidden;font-weight:700;color:var(--accent)">
                        ${avatarHtml(m.foto, m.nome, 40)}
                    </div>
                    <h4>${m.nome} ${m.cargo==='admin'?'👑':''}</h4>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:.82rem;margin-bottom:10px">👤 @${m.usuario} · ⭐ ${m.pontos} pts · Nível ${m.nivel}</p>
                    <div style="display:flex;gap:8px">
                        ${(usuarioAtual.id==m.id||usuarioAtual.cargo==='admin') ? `<button class="config-btn" onclick="abrirModalEditarMembro(${m.id},'${m.nome.replace(/'/g,"\\'")}')"><i class="fas fa-edit"></i> Editar</button>` : ''}
                        ${(usuarioAtual.cargo==='admin'&&usuarioAtual.id!=m.id) ? `<button class="config-btn config-btn-danger" onclick="removerMembro(${m.id},'${m.nome.replace(/'/g,"\\'")}')"><i class="fas fa-trash"></i> Remover</button>` : ''}
                    </div>
                </div>
            </div>
        `).join('');
    });
}
function salvarConfigFamilia() {
    if (usuarioAtual.cargo !== 'admin') { mostrarAlerta('erro','Apenas admins podem editar a família'); return; }
    const nome = document.getElementById('configNomeFamilia').value.trim();
    if (!nome) { mostrarAlerta('erro','Nome obrigatório'); return; }
    api('atualizar_familia', {nome}).then(r => {
        if (r.sucesso) { usuarioAtual.familia_nome = nome; atualizarSidebar(); mostrarAlerta('sucesso','Configurações salvas!'); }
        else mostrarAlerta('erro', r.erro);
    });
}
function alterarFotoFamilia(event) {
    const file = event.target.files[0];
    if (!file) return;
    if (file.size > 5*1024*1024) { mostrarAlerta('erro','Imagem deve ter no máximo 5MB'); return; }
    const reader = new FileReader();
    reader.onload = e => {
        const foto = e.target.result;
        api('atualizar_familia', {nome:usuarioAtual.familia_nome||'', foto}).then(r => {
            if (r.sucesso) {
                usuarioAtual.familia_foto = foto;
                document.getElementById('familyPhotoImg').src = foto;
                document.getElementById('familyPhotoImg').style.display = 'block';
                document.getElementById('familyPhotoPlaceholder').style.display = 'none';
                mostrarAlerta('sucesso','Foto da família atualizada!');
            } else mostrarAlerta('erro', r.erro);
        });
    };
    reader.readAsDataURL(file);
}
function removerFotoFamilia() {
    if (usuarioAtual.cargo !== 'admin') { mostrarAlerta('erro','Apenas admins podem editar a família'); return; }
    abrirModalConfirmar('Remover Foto','Deseja remover a foto da família?', () => {
        api('atualizar_familia', {nome:usuarioAtual.familia_nome||'', foto:''}).then(r => {
            if (r.sucesso) {
                usuarioAtual.familia_foto = null;
                document.getElementById('familyPhotoImg').style.display = 'none';
                document.getElementById('familyPhotoPlaceholder').style.display = 'block';
                mostrarAlerta('sucesso','Foto removida');
            }
        });
    });
}
function previewPerfilFoto(event) {
    const file = event.target.files[0];
    if (!file) return;
    if (file.size > 5*1024*1024) { mostrarAlerta('erro','Imagem deve ter no máximo 5MB'); return; }
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
    if (!nome) { mostrarAlerta('erro','Nome obrigatório'); return; }
    const dados = {id:usuarioAtual.id, nome, senha_atual:senhaAtual, nova_senha:novaSenha};
    if (perfilFotoBase64) dados.foto = perfilFotoBase64;
    api('atualizar_membro', dados).then(r => {
        if (r.sucesso) {
            usuarioAtual.nome = nome;
            if (perfilFotoBase64) usuarioAtual.foto = perfilFotoBase64;
            atualizarSidebar(); mostrarAlerta('sucesso','Perfil atualizado!');
            perfilFotoBase64 = null;
            document.getElementById('perfilSenhaAtual').value = '';
            document.getElementById('perfilNovaSenha').value = '';
        } else mostrarAlerta('erro', r.erro||'Erro ao salvar');
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
                    <div class="activity-time">${formatDateTime(a.criado_em)}${a.membro_nome?' · '+a.membro_nome:''}</div>
                </div>
            </div>
        `).join('') || '<div style="color:var(--text-muted);text-align:center;padding:30px">Nenhuma atividade</div>';
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

// ==================== THREE.JS PARTÍCULAS ====================
(function() {
    const canvas = document.getElementById('particles-canvas');
    const renderer = new THREE.WebGLRenderer({canvas, alpha:true});
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth/window.innerHeight, 0.1, 1000);
    camera.position.z = 3;
    const geo = new THREE.BufferGeometry();
    const count = 1800;
    const pos = new Float32Array(count*3);
    for (let i = 0; i < count*3; i++) pos[i] = (Math.random()-.5)*10;
    geo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
    const mat = new THREE.PointsMaterial({size:.014, color:0x2d9e6b, transparent:true, opacity:.55});
    const mesh = new THREE.Points(geo, mat);
    scene.add(mesh);
    let tX=0, tY=0;
    document.addEventListener('mousemove', e => { tY=(e.clientX/window.innerWidth)*2-1; tX=-((e.clientY/window.innerHeight)*2-1); });
    let time=0;
    function animate() {
        requestAnimationFrame(animate);
        time += .001;
        const p = geo.attributes.position.array;
        for (let i=0; i<p.length; i+=3) p[i+1] += Math.sin(time+p[i])*.001;
        geo.attributes.position.needsUpdate = true;
        mesh.rotation.y += (tY*.3-mesh.rotation.y)*.05;
        mesh.rotation.x += (tX*.2-mesh.rotation.x)*.05;
        renderer.render(scene, camera);
    }
    animate();
    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth/window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });
})();

// ==================== EVENT LISTENERS ====================
document.getElementById('login-senha').addEventListener('keypress', e => { if (e.key==='Enter') fazerLogin(); });
document.getElementById('chatInput').addEventListener('keypress', e => { if (e.key==='Enter'&&!e.shiftKey) { e.preventDefault(); enviarMensagem(); } });
window.onclick = e => {
    ['modalTarefa','modalEditarMembro','modalUploadFoto','modalTransacao','modalLembrete','modalDocumento','modalConfirmar'].forEach(id => {
        const m = document.getElementById(id);
        if (e.target===m) fecharModal(id);
    });
};
</script>
</body>
</html>