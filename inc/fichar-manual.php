<?php

echo '<div class="container" style="margin-top:75px">';
    echo "<div class='row'>";
        echo "<div class='col-xs-12'>";
            echo "<h2>Fichaje Manual</h2>";
            echo "<form action='index.php?ACTION=fichar-mysql-manual' method='POST'>";
                if($response = $class->query("SELECT Nombre, ID FROM Profesores WHERE Tipo <> 1 ORDER BY Nombre" ))
                {
                    echo "<select class='select_profesor' name='ID' id='fichar-manual'>";
                        while($fila = $response->fetch_assoc())
                        {
                            echo "<option value='$fila[ID]'>$fila[Nombre]</option>";
                        }
                    echo "</select>";
                }
                else
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
                echo "<br><br>";
                echo "<label>Hora Fichaje Entrada </label>";
                echo "<input id='add-hora-entrada' type='time' name='horaentrada' min='06:00' max='22:00' required style='margin-left: 2%;'>";
                echo "<br><br>";
                
                if($options['ficharSalida'] == 1)
                {
                    echo "<label>Hora Fichaje Salida </label>";
                    echo "<input id='add-hora-salida' type='time' name='horasalida' min='06:00' max='22:00' required style='margin-left: 3%;'>";
                    echo "<br><br>";
                }
                echo "<input id='add-fecha' class='form-control' name='dia' type='text' placeholder='Seleccione una fecha' autocomplete='off'>";
                echo "<br>";
                echo "<a href='index.php?ACTION=profesores' class='btn btn-danger pull-left'>Cancelar</a>";
                echo "<button id='add-manual' action='add' class='btn btn-info pull-right'>Realizar Fichaje</button>";
            echo "</form>";
        echo "</div>";
    echo "</div>";
echo "</div>";

echo "<script src='js/edit-manual.js'></script>";