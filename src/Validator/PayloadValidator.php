<?php

namespace Whereof\Jwt\Validator;

use Whereof\Jwt\Exceptions\TokenExpiredException;
use Whereof\Jwt\Exceptions\TokenInvalidException;
use Whereof\Jwt\Support\Utils;

/**
 * Class PayloadValidator
 * @author zhiqiang
 * @package Whereof\Jwt\Validator
 */
class PayloadValidator
{
    /**
     * @var array
     */
    protected $requiredClaims = ['iss', 'iat', 'exp', 'nbf', 'sub', 'jti'];

    /**
     * @var int
     */
    protected $refreshTTL = 20160;
    /**
     * @var bool
     */
    protected $refreshFlow;


    /**
     * Run the validations on the payload array.
     * @param $value
     * @return void
     * @throws TokenExpiredException
     * @throws TokenInvalidException
     */
    public function check($value)
    {
        $this->validateStructure($value);

        if (!$this->refreshFlow) {
            $this->validateTimestamps($value);
        } else {
            $this->validateRefresh($value);
        }
    }

    /**
     * Ensure the payload contains the required claims and
     * the claims have the relevant type.
     *
     * @param array $payload
     * @return bool
     * @throws TokenInvalidException
     */
    protected function validateStructure(array $payload)
    {
        if (count(array_diff($this->requiredClaims, array_keys($payload))) !== 0) {
            throw new TokenInvalidException('JWT payload does not contain the required claims');
        }
        return true;
    }

    /**
     * Validate the payload timestamps.
     * @param array $payload
     * @return bool
     * @throws TokenInvalidException
     * @throws TokenExpiredException
     */
    protected function validateTimestamps(array $payload)
    {
        if (isset($payload['nbf']) && Utils::timestamp($payload['nbf'])->isFuture()) {
            throw new TokenInvalidException('Not Before (nbf) timestamp cannot be in the future', 400);
        }
        if (isset($payload['iat']) && Utils::timestamp($payload['iat'])->isFuture()) {
            throw new TokenInvalidException('Issued At (iat) timestamp cannot be in the future', 400);
        }
        if (Utils::timestamp($payload['exp'])->isPast()) {
            throw new TokenExpiredException('Token has expired');
        }
        return true;
    }


    /**
     * @param array $payload
     * @return bool
     * @throws TokenExpiredException
     */
    protected function validateRefresh(array $payload)
    {
        if (isset($payload['iat']) && Utils::timestamp($payload['iat'])->addMinutes($this->refreshTTL)->isPast()) {
            throw new TokenExpiredException('Token has expired and can no longer be refreshed', 400);
        }
        return true;
    }


    /**
     * Set the required claims.
     * @param array $claims
     * @return $this
     */
    public function setRequiredClaims(array $claims)
    {
        $this->requiredClaims = $claims;
        return $this;
    }

    /**
     * Set the refresh ttl.
     * @param int $ttl
     */
    public function setRefreshTTL($ttl)
    {
        $this->refreshTTL = $ttl;
        return $this;
    }

    /**
     * Set the refresh flow.
     *
     * @param bool $refreshFlow
     * @return $this
     */
    public function setRefreshFlow($refreshFlow = true)
    {
        $this->refreshFlow = $refreshFlow;
        return $this;
    }

}