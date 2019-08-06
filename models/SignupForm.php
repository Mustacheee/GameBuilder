<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
//    public $birthdate;
    public $email;
    public $first_name;
//    public $gender;
    public $last_name;
    public $password;
    public $username;

    /**
     * @var \app\models\User $_user
     */
    private $_user;

    public function init()
    {
        parent::init();
        $this->_user = new User();
    }

    public function rules()
    {
        return array_merge($this->_user->getSignupRules(), [
            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 12],
            ['password',
                'match',
                'pattern' => '/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/',
                'message' => 'Password must contain at least one lower and upper case character and a digit.'
            ]
        ]);
    }

    public function upsertUser()
    {
        return $this->validate() && $this->createUser();
    }

    public function addErrors(array $errors)
    {
        foreach ($errors as $attribute => $error) {
            $attribute = $attribute === 'password_hash' ? 'password' : $attribute;
            if ($this->hasProperty($attribute)) {
                $this->addError($attribute, $error);
            }
        }
    }

    private function setUserAttributes()
    {
        $this->_user->email = $this->email;
        $this->_user->first_name = $this->first_name;
        $this->_user->last_name = $this->last_name;
        $this->_user->username = $this->username;
        $this->_user->setPassword($this->password);
    }

    private function createUser()
    {
        $this->setUserAttributes();
        $this->_user->setScenario(User::SCENARIO_SIGNUP);
        if ($this->_user->save()) {
            return true;
        }
        $this->addErrors($this->_user->errors);
        return false;
    }

    public function getUserAttributes()
    {
        return is_null($this->_user) ? [] : $this->_user->attributes;
    }
}
