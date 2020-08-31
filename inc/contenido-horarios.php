<div class="container" style="margin-top:50px">
<?php

if(isset($_GET['ACTION']) && $_GET['OPT'] == 'modificar-horario')
{
    include_once($dirs['inc'] . 'horarios-edit.php');
    include_once "js/update_horario.js";
}
elseif(isset($_GET['ACTION']) && $_GET['OPT'] == 'crear-horario')
{
    include_once($dirs['inc'] . 'crear-horario.php');
}
else
{
    include_once($dirs['inc'] . 'horarios.php');
}

?>
</div>
