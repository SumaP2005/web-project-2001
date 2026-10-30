<?php
require_once __DIR__ . '/BaseDao.php';

class CategoryDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('categories', ['name', 'description']);
    }

    public function get_all()
    {
        return $this->query(
            'SELECT c.*, (SELECT COUNT(*) FROM items i WHERE i.category_id = c.category_id) AS item_count
             FROM categories c
             ORDER BY c.name'
        );
    }

    public function get_by_id($id)
    {
        return $this->query_unique(
            'SELECT * FROM categories WHERE category_id = :id',
            ['id' => $id]
        );
    }

    public function add($entity)
    {
        $created = parent::add($entity);
        $id = $created['id'];
        unset($created['id']);
        $created['category_id'] = $id;
        return $created;
    }

    public function update($entity, $id)
    {
        $entity = $this->only_columns($entity);
        $set = [];
        foreach (array_keys($entity) as $column) {
            $set[] = "$column = :$column";
        }

        $entity['id'] = $id;
        return $this->execute(
            'UPDATE categories SET ' . implode(', ', $set) . ' WHERE category_id = :id',
            $entity
        );
    }

    public function delete($id)
    {
        return $this->execute(
            'DELETE FROM categories WHERE category_id = :id',
            ['id' => $id]
        );
    }

    public function get_by_name($name)
    {
        return $this->query_unique(
            'SELECT * FROM categories WHERE name = :name',
            ['name' => $name]
        );
    }
}
