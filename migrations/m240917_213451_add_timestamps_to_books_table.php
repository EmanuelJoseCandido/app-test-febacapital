<?php

use yii\db\Migration;

/**
 * Class m240917_213451_add_timestamps_to_books_table
 */
class m240917_213451_add_timestamps_to_books_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('books', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
        $this->addColumn('books', 'updated_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
    }

    public function safeDown()
    {
        $this->dropColumn('books', 'created_at');
        $this->dropColumn('books', 'updated_at');
    }

}
