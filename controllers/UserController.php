<?php

namespace app\controllers;

use app\models\Follower;
use app\models\Review;
use app\models\User;
use app\traits\ResponseTrait;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\VerbFilter;

class UserController extends AuthenticatedRestController
{
    use ResponseTrait;
    public $modelClass = User::class;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        return array_merge($behaviors, [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'follow' => ['post'],
                    'unfollow' => ['post'],
                    'followers' => ['get'],
                ],
            ],
        ]);
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['view']);
        return array_merge($actions, [
            'follow',
            'unfollow',
            'followers',
        ]);
    }

    public function actionView($id)
    {
        $user = (new Query())
            ->select([
                'first_name' => 'u.first_name',
                'last_name' => 'u.last_name',
                'description' => 'u.description',
                'review_count' => new Expression('COUNT(DISTINCT r.id)'),
                'follower_count' => new Expression('COUNT(f.id)'),
            ])
            ->from(['u' => User::tableName()])
            ->leftJoin(['r' => Review::tableName()], 'r.user_id = u.id')
            ->leftJoin(['f' => Follower::tableName()], 'f.followed_id = u.id')
            ->groupBy(['u.id'])
            ->andWhere(['u.id' => $id])
            ->one();

        if (empty($user)) {
            Yii::$app->response->setStatusCode(404);
            return $this->returnError(['message' => 'User not found.']);
        }

        return $this->returnSuccess($user);
    }

    public function actionFollowers()
    {
        $user_id = Yii::$app->request->get('user_id', $this->getLoggedInUser()->id);

        $followers = (new Query())
            ->select([
                'first_name' => 'u.first_name',
                'last_name' => 'u.last_name',
            ])
            ->from(['u' => User::tableName()])
            ->innerJoin(['f' => Follower::tableName()], 'f.follower_id = u.id')
            ->andWhere(['f.followed_id' => $user_id])
            ->all();

        return $this->returnSuccess(['followers' => $followers]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionFollow($id)
    {
        /** @var User $loggedInUser */
        $loggedInUser = Yii::$app->user->identity;
        $result = $loggedInUser->follow($id);
        if ($result === true) {
            return $this->returnSuccess();
        }
        return $this->returnError($result);
    }

    /**
     * POST request to follow a user
     *
     * @param $id User to unfollow
     * @return string
     */
    public function actionUnfollow($id)
    {
        $this->getLoggedInUser()->unfollow($id);
        return $this->returnSuccess();
    }
}