<?php

use yii\db\Migration;

/**
 * Class m190407_122422_account_table
 */
class m190407_122422_account_table extends Migration
{
    public const TABLE = 'account';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'email' => $this->string(255)->notNull()->unique(),
            'api_key' => $this->string(255)->notNull()
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
        echo "m190407_122422_account_table cannot be reverted.\n";

        return false;
    }
    */
}
