<?php

if(isset($_GET['Numero']))
{
    $edificio = 'en el edificio ' . $_GET['Numero'];
}
else
{
    $edificio = '';
}
echo "<h2>Aulas sin profesor $edificio</h2>";
include_once($dirs['inc'] . 'guardias.php');