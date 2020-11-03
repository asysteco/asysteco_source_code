<?php

$dia = explode('/', $_POST['dia']);
$dia = $dia[2] . '-' . $dia[1] . '-' . $dia[0];
$diasemana = $class->getDate(strtotime($dia));
$diasemana = $diasemana['weekday'];

if ($class->validFormSQLDate($dia)) {
    if ($comprobacion = $class->query("SELECT Fecha, Festivo FROM Lectivos WHERE Fecha = '$dia' AND Festivo = 'no'")) {
        
        if ($comprobacion->num_rows > 0) {
            
            if ($res = $class->query("SELECT ID_PROFESOR, Fecha FROM Fichar WHERE ID_PROFESOR='$_POST[ID]' AND Fecha='$dia'")) {
                
                if ($res->num_rows == 0) {
                    
                    if ($response = $class->query("INSERT INTO Fichar (ID_PROFESOR, F_entrada, F_Salida, DIA_SEMANA, Fecha)
                    VALUES ('$_POST[ID]', '$_POST[horaentrada]', '$_POST[horasalida]', '$diasemana', '$dia')")) {
                        $OK = 'Registro insertado correctamente';
                    } else {
                        $ERROR = 'El profesor ya ha fichado en la fecha indicada.';
                    }
                } else {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
            } else {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
        } else {
            $ERROR = 'No se puede fichar en dias festivos.';
        }
    } else {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
} else {
    $ERROR = 'Error de Fecha';
}
