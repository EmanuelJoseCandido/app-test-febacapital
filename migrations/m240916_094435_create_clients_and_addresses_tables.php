<?php

use yii\db\Migration;

/**
 * Class m240916_094435_create_clients_and_addresses_tables
 */
class m240916_094435_create_clients_and_addresses_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('clients', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'cpf' => $this->string(14)->notNull()->unique(),
            'sex' => "ENUM('M', 'F') NOT NULL",
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        $this->createTable('addresses', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'zip' => $this->string(10)->notNull(),
            'street' => $this->string()->notNull(),
            'number' => $this->string(10)->notNull(),
            'city' => $this->string()->notNull(),
            'state' => $this->string(2)->notNull(),
            'complement' => $this->string()->null(),
        ]);

        $this->addForeignKey(
            'fk-address-client_id',
            'addresses',
            'client_id',
            'clients',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-address-client_id', 'addresses');

        $this->dropTable('addresses');
        $this->dropTable('clients');
    }
}
