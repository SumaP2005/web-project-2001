<?php
require_once __DIR__ . '/EntityDao.php';

class ItemDao extends EntityDao
{
    public function __construct()
    {
        parent::__construct(
            'items',
            'item_id',
            ['seller_id', 'category_id', 'name', 'description', 'price', 'rating', 'status']
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
