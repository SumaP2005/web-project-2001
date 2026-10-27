<?php
require_once __DIR__ . '/EntityDao.php';

class ItemImageDao extends EntityDao
{
    public function __construct()
    {
        parent::__construct(
            'item_images',
            'image_id',
            ['item_id', 'image_url', 'is_primary']
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
