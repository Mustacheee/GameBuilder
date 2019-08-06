<?php

namespace app\common\interfaces;

use app\models\AccessToken;

interface AuthTokenGeneratorInterface
{
    /**
     * Given an AccessToken model, create an AccessTokenInterface object.
     *
     * @param AccessToken $accessToken
     * @return AccessTokenInterface
     */
    public function generateToken(AccessToken $accessToken): AccessTokenInterface;

    /**
     * Parse a tokenized string and return an AccessTokenInterface object
     *
     * @param string $token
     * @return AccessTokenInterface
     */
    public function parseToken(string $token): AccessTokenInterface;

    /**
     * Validate an AccessTokenInterface object.
     *
     * @param AccessTokenInterface $token
     * @return mixed
     */
    public function validateToken(AccessTokenInterface $token);
}
