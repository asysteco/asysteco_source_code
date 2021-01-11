<?php
if($_SESSION['Perfil'] === 'Admin')
{ 
 if ($response = $class->query("SELECT $class->profesores.ID, $class->profesores.Nombre, $class->profesores.Iniciales, $class->profesores.Activo, $class->profesores.Sustituido FROM $class->profesores INNER JOIN $class->perfiles ON $class->profesores.TIPO=$class->perfiles.ID WHERE $class->profesores.TIPO<>1 ORDER BY Nombre ASC"))
 {
   if ($response->num_rows > 0)
   {
    echo '<div class="container">';
    echo "<div id='horario'></div>";
    echo "<h1>Profesores</h1>";
    echo "<br><h4 style='display: inline-block; margin-right: 15px;'>Buscar profesor: </h4>
      <input style='width: 25%; display: inline-block;' id='busca_prof' class='form-control' type='text' placeholder='Buscar Profesor...' autocomplete='off'>
      <label for='busca_prof'> <span style='font-size: 20px;' class='fa fa-search'></span></label><br>";
    echo "<div class='table-responsive'>";
    echo "</br><table id='tabla_profesores' class='table table-hover'>";
    echo "<thead class='thead-dark'>";
        echo "<tr>";
            echo "<th>Nombre</th>";
            echo "<th>Iniciales</th>";
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
              echo "<td class='row_show' title='Haz click para ver el horario de $fila[Nombre]'>$fila[Nombre]</td>";
              echo "<td class='row_show' title='Haz click para ver el horario de $fila[Nombre]'>$fila[Iniciales]</td>";
              echo "<td class='row_show' title='Haz click para ver el horario de $fila[Nombre]'>$activo</td>";
              echo "<td class='row_show' title='Haz click para ver el horario de $fila[Nombre]'>$sustituido</td>";

              echo "<td><a title='Editar a $fila[Nombre]' href='index.php?ACTION=profesores&OPT=edit&ID=$fila[ID]'><i style='font-size: 25px; color: black;' class='fa fa-pencil-square-o edit_icon'></i></a></td>";
              echo "<td><a title='Mostrar asistencias de $fila[Nombre]' href='index.php?ACTION=asistencias&ID=$fila[ID]'><i style='font-size: 25px; color: black;' class='fa fa-list-ul list_icon'></i></a></td>";
              if($fila['Activo'] == 1)
              {
                echo "<td>
                  <a href='index.php?ACTION=profesores&OPT=des-act&ID=$fila[ID]'
                      title='Desctivar a $fila[Nombre]'
                      onclick=\"return confirm('¿Seguro que desea realizar este cambio? Utilice solo esta opción si el profesor deja el centro por motivos de jubilación, fin de una sustitución o similares.')\">
                      <i style='font-size: 25px; color: red;' class='fa fa-ban remove_icon'></i>
                  </a>
                </td>";
              }
              else
              {
                echo "<td>
                  <a href='index.php?ACTION=profesores&OPT=des-act&ID=$fila[ID]'
                      title='Activar a $fila[Nombre]'
                      onclick=\"return confirm('¡Cuidado! Si realiza este cambio ahora, se considerará que el profesor vuelve a trabajar en el centro.')\">
                      <i style='font-size: 25px; color: green;' class='fa fa-check add_icon'></i>
                  </a>
                </td>";
              }
              echo "<td>
                  <a class='reset_icon'
                      title='Restablecer contraseña de $fila[Nombre]'
                      href='index.php?ACTION=profesores&OPT=reset-pass&ID=$fila[ID]'
                      onclick=\"return confirm('Va a restablecer la contraseña de $fila[Nombre]  ¿Desea continuar?.')\">
                      <i style='font-size: 25px; color: black;' class='fa fa-refresh reset_icon'></i>
                  </a>
              </td>";
            echo '</tr>';
        }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo '</div>';
    include_once($dirs['public'] . 'js/profesores.js');
   }
   else
   {
    $MSG = "No existen profesores, debe importarlos o registrarlos.<br><br>";
    $MSG .= "<a href='$_SERVER[PHP_SELF]?ACTION=profesores&OPT=import-form' class='btn btn-success'>Importar</a> ";
    $MSG .= "<a href='index.php?ACTION=profesores&OPT=add-profesor' class='btn btn-info'>Registrar</a>";
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
