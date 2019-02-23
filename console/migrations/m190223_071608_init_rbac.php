<?php

use yii\db\Migration;

/**
 * Class m190223_071608_init_rbac
 */
class m190223_071608_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $createArticle = $auth->createPermission('createArticle');
        $createArticle->description = 'Create an article';
        $auth->add($createArticle);

        $updateArticle = $auth->createPermission('updateArticle');
        $updateArticle->description = 'Update article';
        $auth->add($updateArticle);

        // add "author" role and give this role the "createPost" permission
        $author = $auth->createRole('author');
        $auth->add($author);
        $auth->addChild($author, $createArticle);

        // add "admin" role and give this role the "updateArticle" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateArticle);
        $auth->addChild($admin, $author);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($admin, 1);

        $rule = new \common\rbac\AuthorRule();
        $auth->add($rule);

        // add the "updateOwnArticle" permission and associate the rule with it.
        $updateOwnArticle = $auth->createPermission('updateOwnArticle');
        $updateOwnArticle->description = 'Update own article';
        $updateOwnArticle->ruleName = $rule->name;
        $auth->add($updateOwnArticle);

        // "updateOwnArticle" will be used from "updateArticle"
        $auth->addChild($updateOwnArticle, $updateArticle);

        // allow "author" to update their own articles
        $auth->addChild($author, $updateOwnArticle);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190223_071608_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
