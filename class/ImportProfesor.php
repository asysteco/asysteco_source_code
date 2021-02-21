<?php

class ImportProfesor
{
    private string $iniciales;
    private string $nombre;
    private string $tutor;

    private bool $rowStatus = true;
    private array $filaCompleta;

    public function __construct(
        $iniciales,
        $nombre,
        $tutor)
    {
        $this->setIniciales($iniciales);
        $this->setNombre($nombre);
        $this->setTutor($tutor);
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
    
    public function nombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre): void
    {
        $nombreValido = "";
        if ($this->nombreValido($nombre)) {
            $nombreValido = $nombre;
        }

        $this->nombre = $nombreValido;
    }
    
    public function tutor()
    {
        return $this->tutor;
    }

    public function setTutor($tutor): void
    {
        $tutorValido = "";
        if ($this->tutorValido($tutor)) {
            $tutorValido = $tutor;
        }

        $this->tutor = $tutorValido;
    }

    public function filaCompleta(): array
    {
        return $this->filaCompleta;
    }

    public function setFilaCompleta(): void
    {
        $filaCompleta = [
            $this->iniciales(),
            $this->nombre(),
            $this->tutor()
        ];

        $this->filaCompleta = $filaCompleta;
    }

    public function rowStatus(): bool {
        return $this->rowStatus;
    }

    public function setRowStatusFalse(): void {
        $this->rowStatus = false;
    }

    public function inicialesValidas($iniciales)
    {
        if (isset($iniciales) && preg_match('/^[a-zA-Z]{2,5}$/i', $iniciales)) {
            return true;
        }

        $this->setRowStatusFalse();
        return false;
    }

    public function nombreValido($nombre)
    {
        if (isset($nombre) && preg_match('/^[ a-zäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ.-]{2,60}$/i', $nombre)) {
            return true;
        }

        $this->setRowStatusFalse();
        return false;
    }

    public function tutorValido($tutor)
    {
        if (isset($tutor) && preg_match('/^([Nn][Oo])|([a-zA-Z0-9 -]{2,25})$/i', $tutor)) {
            return true;
        }

        $this->setRowStatusFalse();
        return false;
    }
}
