<div class="container">
    <div class='row'>
        <div class='col-12'>
            <h1>Gestión de Cursos</h1>
            <div class="input-group mb-3">
                <input id="add-curso" type="text" class="form-control" placeholder="Escribe el nombre del nuevo curso..." aria-label="Escribe el nombre del nuevo curso...">
                <div class="input-group-append">
                    <a id="add-btn" action="add" class="btn btn-success" type="button">Añadir Curso</a>
                </div>
            </div>

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
                if ($response = $class->query("SELECT ID, Nombre FROM Cursos ORDER BY Nombre")) {
                    if ($response->num_rows > 0) {
                        while($row = $response->fetch_assoc()){
                            echo "<tr id='fila_$row[ID]' style='text-align: center;'>";
                                echo "<td style='vertical-align: middle;'>";
                                    echo "<span id='txt_$row[ID]' class='show-it'>$row[Nombre]</span>";
                                    echo "<input id='input_$row[ID]' type='text' class='form-control hide-it' style='width: 30%; display: inline-block; vertical-align: middle;'> ";
                                    echo "<a id='btn_$row[ID]' data='$row[ID]' class='btn btn-success hide-it update' style='vertical-align: middle;'><i class='fa fa-check' aria-hidden='true'></i></a>";
                                echo "</td>";
                                echo "<td style='font-size: 25px; vertical-align: middle;'><span fields='edit_$row[ID]' class='fa fa-pencil-square-o edit'></span></td>";
                                echo "<td style='font-size: 25px; vertical-align: middle;'><span data='$row[ID]' action='remove' class='fa fa-trash-o remove'></span></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr style='text-align: center;'>";
                            echo "<td colspan='100%'><h3>No existen cursos.</h3></td>";
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


<script src="js/edit-cursos.js"></script>