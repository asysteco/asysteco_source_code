<?php
echo '<div class="container" style="margin-top:75px">';
    echo "<div class='row'>";
        echo "<div class='col-xs-12'>";
            echo "<h2>Registrar Profesor</h2>";
            echo "<form action='index.php?ACTION=profesores&OPT=add-profesor' method='post'>";
            echo "<input type='text' name='Iniciales' value='$_POST[Iniciales]' class='form-control' placeholder='Iniciales Profesor'>";
            echo "</br>";
            echo "<input type='text' name='Nombre' value='$_POST[Nombre]' class='form-control' placeholder='Nombre Profesor (Completo)'>";
            echo "</form>";
            echo "</br>";
            echo "<button class='btn btn-info' value='add' name='add-profesor'>Registrar Profesor</button>";
            echo "</form>";
        echo "</div>";
    echo "</div>";
echo "</div>";