<?php

class Aulas
{
    public function aulaValida($aula)
    {
        if (isset($aula) && preg_match('/^[a-zA-Z0-9 -]{2,25}$/i', $aula)) {
            return true;
        }

        return false;
    }
}