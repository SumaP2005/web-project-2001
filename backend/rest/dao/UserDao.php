<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct(
            'users',
            ['first_name', 'last_name', 'email', 'password', 'role']
        );
    }

    public function get_all()
    {
        return $this->query('SELECT * FROM users ORDER BY user_id');
    }

    public function get_by_id($id)
    {
        return $this->query_unique(
            'SELECT * FROM users WHERE user_id = :id',
            ['id' => $id]
        );
    }

    public function add($entity)
    {
        $created = parent::add($entity);
        $id = $created['id'];
        unset($created['id']);
        $created['user_id'] = $id;
        return $created;
    }

    public function update($entity, $id)
    {
        $entity = $this->only_columns($entity);
        $set = [];
        foreach (array_keys($entity) as $column) {
            $set[] = "$column = :$column";
        }

        $entity['id'] = $id;
        return $this->execute(
            'UPDATE users SET ' . implode(', ', $set) . ' WHERE user_id = :id',
            $entity
        );
    }

    public function delete($id)
    {
        return $this->execute(
            'DELETE FROM users WHERE user_id = :id',
            ['id' => $id]
        );
    }

    public function get_by_email($email)
    {
        return $this->query_unique(
            'SELECT * FROM users WHERE email = :email',
            ['email' => $email]
        );
    }
}
