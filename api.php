    <?php
// ============================================================
//  FamilyHub — api.php
//  API REST em PHP puro para o sistema familiar completo.
//  Todas as ações chegam via POST (body FormData) ou GET (query string).
//  Responde sempre em JSON.
// ============================================================

session_start();
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// ============================================================
//  CONFIGURAÇÃO DO BANCO DE DADOS
//  Altere as constantes abaixo conforme seu ambiente.
// ============================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'familyhub');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', '3306');

// ============================================================
//  CONEXÃO PDO (singleton simples)
// ============================================================
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            responder_erro('Erro de conexão com o banco de dados: ' . $e->getMessage(), 500);
        }
    }
    return $pdo;
}

// ============================================================
//  HELPERS DE RESPOSTA
// ============================================================

/** Encerra o script com um JSON de sucesso. */
function responder(array $dados = []): void {
    echo json_encode(array_merge(['sucesso' => true], $dados), JSON_UNESCAPED_UNICODE);
    exit;
}

/** Encerra o script com um JSON de erro. */
function responder_erro(string $mensagem, int $http = 400): void {
    http_response_code($http);
    echo json_encode(['sucesso' => false, 'erro' => $mensagem], JSON_UNESCAPED_UNICODE);
    exit;
}

// ============================================================
//  HELPERS DE INPUT E SEGURANÇA
// ============================================================

/** Lê um parâmetro de POST ou GET, retorna string sanitizada. */
function param(string $chave, string $padrao = ''): string {
    $valor = $_POST[$chave] ?? $_GET[$chave] ?? $padrao;
    return trim((string) $valor);
}

/** Exige que o usuário esteja logado; retorna os dados da sessão. */
function exigir_login(): array {
    if (empty($_SESSION['membro'])) {
        responder_erro('Não autenticado.', 401);
    }
    return $_SESSION['membro'];
}

/** Verifica se o membro logado é admin da sua família. */
function exigir_admin(): array {
    $membro = exigir_login();
    if ($membro['cargo'] !== 'admin') {
        responder_erro('Apenas administradores podem realizar esta ação.', 403);
    }
    return $membro;
}

/** Registra uma entrada na tabela de auditoria. */
function registrar_atividade(int $familia_id, ?int $membro_id, string $tipo, string $descricao): void {
    $sql = 'INSERT INTO atividades (familia_id, membro_id, tipo, descricao) VALUES (?, ?, ?, ?)';
    db()->prepare($sql)->execute([$familia_id, $membro_id, $tipo, $descricao]);
}

/** Adiciona pontos a um membro e verifica evolução de nível. */
function adicionar_pontos(int $membro_id, int $familia_id, int $pontos): void {
    $db = db();

    // Busca situação atual
    $stmt = $db->prepare('SELECT pontos, nivel FROM membros WHERE id = ?');
    $stmt->execute([$membro_id]);
    $membro = $stmt->fetch();
    if (!$membro) return;

    $novos_pontos = $membro['pontos'] + $pontos;
    $nivel_atual  = $membro['nivel'];
    // Fórmula simples: nível sobe a cada 100 pontos
    $novo_nivel   = max(1, (int) floor($novos_pontos / 100) + 1);

    $db->prepare('UPDATE membros SET pontos = ?, nivel = ? WHERE id = ?')
       ->execute([$novos_pontos, $novo_nivel, $membro_id]);

    if ($novo_nivel > $nivel_atual) {
        registrar_atividade(
            $familia_id,
            $membro_id,
            'levelup',
            "Subiu para o nível {$novo_nivel}! 🎉"
        );
    }
}

// ============================================================
//  ROTEAMENTO
// ============================================================
$action = param('action');

if (empty($action)) {
    responder_erro('Parâmetro "action" é obrigatório.');
}

// Mapa de ações para funções
$rotas = [
    // Autenticação
    'verificar_sessao'   => 'acao_verificar_sessao',
    'cadastrar_familia'  => 'acao_cadastrar_familia',
    'cadastrar_membro'   => 'acao_cadastrar_membro',
    'login'              => 'acao_login',
    'logout'             => 'acao_logout',

    // Família
    'listar_familias'    => 'acao_listar_familias',
    'atualizar_familia'  => 'acao_atualizar_familia',

    // Membros
    'listar_membros'     => 'acao_listar_membros',
    'atualizar_membro'   => 'acao_atualizar_membro',
    'remover_membro'     => 'acao_remover_membro',

    // Tarefas
    'listar_tarefas'     => 'acao_listar_tarefas',
    'criar_tarefa'       => 'acao_criar_tarefa',
    'editar_tarefa'      => 'acao_editar_tarefa',
    'concluir_tarefa'    => 'acao_concluir_tarefa',
    'deletar_tarefa'     => 'acao_deletar_tarefa',

    // Chat
    'listar_mensagens'   => 'acao_listar_mensagens',
    'enviar_mensagem'    => 'acao_enviar_mensagem',

    // Galeria
    'listar_fotos'       => 'acao_listar_fotos',
    'upload_foto'        => 'acao_upload_foto',
    'favoritar_foto'     => 'acao_favoritar_foto',
    'deletar_foto'       => 'acao_deletar_foto',

    // Finanças
    'listar_transacoes'  => 'acao_listar_transacoes',
    'criar_transacao'    => 'acao_criar_transacao',
    'deletar_transacao'  => 'acao_deletar_transacao',

    // Lembretes
    'listar_lembretes'   => 'acao_listar_lembretes',
    'criar_lembrete'     => 'acao_criar_lembrete',
    'deletar_lembrete'   => 'acao_deletar_lembrete',

    // Documentos
    'listar_documentos'  => 'acao_listar_documentos',
    'upload_documento'   => 'acao_upload_documento',
    'baixar_documento'   => 'acao_baixar_documento',
    'deletar_documento'  => 'acao_deletar_documento',

    // Ranking e auditoria
    'listar_ranking'     => 'acao_listar_ranking',
    'listar_atividades'  => 'acao_listar_atividades',
];

if (!isset($rotas[$action])) {
    responder_erro("Ação \"{$action}\" não reconhecida.", 404);
}

call_user_func($rotas[$action]);


// ============================================================
// ============================================================
//  IMPLEMENTAÇÃO DAS AÇÕES
// ============================================================
// ============================================================


// ------------------------------------------------------------
//  AUTENTICAÇÃO
// ------------------------------------------------------------

function acao_verificar_sessao(): void {
    if (!empty($_SESSION['membro'])) {
        // Atualiza dados frescos do banco
        $stmt = db()->prepare(
            'SELECT m.*, f.nome AS familia_nome, f.foto AS familia_foto
             FROM membros m
             JOIN familias f ON f.id = m.familia_id
             WHERE m.id = ?'
        );
        $stmt->execute([$_SESSION['membro']['id']]);
        $membro = $stmt->fetch();
        if ($membro) {
            $_SESSION['membro'] = $membro;
            responder(['logado' => true, 'usuario' => $membro]);
        }
    }
    responder(['logado' => false]);
}

function acao_cadastrar_familia(): void {
    $nome_familia   = param('nome_familia');
    $familia_senha  = param('familia_senha');
    $admin_nome     = param('admin_nome');
    $admin_usuario  = param('admin_usuario');
    $admin_senha    = param('admin_senha');
    $foto_familia   = param('foto_familia');

    // Validações
    if (!$nome_familia || !$familia_senha || !$admin_nome || !$admin_usuario || !$admin_senha) {
        responder_erro('Preencha todos os campos obrigatórios.');
    }
    if (strlen($admin_usuario) < 3) {
        responder_erro('O usuário deve ter no mínimo 3 caracteres.');
    }
    if (strlen($admin_senha) < 6) {
        responder_erro('A senha deve ter no mínimo 6 caracteres.');
    }

    $db = db();

    // Verifica usuário duplicado
    $stmt = $db->prepare('SELECT id FROM membros WHERE usuario = ?');
    $stmt->execute([$admin_usuario]);
    if ($stmt->fetch()) {
        responder_erro('Este nome de usuário já está em uso.');
    }

    // Gera código único para a família
    do {
        $codigo = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        $stmt   = $db->prepare('SELECT id FROM familias WHERE codigo = ?');
        $stmt->execute([$codigo]);
    } while ($stmt->fetch());

    $db->beginTransaction();
    try {
        // Cria a família
        $db->prepare(
            'INSERT INTO familias (nome, senha, foto, codigo) VALUES (?, ?, ?, ?)'
        )->execute([
            $nome_familia,
            password_hash($familia_senha, PASSWORD_DEFAULT),
            $foto_familia ?: null,
            $codigo,
        ]);
        $familia_id = (int) $db->lastInsertId();

        // Cria o admin
        $db->prepare(
            'INSERT INTO membros (familia_id, nome, usuario, senha, cargo)
             VALUES (?, ?, ?, ?, "admin")'
        )->execute([
            $familia_id,
            $admin_nome,
            $admin_usuario,
            password_hash($admin_senha, PASSWORD_DEFAULT),
        ]);

        $db->commit();

        registrar_atividade($familia_id, null, 'add', "Família \"{$nome_familia}\" criada.");
        responder(['mensagem' => 'Família criada com sucesso!']);

    } catch (Exception $e) {
        $db->rollBack();
        responder_erro('Erro ao criar família: ' . $e->getMessage(), 500);
    }
}

function acao_cadastrar_membro(): void {
    $familia_id    = (int) param('familia_id');
    $familia_senha = param('familia_senha');
    $nome          = param('nome');
    $usuario       = param('usuario');
    $senha         = param('senha');
    $foto          = param('foto_membro');

    if (!$familia_id || !$familia_senha || !$nome || !$usuario || !$senha) {
        responder_erro('Preencha todos os campos obrigatórios.');
    }
    if (strlen($usuario) < 3)  responder_erro('Usuário deve ter no mínimo 3 caracteres.');
    if (strlen($senha) < 6)    responder_erro('Senha deve ter no mínimo 6 caracteres.');

    $db = db();

    // Verifica família e senha
    $stmt = $db->prepare('SELECT id, nome, senha FROM familias WHERE id = ?');
    $stmt->execute([$familia_id]);
    $familia = $stmt->fetch();

    if (!$familia || !password_verify($familia_senha, $familia['senha'])) {
        responder_erro('Família não encontrada ou senha incorreta.');
    }

    // Verifica usuário duplicado
    $stmt = $db->prepare('SELECT id FROM membros WHERE usuario = ?');
    $stmt->execute([$usuario]);
    if ($stmt->fetch()) {
        responder_erro('Este nome de usuário já está em uso.');
    }

    $db->prepare(
        'INSERT INTO membros (familia_id, nome, usuario, senha, foto) VALUES (?, ?, ?, ?, ?)'
    )->execute([
        $familia_id,
        $nome,
        $usuario,
        password_hash($senha, PASSWORD_DEFAULT),
        $foto ?: null,
    ]);

    $membro_id = (int) $db->lastInsertId();
    registrar_atividade($familia_id, $membro_id, 'add', "{$nome} entrou na família!");
    responder(['mensagem' => 'Conta criada com sucesso!']);
}

function acao_login(): void {
    $usuario = param('usuario');
    $senha   = param('senha');

    if (!$usuario || !$senha) {
        responder_erro('Informe usuário e senha.');
    }

    $stmt = db()->prepare(
        'SELECT m.*, f.nome AS familia_nome, f.foto AS familia_foto
         FROM membros m
         JOIN familias f ON f.id = m.familia_id
         WHERE m.usuario = ?'
    );
    $stmt->execute([$usuario]);
    $membro = $stmt->fetch();

    if (!$membro || !password_verify($senha, $membro['senha'])) {
        responder_erro('Usuário ou senha incorretos.');
    }

    // Remove a senha do array de sessão
    unset($membro['senha']);
    $_SESSION['membro'] = $membro;

    responder(['usuario' => $membro]);
}

function acao_logout(): void {
    session_destroy();
    responder(['mensagem' => 'Sessão encerrada.']);
}


// ------------------------------------------------------------
//  FAMÍLIA
// ------------------------------------------------------------

function acao_listar_familias(): void {
    $stmt = db()->query('SELECT id, nome FROM familias ORDER BY nome ASC');
    $lista = $stmt->fetchAll();
    echo json_encode($lista, JSON_UNESCAPED_UNICODE);
    exit;
}

function acao_atualizar_familia(): void {
    $membro = exigir_admin();
    $nome   = param('nome');
    $foto   = param('foto'); // pode ser vazio para remover

    if (!$nome) responder_erro('Nome da família é obrigatório.');

    // foto = '' remove; foto = null = sem alteração não foi enviada
    $foto_valor = isset($_POST['foto']) ? ($foto ?: null) : false;

    $db = db();
    if ($foto_valor === false) {
        // Só atualiza o nome
        $db->prepare('UPDATE familias SET nome = ? WHERE id = ?')
           ->execute([$nome, $membro['familia_id']]);
    } else {
        $db->prepare('UPDATE familias SET nome = ?, foto = ? WHERE id = ?')
           ->execute([$nome, $foto_valor, $membro['familia_id']]);
    }

    // Atualiza sessão
    $_SESSION['membro']['familia_nome'] = $nome;
    if ($foto_valor !== false) {
        $_SESSION['membro']['familia_foto'] = $foto_valor;
    }

    registrar_atividade($membro['familia_id'], $membro['id'], 'edit', 'Configurações da família atualizadas.');
    responder(['mensagem' => 'Família atualizada.']);
}


// ------------------------------------------------------------
//  MEMBROS
// ------------------------------------------------------------

function acao_listar_membros(): void {
    $membro = exigir_login();
    $stmt   = db()->prepare(
        'SELECT id, nome, usuario, foto, cargo, pontos, nivel, criado_em
         FROM membros WHERE familia_id = ? ORDER BY nome ASC'
    );
    $stmt->execute([$membro['familia_id']]);
    $lista = $stmt->fetchAll();
    echo json_encode($lista, JSON_UNESCAPED_UNICODE);
    exit;
}

function acao_atualizar_membro(): void {
    $sessao     = exigir_login();
    $alvo_id    = (int) param('id', (string) $sessao['id']);
    $nome       = param('nome');
    $senha_atual = param('senha_atual');
    $nova_senha  = param('nova_senha');
    $foto        = param('foto');

    // Só admin pode editar outros membros
    if ($alvo_id !== (int) $sessao['id'] && $sessao['cargo'] !== 'admin') {
        responder_erro('Sem permissão para editar este membro.', 403);
    }

    $db   = db();
    $stmt = $db->prepare('SELECT * FROM membros WHERE id = ? AND familia_id = ?');
    $stmt->execute([$alvo_id, $sessao['familia_id']]);
    $alvo = $stmt->fetch();
    if (!$alvo) responder_erro('Membro não encontrado.', 404);

    if (!$nome) responder_erro('Nome é obrigatório.');

    $campos = ['nome = ?'];
    $valores = [$nome];

    // Troca de foto (se enviada)
    if (isset($_POST['foto']) && $foto !== '') {
        $campos[]  = 'foto = ?';
        $valores[] = $foto;
    }

    // Troca de senha (requer senha atual, exceto admin editando outro)
    if ($nova_senha !== '') {
        if (strlen($nova_senha) < 6) responder_erro('Nova senha deve ter no mínimo 6 caracteres.');
        if ($alvo_id === (int) $sessao['id']) {
            // Editando a si mesmo: exige senha atual
            if (!$senha_atual || !password_verify($senha_atual, $alvo['senha'])) {
                responder_erro('Senha atual incorreta.');
            }
        }
        $campos[]  = 'senha = ?';
        $valores[] = password_hash($nova_senha, PASSWORD_DEFAULT);
    }

    $valores[] = $alvo_id;
    $db->prepare('UPDATE membros SET ' . implode(', ', $campos) . ' WHERE id = ?')
       ->execute($valores);

    // Atualiza sessão se for o próprio usuário
    if ($alvo_id === (int) $sessao['id']) {
        $_SESSION['membro']['nome'] = $nome;
        if (isset($_POST['foto']) && $foto !== '') {
            $_SESSION['membro']['foto'] = $foto;
        }
    }

    registrar_atividade($sessao['familia_id'], $sessao['id'], 'edit', "Perfil de \"{$nome}\" atualizado.");
    responder(['mensagem' => 'Membro atualizado.']);
}

function acao_remover_membro(): void {
    $admin = exigir_admin();
    $id    = (int) param('id');

    if (!$id) responder_erro('ID do membro é obrigatório.');
    if ($id === (int) $admin['id']) responder_erro('Você não pode remover a si mesmo.');

    $db   = db();
    $stmt = $db->prepare('SELECT nome FROM membros WHERE id = ? AND familia_id = ?');
    $stmt->execute([$id, $admin['familia_id']]);
    $alvo = $stmt->fetch();
    if (!$alvo) responder_erro('Membro não encontrado.', 404);

    $db->prepare('DELETE FROM membros WHERE id = ?')->execute([$id]);

    registrar_atividade($admin['familia_id'], $admin['id'], 'delete', "Membro \"{$alvo['nome']}\" removido da família.");
    responder(['mensagem' => 'Membro removido.']);
}


// ------------------------------------------------------------
//  TAREFAS
// ------------------------------------------------------------

function acao_listar_tarefas(): void {
    $membro = exigir_login();
    $stmt   = db()->prepare(
        'SELECT * FROM tarefas WHERE familia_id = ? ORDER BY data ASC, hora ASC, id DESC'
    );
    $stmt->execute([$membro['familia_id']]);
    $lista = $stmt->fetchAll();
    echo json_encode($lista, JSON_UNESCAPED_UNICODE);
    exit;
}

function acao_criar_tarefa(): void {
    $membro = exigir_login();
    $titulo = param('titulo');

    if (!$titulo) responder_erro('Título é obrigatório.');

    $db = db();
    $db->prepare(
        'INSERT INTO tarefas
            (familia_id, titulo, descricao, responsavel, data, hora, prioridade, pontos, criado_por)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
    )->execute([
        $membro['familia_id'],
        $titulo,
        param('descricao')   ?: null,
        param('responsavel') ?: null,
        param('data')        ?: null,
        param('hora')        ?: null,
        param('prioridade')  ?: 'media',
        max(1, min(100, (int) param('pontos', '10'))),
        $membro['id'],
    ]);

    registrar_atividade($membro['familia_id'], $membro['id'], 'add', "Tarefa \"{$titulo}\" criada.");
    responder(['mensagem' => 'Tarefa criada.']);
}

function acao_editar_tarefa(): void {
    $membro = exigir_login();
    $id     = (int) param('id');
    $titulo = param('titulo');

    if (!$id)     responder_erro('ID da tarefa é obrigatório.');
    if (!$titulo) responder_erro('Título é obrigatório.');

    $stmt = db()->prepare('SELECT id FROM tarefas WHERE id = ? AND familia_id = ?');
    $stmt->execute([$id, $membro['familia_id']]);
    if (!$stmt->fetch()) responder_erro('Tarefa não encontrada.', 404);

    db()->prepare(
        'UPDATE tarefas
         SET titulo = ?, descricao = ?, responsavel = ?, data = ?, hora = ?, prioridade = ?, pontos = ?
         WHERE id = ?'
    )->execute([
        $titulo,
        param('descricao')   ?: null,
        param('responsavel') ?: null,
        param('data')        ?: null,
        param('hora')        ?: null,
        param('prioridade')  ?: 'media',
        max(1, min(100, (int) param('pontos', '10'))),
        $id,
    ]);

    registrar_atividade($membro['familia_id'], $membro['id'], 'edit', "Tarefa \"{$titulo}\" editada.");
    responder(['mensagem' => 'Tarefa atualizada.']);
}

function acao_concluir_tarefa(): void {
    $membro    = exigir_login();
    $id        = (int) param('id');
    $concluida = (int) param('concluida');

    if (!$id) responder_erro('ID da tarefa é obrigatório.');

    $db   = db();
    $stmt = $db->prepare('SELECT titulo, pontos, concluida FROM tarefas WHERE id = ? AND familia_id = ?');
    $stmt->execute([$id, $membro['familia_id']]);
    $tarefa = $stmt->fetch();
    if (!$tarefa) responder_erro('Tarefa não encontrada.', 404);

    $db->prepare('UPDATE tarefas SET concluida = ? WHERE id = ?')->execute([$concluida, $id]);

    // Pontos: ganha ao concluir, perde ao desmarcar
    if ($concluida && !$tarefa['concluida']) {
        adicionar_pontos($membro['id'], $membro['familia_id'], (int) $tarefa['pontos']);
        registrar_atividade(
            $membro['familia_id'],
            $membro['id'],
            'check',
            "Tarefa \"{$tarefa['titulo']}\" concluída. +{$tarefa['pontos']} pontos!"
        );
    }

    responder(['mensagem' => $concluida ? 'Tarefa concluída!' : 'Tarefa reaberta.']);
}

function acao_deletar_tarefa(): void {
    $membro = exigir_login();
    $id     = (int) param('id');

    if (!$id) responder_erro('ID da tarefa é obrigatório.');

    $stmt = db()->prepare('SELECT titulo FROM tarefas WHERE id = ? AND familia_id = ?');
    $stmt->execute([$id, $membro['familia_id']]);
    $tarefa = $stmt->fetch();
    if (!$tarefa) responder_erro('Tarefa não encontrada.', 404);

    db()->prepare('DELETE FROM tarefas WHERE id = ?')->execute([$id]);

    registrar_atividade($membro['familia_id'], $membro['id'], 'delete', "Tarefa \"{$tarefa['titulo']}\" excluída.");
    responder(['mensagem' => 'Tarefa excluída.']);
}


// ------------------------------------------------------------
//  CHAT
// ------------------------------------------------------------

function acao_listar_mensagens(): void {
    $membro = exigir_login();
    $stmt   = db()->prepare(
        'SELECT msg.*, m.nome AS autor_nome, m.foto AS autor_foto
         FROM mensagens msg
         JOIN membros m ON m.id = msg.membro_id
         WHERE msg.familia_id = ?
         ORDER BY msg.criado_em ASC
         LIMIT 200'
    );
    $stmt->execute([$membro['familia_id']]);
    $lista = $stmt->fetchAll();
    echo json_encode($lista, JSON_UNESCAPED_UNICODE);
    exit;
}

function acao_enviar_mensagem(): void {
    $membro = exigir_login();
    $texto  = param('texto');

    if (!$texto) responder_erro('A mensagem não pode estar vazia.');
    if (strlen($texto) > 2000) responder_erro('Mensagem muito longa (máx. 2000 caracteres).');

    db()->prepare(
        'INSERT INTO mensagens (familia_id, membro_id, texto) VALUES (?, ?, ?)'
    )->execute([$membro['familia_id'], $membro['id'], $texto]);

    // Ganha 1 ponto por mensagem
    adicionar_pontos($membro['id'], $membro['familia_id'], 1);

    responder(['mensagem' => 'Mensagem enviada.']);
}


// ------------------------------------------------------------
//  GALERIA
// ------------------------------------------------------------

function acao_listar_fotos(): void {
    $membro = exigir_login();
    $stmt   = db()->prepare(
        'SELECT f.id, f.titulo, f.dados, f.favorita, f.criado_em,
                m.nome AS autor_nome
         FROM fotos f
         JOIN membros m ON m.id = f.membro_id
         WHERE f.familia_id = ?
         ORDER BY f.favorita DESC, f.criado_em DESC'
    );
    $stmt->execute([$membro['familia_id']]);
    $lista = $stmt->fetchAll();
    echo json_encode($lista, JSON_UNESCAPED_UNICODE);
    exit;
}

function acao_upload_foto(): void {
    $membro = exigir_login();
    $dados  = param('dados');   // base64 da imagem
    $titulo = param('titulo');

    if (!$dados) responder_erro('Nenhuma foto enviada.');

    // Valida tamanho aproximado (base64 ~133% do original; limite ~5 MB)
    if (strlen($dados) > 7 * 1024 * 1024) {
        responder_erro('Imagem muito grande. Máximo 5 MB.');
    }

    db()->prepare(
        'INSERT INTO fotos (familia_id, membro_id, titulo, dados) VALUES (?, ?, ?, ?)'
    )->execute([$membro['familia_id'], $membro['id'], $titulo ?: null, $dados]);

    registrar_atividade($membro['familia_id'], $membro['id'], 'add', 'Nova foto adicionada à galeria.');
    responder(['mensagem' => 'Foto enviada!']);
}

function acao_favoritar_foto(): void {
    $membro   = exigir_login();
    $id       = (int) param('id');
    $favorita = (int) param('favorita');

    if (!$id) responder_erro('ID da foto é obrigatório.');

    $stmt = db()->prepare('SELECT id FROM fotos WHERE id = ? AND familia_id = ?');
    $stmt->execute([$id, $membro['familia_id']]);
    if (!$stmt->fetch()) responder_erro('Foto não encontrada.', 404);

    db()->prepare('UPDATE fotos SET favorita = ? WHERE id = ?')->execute([$favorita, $id]);
    responder(['mensagem' => $favorita ? 'Foto favoritada.' : 'Foto desfavoritada.']);
}

function acao_deletar_foto(): void {
    $membro = exigir_login();
    $id     = (int) param('id');

    if (!$id) responder_erro('ID da foto é obrigatório.');

    // Admin pode deletar qualquer foto; membro, só a própria
    $condicao = $membro['cargo'] === 'admin'
        ? 'id = ? AND familia_id = ?'
        : 'id = ? AND membro_id = ?';
    $referencia = $membro['cargo'] === 'admin'
        ? $membro['familia_id']
        : $membro['id'];

    $stmt = db()->prepare("SELECT id FROM fotos WHERE {$condicao}");
    $stmt->execute([$id, $referencia]);
    if (!$stmt->fetch()) responder_erro('Foto não encontrada ou sem permissão.', 404);

    db()->prepare('DELETE FROM fotos WHERE id = ?')->execute([$id]);

    registrar_atividade($membro['familia_id'], $membro['id'], 'delete', 'Foto excluída da galeria.');
    responder(['mensagem' => 'Foto excluída.']);
}


// ------------------------------------------------------------
//  FINANÇAS
// ------------------------------------------------------------

function acao_listar_transacoes(): void {
    $membro = exigir_login();
    $stmt   = db()->prepare(
        'SELECT t.*, m.nome AS autor_nome
         FROM transacoes t
         JOIN membros m ON m.id = t.membro_id
         WHERE t.familia_id = ?
         ORDER BY t.data DESC, t.criado_em DESC
         LIMIT 100'
    );
    $stmt->execute([$membro['familia_id']]);
    $lista = $stmt->fetchAll();
    echo json_encode($lista, JSON_UNESCAPED_UNICODE);
    exit;
}

function acao_criar_transacao(): void {
    $membro    = exigir_login();
    $descricao = param('descricao');
    $valor     = param('valor');
    $tipo      = param('tipo');
    $data      = param('data');

    if (!$descricao) responder_erro('Descrição é obrigatória.');
    if (!$valor || (float)$valor <= 0) responder_erro('Valor inválido.');
    if (!in_array($tipo, ['receita', 'despesa'])) responder_erro('Tipo inválido.');
    if (!$data) responder_erro('Data é obrigatória.');

    db()->prepare(
        'INSERT INTO transacoes (familia_id, membro_id, descricao, valor, tipo, categoria, data)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    )->execute([
        $membro['familia_id'],
        $membro['id'],
        $descricao,
        number_format((float) $valor, 2, '.', ''),
        $tipo,
        param('categoria') ?: null,
        $data,
    ]);

    $icone = $tipo === 'receita' ? '💰' : '💸';
    registrar_atividade(
        $membro['familia_id'],
        $membro['id'],
        'add',
        "{$icone} Nova transação: \"{$descricao}\" — R\$ {$valor}."
    );
    responder(['mensagem' => 'Transação salva.']);
}

function acao_deletar_transacao(): void {
    $membro = exigir_login();
    $id     = (int) param('id');

    if (!$id) responder_erro('ID da transação é obrigatório.');

    $condicao   = $membro['cargo'] === 'admin' ? 'id = ? AND familia_id = ?' : 'id = ? AND membro_id = ?';
    $referencia = $membro['cargo'] === 'admin' ? $membro['familia_id'] : $membro['id'];

    $stmt = db()->prepare("SELECT descricao FROM transacoes WHERE {$condicao}");
    $stmt->execute([$id, $referencia]);
    $trans = $stmt->fetch();
    if (!$trans) responder_erro('Transação não encontrada ou sem permissão.', 404);

    db()->prepare('DELETE FROM transacoes WHERE id = ?')->execute([$id]);

    registrar_atividade($membro['familia_id'], $membro['id'], 'delete', "Transação \"{$trans['descricao']}\" excluída.");
    responder(['mensagem' => 'Transação excluída.']);
}


// ------------------------------------------------------------
//  LEMBRETES
// ------------------------------------------------------------

function acao_listar_lembretes(): void {
    $membro = exigir_login();
    $stmt   = db()->prepare(
        'SELECT l.*, m.nome AS autor_nome
         FROM lembretes l
         JOIN membros m ON m.id = l.membro_id
         WHERE l.familia_id = ?
         ORDER BY l.data ASC, l.hora ASC, l.criado_em DESC'
    );
    $stmt->execute([$membro['familia_id']]);
    $lista = $stmt->fetchAll();
    echo json_encode($lista, JSON_UNESCAPED_UNICODE);
    exit;
}

function acao_criar_lembrete(): void {
    $membro = exigir_login();
    $titulo = param('titulo');

    if (!$titulo) responder_erro('Título é obrigatório.');

    db()->prepare(
        'INSERT INTO lembretes (familia_id, membro_id, titulo, descricao, data, hora, cor)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    )->execute([
        $membro['familia_id'],
        $membro['id'],
        $titulo,
        param('descricao') ?: null,
        param('data')      ?: null,
        param('hora')      ?: null,
        param('cor')       ?: '#3aaa6e',
    ]);

    registrar_atividade($membro['familia_id'], $membro['id'], 'add', "Lembrete \"{$titulo}\" criado.");
    responder(['mensagem' => 'Lembrete criado.']);
}

function acao_deletar_lembrete(): void {
    $membro = exigir_login();
    $id     = (int) param('id');

    if (!$id) responder_erro('ID do lembrete é obrigatório.');

    $condicao   = $membro['cargo'] === 'admin' ? 'id = ? AND familia_id = ?' : 'id = ? AND membro_id = ?';
    $referencia = $membro['cargo'] === 'admin' ? $membro['familia_id'] : $membro['id'];

    $stmt = db()->prepare("SELECT titulo FROM lembretes WHERE {$condicao}");
    $stmt->execute([$id, $referencia]);
    $lemb = $stmt->fetch();
    if (!$lemb) responder_erro('Lembrete não encontrado ou sem permissão.', 404);

    db()->prepare('DELETE FROM lembretes WHERE id = ?')->execute([$id]);

    registrar_atividade($membro['familia_id'], $membro['id'], 'delete', "Lembrete \"{$lemb['titulo']}\" excluído.");
    responder(['mensagem' => 'Lembrete excluído.']);
}


// ------------------------------------------------------------
//  DOCUMENTOS
// ------------------------------------------------------------

function acao_listar_documentos(): void {
    $membro = exigir_login();
    $stmt   = db()->prepare(
        'SELECT d.id, d.nome, d.tipo, d.tamanho, d.criado_em, m.nome AS autor_nome
         FROM documentos d
         JOIN membros m ON m.id = d.membro_id
         WHERE d.familia_id = ?
         ORDER BY d.criado_em DESC'
    );
    // Intencionalmente não retorna `dados` (base64) na listagem para economizar tráfego
    $stmt->execute([$membro['familia_id']]);
    $lista = $stmt->fetchAll();
    echo json_encode($lista, JSON_UNESCAPED_UNICODE);
    exit;
}

function acao_upload_documento(): void {
    $membro  = exigir_login();
    $nome    = param('nome');
    $dados   = param('dados');
    $tipo    = param('tipo');
    $tamanho = (int) param('tamanho');

    if (!$nome)  responder_erro('Nome do documento é obrigatório.');
    if (!$dados) responder_erro('Nenhum arquivo enviado.');

    // Limite ~10 MB (base64 ~133%)
    if (strlen($dados) > 14 * 1024 * 1024) {
        responder_erro('Arquivo muito grande. Máximo 10 MB.');
    }

    db()->prepare(
        'INSERT INTO documentos (familia_id, membro_id, nome, tipo, dados, tamanho)
         VALUES (?, ?, ?, ?, ?, ?)'
    )->execute([
        $membro['familia_id'],
        $membro['id'],
        $nome,
        $tipo   ?: null,
        $dados,
        $tamanho ?: null,
    ]);

    registrar_atividade($membro['familia_id'], $membro['id'], 'add', "Documento \"{$nome}\" adicionado.");
    responder(['mensagem' => 'Documento salvo.']);
}

function acao_baixar_documento(): void {
    $membro = exigir_login();
    $id     = (int) param('id');

    if (!$id) responder_erro('ID do documento é obrigatório.');

    $stmt = db()->prepare('SELECT dados FROM documentos WHERE id = ? AND familia_id = ?');
    $stmt->execute([$id, $membro['familia_id']]);
    $doc = $stmt->fetch();
    if (!$doc) responder_erro('Documento não encontrado.', 404);

    responder(['dados' => $doc['dados']]);
}

function acao_deletar_documento(): void {
    $membro = exigir_login();
    $id     = (int) param('id');

    if (!$id) responder_erro('ID do documento é obrigatório.');

    $condicao   = $membro['cargo'] === 'admin' ? 'id = ? AND familia_id = ?' : 'id = ? AND membro_id = ?';
    $referencia = $membro['cargo'] === 'admin' ? $membro['familia_id'] : $membro['id'];

    $stmt = db()->prepare("SELECT nome FROM documentos WHERE {$condicao}");
    $stmt->execute([$id, $referencia]);
    $doc = $stmt->fetch();
    if (!$doc) responder_erro('Documento não encontrado ou sem permissão.', 404);

    db()->prepare('DELETE FROM documentos WHERE id = ?')->execute([$id]);

    registrar_atividade($membro['familia_id'], $membro['id'], 'delete', "Documento \"{$doc['nome']}\" excluído.");
    responder(['mensagem' => 'Documento excluído.']);
}


// ------------------------------------------------------------
//  RANKING E AUDITORIA
// ------------------------------------------------------------

function acao_listar_ranking(): void {
    $membro = exigir_login();
    $stmt   = db()->prepare(
        'SELECT id, nome, foto, cargo, pontos, nivel
         FROM membros
         WHERE familia_id = ?
         ORDER BY pontos DESC, nivel DESC'
    );
    $stmt->execute([$membro['familia_id']]);
    $lista = $stmt->fetchAll();
    echo json_encode($lista, JSON_UNESCAPED_UNICODE);
    exit;
}

function acao_listar_atividades(): void {
    $membro = exigir_login();
    $filtro = param('filtro'); // tipo: add, edit, delete, check, levelup

    $sql    = 'SELECT a.*, m.nome AS membro_nome
               FROM atividades a
               LEFT JOIN membros m ON m.id = a.membro_id
               WHERE a.familia_id = ?';
    $params = [$membro['familia_id']];

    if ($filtro) {
        $sql    .= ' AND a.tipo = ?';
        $params[] = $filtro;
    }

    $sql .= ' ORDER BY a.criado_em DESC LIMIT 100';

    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $lista = $stmt->fetchAll();
    echo json_encode($lista, JSON_UNESCAPED_UNICODE);
    exit;
}