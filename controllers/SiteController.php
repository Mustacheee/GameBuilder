<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\SignupForm;
use app\models\User;
use app\traits\ResponseTrait;
use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\VerbFilter;

class SiteController extends ActiveController
{
    use ResponseTrait;
    public $modelClass = User::class;

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
                    'signup' => ['post'],
                    'contact-us' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return ['signup', 'contact-us', 'error'];
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        // TODO:: Implement Logout Logic
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * POST command used for signing up new users.
     *
     * Expected input:
     * [
     *   'email' => '...',
     *   'first_name' => '...',
     *   'last_name' => '...',
     *   'password' => '...',
     * ]
     * @return string
     */
    public function actionSignup()
    {
        $signupForm = new SignupForm();
        $signupForm->load(['SignupForm' => Yii::$app->request->post()]);

        if ($signupForm->upsertUser()) {
            return $this->returnSuccess(['data' => $signupForm->getUserAttributes()]);
        }
        return $this->returnError($signupForm->errors);
    }

    public function actionContactUs()
    {
        $contactForm = new ContactForm();
        if ($contactForm->load(['ContactForm' => Yii::$app->request->post()]) && $contactForm->send()) {
            return $this->returnSuccess();
        }
        return $this->returnError($contactForm->errors);
    }

    public function actionError()
    {
        Yii::$app->response->setStatusCode(500);
        return $this->returnError(['OH SHIT!']);
    }
}
