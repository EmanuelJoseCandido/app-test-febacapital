<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 */
class m240917_065534_create_books_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('books', [
            'id' => $this->primaryKey(),
            'isbn' => $this->string(13)->notNull()->unique(),
            'title' => $this->string()->notNull(),
            'author' => $this->string()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'stock' => $this->integer()->notNull()->defaultValue(0),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('books');
    }
}
