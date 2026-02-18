<?php

namespace Libraries\Classes\Database;

use InvalidArgumentException;
use PDO;
use RuntimeException;

class SchemaBuilder
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(string $table, array $columns): void
    {
        $sql = $this->compileCreateTable($table, $columns);
        $this->pdo->exec($sql);
    }

    protected function compileCreateTable(string $table, array $columns): string
    {
        $defs = [];
        $primary = [];
        $unique = [];
        $foreignKeys = [];

        foreach ($columns as $name => $col) {
            if (in_array('auto_increment', $col) && !in_array('primary', $col)) {
                throw new RuntimeException("AUTO_INCREMENT requires PRIMARY KEY");
            }

            $defs[] = $this->compileColumn($name, $col);

            if (in_array('primary', $col)) {
                $primary[] = $name;
            }

            if (in_array('unique', $col)) {
                $unique[] = "UNIQUE (`{$name}`)";
            }

            if (in_array('foreign', $col)) {
                $foreignKeys[] = $this->compileForeignKey($table, $name, $col['foreign']);
            }
        }

        if ($primary) {
            $defs[] = 'PRIMARY KEY (' . implode(', ', $primary) . ')';
        }

        $defs = array_merge($defs, $unique, $foreignKeys);

        return sprintf(
            "CREATE TABLE `%s` (\n  %s\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
            $table,
            implode(",\n  ", $defs)
        );
    }

    protected function compileColumn(string $name, array $col): string
    {
        if (!array_key_exists('type', $col)) {
            throw new InvalidArgumentException("Column {$name} has no type");
        }

        $sql = "`{$name}` " . $this->compileType($col);

        if (in_array('unsigned', $col)) {
            $sql .= ' UNSIGNED';
        }

        $sql .= (in_array('not_null', $col) || in_array('primary', $col)) ? ' NOT NULL' : 'NULL';

        if (in_array('default', $col)) {
            $sql .= ' DEFAULT ' . $this->compileDefault($col['default']);
        }

        if (in_array('auto_increment', $col)) {
            $sql .= ' AUTO_INCREMENT';
        }

        return $sql;
    }

    protected function compileType(array $col): string
    {
        return match ($col['type']) {
            'int'      => 'INT',
            'bigint'   => 'BIGINT',
            'bool'     => 'TINYINT(1)',
            'varchar'  => 'VARCHAR(' . ($col['length'] ?? 255) . ')',
            'text'     => 'TEXT',
            'datetime' => 'DATETIME',
            'date'     => 'DATE',
            'float'    => 'FLOAT',
            'decimal'  => 'DECIMAL(' . ($col['precision'] ?? '10,2') . ')',
            default    => throw new InvalidArgumentException("Unknown type {$col['type']}"),
        };
    }

    protected function compileDefault($value): string
    {
        if (is_string($value) && strtoupper($value) === 'CURRENT_TIMESTAMP') {
            return 'CURRENT_TIMESTAMP';
        }

        if (is_string($value)) {
            return $this->pdo->quote($value);
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if ($value === null) {
            return 'NULL';
        }

        return (string) $value;
    }

    public function addColumn(
        string $table,
        string $name,
        array $definition,
        ?string $after = null
    ): void {
        $sql = sprintf(
            "ALTER TABLE `%s` ADD COLUMN %s",
            $table,
            $this->compileColumn($name, $definition)
        );

        if ($after) {
            $sql .= " AFTER `{$after}`";
        }

        $this->pdo->exec($sql);
    }

    public function modifyColumn(
        string $table,
        string $name,
        array $definition,
        ?string $after = null
    ): void {
        $sql = sprintf(
            "ALTER TABLE `%s` MODIFY COLUMN %s",
            $table,
            $this->compileColumn($name, $definition)
        );

        if ($after) {
            $sql .= " AFTER `{$after}`";
        }

        $this->pdo->exec($sql);
    }

    public function drop(string $table): void
    {
        $this->pdo->exec("DROP TABLE IF EXISTS `{$table}`");
    }

    protected function compileForeignKey(string $table, string $column, array $fk): string
    {
        foreach (['table', 'column'] as $required) {
            if (empty($fk[$required])) {
                throw new InvalidArgumentException(
                    "Foreign key on {$table}.{$column} missing {$required}"
                );
            }
        }

        $sql = sprintf(
            "CONSTRAINT `fk_%s_%s` FOREIGN KEY (`%s`) REFERENCES `%s`(`%s`)",
            $table,
            $column,
            $column,
            $fk['table'],
            $fk['column']
        );

        if (array_key_exists('on_delete', $fk)) {
            $sql .= ' ON DELETE ' . strtoupper($fk['on_delete']);
        }

        if (array_key_exists('on_update', $fk)) {
            $sql .= ' ON UPDATE ' . strtoupper($fk['on_update']);
        }

        return $sql;
    }
}
