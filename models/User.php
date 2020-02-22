<?php
namespace app\models;

use app\common\interfaces\AccessTokenInterface;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 *
 * @property string $auth_key
 * @property string $description
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token

 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const SCENARIO_SIGNUP = 'signup';

    /** @var AccessTokenInterface $accessToken */
    private $accessToken;

    public function beforeSave($insert)
    {
        if ($this->scenario === self::SCENARIO_SIGNUP) {
            $this->generateAuthKey();
            $this->status = self::STATUS_ACTIVE;
        }
        return parent::beforeSave($insert);
    }

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'string', 'max' => 254],
            ['email', 'trim', 'skipOnEmpty' => true],
            ['email', 'email'],
            ['email', 'unique', 'targetAttribute' => 'email'],

            ['first_name', 'required'],
            ['first_name', 'string', 'max' => 32],
            ['first_name', 'trim', 'skipOnEmpty' => true],

            ['last_name', 'required'],
            ['last_name', 'string', 'max' => 32],
            ['last_name', 'trim', 'skipOnEmpty' => true],

            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],

            ['description', 'string'],

//            ['birthdate', 'validateBirthdate'],
        ];
    }

    public function scenarios()
    {
        return array_merge(parent::scenarios(), [
            self::SCENARIO_SIGNUP => ['email', 'first_name', 'last_name']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /** @var AccessTokenInterface $token */
        $token = Yii::$app->accessTokenGenerator->parseToken((string) $token);
        if (!Yii::$app->accessTokenGenerator->validateToken($token)) {
            return null;
        }

        $user = static::find()
            ->andWhere([
                'auth_key' => $token->getAuthToken(),
                'id' => $token->getUserId(),
                'status' => self::STATUS_ACTIVE
            ])
            ->one();

        if (!$user instanceof self) {
            return null;
        }

        $user->accessToken = $token;

        return $user;
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return User|null
     */
    public static function findByUsername($email)
    {
        $user = self::find()
            ->andWhere(['email' => $email, 'status' => self::STATUS_ACTIVE])
            ->one();

        /** @var User|null $user */
        return $user;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates and sets authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function refreshAccessToken()
    {
        $new_expiration = time() + Yii::$app->params['accessTokenOffset'];
        AccessToken::updateAll(
            ['expires_at' => date('Y-m-d H:i:s', $new_expiration)],
            ['user_id' => $this->accessToken->getUserId(), 'token' => $this->accessToken->getAuthToken()]
        );
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function follow($userId)
    {
        $follower = new Follower(['followed_id' => $userId, 'follower_id' => $this->id]);

        if ($follower->save()) {
            return true;
        }
        return $follower->errors;
    }

    public function unfollow($userId)
    {
        Follower::deleteAll(['follower_id' => $this->id, 'followed_id' => $userId]);
    }
}
