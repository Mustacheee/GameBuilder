<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    /** @var string $email */
    public $email;

    /** @var string $first_name */
    public $first_name;

    /** @var string $last_name */
    public $last_name;

    /** @var string $password */
    public $password;

    /**
     * @var User $_user
     */
    private $_user;

    public function init()
    {
        parent::init();
        $this->_user = $this->_user ?? new User();
    }

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'string', 'max' => 254],
            ['email', 'trim', 'skipOnEmpty' => true],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email'],

            ['first_name', 'required'],
            ['first_name', 'string', 'max' => 32],
            ['first_name', 'trim', 'skipOnEmpty' => true],

            ['last_name', 'required'],
            ['last_name', 'string', 'max' => 32],
            ['last_name', 'trim', 'skipOnEmpty' => true],

            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 12],
            ['password',
                'match',
                'pattern' => '/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/',
                'message' => 'Password must contain at least one lower and upper case character and a digit.'
            ]
        ];
    }

    /**
     * Load the params (if available), validate the input and create the user.
     *
     * @param array $params
     * @return bool
     */
    public function signupUser(array $params = []): bool
    {
        if (!empty($params)) {
            $this->load($params, '');
        }

        return $this->validate() && $this->createUser();
    }

    /**
     * Set our user's attributes and attempt to create it.
     *
     * @return bool
     */
    private function createUser(): bool
    {
        $this->setUserAttributes();
        return $this->_user->save();
    }

    /**
     * Transfer the attributes from our signup form to our user.
     */
    private function setUserAttributes()
    {
        $this->_user->email = $this->email;
        $this->_user->first_name = $this->first_name;
        $this->_user->last_name = $this->last_name;
        $this->_user->setPassword($this->password);
        $this->_user->setScenario(User::SCENARIO_SIGNUP);
    }

    /**
     * Get the errors that occurred during the signup process.
     *
     * @param string|null $attribute
     * @return array
     */
    public function getErrors($attribute = null): array
    {
        if ($this->hasErrors($attribute)) {
            return parent::getErrors($attribute);
        }

        // @codeCoverageIgnoreStart
        return $this->_user->getErrors($attribute);
        // @codeCoverageIgnoreEnd
    }
}
