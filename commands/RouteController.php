<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class RouteController extends Controller
{
    public function actionList()
    {
        $rules = Yii::$app->urlManager->rules;
        foreach ($rules as $rule) {
            echo "<pre>";
            print_r($rule);
            echo "</pre>";
        }
    }
}
