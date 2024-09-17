<?php

namespace app\controllers;

use app\services\clients\ClientService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * ClientController handles client-related API requests.
 */
class ClientController extends Controller
{
    private ClientService $clientService;

    public function __construct($id, $module, ClientService $clientService, $config = [])
    {
        $this->clientService = $clientService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * Creates a new client along with an address.
     *
     * @return array
     */
    public function actionCreate()
    {
        $data = Yii::$app->request->post();
        $response = Yii::$app->response;

        try {
            $client = $this->clientService->createClient($data);
            $response->statusCode = 201;
            return [
                'success' => true,
                'message' => 'Client successfully created',
                'data' => $client,
            ];
        } catch (BadRequestHttpException $e) {
            $response->statusCode = 400;
            return [
                'success' => false,
                'message' => 'Failed to create client',
                'errors' => json_decode($e->getMessage(), true),
            ];
        }
    }

    /**
     * Lists clients with pagination, filters, and sorting.
     *
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->get();
        return $this->clientService->getClientsList($params);
    }

    /**
     * Views a single client by ID.
     *
     * @param int $id
     * @return array
     */
    public function actionView($id)
    {
        $response = Yii::$app->response;

        try {
            $client = $this->clientService->getClientById($id);
            return [
                'success' => true,
                'data' => $client,
            ];
        } catch (BadRequestHttpException $e) {
            $response->statusCode = 404;
            return [
                'success' => false,
                'message' => 'Client not found',
            ];
        }
    }

    /**
     * Updates a client by ID.
     *
     * @param int $id
     * @return array
     */
    public function actionUpdate($id)
    {
        $data = Yii::$app->request->post();
        $response = Yii::$app->response;

        try {
            $client = $this->clientService->updateClient($id, $data);
            return [
                'success' => true,
                'message' => 'Client successfully updated',
                'data' => $client,
            ];
        } catch (BadRequestHttpException $e) {
            $response->statusCode = 400;
            return [
                'success' => false,
                'message' => 'Failed to update client',
                'errors' => json_decode($e->getMessage(), true),
            ];
        }
    }

    /**
     * Deletes a client by ID.
     *
     * @param int $id
     * @return array
     */
    public function actionDelete($id)
    {
        $response = Yii::$app->response;

        try {
            $this->clientService->deleteClient($id);
            $response->statusCode = 204;
            return [];
        } catch (BadRequestHttpException $e) {
            $response->statusCode = 404;
            return [
                'success' => false,
                'message' => 'Client not found',
            ];
        }
    }
}
