<?php
if(isset($_POST['enviar']))
{
    if($_POST['enviar'] == 'Insertar')
    {
        if(isset($_POST['inicio']) && isset($_POST['fin']) && $_POST['inicio'] != '' && $_POST['fin'] != '')
        {
            if(preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/", $_POST['inicio']) && preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/", $_POST['fin']))
            {
                $sep = preg_split('/\//', $_POST['inicio']);
                $sep2 = preg_split('/\//', $_POST['fin']);
                $dia = $sep[0];
                $m = $sep[1];
                $Y = $sep[2];
                $_POST['inicio'] = $Y . '-' . $m . '-' . $dia;
                $dia2 = $sep2[0];
                $m2 = $sep2[1];
                $Y2 = $sep2[2];
                $_POST['fin'] = $Y2 . '-' . $m2 . '-' . $dia2;
                if($respuesta = $class->query("SELECT $class->profesores.ID FROM $class->profesores"))
                {
                    if($respuesta->num_rows > 0)
                    {
                        if($response = $class->dateLoop($_POST['inicio'], $_POST['fin']))
                        {
                            $MSG = 'Datos insertados correctamente.';
                            //header("Refresh: 0;  $_SERVER[REQUEST_URI]");
                        }
                        else
                        {
                            $ERR_MSG = $class->ERR_ASYSTECO;
                        }
                    }
                    else
                    {
                        $ERR_MSG ="Debe registrar primero el listado de profesores.";
                    }
                }
                else
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
            }
            else
            {
                $ERR_MSG = 'El formato de fecha es incorrecto.';
            }
        }
        else
        {
            $ERR_MSG = 'Indique una fecha de inicio y otra de fin de días lectivos.';
        }
    }
    elseif($_POST['enviar'] == 'Festivos')
    {
        if(isset($_POST['inicio']) && isset($_POST['fin']) && $_POST['inicio'] != '' && $_POST['fin'] != '')
        {
            if(preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/", $_POST['inicio']) && preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/", $_POST['fin']))
            {
                $inicio = $_POST['inicio'];
                $fin = $_POST['fin'];
                $sep = preg_split('/\//', $_POST['inicio']);
                $sep2 = preg_split('/\//', $_POST['fin']);
                $dia = $sep[0];
                $m = $sep[1];
                $Y = $sep[2];
                $_POST['inicio'] = $Y . '-' . $m . '-' . $dia;
                $dia2 = $sep2[0];
                $m2 = $sep2[1];
                $Y2 = $sep2[2];
                $_POST['fin'] = $Y2 . '-' . $m2 . '-' . $dia2;
                if($response2 = $class->updateDateLoop($_POST['inicio'], $_POST['fin']))
                {
                    $MSG = 'Fechas Festivas añadidas correctamente.';
                }
                else
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
            }
            else
            {
                $ERR_MSG = 'El formato de fecha es incorrecto.';
            }
        }
        else
        {
            $ERR_MSG = 'Indique una fecha de inicio y otra de fin de días festivos.';
        }
    }
}