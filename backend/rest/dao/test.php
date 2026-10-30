<?php
require_once __DIR__ . '/UserDao.php';
require_once __DIR__ . '/CategoryDao.php';
require_once __DIR__ . '/ItemDao.php';
require_once __DIR__ . '/ItemImageDao.php';
require_once __DIR__ . '/OrderDao.php';
require_once __DIR__ . '/ReviewDao.php';

function print_dao_output($label, $output)
{
    echo PHP_EOL . $label . PHP_EOL;
    print_r(redact_passwords($output));
    echo PHP_EOL;
}

function redact_passwords($output)
{
    if (!is_array($output)) {
        return $output;
    }

    foreach ($output as $key => $value) {
        $output[$key] = $key === 'password' ? '[redacted]' : redact_passwords($value);
    }

    return $output;
}

$user_dao = new UserDao();
$category_dao = new CategoryDao();
$item_dao = new ItemDao();
$image_dao = new ItemImageDao();
$order_dao = new OrderDao();
$review_dao = new ReviewDao();

$ids = [
    'user' => null,
    'category' => null,
    'item' => null,
    'image' => null,
    'order' => null,
    'review' => null
];
$marker = uniqid('dao-output-', true);

try {
    $user = $user_dao->add([
        'first_name' => 'DAO',
        'last_name' => 'Output',
        'email' => $marker . '@example.test',
        'password' => 'dao-output-placeholder',
        'role' => 'user'
    ]);
    $ids['user'] = (int) $user['user_id'];
    print_dao_output('UserDao::add', $user);
    print_dao_output('UserDao::get_all', $user_dao->get_all());
    print_dao_output('UserDao::get_by_id', $user_dao->get_by_id($ids['user']));
    print_dao_output('UserDao::get_by_email', $user_dao->get_by_email($user['email']));
    print_dao_output(
        'UserDao::update',
        $user_dao->update(['first_name' => 'DAO Updated'], $ids['user'])
    );

    $category = $category_dao->add([
        'name' => 'DAO Output ' . $marker,
        'description' => 'Created by the DAO output example'
    ]);
    $ids['category'] = (int) $category['category_id'];
    print_dao_output('CategoryDao::add', $category);
    print_dao_output('CategoryDao::get_all', $category_dao->get_all());
    print_dao_output('CategoryDao::get_by_id', $category_dao->get_by_id($ids['category']));
    print_dao_output('CategoryDao::get_by_name', $category_dao->get_by_name($category['name']));
    print_dao_output(
        'CategoryDao::update',
        $category_dao->update(['description' => 'Updated by the DAO output example'], $ids['category'])
    );

    $item = $item_dao->add([
        'seller_id' => $ids['user'],
        'category_id' => $ids['category'],
        'name' => 'DAO Output Item',
        'description' => 'Created by the DAO output example',
        'price' => 12.50,
        'rating' => 0,
        'status' => 'available'
    ]);
    $ids['item'] = (int) $item['item_id'];
    print_dao_output('ItemDao::add', $item);
    print_dao_output('ItemDao::get_all', $item_dao->get_all());
    print_dao_output('ItemDao::get_by_id', $item_dao->get_by_id($ids['item']));
    print_dao_output('ItemDao::get_by_seller_id', $item_dao->get_by_seller_id($ids['user']));
    print_dao_output('ItemDao::get_by_category_id', $item_dao->get_by_category_id($ids['category']));
    print_dao_output(
        'ItemDao::update',
        $item_dao->update(['description' => 'Updated by the DAO output example'], $ids['item'])
    );

    $image = $image_dao->add([
        'item_id' => $ids['item'],
        'image_url' => 'dao-output-image.jpg',
        'is_primary' => false
    ]);
    $ids['image'] = (int) $image['image_id'];
    print_dao_output('ItemImageDao::add', $image);
    print_dao_output('ItemImageDao::get_all', $image_dao->get_all());
    print_dao_output('ItemImageDao::get_by_id', $image_dao->get_by_id($ids['image']));
    print_dao_output('ItemImageDao::get_by_item_id', $image_dao->get_by_item_id($ids['item']));
    print_dao_output(
        'ItemImageDao::update',
        $image_dao->update(['image_url' => 'dao-output-image-updated.jpg'], $ids['image'])
    );

    $order = $order_dao->add([
        'buyer_id' => $ids['user'],
        'item_id' => $ids['item'],
        'total_price' => 12.50
    ]);
    $ids['order'] = (int) $order['order_id'];
    print_dao_output('OrderDao::add', $order);
    print_dao_output('OrderDao::get_all', $order_dao->get_all());
    print_dao_output('OrderDao::get_by_id', $order_dao->get_by_id($ids['order']));
    print_dao_output('OrderDao::get_by_buyer_id', $order_dao->get_by_buyer_id($ids['user']));
    print_dao_output(
        'OrderDao::update',
        $order_dao->update(['total_price' => 18.75], $ids['order'])
    );

    $review = $review_dao->add([
        'item_id' => $ids['item'],
        'user_id' => $ids['user'],
        'rating' => 5,
        'comment' => 'Created by the DAO output example'
    ]);
    $ids['review'] = (int) $review['review_id'];
    print_dao_output('ReviewDao::add', $review);
    print_dao_output('ReviewDao::get_all', $review_dao->get_all());
    print_dao_output('ReviewDao::get_by_id', $review_dao->get_by_id($ids['review']));
    print_dao_output('ReviewDao::get_by_item_id', $review_dao->get_by_item_id($ids['item']));
    print_dao_output(
        'ReviewDao::update',
        $review_dao->update(['comment' => 'Updated by the DAO output example'], $ids['review'])
    );

    print_dao_output('ReviewDao::delete', $review_dao->delete($ids['review']));
    $ids['review'] = null;
    print_dao_output('OrderDao::delete', $order_dao->delete($ids['order']));
    $ids['order'] = null;
    print_dao_output('ItemImageDao::delete', $image_dao->delete($ids['image']));
    $ids['image'] = null;
    print_dao_output('ItemDao::delete', $item_dao->delete($ids['item']));
    $ids['item'] = null;
    print_dao_output('CategoryDao::delete', $category_dao->delete($ids['category']));
    $ids['category'] = null;
    print_dao_output('UserDao::delete', $user_dao->delete($ids['user']));
    $ids['user'] = null;
} finally {
    foreach ([
        ['dao' => $review_dao, 'key' => 'review'],
        ['dao' => $order_dao, 'key' => 'order'],
        ['dao' => $image_dao, 'key' => 'image'],
        ['dao' => $item_dao, 'key' => 'item'],
        ['dao' => $category_dao, 'key' => 'category'],
        ['dao' => $user_dao, 'key' => 'user']
    ] as $cleanup) {
        if ($ids[$cleanup['key']] !== null) {
            print_dao_output(
                get_class($cleanup['dao']) . '::delete (cleanup)',
                $cleanup['dao']->delete($ids[$cleanup['key']])
            );
        }
    }
}
