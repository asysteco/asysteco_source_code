<?php  
        echo "<div id='listado_mensajes' class='col-12'>";
        echo "<h2>Mensajes</h2>";
        echo '<div id="tabs">';
            echo '<ul>';
                echo '<li><a href="#tabs-1">Recibidos <span id="recibidos_badge" class="badge">0</span></a></li>';
                echo '<li><a href="#tabs-2">Enviados <span id="enviados_badge" class="badge">0</span></a></li>';
            echo '</ul>';
            echo '<div id="tabs-1">';

    $recibidos = "SELECT *
    FROM Mensajes
    WHERE (ID_DESTINATARIO='$_SESSION[ID]' AND Borrado_Destinatario=0)
    ORDER BY ID DESC";
    if($response_rec = $class->query($recibidos))
    {
        if ($response_rec->num_rows > 0)
        {
            echo "<script>";
                echo "$('#recibidos_badge').html('$response_rec->num_rows')";
            echo "</script>";
            echo"
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>EMISOR</th>
                            <th>RECEPTOR</th>
                            <th>ASUNTO</th>
                            <th>MENSAJE</th>
                            <th>Fecha</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
            ";
            while($datos_rec = $response_rec->fetch_assoc())
            {
                if($nombre1 = $class->query("SELECT Nombre FROM Profesores WHERE ID='$datos_rec[ID_PROFESOR]'"))
                {
                    $emisor = $nombre1->fetch_assoc();
                    $emisor = $emisor['Nombre'];
                }
                else
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }

                if($nombre2 = $class->query("SELECT Nombre FROM Profesores WHERE ID='$datos_rec[ID_DESTINATARIO]'"))
                {
                    $receptor = $nombre2->fetch_assoc();
                    $receptor = $receptor['Nombre'];
                }
                else
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }

                if($datos_rec['ID_DESTINATARIO'] == $_SESSION['ID'] && $datos_rec['Leido'] == 0)
                {
                    $leido = "UPDATE Mensajes
                            SET Leido=1
                            WHERE ID='$datos_rec[ID]'";
                    if(! $class->query($leido))
                    {
                        $ERR_MSG = $class->ERR_ASYSTECO;
                    }
                }

                $sep = preg_split('/[ -]/', $datos_rec['Fecha']);
                $dia = $sep[2];
                $m = $sep[1];
                $Y = $sep[0];
                $h = $sep[3];
                echo "  
                    <tr id='$datos_rec[ID]'>
                        <td>$emisor</td>
                        <td>$receptor</td>
                        <td>$datos_rec[Asunto]</td>
                        <td>$datos_rec[Mensaje]</td>
                        <td>$dia/$m/$Y $h</td>
                        <td><a onclick=\"return confirm('¿Estas seguro de borrar el mensaje?')\" href='index.php?ACTION=mensajes&OPT=remove&ID=$datos_rec[ID]'><span class='glyphicon glyphicon-trash'></span></a></td>
                    </tr> 
                ";
            }
        }
        else
        {
            echo "<tr>";
                echo "<td colspan='6' style='vertical-align: middle; text-align: center;'>No Tienes Mensajes.</td>";    
            echo "</tr>";
        }
            echo "
                        </tbody>
                    </table>
            ";
        echo '</div>';
    }
    else
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }

    echo '<div id="tabs-2">';

    $enviados = "SELECT *
    FROM Mensajes
    WHERE (ID_PROFESOR='$_SESSION[ID]' AND Borrado_Profesor=0)
    ORDER BY ID DESC";

    if($response_env = $class->query($enviados))
    {
        if ($response_env->num_rows > 0)
        {
            echo "<script>";
                echo "$('#enviados_badge').html('$response_env->num_rows')";
            echo "</script>";
            echo"
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>EMISOR</th>
                            <th>RECEPTOR</th>
                            <th>ASUNTO</th>
                            <th>MENSAJE</th>
                            <th>Fecha</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
            ";    
            while($datos_env = $response_env->fetch_assoc())
            {
                if($nombre1 = $class->query("SELECT Nombre FROM Profesores WHERE ID='$datos_env[ID_PROFESOR]'"))
                {
                    $emisor = $nombre1->fetch_assoc();
                    $emisor = $emisor['Nombre'];
                }
                else
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }

                if($nombre2 = $class->query("SELECT Nombre FROM Profesores WHERE ID='$datos_env[ID_DESTINATARIO]'"))
                {
                    $receptor = $nombre2->fetch_assoc();
                    $receptor = $receptor['Nombre'];
                }
                else
                {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }

                if($datos_env['ID_DESTINATARIO'] == $_SESSION['ID'] && $datos_env['Leido'] == 0)
                {
                    $leido = "UPDATE Mensajes
                            SET Leido=1
                            WHERE ID='$datos_env[ID]'";
                    if(! $class->query($leido))
                    {
                        $ERR_MSG = $class->ERR_ASYSTECO;
                    }
                }

                $sep = preg_split('/[ -]/', $datos_env['Fecha']);
                $dia = $sep[2];
                $m = $sep[1];
                $Y = $sep[0];
                $h = $sep[3];
                echo "  
                    <tr id='$datos_env[ID]'>
                        <td>$emisor</td>
                        <td>$receptor</td>
                        <td>$datos_env[Asunto]</td>
                        <td>$datos_env[Mensaje]</td>
                        <td>$dia/$m/$Y $h</td>
                        <td><a onclick=\"return confirm('¿Estas seguro de borrar el mensaje?')\" href='index.php?ACTION=mensajes&OPT=remove&ID=$datos_env[ID]'><span class='glyphicon glyphicon-trash'></span></a></td>
                    </tr> 
                ";
            }
        }
        else
        {
            echo "<tr>";
                echo "<td colspan='6' style='vertical-align: middle; text-align: center;'>No Tienes Mensajes.</td>";    
            echo "</tr>";
        }
            echo "
                        </tbody>
                    </table>
            ";
        echo '</div>';
    }
    else
    {
        $ERR_MSG = $class->ERR_ASYSTECO;
    }
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";