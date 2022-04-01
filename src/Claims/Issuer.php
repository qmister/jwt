<?php

namespace Whereof\Jwt\Claims;

/**
 * Class Issuer
 * @author zhiqiang
 * @package Whereof\Jwt\Claims
 */
class Issuer extends Claim
{
    /**
     * The claim name.
     *
     * @var string
     */
    protected $name = 'iss';
}