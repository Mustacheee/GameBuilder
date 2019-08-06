<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "access_tokens".
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string $expires_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class AccessToken extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%access_tokens}}';
    }

    public static function generateNewAccessToken(?User $user)
    {
        $accessToken = new self([
            'user_id' => $user->id,
            'token' => $user->getAuthKey(),
            'expires_at' => date('Y-m-d H:i:s', time() + Yii::$app->params['accessTokenOffset'])
        ]);

        $accessToken->save();
        return $accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'token', 'expires_at'], 'required'],
            [['user_id'], 'integer'],
            [['token'], 'string'],
            [['expires_at', 'created_at', 'updated_at'], 'safe'],
//            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'token' => 'Token',
            'expires_at' => 'Expires At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
