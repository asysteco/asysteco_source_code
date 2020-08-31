<?php

if(isset($_GET['Numero']))
{
    if($_SESSION['Perfil'] === 'Admin')
    {
    echo '<div class="container-fluid" style="margin-top:50px">';
        echo "<div class='row'>";
        echo "<div id='qreader' class='col-xs-12 col-md-4' >";
            echo "<h3>Fichaje</h3>";
            include($dirs['inc'] . 'qr-reader.php');
        echo "</div>";
        echo "<div class='col-xs-12 col-md-8' style='text-align: center;'>";
            include($dirs['inc'] . 'filtro-edif-guardias.php');
            include($dirs['inc'] . 'contenido-guardias.php');
        echo "</div>";
        echo "</div>";
    echo "</div>";
    }
    else
    {
    echo '<div class="container-fluid" style="margin-top:50px">';
        echo "<div class='row'>";
        echo "<div class='col-xs-12' style='text-align: center;'>";
            include($dirs['inc'] . 'filtro-edif-guardias.php');
            include($dirs['inc'] . 'contenido-guardias.php');
        echo "</div>";
        echo "</div>";
    echo "</div>";
    }
}
else
{
    header('Location: index.php');
}