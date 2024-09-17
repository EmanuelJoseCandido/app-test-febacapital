<?php

use yii\db\Migration;

/**
 * Class m240917_201655_seed_books_table
 */
class m240917_201655_seed_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insertFakeBooks();
    }

    private function insertFakeBooks()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $this->insert(
                'books',
                [
                    'isbn' => $faker->isbn13,
                    'title' => $faker->sentence,
                    'author' => $faker->name,
                    'price' => $faker->randomFloat(2, 5, 50),
                    'stock' => $faker->numberBetween(0, 100),
                ]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240917_201655_seed_books_table cannot be reverted.\n";

        return false;
    }
}
