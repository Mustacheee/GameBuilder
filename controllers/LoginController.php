<?php

namespace app\controllers;

use app\models\LoginForm;
use app\traits\ResponseTrait;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;

class LoginController extends ActiveController
{
    use ResponseTrait;
    public $modelClass = LoginForm::class;

    public function actions()
    {
        return ['login'];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'login' => ['post'],
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->login(Yii::$app->request->post())) {
            return $this->returnSuccess(['access_token' => $model->getAccessToken()->toString()]);
        }

        return $this->returnError($model->errors);
    }
}

