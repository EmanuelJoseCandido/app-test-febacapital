<?php

namespace app\services;

use app\models\UserModel;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;

class UserService
{
    public function createUser($username, $password)
    {
        $user = new UserModel();
        $user->username = $username;
        $user->setPassword($password);
        $user->generateAuthKey();

        if ($user->validate() && $user->save()) {
            return "User created successfully.\n";
        } else {
            $errors = [];
            foreach ($user->errors as $attributeErrors) {
                foreach ($attributeErrors as $error) {
                    $errors[] = $error;
                }
            }
            return "Failed to create user:\n" . implode("\n", $errors) . "\n";
        }
    }

    public function updateUser($id, $username, $password = null)
    {
        $user = UserModel::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException("User with ID $id not found.\n");
        }

        $user->username = $username;
        if ($password !== null) {
            $user->setPassword($password);
        }

        if ($user->validate() && $user->save()) {
            return "User updated successfully.\n";
        } else {
            $errors = [];
            foreach ($user->errors as $attributeErrors) {
                foreach ($attributeErrors as $error) {
                    $errors[] = $error;
                }
            }
            return "Failed to update user:\n" . implode("\n", $errors) . "\n";
        }
    }

    public function deleteUser($id)
    {
        $user = UserModel::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException("User with ID $id not found.\n");
        }

        if ($user->delete()) {
            return "User deleted successfully.\n";
        } else {
            $errors = [];
            foreach ($user->errors as $attributeErrors) {
                foreach ($attributeErrors as $error) {
                    $errors[] = $error;
                }
            }
            return "Failed to delete user:\n" . implode("\n", $errors) . "\n";
        }
    }
}
