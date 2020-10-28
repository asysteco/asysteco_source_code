<?php
if(isset($_GET['profesor']))
{
    $sql = "SELECT $class->profesores.ID, $class->profesores.Nombre FROM $class->profesores WHERE ID='$_GET[profesor]' AND Activo=1 AND Sustituido=0 AND TIPO=2 AND EXISTS (SELECT * FROM $class->horarios WHERE $class->horarios.ID_PROFESOR=$class->profesores.ID) ORDER BY ID ASC";
}
else
{
    $sql = "SELECT $class->profesores.ID, $class->profesores.Nombre FROM $class->profesores WHERE Activo=1 AND Sustituido=0 AND TIPO=2 AND EXISTS (SELECT * FROM $class->horarios WHERE $class->horarios.ID_PROFESOR=$class->profesores.ID) ORDER BY ID ASC";
}

if($response = $class->query($sql))
{
    if($response->num_rows > 0)
    {
        $fila = $response->fetch_assoc();
        $_GET['profesor'] = $fila['ID'];
        $n['ID'] = $fila['ID'];
        $n['Nombre'] = $fila['Nombre'];
    
        // Obtenemos el tipo de Horario del profesor

        $sql = "SELECT DISTINCT Tipo
        FROM $class->horarios
        WHERE $class->horarios.ID_PROFESOR='$_GET[profesor]'";
        if($response = $class->query($sql))
        {
            if ($response->num_rows == 1)
            {
                $dia = $class->getDate();
                $datosprof = $response->fetch_assoc();
                $franja = $datosprof['Tipo'];

                // Obtenemos los siguientes registros de profesores:
                // Primer, Ultimo, Siguiente y Anterior
            
                if(! $maxmin = $class->query("SELECT MAX(Profesores.ID) AS Ultimo, MIN(Profesores.ID) AS Primero FROM Profesores WHERE Activo=1 AND TIPO=2 AND Sustituido=0 AND EXISTS (SELECT * FROM Horarios WHERE ID_PROFESOR=Profesores.ID) ORDER BY ID ASC")->fetch_assoc())
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
                if(! $siguiente = $class->query("SELECT ID FROM Profesores WHERE ID > '$n[ID]' AND Activo=1 AND TIPO=2 AND Sustituido=0 AND EXISTS (SELECT * FROM $class->horarios WHERE ID_PROFESOR=$class->profesores.ID) ORDER BY ID ASC LIMIT 1")->fetch_assoc())
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
                if(! $anterior = $class->query("SELECT ID FROM Profesores WHERE ID < '$n[ID]' AND Activo=1 AND TIPO=2 AND Sustituido=0 AND EXISTS (SELECT * FROM $class->horarios WHERE ID_PROFESOR=$class->profesores.ID) ORDER BY ID DESC LIMIT 1")->fetch_assoc())
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
                // Start --> H2 con select
        
                echo "<h2 id='profesor_act' profesor='$n[ID]'>Horario: ";
                if($select = $class->query("SELECT DISTINCT $class->profesores.Nombre, $class->profesores.ID
                FROM $class->profesores WHERE EXISTS 
                (SELECT * FROM $class->horarios WHERE $class->horarios.ID_PROFESOR=$class->profesores.ID) AND TIPO=2 AND Activo=1 ORDER BY Nombre ASC"))
                {
                    echo "<select id='select-edit-guardias'>";
                        while($selection = $select->fetch_assoc())
                        {
                            $n['ID'] == $selection['ID'] ? $selection['ID'] = "'$selection[ID]' selected" : $selection['ID'] = "$selection[ID]";
                            echo "<option value=$selection[ID] >$selection[Nombre]</option>";
                        }
                    echo "</select>";
                }
                else
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
                echo "</h2>";
        
                if($maxmin['Primero'] != $n['ID'])
                {
                        echo "<a id='anterior-profesor' class='btn btn-success'> Anterior</a>";
                }
                if($maxmin['Ultimo'] != $n['ID'])
                {
                        echo "<a id='siguiente-profesor' class='btn btn-success pull-right'> Siguiente</a>";
                }
                echo "</br>";
        
                // End --> H2 con select
        
                // Start --> Tabla con contenido
        
                echo "<div id='tabla-horario-guardias'>";
                    echo "<table class='table'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th style='text-align: center;'>Horas</th>";
                                echo "<th style='text-align: center;'>Lunes</th>";
                                echo "<th style='text-align: center;'>Martes</th>";
                                echo "<th style='text-align: center;'>Miercoles</th>";
                                echo "<th style='text-align: center;'>Jueves</th>";
                                echo "<th style='text-align: center;'>Viernes</th>";
                                echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        foreach ($franjasHorarias[$franja] as $valor => $datos)
                        {
                            $Hora = $valor;
                            $horaInicioSplit = preg_split('/:/', $datos['Inicio']);
                            $horaInicioSinSegundos = $horaInicioSplit[0] . ":" . $horaInicioSplit[1];
                            $horaFinSplit = preg_split('/:/', $datos['Fin']);
                            $horaFinSinSegundos = $horaFinSplit[0] . ":" . $horaFinSplit[1];

                            echo "<tr>";
                            echo "<td style='text-align: center; vertical-align: middle;'>$horaInicioSinSegundos <br>$horaFinSinSegundos</td>";
                                
                                for($dialoop = 1; $dialoop <= 5; $dialoop++)
                                {
                                    $dia['wday'] == $dialoop ? $dia['color'] = "success" : $dia['color'] = '';
                                    if($response = $class->query("SELECT Hora, Dia, Aula, Grupo, Edificio FROM Horarios WHERE ID_PROFESOR='$_GET[profesor]' AND Hora='$Hora' AND Dia='$dialoop' ORDER BY Hora "))
                                    {
                                        if($response->num_rows > 0)
                                        {
                                            $fila = $response->fetch_all();
                                            $m=2;
                                            echo "<td id='$dialoop-$Hora' style='vertical-align: middle; text-align: center;' class=' $dia[color]'>";
                                            
                                            if($fila[0][3] == 'Guardia')
                                            {
                                                echo "<a class='remove-guardia' style='color: red;' enlace='index.php?ACTION=horarios&OPT=edit-guardias&SUBOPT=remove&profesor=$n[ID]&Dia=$dialoop&Hora=$Hora' title='Quitar Guardia'>";
                                                    echo "<span class='glyphicon glyphicon-remove-circle btn-react-del'></span>";
                                                echo "</a><br>";
                                                echo "<span><b>Guardia</b></span>";
                                                echo "<br>";
                                                echo "<span><b>Edificio " . $fila[0][4] . "</b></span>";
                                            }
                                            else
                                            {
                                                echo "<b>Aula: </b>";
                                                echo "<span>" . $fila[0][2] . "</span>";
                                                echo "<br><b>Grupo: </b>";
                                                for($i=0;$i<count($fila);$i++)
                                                {
                                                    $m % 2 == 0 ? $espacio = " " : $espacio = "<br>";
                                                    //echo $espacio . $fila[$i][3];
    
                                                    echo  $espacio . "<span>" . $fila[$i][3] . "</span> ";
    
                                                    $grupo = $fila[$i][3];
                                                    $m++;
                                                }
                                            }
                                            echo "</td>";
                                        }
                                        else
                                        {
                                            echo "<td id='$dialoop-$Hora' style='vertical-align: middle; text-align: center;' class=' $dia[color]'>";
                                            if($resp = $class->query("SELECT DISTINCT Edificio FROM Horarios WHERE Edificio<>0 ORDER BY Edificio ASC"))
                                            {
                                                echo "<select id='edificio-$dialoop-$Hora' class='edificio' title='Selecciona un edificio para la guardia'>";
                                                echo "<option value=''>Edificio...</option>";
                                                while($row = $resp->fetch_assoc())
                                                {
                                                    echo "<option value='$row[Edificio]'>Edificio $row[Edificio]</option>";
                                                }
                                                echo "</select>";
                                            }
                                            echo '<br>';
                                                echo "<a id='plus-$dialoop-$Hora' class='act' enlace='index.php?ACTION=horarios&OPT=edit-guardias&SUBOPT=add&profesor=$n[ID]&Dia=$dialoop&Hora=$Hora&Tipo=$franja' title='Asignar Guardia'>";
                                                    echo "<span class='glyphicon glyphicon-plus btn-react-add'></span>";
                                                echo "</a>";
                                            echo "</td>";
                                        }
                                    }
                                }
                            echo "</tr>";
                        }
            }
            elseif($response->num_row > 1)
            {
                echo "<h1 style='vertical-align: middle; text-align: center;'>Formato no válido, revise su horario...</h1>";
            }
            else
            {
                echo "<h1 style='display: block; text-align: center;'>";
                echo "$n[Nombre] no tiene horario";
                echo "</h1>";
                echo "<div style='text-align: center;'>";
                    echo "<a id='crear-horario' href='index.php?ACTION=horarios&OPT=crear&profesor=$n[ID]&Tipo=Mañana' class='btn btn-success'>Crear horario para $n[Nombre]</a>";
                echo "</div>";
            }
        }
        else
        {
            $ERR_MSG = $class->ERR_ASYSTECO;
        }
    }
    else
    {
        echo "<h1>No existen horarios...</h1>";
    }
}
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}

echo "<script>
    $('#anterior-profesor').click(function(){
        $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor=$anterior[ID]')
    });

    $('#siguiente-profesor').click(function(){
        $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor=$siguiente[ID]')
    });
</script>";
?>

<script src="js/update_guardias.js"></script>