<?php
require_once __DIR__ . '/UserService.php';
require_once __DIR__ . '/CategoryService.php';
require_once __DIR__ . '/ItemService.php';
require_once __DIR__ . '/ItemImageService.php';
require_once __DIR__ . '/OrderService.php';
require_once __DIR__ . '/ReviewService.php';

class ServiceTestDao
{
    private $primary_key;
    private $rows = [];
    private $next_id = 1;

    public function __construct($primary_key, $rows = [])
    {
        $this->primary_key = $primary_key;
        foreach ($rows as $row) {
            $this->rows[$row[$this->primary_key]] = $row;
            $this->next_id = max($this->next_id, (int) $row[$this->primary_key] + 1);
        }
    }

    public function get_all()
    {
        return array_values($this->rows);
    }

    public function get_by_id($id)
    {
        return $this->rows[$id] ?? null;
    }

    public function add($entity)
    {
        $entity[$this->primary_key] = $this->next_id++;
        $this->rows[$entity[$this->primary_key]] = $entity;
        return $entity;
    }

    public function update($entity, $id)
    {
        $this->rows[$id] = array_merge($this->rows[$id], $entity);
        return 1;
    }

    public function delete($id)
    {
        unset($this->rows[$id]);
        return 1;
    }

    public function get_by_email($email)
    {
        return $this->find_by('email', $email);
    }

    public function get_by_name($name)
    {
        return $this->find_by('name', $name);
    }

    public function get_by_seller_id($seller_id)
    {
        return $this->find_all_by('seller_id', $seller_id);
    }

    public function get_by_category_id($category_id)
    {
        return $this->find_all_by('category_id', $category_id);
    }

    public function get_by_item_id($item_id)
    {
        return $this->find_all_by('item_id', $item_id);
    }

    public function get_by_buyer_id($buyer_id)
    {
        return $this->find_all_by('buyer_id', $buyer_id);
    }

    private function find_by($field, $value)
    {
        foreach ($this->rows as $row) {
            if (($row[$field] ?? null) === $value) {
                return $row;
            }
        }
        return null;
    }

    private function find_all_by($field, $value)
    {
        return array_values(array_filter($this->rows, function ($row) use ($field, $value) {
            return (string) ($row[$field] ?? '') === (string) $value;
        }));
    }
}

$tests_run = 0;

function assert_service_test($condition, $message)
{
    global $tests_run;
    $tests_run++;
    if (!$condition) {
        throw new RuntimeException("Test failed: $message");
    }
}

function assert_service_throws($exception_class, $callback, $message)
{
    try {
        $callback();
    } catch (Exception $exception) {
        assert_service_test($exception instanceof $exception_class, $message);
        return;
    }
    assert_service_test(false, $message);
}

$users = new ServiceTestDao('user_id', [
    ['user_id' => 1, 'first_name' => 'Seller', 'last_name' => 'One', 'email' => 'seller@example.test', 'role' => 'seller'],
    ['user_id' => 2, 'first_name' => 'Buyer', 'last_name' => 'Two', 'email' => 'buyer@example.test', 'role' => 'user']
]);
$categories = new ServiceTestDao('category_id', [
    ['category_id' => 1, 'name' => 'Clothing']
]);
$items = new ServiceTestDao('item_id', [
    ['item_id' => 1, 'seller_id' => 1, 'category_id' => 1, 'name' => 'Used coat', 'price' => 20, 'status' => 'available']
]);

$user_storage = new ServiceTestDao('user_id');
$user_service = new UserService($user_storage);
$created_user = $user_service->add([
    'first_name' => 'New',
    'last_name' => 'User',
    'email' => 'new@example.test',
    'password' => 'password123'
]);
assert_service_test($created_user['user_id'] === 1, 'UserService returns the created user using its entity primary key.');
assert_service_test(!isset($created_user['password']), 'UserService omits the password from returned users.');
assert_service_test(
    password_verify('password123', $user_storage->get_by_id(1)['password']),
    'UserService hashes passwords before storing them.'
);
assert_service_throws(ValidationException::class, function () use ($user_service) {
    $user_service->add([
        'first_name' => 'Bad',
        'last_name' => 'Password',
        'email' => 'bad@example.test',
        'password' => 'short'
    ]);
}, 'UserService rejects weak passwords.');

$category_service = new CategoryService(new ServiceTestDao('category_id'));
$created_category = $category_service->add(['name' => 'Books']);
assert_service_test($created_category['category_id'] === 1, 'CategoryService returns the created category.');
$category_service_with_existing_name = new CategoryService($categories);
assert_service_throws(ValidationException::class, function () use ($category_service_with_existing_name) {
    $category_service_with_existing_name->add(['name' => 'Clothing']);
}, 'CategoryService rejects duplicate names.');

$item_service = new ItemService(
    new ServiceTestDao('item_id'),
    $users,
    $categories
);
$created_item = $item_service->add([
    'seller_id' => 1,
    'category_id' => 1,
    'name' => 'Vintage jacket',
    'description' => 'A used jacket.',
    'price' => 15.50
]);
assert_service_test($created_item['item_id'] === 1, 'ItemService validates relationships and returns the created item.');
assert_service_throws(ValidationException::class, function () use ($item_service) {
    $item_service->add([
        'seller_id' => 1,
        'category_id' => 1,
        'name' => 'Free jacket',
        'description' => 'A used jacket.',
        'price' => 0
    ]);
}, 'ItemService rejects non-positive prices.');

$image_service = new ItemImageService(new ServiceTestDao('image_id'), $items);
$created_image = $image_service->add([
    'item_id' => 1,
    'image_url' => 'images/item.jpg',
    'is_primary' => true
]);
assert_service_test($created_image['image_id'] === 1, 'ItemImageService validates and returns the created image.');
assert_service_throws(NotFoundException::class, function () use ($image_service) {
    $image_service->add(['item_id' => 99, 'image_url' => 'images/missing.jpg']);
}, 'ItemImageService rejects images for missing items.');

$order_service = new OrderService(
    new ServiceTestDao('order_id'),
    $users,
    $items
);
$created_order = $order_service->add([
    'buyer_id' => 2,
    'item_id' => 1,
    'total_price' => 20
]);
assert_service_test($created_order['order_id'] === 1, 'OrderService accepts an available item and returns the created order.');
$sold_items = new ServiceTestDao('item_id', [
    ['item_id' => 2, 'seller_id' => 1, 'status' => 'sold']
]);
$order_service_with_sold_item = new OrderService(new ServiceTestDao('order_id'), $users, $sold_items);
assert_service_throws(ValidationException::class, function () use ($order_service_with_sold_item) {
    $order_service_with_sold_item->add([
        'buyer_id' => 2,
        'item_id' => 2,
        'total_price' => 20
    ]);
}, 'OrderService rejects purchases of sold items.');

$review_service = new ReviewService(new ServiceTestDao('review_id'), $users, $items);
$created_review = $review_service->add([
    'item_id' => 1,
    'user_id' => 2,
    'rating' => 5,
    'comment' => 'Great condition.'
]);
assert_service_test($created_review['review_id'] === 1, 'ReviewService returns the created review.');
assert_service_throws(ValidationException::class, function () use ($review_service) {
    $review_service->add([
        'item_id' => 1,
        'user_id' => 2,
        'rating' => 6
    ]);
}, 'ReviewService rejects ratings outside the allowed range.');

echo "Passed $tests_run service tests." . PHP_EOL;
