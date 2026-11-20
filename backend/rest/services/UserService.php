<?php
require_once __DIR__ . '/BaseServices.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../../utils/Roles.php';

class UserService extends BaseService
{
    public function __construct($dao = null)
    {
        parent::__construct($dao ?: new UserDao());
        $this->entity_name = 'User';
    }

    public function add($entity)
    {
        $this->validate($entity);
        $entity['password'] = password_hash($entity['password'], PASSWORD_DEFAULT);
        $created = $this->dao->add($entity);
        $id = (int) ($created['user_id'] ?? $created['id']);
        return $this->without_password($this->dao->get_by_id($id));
    }

    public function get_all()
    {
        return array_map([$this, 'without_password'], $this->dao->get_all());
    }

    public function get_by_id($id)
    {
        return $this->without_password(parent::get_by_id($id));
    }

    public function update($entity, $id)
    {
        $this->get_by_id($id);
        $this->validate($entity, $id);
        if (array_key_exists('password', $entity)) {
            $entity['password'] = password_hash($entity['password'], PASSWORD_DEFAULT);
        }
        $this->dao->update($entity, $id);
        return $this->get_by_id($id);
    }

    public function get_by_email($email)
    {
        Validator::email($email);
        $user = $this->dao->get_by_email($email);
        if (!$user) {
            throw new NotFoundException('User not found.');
        }
        return $this->without_password($user);
    }

    protected function validate($entity, $id = null)
    {
        if ($id === null) {
            Validator::required($entity, ['first_name', 'last_name', 'email', 'password']);
        }

        if (array_key_exists('first_name', $entity)) {
            Validator::length($entity['first_name'], 'first_name', 1, 50);
            Validator::no_html($entity['first_name'], 'first_name');
        }
        if (array_key_exists('last_name', $entity)) {
            Validator::length($entity['last_name'], 'last_name', 1, 50);
            Validator::no_html($entity['last_name'], 'last_name');
        }
        if (array_key_exists('email', $entity)) {
            Validator::email($entity['email']);
            $existing = $this->dao->get_by_email($entity['email']);
            if ($existing && (int) ($existing['user_id'] ?? 0) !== (int) $id) {
                throw new ValidationException("Email '{$entity['email']}' is already registered.");
            }
        }
        if (array_key_exists('password', $entity)) {
            Validator::password($entity['password']);
        }
        if (array_key_exists('role', $entity)) {
            Validator::one_of($entity['role'], 'role', Roles::all());
        }
    }

    private function without_password($user)
    {
        if (is_array($user)) {
            unset($user['password']);
        }
        return $user;
    }
}
