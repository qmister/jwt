<?php

namespace Whereof\Jwt\Payload;

use BadMethodCallException;
use Whereof\Jwt\Claims\Factory;
use Whereof\Jwt\Support\Utils;
use Whereof\Jwt\Validator\PayloadValidator;

/**
 * Class PayloadFactory
 * @author zhiqiang
 * @package Whereof\Jwt\Payload
 */
class PayloadFactory
{
    /**
     * @var Factory
     */
    protected $claimFactory;
    /**
     * @var PayloadValidator
     */
    protected $validator;
    /**
     * @var bool
     */
    protected $refreshFlow = false;
    /**
     * @var int
     */
    protected $ttl = 60;
    /**
     * @var array
     */
    protected $defaultClaims = ['iss', 'iat', 'exp', 'nbf', 'jti'];
    /**
     * @var array
     */
    protected $claims;


    public function __construct()
    {
        $this->claimFactory = new Factory();
        $this->validator = new PayloadValidator();
    }

    /**
     * Create the Payload instance.
     *
     * @param array $customClaims
     * @return Payload
     */
    public function make(array $customClaims = [])
    {
        $claims = $this->buildClaims($customClaims)->resolveClaims();
        return new Payload($claims, $this->validator, $this->refreshFlow);
    }

    /**
     * Build the default claims.
     *
     * @param array $customClaims
     * @return $this
     */
    protected function buildClaims(array $customClaims)
    {
        // add any custom claims first
        $this->addClaims($customClaims);
        foreach ($this->defaultClaims as $claim) {
            if (!array_key_exists($claim, $customClaims)) {
                $this->addClaim($claim, $this->$claim());
            }
        }
        return $this;
    }

    /**
     * Add an array of claims to the Payload.
     *
     * @param array $claims
     * @return $this
     */
    public function addClaims(array $claims)
    {
        foreach ($claims as $name => $value) {
            $this->addClaim($name, $value);
        }
        return $this;
    }

    /**
     * Add a claim to the Payload.
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function addClaim($name, $value)
    {
        $this->claims[$name] = $value;
        return $this;
    }

    /**
     * Build out the Claim DTO's.
     * @return array
     */
    public function resolveClaims()
    {
        $resolved = [];
        foreach ($this->claims as $name => $value) {
            $resolved[] = $this->claimFactory->get($name, $value);
        }
        return $resolved;
    }

    /**
     * Set the Issuer (iss) claim.
     *
     * @return string
     */
    public function iss()
    {
        return $_SERVER['HTTP_HOST'] ?? '127.0.0.1';
    }

    /**
     * Set the Issued At (iat) claim.
     *
     * @return int
     */
    public function iat()
    {
        return Utils::now()->timestamp;
    }

    /**
     * Set the Expiration (exp) claim.
     *
     * @return int
     */
    public function exp()
    {
        return Utils::now()->addMinutes($this->ttl)->timestamp;
    }

    /**
     * Set the Not Before (nbf) claim.
     *
     * @return int
     */
    public function nbf()
    {
        return Utils::now()->timestamp;
    }

    /**
     * Set a unique id (jti) for the token.
     *
     * @return string
     */
    protected function jti()
    {
        return uniqid();
    }

    /**
     * Set the token ttl (in minutes).
     *
     * @param int $ttl
     * @return $this
     */
    public function setTTL($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Get the token ttl.
     *
     * @return int
     */
    public function getTTL()
    {
        return $this->ttl;
    }

    /**
     * Magically add a claim.
     *
     * @param string $method
     * @param array $parameters
     * @return PayloadFactory
     * @throws BadMethodCallException
     */
    public function __call(string $method, array $parameters)
    {
        $this->addClaim($method, $parameters[0]);
        return $this;
    }
}