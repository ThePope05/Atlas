<?php

namespace Libraries\Classes\Mvc;

use Libraries\Classes\Database;

abstract class Model
{
    protected array $fillable = [];
    protected string $table = '';
    protected Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    protected function resolveFields(array $fields = []): array
    {
        if (empty($fields))
            return $this->fillable;

        return array_values(array_intersect($fields, $this->fillable));
    }

    protected function all(): array|null
    {
        return $this->get();
    }

    protected function get(array $fields = []): array|null
    {
        $fields = $this->resolveFields($fields);

        if (empty($fields))
            return null;

        $columns = implode(', ', $fields);

        $this->db->query("SELECT $columns FROM `$this->table`");

        return $this->db->execute(true);
    }

    protected function insert(array $data): void
    {
        if (empty($data))
            return;

        $data = array_intersect_key($data, array_flip($this->fillable));

        if (empty($data))
            return;

        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));

        $this->db->query("INSERT INTO `$this->table` ($columns) VALUES ($values)");

        foreach ($data as $key => $value) {
            $this->db->bind(":$key", $value);
        }

        $this->db->execute();
    }

    protected function delete(string|int $id): void
    {
        if (empty($id))
            return;

        $this->db->query("DELETE FROM `$this->table` WHERE id = :id");

        $this->db->bind(':id', $id);

        $this->db->execute();
    }

    protected function update(array $data, array $fields = []): void
    {
        if (empty($data))
            return;

        if (!isset($data['id']))
            throw new \InvalidArgumentException('Update requires an id');

        $id = $data['id'];
        unset($data['id']);

        $allowedFields = $this->resolveFields($fields);

        $data = array_intersect_key($data, array_flip($allowedFields));

        if (empty($data))
            return;

        $set = implode(', ', array_map(
            fn($key) => "$key = :$key",
            array_keys($data)
        ));

        $this->db->query("UPDATE `$this->table` SET $set WHERE id = :id");

        $this->db->bind(':id', $id);

        foreach ($data as $key => $value) {
            $this->db->bind(":$key", $value);
        }
        $this->db->execute();
    }
}
