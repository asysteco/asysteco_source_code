<?php

class ImportHorario
{
    private string $grupo;
    private string $iniciales;
    private string $aula;
    private int $dia;
    private int $hora;

    private bool $rowStatus = true;
    private array $filaCompleta;

    public function __construct(
        $grupo,
        $iniciales,
        $aula,
        $dia,
        $hora)
    {
        $this->setGrupo($grupo);
        $this->setIniciales($iniciales);
        $this->setAula($aula);
        $this->setDia($dia);
        $this->setHora($hora);
        $this->setFilaCompleta();
    }

    public function grupo()
    {
        return $this->grupo;
    }

    public function setGrupo($grupo): void
    {
        $grupoValido = "";
        if ($this->grupoValido($grupo)) {
            $grupoValido = strtoupper($grupo);
        }

        $this->grupo = $grupoValido;
    }
    
    public function iniciales()
    {
        return $this->iniciales;
    }

    public function setIniciales($iniciales): void
    {
        $inicialesValidas = "";
        if ($this->inicialesValidas($iniciales)) {
            $inicialesValidas = strtoupper($iniciales);
        }

        $this->iniciales = $inicialesValidas;
    }
    
    public function aula()
    {
        return $this->aula;
    }

    public function setAula($aula): void
    {
        $aulaValida = "";
        if ($this->aulaValida($aula)) {
            $aulaValida = strtoupper($aula);
        }

        $this->aula = $aulaValida;
    }
    
    public function dia()
    {
        return $this->dia;
    }

    public function setDia($dia): void
    {
        $diaValido = "";
        if ($this->diaValido($dia)) {
            $diaValido = $dia;
        }

        $this->dia = (int) $diaValido;
    }
    
    public function hora()
    {
        return $this->hora;
    }

    public function setHora($hora): void
    {
        $horaValida = "";
        if ($this->horaValida($hora)) {
            $horaValida = $hora;
        }

        $this->hora = (int) $horaValida;
    }

    public function filaCompleta(): array
    {
        return $this->filaCompleta;
    }

    public function setFilaCompleta(): void
    {
        $filaCompleta = [
            $this->grupo(),
            $this->iniciales(),
            $this->aula(),
            $this->dia(),
            $this->hora(),
        ];

        $this->filaCompleta = $filaCompleta;
    }

    public function rowStatus(): bool {
        return $this->rowStatus;
    }

    public function setRowStatusFalse(): void {
        $this->rowStatus = false;
    }

    public function grupoValido($grupo)
    {
        if (isset($grupo) && preg_match('/^[a-zA-Z0-9 -]{2,25}$/i', $grupo)) {
            return true;
        }

        $this->setRowStatusFalse();
        return false;
    }

    public function inicialesValidas($iniciales)
    {
        if (isset($iniciales) && preg_match('/^[a-zA-Z]{2,5}$/i', $iniciales)) {
            return true;
        }

        $this->setRowStatusFalse();
        return false;
    }

    public function aulaValida($aula)
    {
        if (isset($aula) && preg_match('/^[a-zA-ZäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ0-9 -]{2,25}$/i', $aula)) {
            return true;
        }

        $this->setRowStatusFalse();
        return false;
    }

    public function diaValido($dia)
    {
        if (isset($dia) && preg_match('/^[1-5]$/', $dia)) {
            return true;
        }

        $this->setRowStatusFalse();
        return false;
    }

    public function horaValida($hora)
    {
        if (isset($hora) && preg_match('/^([12][0-9])|([1-9])$/', $hora)) {
            return true;
        }

        $this->setRowStatusFalse();
        return false;
    }
}
