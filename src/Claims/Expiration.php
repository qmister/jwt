<?php

namespace Whereof\Jwt\Claims;

/**
 * Class Expiration
 * @author zhiqiang
 * @package Whereof\Jwt\Claims
 */
class Expiration extends Claim
{
    /**
     * The claim name.
     *
     * @var string
     */
    protected $name = 'exp';

    /**
     * Validate the expiry claim.
     *
     * @param mixed $value
     * @return bool
     */
    protected function validate($value)
    {
        return is_numeric($value);
    }
}