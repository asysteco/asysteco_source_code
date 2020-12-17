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
            echo "<a id='sustituto_cancelar' href='$_SERVER[HTTP_REFERER]' class='btn btn-danger pull-left'>Cancelar</a>";
            echo "<button id='sustituto_agregar' class='btn btn-success pull-right' value='profesores' name='ACTION'>Agregar</button>";
            echo "</form>";
        echo "</div>";
    echo "</div>";
echo "</div>";
?>