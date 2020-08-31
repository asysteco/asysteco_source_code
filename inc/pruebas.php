<?php
if($_SESSION['Perfil'] === 'Admin')
{ 
 if ($response = $class->query("SELECT $class->profesores.ID, $class->profesores.Nombre, $class->profesores.Iniciales, $class->profesores.Activo FROM $class->profesores WHERE $class->profesores[ID]='$_GET[ID]'")
 {
   if ($response->num_rows > 0)
   {
    echo "<div id='guardias'></div>";
    echo "<h2>Edición de Guardias</h2>";
    echo "</br><table id='tabla_profesores' class='table table-hover'>";
    echo "<thead>";
        echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Nombre</th>";
            echo "<th>Iniciales</th>";
            echo "<th>Activo</th>";
        echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while ($fila = $response->fetch_assoc())
    {
        echo "<tr id='profesor_$fila[ID]' class='row_prof'>";
        if($fila['Tipo'] == 'Admin')
            {
              echo "<td>$fila[ID]</td>";
              echo "<td>$fila[Nombre]</td>";
              echo "<td>$fila[Iniciales]</td>";
              echo "<td>$activo</td>";
            }
    }
   }
   else
   {
       $ERR_MSG = "Solo es una prueba";
   }
 }
 else
 {
    $ERR_MSG = "Solo es una prueba";

 }
}
else
{
    $ERR_MSG = "Solo es una prueba";

}