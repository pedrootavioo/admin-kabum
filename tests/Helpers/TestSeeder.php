<?php

namespace Test\Helpers;

use Source\Models\User;

function seedAdminUser(): false|User
{
    $data = [
        'name' => CONF_USER_ADMIN_TEST['name'],
        'email' => CONF_USER_ADMIN_TEST['email'],
        'password' => CONF_USER_ADMIN_TEST['password'],
        'confirm_password' => CONF_USER_ADMIN_TEST['password'],
    ];

    $user = new User();

    return $user->persist($data);
}