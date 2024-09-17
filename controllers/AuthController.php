<?php
namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\services\AuthService;
use yii\web\UnauthorizedHttpException;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct($id, $module, AuthService $authService, $config = [])
    {
        $this->authService = $authService;
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = \yii\web\Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     * Login action to authenticate user and return a JWT token.
     */
    public function actionLogin()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');

        try {
            $response = $this->authService->authenticateAndGenerateToken($username, $password);
            return $response;
        } catch (UnauthorizedHttpException $e) {
            throw $e;
        }
    }
}

