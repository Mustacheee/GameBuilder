<?php
namespace app\controllers;

use app\models\Review;
use app\traits\ResponseTrait;
use yii\data\ActiveDataProvider;

class ReviewController extends AuthenticatedRestController
{
    public $modelClass = Review::class;
    use ResponseTrait;

    public function actions()
    {
        $actions = parent::actions();
        return array_merge($actions, [
            'get-by-user'
        ]);
    }

    public function actionGetByUser($user_id)
    {
        $query = Review::find()->andWhere(['user_id' => $user_id]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);
    }
}