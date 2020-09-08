<?php
if(isset($_GET['act']))
{
    if($_GET['act'] == 'add' && isset($_GET['ID']) && isset($_GET['Dia']) && isset($_GET['Hora']) && isset($_GET['Tipo']) && isset($_GET['Fecha']))
    {
        $Edificio = 0;
        $Tipo = preg_split('//', $_GET['Tipo'], -1, PREG_SPLIT_NO_EMPTY);
        $Tipo = $Tipo[0];
        $Horatipo= $_GET['Hora'] . $Tipo;
        echo "INSERT INTO T_horarios (ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida, Fecha_incorpora) 
        VALUES ('$_GET[ID]', '$_GET[Dia]', '$Horatipo', '$_GET[Hora]', '$_GET[Tipo]', '$Edificio', 'Selec.', 'Selec.', '00:00:00', '00:00:00', '$_GET[Fecha]')";
        if($class->query("INSERT
            INTO T_horarios (ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida, Fecha_incorpora) 
            VALUES ('$_GET[ID]', '$_GET[Dia]', '$Horatipo', '$_GET[Hora]', '$_GET[Tipo]', '$Edificio', 'Selec.', 'Selec.', '00:00:00', '00:00:00', '$_GET[Fecha]')"))
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
                      Añadido correctamente.
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
                    <p style="color: red;">
                      ' . $class->ERR_ASYSTECO . '
                    </p>
                  </div>
                </div>
              </div>
            </div>
            ';
        }
      }
    elseif($_GET['act'] == 'del' && isset($_GET['ID']))
    {
        if($res = $class->query("DELETE FROM T_horarios WHERE ID='$_GET[ID]'"))
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
                      Borrado correctamente.
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
                    <p style="color: red;">
                      ' . $class->ERR_ASYSTECO . '
                    </p>
                  </div>
                </div>
              </div>
            </div>
            ';
        }
    }
    elseif($_GET['act'] == 'del_hora' && isset($_GET['ID_PROFESOR']) && isset($_GET['Dia']) && isset($_GET['Hora']) && isset($_GET['Fecha']))
    {
      if($res = $class->query("DELETE FROM T_horarios WHERE ID_PROFESOR='$_GET[ID_PROFESOR]' AND Fecha_incorpora='$_GET[Fecha]' AND Hora='$_GET[Hora]' AND Dia='$_GET[Dia]'"))
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
                    Borrado correctamente.
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
                  <p style="color: red;">
                    ' . $class->ERR_ASYSTECO . '
                  </p>
                </div>
              </div>
            </div>
          </div>
          ';
      }
    }
    elseif($_GET['act'] == 'add_more' && isset($_GET['ID']) && isset($_GET['Dia']) && isset($_GET['Hora']) && isset($_GET['Fecha']))
    {
      $Edificio = 0;
      if (isset($_GET['Aula'])) {
          $sed = preg_split('//', $_GET['Aula'], -1, PREG_SPLIT_NO_EMPTY);
          $Edificio = mysqli_real_escape_string($class->conex, utf8_encode($sed[2]));
          preg_match('/^[0-9]$/', $Edificio) ? $Edificio = $Edificio : $Edificio=0;
      }
      if($class->query("INSERT INTO T_horarios (ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida, Fecha_incorpora) 
          SELECT ID_PROFESOR, Dia, HORA_TIPO, Hora, Tipo, Edificio, Aula, Grupo, Hora_entrada, Hora_salida, Fecha_incorpora
          FROM T_horarios
          WHERE ID_PROFESOR='$_GET[ID]' AND Fecha_incorpora='$_GET[Fecha]' AND Dia='$_GET[Dia]' AND Hora='$_GET[Hora]'"))
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
                    Añadido correctamente.
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
                  <p style="color: red;">
                    ' . $class->ERR_ASYSTECO . '
                  </p>
                </div>
              </div>
            </div>
          </div>
          ';
      }
    }
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
            <p style="color: red;">
            No puede realizar la acción.
            </p>
          </div>
        </div>
      </div>
    </div>
    ';
}