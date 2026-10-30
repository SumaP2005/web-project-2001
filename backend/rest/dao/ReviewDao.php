<?php
require_once __DIR__ . '/BaseDao.php';

class ReviewDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct(
            'reviews',
            ['item_id', 'user_id', 'rating', 'comment']
        );
    }

    public function get_all()
    {
        return $this->query('SELECT * FROM reviews ORDER BY review_id');
    }

    public function get_by_id($id)
    {
        return $this->query_unique(
            'SELECT * FROM reviews WHERE review_id = :id',
            ['id' => $id]
        );
    }

    public function add($entity)
    {
        $created = parent::add($entity);
        $id = $created['id'];
        unset($created['id']);
        $created['review_id'] = $id;
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
            'UPDATE reviews SET ' . implode(', ', $set) . ' WHERE review_id = :id',
            $entity
        );
    }

    public function delete($id)
    {
        return $this->execute(
            'DELETE FROM reviews WHERE review_id = :id',
            ['id' => $id]
        );
    }

    public function get_by_item_id($item_id)
    {
        return $this->query(
            'SELECT * FROM reviews WHERE item_id = :item_id ORDER BY date DESC, review_id DESC',
            ['item_id' => $item_id]
        );
    }
}
