<?php

namespace Whereof\Jwt\JWT;

use Exception;
use Namshi\JOSE\JWS;
use Whereof\Jwt\Exceptions\JWTException;
use Whereof\Jwt\Exceptions\TokenInvalidException;

/**
 * Class NamshiAdapter
 * @author zhiqiang
 * @package Whereof\Jwt\JWT
 */
class NamshiAdapter extends JWTProvider implements JWTInterface
{
    /**
     * @var JWS
     */
    protected $jws;

    /**
     * @param string $secret
     * @param string $algo
     * @param null $driver
     */
    public function __construct(string $secret, string $algo, $driver = null)
    {
        parent::__construct($secret, $algo);

        $this->jws = $driver ?: new JWS(['typ' => 'JWT', 'alg' => $algo]);
    }

    /**
     * Create a JSON Web Token.
     * @param array $payload
     * @return string
     * @throws JWTException
     */
    public function encode(array $payload)
    {
        try {
            $this->jws->setPayload($payload)->sign($this->secret);
            return $this->jws->getTokenString();
        } catch (Exception $e) {
            throw new JWTException('Could not create token: ' . $e->getMessage());
        }
    }

    /**
     * Decode a JSON Web Token.
     * @param $token
     * @return array
     * @throws TokenInvalidException
     */
    public function decode($token)
    {
        try {
            $jws = JWS::load($token);
        } catch (Exception $e) {
            throw new TokenInvalidException('Could not decode token: ' . $e->getMessage());
        }
        if (!$jws->verify($this->secret, $this->algo)) {
            throw new TokenInvalidException('Token Signature could not be verified.');
        }
        return $jws->getPayload();
    }
}