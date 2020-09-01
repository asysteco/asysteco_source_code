<?php
if(isset($_GET['profesor']))
{
    $sql = "SELECT $class->profesores.ID, $class->profesores.Nombre FROM $class->profesores WHERE ID='$_GET[profesor]' AND Activo=1 AND Sustituido=0 AND TIPO=2 AND EXISTS (SELECT * FROM $class->horarios WHERE ID_PROFESOR=$class->profesores.ID) ORDER BY ID ASC";
}
else
{
    $sql = "SELECT $class->profesores.ID, $class->profesores.Nombre FROM $class->profesores WHERE Activo=1 AND Sustituido=0 AND TIPO=2 AND EXISTS (SELECT * FROM $class->horarios WHERE ID_PROFESOR=$class->profesores.ID) ORDER BY ID ASC";
}
if($response = $class->query($sql))
{
    $fila = $response->fetch_assoc();
    $profesor = $fila['ID'];
    $nombre = $fila['Nombre'];

    // Obtenemos los siguientes registros de profesores:
    // Primer, Ultimo, Siguiente y Anterior

    if(! $maxmin = $class->query("SELECT MAX(Profesores.ID) AS Ultimo, MIN(Profesores.ID) AS Primero FROM Profesores WHERE Activo=1 AND TIPO=2 AND EXISTS (SELECT * FROM Horarios WHERE ID_PROFESOR=Profesores.ID) ORDER BY ID ASC")->fetch_assoc())
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
    if(! $siguiente = $class->query("SELECT ID FROM Profesores WHERE ID > '$profesor' AND Activo=1 AND TIPO=2 AND EXISTS (SELECT * FROM $class->horarios WHERE ID_PROFESOR=$class->profesores.ID) ORDER BY ID ASC LIMIT 1")->fetch_assoc())
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
    if(! $anterior = $class->query("SELECT ID FROM Profesores WHERE ID < '$profesor' AND Activo=1 AND TIPO=2 AND EXISTS (SELECT * FROM $class->horarios WHERE ID_PROFESOR=$class->profesores.ID) ORDER BY ID DESC LIMIT 1")->fetch_assoc())
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }

    // Obtenemos su tipo de horario $l dice si es de tarde o de mañana
    
    if($tipohorario = $class->query("SELECT $class->horarios.HORA_TIPO FROM $class->horarios WHERE ID_PROFESOR='$profesor' AND (HORA_TIPO LIKE '%M' OR HORA_TIPO LIKE '%C')"))
    {
        if($tipohorario->num_rows > 0)
        {
            $l = 6;
            $tipo = 'M';
        }
        else
        {
            $l = 5;
            $tipo = 'T';
        }
    }
    else
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }

    // Obtenemos todos los registros del Horario del Profesor ($_GET[profesor])

    $sql = 
    "SELECT $class->horarios.*,
    Diasemana.Diasemana 
    FROM ($class->horarios INNER JOIN $class->profesores ON $class->horarios.ID_PROFESOR=$class->profesores.ID) 
        INNER JOIN Diasemana ON Diasemana.ID=$class->horarios.Dia
    WHERE $class->profesores.ID = '$profesor'
    ORDER BY $class->horarios.HORA_TIPO, $class->horarios.Dia";
    if($response = $class->query($sql))
    {
        // Start --> H2 con select

        echo "<h2 id='profesor_act' profesor='$profesor'>Horario: ";
        if($select = $class->query("SELECT DISTINCT $class->profesores.Nombre, $class->profesores.ID
        FROM $class->profesores WHERE EXISTS 
        (SELECT * FROM $class->horarios WHERE $class->horarios.ID_PROFESOR=$class->profesores.ID) AND TIPO=2 AND Activo=1 ORDER BY Nombre ASC"))
        {
            echo "<select id='select-edit-guardias'>";
                while($selection = $select->fetch_assoc())
                {
                    $profesor == $selection['ID'] ? $selection['ID'] = "'$selection[ID]' selected" : $selection['ID'] = "$selection[ID]";
                    echo "<option value=$selection[ID] >$selection[Nombre]</option>";
                }
            echo "</select>";
        }
        else
        {
            $ERR_MSG = $class->ERR_ASYSTECO;
        }
        echo "</h2>";

        if($maxmin['Primero'] != $profesor)
        {
             echo "<a id='anterior-profesor' class='btn btn-success'> Anterior</a>";
        }
        if($maxmin['Ultimo'] != $profesor)
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

                /* 
                * Comienza bucle por filas horarias 
                * Hasta completar las 5 o 6 de cada horario
                * Dependiendo si el horario es de tarde o de mañana
                */
                
                for ($i = 0; $i < $l; $i++)
                {
                    $dia = $class->getDate();
                    $hora = $i+1;

                    /*
                    * Recogemos valores de cada HORA_TIPO del Profesor en $response
                    * Valores ordenados por HORA_TIPO y Día
                    */

                    $sql = "SELECT $class->horarios.*, Diasemana.Diasemana
                    FROM (($class->horarios INNER JOIN $class->profesores ON $class->horarios.ID_PROFESOR=$class->profesores.ID) 
                    INNER JOIN Diasemana ON Diasemana.ID=$class->horarios.Dia)
                    INNER JOIN $class->horas ON $class->horas.Hora=$class->horarios.HORA_TIPO
                    WHERE $class->profesores.ID='$profesor' AND ($class->horarios.HORA_TIPO=" . "'" . $hora ."M' OR $class->horarios.HORA_TIPO=" . "'" . $hora ."T' OR $class->horarios.HORA_TIPO=" . "'" . $hora ."C')
                    ORDER BY $class->horarios.Dia, $class->horarios.HORA_TIPO";
                    
                    if($response = $class->query($sql))
                    {
                        // $k -> Contador de índice del array

                        $k = 0;
                        $filahora = $response->fetch_all();

                        // Start --> tr in table

                        echo "<tr>";
                        echo "<td style='vertical-align: middle; text-align: center;'><b>$hora</b></td>";

                        /*
                        * Bucle que recorre el campo Dia
                        * Este campo determinará su posición en la tabla (Horizontalmente)
                        */
                        
                        for($j = 1; $j <= 5; $j++)
                        {

                            /*
                            * Comprobamos si $filahora[$k][2] coincide con el Dia de la Semana exacto
                            */

                            if($filahora[$k][2] == $j)
                            {
                                $dia['weekday'] === $filahora[$k][9] ? $dia['color'] = "success" : $dia['color'] = '';
                                echo "<td id='$j-$hora' style='vertical-align: middle; text-align: center;' class='$dia[color]'>";
                                isset($filahora[$k][3]) ? $horavar = $filahora[$k][3] : $horavar = $hora . $tipo;
                                
                                // Comprobamos si es una Guardia

                                if($filahora[$k][6] == 'Guardia')
                                {
                                    echo "<a class='remove-guardia' style='color: red;' enlace='index.php?ACTION=horarios&OPT=edit-guardias&SUBOPT=remove&profesor=" . $filahora[$k][1] . "&d=$j&h=" . $horavar . "' title='Quitar Guardia'>";
                                        echo "<span class='glyphicon glyphicon-remove-circle btn-react-del'></span>";
                                    echo "</a><br>";
                                    echo "<span><b>Guardia</b></span>";
                                    echo "<br>";
                                    echo "<span><b>Edificio " . $filahora[$k][4] . "</b></span>";
                                    $k++;
                                }
                                else
                                {
                                    echo "<b>Aula: </b>";
                                    echo "<span>" . $filahora[$k][5] . "</span>";
                                    echo "<br>";
                                    echo "<b>Grupo:</b>";
                                    echo "<span>" . $filahora[$k][6] . "</span>";

                                    /*
                                    * Comprobamos si el siguiente objeto coincide con el mismo Dia de la Semana
                                    * Esta comprobación se realizará hasta que ya no coincida
                                    * Ya que pertenecerá al siguiente Dia
                                    */

                                    $mismoaula = $filahora[$k][5];
                                    $k++;

                                    while($filahora[$k][2] == $j && $filahora[$k][5] == $mismoaula)
                                    {
                                        echo "<br>";
                                        echo "<span>" . $filahora[$k][6] . "</span>";
                                        $k++;
                                    }
                                }

                                isset($filahora[$k][3]) ? $horavar = $filahora[$k][3] : $horavar = $hora . $tipo;
                                echo "</td>";
                            }
                            else
                            {
                                echo "<td id='$j-$hora' style='vertical-align: middle; text-align: center;'>";
                                isset($filahora[$k][3]) ? $horavar = $filahora[$k][3] : $horavar = $hora . $tipo;
                                if($resp = $class->query("SELECT DISTINCT Edificio FROM Horarios WHERE Edificio<>0 ORDER BY Edificio ASC"))
                                {
                                    echo "<select id='edificio-$j-$hora' class='edificio' title='Selecciona un edificio para la guardia'>";
                                    echo "<option value=''>Edificio...</option>";
                                    while($row = $resp->fetch_assoc())
                                    {
                                        echo "<option value='$row[Edificio]'>Edificio $row[Edificio]</option>";
                                    }
                                    echo "</select>";
                                }
                                echo '<br>';
                                    echo "<a id='plus-$j-$hora' class='act' enlace='index.php?ACTION=horarios&OPT=edit-guardias&SUBOPT=add&profesor=$profesor&d=$j&h=$horavar' title='Asignar Guardia'>";
                                        echo "<span class='glyphicon glyphicon-plus btn-react-add'></span>";
                                    echo "</a>";
                                echo "</td>";
                            }
                        }
                        echo "</tr>";
                    }
                    else
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
    $ERR_MSG = $class->ERR_ASYSTECO;
}

echo "<script>
$('#anterior-profesor').click(function(){
    $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor=$anterior[ID]')
});

$('#siguiente-profesor').click(function(){
    $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor=$siguiente[ID]')
});

$('#select-edit-guardias').on('change', function(){
profesor = $(this).val(),
$('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor='+profesor)
});

$('.act').hide();
$('.act').on('click', function(){
$('#loading-msg').html('Cargando...'),
$('#loading').show(),
$('#guardias-response').html(''),
enlace = $(this).attr('enlace'),
$('#act-response').load(enlace),
setTimeout(function(){
$('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor='+$profesor)
},200),
$('#loading').delay('1200').fadeOut()
});

$('.remove-guardia').on('click', function(){
$('#loading-msg').html('Cargando...'),
$('#loading').show(),
$('#guardias-response').html(''),
enlace = $(this).attr('enlace'),
$('#act-response').load(enlace),
setTimeout(function(){
$('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor='+$profesor)
},200),
$('#loading').delay().fadeOut()
});

$('.edificio').on('change', function() {
edificio = $(this).val(),
id = $(this).attr('id').split('-'),
plus = 'plus-'+id[1]+'-'+id[2];
if(edificio == '')
{
    $('#'+plus).hide();
    return
}
else
{
    enlace = $('#'+plus).attr('enlace'),
    $('#'+plus).attr('enlace', enlace+'&e='+edificio),
    $('#'+plus).show()
}
});
</script>";