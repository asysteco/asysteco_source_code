<?php
$dia = explode('/', $_POST['dia']);
$dia = $dia[2] . '-' . $dia[1] . '-' . $dia[0];
$diasemana = $class->getDate(strtotime($dia));
$diasemana = $diasemana['weekday'];
if($class->validFormSQLDate($dia))
{
    if($response = $class->query("INSERT INTO fichar (ID_PROFESOR, F_entrada, DIA_SEMANA, Fecha)
    VALUES ('$_POST[ID]', '$_POST[hora]', '$diasemana', '$dia')"))
    {
        $MSG = 'Registro insertado correctamente';
    }
    else
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}
else
{
    $ERR_MSG = 'Error de Fecha';
    echo "$ERR_MSG";
}


?>
            