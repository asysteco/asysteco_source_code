<?php

$profesor = $_POST['profesor'] ?? '';

?>

<div class="container">
    <div class="wrapper fadeInDown">
        <div id="formContent">        
            <h3 style='margin: 15px;'>Seleccione un profesor</h3>
            <i>Al confirmar estos cambios, clonará el horario al profesor seleccionado en el siguiente desplegable.</i>
            <form action='index.php' method='GET'>
                <input type='text' class='d-none' name='ID_PROFESOR' value='$profesor'>
                <?php
                if ($profesor) {
                    $sql = "SELECT DISTINCT Profesores.Nombre, Profesores.ID
                    FROM Profesores WHERE TIPO <> 1 AND Activo = 1 AND Sustituido = 0 AND ID <> $profesor ORDER BY Nombre";
                    if($response = $class->query($sql)) {
                    ?>
                        <select id='select_sustituto' name='ID_CLONADO'>
                            <?php
                            while($fila = $response->fetch_assoc()) {
                            echo "<option value='$fila[ID]'>$fila[Nombre]</option>";
                            }
                            ?>
                        </select><br><br>
                    <?php
                    } else {
                        $ERR_MSG = $class->ERR_ASYSTECO;
                    }
                }
                ?>
            </form>
        </div>
    </div>
</div>
