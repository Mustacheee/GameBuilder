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
    /** @var string $email */
    public $email;

    /** @var string $password */
    public $password;

    /** @var AuthTokenGeneratorInterface $_authTokenGenerator */
    protected $_authTokenGenerator;

    /** @var AccessTokenInterface */
    private $accessToken;

    /** @var User|bool $_user*/
    private $_user = false;

    /**
     * Make sure defaults to dependencies are met
     */
    public function init()
    {
        parent::init();
        $this->_authTokenGenerator = $this->_authTokenGenerator ?? new JWTAuthTokenGenerator();
        $this->ip_address = Yii::$app->request->getUserIP();
    }

    /**
     * After recording our login, create an access token for the user.
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->accessToken = $this->generateAccessToken();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Generate an auth key and a corresponding access token for our now-logged in user.
     *
     * @return AccessTokenInterface
     */
    private function generateAccessToken(): AccessTokenInterface
    {
        $user = $this->getUser();
        $user->generateAuthKey();
        $user->save(false);

        $accessToken = AccessToken::generateNewAccessToken($user);
        return $this->_authTokenGenerator->generateToken($accessToken);
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
            ['email', 'string'],
            ['password', 'string'],

            [['email', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Load our params and attempt to login.
     *
     * @param array $params
     * @return bool
     */
    public function login(array $params = [])
    {
        if (!empty($params)) {
            $this->load($params, '');
        }
        return $this->save();
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

            if (!$user instanceof User || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect email or password.');
                return;
            }
            $this->user_id = $user->id;
        }
    }

    /**
     * Get our access token to be used by the user logging in.
     *
     * @return AccessTokenInterface
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }

    /**
     * Set our Authentication Token Generator
     *
     * @param AuthTokenGeneratorInterface $authTokenGenerator
     */
    public function setAuthTokenGenerator(AuthTokenGeneratorInterface $authTokenGenerator)
    {
        $this->_authTokenGenerator = $authTokenGenerator;
    }
}
