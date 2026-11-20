<?php
require_once __DIR__ . '/BaseServices.php';
require_once __DIR__ . '/../dao/ReviewDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/ItemDao.php';

class ReviewService extends BaseService
{
    protected $user_dao;
    protected $item_dao;

    public function __construct($dao = null, $user_dao = null, $item_dao = null)
    {
        parent::__construct($dao ?: new ReviewDao());
        $this->entity_name = 'Review';
        $this->user_dao = $user_dao ?: new UserDao();
        $this->item_dao = $item_dao ?: new ItemDao();
    }

    public function add($entity)
    {
        $this->validate($entity);
        $created = $this->dao->add($entity);
        $id = (int) ($created['review_id'] ?? $created['id']);
        return $this->dao->get_by_id($id);
    }

    public function get_by_item_id($item_id)
    {
        Validator::integer($item_id, 'item_id', 1);
        return $this->dao->get_by_item_id($item_id);
    }

    protected function validate($entity, $id = null)
    {
        if ($id === null) {
            Validator::required($entity, ['item_id', 'user_id', 'rating']);
        }
        if (array_key_exists('item_id', $entity)) {
            Validator::integer($entity['item_id'], 'item_id', 1);
            if (!$this->item_dao->get_by_id($entity['item_id'])) {
                throw new NotFoundException('Item not found.');
            }
        }
        if (array_key_exists('user_id', $entity)) {
            Validator::integer($entity['user_id'], 'user_id', 1);
            if (!$this->user_dao->get_by_id($entity['user_id'])) {
                throw new NotFoundException('User not found.');
            }
        }
        if (array_key_exists('rating', $entity)) {
            Validator::integer($entity['rating'], 'rating', 1, 5);
        }
        if (array_key_exists('comment', $entity) && $entity['comment'] !== null) {
            Validator::length($entity['comment'], 'comment', 0, 1000);
            Validator::no_html($entity['comment'], 'comment');
        }
    }
}
