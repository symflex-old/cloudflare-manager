<?php

use yii\db\Migration;

/**
 * Class m190407_181814_user_table
 */
class m190407_181814_user_table extends Migration
{
    public const TABLE = 'user';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'login' => $this->string(255)->notNull()->unique(),
            'password' => $this->string(60)->notNull()
        ]);

        $this->insert(self::TABLE, [
            'login' => 'admin',
            'password' => Yii::$app->security->generatePasswordHash('admin')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190407_181814_user_table cannot be reverted.\n";

        return false;
    }
    */
}
