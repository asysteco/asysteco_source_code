<?php

$profesor = $_GET['ID'];

echo '<div class="container">';
    echo '<div class="wrapper fadeInDown">';
        echo '<div id="formContent">';        
            echo "<h1 style='margin: 15px;'>Elija un sustituto</h1>";
            echo "<form action='index.php' method='GET'>";
            echo "<input type='text' class='d-none' name='ID_PROFESOR' value='$profesor'>";
            echo "<input type='text' class='d-none' name='OPT' value='add-sustituto'>";
            if($response = $class->query("SELECT DISTINCT Profesores.Nombre, Profesores.ID
            FROM Profesores WHERE TIPO <> 1 AND Activo = 1 AND Sustituido = 0 ORDER BY Nombre"))
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
            echo "</form>";
        echo "</div>";
    echo "</div>";
echo "</div>";
