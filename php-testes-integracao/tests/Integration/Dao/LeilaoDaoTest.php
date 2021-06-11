<?php

namespace Alura\Leilao\Tests\Integration\Dao;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Model\Leilao;
use PDO;
use PHPUnit\Framework\TestCase;

class LeilaoDaoTest extends TestCase
{
    private static PDO $con;

    public static function setUpBeforeClass(): void
    {
        self::$con = new PDO('sqlite::memory:');
        self::$con->exec('create table leiloes (
            id INTEGER primary key,
            descricao TEXT,
            finalizado BOOL,
            dataInicio TEXT
        );');
    }

    protected function setUp(): void
    {
        self::$con->beginTransaction();
    }

    protected function tearDown(): void
    {
        self::$con->rollBack();
    }

    /**
     * @dataProvider leiloes
     */
    public function testBuscaLeiloesNaoFinalizados(array $leiloes)
    {
        // arrange
        $leilaoDao = new LeilaoDao(self::$con);
        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
        }

        // act
        $leiloes = $leilaoDao->recuperarNaoFinalizados();

        // assert
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        /** @var Leilao $primeiroLeilao */
        $primeiroLeilao = array_shift($leiloes);
        self::assertSame('Variante 0KM', $primeiroLeilao->recuperarDescricao());
        self::assertFalse($primeiroLeilao->estaFinalizado());
    }

    /**
     * @dataProvider leiloes
     */
    public function testBuscaLeiloesFinalizados(array $leiloes)
    {
        // arrange
        $leilaoDao = new LeilaoDao(self::$con);
        foreach ($leiloes as $leilao) {
            $leilaoDao->salva($leilao);
        }

        // act
        $leiloes = $leilaoDao->recuperarFinalizados();

        // assert
        self::assertCount(1, $leiloes);
        self::assertContainsOnlyInstancesOf(Leilao::class, $leiloes);
        /** @var Leilao $primeiroLeilao */
        $primeiroLeilao = array_shift($leiloes);
        self::assertSame('Fiat 147 0KM', $primeiroLeilao->recuperarDescricao());
        self::assertTrue($primeiroLeilao->estaFinalizado());
    }

    public function testAoAtualizarLeilaoStatusDeveSerAlterado()
    {
        // arrange
        $leilaoDao = new LeilaoDao(self::$con);
        $leilao = new Leilao('Brasília Amarela');
        $leilao = $leilaoDao->salva($leilao);

        // act
        $leilao->finaliza();
        $leilaoDao->atualiza($leilao);

        // assert
        $leiloes = $leilaoDao->recuperarFinalizados();
        self::assertCount(1, $leiloes);
        /** @var Leilao $primeiroLeilao */
        $primeiroLeilao = array_shift($leiloes);
        self::assertSame('Brasília Amarela', $primeiroLeilao->recuperarDescricao());
        self::assertTrue($primeiroLeilao->estaFinalizado());
    }

    public function leiloes()
    {
        $leilaoNaoFinalizado = new Leilao('Variante 0KM');

        $leilaoFinalizado = new Leilao('Fiat 147 0KM');
        $leilaoFinalizado->finaliza();

        return [
            [
                [$leilaoNaoFinalizado, $leilaoFinalizado]
            ]
        ];
    }
}
