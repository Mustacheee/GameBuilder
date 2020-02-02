<?php
namespace app\traits;

use Yii;
use yii\web\Response;

trait ResponseTrait
{
    /**
     * @param array $info
     * @param int $statusCode
     * @return string
     */
    protected function returnSuccess(array $info = [], $statusCode = 200): string
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->statusCode = $statusCode;
        return json_encode(array_merge(['status' => 'success'], $info));
    }

    /**
     * @param array $errors
     * @param int $statusCode
     * @return string
     */
    protected function returnError(array $errors = [], $statusCode = 400): string
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->statusCode = $statusCode;
        return json_encode(['status' => 'error', 'errors' => $errors]);
    }
}