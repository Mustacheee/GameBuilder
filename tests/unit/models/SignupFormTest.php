<?php

namespace app\tests\unit\models;

use app\models\SignupForm;
use app\tests\helpers\UserHelper;
use Faker\Factory;

class SignupFormTest extends \Codeception\Test\Unit
{
    /**
     * Test the various restrictions on the user email
     */
    public function testEmailRequirements()
    {
        $params = [
            'first_name' => 'Mike',
            'last_name' => 'Jones',
            'password' => 'Test.1234',
        ];

        $model = new SignupForm();
        expect_not($model->signupUser(array_merge($params, [
            'email' => 'invalid_email_format',
        ])));

        expect($model->errors)->hasKey('email');
        $this->assertContains(
            'not a valid email address',
            $model->errors['email'][0],
            'Email should have been deemed invalid.'
        );

        $userHelper = new UserHelper();
        $user = $userHelper->createUser();

        expect_not($model->signupUser(array_merge($params, [
            'email' => $user->email,
        ])));

        expect($model->errors)->hasKey('email');
        $this->assertContains(
            'has already been taken',
            $model->errors['email'][0],
            'Email should have been marked taken.'
        );
    }

    /**
     * Attempting to signup yields proper errors
     */
    public function testMissingParameters()
    {
        $model = new SignupForm();
        expect_not($model->signupUser([]));
        expect($model->errors)->hasKey('password');
        expect($model->errors)->hasKey('email');
        expect($model->errors)->hasKey('first_name');
        expect($model->errors)->hasKey('last_name');
    }

    /**
     * Test the various constraints on a user's password
     */
    public function testPasswordRequirements()
    {
        $faker = Factory::create();

        $model = new SignupForm([
            'first_name' => 'Mike',
            'last_name' => 'Jones',
            'email' => $faker->email,
        ]);

        // Too short of a password
        expect_not($model->signupUser(['password' => 'short']));
        expect($model->errors)->hasKey('password');
        $this->assertContains(
            'should contain at least',
            $model->errors['password'][0],
            'Password should be marked as too short'
        );

        expect_not($model->signupUser(['password' => 'too_long_of_a_password_to_save']));
        expect($model->errors)->hasKey('password');
        $this->assertContains(
            'should contain at most',
            $model->errors['password'][0],
            'Password should be marked as too long'
        );

        expect_not($model->signupUser(['password' => 'lowercase']));
        expect($model->errors)->hasKey('password');
        $this->assertContains(
            'must contain at least',
            $model->errors['password'][0],
            'Password should not allow lowercase only'
        );

        expect_not($model->signupUser(['password' => 'UPPERCASE']));
        expect($model->errors)->hasKey('password');
        $this->assertContains(
            'must contain at least',
            $model->errors['password'][0],
            'Password should not allow uppercase only'
        );

        expect_not($model->signupUser(['password' => '12345678']));
        expect($model->errors)->hasKey('password');
        $this->assertContains(
            'must contain at least',
            $model->errors['password'][0],
            'Password should not allow digits only'
        );

        expect_not($model->signupUser(['password' => 'lowercas3']));
        expect($model->errors)->hasKey('password');
        $this->assertContains(
            'must contain at least',
            $model->errors['password'][0],
            'Password should not allow lowercase + digit only'
        );

        expect_not($model->signupUser(['password' => 'UPPERCAS3']));
        expect($model->errors)->hasKey('password');
        $this->assertContains(
            'must contain at least',
            $model->errors['password'][0],
            'Password should not allow uppercase + digit only'
        );

        expect_not($model->signupUser(['password' => 'bothCASE']));
        expect($model->errors)->hasKey('password');
        $this->assertContains(
            'must contain at least',
            $model->errors['password'][0],
            'Password should not only uppercase + lowercase only'
        );

        expect($model->signupUser(['password' => 'Test.1234']));
    }
}