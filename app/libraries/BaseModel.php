<?php

class BaseModel
{
    protected $db;
    protected $table;
    protected $fillable = [];

    public function __construct()
    {
        $this->db = new Database();
    }

    protected function store(array $values): void
    {
        $fields = implode(', ', $this->fillable);
        $values = implode(', ', $values);
        $sql = "INSERT INTO " . $this->table . " ($fields) VALUES ($values)";
        $this->db->query($sql);
        $this->db->execute();
    }

    protected function get(array $fields = [], array $where = []): array
    {
        $fields = empty($fields) ? '*' : implode(', ', $fields);
        $where = empty($where) ? '' : ' WHERE ' . implode(' AND ', $where);

        $sql = "SELECT $fields FROM " . $this->table . $where;
        $this->db->query($sql);
        return $this->db->execute(true);
    }

    protected function update(array $fields, array $where): void
    {
        $fields = implode(', ', $fields);
        $where = implode(' AND ', $where);
        $sql = "UPDATE " . $this->table . " SET $fields WHERE $where";
        $this->db->query($sql);
        $this->db->execute();
    }

    protected function delete(array $where): void
    {
        $where = implode(' AND ', $where);
        $sql = "DELETE FROM " . $this->table . " WHERE $where";
        $this->db->query($sql);
        $this->db->execute();
    }
}
