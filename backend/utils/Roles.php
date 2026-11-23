<?php
/**
 * User roles – must match the ENUM of the users.role column.
 */
class Roles
{
    const ADMIN = 'admin';
    const USER = 'user';
    const SELLER = 'seller';

    public static function all()
    {
        return [self::ADMIN, self::USER, self::SELLER];
    }
}