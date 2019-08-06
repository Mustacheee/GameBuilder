<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "followers".
 *
 * @property int $id
 * @property int $followed_id
 * @property int $follower_id
 *
 * @property User $followed
 * @property User $follower
 */
class Follower extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'followers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['followed_id', 'follower_id'], 'required'],
            [['followed_id', 'follower_id'], 'integer'],
            [['followed_id'], 'unique', 'targetAttribute' => ['followed_id', 'follower_id'], 'message' => 'You are already following this user!'],
            [['followed_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['followed_id' => 'id']],
            [['follower_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['follower_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'followed_id' => 'Followed',
            'follower_id' => 'Follower',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowed()
    {
        return $this->hasOne(User::class, ['id' => 'followed_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollower()
    {
        return $this->hasOne(User::class, ['id' => 'follower_id']);
    }
}
