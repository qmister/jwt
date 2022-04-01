<?php

namespace Whereof\Jwt\Claims;

/**
 * Class Subject
 * @author zhiqiang
 * @package Whereof\Jwt\Claims
 */
class Subject extends Claim
{
    /**
     * The claim name.
     *
     * @var string
     */
    protected $name = 'sub';
}