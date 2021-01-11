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
                <label>Hora Fichaje Entrada </label>
                <input id='add-hora-entrada' type='time' name='horaentrada' min='06:00' max='22:00' style='margin-left: 2%;' required>
                <br><br>
                <?php if ($options['ficharSalida'] === 1) { ?>
                    <label>Hora Fichaje Salida </label>
                    <input id='add-hora-salida' type='time' name='horasalida' min='06:00' max='22:00' style='margin-left: 3%;' required>
                    <br><br>
                <?php } ?>
                <input id='add-fecha' class='form-control' name='dia' type='text' placeholder='Seleccione una fecha' autocomplete='off' required>
                <br>
                <a  id='remove-manual' href='index.php?ACTION=profesores' class='btn btn-danger float-left'>Cancelar</a>
                <button id='add-manual' action='add' class='btn btn-success float-right'>Fichar</button>
            </form>
        </div>
    </div>
</div>

<script src='js/fichar_manual.js'></script>