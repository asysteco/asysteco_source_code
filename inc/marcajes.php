<?php
if($response4 = $class->query("SELECT $class->lectivos.Fecha FROM $class->lectivos"))
{
    if($response4->num_rows > 0)
    {
        if(isset($fechaactual))
        {
            if($lectivos = $class->query("SELECT $class->lectivos.Fecha FROM $class->lectivos WHERE $class->lectivos.Festivo='no' AND $class->lectivos.Fecha>='$fechaactual'"))
            {
                while($fila = $lectivos->fetch_assoc())
                {
                    if(! $respose = $class->query("INSERT INTO Marcajes SELECT DISTINCT ID_PROFESOR,'$fila[Fecha]' as Fecha, HORA_TIPO, Dia, 0
                    FROM Horarios INNER JOIN Diasemana ON Horarios.Dia=Diasemana.ID
                    WHERE Dia = WEEKDAY('$fila[Fecha]')+1"))
                    {
                        $ERR_MSG = $class->ERR_ASYSTECO;
                    }
                }
                $MSG = "<span style='color: green;'>Marcajes por horas actualizados correctamente</span>";
                if(isset($ERR_MSG)  && $ERR_MSG != '')
                {
                    header("Location: $_SERVER[HTTP_REFERER]&ERR_MSG=" . $ERR_MSG);
                }
                else
                {
                    header("Location: $_SERVER[HTTP_REFERER]&MSG=" . $MSG);
                }
            }
            else
            {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
        }
        elseif(isset($_GET['ID_PROFESOR']))
        {
            $fechaactual = date('Y-m-d');
            if($lectivos = $class->query("SELECT $class->lectivos.Fecha FROM $class->lectivos WHERE $class->lectivos.Festivo='no' AND $class->lectivos.Fecha>='$fechaactual'"))
            {
                while($fila = $lectivos->fetch_assoc())
                {
                    if(! $class->query("INSERT INTO Marcajes SELECT DISTINCT ID_PROFESOR,'$fila[Fecha]' as Fecha, HORA_TIPO, Dia, 0
                    FROM Horarios INNER JOIN Diasemana ON Horarios.Dia=Diasemana.ID
                    WHERE Dia = WEEKDAY('$fila[Fecha]')+1 AND ID_PROFESOR='$_GET[ID_PROFESOR]'"))
                    {
                        $ERR_MSG = $class->ERR_ASYSTECO;
                    }
                }
                $MSG .= "<br><span style='color: green;'>Marcajes por horas actualizados correctamente</span>";
                if(isset($ERR_MSG)  && $ERR_MSG != '')
                {
                    header("Location: $_SERVER[HTTP_REFERER]&ERR_MSG=" . $ERR_MSG);
                }
                else
                {
                    header("Location: $_SERVER[HTTP_REFERER]&MSG=" . $MSG);
                }
            }
            else
            {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
        }
        elseif(isset($_GET['profesor']) && isset($_GET['SUBOPT']) && $_GET['SUBOPT'] == 'add')
        {
            $fechaactual = date('Y-m-d');
            if($lectivos = $class->query("SELECT $class->lectivos.Fecha FROM $class->lectivos WHERE $class->lectivos.Festivo='no' AND $class->lectivos.Fecha>='$fechaactual'"))
            {
                while($fila = $lectivos->fetch_assoc())
                {
                    if(! $class->query("INSERT INTO Marcajes SELECT DISTINCT ID_PROFESOR,'$fila[Fecha]' as Fecha, HORA_TIPO, Dia, 0
                    FROM Horarios INNER JOIN Diasemana ON Horarios.Dia=Diasemana.ID
                    WHERE Dia = WEEKDAY('$fila[Fecha]')+1 AND ID_PROFESOR='$_GET[profesor]'"))
                    {
                        $ERR_MSG = $class->ERR_ASYSTECO;
                    }
                }
            }
            else
            {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
        }
        elseif(isset($_GET['profesor']) && isset($_GET['SUBOPT']) && $_GET['SUBOPT'] == 'remove')
        {
            $fechaactual = date('Y-m-d');
            if($lectivos = $class->query("SELECT $class->lectivos.Fecha FROM $class->lectivos WHERE $class->lectivos.Festivo='no' AND $class->lectivos.Fecha>='DATE_ADD('$fechaactual', INTERVAL +1 DAY)'"))
            {
                while($fila = $lectivos->fetch_assoc())
                {
                    if(! $class->query("DELETE FROM Marcajes WHERE ID_PROFESOR='$_GET[profesor]' AND Dia='$dia' AND Hora='$hora' AND Fecha='$fila[Fecha]'"))
                    {
                        $ERR_MSG = $class->ERR_ASYSTECO;
                    }
                }
            }
            else
            {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
        }
        else
        {
            if($lectivos = $class->query("SELECT $class->lectivos.Fecha FROM $class->lectivos WHERE $class->lectivos.Festivo='no'"))
            {
                while($fila = $lectivos->fetch_assoc())
                {
                    if(! $respose = $class->query("INSERT INTO Marcajes SELECT DISTINCT ID_PROFESOR,'$fila[Fecha]' as Fecha, HORA_TIPO, Dia, 0
                    FROM Horarios INNER JOIN Diasemana ON Horarios.Dia=Diasemana.ID
                    WHERE Dia = WEEKDAY('$fila[Fecha]')+1"))
                    {
                        $ERR_MSG = $class->ERR_ASYSTECO;
                    }
                }
                $MSG = "<span style='color: green;'>Marcajes por horas actualizados correctamente</span>";
                if(isset($ERR_MSG)  && $ERR_MSG != '')
                {
                    header("Location: $_SERVER[HTTP_REFERER]&ERR_MSG=" . $ERR_MSG);
                }
                else
                {
                    header("Location: $_SERVER[HTTP_REFERER]&MSG=" . $MSG);
                }
            }
            else
            {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
        }
    }
    else
    {   
        $ERR_MSG ="Debe establecer el calendario lectivo/festivo.";
    }
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}