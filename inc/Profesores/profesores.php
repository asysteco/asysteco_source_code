<?php
if($_SESSION['Perfil'] === 'Admin')
{
  $sql = "SELECT p.ID, p.Nombre, p.Iniciales, p.Activo, p.Sustituido
          FROM Profesores p
            INNER JOIN Perfiles pf ON p.TIPO=pf.ID
          WHERE p.TIPO<>1
          ORDER BY p.Nombre ASC, p.Iniciales ASC";
 if ($response = $class->query($sql))
 {
   if ($response->num_rows > 0)
   {
    echo '<div class="container">';
    echo "<h1>Profesores/Personal</h1>";
    echo "<br><h4 style='display: inline-block; margin-right: 15px;'>Buscar profesor/personal: </h4>
      <input style='width: 25%; display: inline-block;' id='busca_prof' class='form-control' type='text' placeholder='Buscar Profesor/Personal...' autocomplete='off'>
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
          $activo = $fila['Activo'] == 1? 'Si': 'No';
          $sustituido = $fila['Sustituido'] == 0? 'No': 'Si';
            
          echo "<tr id='profesor_$fila[ID]' nombre='$fila[Nombre]' class='row_prof'>";
            echo "<td data-th='Nombre' class='act' action='horario'>$fila[Nombre]</td>";
            echo "<td data-th='Iniciales' class='act' action='horario'>$fila[Iniciales]</td>";
            echo "<td data-th='Activo' class='act' action='horario'>$activo</td>";
            echo "<td data-th='Sustituido' class='act' action='horario'>$sustituido</td>";

            echo "<td data-th='Editar'><a title='Editar a $fila[Nombre]' href='index.php?ACTION=profesores&OPT=edit&ID=$fila[ID]'><i style='font-size: 25px; color: black;' class='fa fa-pencil-square-o edit_icon'></i></a></td>";
            echo "<td data-th='Asistencias'><a profesor='$fila[ID]' class='act' action='modal-asistencias'><i style='font-size: 25px; color: black;' class='fa fa-list-ul list_icon'></i></a></td>";
            if($fila['Activo'] == 1)
            {
              echo "<td data-th='Desactivar / Activar'>
                <a profesor='$fila[ID]'
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
                <a profesor='$fila[ID]'
                    title='Activar a $fila[Nombre]'
                    class='act'
                    action='modal-activar'>
                    <i style='font-size: 25px; color: green;' class='fa fa-check add_icon'></i>
                </a>
              </td>";
            }
            echo "<td data-th='Reset. Contraseña'>
                <a profesor='$fila[ID]'
                    title='Restablecer contraseña de $fila[Nombre]'
                    class='act'
                    action='modal-reset'
                    nombre='$fila[Nombre]'>
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
<script src="js/filtro_asistencias.js"></script>
<script src="js/profesores.js"></script>
<script src="js/update_marcajes.js"></script>

<div id="modal-profesores" class="modal fade" tabindex="-1" role="dialog">
  <div id='modal-size' class="modal-dialog" role="document">
    <div class="modal-content"> 
      <div id='modal-cabecera' class="modal-header"></div>
      <div class="modal-body">
        <div id='modal-contenido'></div>
      </div>
      <div id='modal-pie' class="modal-footer"></div>
    </div>
  </div>
</div>
