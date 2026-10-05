<?php
/**
 * Djumbai — CategoriaModel
 * Operações sobre a tabela `categorias`
 */

class CategoriaModel extends Model
{
    protected string $table = 'categorias';

    /**
     * Retorna todas as categorias com a contagem de problemas associados
     */
    public function findAllComContagem(): array
    {
        $stmt = $this->db->query("
            SELECT
                c.id, c.nome,
                COUNT(p.id) AS total_problemas
            FROM categorias c
            LEFT JOIN problemas p ON p.categoria_id = c.id
            GROUP BY c.id
            ORDER BY c.nome ASC
        ");
        return $stmt->fetchAll();
    }
}
