<?php

namespace Alura\Arquitetura\Tests\Dominio;

use Alura\Arquitetura\Dominio\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @covers Email
     */
    public function testEmailNoFormatoInvalidoNaoDevePoderExistir()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Email('email');
    }

    /**
     * @covers Email
     */
    public function testEmailDevePoderSerRepresentadoComoString()
    {
        $email = new Email('email@test.com');

        $this->assertSame('email@test.com', (string) $email);
    }
}
