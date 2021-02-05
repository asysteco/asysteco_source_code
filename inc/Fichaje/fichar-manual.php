<div class="container">
    <div class="wrapper fadeInDown">
        <div id="formContent">
            <h1>Fichaje Manual</h1>
            <form action='index.php?ACTION=fichar-mysql-manual' method='POST'>
                <?php
                if ($response = $class->query("SELECT Nombre, ID FROM Profesores WHERE Tipo <> 1 ORDER BY Nombre")) {
                    if (!$response->num_rows > 0) {
                        echo "<h2 style='text-align: center; margin-top: 50px;'>No existen profesores, no se puede fichar manualmente.</h2>";
                        return;
                    }
                    echo "<select class='select_profesor' name='ID' id='fichar-manual' required>";
                        echo "<option value=''>Selecciona un/a profesor/a ...</option>";
                    while ($fila = $response->fetch_assoc()) {
                        echo "<option value='$fila[ID]'>$fila[Nombre]</option>";
                    }
                    echo "</select>";
                } else {
                    $ERR_MSG = $class->ERR_ASYSTECO;
                }
                ?>
                <br><br>
                <label for="add-hora-salida">Hora Fichaje Entrada </label>
                <input id='add-hora-entrada' type="text" name='horaentrada' class="form-control fichajeEntrada" placeholder="Hora entrada" required/>
                <br><br>
                <?php if ($options['ficharSalida'] === 1) { ?>
                    <label for="add-hora-salida">Hora Fichaje Salida </label>
                    <input id='add-hora-salida' type="text" name='horasalida' class="form-control fichajeSalida" placeholder="Hora salida" required/>
                    <br><br>
                <?php } ?>
                <label for="add-fecha">Fecha de fichaje </label>
                <input id='add-fecha' class='form-control' name='dia' type='text' placeholder='Seleccione una fecha' autocomplete='off' required>
                <br>
                <a  id='remove-manual' href='index.php?ACTION=profesores' class='btn btn-danger float-left'>Cancelar</a>
                <button id='add-manual' action='add' class='btn btn-success float-right'>Fichar</button>
            </form>
        </div>
    </div>
</div>

<script src='js/fichar_manual.js'></script>