<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "roles".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property integer $hierarchy
 */
class Role extends ActiveRecord
{
    public const ROLE_DEV = 'dev';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUPPORT = 'support';
    public const ROLE_CHEF = 'chef';
    public const ROLE_USER = 'user';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%roles}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'hierarchy'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['hierarchy'], 'number']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    public function isAtLeast(string $roleName): bool
    {
        $role = Role::findOne(['name' => $roleName]);
        if (!$role instanceof Role) {
            return false;
        }
        return $this->hierarchy <= $role->hierarchy;
    }
}
