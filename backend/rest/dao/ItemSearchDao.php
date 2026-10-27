<?php
require_once __DIR__ . '/BaseDao.php';

class ItemSearchDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('items');
    }

    public function search($filters = [], $limit = 20, $offset = 0)
    {
        $conditions = [];
        $params = [];

        if (isset($filters['q']) && trim($filters['q']) !== '') {
            $term = addcslashes(trim($filters['q']), '\\%_');
            $conditions[] = '(i.name LIKE :name OR i.description LIKE :description)';
            $params['name'] = '%' . $term . '%';
            $params['description'] = '%' . $term . '%';
        }

        foreach (['category_id', 'seller_id', 'status'] as $field) {
            if (isset($filters[$field]) && $filters[$field] !== '') {
                $conditions[] = "i.{$field} = :{$field}";
                $params[$field] = $filters[$field];
            }
        }

        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $conditions[] = 'i.price >= :min_price';
            $params['min_price'] = $filters['min_price'];
        }

        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $conditions[] = 'i.price <= :max_price';
            $params['max_price'] = $filters['max_price'];
        }

        $where = count($conditions) > 0 ? ' WHERE ' . implode(' AND ', $conditions) : '';
        $limit = max(1, min(100, (int) $limit));
        $offset = max(0, (int) $offset);

        $query = "SELECT i.*, c.name AS category_name,
                         u.first_name AS seller_first_name, u.last_name AS seller_last_name,
                         (SELECT image_url FROM item_images ii
                          WHERE ii.item_id = i.item_id
                          ORDER BY ii.is_primary DESC, ii.image_id
                          LIMIT 1) AS primary_image
                  FROM items i
                  LEFT JOIN categories c ON c.category_id = i.category_id
                  LEFT JOIN users u ON u.user_id = i.seller_id"
                  . $where
                  . ' ORDER BY i.created_at DESC, i.item_id DESC
                      LIMIT :limit OFFSET :offset';

        $stmt = self::connection()->prepare($query);
        foreach ($params as $name => $value) {
            $stmt->bindValue(':' . $name, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
