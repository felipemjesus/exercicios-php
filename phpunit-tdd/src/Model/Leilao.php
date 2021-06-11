<?php

namespace Alura\Leilao\Model;

use DomainException;

class Leilao
{
    /** @var Lance[] */
    private array $lances;
    /** @var string */
    private string $descricao;
    private bool $finalizado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->finalizado = false;
    }

    public function recebeLance(Lance $lance): void
    {
        if (!empty($this->lances) && $this->ehUltimoUsuario($lance)) {
            throw new DomainException('Usuário não pode propor 2 lances consecutivos.');
        }

        $totalLancesUsuario = $this->quantidadeLancesPorUsuario($lance->getUsuario());
        if ($totalLancesUsuario >= 5) {
            throw new DomainException('Usuário não pode propor mais de 5 lances por leilão.');
        }

        $this->lances[] = $lance;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    public function finaliza(): void
    {
        $this->finalizado = true;
    }

    public function estaFinalizado(): bool
    {
        return $this->finalizado;
    }

    /**
     * @param Lance $lance
     * @return bool
     */
    private function ehUltimoUsuario(Lance $lance): bool
    {
        $ultimoLance = end($this->lances);

        return $lance->getUsuario() === $ultimoLance->getUsuario();
    }

    /**
     * @param Usuario $usuario
     * @return int
     */
    private function quantidadeLancesPorUsuario(Usuario $usuario): int
    {
        return array_reduce(
            $this->lances,
            function (int $totalAcumulado, Lance $lance) use ($usuario) {
                if ($lance->getUsuario() === $usuario) {
                    return $totalAcumulado + 1;
                }

                return $totalAcumulado;
            },
            0
        );
    }
}
