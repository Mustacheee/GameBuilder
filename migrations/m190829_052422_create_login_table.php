<?php

use app\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%login}}`.
 */
class m190829_052422_create_login_table extends Migration
{
    private $tableName = '{{%user_logins}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'ip_address' => $this->string(),
        ], $tableOptions);

        $this->addForeignKey('fk_user_logins_user', $this->tableName, 'user_id', User::tableName(),'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
