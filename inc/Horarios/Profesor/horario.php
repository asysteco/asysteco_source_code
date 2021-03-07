<div class="container">
    <?php

    $profesor = $_POST['profesor'];

    if (!$n = $class->query("SELECT Nombre, ID, Activo FROM Profesores WHERE ID='$profesor'")->fetch_assoc()) {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
    $sql = "SELECT DISTINCT Tipo
    FROM Horarios h
    WHERE h.ID_PROFESOR='$profesor'";
    if ($response = $class->query($sql)) {
        if ($response->num_rows == 1) {
            $dia = $class->getDate();
            $datosprof = $response->fetch_assoc();
            $franja = $datosprof['Tipo'];
            echo "<h2 style='text-align: center;'>Horario: $n[Nombre]</h2>";
            if ($n['Activo'] == 1) {
                echo "<form action='index.php?ACTION=horarios&OPT=gest-horario' method='POST'>";
                echo "<input type='hidden' name='profesor' value='$n[ID]' />";
                echo "<input type='hidden' name='nProfesor' value='$n[Nombre]' />";
                echo "<button type='submit' id='editar-horario' class='btn btn-success float-left'>Editar horario</button>";
                echo "</form>";
            } else {
                echo "<a class='btn btn-danger tp' class='btn btn-success float-left'><i class='tpt'>Usuario desactivado.</i>Editar horario</a>";
            }
            echo "<a id='eliminar-horario' class='act btn btn-danger float-right' action='remove-horario' profesor='$n[ID]'>Limpiar horario</a>";
            echo "<div id='response'></div>";
            echo "</br>";
            echo "<div class='table-responsive'>";
            echo "<table class='table' style='margin-top: 25px;'>";
            echo "<thead class='thead-dark'>";
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

            foreach ($franjasHorarias[$franja] as $valor => $datos) {
                $Hora = $valor;
                $horaInicioSinSegundos = $class->transformHoraMinutos($datos['Inicio']);
                $horaFinSinSegundos = $class->transformHoraMinutos($datos['Fin']);

                if (!$class->query("SELECT Horarios.*, Cursos.Nombre as Curso, Aulas.Nombre as Aula
                                        FROM (Horarios 
                                            INNER JOIN Cursos ON Horarios.grupo = Cursos.ID)
                                            INNER JOIN Aulas ON Horarios.Aula = Aulas.ID
                                        WHERE ID_PROFESOR='$profesor'
                                        AND Hora='$Hora'
                                        ORDER BY Hora ")->num_rows > 0) {
                    continue;
                }
                echo "<tr id='Hora_$Hora'>";
                echo "<td style='text-align: center; vertical-align: middle;'>$horaInicioSinSegundos<br>$horaFinSinSegundos</td>";
                for ($dialoop = 1; $dialoop <= 5; $dialoop++) {
                    $dia['wday'] == $dialoop ? $dia['color'] = "table-success" : $dia['color'] = '';
                    if ($response = $class->query("SELECT Hora, Dia, Aulas.Nombre as Aula, Cursos.Nombre as Curso, Edificio
                            FROM (Horarios 
                                INNER JOIN Cursos ON Horarios.grupo = Cursos.ID)
                                INNER JOIN Aulas ON Horarios.Aula = Aulas.ID
                            WHERE ID_PROFESOR='$profesor'
                                AND Hora='$Hora'
                                AND Dia='$dialoop'
                            ORDER BY Hora ")) {
                        if ($response->num_rows > 0) {
                            $fila = $response->fetch_all();
                            $m = 2;
                            echo "<td style='text-align: center; vertical-align: middle;' class=' $dia[color]'>";

                            if ($fila[0][3] == 'Guardia') {
                                echo "<span><b>Guardia</b></span>";
                                echo "<br>";
                                echo "<span><b>Edificio " . $fila[0][4] . "</b></span>";
                            } else {
                                echo "<b>Aula: </b>" . $fila[0][2];
                                echo "<br><b>Grupo: </b>";
                                for ($i = 0; $i < count($fila); $i++) {
                                    $m % 2 == 0 ? $espacio = " " : $espacio = "<br>";
                                    echo $espacio . $fila[$i][3];
                                    $m++;
                                }
                            }
                            echo "</td>";
                        } else {
                            echo "<td style='text-align: center; vertical-align: middle;' class=' $dia[color]'></td>";
                        }
                    }
                }
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            include_once('js/update_horario.js');
        } elseif ($response->num_rows > 1) {
            echo "<h1 style='vertical-align: middle; text-align: center;'>Formato no válido, revise su horario...</h1>";
        } else {
            echo "<h1 style='display: block; text-align: center;'>";
            echo "$n[Nombre] no tiene horario";
            echo "</h1>";
            if ($n['Activo'] == 1) {
                echo "<div style='text-align: center;'>";
                echo "<form action='index.php?ACTION=horarios&OPT=gest-horario' method='POST'>";
                echo "<input type='hidden' name='profesor' value='$n[ID]' />";
                echo "<input type='hidden' name='nProfesor' value='$n[Nombre]' />";
                echo "<button type='submit' style='width: 100%;' id='editar-horario' class='btn btn-success'>Crear horario</button>";
                echo "</form>";
                echo "</div>";
            } else {
                echo "<div style='text-align: center;'>";
                echo "<a class='btn btn-danger tp' style='width: 100%;'><i class='tpt'>$n[Nombre] está desactivado</i>Crear horario</a>";
                echo "</div>";
            }
        }
    } else {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
    ?>
</div>