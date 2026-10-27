<?php
require_once __DIR__ . '/BaseDao.php';

class EntityDao extends BaseDao
{
    protected $primary_key;

    public function __construct($table_name, $primary_key, $columns)
    {
        parent::__construct($table_name, $columns);
        $this->primary_key = $primary_key;
    }

    public function get_all()
    {
        return $this->query(
            "SELECT * FROM {$this->table_name} ORDER BY {$this->primary_key}"
        );
    }

    public function get_by_id($id)
    {
        return $this->query_unique(
            "SELECT * FROM {$this->table_name} WHERE {$this->primary_key} = :id",
            ['id' => $id]
        );
    }

    public function add($entity)
    {
        $created = parent::add($entity);
        $id = $created['id'];
        unset($created['id']);
        $created[$this->primary_key] = $id;
        return $created;
    }

    public function update($entity, $id)
    {
        $entity = $this->only_columns($entity);
        if (count($entity) === 0) {
            return 0;
        }

        $set = [];
        foreach (array_keys($entity) as $column) {
            $set[] = "$column = :$column";
        }

        $entity['id'] = $id;
        return $this->execute(
            "UPDATE {$this->table_name} SET " . implode(', ', $set)
                . " WHERE {$this->primary_key} = :id",
            $entity
        );
    }

    public function delete($id)
    {
        return $this->execute(
            "DELETE FROM {$this->table_name} WHERE {$this->primary_key} = :id",
            ['id' => $id]
        );
    }
}
