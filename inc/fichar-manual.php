<?php

echo '<div class="container" style="margin-top:75px">';
    echo "<div class='row'>";
        echo "<div class='col-xs-12'>";
            echo "<h2>Fichaje Manual</h2>";
            echo "<form action='index.php?ACTION=fichar-mysql-manual' method='POST'>";
            if($response = $class->query("SELECT Nombre, ID FROM Profesores WHERE Tipo <> 1 ORDER BY Nombre" ))
                    {
                echo "<select id='select_sustituto' name='ID'>";
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
                echo "<input type='time' name='hora' min='06:00' max='22:00' required>";
                echo "<br><br>";
                echo "<input id='fichar-manual' class='form-control' name='dia' type='text' placeholder='' autocomplete='off'>";
                echo "<br>";
                echo "<a href='index.php?ACTION=profesores' class='btn btn-danger pull-left'>Cancelar</a>";
                echo "<button class='btn btn-info pull-right'>Realizar Fichaje</button>";
            echo "</form>";
        echo "</div>";
    echo "</div>";
echo "</div>";
