<?php
// variables a declarar
$action = $_POST['action'];

if ($action === 'change-lectivo') {
    $MSG = "cambio realizado";
    $sql = "UPDATE Lectivos SET Festivo WHERE ";

} elseif ($action === 'change-festivo') {
    $MSG = "cambio realizado";
}