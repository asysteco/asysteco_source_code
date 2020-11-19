<div class="container" style="margin-top:75px">
    <div class='row'>
        <div class='col-xs-12'>
            <h2>Gestión de Aulas</h2>
            <input id="add-aula" type="text" class="form-control" placeholder="Escribe el nombre del aula nueva..."><br>
            <a id="add-btn-aula" action="add" class="btn btn-success">Añadir Aula</a><br>
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
                if ($response = $class->query("SELECT ID, Nombre FROM Aulas ORDER BY Nombre")) {
                    if ($response->num_rows > 0) {
                        while($row = $response->fetch_assoc()){
                            echo "<tr id='fila_$row[ID]' style='text-align: center;'>";
                                echo "<td style='vertical-align: middle;'>";
                                    echo "<input id='input_$row[ID]' type='text' class='form-control hide-it' style='width: 30%; display: inline-block;'>";
                                    echo "<span id='txt_$row[ID]' class='show-it'>$row[Nombre]</span> ";
                                    echo "<a id='btn_$row[ID]' data='$row[ID]' class='btn btn-success hide-it update'>Aplicar</a>";
                                echo "</td>";
                                echo "<td style='vertical-align: middle;'><span fields='edit_$row[ID]' class='glyphicon glyphicon-pencil edit'></span></td>";
                                echo "<td style='vertical-align: middle;'><span data='$row[ID]' action='remove' class='glyphicon glyphicon-trash remove'></span></td>";
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

<div id="loading" class="col-xs-12" style="position: absolute; top: 0; left: 0; width: 100%; height: 100vh; text-align: center;">
    <div class="caja" style="margin-top: 35vh; display: inline-block; padding: 25px; background-color: white; border-radius: 10px; box-shadow: 4px 4px 16px 0 #808080bf;">
        <div>
            <img src="resources/img/loading.gif" alt="Cargando...">
            <h2 id="loading-msg"></h2>
        </div>
    </div>
</div>

<script src="js/edit-aulas.js"></script>