<?php

$profesor = $_GET['ID'];

if($response = $class->query("SELECT ID, Iniciales, Nombre, Tutor, Activo, Sustituido, TIPO FROM Profesores WHERE ID='$profesor'"))
{
    $datos = $response->fetch_assoc();
    echo '<div id="formContent">';
        echo '<h1 style="margin: 15px;">Edición de Personal</h1>';
        echo "<form  id='formulario-edit' method='POST' action='$_SERVER[REQUEST_URI]'>";
            echo "<input type='text' class='d-none' name='ID' value='$datos[ID]'>";
            echo "<label class='etiquetas'>Iniciales</label></br>";
            echo "<input type='text' name='Iniciales' value='$datos[Iniciales]'></br>";
            echo "<label class='etiquetas'>Nombre</label></br>";
            echo "<input type='text' name='Nombre' value='$datos[Nombre]'></br>";
            echo "<label class='etiquetas'>Tutor</label></br>";
            echo "<input id='grupo-tutor' type='text' name='Tutor' value='$datos[Tutor]'></br>";
            if($response2 = $class->query("SELECT DISTINCT ID, Nombre FROM Cursos WHERE Nombre != '' ORDER BY Nombre")) {
                echo "<select id='grupo-tutor-select' class='entrada' style='display: none;'>";
                    echo "<option value='No'>No</option>";
                    while($fila = $response2->fetch_assoc())
                    {
                        echo "<option value='$fila[Nombre]'>$fila[Nombre]</option>";
                    }
                echo "</select>";
            } else {
                echo "<span style='color:red;'>$class->ERR_ASYSTECO</span>";
            }
            echo "</br><label class='etiquetas'>Docente</label></br>";
            echo "<input type='text' class='d-none' name='Docente' value='$datos[TIPO]'>";
            if ($response) {
                $docente = $datos['TIPO'] ==3? 'No':'Si';
            }
            echo "<h4>$docente</h4>";
            echo "<label class='etiquetas'>Activo</label></br>";
            echo "<input type='text' class='d-none' id='Activo' name='Activo' value='$datos[Activo]'>";
            if($response) {
                $activo = $datos['Activo'] == 1? 'Si': 'No';
            }
            echo "<h4>$activo</h4>";
            echo "<label class='etiquetas'>Sustituido</label></br>";
            echo "<input type='text' class='d-none' name='Sustituido' value='$datos[Sustituido]'>";
            if($response) {
                $sustituido = $datos['Sustituido'] == 1? 'Si': 'No';
            }
            echo "<h4>$sustituido</h4>";
            if($resp = $class->query("SELECT Nombre, ID FROM Profesores WHERE ID=$profesor AND Sustituido=0"))
            {
                if($resp->num_rows > 0)
                {
                    echo "<a profesor='$datos[ID]' class='btn btn-info act' action='modal-form-sustituir'>Sustituir</a><br><br>";
                } 
                else
                {
                    echo "<a profesor='$datos[ID]' id='profe_retirar' class='btn btn-warning act' action='modal-fin-sustitucion'>Retirar Sustituto</a><br><br>";
                }
            }
        echo "</form>";
    echo '</div>';
} else {
    $ERR_MSG = $class->ERR_ASYSTECO;
}
