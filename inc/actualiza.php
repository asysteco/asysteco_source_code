<?php
if($_GET['SUBOPT'] == 'horario')
{
  $tabla = $class->horarios;
}
elseif($_GET['SUBOPT'] == 't-horario')
{
  $tabla = 'T_horarios';
}
$id = mysqli_real_escape_string($class->conex, $_GET["id"]);
$columna = mysqli_real_escape_string($class->conex, $_GET["columna"]);
$texto = mysqli_real_escape_string($class->conex, $_GET["texto"]);
if($columna === 'Aula' || $columna === 'Grupo')
{
  $sql="UPDATE $tabla SET $columna='$texto' WHERE id='$id'";
  if(! $class->query($sql))
  {
    $ERR_MSG = $class->ERR_ASYSTECO;
  }
}
else
{
    $sql="";
}
if(isset($sql) && $sql != '')
{
    if($class->query($sql))
    {
        echo "
        <script>
          $('#ERR_MSG_MODAL').modal('show')
        </script>
        ";
        echo '
        <!-- Modal -->
        <div class="modal fade" id="ERR_MSG_MODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p style="color: green;">
                  Actualizado correctamente.
                </p>
              </div>
            </div>
          </div>
        </div>
        ';
    }
    else
    {
        echo $class->ERR_ASYSTECO;
    }
}
?>