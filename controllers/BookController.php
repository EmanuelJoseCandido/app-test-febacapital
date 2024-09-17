<?php

namespace app\controllers;

use app\services\BookService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * BookController handles book-related API requests.
 */
class BookController extends Controller
{
    private BookService $bookService;

    public function __construct($id, $module, BookService $bookService, $config = [])
    {
        $this->bookService = $bookService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * Creates a new book.
     *
     * @return array
     */
    public function actionCreate()
    {
        $data = Yii::$app->request->post();
        $response = Yii::$app->response;

        try {
            $book = $this->bookService->createBook($data);

            $response->statusCode = 201;
            return [
                'success' => true,
                'message' => 'Book successfully created',
                'data' => $book,
            ];
        } catch (BadRequestHttpException $e) {
            $response->statusCode = 400;
            return [
                'success' => false,
                'message' => 'Failed to create book',
                'errors' => json_decode($e->getMessage(), true),
            ];
        }
    }

    /**
     * Updates an existing book.
     *
     * @param int $id The ID of the book to update.
     * @return array
     * @throws NotFoundHttpException if the book is not found
     * @throws BadRequestHttpException if validation fails
     */
    public function actionUpdate($id)
    {
        $data = Yii::$app->request->post();
        $response = Yii::$app->response;

        try {
            $book = $this->bookService->updateBook($id, $data);

            $response->statusCode = 200; 
            return [
                'success' => true,
                'message' => 'Book successfully updated',
                'data' => $book,
            ];
        } catch (NotFoundHttpException $e) {
            $response->statusCode = 404; 
            return [
                'success' => false,
                'message' => 'Book not found',
            ];
        } catch (BadRequestHttpException $e) {
            $response->statusCode = 400;
            return [
                'success' => false,
                'message' => 'Failed to update book',
                'errors' => json_decode($e->getMessage(), true),
            ];
        }
    }

    /**
     * Deletes an existing book.
     *
     * @param int $id The ID of the book to delete.
     * @return array
     * @throws NotFoundHttpException if the book is not found
     */
    public function actionDelete($id)
    {
        $response = Yii::$app->response;

        try {
            $this->bookService->deleteBook($id);

            $response->statusCode = 204; 
            return [
                'success' => true,
                'message' => 'Book successfully deleted',
            ];
        } catch (NotFoundHttpException $e) {
            $response->statusCode = 404; 
            return [
                'success' => false,
                'message' => 'Book not found',
            ];
        }
    }

    /**
     * Retrieves a list of books with pagination, filters, and sorting.
     *
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->get();
        return $this->bookService->getBooksList($params);
    }

    /**
     * Retrieves a single book by ID.
     *
     * @param int $id The ID of the book to retrieve.
     * @return array
     * @throws NotFoundHttpException if the book is not found
     */
    public function actionView($id)
    {
        $response = Yii::$app->response;

        try {
            $book = $this->bookService->getBook($id);

            $response->statusCode = 200;
            return [
                'success' => true,
                'data' => $book,
            ];
        } catch (NotFoundHttpException $e) {
            $response->statusCode = 404;
            return [
                'success' => false,
                'message' => 'Book not found',
            ];
        }
    }
}
