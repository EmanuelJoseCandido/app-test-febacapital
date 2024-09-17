<?php

namespace app\services;

use app\models\BookModel;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * BookService handles the business logic for book operations.
 */
class BookService
{
    /**
     * Creates a new book record.
     *
     * @param array $data The data for the new book.
     * @return BookModel The created book model.
     * @throws BadRequestHttpException if validation fails.
     */
    public function createBook(array $data): BookModel
    {
        $book = new BookModel();

        if ($book->load($data, '') && $book->validate()) {
            if ($book->save()) {
                return $book;
            }
        }

        throw new BadRequestHttpException(json_encode($book->errors));
    }

    /**
     * Updates an existing book record.
     *
     * @param int $id The ID of the book to update.
     * @param array $data The data to update the book with.
     * @return BookModel The updated book model.
     * @throws NotFoundHttpException if the book is not found.
     * @throws BadRequestHttpException if validation fails.
     */
    public function updateBook(int $id, array $data): BookModel
    {
        $book = BookModel::findOne($id);

        if ($book === null) {
            throw new NotFoundHttpException('Book not found.');
        }

        if ($book->load($data, '') && $book->validate()) {
            if ($book->save()) {
                return $book;
            }
        }

        throw new BadRequestHttpException(json_encode($book->errors));
    }

    /**
     * Deletes an existing book record.
     *
     * @param int $id The ID of the book to delete.
     * @throws NotFoundHttpException if the book is not found.
     * @throws \Exception if the deletion fails.
     */
    public function deleteBook(int $id): void
    {
        $book = BookModel::findOne($id);

        if ($book === null) {
            throw new NotFoundHttpException('Book not found.');
        }

        if (!$book->delete()) {
            throw new \Exception('Failed to delete the book.');
        }
    }

    /**
     * Retrieves a single book record.
     *
     * @param int $id The ID of the book to retrieve.
     * @return BookModel The requested book model.
     * @throws NotFoundHttpException if the book is not found.
     */
    public function getBook(int $id): BookModel
    {
        $book = BookModel::findOne($id);

        if ($book === null) {
            throw new NotFoundHttpException('Book not found.');
        }

        return $book;
    }

    /**
     * Lists books with pagination, filters, and sorting.
     *
     * @param array $params The parameters for filtering and pagination.
     * @return ActiveDataProvider The data provider with the filtered and sorted list of books.
     */
    public function getBooksList(array $params): ActiveDataProvider
    {
        $query = BookModel::find();

        if (!empty($params['title'])) {
            $query->andFilterWhere(['like', 'title', $params['title']]);
        }

        if (!empty($params['author'])) {
            $query->andFilterWhere(['like', 'author', $params['author']]);
        }

        if (!empty($params['isbn'])) {
            $query->andFilterWhere(['isbn' => $params['isbn']]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['limit'] ?? 10,
                'page' => ($params['offset'] ?? 0) / ($params['limit'] ?? 10),
            ],
            'sort' => [
                'defaultOrder' => ['title' => SORT_ASC],
                'attributes' => [
                    'title',
                    'price',
                ],
            ],
        ]);
    }
}
