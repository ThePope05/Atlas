<?php

namespace Libraries\Classes\Database;

use PDO;
use RuntimeException;

class QueryBuilder
{
    protected PDO $pdo;

    protected string $table;
    protected array $columns = ['*'];
    protected array $wheres = [];
    protected array $bindings = [];
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected array $orders = [];
    protected array $joins = [];

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function select(array|string $columns): self
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function where(
        string $column,
        string|int|float|null $operator,
        string|int|float|null $value = null
    ): self {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $param = ':w' . count($this->bindings);

        $this->wheres[] = "{$column} {$operator} {$param}";
        $this->bindings[$param] = $value;

        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orders[] = "{$column} " . strtoupper($direction);
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function get(): array
    {
        $stmt = $this->pdo->prepare($this->toSql());
        $stmt->execute($this->bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first(): ?array
    {
        $this->limit(1);
        $results = $this->get();
        return $results[0] ?? null;
    }

    public function count(): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->table;

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);

        return (int) $stmt->fetchColumn();
    }

    public function join(
        string $table,
        string $first,
        string $operator,
        string $second,
        string $type = 'INNER'
    ): self {
        $this->joins[] = sprintf(
            '%s JOIN %s ON %s %s %s',
            strtoupper($type),
            $table,
            $first,
            $operator,
            $second
        );

        return $this;
    }

    public function leftJoin(
        string $table,
        string $first,
        string $operator,
        string $second
    ): self {
        return $this->join($table, $first, $operator, $second, 'LEFT');
    }

    public function rightJoin(
        string $table,
        string $first,
        string $operator,
        string $second
    ): self {
        return $this->join($table, $first, $operator, $second, 'RIGHT');
    }

    public function insert(array $data): int
    {
        $columns = array_keys($data);
        $params  = [];

        foreach ($data as $key => $value) {
            $param = ':' . $key;
            $params[$param] = $value;
        }

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            implode(', ', $columns),
            implode(', ', array_keys($params))
        );

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(array $data): int
    {
        if (!$this->wheres) {
            throw new RuntimeException('Update without WHERE is not allowed.');
        }

        $sets = [];
        $bindings = $this->bindings;

        foreach ($data as $column => $value) {
            $param = ':u_' . $column;
            $sets[] = "{$column} = {$param}";
            $bindings[$param] = $value;
        }

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $this->table,
            implode(', ', $sets),
            implode(' AND ', $this->wheres)
        );

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);

        return $stmt->rowCount();
    }

    public function delete(): int
    {
        if (!$this->wheres) {
            throw new RuntimeException('Delete without WHERE is not allowed.');
        }

        $sql = sprintf(
            'DELETE FROM %s WHERE %s',
            $this->table,
            implode(' AND ', $this->wheres)
        );

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);

        return $stmt->rowCount();
    }

    protected function toSql(): string
    {
        $sql = 'SELECT ' . implode(', ', $this->columns)
            . ' FROM ' . $this->table;

        if ($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        if ($this->orders) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orders);
        }

        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        if ($this->offset !== null) {
            $sql .= ' OFFSET ' . $this->offset;
        }

        return $sql;
    }
}
