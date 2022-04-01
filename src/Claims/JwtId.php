<?php

namespace Whereof\Jwt\Claims;

/**
 * Class JwtId
 * @author zhiqiang
 * @package Whereof\Jwt\Claims
 */
class JwtId extends Claim
{
    /**
     * The claim name.
     *
     * @var string
     */
    protected $name = 'jti';
}