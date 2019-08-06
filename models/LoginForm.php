<?php

namespace app\models;

use app\common\components\JWTAuthTokenGenerator;
use app\common\interfaces\AccessTokenInterface;
use app\common\interfaces\AuthTokenGeneratorInterface;
use sizeg\jwt\Jwt;
use Yii;
use yii\db\ActiveRecord;

/**
 * LoginForm is the model behind the login form.
 *
 * @property string $id
 * @property int $user_id
 * @property string $created_at
 * @property string ip_address
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends ActiveRecord
{
    public $username;
    public $password;

    /** @var AccessTokenInterface */
    private $accessToken;

    /** @var AuthTokenGeneratorInterface $authTokenGenerator */
    protected $authTokenGenerator;

    private $_user = false;

    public function init()
    {
        parent::init();
        $this->authTokenGenerator = $this->authTokenGenerator ?? new JWTAuthTokenGenerator();
    }

    public function beforeValidate()
    {
        $this->ip_address = Yii::$app->request->getUserIP();
        $user = $this->getUser();
        if ($user instanceof User) {
            $this->user_id = $user->id;
        }
        return parent::beforeValidate();
    }

    public function afterSave($insert, $changedAttributes)
    {
        /** @var AccessTokenInterface $token */
        $user = $this->getUser(); // TODOOOOO TODO: Refresh access token logic
        $user->generateAuthKey();
        $user->save(false);

        $accessToken = AccessToken::generateNewAccessToken($user);
        $this->accessToken = $this->authTokenGenerator->generateToken($accessToken);
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_logins}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password', 'user_id', 'ip_address'], 'required'],
            ['password', 'validatePassword'],

//            ['username', 'string'],
//
//            ['password', 'string'],
//
//            ['user_id', 'int'],
//            //TODO:: Finish writing rules
//            ['access_token', 'string'],
//
//            ['ip_address', 'string']
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
