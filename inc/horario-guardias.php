<?php

if(isset($_GET['profesor']))
{
    if ($response = $class->query("SELECT $class->profesores.ID, $class->profesores.Nombre FROM $class->profesores WHERE ID='$_GET[profesor]' AND Activo=1 AND TIPO=2 AND EXISTS (SELECT * FROM $class->horarios WHERE ID_PROFESOR=$class->profesores.ID) ORDER BY ID ASC"))
    {
      if ($response->num_rows > 0)
      {
       $fila = $response->fetch_assoc();
       if(! $maxmin = $class->query("SELECT MAX(Profesores.ID) AS Ultimo, MIN(Profesores.ID) AS Primero FROM Profesores WHERE Activo=1 AND TIPO=2 AND EXISTS (SELECT * FROM Horarios WHERE ID_PROFESOR=Profesores.ID) ORDER BY ID ASC")->fetch_assoc())
       {
            $ERR_MSG = $class->ERR_ASYSTECO;
       }
       if(! $siguiente = $class->query("SELECT ID FROM Profesores WHERE ID > '$fila[ID]' AND Activo=1 AND TIPO=2 AND EXISTS (SELECT * FROM $class->horarios WHERE ID_PROFESOR=$class->profesores.ID) ORDER BY ID ASC LIMIT 1")->fetch_assoc())
       {
            $ERR_MSG = $class->ERR_ASYSTECO;
       }
       if(! $anterior = $class->query("SELECT ID FROM Profesores WHERE ID < '$fila[ID]' AND Activo=1 AND TIPO=2 AND EXISTS (SELECT * FROM $class->horarios WHERE ID_PROFESOR=$class->profesores.ID) ORDER BY ID DESC LIMIT 1")->fetch_assoc())
       {
            $ERR_MSG = $class->ERR_ASYSTECO;
       }
           if($response = $class->query("SELECT $class->horarios.*, Diasemana.Diasemana 
                                       FROM ($class->horarios INNER JOIN $class->profesores ON $class->horarios.ID_PROFESOR=$class->profesores.ID) 
                                       INNER JOIN Diasemana ON Diasemana.ID=$class->horarios.Dia WHERE $class->profesores.ID='$_GET[profesor]' 
                                       ORDER BY $class->horarios.HORA_TIPO, $class->horarios.Dia"))
           {
               if ($response->num_rows > 0)
               {
                   if(! $n = $class->query("SELECT Nombre, ID FROM $class->profesores WHERE ID='$_GET[profesor]'")->fetch_assoc())
                   {
                       $ERR_MSG = $class->ERR_ASYSTECO;
                   }
                 
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

                   if($fila['Primero'] != $_GET['profesor'] && $fila['Primero'] != $anterior['ID'])
                   {
                        echo "<a id='anterior-profesor' class='btn btn-success'> Anterior</a>";
                   }
                   if($fila['Ultimo'] != $_GET['profesor'] && $fila['Primero'] != $siguiente['ID'])
                   {
                        echo "<a id='siguiente-profesor' class='btn btn-success pull-right'> Siguiente</a>";
                   }
                   echo "<div id='response'></div>";
                   echo "</br><table class='table'>";
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
                           if($response2 = $class->query("SELECT $class->horarios.HORA_TIPO FROM $class->horarios WHERE ID_PROFESOR='$_GET[profesor]' AND (HORA_TIPO LIKE '%M' OR HORA_TIPO LIKE '%C')"))
                           {
                               if($response2->num_rows > 0)
                               {
                                   $l = 6;
                               }
                               else
                               {
                                   $l = 5;
                               }
                           }
                           else
                           {
                               $ERR_MSG = $class->ERR_ASYSTECO;
                           }
                               /* 
                               * Comienza bucle por filas horarias 
                               * Hasta completar las 6 de cada horario
                               */
                               
                               for ($i = 0; $i < $l; $i++)
                               {
                                   $dia = $class->getDate();
                                   $hora = $i+1;
   
                                   /*
                                   * Recogemos valores de cada HORA_TIPO del Profesor en $response
                                   * Valores ordenados por HORA_TIPO y Día
                                   */
   
                                   if($response = $class->query("SELECT $class->horarios.*, Diasemana.Diasemana, Diasemana.ID, $class->horas.Inicio, $class->horas.Fin 
                                   FROM (($class->horarios INNER JOIN $class->profesores ON $class->horarios.ID_PROFESOR=$class->profesores.ID) 
                                   INNER JOIN Diasemana ON Diasemana.ID=$class->horarios.Dia)
                                   INNER JOIN $class->horas ON $class->horas.Hora=$class->horarios.HORA_TIPO
                                   WHERE $class->profesores.ID='$_GET[profesor]' AND ($class->horarios.HORA_TIPO=" . "'" . $hora ."M' OR $class->horarios.HORA_TIPO=" . "'" . $hora ."T' OR $class->horarios.HORA_TIPO=" . "'" . $hora ."C')
                                   ORDER BY $class->horarios.HORA_TIPO, $class->horarios.Dia"))
                                   {
                                       // $k -> Contador de índice del array
                                       $k = 0;
                                       $filahora = $response->fetch_all();
                                       echo "<tr>";
                                       echo "<td style='vertical-align: middle; text-align: center;'><b>$hora</b></td>";
   
                                       /*
                                       * Bucle que recorre el campo Dia
                                       * Este campo determinará su posición en la tabla (Horizontalmente)
                                       */
                                       
                                       for($j = 1; $j <= 5; $j++)
                                       {
   
                                           /*
                                           * Comprobamos si $filahora[$k][10] coincide con el Dia de la Semana exacto
                                           */
   
                                           if($filahora[$k][10] == $j)
                                           {
                                               $dia['weekday'] === $filahora[$k][9] ? $dia['color'] = "success" : $dia['color'] = '';
                                               echo "<td style='vertical-align: middle; text-align: center;' class='$dia[color]'>";
                                               echo "<b>Aula: </b>";
                                               echo "<span id='sp_" . $filahora[$k][0] . "_Aula' class='txt'>" . $filahora[$k][5] . "</span>";
                                               //echo "<input id='in_" . $filahora[$k][0] . "_Aula' class='entrada' type='text'>";
                                               if($response = $class->query("SELECT DISTINCT $class->horarios.Aula FROM $class->horarios WHERE $class->horarios.Aula <> '' ORDER BY $class->horarios.Aula"))
                                               {
                                                   echo "<select id='in_" . $filahora[$k][0] . "_Aula' class='entrada' name='Aula'>";
                                                       while($fila = $response->fetch_assoc())
                                                       {
                                                           echo "<option value='$fila[Aula]'>$fila[Aula]</option>";
                                                       }
                                                   echo "</select>";
                                               }
                                               else
                                               {
                                                   echo "<span style='color:red;'>$class->ERR_ASYSTECO</span>";
                                               }
                                               echo "<br>";
                                               echo "<b>Grupo:</b>";
                                               echo "<span id='sp2_" . $filahora[$k][0] . "_Grupo' class='txt'>" . $filahora[$k][6] . "</span>";
                                               if($response2 = $class->query("SELECT DISTINCT $class->horarios.Grupo FROM $class->horarios WHERE $class->horarios.Grupo <> '' ORDER BY $class->horarios.Grupo"))
                                               {
                                                   echo "<select id='in2_" . $filahora[$k][0] . "_Grupo' class='entrada' name='Grupo'>";
                                                       while($fila = $response2->fetch_assoc())
                                                       {
                                                           echo "<option value='$fila[Grupo]'>$fila[Grupo]</option>";
                                                       }
                                                   echo "</select>";
                                               }
                                               else
                                               {
                                                   echo "<span style='color:red;'>$class->ERR_ASYSTECO</span>";
                                               }
                                               $k++;
                                               // $m -> Contador de pares para saltar línea o añadir espacio
                                               $m = 2;
   
                                               /*
                                               * Comprobamos si el siguiente objeto coincide con el mismo Dia de la Semana
                                               * Esta comprobación se realizará hasta que ya no coincida
                                               * Ya que pertenecerá al siguiente Dia
                                               */
   
                                               while($filahora[$k][10] == $j)
                                               {
                                                   if($m % 2 == 0)
                                                   {
                                                       echo "<br>";
                                                   }
                                                   else
                                                   {
                                                       echo " ";
                                                   }
                                                   echo $filahora[$k][6];
                                                   $m++;
                                                   $k++;
                                               }
                                               echo "</td>";
                                           }
                                           else
                                           {
                                                echo "<td id='$j-$hora' style='vertical-align: middle; text-align: center;'>";
                                                isset($filahora[$k][3]) ? $horavar = $filahora[$k][3] : $horavar = $hora . $tipo;
                                                    echo "<a class='act' enlace='index.php?ACTION=horarios&OPT=edit-guardias&SUBOPT=add&profesor=$n[ID]&d=$j&Hora=$horavar' title='Asignar Guardia'>";
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
                       echo "</tbody>";
                   echo "</table>";
                   include_once('js/update_horario.js');
                   include_once('js/temp_horario.js');
               }
               else
               {
                   if(! $n = $class->query("SELECT ID, Nombre FROM $class->profesores WHERE ID='$_GET[profesor]'")->fetch_assoc())
                   {
                       $ERR_MSG = $class->ERR_ASYSTECO;
                   }
                   echo "<a id='crear-horario' href='index.php?ACTION=crear-horario&profesor=$n[ID]&Tipo=M' class='btn btn-success'>Crear horario para $n[Nombre]</a>";
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
    }
    else
    {
               $ERR_MSG = $class->ERR_ASYSTECO;
   
    } 
}
else
{
    if ($response = $class->query("SELECT $class->profesores.ID, $class->profesores.Nombre FROM $class->profesores WHERE Activo=1 AND TIPO=2 AND EXISTS (SELECT * FROM $class->horarios WHERE ID_PROFESOR=$class->profesores.ID) ORDER BY ID ASC"))
    {
      if ($response->num_rows > 0)
      {
       $fila = $response->fetch_assoc();
       $_GET['profesor'] = $fila['ID'];
       if(! $siguiente = $class->query("SELECT ID FROM Profesores WHERE ID > '$fila[ID]' AND Activo=1 AND TIPO=2 AND EXISTS (SELECT * FROM $class->horarios WHERE ID_PROFESOR=$class->profesores.ID) ORDER BY ID ASC LIMIT 1")->fetch_assoc())
       {
            $ERR_MSG = $class->ERR_ASYSTECO;
       }
       if(! $anterior = $class->query("SELECT ID FROM Profesores WHERE ID < '$fila[ID]' AND Activo=1 AND TIPO=2 AND EXISTS (SELECT * FROM $class->horarios WHERE ID_PROFESOR=$class->profesores.ID) ORDER BY ID DESC LIMIT 1")->fetch_assoc())
       {
            $ERR_MSG = $class->ERR_ASYSTECO;
       }
           if($response = $class->query("SELECT $class->horarios.*, Diasemana.Diasemana 
                                       FROM ($class->horarios INNER JOIN $class->profesores ON $class->horarios.ID_PROFESOR=$class->profesores.ID) 
                                       INNER JOIN Diasemana ON Diasemana.ID=$class->horarios.Dia WHERE $class->profesores.ID='$_GET[profesor]' 
                                       ORDER BY $class->horarios.HORA_TIPO, $class->horarios.Dia"))
           {
               if ($response->num_rows > 0)
               {
                   if(! $n = $class->query("SELECT Nombre, ID FROM $class->profesores WHERE ID='$_GET[profesor]'")->fetch_assoc())
                   {
                       $ERR_MSG = $class->ERR_ASYSTECO;
                   }
                 
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
                   
                   echo "<a id='siguiente-profesor' class='btn btn-success pull-right'> Siguiente</a>";
                   echo "<div id='response'></div>";
                   echo "</br><table class='table'>";
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
                           if($response2 = $class->query("SELECT $class->horarios.HORA_TIPO FROM $class->horarios WHERE ID_PROFESOR='$_GET[profesor]' AND (HORA_TIPO LIKE '%M' OR HORA_TIPO LIKE '%C')"))
                           {
                               if($response2->num_rows > 0)
                               {
                                   $l = 6;
                               }
                               else
                               {
                                   $l = 5;
                               }
                           }
                           else
                           {
                               $ERR_MSG = $class->ERR_ASYSTECO;
                           }
                               /* 
                               * Comienza bucle por filas horarias 
                               * Hasta completar las 6 de cada horario
                               */
                               
                               for ($i = 0; $i < $l; $i++)
                               {
                                   $dia = $class->getDate();
                                   $hora = $i+1;
   
                                   /*
                                   * Recogemos valores de cada HORA_TIPO del Profesor en $response
                                   * Valores ordenados por HORA_TIPO y Día
                                   */
   
                                   if($response = $class->query("SELECT $class->horarios.*, Diasemana.Diasemana, Diasemana.ID, $class->horas.Inicio, $class->horas.Fin 
                                   FROM (($class->horarios INNER JOIN $class->profesores ON $class->horarios.ID_PROFESOR=$class->profesores.ID) 
                                   INNER JOIN Diasemana ON Diasemana.ID=$class->horarios.Dia)
                                   INNER JOIN $class->horas ON $class->horas.Hora=$class->horarios.HORA_TIPO
                                   WHERE $class->profesores.ID='$_GET[profesor]' AND ($class->horarios.HORA_TIPO=" . "'" . $hora ."M' OR $class->horarios.HORA_TIPO=" . "'" . $hora ."T' OR $class->horarios.HORA_TIPO=" . "'" . $hora ."C')
                                   ORDER BY $class->horarios.HORA_TIPO, $class->horarios.Dia"))
                                   {
                                       // $k -> Contador de índice del array
                                       $k = 0;
                                       $filahora = $response->fetch_all();
                                       echo "<tr>";
                                       echo "<td style='vertical-align: middle; text-align: center;'><b>$hora</b></td>";
   
                                       /*
                                       * Bucle que recorre el campo Dia
                                       * Este campo determinará su posición en la tabla (Horizontalmente)
                                       */
                                       
                                       for($j = 1; $j <= 5; $j++)
                                       {
   
                                           /*
                                           * Comprobamos si $filahora[$k][10] coincide con el Dia de la Semana exacto
                                           */
   
                                           if($filahora[$k][10] == $j)
                                           {
                                               $dia['weekday'] === $filahora[$k][9] ? $dia['color'] = "success" : $dia['color'] = '';
                                               echo "<td style='vertical-align: middle; text-align: center;' class='$dia[color]'>";
                                               echo "<b>Aula: </b>";
                                               echo "<span id='sp_" . $filahora[$k][0] . "_Aula' class='txt'>" . $filahora[$k][5] . "</span>";
                                               //echo "<input id='in_" . $filahora[$k][0] . "_Aula' class='entrada' type='text'>";
                                               if($response = $class->query("SELECT DISTINCT $class->horarios.Aula FROM $class->horarios WHERE $class->horarios.Aula <> '' ORDER BY $class->horarios.Aula"))
                                               {
                                                   echo "<select id='in_" . $filahora[$k][0] . "_Aula' class='entrada' name='Aula'>";
                                                       while($fila = $response->fetch_assoc())
                                                       {
                                                           echo "<option value='$fila[Aula]'>$fila[Aula]</option>";
                                                       }
                                                   echo "</select>";
                                               }
                                               else
                                               {
                                                   echo "<span style='color:red;'>$class->ERR_ASYSTECO</span>";
                                               }
                                               echo "<br>";
                                               echo "<b>Grupo:</b>";
                                               echo "<span id='sp2_" . $filahora[$k][0] . "_Grupo' class='txt'>" . $filahora[$k][6] . "</span>";
                                               if($response2 = $class->query("SELECT DISTINCT $class->horarios.Grupo FROM $class->horarios WHERE $class->horarios.Grupo <> '' ORDER BY $class->horarios.Grupo"))
                                               {
                                                   echo "<select id='in2_" . $filahora[$k][0] . "_Grupo' class='entrada' name='Grupo'>";
                                                       while($fila = $response2->fetch_assoc())
                                                       {
                                                           echo "<option value='$fila[Grupo]'>$fila[Grupo]</option>";
                                                       }
                                                   echo "</select>";
                                               }
                                               else
                                               {
                                                   echo "<span style='color:red;'>$class->ERR_ASYSTECO</span>";
                                               }
                                               $k++;
                                               // $m -> Contador de pares para saltar línea o añadir espacio
                                               $m = 2;
   
                                               /*
                                               * Comprobamos si el siguiente objeto coincide con el mismo Dia de la Semana
                                               * Esta comprobación se realizará hasta que ya no coincida
                                               * Ya que pertenecerá al siguiente Dia
                                               */
   
                                               while($filahora[$k][10] == $j)
                                               {
                                                   if($m % 2 == 0)
                                                   {
                                                       echo "<br>";
                                                   }
                                                   else
                                                   {
                                                       echo " ";
                                                   }
                                                   echo $filahora[$k][6];
                                                   $m++;
                                                   $k++;
                                               }
                                               echo "</td>";
                                           }
                                           else
                                           {
                                                echo "<td id='$j-$hora' style='vertical-align: middle; text-align: center;'>";
                                                isset($filahora[$k][3]) ? $horavar = $filahora[$k][3] : $horavar = $hora . $tipo;
                                                    echo "<a class='act' enlace='index.php?ACTION=horarios&OPT=edit-guardias&SUBOPT=add&profesor=$n[ID]&d=$j&Hora=$horavar' title='Asignar Guardia'>";
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
                       echo "</tbody>";
                   echo "</table>";
                   include_once('js/update_horario.js');
                   include_once('js/temp_horario.js');
               }
               else
               {
                   if(! $n = $class->query("SELECT ID, Nombre FROM $class->profesores WHERE ID='$_GET[profesor]'")->fetch_assoc())
                   {
                       $ERR_MSG = $class->ERR_ASYSTECO;
                   }
                   echo "<a id='crear-horario' href='index.php?ACTION=crear-horario&profesor=$n[ID]&Tipo=M' class='btn btn-success'>Crear horario para $n[Nombre]</a>";
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
    }
    else
    {
               $ERR_MSG = $class->ERR_ASYSTECO;
   
    }
}
echo "<script>
    $('#anterior-profesor').click(function(){
        $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor=$anterior[ID]')
    })
</script>";
echo "<script>
    $('#siguiente-profesor').click(function(){
        $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor=$siguiente[ID]')
    })
</script>";
echo "<script>
$('#select-edit-guardias').on('change', function(){
    profesor = $(this).val(),
    $('#guardias-response').load('index.php?ACTION=horarios&OPT=guardias&profesor='+profesor)
})
</script>";