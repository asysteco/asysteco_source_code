<?php

$profesor = $_GET['profesor'];
$dia = $_GET['Dia'];
$hora = $_GET['Hora'];
$subopt = $_GET['SUBOPT'];
$edificio = $_GET['e'];

$Tipo = preg_split('//', $_GET['Tipo'], -1, PREG_SPLIT_NO_EMPTY);
$Tipo = $Tipo[0];
$Horatipo= $_GET['Hora'] . $Tipo;

if($subopt == 'add')
{
    $sql = "INSERT INTO $class->horarios (ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida) VALUES ('$profesor', '$dia', '$Horatipo', '$hora', '$_GET[Tipo]', '$edificio', 'GU". $edificio ."00', 'Guardia', '00:00:00', '00:00:00')";
    if($class->query($sql))
    {
        $class->updateHoras($profesor);
        $class->marcajes($profesor, $dia, $hora, $subopt);
        $MSG = 'Guardia añadida.';
    }
    else
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}
elseif($subopt == 'remove')
{
    $sql = "DELETE FROM $class->horarios WHERE ID_PROFESOR='$profesor' AND Dia='$dia' AND Hora='$hora'";
    if($class->query($sql))
    {
        $class->updateHoras($profesor);
        $class->marcajes($profesor, $dia, $hora, $subopt);
        $MSG = 'Guardia eliminada.';
    }
    else
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}
elseif($subopt == 'addt')
{
    $fecha = date('Y-m-d');
    $sql = "INSERT INTO T_horarios (ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida, Fecha_incorpora)
    VALUES ('$profesor', '$dia', '$Horatipo', '$hora', '$_GET[Tipo]', '$edificio', 'GU". $edificio ."00', 'Guardia', '00:00:00', '00:00:00', '$fecha')";
    if($class->query($sql))
    {
      $MSG = 'Guardia añadida.';
    }
    else
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}
elseif($subopt == 'removet')
{
    $sql = "DELETE FROM T_horarios WHERE ID_PROFESOR='$profesor' AND Dia='$dia' AND Hora='$hora'";
    if($class->query($sql))
    {
      $MSG = 'Guardia eliminada.';
    }
    else
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
}
else
{
    echo $ERR_MSG = "No SUBOPT has given.";
}

if(isset($ERR_MSG) && $ERR_MSG != '')
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
            <p style="color: red;">
              ' . $ERR_MSG . '
            </p>
          </div>
        </div>
      </div>
    </div>
    ';
}
else
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
            ' . $MSG . '
            </p>
          </div>
        </div>
      </div>
    </div>
    ';
}