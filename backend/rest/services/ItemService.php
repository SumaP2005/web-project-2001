<?php
require_once __DIR__ . '/BaseServices.php';
require_once __DIR__ . '/../dao/ItemDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/CategoryDao.php';
require_once __DIR__ . '/../../utils/Roles.php';

class ItemService extends BaseService
{
    protected $user_dao;
    protected $category_dao;

    public function __construct($dao = null, $user_dao = null, $category_dao = null)
    {
        parent::__construct($dao ?: new ItemDao());
        $this->entity_name = 'Item';
        $this->user_dao = $user_dao ?: new UserDao();
        $this->category_dao = $category_dao ?: new CategoryDao();
    }

    public function add($entity)
    {
        $this->validate($entity);
        $created = $this->dao->add($entity);
        $id = (int) ($created['item_id'] ?? $created['id']);
        return $this->dao->get_by_id($id);
    }

    public function get_by_seller_id($seller_id)
    {
        Validator::integer($seller_id, 'seller_id', 1);
        return $this->dao->get_by_seller_id($seller_id);
    }

    public function get_by_category_id($category_id)
    {
        Validator::integer($category_id, 'category_id', 1);
        return $this->dao->get_by_category_id($category_id);
    }

    protected function validate($entity, $id = null)
    {
        if ($id === null) {
            Validator::required($entity, ['seller_id', 'category_id', 'name', 'description', 'price']);
        }
        if (array_key_exists('seller_id', $entity)) {
            Validator::integer($entity['seller_id'], 'seller_id', 1);
            $seller = $this->user_dao->get_by_id($entity['seller_id']);
            if (!$seller) {
                throw new NotFoundException('Seller not found.');
            }
            if (!in_array($seller['role'], [Roles::SELLER, Roles::ADMIN], true)) {
                throw new ValidationException('seller_id must belong to a seller or admin.');
            }
        }
        if (array_key_exists('category_id', $entity)) {
            Validator::integer($entity['category_id'], 'category_id', 1);
            if (!$this->category_dao->get_by_id($entity['category_id'])) {
                throw new NotFoundException('Category not found.');
            }
        }
        if (array_key_exists('name', $entity)) {
            Validator::length($entity['name'], 'name', 1, 100);
            Validator::no_html($entity['name'], 'name');
        }
        if (array_key_exists('description', $entity)) {
            Validator::required(['description' => $entity['description']], ['description']);
            Validator::no_html($entity['description'], 'description');
        }
        if (array_key_exists('price', $entity)) {
            if (!is_numeric($entity['price']) || (float) $entity['price'] <= 0) {
                throw new ValidationException("Field 'price' must be a positive number.");
            }
        }
        if (array_key_exists('rating', $entity)) {
            if (!is_numeric($entity['rating']) || (float) $entity['rating'] < 0 || (float) $entity['rating'] > 5) {
                throw new ValidationException("Field 'rating' must be between 0 and 5.");
            }
        }
        if (array_key_exists('status', $entity)) {
            Validator::one_of($entity['status'], 'status', ['available', 'sold']);
        }
    }
}
