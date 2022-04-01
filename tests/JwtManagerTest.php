<?php

namespace whereof\Tests;

use PHPUnit\Framework\TestCase;
use Whereof\Jwt\Exceptions\JWTException;
use Whereof\Jwt\Exceptions\TokenInvalidException;
use Whereof\Jwt\JwtManage;

/**
 * Class JwtManagerTest
 * @author zhiqiang
 * @package whereof\Tests
 */
class JwtManagerTest extends TestCase
{
    /**
     * @return void
     * @throws JWTException
     * @throws TokenInvalidException
     */
    public function testJwtSub()
    {
        $jwt = (new JwtManage())->sub('1');
        $token = $jwt->encode();
        $this->assertIsString($token);
        $payload = $jwt->decode($token);
        $this->assertEquals($jwt->getPayload(), $payload);
    }

    /**
     * @return void
     * @throws JWTException
     * @throws TokenInvalidException
     */
    public function testJwtEncode()
    {
        $jwt = new JwtManage();
        $token = $jwt->encode(['sub' => 1]);
        $this->assertIsString($token);
        $payload = $jwt->decode($token);
        $this->assertEquals($jwt->getPayload(), $payload);
    }
}