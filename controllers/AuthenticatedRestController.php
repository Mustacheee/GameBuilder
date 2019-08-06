<?php

namespace app\controllers;

use app\models\User;
use sizeg\jwt\JwtHttpBearerAuth;
use Yii;
use yii\rest\ActiveController;

class AuthenticatedRestController extends ActiveController
{
    public $layout = null;

    /**
     * @var User $loggedInUser
     */
    private $loggedInUser;

    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
        ];
        return $behaviors;
    }

    /**
     * {@inheritDoc}
     */
    public function afterAction($action, $result)
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;
        $user->refreshAccessToken();
        return parent::afterAction($action, $result);
    }

    /**
     * @return User The current logged in User.
     */
    protected function getLoggedInUser(): User
    {
        if (is_null($this->loggedInUser)) {
            $loggedInUser = Yii::$app->user->identity;
            $this->loggedInUser = $loggedInUser;
        }
        return $this->loggedInUser;
    }
}