<?php

namespace Whereof\Jwt\Claims;

/**
 * Class Factory
 * @author zhiqiang
 * @package Whereof\Jwt\Claims
 */
class Factory
{
    /**
     * @var array
     */
    protected static $classMap = [
        'aud' => Audience::class,
        'exp' => Expiration::class,
        'iat' => IssuedAt::class,
        'iss' => Issuer::class,
        'jti' => JwtId::class,
        'nbf' => NotBefore::class,
        'sub' => Subject::class,
    ];

    /**
     * Get the instance of the claim when passing the name and value.
     *
     * @param string $name
     * @param mixed $value
     * @return Custom
     */
    public function get($name, $value)
    {
        if ($this->has($name)) {
            return new self::$classMap[$name]($value);
        }
        return new Custom($name, $value);
    }

    /**
     * Check whether the claim exists.
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, self::$classMap);
    }
}