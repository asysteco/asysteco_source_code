<?php
echo '<div class="container" style="margin-top:75px">';
    echo "<div class='row'>";
        echo "<div class='col-xs-12'>";
            echo "<h2>Elija un sustituto</h2>";
            echo "<form action='index.php' method='GET'>";
            echo "<input class='hidden' name='ID_PROFESOR' value='$_GET[ID]'>";
            echo "<input class='hidden' name='OPT' value='sustituto'>";
            if($response = $class->query("SELECT DISTINCT $class->profesores.Nombre, $class->profesores.ID
            FROM $class->profesores WHERE NOT EXISTS 
            (SELECT $class->horarios.ID_PROFESOR FROM $class->horarios WHERE $class->horarios.ID_PROFESOR=$class->profesores.ID)"))
            {
                echo "<select id='select_sustituto' name='ID_SUSTITUTO'>";
                    while($fila = $response->fetch_assoc())
                    {
                        echo "<option value='$fila[ID]'>$fila[Nombre]</option>";
                    }
                echo "</select><br><br>";
            }
            else
            {
                $ERR_MSG = $class->ERR_ASYSTECO;
            }
            echo "<button class='btn btn-info' value='profesores' name='ACTION'>Agregar Sustituto</button>";
            echo "</form>";
        echo "</div>";
    echo "</div>";
echo "</div>";
?>