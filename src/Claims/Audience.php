<?php

namespace Whereof\Jwt\Claims;

/**
 * Class Audience
 * @author zhiqiang
 * @package Whereof\Jwt\Claims
 */
class Audience extends Claim
{
    /**
     * The claim name.
     *
     * @var string
     */
    protected $name = 'aud';
}