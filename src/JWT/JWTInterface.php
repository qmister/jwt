<?php

namespace Whereof\Jwt\JWT;

/**
 * Interface JWTInterface
 * @author zhiqiang
 * @package Whereof\Jwt\JWT
 */
interface JWTInterface
{
    /**
     * @param  array  $payload
     * @return string
     */
    public function encode(array $payload);

    /**
     * @param  string  $token
     * @return array
     */
    public function decode($token);
}