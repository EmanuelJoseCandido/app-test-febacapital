<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\services\UserService;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct($id, $module, UserService $userService, $config = [])
    {
        $this->userService = $userService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Command to create a new user.
     *
     * @param string $username
     * @param string $password
     */
    public function actionCreate($username, $password)
    {
        echo $this->userService->createUser($username, $password);
    }

    /**
     * Command to update an existing user.
     *
     * @param int $id The ID of the user to update.
     * @param string $username The new username.
     * @param string|null $password The new password (if changing).
     */
    public function actionUpdate($id, $username, $password = null)
    {
        try {
            echo $this->userService->updateUser($id, $username, $password);
        } catch (NotFoundHttpException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Command to delete a user.
     *
     * @param int $id The ID of the user to delete.
     */
    public function actionDelete($id)
    {
        try {
            echo $this->userService->deleteUser($id);
        } catch (NotFoundHttpException $e) {
            echo $e->getMessage();
        }
    }
}

