<?php

namespace Alura\Arquitetura\Tests\Dominio;

use Alura\Arquitetura\Dominio\Cpf;
use PHPUnit\Framework\TestCase;

class CpfTest extends TestCase
{
    /**
     * @covers Cpf
     */
    public function testComNumeroNoFormatoInvalidoNaoDevePoderExistir()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Cpf('12345678900');
    }

    /**
     * @covers Cpf
     */
    public function testCpfDevePoderSerRepresentadoComoString()
    {
        $cpf = new Cpf('123.456.789-00');

        $this->assertSame('123.456.789-00', (string) $cpf);
    }
}
