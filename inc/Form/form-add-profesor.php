<?php
echo '<div class="container">';
    echo "<div class='row'>";
        echo "<div class='col-12'>";
            echo "<h1>Registrar Profesor</h1>";
            echo "<form action='index.php?ACTION=profesores&OPT=add-profesor' method='post'>";
            echo "<input type='text' name='Iniciales' value='$_POST[Iniciales]' class='form-control' placeholder='Iniciales Profesor'>";
            echo "</br>";
            echo "<input type='text' name='Nombre' value='$_POST[Nombre]' class='form-control' placeholder='Nombre Profesor (Completo)'>";
            echo "</br>";
            echo "<div style='display: inline'>";
            echo "<button class='btn btn-success float-right' value='add' name='add-profesor'>Registrar Profesor</button>";
            echo "<a href='$_SERVER[HTTP_REFERER]' class='btn btn-danger float-left'>Cancelar</a>";
            echo "</div>";
            echo "</form>";
        echo "</div>";
    echo "</div>";
echo "</div>";