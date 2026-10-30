<?php
require_once __DIR__ . '/BaseDao.php';

class OrderDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct(
            'orders',
            ['buyer_id', 'item_id', 'total_price']
        );
    }

    public function get_all()
    {
        return $this->query('SELECT * FROM orders ORDER BY order_id');
    }

    public function get_by_id($id)
    {
        return $this->query_unique(
            'SELECT * FROM orders WHERE order_id = :id',
            ['id' => $id]
        );
    }

    public function add($entity)
    {
        $created = parent::add($entity);
        $id = $created['id'];
        unset($created['id']);
        $created['order_id'] = $id;
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
            'UPDATE orders SET ' . implode(', ', $set) . ' WHERE order_id = :id',
            $entity
        );
    }

    public function delete($id)
    {
        return $this->execute(
            'DELETE FROM orders WHERE order_id = :id',
            ['id' => $id]
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
