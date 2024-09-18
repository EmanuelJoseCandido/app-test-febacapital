<?php

namespace tests\unit\models;

use app\models\UserModel;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        verify($user = UserModel::findIdentity(100))->notEmpty();
        verify($user->username)->equals('admin');

        verify(UserModel::findIdentity(999))->empty();
    }

    public function testFindUserByAccessToken()
    {
        verify($user = UserModel::findIdentityByAccessToken('100-token'))->notEmpty();
        verify($user->username)->equals('admin');

        verify(UserModel::findIdentityByAccessToken('non-existing'))->empty();
    }

    public function testFindUserByUsername()
    {
        verify($user = UserModel::findByUsername('admin'))->notEmpty();
        verify(UserModel::findByUsername('not-admin'))->empty();
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser()
    {
        $user = UserModel::findByUsername('admin');
        verify($user->validateAuthKey('test100key'))->notEmpty();
        verify($user->validateAuthKey('test102key'))->empty();

        verify($user->validatePassword('admin'))->notEmpty();
        verify($user->validatePassword('123456'))->empty();
    }

}
