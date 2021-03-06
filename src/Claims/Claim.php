<?php

namespace Whereof\Jwt\Claims;

use Whereof\Jwt\Exceptions\InvalidClaimException;

/**
 * Class Claim
 * @author zhiqiang
 * @package Whereof\Jwt\Claims
 */
abstract class Claim implements ClaimInterface
{
    /**
     * The claim name.
     *
     * @var string
     */
    protected $name;

    /**
     * The claim value.
     *
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * Set the claim value, and call a validate method if available.
     *
     * @param $value
     * @return $this
     * @throws InvalidClaimException
     */
    public function setValue($value)
    {
        if (!$this->validate($value)) {
            throw new InvalidClaimException('Invalid value provided for claim "' . $this->getName() . '": ' . $value);
        }
        $this->value = $value;
        return $this;
    }

    /**
     * Get the claim value.
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the claim name.
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    /**
     * Get the claim name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Validate the Claim value.
     *
     * @param  $value
     * @return bool
     */
    protected function validate($value)
    {
        return true;
    }

    /**
     * Build a key value array comprising of the claim name and value.
     *
     * @return array
     */
    public function toArray()
    {
        return [$this->getName() => $this->getValue()];
    }

    /**
     * Get the claim as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES);
    }
}