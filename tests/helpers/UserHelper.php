<?php

namespace app\tests\helpers;

use app\models\User;
use Faker\Factory;

class UserHelper
{
    private $faker;

    private const DEFAULT_PASSWORD = 'Test.1234';

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function createUser(array $config = []): User
    {
        $user = new User($config);
        $user->email = $user->email ?? $this->faker->email;
        $user->first_name = $user->first_name ??$this->faker->firstName;
        $user->last_name = $user->last_name ?? $this->faker->lastName;
        $password = $config['password'] ?? self::DEFAULT_PASSWORD;

        $user->setPassword($password);
        $user->generateAuthKey();
        $user->save(false);
        return $user;
    }
}