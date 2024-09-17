<?php

namespace app\services;

use Yii;
use app\models\UserModel;
use app\helpers\JwtHelper;
use yii\web\UnauthorizedHttpException;

class AuthService
{
    /**
     * Authenticates a user and returns a JWT token if successful.
     *
     * @param string $username
     * @param string $password
     * @return array
     * @throws UnauthorizedHttpException
     */
    public function authenticateAndGenerateToken(string $username, string $password): array
    {
        $user = UserModel::findByUsername($username);

        if (!$user || !$user->validatePassword($password)) {
            throw new UnauthorizedHttpException('Invalid username or password.');
        }

        $token = JwtHelper::generateToken($user->id, $user->username);

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ];
    }
}
