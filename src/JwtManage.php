<?php

namespace Whereof\Jwt;

use Whereof\Jwt\JWT\JWTInterface;
use Whereof\Jwt\JWT\NamshiAdapter;
use Whereof\Jwt\Payload\PayloadFactory;

/**
 * Class JwtManage
 * @author zhiqiang
 * @package Whereof\Jwt
 */
class JwtManage
{
    /**
     * @var  JWTInterface
     */
    protected $jwt;

    /**
     * @var string
     */
    protected $secret;
    /**
     * @var string
     */
    protected $algo;
    /**
     * @var array
     */
    private $payload = [];
    /**
     * @var PayloadFactory
     */
    protected $payloadFactory;

    /**
     * @param JWTInterface|null $jwt
     * @param string $secret
     * @param string $algo
     */
    public function __construct(JWTInterface $jwt = null, string $secret = 'whereof-jwt-2022', string $algo = 'HS256')
    {
        $this->secret = $secret;
        $this->algo = $algo;
        $this->jwt = $jwt ?: new NamshiAdapter($this->secret, $this->algo);
        $this->payloadFactory = new PayloadFactory();
    }

    /**
     * @param string $sub
     * @return $this
     */
    public function sub(string $sub)
    {
        $this->payload['sub'] = $sub;
        return $this;
    }


    /**
     * @param array $payload
     * @return string
     * @throws Exceptions\JWTException
     */
    public function encode(array $payload = [])
    {
        $this->payload = $this->payloadFactory->make(array_merge($this->payload, $payload))->toArray();
        return $this->jwt->encode($this->payload);
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param $token
     * @return array
     * @throws Exceptions\TokenInvalidException
     */
    public function decode($token)
    {
        return $this->jwt->decode($token);
    }
}