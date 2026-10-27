<?php
require_once __DIR__ . '/EntityDao.php';

class UserDao extends EntityDao
{
    public function __construct()
    {
        parent::__construct(
            'users',
            'user_id',
            ['first_name', 'last_name', 'email', 'password', 'role']
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
