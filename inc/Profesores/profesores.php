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
              echo "<td data-th='Nombre' class='row-show' title='Haz click para ver el horario de $fila[Nombre]'>$fila[Nombre]</td>";
              echo "<td data-th='Iniciales' class='row-show' title='Haz click para ver el horario de $fila[Nombre]'>$fila[Iniciales]</td>";
              echo "<td data-th='Activo' class='row-show' title='Haz click para ver el horario de $fila[Nombre]'>$activo</td>";
              echo "<td data-th='Sustituido' class='row-show' title='Haz click para ver el horario de $fila[Nombre]'>$sustituido</td>";

              echo "<td data-th='Editar'><a title='Editar a $fila[Nombre]' href='index.php?ACTION=profesores&OPT=edit&ID=$fila[ID]'><i style='font-size: 25px; color: black;' class='fa fa-pencil-square-o edit_icon'></i></a></td>";
              echo "<td data-th='Asistencias'><a title='Mostrar asistencias de $fila[Nombre]' href='index.php?ACTION=asistencias&ID=$fila[ID]'><i style='font-size: 25px; color: black;' class='fa fa-list-ul list_icon'></i></a></td>";
              if($fila['Activo'] == 1)
              {
                echo "<td data-th='Desactivar / Activar'>
                  <a enlace='index.php?ACTION=profesores&OPT=des-act&ID=$fila[ID]'
                      title='Desctivar a $fila[Nombre]'
                      class='desactivar-profesor'>
                      <i style='font-size: 25px; color: red;' class='fa fa-ban remove_icon'></i>
                  </a>
                </td>";
              }
              else
              {
                echo "<td data-th='Desactivar / Activar'>
                  <a enlace='index.php?ACTION=profesores&OPT=des-act&ID=$fila[ID]'
                      title='Activar a $fila[Nombre]'
                      class='activar-profesor'>
                      <i style='font-size: 25px; color: green;' class='fa fa-check add_icon'></i>
                  </a>
                </td>";
              }
              echo "<td data-th='Reset. Contraseña'>
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

<div id="modal-horario" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div id='horario'>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div id="modal-desactivar" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Desactivar Profesor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <i>¿Seguro que desea realizar este cambio? Utilice solo esta opción si el profesor deja el centro por motivos de jubilación, fin de una sustitución o similares.</i>
        <br><br>
        <input id="fecha-desactivar" class="form-control" name="fecha" type="text" placeholder="Seleccione una fecha..." autocomplete="off">
      </div>
      <div class="modal-buttons-footer">
        <button type="button" class="btn btn-success act float-right" action="desactivar">Confirmar</button>
        <button type="button" class="btn btn-danger float-left" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<div id="modal-activar" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Activar Profesor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <i>¡Cuidado! Si realiza este cambio ahora, se considerará que el profesor vuelve a trabajar en el centro.</i>
      </div>
      <div class="modal-buttons-footer">
        <button type="button" class="btn btn-success act float-right" action="activar">Confirmar</button>
        <button type="button" class="btn btn-danger float-left" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<script src="js/desactivar-profesor.js"></script>
