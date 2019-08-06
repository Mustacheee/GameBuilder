<?php
namespace app\traits;

use Yii;
use yii\web\Response;

trait ResponseTrait
{
    /**
     * @param array $info
     * @return string
     */
    protected function returnSuccess(array $info = []): string
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return json_encode(array_merge(['status' => 'success'], $info));
    }

    /**
     * @param array $errors
     * @return string
     */
    protected function returnError(array $errors = []): string
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return json_encode(['status' => 'error', 'errors' => $errors]);
    }
}