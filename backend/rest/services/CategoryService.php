<?php
require_once __DIR__ . '/BaseServices.php';
require_once __DIR__ . '/../dao/CategoryDao.php';

class CategoryService extends BaseService
{
    public function __construct($dao = null)
    {
        parent::__construct($dao ?: new CategoryDao());
        $this->entity_name = 'Category';
    }

    public function add($entity)
    {
        $this->validate($entity);
        $created = $this->dao->add($entity);
        $id = (int) ($created['category_id'] ?? $created['id']);
        return $this->dao->get_by_id($id);
    }

    public function get_by_name($name)
    {
        Validator::required(['name' => $name], ['name']);
        $category = $this->dao->get_by_name($name);
        if (!$category) {
            throw new NotFoundException('Category not found.');
        }
        return $category;
    }

    protected function validate($entity, $id = null)
    {
        if ($id === null) {
            Validator::required($entity, ['name']);
        }
        if (array_key_exists('name', $entity)) {
            Validator::length($entity['name'], 'name', 1, 50);
            Validator::no_html($entity['name'], 'name');
            $existing = $this->dao->get_by_name($entity['name']);
            if ($existing && (int) ($existing['category_id'] ?? 0) !== (int) $id) {
                throw new ValidationException("Category name '{$entity['name']}' already exists.");
            }
        }
        if (array_key_exists('description', $entity) && $entity['description'] !== null) {
            Validator::no_html($entity['description'], 'description');
        }
    }
}
