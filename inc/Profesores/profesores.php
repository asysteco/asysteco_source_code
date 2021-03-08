<?php
if ($_SESSION['Perfil'] === 'Admin') {
  $sql = "SELECT p.ID, p.Nombre, p.Iniciales, p.Activo, p.Sustituido, p.TIPO
          FROM Profesores p
            INNER JOIN Perfiles pf ON p.TIPO=pf.ID
          WHERE p.TIPO<>1
          ORDER BY p.Nombre ASC, p.Iniciales ASC";
  if ($response = $class->query($sql)) {
    if ($response->num_rows > 0) {
?>
      <div class="container">
        <h1>Profesores/Personal</h1>
        <br />
        <h4 style='display: inline-block; margin-right: 15px;'>Buscar profesor/personal: </h4>
        <input style='width: 25%; display: inline-block; min-width: 250px;' id='busca_prof' class='form-control' type='text' placeholder='Buscar Profesor/Personal...' autocomplete='off'>
        <label for='busca_prof'> <span style='font-size: 20px;' class='fa fa-search'></span></label>
        <br />
        <div class='table-responsive'>
          </br>
          <table id='tabla_profesores' class='table table-hover table-striped responsiveTable'>
            <thead class='thead-dark'>
              <tr>
                <th>Nombre</th>
                <th>Iniciales</th>
                <th>Activo</th>
                <th>Sustituido</th>
                <th>Horario</th>
                <th>Editar</th>
                <th>Asistencias</th>
                <th>Desactivar / Activar</th>
                <th>Reset. Contraseña</th>
              </tr>
            </thead>
            <tbody>

              <?php
              while ($fila = $response->fetch_assoc()) {
                $activo = $fila['Activo'] == 1 ? 'Si' : 'No';
                $sustituido = $fila['Sustituido'] == 0 ? 'No' : 'Si';
                $typeIcon = $fila['TIPO'] == 2 ?
                  '<i class="fa fa-graduation-cap" title="Profesorado"></i>' :
                  '<i class="fa fa-user personal-icon-azul" title="Personal No Docente"></i>';

                $profesorId = $fila['ID'];
                $nombreProfesor = $fila['Nombre'];
                $iniciales = $fila['Iniciales'];
              ?>

                <tr nombre='<?= $nombreProfesor ?>' class='row_prof'>
                  <td data-th='Nombre'><?= $nombreProfesor ?></td>
                  <td data-th='Iniciales'><?= $typeIcon ?> <?= $iniciales ?></td>
                  <td data-th='Activo'><?= $activo ?></td>
                  <td data-th='Sustituido'><?= $sustituido ?></td>

                  <td data-th='Horario'>
                    <a profesor='<?= $profesorId ?>' title='Editar a <?= $nombreProfesor ?>' class='act' action='horario'>
                      <i style='font-size: 25px; color: black;' class='fa fa-calendar calendar_icon'></i>
                    </a>
                  </td>

                  <td data-th='Editar'>
                    <a profesor='<?= $profesorId ?>' title='Editar a <?= $nombreProfesor ?>' class='act' action='modal-editar'>
                      <i style='font-size: 25px; color: black;' class='fa fa-pencil-square-o edit_icon'></i>
                    </a>
                  </td>

                  <td data-th='Asistencias'>
                    <a profesor='<?= $profesorId ?>' nombre='<?= $nombreProfesor ?>' class='act' action='modal-asistencias'>
                      <i style='font-size: 25px; color: black;' class='fa fa-list-ul list_icon'></i>
                    </a>
                  </td>

                  <?php if ($fila['Activo'] == 1) { ?>
                    <td data-th='Desactivar / Activar'>
                      <a profesor='<?= $profesorId ?>' title='Desactivar a <?= $nombreProfesor ?>' class='act' action='modal-desactivar'>
                        <i style='font-size: 25px; color: red;' class='fa fa-ban remove_icon'></i>
                      </a>
                    </td>

                  <?php } else { ?>

                    <td data-th='Desactivar / Activar'>
                      <a profesor='<?= $profesorId ?>' title='Activar a <?= $nombreProfesor ?>' class='act' action='modal-activar'>
                        <i style='font-size: 25px; color: green;' class='fa fa-check add_icon'></i>
                      </a>
                    </td>

                  <?php } ?>

                  <td data-th='Reset. Contraseña'>
                    <a profesor='<?= $profesorId ?>' title='Restablecer contraseña de <?= $nombreProfesor ?>' class='act' action='modal-reset' nombre='<?= $nombreProfesor ?>'>
                      <i style='font-size: 25px; color: black;' class='fa fa-refresh reset_icon'></i>
                    </a>
                  </td>
                </tr>

              <?php } ?>

            </tbody>
          </table>
        </div>
      </div>

<?php
    } else {
      $MSG = "No existen profesores, debe importarlos o registrarlos.<br /><br />";
      $MSG .= "<a href='$_SERVER[PHP_SELF]?ACTION=profesores&OPT=import-form' class='btn btn-success'>Importar</a> ";
      $MSG .= "<a href='index.php?ACTION=profesores&OPT=add-profesor' class='btn btn-info'>Registrar</a>";
    }
  } else {
    $ERR_MSG = $class->ERR_ASYSTECO;
  }
} else {
  $ERR_MSG = "No tiene permisos de administrador.";
}
?>

<script src="js/filtro_prof.js"></script>
<script src="js/filtro_asistencias.js"></script>
<script src="js/profesores.js"></script>
<script src="js/update_marcajes.js"></script>
<script src="js/editar_profesor.js"></script>

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