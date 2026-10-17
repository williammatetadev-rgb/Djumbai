<?php
/**
 * Djumbai — UserModel
 * Operações sobre a tabela `user`
 */

class UserModel extends Model
{
    protected string $table = 'user';

    /**
     * Encontra um utilizador pelo endereço de e-mail
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM user WHERE email = :email LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Encontra um utilizador pelo telefone
     */
    public function findByTelefone(string $telefone): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM user WHERE telefone = :telefone LIMIT 1"
        );
        $stmt->execute([':telefone' => $telefone]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Encontra um utilizador por e-mail OU telefone (login flexível)
     */
    public function findByEmailOrTelefone(string $identificador): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM user WHERE (email = :id_e AND email IS NOT NULL AND email != '') OR (telefone = :id_t AND telefone IS NOT NULL AND telefone != '') LIMIT 1"
        );
        $stmt->execute([
            ':id_e' => $identificador,
            ':id_t' => $identificador,
        ]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Verifica se um e-mail já está registado na plataforma
     */
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM user WHERE email = :email AND email IS NOT NULL AND email != ''"
        );
        $stmt->execute([':email' => $email]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Verifica se um telefone já está registado na plataforma
     */
    public function telefoneExists(string $telefone): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM user WHERE telefone = :telefone AND telefone IS NOT NULL AND telefone != ''"
        );
        $stmt->execute([':telefone' => $telefone]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Verifica se um e-mail já está registado por outro utilizador
     */
    public function emailExistsExcept(string $email, int $excludeUserId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM user WHERE email = :email AND id != :uid AND email IS NOT NULL AND email != ''"
        );
        $stmt->execute([':email' => $email, ':uid' => $excludeUserId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Verifica se um número de BI já está registado por outro utilizador
     */
    public function biNumeroExistsExcept(string $biNumero, int $excludeUserId): bool
    {
        $biNumero = strtoupper(trim($biNumero));
        if ($biNumero === '') {
            return false;
        }
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM user WHERE bi_numero = :bi AND id != :uid AND bi_numero IS NOT NULL AND bi_numero != ''"
        );
        $stmt->execute([':bi' => $biNumero, ':uid' => $excludeUserId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Atualiza os dados pessoais do perfil do cidadão (nome, e-mail, telefone)
     */
    public function updateDadosPerfil(int $id, string $nome, string $email, string $telefone): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE user SET nome = :nome, email = :email, telefone = :telefone WHERE id = :id"
        );
        return $stmt->execute([
            ':nome'     => $nome,
            ':email'    => $email,
            ':telefone' => $telefone,
            ':id'       => $id,
        ]);
    }

    /**
     * Atualiza a palavra-passe do utilizador
     */
    public function alterarSenha(int $id, string $novaSenha): bool
    {
        $hash = password_hash($novaSenha, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE user SET senha_hash = :hash WHERE id = :id");
        return $stmt->execute([':hash' => $hash, ':id' => $id]);
    }

    /**
     * Atualiza o carimbo de data/hora do último acesso
     */
    public function updateUltimoAcesso(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE user SET ultimo_acesso = NOW() WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    /**
     * Altera o estado do utilizador (ativo / banido)
     */
    public function setStatus(int $id, string $status): bool
    {
        if (!in_array($status, ['ativo', 'banido'], true)) {
            return false;
        }

        // Ao banir, incrementa session_version para invalidar sessões ativas
        if ($status === 'banido') {
            $stmt = $this->db->prepare("UPDATE user SET status = :status, session_version = session_version + 1 WHERE id = :id");
        } else {
            $stmt = $this->db->prepare("UPDATE user SET status = :status WHERE id = :id");
        }
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    /**
     * Retorna os dados públicos de um utilizador (sem a senha_hash)
     */
    public function getPerfil(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                u.id, u.nome, u.email, u.telefone, u.bi_numero, 
                u.nome_completo_bi, u.data_nascimento, u.idade, u.estado_civil, u.nacionalidade,
                u.bi_frente, u.bi_verso, u.bi_status, u.bi_nota, u.bi_avaliado_em,
                u.tipo, u.status, u.ultimo_acesso, u.criado_em,
                b.nome  AS bairro,
                m.nome  AS municipio,
                p.nome  AS provincia,
                COUNT(DISTINCT pr.id)  AS total_reportes,
                COUNT(DISTINCT c.id)   AS total_confirmacoes
            FROM user u
            LEFT JOIN bairros   b ON b.id = u.bairro_id
            LEFT JOIN municipios m ON m.id = b.municipio_id
            LEFT JOIN provincias p ON p.id = m.provincia_id
            LEFT JOIN problemas  pr ON pr.user_id = u.id
            LEFT JOIN confirmacoes c ON c.user_id = u.id
            WHERE u.id = :id
            GROUP BY u.id
        ");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Lista todos os utilizadores com estatísticas e filtros (para o admin)
     */
    public function listarComEstatisticas(
        string $busca = '',
        string $filtroStatus = '',
        string $filtroBi = '',
        int $municipioId = 0,
        string $tipo = 'cidadao'
    ): array {
        $where = ['1 = 1'];
        $params = [];

        if ($tipo !== 'todos') {
            $where[] = 'u.tipo = :tipo';
            $params[':tipo'] = $tipo;
        }

        if (!empty($filtroStatus)) {
            $where[] = 'u.status = :status';
            $params[':status'] = $filtroStatus;
        }

        if (!empty($filtroBi)) {
            $where[] = 'u.bi_status = :bi_status';
            $params[':bi_status'] = $filtroBi;
        }

        if ($municipioId > 0) {
            $where[] = 'b.municipio_id = :mun_id';
            $params[':mun_id'] = $municipioId;
        }

        $busca = trim($busca);
        if ($busca !== '') {
            $where[] = '(u.nome LIKE :b_nome OR u.email LIKE :b_email OR u.telefone LIKE :b_tel OR u.bi_numero LIKE :b_bi)';
            $term = '%' . $busca . '%';
            $params[':b_nome']  = $term;
            $params[':b_email'] = $term;
            $params[':b_tel']   = $term;
            $params[':b_bi']    = $term;
        }

        $whereClause = implode(' AND ', $where);

        $sql = "
            SELECT
                u.id, u.nome, u.email, u.telefone, u.bi_numero, 
                u.bi_frente, u.bi_verso, u.bi_status, u.bi_nota, u.bi_avaliado_em,
                u.tipo, u.status, u.ultimo_acesso, u.criado_em,
                b.nome AS bairro_nome,
                m.nome AS municipio_nome,
                p.nome AS provincia_nome,
                COUNT(DISTINCT pr.id)  AS total_reportes,
                COUNT(DISTINCT com.id) AS total_comentarios
            FROM user u
            LEFT JOIN bairros    b   ON b.id   = u.bairro_id
            LEFT JOIN municipios m   ON m.id   = b.municipio_id
            LEFT JOIN provincias p   ON p.id   = m.provincia_id
            LEFT JOIN problemas  pr  ON pr.user_id  = u.id
            LEFT JOIN comentarios com ON com.user_id = u.id
            WHERE {$whereClause}
            GROUP BY u.id
            ORDER BY u.criado_em DESC
        ";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            if ($k === ':mun_id') {
                $stmt->bindValue($k, $v, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($k, $v, PDO::PARAM_STR);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Avalia e decide sobre o documento de identificação (BI) do cidadão
     */
    public function avaliarDocumentoBI(int $userId, string $novoBiStatus, ?string $nota, int $adminId): bool
    {
        if (!in_array($novoBiStatus, ['verificado', 'rejeitado', 'pendente', 'nao_enviado'], true)) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE user 
            SET bi_status = :status, bi_nota = :nota, bi_avaliado_em = NOW()
            WHERE id = :id
        ");
        $res = $stmt->execute([
            ':status' => $novoBiStatus,
            ':nota'   => $nota ?: null,
            ':id'     => $userId,
        ]);

        if ($res) {
            // Disparar notificação ao cidadão
            $msg = ($novoBiStatus === 'verificado')
                ? "Parabéns! O seu Bilhete de Identidade foi verificado com sucesso. O seu perfil agora possui o Selo Cívico Verificado!"
                : "A verificação do seu Bilhete de Identidade foi rejeitada pela moderação. " . ($nota ? "Motivo: {$nota}" : "Por favor reenvie imagens legíveis nas definições do seu perfil.");

            $stmtNotif = $this->db->prepare("
                INSERT INTO notificacoes (user_id, problema_id, mensagem, lida, criado_em)
                VALUES (:uid, 1, :msg, 0, NOW())
            ");
            $stmtNotif->execute([':uid' => $userId, ':msg' => $msg]);
        }

        return $res;
    }

    /**
     * Retorna o dossiê completo de um utilizador (para a ficha cívica detalhada)
     */
    public function getDossieCompleto(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                u.id, u.nome, u.email, u.telefone, u.bi_numero, 
                u.nome_completo_bi, u.data_nascimento, u.idade, u.estado_civil, u.nacionalidade,
                u.genero,
                u.bi_frente, u.bi_verso, u.bi_status, u.bi_nota, u.bi_avaliado_em,
                u.tipo, u.status, u.ultimo_acesso, u.criado_em,
                b.nome AS bairro_nome,
                m.nome AS municipio_nome,
                p.nome AS provincia_nome,
                COUNT(DISTINCT pr.id)  AS total_reportes,
                COUNT(DISTINCT com.id) AS total_comentarios,
                COUNT(DISTINCT cf.id)  AS total_confirmacoes
            FROM user u
            LEFT JOIN bairros      b   ON b.id   = u.bairro_id
            LEFT JOIN municipios   m   ON m.id   = b.municipio_id
            LEFT JOIN provincias   p   ON p.id   = m.provincia_id
            LEFT JOIN problemas    pr  ON pr.user_id  = u.id
            LEFT JOIN comentarios  com ON com.user_id = u.id
            LEFT JOIN confirmacoes cf  ON cf.user_id  = u.id
            WHERE u.id = :id
            GROUP BY u.id
        ");
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch();
        if (!$user) return null;

        // Buscar ocorrências abertas pelo utilizador
        $stmtProbs = $this->db->prepare("
            SELECT pr.id, pr.titulo, pr.criado_em, pr.estado_id, e.nome AS estado_nome, e.cor AS estado_cor,
                   COUNT(cf.id) AS total_confirmacoes
            FROM problemas pr
            JOIN estados_problema e ON e.id = pr.estado_id
            LEFT JOIN confirmacoes cf ON cf.problema_id = pr.id
            WHERE pr.user_id = :id
            GROUP BY pr.id
            ORDER BY pr.criado_em DESC
            LIMIT 5
        ");
        $stmtProbs->execute([':id' => $userId]);
        $user['ocorrencias_recentes'] = $stmtProbs->fetchAll();

        // Cálculo de Score de Confiabilidade Cívica (0 a 100)
        $score = 50; // Base inicial
        if ($user['bi_status'] === 'verificado') $score += 30;
        elseif ($user['bi_status'] === 'rejeitado') $score -= 20;
        $score += min(20, (int)$user['total_reportes'] * 4);
        $score += min(10, (int)$user['total_confirmacoes'] * 2);
        if ($user['status'] === 'banido') $score = max(0, $score - 40);
        $user['reputacao_score'] = min(100, max(0, $score));

        return $user;
    }

    /**
     * Cria uma nova conta administrativa
     */
    public function criarAdministrador(string $nome, string $email, string $senha, ?string $telefone = null): int
    {
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("
            INSERT INTO user (nome, email, telefone, senha_hash, tipo, status, criado_em)
            VALUES (:nome, :email, :tel, :hash, 'admin', 'ativo', NOW())
        ");
        $stmt->execute([
            ':nome'  => $nome,
            ':email' => $email,
            ':tel'   => $telefone ?: null,
            ':hash'  => $hash,
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Elimina um utilizador e limpa com segurança os seus registos associados
     */
    public function eliminarUsuario(int $id): bool
    {
        try {
            $this->db->beginTransaction();

            // 1. Eliminar notificações do utilizador
            $stmt = $this->db->prepare("DELETE FROM notificacoes WHERE user_id = :uid");
            $stmt->execute([':uid' => $id]);

            // 2. Eliminar confirmações do utilizador
            $stmt = $this->db->prepare("DELETE FROM confirmacoes WHERE user_id = :uid");
            $stmt->execute([':uid' => $id]);

            // 3. Eliminar comentários do utilizador
            $stmt = $this->db->prepare("DELETE FROM comentarios WHERE user_id = :uid");
            $stmt->execute([':uid' => $id]);

            // 4. Tratar ocorrências criadas pelo utilizador
            $stmtProbs = $this->db->prepare("SELECT id FROM problemas WHERE user_id = :uid");
            $stmtProbs->execute([':uid' => $id]);
            $probIds = $stmtProbs->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($probIds)) {
                $placeholders = implode(',', array_fill(0, count($probIds), '?'));
                $stmtDel = $this->db->prepare("DELETE FROM confirmacoes WHERE problema_id IN ($placeholders)");
                $stmtDel->execute($probIds);

                $stmtDel = $this->db->prepare("DELETE FROM comentarios WHERE problema_id IN ($placeholders)");
                $stmtDel->execute($probIds);

                $stmtDel = $this->db->prepare("DELETE FROM notificacoes WHERE problema_id IN ($placeholders)");
                $stmtDel->execute($probIds);

                $stmtDel = $this->db->prepare("DELETE FROM historico_estados WHERE problema_id IN ($placeholders)");
                $stmtDel->execute($probIds);
            }

            $stmtDelProb = $this->db->prepare("DELETE FROM problemas WHERE user_id = :uid");
            $stmtDelProb->execute([':uid' => $id]);

            // 5. Eliminar utilizador (apenas se não for admin)
            $stmt = $this->db->prepare("DELETE FROM user WHERE id = :uid AND tipo != 'admin'");
            $result = $stmt->execute([':uid' => $id]);

            $this->db->commit();
            return $result;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            SecurityLogger::log('USER_DELETE_ERROR', $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza os documentos de identificação (BI Frente e Verso) do utilizador e dados cívicos
     */
    public function atualizarDocumentosBI(
        int $userId,
        string $biFrente,
        string $biVerso,
        ?string $biNumero = null,
        array $dadosCivis = []
    ): bool {
        $fields = [
            'bi_frente = :frente',
            'bi_verso = :verso',
            'bi_status = :status',
            'bi_nota = NULL'
        ];
        $params = [
            ':frente' => $biFrente,
            ':verso'  => $biVerso,
            ':status' => 'verificado',
            ':id'     => $userId,
        ];

        if ($biNumero !== null && trim($biNumero) !== '') {
            $fields[] = 'bi_numero = :bi_num';
            $params[':bi_num'] = strtoupper(trim($biNumero));
        }

        if (!empty($dadosCivis['nome_completo_bi'])) {
            $fields[] = 'nome_completo_bi = :nome_bi';
            $params[':nome_bi'] = trim($dadosCivis['nome_completo_bi']);
        }
        if (!empty($dadosCivis['data_nascimento'])) {
            $fields[] = 'data_nascimento = :data_nasc';
            $params[':data_nasc'] = $dadosCivis['data_nascimento'];
        }
        if (isset($dadosCivis['idade']) && (int)$dadosCivis['idade'] > 0) {
            $fields[] = 'idade = :idade';
            $params[':idade'] = (int)$dadosCivis['idade'];
        }
        if (!empty($dadosCivis['estado_civil'])) {
            $fields[] = 'estado_civil = :est_civil';
            $params[':est_civil'] = trim($dadosCivis['estado_civil']);
        }
        if (!empty($dadosCivis['nacionalidade'])) {
            $fields[] = 'nacionalidade = :nac';
            $params[':nac'] = trim($dadosCivis['nacionalidade']);
        }
        if (!empty($dadosCivis['genero'])) {
            $fields[] = 'genero = :genero';
            $params[':genero'] = ($dadosCivis['genero'] === 'F') ? 'F' : 'M';
        }

        $sql = "UPDATE user SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
