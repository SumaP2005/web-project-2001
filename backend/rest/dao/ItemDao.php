<?php
require_once __DIR__ . '/BaseDao.php';

class ItemDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct(
            'items',
            ['seller_id', 'category_id', 'name', 'description', 'price', 'rating', 'status']
        );
    }

    public function get_all()
    {
        return $this->query('SELECT * FROM items ORDER BY item_id');
    }

    public function get_by_id($id)
    {
        return $this->query_unique(
            'SELECT * FROM items WHERE item_id = :id',
            ['id' => $id]
        );
    }

    public function add($entity)
    {
        $created = parent::add($entity);
        $id = $created['id'];
        unset($created['id']);
        $created['item_id'] = $id;
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
            'UPDATE items SET ' . implode(', ', $set) . ' WHERE item_id = :id',
            $entity
        );
    }

    public function delete($id)
    {
        return $this->execute(
            'DELETE FROM items WHERE item_id = :id',
            ['id' => $id]
        );
    }

    public function get_by_seller_id($seller_id)
    {
        return $this->query(
            'SELECT * FROM items WHERE seller_id = :seller_id ORDER BY item_id',
            ['seller_id' => $seller_id]
        );
    }

    public function get_by_category_id($category_id)
    {
        return $this->query(
            'SELECT * FROM items WHERE category_id = :category_id ORDER BY item_id',
            ['category_id' => $category_id]
        );
    }
}
