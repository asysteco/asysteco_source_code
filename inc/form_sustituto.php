<?php

$profesor = $_GET['ID'];

echo '<div class="container" style="margin-top:75px">';
    echo "<div class='row'>";
        echo "<div class='col-xs-12'>";
            echo "<h2>Elija un sustituto</h2>";
            echo "<form action='index.php' method='GET'>";
            echo "<input class='hidden' name='ID_PROFESOR' value='$profesor'>";
            echo "<input class='hidden' name='OPT' value='add-sustituto'>";
            if($response = $class->query("SELECT DISTINCT Profesores.Nombre, Profesores.ID
            FROM Profesores WHERE NOT EXISTS 
            (SELECT Horarios.ID_PROFESOR FROM Horarios WHERE Horarios.ID_PROFESOR=Profesores.ID) AND TIPO <> 1 ORDER BY Nombre"))
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