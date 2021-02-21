<div class="container" id="botonera">
    <div class="row">
        <div class="col-12">
            <?php
            $sql = "SELECT Notificaciones.*, Profesores.Nombre, Profesores.Iniciales
                        FROM Notificaciones
                            INNER JOIN Profesores ON Notificaciones.ID_PROFESOR=Profesores.ID
                        ORDER BY Fecha DESC
                        LIMIT 100";
            $result = $class->query($sql);
            if (!empty($result)) {
                echo "<h1>Registros de Notificaciones</h1>";
                echo "<div class='table-responsive'>";
                echo "<table id='userTable' class='table table-striped'>
                        <thead>
                            <tr>
                                <th>INICIALES</th>
                                <th>NOMBRE</th>
                                <th>MODIFICACIÓN</th>
                                <th>FECHA</th>
                            </tr>
                        </thead>
                        <tbody>
                    ";
                while ($datos = $result->fetch_assoc()) {
                    $ultimos = "";
                    if ($datos['Visto'] == 0) {
                        $ultimos = "style='background-color: #fff2b3; font-weight: bold;'";
                    }
                    $sep = preg_split('/[ -]/', $datos['Fecha']);
                    $dia = $sep[2];
                    $m = $sep[1];
                    $Y = $sep[0];
                    $h = $sep[3];
                    echo "
                            <tr $ultimos>
                                <td style='vertical-align: middle;'>$datos[Iniciales]</td>
                                <td style='vertical-align: middle;'>$datos[Nombre]</td>
                                <td style='vertical-align: middle;'>$datos[Modificacion]</td>
                                <td style='vertical-align: middle;'>$dia/$m/$Y $h</td>
                            </tr>
                        ";
                }

                if (!$class->query("UPDATE Notificaciones SET Visto = 1 WHERE Visto = 0")) {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
            }
            echo "
                    </tbody>
                </table>
                </div>
                ";
            ?>
        </div>
    </div>
</div>