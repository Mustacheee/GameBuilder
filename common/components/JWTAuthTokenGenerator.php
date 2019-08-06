<?php

namespace app\common\components;

use app\common\interfaces\AccessTokenInterface;
use app\common\interfaces\AuthTokenGeneratorInterface;
use app\models\AccessToken;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use sizeg\jwt\Jwt;
use Yii;

class JWTAuthTokenGenerator implements AuthTokenGeneratorInterface
{
    private const ISSUER = 'http://api.growie.com'; //TODO: Update this
    private const FIELD_USER_ID = 'user_id';
    private const FIELD_ACCESS_TOKEN = 'access_token';

    /**
     * {@inheritdoc}
     */
    public function generateToken(AccessToken $accessToken): AccessTokenInterface
    {
        $id = uniqid();
        $now = time();

        /** @var JWT $jwt */
        $jwt = Yii::$app->jwt;

        /** @var Token $token */
        $token = $jwt->getBuilder()
            ->setIssuer(self::ISSUER)
            ->setAudience(self::ISSUER)
            ->setId($id, true)
            ->setIssuedAt($now)
            ->set(self::FIELD_USER_ID, $accessToken->user_id)
            ->set(self::FIELD_ACCESS_TOKEN, $accessToken->token)
            ->sign(new Sha256(), 'secret')
            ->getToken();

        return new JWTAccessToken((string) $token);
    }

    /**
     * {@inheritdoc}
     */
    public function parseToken(string $token): AccessTokenInterface
    {
        return new JWTAccessToken($token);
    }

    /**
     * {@inheritdoc}
     */
    public function validateToken(?AccessTokenInterface $token): bool
    {
        if (is_null($token)|| is_null($token->getUserId()) || is_null($token->getAuthToken())) {
            return false;
        }

        $accessToken = AccessToken::find()
            ->andWhere(['user_id' => $token->getUserId(), 'token' => $token->getAuthToken()])
            ->andWhere(['>=', 'expires_at', date('Y-m-d H:i:s', time())])
            ->one();

        return !is_null($accessToken);
    }
}