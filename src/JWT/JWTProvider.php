<?php

namespace Whereof\Jwt\JWT;

/**
 * Class JWTProvider
 * @author zhiqiang
 * @package Whereof\Jwt\JWT
 */
abstract class JWTProvider
{
    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $algo;

    /**
     * @param string $secret
     * @param string $algo
     */
    public function __construct(string $secret, string $algo = 'HS256')
    {
        $this->secret = $secret;
        $this->algo = $algo;
    }

    /**
     * Set the algorithm used to sign the token.
     *
     * @param string $algo
     * @return self
     */
    public function setAlgo($algo)
    {
        $this->algo = $algo;

        return $this;
    }

    /**
     * Get the algorithm used to sign the token.
     *
     * @return string
     */
    public function getAlgo()
    {
        return $this->algo;
    }

    /**
     * Set the secret used to sign the token.
     *
     * @param string $secret
     *
     * @return $this
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get the secret used to sign the token.
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }
}