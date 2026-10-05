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
     * Verifica se um e-mail já está registado na plataforma
     */
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM user WHERE email = :email"
        );
        $stmt->execute([':email' => $email]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Verifica se um e-mail já está registado por outro utilizador
     */
    public function emailExistsExcept(string $email, int $excludeUserId): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM user WHERE email = :email AND id != :uid"
        );
        $stmt->execute([':email' => $email, ':uid' => $excludeUserId]);
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
        $stmt = $this->db->prepare("UPDATE user SET status = :status WHERE id = :id");
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    /**
     * Retorna os dados públicos de um utilizador (sem a senha_hash)
     */
    public function getPerfil(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                u.id, u.nome, u.email, u.telefone, u.tipo, u.status, u.ultimo_acesso, u.criado_em,
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
     * Lista todos os utilizadores com estatísticas (para o admin)
     */
    public function listarComEstatisticas(): array
    {
        $stmt = $this->db->query("
            SELECT
                u.id, u.nome, u.email, u.telefone, u.tipo, u.status, u.ultimo_acesso, u.criado_em,
                b.nome AS bairro_nome,
                m.nome AS municipio_nome,
                COUNT(DISTINCT pr.id)  AS total_reportes,
                COUNT(DISTINCT com.id) AS total_comentarios
            FROM user u
            LEFT JOIN bairros    b   ON b.id   = u.bairro_id
            LEFT JOIN municipios m   ON m.id   = b.municipio_id
            LEFT JOIN problemas  pr  ON pr.user_id  = u.id
            LEFT JOIN comentarios com ON com.user_id = u.id
            WHERE u.tipo = 'cidadao'
            GROUP BY u.id
            ORDER BY u.criado_em DESC
        ");
        return $stmt->fetchAll();
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
                $in = implode(',', array_map('intval', $probIds));
                $this->db->exec("DELETE FROM confirmacoes WHERE problema_id IN ($in)");
                $this->db->exec("DELETE FROM comentarios WHERE problema_id IN ($in)");
                $this->db->exec("DELETE FROM notificacoes WHERE problema_id IN ($in)");
                $this->db->exec("DELETE FROM historico_estados WHERE problema_id IN ($in)");
                $this->db->exec("DELETE FROM problemas WHERE user_id = {$id}");
            }

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
}
