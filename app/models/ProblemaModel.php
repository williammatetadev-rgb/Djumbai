<?php
/**
 * Djumbai — ProblemaModel
 * Todas as queries relacionadas com problemas comunitários
 */

class ProblemaModel extends Model
{
    protected string $table = 'problemas';

    /**
     * Lista problemas com filtros e paginação.
     * Inclui dados do utilizador, bairro, categoria e estado.
     */
    public function listar(
        int $categoriaId = 0,
        int $estadoId    = 0,
        int $limite      = 10,
        int $offset      = 0,
        string $search   = '',
        int $municipioId = 0,
        string $ordem    = 'populares'
    ): array {
        $where = ['1 = 1'];
        $params = [];

        if ($categoriaId > 0) {
            $where[]  = 'pr.categoria_id = :cat_id';
            $params[':cat_id'] = $categoriaId;
        }

        if ($estadoId > 0) {
            $where[]  = 'pr.estado_id = :est_id';
            $params[':est_id'] = $estadoId;
        }

        if ($municipioId > 0) {
            $where[] = 'b.municipio_id = :mun_id';
            $params[':mun_id'] = $municipioId;
        }

        $search = trim($search);
        if ($search !== '') {
            $where[] = '(pr.titulo LIKE :search OR pr.descricao LIKE :search OR pr.referencia_local LIKE :search OR b.nome LIKE :search OR m.nome LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $whereClause = implode(' AND ', $where);

        $orderBy = match ($ordem) {
            'recentes' => 'pr.criado_em DESC',
            'antigos'  => 'pr.criado_em ASC',
            default    => 'total_confirmacoes DESC, pr.criado_em DESC',
        };

        $sql = "
            SELECT
                pr.id, pr.titulo, pr.foto, pr.referencia_local, pr.criado_em,
                pr.estado_id, pr.categoria_id, pr.user_id,
                u.nome              AS autor_nome,
                c.nome              AS categoria_nome,
                e.nome              AS estado_nome,
                e.cor               AS estado_cor,
                b.nome              AS bairro_nome,
                m.nome              AS municipio_nome,
                COUNT(DISTINCT cf.id) AS total_confirmacoes
            FROM problemas pr
            LEFT JOIN user            u  ON u.id  = pr.user_id
            LEFT JOIN categorias      c  ON c.id  = pr.categoria_id
            LEFT JOIN estados_problema e ON e.id  = pr.estado_id
            LEFT JOIN bairros         b  ON b.id  = pr.bairro_id
            LEFT JOIN municipios      m  ON m.id  = b.municipio_id
            LEFT JOIN confirmacoes    cf ON cf.problema_id = pr.id
            WHERE {$whereClause}
            GROUP BY pr.id
            ORDER BY {$orderBy}
            LIMIT :limite OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            if ($key === ':search') {
                $stmt->bindValue($key, $val, PDO::PARAM_STR);
            } else {
                $stmt->bindValue($key, $val, PDO::PARAM_INT);
            }
        }
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Total de registos com os filtros activos (para paginação)
     */
    public function totalFiltrado(
        int $categoriaId = 0,
        int $estadoId    = 0,
        string $search   = '',
        int $municipioId = 0
    ): int {
        $where  = ['1 = 1'];
        $params = [];

        if ($categoriaId > 0) {
            $where[] = 'pr.categoria_id = :cat_id';
            $params[':cat_id'] = $categoriaId;
        }

        if ($estadoId > 0) {
            $where[] = 'pr.estado_id = :est_id';
            $params[':est_id'] = $estadoId;
        }

        if ($municipioId > 0) {
            $where[] = 'b.municipio_id = :mun_id';
            $params[':mun_id'] = $municipioId;
        }

        $search = trim($search);
        if ($search !== '') {
            $where[] = '(pr.titulo LIKE :search OR pr.descricao LIKE :search OR pr.referencia_local LIKE :search OR b.nome LIKE :search OR m.nome LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $whereClause = implode(' AND ', $where);
        $sql = "
            SELECT COUNT(DISTINCT pr.id) 
            FROM problemas pr
            LEFT JOIN bairros    b ON b.id = pr.bairro_id
            LEFT JOIN municipios m ON m.id = b.municipio_id
            WHERE {$whereClause}
        ";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            if ($key === ':search') {
                $stmt->bindValue($key, $val, PDO::PARAM_STR);
            } else {
                $stmt->bindValue($key, $val, PDO::PARAM_INT);
            }
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Detalhe completo de um problema (para a página individual)
     */
    public function getDetalhes(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                pr.*,
                u.nome              AS autor_nome,
                c.nome              AS categoria_nome,
                e.nome              AS estado_nome,
                e.cor               AS estado_cor,
                b.nome              AS bairro_nome,
                m.nome              AS municipio_nome,
                p.nome              AS provincia_nome,
                COUNT(DISTINCT cf.id) AS total_confirmacoes
            FROM problemas pr
            LEFT JOIN user            u  ON u.id  = pr.user_id
            LEFT JOIN categorias      c  ON c.id  = pr.categoria_id
            LEFT JOIN estados_problema e ON e.id  = pr.estado_id
            LEFT JOIN bairros         b  ON b.id  = pr.bairro_id
            LEFT JOIN municipios      m  ON m.id  = b.municipio_id
            LEFT JOIN provincias      p  ON p.id  = m.provincia_id
            LEFT JOIN confirmacoes    cf ON cf.problema_id = pr.id
            WHERE pr.id = :id
            GROUP BY pr.id
        ");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Retorna os N problemas com mais confirmações (para homepage)
     */
    public function getMaisReportados(int $limite = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT
                pr.id, pr.titulo, pr.criado_em,
                b.nome              AS bairro_nome,
                m.nome              AS municipio_nome,
                c.nome              AS categoria_nome,
                e.nome              AS estado_nome,
                e.cor               AS estado_cor,
                COUNT(cf.id)        AS total_confirmacoes
            FROM problemas pr
            LEFT JOIN bairros         b  ON b.id  = pr.bairro_id
            LEFT JOIN municipios      m  ON m.id  = b.municipio_id
            LEFT JOIN categorias      c  ON c.id  = pr.categoria_id
            LEFT JOIN estados_problema e ON e.id  = pr.estado_id
            LEFT JOIN confirmacoes    cf ON cf.problema_id = pr.id
            GROUP BY pr.id
            ORDER BY total_confirmacoes DESC
            LIMIT :limite
        ");
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Panorama geral de estatísticas (para homepage e dashboard admin)
     */
    public function getPanorama(): array
    {
        $stmt = $this->db->query("
            SELECT
                SUM(estado_id = 1) AS pendentes,
                SUM(estado_id = 2) AS em_analise,
                SUM(estado_id = 3) AS resolvidos,
                SUM(estado_id = 4) AS rejeitados,
                COUNT(*)           AS total
            FROM problemas
        ");
        return $stmt->fetch() ?: [
            'pendentes'  => 0,
            'em_analise' => 0,
            'resolvidos' => 0,
            'rejeitados' => 0,
            'total'      => 0,
        ];
    }

    /**
     * Comentários de um problema ordenados cronologicamente e agrupados com respostas
     */
    public function getComentarios(int $problemaId): array
    {
        $stmt = $this->db->prepare("
            SELECT cm.*, u.nome AS autor_nome, u.tipo AS autor_tipo
            FROM comentarios cm
            LEFT JOIN user u ON u.id = cm.user_id
            WHERE cm.problema_id = :pid
            ORDER BY cm.criado_em ASC
        ");
        $stmt->execute([':pid' => $problemaId]);
        $all = $stmt->fetchAll();

        $comentarios = [];
        $respostas = [];

        foreach ($all as $item) {
            if (empty($item['parent_id'])) {
                $item['respostas'] = [];
                $comentarios[$item['id']] = $item;
            } else {
                $respostas[] = $item;
            }
        }

        foreach ($respostas as $resp) {
            $pId = (int) $resp['parent_id'];
            if (isset($comentarios[$pId])) {
                $comentarios[$pId]['respostas'][] = $resp;
            } else {
                $resp['respostas'] = [];
                $comentarios[$resp['id']] = $resp;
            }
        }

        return array_values($comentarios);
    }

    /**
     * Adiciona um novo comentário ou resposta a um problema
     */
    public function adicionarComentario(int $problemaId, int $userId, string $texto, ?int $parentId = null): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO comentarios (problema_id, user_id, parent_id, texto, criado_em)
            VALUES (:problema_id, :user_id, :parent_id, :texto, NOW())
        ");
        $stmt->execute([
            ':problema_id' => $problemaId,
            ':user_id'     => $userId,
            ':parent_id'   => $parentId ?: null,
            ':texto'       => $texto,
        ]);

        // Se for resposta a outro comentário, notificar o autor do comentário pai
        if ($parentId > 0) {
            $stmtParent = $this->db->prepare("SELECT user_id FROM comentarios WHERE id = :pid");
            $stmtParent->execute([':pid' => $parentId]);
            $parentUserId = (int) $stmtParent->fetchColumn();

            if ($parentUserId > 0 && $parentUserId !== $userId) {
                $this->criarNotificacao(
                    $parentUserId,
                    $problemaId,
                    "Um morador respondeu ao seu comentário numa ocorrência comunitária."
                );
            }
        }
    }

    /**
     * Verifica se um utilizador já confirmou um problema específico
     */
    public function jaConfirmou(int $problemaId, int $userId): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM confirmacoes
            WHERE problema_id = :pid AND user_id = :uid
        ");
        $stmt->execute([':pid' => $problemaId, ':uid' => $userId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Adiciona a confirmação de um utilizador
     */
    public function adicionarConfirmacao(int $problemaId, int $userId): void
    {
        $stmt = $this->db->prepare("
            INSERT IGNORE INTO confirmacoes (problema_id, user_id, criado_em)
            VALUES (:pid, :uid, NOW())
        ");
        $stmt->execute([':pid' => $problemaId, ':uid' => $userId]);
    }

    /**
     * Remove a confirmação de um utilizador (toggle)
     */
    public function removerConfirmacao(int $problemaId, int $userId): void
    {
        $stmt = $this->db->prepare("
            DELETE FROM confirmacoes WHERE problema_id = :pid AND user_id = :uid
        ");
        $stmt->execute([':pid' => $problemaId, ':uid' => $userId]);
    }

    /**
     * Total de confirmações de um problema
     */
    public function totalConfirmacoes(int $problemaId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM confirmacoes WHERE problema_id = :pid"
        );
        $stmt->execute([':pid' => $problemaId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Distribuição de problemas por categoria com contagem
     */
    public function getDistribuicaoCategorias(): array
    {
        $stmt = $this->db->query("
            SELECT
                c.id, c.nome,
                COUNT(pr.id) AS total,
                SUM(CASE WHEN pr.estado_id = 1 THEN 1 ELSE 0 END) AS pendentes,
                SUM(CASE WHEN pr.estado_id = 3 THEN 1 ELSE 0 END) AS resolvidos
            FROM categorias c
            LEFT JOIN problemas pr ON pr.categoria_id = c.id
            GROUP BY c.id
            ORDER BY total DESC, c.nome ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Top municípios com mais ocorrências
     */
    public function getDistribuicaoMunicipios(int $limite = 6): array
    {
        $stmt = $this->db->prepare("
            SELECT
                m.id, m.nome AS municipio, p.nome AS provincia,
                COUNT(pr.id) AS total
            FROM municipios m
            JOIN bairros b ON b.municipio_id = m.id
            JOIN provincias p ON p.id = m.provincia_id
            LEFT JOIN problemas pr ON pr.bairro_id = b.id
            GROUP BY m.id
            HAVING total > 0
            ORDER BY total DESC
            LIMIT :limite
        ");
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Trilha de auditoria recente de despachos administrativos
     */
    public function getHistoricoAuditoriaRecente(int $limite = 6): array
    {
        $stmt = $this->db->prepare("
            SELECT
                h.id, h.problema_id, h.observacao, h.criado_em,
                u.nome    AS admin_nome,
                pr.titulo AS problema_titulo,
                ea.nome   AS estado_antigo_nome,
                ea.cor    AS estado_antigo_cor,
                en.nome   AS estado_novo_nome,
                en.cor    AS estado_novo_cor
            FROM historico_estados h
            JOIN user u ON u.id = h.admin_id
            JOIN problemas pr ON pr.id = h.problema_id
            LEFT JOIN estados_problema ea ON ea.id = h.estado_anterior_id
            JOIN estados_problema en ON en.id = h.estado_novo_id
            ORDER BY h.criado_em DESC
            LIMIT :limite
        ");
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retorna todos os problemas submetidos por um utilizador específico
     */
    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                pr.id, pr.titulo, pr.descricao, pr.foto, pr.referencia_local, pr.criado_em,
                pr.estado_id, pr.categoria_id,
                c.nome               AS categoria_nome,
                e.nome               AS estado_nome,
                e.cor                AS estado_cor,
                b.nome               AS bairro_nome,
                m.nome               AS municipio_nome,
                COUNT(DISTINCT cf.id) AS total_confirmacoes,
                COUNT(DISTINCT com.id) AS total_comentarios
            FROM problemas pr
            LEFT JOIN categorias       c   ON c.id   = pr.categoria_id
            LEFT JOIN estados_problema  e   ON e.id   = pr.estado_id
            LEFT JOIN bairros          b   ON b.id   = pr.bairro_id
            LEFT JOIN municipios       m   ON m.id   = b.municipio_id
            LEFT JOIN confirmacoes     cf  ON cf.problema_id = pr.id
            LEFT JOIN comentarios      com ON com.problema_id = pr.id
            WHERE pr.user_id = :user_id
            GROUP BY pr.id
            ORDER BY pr.criado_em DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Retorna as notificações de um utilizador
     */
    public function getNotificacoesUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT id, problema_id, mensagem, lida, criado_em
            FROM notificacoes
            WHERE user_id = :user_id
            ORDER BY criado_em DESC
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Marca uma notificação como lida
     */
    public function marcarNotificacaoLida(int $notifId, int $userId): void
    {
        $stmt = $this->db->prepare("
            UPDATE notificacoes SET lida = 1 WHERE id = :id AND user_id = :user_id
        ");
        $stmt->execute([':id' => $notifId, ':user_id' => $userId]);
    }

    /**
     * Cria uma nova notificação para um utilizador
     */
    public function criarNotificacao(int $userId, ?int $problemaId, string $mensagem): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO notificacoes (user_id, problema_id, mensagem, lida, criado_em)
            VALUES (:user_id, :problema_id, :mensagem, 0, NOW())
        ");
        $stmt->execute([
            ':user_id'     => $userId,
            ':problema_id' => $problemaId ?? 1, // Fallback se global
            ':mensagem'    => $mensagem,
        ]);
    }


    /**
     * Lista todos os comentários do sistema (para moderação do Admin)
     */
    public function listarTodosComentarios(): array
    {
        $stmt = $this->db->query("
            SELECT
                c.id, c.texto, c.criado_em, c.problema_id, c.user_id,
                u.nome  AS autor_nome,
                u.email AS autor_email,
                u.status AS autor_status,
                p.titulo AS problema_titulo
            FROM comentarios c
            JOIN user u ON u.id = c.user_id
            JOIN problemas p ON p.id = c.problema_id
            ORDER BY c.criado_em DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Elimina um comentário (moderação)
     */
    public function eliminarComentario(int $comentarioId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM comentarios WHERE id = :id");
        return $stmt->execute([':id' => $comentarioId]);
    }

    /**
     * Retorna ocorrências com coordenadas geográficas para o Mini-Mapa Visual Interativo
     */
    public function getPontosMapa(
        int $categoriaId = 0,
        int $estadoId = 0,
        int $provinciaId = 0,
        int $municipioId = 0
    ): array {
        $where = ['1 = 1'];
        $params = [];

        if ($categoriaId > 0) {
            $where[] = 'pr.categoria_id = :cat_id';
            $params[':cat_id'] = $categoriaId;
        }

        if ($estadoId > 0) {
            $where[] = 'pr.estado_id = :est_id';
            $params[':est_id'] = $estadoId;
        }

        if ($provinciaId > 0) {
            $where[] = 'p.id = :prov_id';
            $params[':prov_id'] = $provinciaId;
        }

        if ($municipioId > 0) {
            $where[] = 'b.municipio_id = :mun_id';
            $params[':mun_id'] = $municipioId;
        }

        $whereClause = implode(' AND ', $where);

        $sql = "
            SELECT
                pr.id,
                pr.titulo,
                pr.descricao,
                pr.foto,
                pr.referencia_local,
                pr.criado_em,
                pr.estado_id,
                pr.categoria_id,
                COALESCE(pr.latitude, b.latitude, p.latitude, -8.838333) AS latitude,
                COALESCE(pr.longitude, b.longitude, p.longitude, 13.234444) AS longitude,
                c.nome              AS categoria_nome,
                e.nome              AS estado_nome,
                e.cor               AS estado_cor,
                b.nome              AS bairro_nome,
                m.nome              AS municipio_nome,
                p.nome              AS provincia_nome,
                COUNT(DISTINCT cf.id) AS total_confirmacoes
            FROM problemas pr
            LEFT JOIN categorias      c  ON c.id  = pr.categoria_id
            LEFT JOIN estados_problema e ON e.id  = pr.estado_id
            LEFT JOIN bairros         b  ON b.id  = pr.bairro_id
            LEFT JOIN municipios      m  ON m.id  = b.municipio_id
            LEFT JOIN provincias      p  ON p.id  = m.provincia_id
            LEFT JOIN confirmacoes    cf ON cf.problema_id = pr.id
            WHERE {$whereClause}
            GROUP BY pr.id
            ORDER BY total_confirmacoes DESC, pr.criado_em DESC
        ";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_INT);
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }
}

