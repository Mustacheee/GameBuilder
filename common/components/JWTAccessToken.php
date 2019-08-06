<?php

namespace app\common\components;

use app\common\interfaces\AccessTokenInterface;
use Lcobucci\JWT\Token;
use Yii;

class JWTAccessToken implements AccessTokenInterface
{
    /** @var Token $token */
    private $token;

    /**
     * JWTAccessToken constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = Yii::$app->jwt->loadToken($token, true, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserId(): int
    {
        return (int) $this->token->getClaim('user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthToken(): string
    {
        return (string) $this->token->getClaim('access_token');
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return strval($this->token);
    }
}