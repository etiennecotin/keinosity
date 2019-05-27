<?php

namespace App\Tests\Servicse;

use App\Utils\TokenModel;
use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    /** @var TokenModel */
    private $tokenModel;

    public function setUp()
    {
        $this->tokenModel = new TokenModel();
    }

    public function testGenerateToken()
    {
        $token = $this->tokenModel->generateToken();
        $this->assertRegExp('/^[a-zA-Z0-9_\-]{43}$/', $token);
    }
}
