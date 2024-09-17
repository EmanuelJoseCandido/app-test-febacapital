<?php

namespace app\helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Yii;

class JwtHelper
{
    private static $key = 'your_secret_key'; 
    private static $algorithm = 'HS256';

    public static function generateToken($userId, $username)
    {
        $payload = [
            'iss' => 'your-domain.com',
            'aud' => 'your-domain.com',
            'iat' => time(),
            'exp' => time() + (60 * 60), 
            'userId' => $userId,
            'username' => $username,
        ];

        return JWT::encode($payload, self::$key, self::$algorithm);
    }

    public static function verifyToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(self::$key, self::$algorithm));
            return (array) $decoded;
        } catch (\Exception $e) {
            return false;
        }
    }
}
