<div class="container" style="margin-top:75px">
    <div class='row'>
        <div class='col-xs-12'>
            <h2>Gestión de Aulas</h2>
            <input type="text">
            <a class="btn btn-success"></a>
            <table class="table table-striped">
                <thead>
                    <tr style="text-align: center;">
                        <th style="text-align: center;">Nombre</th>
                        <th style="text-align: center;">Editar</th>
                        <th style="text-align: center;">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
            <?php        
                if ($response = $class->query("SELECT ID, Nombre FROM Aulas")) {
                    if ($response->num_rows > 0) {
                        while($row = $response->fetch_assoc()){
                            echo "<tr style='text-align: center;'>";
                                echo "<td>$row[Nombre]</td>";
                                echo "<td><span data='$row[ID]' class='glyphicon glyphicon-pencil edit'></span></td>";
                                echo "<td><span data='$row[ID]' class='glyphicon glyphicon-trash remove'></span></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr style='text-align: center;'>";
                            echo "<td colspan='100%'><h3>No existen aulas.</h3></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr style='text-align: center;'>";
                        echo "<td colspan='100%'><h3>" . $class->ERR_ASYSTECO . "</h3></td>";
                    echo "</tr>";
                }
            ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
