<?php

namespace app\controllers;

use app\models\Follower;
use app\models\Game;
use app\models\User;
use app\traits\ResponseTrait;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\VerbFilter;

class GameController extends AuthenticatedRestController
{
    use ResponseTrait;
    public $modelClass = Game::class;

    /**
     * {@inheritdoc}
     */
//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//
//        return array_merge($behaviors, [
//            'verbs' => [
//                'class'   => VerbFilter::class,
//                'actions' => [
//                    'follow' => ['post'],
//                    'unfollow' => ['post'],
//                    'followers' => ['get'],
//                ],
//            ],
//        ]);
//    }

//    public function actions()
//    {
//        $actions = parent::actions();
//        unset($actions['create']);
//        unset($actions['view']);
//        return array_merge($actions, [
//            'follow',
//            'unfollow',
//            'followers',
//        ]);
//    }
}