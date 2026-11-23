<?php
require_once __DIR__ . '/BaseServices.php';
require_once __DIR__ . '/../dao/OrderDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/ItemDao.php';

class OrderService extends BaseService
{
    protected $buyer_dao;
    protected $item_dao;

    public function __construct($dao = null, $buyer_dao = null, $item_dao = null)
    {
        parent::__construct($dao ?: new OrderDao());
        $this->entity_name = 'Order';
        $this->buyer_dao = $buyer_dao ?: new UserDao();
        $this->item_dao = $item_dao ?: new ItemDao();
    }

    public function add($entity)
    {
        $this->validate($entity);
        $created = $this->dao->add($entity);
        $id = (int) ($created['order_id'] ?? $created['id']);
        return $this->dao->get_by_id($id);
    }

    public function get_by_buyer_id($buyer_id)
    {
        Validator::integer($buyer_id, 'buyer_id', 1);
        return $this->dao->get_by_buyer_id($buyer_id);
    }

    protected function validate($entity, $id = null)
    {
        if ($id === null) {
            Validator::required($entity, ['buyer_id', 'item_id', 'total_price']);
        }
        if (array_key_exists('buyer_id', $entity)) {
            Validator::integer($entity['buyer_id'], 'buyer_id', 1);
            if (!$this->buyer_dao->get_by_id($entity['buyer_id'])) {
                throw new NotFoundException('Buyer not found.');
            }
        }
        if (array_key_exists('item_id', $entity)) {
            Validator::integer($entity['item_id'], 'item_id', 1);
            $item = $this->item_dao->get_by_id($entity['item_id']);
            if (!$item) {
                throw new NotFoundException('Item not found.');
            }
            if ($item['status'] !== 'available') {
                throw new ValidationException('The item is not available for purchase.');
            }
            if (array_key_exists('buyer_id', $entity) &&
                (int) $item['seller_id'] === (int) $entity['buyer_id']) {
                throw new ValidationException('A seller cannot purchase their own item.');
            }
        }
        if (array_key_exists('total_price', $entity) &&
            (!is_numeric($entity['total_price']) || (float) $entity['total_price'] <= 0)) {
            throw new ValidationException("Field 'total_price' must be a positive number.");
        }
    }
}
