<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m190222_134045_add_comment
 */
class m190222_134045_add_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        // table comment
        $this->createTable(
            '{{%comment}}',
            [
                'id' => Schema::TYPE_PK,
                'article_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'content' => Schema::TYPE_TEXT . ' NOT NULL',
                'author' => Schema::TYPE_STRING . '(128) NOT NULL',
                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
            ],
            $tableOptions
        );
        // Indexes
        $this->createIndex('article_id', '{{%comment}}', 'article_id');
        $this->createIndex('created_at', '{{%comment}}', 'created_at');
        // Foreign Keys
        $this->addForeignKey('FK_comment', '{{%comment}}', 'article_id', '{{%article}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%comment}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190222_134045_add_comment cannot be reverted.\n";

        return false;
    }
    */
}
