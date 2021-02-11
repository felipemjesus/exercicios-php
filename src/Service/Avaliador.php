<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Model\Leilao;

class Avaliador
{
    private float $maiorValor;

    public function avalia(Leilao $leilao): void
    {
        $lances = $leilao->getLances();
        $ultimoLance = end($lances);
        $this->maiorValor = $ultimoLance->getValor();
    }

    public function getMaiorValor(): float
    {
        return $this->maiorValor;
    }
}
