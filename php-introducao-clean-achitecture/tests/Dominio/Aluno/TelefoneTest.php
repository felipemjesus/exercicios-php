<?php

namespace Alura\Arquitetura\Tests\Dominio\Aluno;

use Alura\Arquitetura\Dominio\Aluno\Telefone;
use PHPUnit\Framework\TestCase;

class TelefoneTest extends TestCase
{
    /**
     * @covers Telefone
     */
    public function testTelefoneDevePoderSerRepresentadoComoString()
    {
        $telefone = new Telefone('12', '123456789');

        $this->assertSame('(12) 123456789', (string) $telefone);
    }

    /**
     * @covers Telefone
     */
    public function testTelefoneComDddInvalidoNaoDeveExistir()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('DDD inválido');

        new Telefone('123', '123456789');
    }

    /**
     * @covers Telefone
     */
    public function testTelefoneComNumeroInvalidoNaoDeveExistir()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Número de telefone inválido');

        new Telefone('12', '1234567890');
    }
}
