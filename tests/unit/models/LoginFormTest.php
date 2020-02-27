<?php

namespace app\tests\unit\models;

use app\common\components\JWTAuthTokenGenerator;
use app\common\interfaces\AccessTokenInterface;
use app\models\LoginForm;
use app\tests\helpers\UserHelper;

class LoginFormTest extends \Codeception\Test\Unit
{
    private $model;

    private const DEFAULT_ERROR = 'Incorrect email or password.';

    protected function _after()
    {
        \Yii::$app->user->logout();
    }

    /**
     * Make sure submitting form without input returns proper errors
     *
     * @throws \Exception
     */
    public function testMissingParameters()
    {
        $generator = $this->make(JWTAuthTokenGenerator::class);
        $this->model = new LoginForm(['authTokenGenerator' => $generator]);

        expect_not($this->model->login());
        expect($this->model->errors)->hasKey('email');
        expect($this->model->errors)->hasKey('password');
        $this->assertContains('cannot be blank', $this->model->errors['email'][0]);
        $this->assertContains('cannot be blank', $this->model->errors['password'][0]);
    }

    /**
     * Logging in with a non-existent email should return expected error.
     *
     * @throws \Exception
     */
    public function testLoginNoUser()
    {
        $generator = $this->make(JWTAuthTokenGenerator::class);

        $this->model = new LoginForm([
            'email' => 'not_existing_email',
            'password' => 'not_existing_password',
            'authTokenGenerator' => $generator
        ]);

        expect_not($this->model->login());
        expect($this->model->errors)->hasKey('password');
        $this->assertEquals($this->model->errors['password'][0], self::DEFAULT_ERROR);
    }

    /**
     * Attempting to login with a correct email, but incorrect password, displays
     * the same vague error message.
     *
     * @throws \Exception
     */
    public function testLoginWrongPassword()
    {
        $userHelper = new UserHelper();
        $user = $userHelper->createUser();

        $generator = $this->make(JWTAuthTokenGenerator::class);

        $this->model = new LoginForm([
            'email' => $user->email,
            'password' => 'wrong_password',
            'authTokenGenerator' => $generator,
        ]);

        expect_not($this->model->save());
        expect($this->model->errors)->hasKey('password');
        $this->assertEquals($this->model->errors['password'][0], self::DEFAULT_ERROR);
    }

    /**
     * Using the correct set of credentials results in a successful login.
     *
     * @throws \Exception
     */
    public function testLoginCorrect()
    {
        $generator = $this->make(JWTAuthTokenGenerator::class, ['generateToken' => function($user) {
            return $this->makeEmpty(AccessTokenInterface::class);
        }]);

        $userHelper = new UserHelper();
        $password = 'Test.1234';
        $user = $userHelper->createUser(['password' => $password]);

        $this->model = new LoginForm(['authTokenGenerator' => $generator]);

        expect_that($this->model->login([
            'email' => $user->email,
            'password' => $password,
        ]));

        $this->assertTrue($this->model->getAccessToken() instanceof  AccessTokenInterface);
    }

}
