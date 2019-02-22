<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190222_132526_add_article
 */
class m190222_132526_add_article extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        // table article
        $this->createTable(
            '{{%article}}',
            [
                'id' => Schema::TYPE_PK,
                'title' => Schema::TYPE_STRING . '(255) NOT NULL',
                'brief' => Schema::TYPE_TEXT,
                'content' => Schema::TYPE_TEXT . ' NOT NULL',
                'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'status' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
            ],
            $tableOptions
        );
        // Indexes
        $this->createIndex('status', '{{%article}}', 'status');
        $this->createIndex('created_at', '{{%article}}', 'created_at');
        // Foreign Keys
        $this->addForeignKey('FK_post_user', '{{%article}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%article}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190222_132526_add_article cannot be reverted.\n";

        return false;
    }
    */
}
