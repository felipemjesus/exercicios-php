<?php

namespace Alura\Arquitetura\Infra\Aluno;

use Alura\Arquitetura\Dominio\Aluno\Aluno;
use Alura\Arquitetura\Dominio\Aluno\AlunoNaoEncontrado;
use Alura\Arquitetura\Dominio\Aluno\RepositorioDeAluno;
use Alura\Arquitetura\Dominio\Cpf;
use PHPUnit\Util\Exception;

class RepositorioDeAlunoEmMemoria implements RepositorioDeAluno
{
    /**
     * @var Aluno[]
     */
    private array $alunos = [];

    public function adicionar(Aluno $aluno): void
    {
        $this->alunos[] = $aluno;
    }

    public function buscarPorCpf(Cpf $cpf): Aluno
    {
        $alunosFilstrados = array_filter($this->alunos, fn ($aluno) => $aluno->cpf() == (string) $cpf);

        if (count($alunosFilstrados) === 0) {
            throw new AlunoNaoEncontrado($cpf);
        }

        if (count($alunosFilstrados) > 1) {
            throw new Exception('Existem vários alunos com o mesmo CPF');
        }

        return $alunosFilstrados[0];
    }

    /**
     * @return Aluno[]
     */
    public function buscarTodos(): array
    {
        return $this->alunos;
    }
}
