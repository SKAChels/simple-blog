<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m190222_130817_add_admin
 */
class m190222_130817_add_admin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = new User();
        $user->generateAuthKey();
        $user->username = 'admin';
        $user->setPassword('admin');
        $user->status = User::STATUS_ACTIVE;
        $user->generatePasswordResetToken();
        $user->email = 'admin@admin.ru';
        $user->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190222_130817_add_admin cannot be reverted.\n";

        return false;
    }



    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190222_130817_add_admin cannot be reverted.\n";

        return false;
    }
    */
}
