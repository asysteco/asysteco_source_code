<?php

$iniciales = $_POST['Iniciales'] ?? '';
$nombre = $_POST['Nombre'] ?? '';

?>
<div class="container">
    <div class="wrapper fadeInDown">
        <div id="formContent">
            <h1>Registrar Profesor o Personal</h1>
            <form action='index.php?ACTION=profesores&OPT=add-profesor' method='post'>
                <input type='text' name='Iniciales' value='<?= $iniciales ?>' class='form-control' placeholder='Iniciales Profesor/Personal'>
                </br>
                <input type='text' name='Nombre' value='<?= $nombre ?>' class='form-control' placeholder='Nombre Profesor/Personal (Completo)'>
                </br>
                <div class="container">
                    </br>
                    <h4>¿Pesonal Docente?</h4>
                    <label style="margin-right: 100px">
                        <input type="radio" name="docente" value="2" checked> Si
                    </label>
                    <label style="margin-left: 100px">
                        <input type="radio" name="docente" value="3"> No
                    </label>
                </div>
                <div style='display: inline'>
                    <button class='btn btn-success float-right' style='margin-right: 33px; margin-bottom: 10px; margin-top: 15px' value='add' name='add-profesor'>Registrar</button>
                    <a href='index.php?ACTION=profesores' class='btn btn-danger float-left' style='margin-left: 33px; margin-top: 15px'>Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>