<?php

namespace app\common\interfaces;

interface AccessTokenInterface
{
    /**
     * AccessTokenInterface constructor.
     * @param string $token
     */
    public function __construct(string $token);

    /**
     * Return a string representation of the AccessToken
     *
     * @return string
     */
    public function toString(): string;

    /**
     * Get the User ID from the access token
     *
     * @return int
     */
    public function getUserId(): int;

    /**
     * Retrieve the Auth Key from the Access Token
     *
     * @return string
     */
    public function getAuthToken(): string;
}