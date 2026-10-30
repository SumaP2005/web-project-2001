<?php
require_once __DIR__ . '/BaseDao.php';

class ItemImageDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct(
            'item_images',
            ['item_id', 'image_url', 'is_primary']
        );
    }

    public function get_all()
    {
        return $this->query('SELECT * FROM item_images ORDER BY image_id');
    }

    public function get_by_id($id)
    {
        return $this->query_unique(
            'SELECT * FROM item_images WHERE image_id = :id',
            ['id' => $id]
        );
    }

    public function add($entity)
    {
        $created = parent::add($entity);
        $id = $created['id'];
        unset($created['id']);
        $created['image_id'] = $id;
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
            'UPDATE item_images SET ' . implode(', ', $set) . ' WHERE image_id = :id',
            $entity
        );
    }

    public function delete($id)
    {
        return $this->execute(
            'DELETE FROM item_images WHERE image_id = :id',
            ['id' => $id]
        );
    }

    public function get_by_item_id($item_id)
    {
        return $this->query(
            'SELECT * FROM item_images WHERE item_id = :item_id ORDER BY is_primary DESC, image_id',
            ['item_id' => $item_id]
        );
    }
}
