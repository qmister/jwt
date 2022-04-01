<?php

namespace Whereof\Jwt\Claims;

/**
 * Class Custom
 * @author zhiqiang
 * @package Whereof\Jwt\Claims
 */
class Custom extends Claim
{
    /**
     * @param string  $name
     * @param mixed   $value
     */
    public function __construct($name, $value)
    {
        parent::__construct($value);
        $this->setName($name);
    }
}