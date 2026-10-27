<?php
require_once __DIR__ . '/EntityDao.php';

class CategoryDao extends EntityDao
{
    public function __construct()
    {
        parent::__construct('categories', 'category_id', ['name', 'description']);
    }

    public function get_all()
    {
        return $this->query(
            'SELECT c.*, (SELECT COUNT(*) FROM items i WHERE i.category_id = c.category_id) AS item_count
             FROM categories c
             ORDER BY c.name'
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
