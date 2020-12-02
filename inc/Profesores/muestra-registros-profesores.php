<?php

$sql = "SELECT ID, Iniciales, Nombre, Tutor, Activo, Sustituido FROM Profesores ORDER BY Nombre, Iniciales";

$result = $class->query($sql);
if (! empty($result)) 
{
    echo "<h2>Registros de Horarios</h2>"; 
    echo "<table id='userTable' class='table'>
        <thead>
            <tr>
                <th>Iniciales</th>
                <th>Nombre</th>
                <th>Tutor</th>
                <th>Activo</th>
                <th>Sustituido</th>
            </tr>
        </thead>
    ";
    while($row = $result->fetch_assoc()) 
        {
            
            if($fila['Activo'] == 1)
            {
              $activo = 'Si';
            }
            else
            {
              $activo = 'No';
            }

            if($fila['Sustituido'] == 0)
            {
              $sustituido = 'No';
            }
            else
            {
              $sustituido = 'Si';
            }

    echo "
        <tbody>
            <tr>
                <td>$row[Iniciales]</td>
                <td>$row[Nombre]</td>
                <td>$row[Tutor]</td>
                <td>$activo</td>
                <td>$sustituido</td>
            </tr>
        ";
        }
}
echo "
    </tbody>
</table>
";