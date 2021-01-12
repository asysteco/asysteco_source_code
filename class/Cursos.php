<?php

class Cursos
{
    public function cursoValido($curso)
    {
        if (isset($curso) && preg_match('/^[a-zA-ZäÄëËïÏöÖüÜáéíóúáéíóúÁÉÍÓÚÂÊÎÔÛâêîôûàèìòùÀÈÌÒÙñÑ0-9 -]{2,25}$/i', $curso)) {
            return true;
        }

        return false;
    }
}