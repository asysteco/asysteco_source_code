<?php

$nombreProfesor = $_POST['nProfesor'] ?? '';
$profesor = $_POST['profesor'] ?? '';
$programDate = $_POST['programDate'] ?? '';

if ($programDate !== '') {
    $splitDate = explode('/', $programDate);
    $mysqlProgramDate = $splitDate[2] . '-' . $splitDate[1] . '-' . $splitDate[0];
    

    $response = $class->query(
        "SELECT h.*, ds.Diasemana, hs.Inicio, hs.Fin, a.Nombre as nAula, c.Nombre as nCurso
        FROM T_horarios h
            INNER JOIN Diasemana ds ON h.Dia = ds.ID
            INNER JOIN Horas hs ON h.Hora = hs.Hora AND h.Tipo = hs.Tipo
            INNER JOIN Cursos c ON h.Grupo = c.ID
            INNER JOIN Aulas a ON h.Aula = a.ID
        WHERE ID_PROFESOR = '$profesor'
            AND Fecha_incorpora = '$mysqlProgramDate'
        ORDER BY Dia, h.Hora");

    $defaultTipo = '';
    $hasHorario = false;
    $rHasHorario = $class->query("SELECT ID, Tipo FROM T_horarios WHERE ID_PROFESOR = '$profesor'");
    if ($rHasHorario && $rHasHorario->num_rows > 0) {
        $hasHorarioData = $rHasHorario->fetch_object();
        $hasHorario = true;
        
        if (empty($defaultTipo)) {
            $defaultTipo = $hasHorarioData->Tipo;
        }
    }

    $totalDias = [];
    $rDiasemana = $class->conex->query("SELECT ID, Diasemana FROM Diasemana ORDER BY ID");
    if ($rDiasemana->num_rows > 0) {
        $nombresDias = $rDiasemana->fetch_all();
        foreach ($nombresDias as $key => $value) {
            if (isset($value[0]) && isset($value[1])) {
                $nombre = strtoupper($value[1]);
                $id = $value[0];
                $totalDias[$nombre] = $id;
            }
        }
    }
    
    $totalHoras = [];
    $rHoras = $class->conex->query("SELECT Hora, Inicio, Fin, Tipo FROM Horas ORDER BY Hora");
    if ($rHoras->num_rows > 0) {
        while ($nombresHoras = $rHoras->fetch_object()) {
            $horaInicio = $class->transformHoraMinutos($nombresHoras->Inicio);
            $horaFin = $class->transformHoraMinutos($nombresHoras->Fin);
            $totalHoras[$nombresHoras->Tipo][$nombresHoras->Hora] = $horaInicio . ' - ' . $horaFin;
            
            if (empty($defaultTipo)) {
                $defaultTipo = $nombresHoras->Tipo;
            }
        }
    }
    $totalTipos = count($totalHoras);
    
    $totalCursos = [];
    $rCurso = $class->conex->query("SELECT ID, Nombre FROM Cursos ORDER BY Nombre");
    if ($rCurso->num_rows > 0) {
        $nombresCursos = $rCurso->fetch_all();
        foreach ($nombresCursos as $key => $value) {
            if (isset($value[0]) && isset($value[1])) {
                $nombre = strtoupper($value[1]);
                $id = $value[0];
                $totalCursos[$nombre] = $id;
            }
        }
    }
    
    $totalAulas = [];
    $rAula = $class->conex->query("SELECT ID, Nombre FROM Aulas ORDER BY Nombre");
    if ($rAula->num_rows > 0) {
        $nombresAulas = $rAula->fetch_all();
        foreach ($nombresAulas as $key => $value) {
            if (isset($value[0]) && isset($value[1])) {
                $nombre = strtoupper($value[1]);
                $id = $value[0];
                $totalAulas[$nombre] = $id;
            }
        }
    }
    ?>
    <div class="container">
        <div class='row'>
            <div class='col-12'>
                <h1 id="profesor" data="<?=$profesor;?>">Programar Horario para <b><?=$nombreProfesor?></b></h1>
                <h4 id="program-date" data="<?=$mysqlProgramDate?>" data-real="<?=$programDate?>">*<i> Al "Programar este horario", entrará en vigor el día <?=$programDate?></i></h4>
                <div class="add-fields">
                    <?php
                    if (!$hasHorario && $totalTipos > 1) {
                        echo '<select id="add-tipo" class="form-control" style="display: inline-block; width: 15%; min-width: 150px; max-width: 15%; margin-bottom: 5px;">';
                        foreach($totalHoras as $tipo => $horas) {
                            if (empty($defaultTipo)) {
                                $defaultTipo = $tipo;
                            }
                            echo "<option value='$tipo'>$tipo</option>";
                        }
                        echo '</select>';
                    } elseif ($hasHorario) {
                        echo "<input id='add-tipo' type='hidden' class='form-control' value='$defaultTipo'>";
                    } else {
                        echo '<input id="add-tipo" type="hidden" class="form-control" value="Mañana">';
                    }
                    ?>
                    <select id="add-dia" class="form-control" style="display: inline-block; width: 15%; min-width: 150px; max-width: 15%; margin-bottom: 5px;">
                        <?php
                        foreach($totalDias as $key => $value) {
                            echo "<option value='$value'>$key</option>";
                        }
                        ?>
                    </select>
                    <?php
                    if (isset($options['edificios']) && $options['edificios'] > 1) {
                        echo '<select id="add-edificio" class="form-control" style="display: inline-block; width: 15%; min-width: 150px; max-width: 15%; margin-bottom: 5px;">';
                            echo "<option value=''>Selec. Edificio</option>";
                                for($i = 1; $i <= $options['edificios']; $i++) {
                                    echo "<option value='$i'> Edificio $i</option>";
                                }
                        echo '</select>';
                    } else {
                        echo '<input id="add-edificio" type="hidden" class="form-control" style="display: inline-block; width: 15%; min-width: 150px; max-width: 15%; margin-bottom: 5px;" value="1">';
                    }

                    foreach($totalHoras as $tipo => $horas) {
                        $selected = $defaultTipo === $tipo ? 'inline-block' : 'none';
                        echo "<select id='add-hora-$tipo' class='form-control select-hora' style='display: $selected; width: 15%; min-width: 150px; max-width: 15%; margin-bottom: 5px;'>";
                        foreach($horas as $hora => $franja) {
                            echo "<option value='$hora'>$franja</option>";
                        }
                        echo '</select> ';
                    }
                    ?>
                    <select id="add-aula" class="form-control" style="display: inline-block; width: 15%; min-width: 150px; max-width: 15%; margin-bottom: 5px;">
                        <option value=''>Selec. Aula</option>
                        <?php
                        foreach($totalAulas as $key => $value) {
                            echo "<option value='$value'>$key</option>";
                        }
                        ?>
                    </select>
                    <select id="add-curso" class="form-control" style="display: inline-block; width: 15%; min-width: 150px; max-width: 15%; margin-bottom: 5px;">
                        <option value=''>Selec. Curso/Grupo</option>
                        <?php
                        foreach($totalCursos as $key => $value) {
                            echo "<option value='$value'>$key</option>";
                        }
                        ?>
                    </select>
                    <a action="add" class="btn btn-success act" style="margin-bottom: 5px;">Añadir Hora</a>
                </div>
                <table id="tableHorarios" class="table table-striped">
                    <thead>
                        <tr style="text-align: center;">
                            <th style="text-align: center; font-size: 15pt;">Hora</th>
                            <?php
                                if (isset($options['edificios']) && $options['edificios'] > 1) {
                                    echo '<th style="text-align: center; font-size: 15pt;">Edificio</th>';
                                }
                            ?>
                            <th style="text-align: center; font-size: 15pt;">Aula</th>
                            <th style="text-align: center; font-size: 15pt;">Curso / Grupo</th>
                            <th style="text-align: center; font-size: 15pt;">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php        
                    if ($response) {
                        if ($response->num_rows > 0) {
                            echo '<a id="cancel-program" action="cancel-program" class="btn btn-danger act">Cancelar programación</a>';
                            echo '<a id="apply-program" action="apply-program" class="btn btn-success act">Programar este horario</a>';
                            $ultimodia = '';
                            while($row = $response->fetch_object()) {
                                if ($ultimodia !== $row->Diasemana) {
                                    echo "<tr style='text-align: center; background-color: black; color: white; font-size: 15pt;'>";
                                    echo "<td colspan='100%'>".$row->Diasemana."</td>";
                                    echo "</tr>";
                                }
                                echo "<tr id='fila_".$row->ID."' style='text-align: center;'>";
                                    echo "<td>". $row->Inicio . ' - ' . $row->Fin ."</td>";
                                    if (isset($options['edificios']) && $options['edificios'] > 1) {
                                        echo "<td>". $row->Edificio ."</td>";
                                    }
                                    echo "<td>";
                                        echo "<select id='select_".$row->ID."' data-info='".$row->ID."' data-field='Aula' class='form-control update'>";
                                            foreach($totalAulas as $key => $value) {
                                                $select = $key == strtoupper($row->nAula) ? 'selected': '';
                                                echo "<option value='$value' $select>$key</option>";
                                            }
                                        echo "</select>";
                                    echo "</td>";
                                    echo "<td>";
                                        echo "<select id='select_".$row->ID."' data-info='".$row->ID."' data-field='Grupo' class='form-control update'>";
                                            foreach($totalCursos as $key => $value) {
                                                $select = $key == strtoupper($row->nCurso) ? 'selected': '';
                                                echo "<option value='$value' $select>$key</option>";
                                            }
                                        echo "</select>";
                                    echo "</td>";
                                    echo "<td><span data='".$row->ID."' action='remove' style='font-size: 25px;' class='fa fa-trash-o remove act'></span></td>";
                                echo "</tr>";
                                $ultimodia = $row->Diasemana;
                            }
                        } else {
                            echo "<tr style='text-align: center;'>";
                                echo "<td colspan='100%'><h3>No hay horas asignadas.</h3></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr style='text-align: center;'>";
                            echo "<td colspan='100%'><h3>" . $class->ERR_ASYSTECO . "</h3></td>";
                        echo "</tr>";
                    }
                ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <a id="cancel-btn" action="cancel" class="btn btn-danger act" style="display: none;">Cancelar cambios</a>
    <a id="update-btn" action="update" class="btn btn-success act" style="display: none;">Aplicar cambios</a>
    
    <script src="js/edit-t_horario.js"></script>
<?php
}
