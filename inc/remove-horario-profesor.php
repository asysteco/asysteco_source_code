<?php

$profesor = $_GET['profesor'];

if($class->query("DELETE FROM $class->horarios WHERE ID_PROFESOR='$profesor'"))
{
    $class->marcajes($profesor, 'remove');
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}