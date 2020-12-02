<?php

if($response = $class->query("SELECT ID, Iniciales, Nombre, Tutor, Activo, Sustituido FROM $class->profesores WHERE ID='$_GET[ID]'"))
{
    
    $datos = $response->fetch_assoc();
    echo '<div class="container" style="margin-top:50px">';
        echo '<div class="wrapper fadeInDown">';
            echo '<div id="formContent">';
                echo '<h1>Edición de Profesor</h1>';
                echo "<form  method='POST' action='$_SERVER[REQUEST_URI]'>";
                    echo "<input type='text' class='hidden' name='ID' value='$datos[ID]'>";
                    echo "<label>Iniciales</label></br>";
                    echo "<input type='text' name='Iniciales' value='$datos[Iniciales]'></br>";
                    echo "<label>Nombre</label></br>";
                    echo "<input type='text' name='Nombre' value='$datos[Nombre]'></br>";
                    echo "<label>Tutor</label></br>";
                    echo "<input id='grupo-tutor' type='text' name='Tutor' value='$datos[Tutor]'>";
                    if($response2 = $class->query("SELECT DISTINCT $class->horarios.Grupo FROM $class->horarios WHERE $class->horarios.Grupo <> '' AND $class->horarios.Grupo <> 'Selec.' ORDER BY $class->horarios.Grupo"))
                    {
                        echo "<select id='grupo-tutor-select' class='entrada'>";
                            echo "<option value='No'>No</option>";
                            while($fila = $response2->fetch_assoc())
                            {
                                echo "<option value='$fila[Grupo]'>$fila[Grupo]</option>";
                            }
                        echo "</select>";
                    }
                    else
                    {
                        echo "<span style='color:red;'>$class->ERR_ASYSTECO</span>";
                    }
                    echo "</br><label>Activo</label></br>";
                    echo "<input type='text' class='hidden' id='Activo' name='Activo' value='$datos[Activo]'>";
                    if($response == true)
                    {
                        if($datos['Activo'] == 1)
                        {
                            $datos['Activo'] = 'Si';
                        }
                        else
                        {
                            $datos['Activo'] = 'No';
                        }

                    }
                    echo "<h4>$datos[Activo]</h4>";
                    echo "<label>Sustituido</label></br>";
                    echo "<input type='text' class='hidden' name='Sustituido' value='$datos[Sustituido]'>";
                    if($response == true)
                    {
                        if($datos['Sustituido'] == 1)
                        {
                            $datos['Sustituido'] = 'Si';
                        }
                        else
                        {
                            $datos['Sustituido'] = 'No';
                        }

                    }
                    echo "<h4>$datos[Sustituido]</h4>";
                    if($resp = $class->query("SELECT Nombre, ID FROM $class->profesores WHERE ID=$_GET[ID] AND Sustituido=0"))
                    {
                        if($resp->num_rows > 0)
                        {
                            echo "<a href='index.php?ACTION=profesores' style='margin-left: 30px' class='btn btn-danger pull-left'>Cancelar</a>";
                            echo "<a href='index.php?ACTION=profesores&OPT=sustituir&ID=$datos[ID]' style='margin-right: 30px' class='btn btn-info pull-right'>Sustituir</a><br><br>";
                        } 
                        else
                        {
                            echo "<a href='index.php?ACTION=profesores&OPT=remove-sustituto&ID=$datos[ID]' class='btn btn-info'>Retirar Sustituto</a><br><br>";
                        }
                    }
                    echo "<button class='btn btn-info' name='ACTION' value='editar_profesor'>Actualizar Profesor</button></br></br>";
                echo "</form>";
            echo '</div>';
        echo '</div>';
    echo '</div>';
    include_once('js/editar_profesor.js');
}
 
else
{
    $ERR_MSG = $class->ERR_ASYSTECO;
}

?>