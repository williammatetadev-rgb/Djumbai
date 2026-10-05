<?php
/**
 * Djumbai — Classe Base Model Blindada contra SQL Injection
 */

abstract class Model
{
    protected PDO $db;
    protected string $table = '';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Encontra um registo pelo seu ID primário
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table}` WHERE id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * Retorna todos os registos da tabela com ordenação validada
     */
    public function findAll(string $orderBy = 'id ASC'): array
    {
        // Sanitiza e valida a cláusula de ordenação para impedir SQL Injection
        if (!preg_match('/^[a-zA-Z0-9_,\s\.\(\)]+$/', $orderBy)) {
            $orderBy = 'id ASC';
        }

        $stmt = $this->db->query("SELECT * FROM `{$this->table}` ORDER BY {$orderBy}");
        return $stmt->fetchAll();
    }

    /**
     * Insere um novo registo na tabela com validação rigorosa de colunas
     */
    public function create(array $data): int
    {
        $safeColumns = [];
        $placeholders = [];
        $params = [];

        foreach ($data as $col => $val) {
            // Garante que o nome da coluna contenha apenas caracteres alfanuméricos e underscore
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $col)) {
                throw new InvalidArgumentException("Nome de coluna inválido: {$col}");
            }

            $safeColumns[]  = "`{$col}`";
            $placeholders[] = ":{$col}";
            $params[":{$col}"] = $val;
        }

        $columnsClause      = implode(', ', $safeColumns);
        $placeholdersClause = implode(', ', $placeholders);

        $sql = "INSERT INTO `{$this->table}` ({$columnsClause}) VALUES ({$placeholdersClause})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Actualiza um registo existente pelo ID com validação de colunas
     */
    public function update(int $id, array $data): bool
    {
        $setParts = [];
        $params   = [':id' => $id];

        foreach ($data as $col => $val) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $col)) {
                throw new InvalidArgumentException("Nome de coluna inválido: {$col}");
            }

            $setParts[] = "`{$col}` = :set_{$col}";
            $params[":set_{$col}"] = $val;
        }

        $setClause = implode(', ', $setParts);
        $sql = "UPDATE `{$this->table}` SET {$setClause} WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

    /**
     * Elimina um registo pelo ID
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM `{$this->table}` WHERE id = :id"
        );
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Conta o número total de registos
     */
    public function count(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM `{$this->table}`");
        return (int) $stmt->fetchColumn();
    }
}
