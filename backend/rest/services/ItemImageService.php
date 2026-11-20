<?php
require_once __DIR__ . '/BaseServices.php';
require_once __DIR__ . '/../dao/ItemImageDao.php';
require_once __DIR__ . '/../dao/ItemDao.php';

class ItemImageService extends BaseService
{
    protected $item_dao;

    public function __construct($dao = null, $item_dao = null)
    {
        parent::__construct($dao ?: new ItemImageDao());
        $this->entity_name = 'Item image';
        $this->item_dao = $item_dao ?: new ItemDao();
    }

    public function add($entity)
    {
        $this->validate($entity);
        $created = $this->dao->add($entity);
        $id = (int) ($created['image_id'] ?? $created['id']);
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
            Validator::required($entity, ['item_id', 'image_url']);
        }
        if (array_key_exists('item_id', $entity)) {
            Validator::integer($entity['item_id'], 'item_id', 1);
            if (!$this->item_dao->get_by_id($entity['item_id'])) {
                throw new NotFoundException('Item not found.');
            }
        }
        if (array_key_exists('image_url', $entity)) {
            Validator::length($entity['image_url'], 'image_url', 1, 255);
        }
        if (array_key_exists('is_primary', $entity) &&
            !in_array($entity['is_primary'], [true, false, 0, 1, '0', '1'], true)) {
            throw new ValidationException("Field 'is_primary' must be boolean.");
        }
    }
}
