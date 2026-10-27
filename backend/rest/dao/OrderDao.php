<?php
require_once __DIR__ . '/EntityDao.php';

class OrderDao extends EntityDao
{
    public function __construct()
    {
        parent::__construct(
            'orders',
            'order_id',
            ['buyer_id', 'item_id', 'total_price']
        );
    }

    public function get_by_buyer_id($buyer_id)
    {
        return $this->query(
            'SELECT * FROM orders WHERE buyer_id = :buyer_id ORDER BY order_date DESC, order_id DESC',
            ['buyer_id' => $buyer_id]
        );
    }
}
