<?php

if(isset($_POST['fecha']))
{
    if($class->validFormDate($_POST['fecha']))
    {
        $fileName = $_FILES["file"]["tmp_name"];
        if ($_FILES["file"]["size"] > 0)
        { 
            $file = fopen($fileName, "r");
            $row = 1;
            while (($column = fgetcsv($file, 10000, ";")) !== FALSE)
            {
                $columnas = count($column);
                if($columnas == 7)
                {
                    if($row == 1)
                    {
                        foreach($column as $dato)
                        {
                            if(preg_match('/^[A-Z]+$/i', $dato))
                            {
                                $cabecera = true;
                            }
                            else
                            {
                                $cabecera = false;
                            }
                        }
                        if($cabecera)
                        {
                            $row++;
                            continue;
                        }
                    }
                    
                    $horarioID = "";
                    if (isset($column[0])) {
                        $column[0] = preg_replace('/(\")|(\s)/', '', $column[0]);
                        $horarioID = mysqli_real_escape_string($class->conex, utf8_encode($column[0]));
                    }
                    $Grupo = "";
                    if (isset($column[1])) {
                        $column[1] = preg_replace('/(\")|(\s)/', '', $column[1]);
                        $Grupo = mysqli_real_escape_string($class->conex, utf8_encode($column[1]));
                    }
                    $Iniciales = "";
                    if (isset($column[2])) {
                        $column[2] = preg_replace('/(\")|(\s)/', '', $column[2]);
                        $Iniciales = mysqli_real_escape_string($class->conex, utf8_encode($column[2]));
                    }
                    $Aula = "";
                    if (isset($column[4])) {
                        $column[4] = preg_replace('/(\")|(\s)/', '', $column[4]);
                        $Aula = mysqli_real_escape_string($class->conex, utf8_encode($column[4]));
                    }
                    $Diasemana = "";
                    if (isset($column[5])) {
                        $column[5] = preg_replace('/(\")|(\s)/', '', $column[5]);
                        $Diasemana = mysqli_real_escape_string($class->conex, utf8_encode($column[5]));
                    }
                    $Hora_tipo = "";
                    if (isset($column[6])) {
                        $column[6] = preg_replace('/(\")|(\s)/', '', $column[6]);
                        $Hora_tipo = mysqli_real_escape_string($class->conex, utf8_encode($column[6]));
                    }
                    $Edificio = "";
                    if (isset($Aula)) {
                        $sed = preg_split('//', $Aula, -1, PREG_SPLIT_NO_EMPTY);
                        $Edificio = mysqli_real_escape_string($class->conex, utf8_encode($sed[2]));
                        preg_match('/^[0-9]$/', $Edificio) ? $Edificio = $Edificio : $Edificio=0;
                    }
                    if($Edificio == 1)
                    {
                        $Hora_tipo .= 'C';
                    }
                    else
                    {
                        $Hora_tipo .= 'M';
                    }
                    $response = $class->query("SELECT ID FROM Profesores WHERE Iniciales='$Iniciales'");
                    $IDPROFESOR = $response->fetch_assoc();
                    $IDPROFESOR = $IDPROFESOR['ID'];
                    $Hora_entrada = "00:00:00";
                    $Hora_salida = "00:00:00";
                    $sep = preg_split('/\//', $_POST['fecha']);
                    $dia = $sep[0];
                    $m = $sep[1];
                    $Y = $sep[2];
                    $Hora_incorpora = "$Y-$m-$dia";
                    if($class->query("INSERT into T_horarios (ID_PROFESOR, Dia, HORA_TIPO, Edificio, Aula, Grupo, Hora_entrada, Hora_salida, Fecha_incorpora)
                    values (
                        '$IDPROFESOR',
                        '$Diasemana',
                        '$Hora_tipo',
                        '$Edificio',
                        '$Aula',
                        '$Grupo',
                        '$Hora_entrada',
                        '$Hora_salida',
                        '$Hora_incorpora')"))
                    {
                        $MSG = "Horarios importados correctamente.<br>";
                        $MSG .= "Entrarán en vigor el día $_POST[fecha]";
                    }
                    else
                    {
                        $ERR_MSG = "<br>Error al importar datos desde CSV.<br>";
                        $ERR_MSG .= $class->ERR_ASYSTECO;
                    }
                }
                else
                {
                    $ERR_MSG = "<br>Error en Fichero, no es el esperado.<br>";
                }
            }
        }
        else
        {
            $ERR_MSG = "El fichero está vacío.";
        }
    }
}
else
{
    $fileName = $_FILES["file"]["tmp_name"];
    if ($_FILES["file"]["size"] > 0)
    { 
        $file = fopen($fileName, "r");
        $row = 1;
        while (($column = fgetcsv($file, 10000, ";")) !== FALSE)
        {
            $columnas = count($column);
            if($columnas == 7)
            {
                if($row == 1)
                {
                    foreach($column as $dato)
                    {
                        if(preg_match('/^[A-Z]+$/i', $dato))
                        {
                            $cabecera = true;
                        }
                        else
                        {
                            $cabecera = false;
                        }
                    }
                    if($cabecera)
                    {
                        $row++;
                        continue;
                    }
                }

                $horarioID = "";
                if (isset($column[0])) {
                    $column[0] = preg_replace('/(\")|(\s)/', '', $column[0]);
                    $horarioID = mysqli_real_escape_string($class->conex, utf8_encode($column[0]));
                }
                $Grupo = "";
                if (isset($column[1])) {
                    $column[1] = preg_replace('/(\")|(\s)/', '', $column[1]);
                    $Grupo = mysqli_real_escape_string($class->conex, utf8_encode($column[1]));
                }
                $Iniciales = "";
                if (isset($column[2])) {
                    $column[2] = preg_replace('/(\")|(\s)/', '', $column[2]);
                    $Iniciales = mysqli_real_escape_string($class->conex, utf8_encode($column[2]));
                }
                $Aula = "";
                if (isset($column[4])) {
                    $column[4] = preg_replace('/(\")|(\s)/', '', $column[4]);
                    $Aula = mysqli_real_escape_string($class->conex, utf8_encode($column[4]));
                }
                $Diasemana = "";
                if (isset($column[5])) {
                    $column[5] = preg_replace('/(\")|(\s)/', '', $column[5]);
                    $Diasemana = mysqli_real_escape_string($class->conex, utf8_encode($column[5]));
                }
                $Hora_tipo = "";
                if (isset($column[6])) {
                    $column[6] = preg_replace('/(\")|(\s)/', '', $column[6]);
                    $Hora_tipo = mysqli_real_escape_string($class->conex, utf8_encode($column[6]));
                }
                $Edificio = "";
                if (isset($Aula)) {
                    $sed = preg_split('//', $Aula, -1, PREG_SPLIT_NO_EMPTY);
                    $Edificio = mysqli_real_escape_string($class->conex, utf8_encode($sed[2]));
                    preg_match('/^[0-9]$/', $Edificio) ? $Edificio = $Edificio : $Edificio=0;
                }
                if($Edificio == 1)
                {
                    $Hora_tipo .= 'C';
                }
                else
                {
                    $Hora_tipo .= 'M';
                }
                $response = $class->query("SELECT ID FROM Profesores WHERE Iniciales='$Iniciales'");
                $IDPROFESOR = $response->fetch_assoc();
                $IDPROFESOR = $IDPROFESOR['ID'];
                $Hora_entrada = "00:00:00";
                $Hora_salida = "00:00:00";
                $response = $class->query("SELECT ID FROM Horarios WHERE ID_PROFESOR='$IDPROFESOR' AND Dia='$Diasemana' AND HORA_TIPO='$Hora_tipo' AND Grupo='$Grupo'");
                if($response->num_rows == 0)
                {
                    if(! $class->query("INSERT into $class->horarios (ID_PROFESOR, Dia, HORA_TIPO, Edificio, Aula, Grupo, Hora_entrada, Hora_salida)
                    values (
                        '$IDPROFESOR',
                        '$Diasemana',
                        '$Hora_tipo',
                        '$Edificio',
                        '$Aula',
                        '$Grupo',
                        '$Hora_entrada',
                        '$Hora_salida')"))
                    {
                        $ERR_MSG = "<br>Error al importar datos desde CSV.<br>";
                        $ERR_MSG .= $class->ERR_ASYSTECO;
                    }
                    else
                    {
                        $MSG = "Horarios importados correctamente.<br>";
                    }
                }
            }
            else
            {
                $ERR_MSG = "<br>Error en Fichero, no es el CSV esperado.<br>";
            }
        }
    }
    else
    {
        $ERR_MSG = "El fichero está vacío.";
    }
    $class->marcajes();
}
?>