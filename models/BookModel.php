<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Book model.
 *
 * @property string $isbn
 * @property string $title
 * @property string $author
 * @property float $price
 * @property int $stock
 */
class BookModel extends ActiveRecord
{
    public static function tableName()
    {
        return 'books';
    }

    public function rules()
    {
        return [
            [['isbn', 'title', 'author', 'price', 'stock'], 'required'],
            [['price'], 'number', 'min' => 0],
            [['stock'], 'integer', 'min' => 0],
            [['isbn'], 'string', 'length' => 13],
            [['isbn'], 'unique'],
            [['title', 'author'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'isbn' => 'ISBN',
            'title' => 'Title',
            'author' => 'Author',
            'price' => 'Price',
            'stock' => 'Stock',
        ];
    }
}
