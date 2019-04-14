<?php

use yii\db\Migration;

/**
 * Class m190412_121558_zones_table
 */
class m190412_121558_zones_table extends Migration
{
    public const TABLE = 'zones';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('name_server', [
            'id' => $this->primaryKey(),
            'server' => $this->string(255)->notNull(),
            //'zone_id' => $this->integer(255)->notNull()
        ]);

        $this->createTable('dns_record', [
            'id' => $this->primaryKey(),
            //'zone_id' => $this->integer(255)->notNull(),
            'record_id' => $this->string(255)->notNull(),
            'type' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
            'value' => $this->text()->notNull(),
            'ttl'   => $this->integer(255)->notNull(),
            'status' => $this->boolean()->notNull()
        ]);

        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(),
            'zone_id' => $this->string(255),
            'account_id' => $this->integer(255)->notNull(),
            'domain' => $this->string(255)->notNull(),
            'ssl' => $this->boolean()->notNull(),
            'tls' => $this->string(255)->notNull(),
            'rewrite' => $this->boolean()->notNull(),
            'debug' => $this->boolean()->notNull(),
            'attack_mode' => $this->boolean()->notNull()
        ]);

        $this->createTable('zone_name_server', [
            'zone_id' => $this->integer(),
            'server_id' => $this->integer(),
            'PRIMARY KEY(zone_id, server_id)',
        ]);

        $this->createIndex(
            'idx-zone-server',
            'zone_name_server',
            'zone_id'
        );

        $this->addForeignKey(
            'fk-zone-server',
            'zone_name_server',
            'zone_id',
            'zones',
            'id',
            'CASCADE'
        );

        // creates index for column `tag_id`
        $this->createIndex(
            'idx-server-zone',
            'zone_name_server',
            'server_id'
        );

        // add foreign key for table `tag`
        $this->addForeignKey(
            'fk-server-zone',
            'zone_name_server',
            'server_id',
            'name_server',
            'id',
            'CASCADE'
        );


        $this->createTable('zone_dns', [
            'zone_id' => $this->integer(),
            'dns_id' => $this->integer(),
            'PRIMARY KEY(zone_id, dns_id)',
        ]);

        $this->createIndex(
            'idx-zone-dns',
            'zone_dns',
            'zone_id'
        );

        $this->addForeignKey(
            'fk-zone-dns',
            'zone_dns',
            'zone_id',
            'zones',
            'id',
            'CASCADE'
        );

        // creates index for column `tag_id`
        $this->createIndex(
            'idx-dns-zone',
            'zone_dns',
            'dns_id'
        );

        // add foreign key for table `tag`
        $this->addForeignKey(
            'fk-dns-zone',
            'zone_dns',
            'dns_id',
            'dns_record',
            'id',
            'CASCADE'
        );

        $this->addForeignKey('fk_account', self::TABLE, 'account_id', 'account', 'id', 'CASCADE', 'CASCADE');

        //$this->addForeignKey('fk_zone', 'name_server', 'zone_id', self::TABLE, 'id', 'CASCADE', 'CASCADE');
        //$this->addForeignKey('fk_ns', 'dns_record', 'zone_id', self::TABLE, 'id', 'CASCADE', 'CASCADE');
        //$this->addForeignKey('fk_account', self::TABLE, 'account_id', 'account', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190412_121558_zones_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190412_121558_zones_table cannot be reverted.\n";

        return false;
    }
    */
}
