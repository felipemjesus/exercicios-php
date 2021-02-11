<?php

namespace Alura\Leilao\Tests\Integration\Dao;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Infra\ConnectionCreator;
use Alura\Leilao\Model\Leilao;
use PDO;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{
    private PDO $con;

    protected function setUp(): void
    {
        $this->con = ConnectionCreator::getConnection();
        $this->con->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->con->rollBack();
    }

    public function testInsercaoEBuscaDevemFuncionar()
    {
        // arrange
        $leilao = new Leilao('Variante 0KM');
        $leilaoDao = new LeilaoDao($this->con);
        $leilaoDao->salva($leilao);

        // act
        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        // assert
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        self::assertSame('Variante 0KM', array_shift($leiloes)->recuperarDescricao());
    }
}
