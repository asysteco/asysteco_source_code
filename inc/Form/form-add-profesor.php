<?php
echo '<div class="container">';
    echo '<div class="wrapper fadeInDown">';
        echo '<div id="formContent">';
            echo "<h1>Registrar Profesor</h1>";
            echo "<form action='index.php?ACTION=profesores&OPT=add-profesor' method='post'>";
            echo "<input type='text' name='Iniciales' value='$_POST[Iniciales]' class='form-control' placeholder='Iniciales Profesor'>";
            echo "</br>";
            echo "<input type='text' name='Nombre' value='$_POST[Nombre]' class='form-control' placeholder='Nombre Profesor (Completo)'>";
            echo "</br>";
            echo "<div style='display: inline'>";
            echo "<button class='btn btn-success float-right' style='margin-right: 33px; margin-bottom: 10px; margin-top: 15px' value='add' name='add-profesor'>Registrar</button>";
            echo "<a href='index.php?ACTION=profesores' class='btn btn-danger float-left' style='margin-left: 33px; margin-top: 15px'>Cancelar</a>";
            echo "</div>";
            echo "</form>";
        echo "</div>";
    echo "</div>";
echo "</div>";