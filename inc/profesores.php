<?php
if($_SESSION['Perfil'] === 'Admin')
{ 
 if ($response = $class->query("SELECT $class->profesores.ID, $class->profesores.Nombre, $class->profesores.Iniciales, $class->perfiles.Tipo, $class->profesores.Activo, $class->profesores.Sustituido FROM $class->profesores INNER JOIN $class->perfiles ON $class->profesores.TIPO=$class->perfiles.ID WHERE $class->profesores.TIPO<>1 ORDER BY Nombre ASC"))
 {
   if ($response->num_rows > 0)
   {
    echo '<div class="container" style="margin-top:50px">';
    echo "<div id='horario'></div>";
    echo "<h2>Profesores</h2>";
    echo "<a href='index.php?ACTION=profesores&OPT=add-profesor' class='btn btn-info'>Registrar Profesor</a></br>";
    echo "<br><h4 style='display: inline-block; margin-right: 15px;'>Buscar profesor: </h4>
      <span class='glyphicon glyphicon-search'></span> 
      <input style='width: 25%; display: inline-block;' id='busca_prof' class='form-control' type='text' placeholder='Buscar Profesor...' autocomplete='off'><br>";
    echo "</br><table id='tabla_profesores' class='table table-hover'>";
    echo "<thead>";
        echo "<tr>";
            echo "<th>Nombre</th>";
            echo "<th>Iniciales</th>";
            echo "<th>Tipo</th>";
            echo "<th>Activo</th>";
            echo "<th>Sustituido</th>";
            echo "<th>Editar</th>";
            echo "<th>Asistencias</th>";
            echo "<th>Desactivar/Activar</th>";
            echo "<th>Reset. Contraseña</th>";
        echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
        while ($fila = $response->fetch_assoc())
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
            
            echo "<tr id='profesor_$fila[ID]' class='row_prof'>";
            if($fila['Tipo'] == 'Admin')
            {
              echo "<td>$fila[Nombre]</td>";
              echo "<td>$fila[Iniciales]</td>";
              echo "<td>$fila[Tipo]</td>";
              echo "<td>$activo</td>";
              echo "<td></td>";
              echo "<td></td>";
              echo "<td></td>";
              echo "<td></td>";
              echo "<td></td>";
            }
            else
            {
              echo "<td class='row_show' title='Haz click para ver el horario de $fila[Nombre]'>$fila[Nombre]</td>";
              echo "<td class='row_show' title='Haz click para ver el horario de $fila[Nombre]'>$fila[Iniciales]</td>";
              echo "<td class='row_show' title='Haz click para ver el horario de $fila[Nombre]'>$fila[Tipo]</td>";
              echo "<td class='row_show' title='Haz click para ver el horario de $fila[Nombre]'>$activo</td>";
              echo "<td class='row_show' title='Haz click para ver el horario de $fila[Nombre]'>$sustituido</td>";
              echo "<td><a title='Editar a $fila[Nombre]' href='index.php?ACTION=profesores&OPT=edit&ID=$fila[ID]'><span class='glyphicon glyphicon-pencil edit_icon'></span></a></td>";
              echo "<td><a title='Mostrar asistencias de $fila[Nombre]' href='index.php?ACTION=asistencias&ID=$fila[ID]'><span class='glyphicon glyphicon-list list_icon'></span></a></td>";
              if($fila['Activo'] == 1)
              {
                echo "<td>
                  <a href='index.php?ACTION=profesores&OPT=des-act&ID=$fila[ID]'
                      title='Desctivar a $fila[Nombre]'
                      onclick=\"return confirm('¿Seguro que desea realizar este cambio? Utilice solo esta opción si el profesor deja el centro por motivos de jubilación, fin de una sustitución o similares.')\">
                      <span class='glyphicon glyphicon-remove remove_icon'></span>
                  </a>
                </td>";
              }
              else
              {
                echo "<td>
                  <a href='index.php?ACTION=profesores&OPT=des-act&ID=$fila[ID]'
                      title='Activar a $fila[Nombre]'
                      onclick=\"return confirm('¡Cuidado! Si realiza este cambio ahora, se considerará que el profesor vuelve a trabajar en el centro.')\">
                      <span class='glyphicon glyphicon-ok add_icon'></span>
                  </a>
                </td>";
              }
              echo "<td>
                  <a class='reset_icon'
                      title='Restablecer contraseña de $fila[Nombre]'
                      href='index.php?ACTION=profesores&OPT=reset-pass&ID=$fila[ID]'
                      onclick=\"return confirm('Va a restablecer la contraseña de $fila[Nombre]  ¿Desea continuar?.')\">
                      <span class='glyphicon glyphicon-refresh reset_icon'></span>
                  </a>
              </td>";
            echo '</tr>';
            }
        }
    echo "</tbody>";
    echo "</table>";
    echo '</div>';
    include_once($dirs['public'] . 'js/profesores.js');
   }
   else
   {
    $ERR_MSG = "No existen profesores, debe importarlos.<br>";
    $ERR_MSG .= "<a href='$_SERVER[PHP_SELF]?ACTION=profesores&OPT=import-form' class='btn btn-success'>Aceptar</a>";
   }
 }
 else
 {
   $ERR_MSG = $class->ERR_ASYSTECO;
 }
}
else
{
  $ERR_MSG = "No tiene permisos de administrador.";
}
include_once "js/filtro_prof.js";
?>
