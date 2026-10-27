<?php
require_once __DIR__ . '/EntityDao.php';

class ReviewDao extends EntityDao
{
    public function __construct()
    {
        parent::__construct(
            'reviews',
            'review_id',
            ['item_id', 'user_id', 'rating', 'comment']
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
