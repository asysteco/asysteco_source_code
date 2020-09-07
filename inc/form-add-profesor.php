<?php
echo '<div class="container" style="margin-top:75px">';
    echo "<div class='row'>";
        echo "<div class='col-xs-12'>";
            echo "<h2>Registrar Profesor</h2>";
            echo "<div>";
            echo "<form action='index.php?ACTION=profesores&OPT=add-profesor' method='post'>";
            echo "<input type='text' id='iniciales' placeholder='Iniciales Profesor'";
            echo "<input type='text' id='nombre' placeholder='Nombre Profesor (Completo)'";
            echo "</form>";
            echo "</div>";
            if($response = $class->query("INSER INTO Profesores VALUES (Iniciales, Nombre)"))
            {
                $MSG = "Profesor Añadido Correctamente";
            }
            else
            {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
            echo "<button class='btn btn-info' value='add' name='add-profesor'>Registrar Profesor</button>";
            echo "</form>";
        echo "</div>";
    echo "</div>";
echo "</div>";
?>