<?php

use app\models\User;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%followers}}`.
 */
class m191106_022507_create_followers_table extends Migration
{
    private $tableName = '{{%followers}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'followed_id' => $this->integer()->notNull(),
            'follower_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_followers_followed_follower', $this->tableName, ['followed_id', 'follower_id'], true);
        $this->addForeignKey('fk_followers_follower', $this->tableName, 'follower_id', User::tableName(), 'id');
        $this->addForeignKey('fk_followers_followed', $this->tableName, 'followed_id', User::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_followers_follower', $this->tableName);
        $this->dropForeignKey('fk_followers_followed', $this->tableName);
        $this->dropIndex('idx_followers_followed_follower', $this->tableName);

        $this->dropTable('{{%followers}}');
    }
}
