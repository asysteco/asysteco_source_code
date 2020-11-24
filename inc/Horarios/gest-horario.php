<?php

$nombreProfesor = $_GET['nProfesor'];
$profesor = $_GET['profesor'];

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
$rHoras = $class->conex->query("SELECT Hora, Inicio, Fin FROM Horas ORDER BY Hora");
if ($rHoras->num_rows > 0) {
    $nombresHoras = $rHoras->fetch_all();
    foreach ($nombresHoras as $key => $value) {
        if (isset($value[0]) && isset($value[1])) {
            $nombre = $value[1] . ' - ' . $value[2];
            $id = $value[0];
            $totalHoras[$nombre] = $id;
        }
    }
}

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
<div class="container" style="margin-top:75px">
    <div class='row'>
        <div class='col-xs-12'>
            <h1 id="profesor" data="<?=$profesor;?>">Gestionar Horario de <b><?=$nombreProfesor?></b></h1>
            <div class="add-fields">
                <select id="add-dia" class="form-control" style="display: inline-block; width: 15%;">
                    <option value=''>Selec. día...</option>
                    <?php
                    foreach($totalDias as $key => $value) {
                        echo "<option value='$value'>$key</option>";
                    }
                    ?>
                </select>
                <select id="add-edificio" class="form-control" style="display: inline-block; width: 15%;">
                    <option value=''>Selec. Edificio...</option>
                    <?php
                    if (isset($options['edificios'])) {
                        for($i = 1; $i <= $options['edificios']; $i++) {
                            echo "<option value='$i'> Edificio $i</option>";
                        }
                    }
                    ?>
                </select>
                <select id="add-hora" class="form-control" style="display: inline-block; width: 15%;">
                    <option value=''>Selec. hora...</option>
                    <?php
                    foreach($totalHoras as $key => $value) {
                        echo "<option value='$value'>$key</option>";
                    }
                    ?>
                </select>
                <select id="add-aula" class="form-control" style="display: inline-block; width: 15%;">
                    <option value=''>Selec. Aula...</option>
                    <?php
                    foreach($totalAulas as $key => $value) {
                        echo "<option value='$value'>$key</option>";
                    }
                    ?>
                </select>
                <select id="add-curso" class="form-control" style="display: inline-block; width: 15%;">
                    <option value=''>Selec. Curso/Grupo...</option>
                    <?php
                    foreach($totalCursos as $key => $value) {
                        echo "<option value='$value'>$key</option>";
                    }
                    ?>
                </select>
                <a action="add" class="btn btn-success act">Añadir Hora</a>
            </div>
            <div id="programar-horario">
                <input type="text" id="fecha-programar-horario" class="form-control" autocomplete="off" placeholder="Selecciona una fecha..." style="display: inline-block; width: 20%;">
                <a action="program" class="btn btn-success act" style="display: inline-block; margin-top: -4px;">Programar Horario</a>
            </div>
            <table id="tableHorarios" class="table table-striped">
                <thead>
                    <tr style="text-align: center;">
                        <th style="text-align: center; font-size: 15pt;">Hora</th>
                        <th style="text-align: center; font-size: 15pt;">Edificio</th>
                        <th style="text-align: center; font-size: 15pt;">Aula</th>
                        <th style="text-align: center; font-size: 15pt;">Curso/Grupo</th>
                        <th style="text-align: center; font-size: 15pt;">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
            <?php        
                if ($response = $class->query(
                    "SELECT h.*, ds.Diasemana, hs.Inicio, hs.Fin, a.Nombre as nAula, c.Nombre as nCurso
                    FROM (((Horarios h
                        INNER JOIN Diasemana ds ON h.Dia = ds.ID)
                        INNER JOIN Horas hs ON h.Hora = hs.Hora)
                        INNER JOIN Cursos c ON h.Grupo = c.ID)
                        INNER JOIN Aulas a ON h.Aula = a.ID
                    WHERE ID_PROFESOR = '$profesor' ORDER BY Dia, h.Hora")) {
                    if ($response->num_rows > 0) {
                        $ultimodia = '';
                        while($row = $response->fetch_object()){
                            if ($ultimodia !== $row->Diasemana) {
                                echo "<tr style='text-align: center; background-color: black; color: white; font-size: 15pt;'>";
                                echo "<td colspan='100%'>".$row->Diasemana."</td>";
                                echo "</tr>";
                            }
                            echo "<tr id='fila_".$row->ID."' style='text-align: center;'>";
                                echo "<td>". $row->Inicio . ' - ' . $row->Fin ."</td>";
                                echo "<td>". $row->Edificio ."</td>";
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
                                echo "<td><span data='".$row->ID."' action='remove' class='glyphicon glyphicon-trash remove act'></span></td>";
                            echo "</tr>";
                            $ultimodia = $row->Diasemana;
                        }
                    } else {
                        echo "<tr style='text-align: center;'>";
                            echo "<td colspan='100%'><h3>No hay hora asignadas.</h3></td>";
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
<a id="cancel-btn" action="cancel" class="btn btn-danger act">Cancelar cambios</a>
<a id="update-btn" action="update" class="btn btn-success act">Aplicar cambios</a>
<div id="loading" class="col-xs-12" style="position: absolute; top: 0; left: 0; width: 100%; height: 100vh; text-align: center;">
    <div class="caja" style="margin-top: 35vh; display: inline-block; padding: 25px; background-color: white; border-radius: 10px; box-shadow: 4px 4px 16px 0 #808080bf;">
        <div>
            <img src="resources/img/loading.gif" alt="Cargando...">
            <h2 id="loading-msg"></h2>
        </div>
    </div>
</div>

<script src="js/edit-horario.js"></script>