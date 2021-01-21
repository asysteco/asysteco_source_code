<?php
if($_SESSION['Perfil'] === 'Admin')
{ 
 if ($response = $class->query("SELECT $class->profesores.ID, $class->profesores.Nombre, $class->profesores.Iniciales, $class->profesores.Activo, $class->profesores.Sustituido FROM $class->profesores INNER JOIN $class->perfiles ON $class->profesores.TIPO=$class->perfiles.ID WHERE $class->profesores.TIPO<>1 ORDER BY Nombre ASC"))
 {
   if ($response->num_rows > 0)
   {
    echo '<div class="container">';
    echo "<h1>Profesores</h1>";
    echo "<br><h4 style='display: inline-block; margin-right: 15px;'>Buscar profesor: </h4>
      <input style='width: 25%; display: inline-block;' id='busca_prof' class='form-control' type='text' placeholder='Buscar Profesor...' autocomplete='off'>
      <label for='busca_prof'> <span style='font-size: 20px;' class='fa fa-search'></span></label><br>";
    echo "<div class='table-responsive'>";
    echo "</br><table id='tabla_profesores' class='table table-hover table-striped responsiveTable'>";
    echo "<thead class='thead-dark'>";
        echo "<tr>";
            echo "<th>Nombre</th>";
            echo "<th>Iniciales</th>";
            echo "<th>Activo</th>";
            echo "<th>Sustituido</th>";
            echo "<th>Editar</th>";
            echo "<th>Asistencias</th>";
            echo "<th>Desactivar / Activar</th>";
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
              echo "<td data-th='Nombre' data-toggle='tooltip' class='act' action='horario' title='Haz click para ver el horario de $fila[Nombre]'>$fila[Nombre]</td>";
              echo "<td data-th='Iniciales' data-toggle='tooltip' class='act' action='horario' title='Haz click para ver el horario de $fila[Nombre]'>$fila[Iniciales]</td>";
              echo "<td data-th='Activo' data-toggle='tooltip' class='act' action='horario' title='Haz click para ver el horario de $fila[Nombre]'>$activo</td>";
              echo "<td data-th='Sustituido' data-toggle='tooltip' class='act' action='horario' title='Haz click para ver el horario de $fila[Nombre]'>$sustituido</td>";

              echo "<td data-th='Editar'><a data-toggle='tooltip' title='Editar a $fila[Nombre]' href='index.php?ACTION=profesores&OPT=edit&ID=$fila[ID]'><i style='font-size: 25px; color: black;' class='fa fa-pencil-square-o edit_icon'></i></a></td>";
              echo "<td data-th='Asistencias'><a data-toggle='tooltip' title='Mostrar asistencias de $fila[Nombre]' href='index.php?ACTION=asistencias&ID=$fila[ID]'><i style='font-size: 25px; color: black;' class='fa fa-list-ul list_icon'></i></a></td>";
              if($fila['Activo'] == 1)
              {
                echo "<td data-th='Desactivar / Activar'>
                  <a enlace='index.php?ACTION=profesores&OPT=des-act&ID=$fila[ID]'
                      data-toggle='tooltip'
                      title='Desactivar a $fila[Nombre]'
                      class='act'
                      action='modal-desactivar'>
                      <i style='font-size: 25px; color: red;' class='fa fa-ban remove_icon'></i>
                  </a>
                </td>";
              }
              else
              {
                echo "<td data-th='Desactivar / Activar'>
                  <a enlace='index.php?ACTION=profesores&OPT=des-act&ID=$fila[ID]'
                      data-toggle='tooltip'
                      title='Activar a $fila[Nombre]'
                      class='act'
                      action='modal-activar'>
                      <i style='font-size: 25px; color: green;' class='fa fa-check add_icon'></i>
                  </a>
                </td>";
              }
              echo "<td data-th='Reset. Contraseña'>
                  <a class='reset_icon'
                      data-toggle='tooltip'
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

?>

<script src="js/filtro_prof.js"></script>
<script src="js/profesores.js"></script>

<div id="modal-profesores" class="modal fade" tabindex="-1" role="dialog">
  <div id='modal-size' class="modal-dialog" role="document">
    <div class="modal-content"> 
    <div id='modal-cabecera' class="modal-header">
    </div>
      <div class="modal-body">
        <div id='modal-contenido'>
        </div>
      </div>
      <div id='modal-pie' class="modal-footer">
      </div>
    </div>
  </div>
</div>
